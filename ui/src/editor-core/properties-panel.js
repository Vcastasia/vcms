// PROPERTIES PANEL Module

const loadingTemplate = require('../templates/loading.hbs');
const propertiesPanel = require('../templates/properties-panel.hbs');
const actionsTemplate = require('../templates/actions-form-template.hbs');
const actionsButtonTemplate = require('../templates/actions-button-template.hbs');

/**
 * Properties panel contructor
 * @param {object} container - the container to render the panel to
 */
let PropertiesPanel = function(parent, container) {

    this.parent = parent;
    this.DOMObject = container;

    // Initialy loaded data on the form
    this.formSerializedLoadData = '';

    this.inlineEditor = false;

    this.openTabOnRender = '';
};

/**
 * Call an action on the element object
 * @param {object} element - the element that the form relates to
 */
PropertiesPanel.prototype.elementAction = function(element, subAction) {
    const app = this.parent;
    const mainElement = app.getElementByTypeAndId(element.type, element.id, element.regionId);
    mainElement[subAction]();
};

/**
 * Save properties from the panel form
 * @param {object} element - the element that the form relates to
 */
PropertiesPanel.prototype.save = function(element) {

    const app = this.parent;

    // If inline editor and viewer exist
    if(this.inlineEditor && (typeof app.viewer != 'undefined')) {
        app.viewer.hideInlineEditor();
    }

    // Run form submit module optional function
    if(element.type === 'widget'){
        formHelpers.widgetFormEditBeforeSubmit(this.DOMObject, element.subType);
    } 

    const form = $(this.DOMObject).find('form');

    // If form is valid, submit it ( add change )
    if(form.valid()) {

        const formNewData = form.serialize();

        app.common.showLoadingScreen();

        // Add a save form change to the history array, with previous form state and the new state
        app.manager.addChange(
            "saveForm",
            element.type, // targetType
            element[element.type + 'Id'], // targetId
            this.formSerializedLoadData, // oldValues
            formNewData, // newValues
            {
                customRequestPath: {
                    url: form.attr('action'),
                    type: form.attr('method')
                }
            }
        ).then((res) => { // Success

            app.common.hideLoadingScreen();

            // Behavior if successful 
            if(typeof app.timeline.resetZoom === "function") {
                // safe to use the function
                app.timeline.resetZoom();
            }

            const resultMessage = res.message;

            const reloadData = function() {
                toastr.success(resultMessage);

                const mainObject = app.getElementByTypeAndId(app.mainObjectType, app.mainObjectId);
                app.reloadData(mainObject);
            };

            // Check if its a drawer widget and if we need to save the target region id
            const $drawerWidgetTargetRegion = this.DOMObject.find('#drawerWidgetTargetRegion');
            if($drawerWidgetTargetRegion.length > 0 && $drawerWidgetTargetRegion.val() != '') {
                const valueToSave = $drawerWidgetTargetRegion.val();

                let requestPath = urlsForApi[element.type].setRegion.url;
                requestPath = requestPath.replace(':id', element[element.type + 'Id']);

                $.ajax({
                    url: requestPath,
                    type: urlsForApi[element.type].setRegion.type,
                    data: {
                        targetRegionId: valueToSave
                    }
                }).done(function(res) {
                    if(res.success) {
                        toastr.success(res.message);
                        reloadData();
                    } else {
                        // Login Form needed?
                        if(res.login) {
                            window.location.href = window.location.href;
                            location.reload(false);
                        } else {
                            toastr.error(errorMessagesTrans.formLoadFailed);
            
                            // Just an error we dont know about
                            if(res.message == undefined) {
                                console.error(res);
                            } else {
                                console.error(res.message);
                            }
                        }
                    }
                }).catch(function(jqXHR, textStatus, errorThrown) {
                    console.error(jqXHR, textStatus, errorThrown);
                    toastr.error(errorMessagesTrans.formLoadFailed);
                });
            } else {
                reloadData();
            }
        }).catch((error) => { // Fail/error

            app.common.hideLoadingScreen();

            // Show error returned or custom message to the user
            let errorMessage = '';

            if(typeof error == 'string') {
                errorMessage += error;
            } else {
                errorMessage += error.errorThrown;
            }
            // Remove added change from the history manager
            app.manager.removeLastChange();

            // Display message in form
            formHelpers.displayErrorMessage(form, errorMessage, 'danger');

            // If Save fails and we have an inline editor opened, reshow it
            if(app.propertiesPanel.inlineEditor) {
                app.viewer.showInlineEditor();
            }

            // Show toast message
            toastr.error(errorMessage);
        });
    }
};

/**
 * Go to the previous form step
 * @param {object} element - the element that the form relates to
 */
PropertiesPanel.prototype.back = function(element) {
    
    // Get current step
    const currentStep = this.DOMObject.find('form').data('formStep');

    // Render previous form
    this.render(element, currentStep - 1);
};

/**
 * Disable all the form inputs and make it read only
 */
PropertiesPanel.prototype.makeFormReadOnly = function() {

    // Disable inputs, select, textarea and buttons
    this.DOMObject.find('input, select, textarea, button:not(.copyTextAreaButton)').attr('disabled', 'disabled');

    // Hide bootstrap switch
    this.DOMObject.find('.bootstrap-switch').hide();
};

/**
 * Render panel
 * @param {Object} element - the element object to be rendered
 */
PropertiesPanel.prototype.render = function(element, step) {
    // Prevent the panel to render if there's no selected object
    if(typeof element == 'undefined' || $.isEmptyObject(element) || typeof element.type == 'undefined' || typeof element[element.type + 'Id'] == 'undefined') {
        // Clean the property panel html
        this.DOMObject.html('');

        return false;
    }

    // Reset inline editor to false on each refresh
    this.inlineEditor = false;

    this.DOMObject.html(loadingTemplate());
    let requestPath = urlsForApi[element.type].getForm.url;

    requestPath = requestPath.replace(':id', element[element.type + 'Id']);

    if(step !== undefined && typeof step == 'number') {
        requestPath += '?step=' + step;
    } 

    // Get form for the given element
    const self = this;

    // If there was still a render request, abort it
    if(this.renderRequest != undefined) {
        this.renderRequest.abort('requestAborted');
    }
    
    // Create a new request
    this.renderRequest = $.get(requestPath).done(function(res) {

        const app = self.parent;

        // Clear request var after response
        self.renderRequest = undefined;

        // Prevent rendering null html
        if(res.html === null || res.success === false) {
            self.DOMObject.html('<div class="unsuccessMessage">' + res.message + '</div>');
            return;
        }

        const htmlTemplate = Handlebars.compile(res.html);

        // Create buttons object
        let buttons = {};
        
        if(app.readOnlyMode === undefined || app.readOnlyMode === false) {
            // Process buttons from result
            buttons = formHelpers.widgetFormRenderButtons(res.buttons);
        }
        
        const html = propertiesPanel({
            header: res.dialogTitle,
            style: element.type,
            form: htmlTemplate(element),
            buttons: buttons,
            trans: propertiesPanelTrans,
            isDrawerWidget: element.drawerWidget || false
        });

        // Append layout html to the main div
        self.DOMObject.html(html);

        if (app.mainObjectType === 'layout') {
            // the url to Action Add Form
            let actionFormAddRequest = urlsForApi[element.type].addActionForm.url;
            actionFormAddRequest = actionFormAddRequest.replace(':id', element[element.type + 'Id']);

            // append new tab
            let tabName = actionsTranslations.tableHeaders.name;
            const tabList = self.DOMObject.find('.nav-tabs');
            let tabHtml = '<li class="nav-item"><a class="nav-link action-tab" href="#actionTab" role="tab" data-toggle="tab"><span id="actionTabName"></span></a></li>';
            $(tabHtml).appendTo(tabList);
            $('#actionTabName').text(tabName);

            // render the html from actions template
            const actionsHtml = actionsTemplate({
                trans: actionsTranslations
            });

            // append Action tab html to tab content in edit form
            const tabContent = self.DOMObject.find('.tab-content');
            $(actionsHtml).appendTo(tabContent);

            // call the javascript to render the datatable when on Actions tab
            showActionsGrid(element.type, element[element.type + 'Id']);

            // add a button to the button panel for adding an action.
            self.DOMObject.find('.button-container').prepend($(actionsButtonTemplate({
                addUrl: actionFormAddRequest,
                trans: actionsTranslations
            })));
        }

        // Store the extra
        self.DOMObject.data("extra", res.extra);

        // Check if there's a viewer element
        const viewerExists = (typeof app.viewer != 'undefined');
        self.DOMObject.data('formEditorOnly', !viewerExists);

        // If the viewer exists, save its data  to the DOMObject
        if(viewerExists) {
            self.DOMObject.data('viewerObject', app.viewer);
        }
        
        // Run form open module optional function
        if(element.type === 'widget') {
            // Pass widget options to the form as data
            if(element.getOptions != undefined) {
                self.DOMObject.find('form').data('elementOptions', element.getOptions());
            }

            formHelpers.widgetFormEditAfterOpen(self.DOMObject, element.subType);
        } else if(element.type === 'region' && typeof window.regionFormEditOpen === 'function') {
            window.regionFormEditOpen.bind(self.DOMObject)();
        }

        // Check for spacing issues on text fields
        self.DOMObject.find('input[type=text]').each(function(index, el) {
            formRenderDetectSpacingIssues(el);

            $(el).on("keyup", _.debounce(function() {
                formRenderDetectSpacingIssues(el);
            }, 500));
        });

        // Save form data
        self.formSerializedLoadData = self.DOMObject.find('form').serialize();

        // Handle buttons click
        if(app.readOnlyMode === undefined || app.readOnlyMode === false) {
            self.DOMObject.find('.properties-panel-btn').click(function(el) {
                if($(this).data('action')) {
                    self[$(this).data('action')](element, $(this).data('subAction'));
                }  
            });

            // Handle back button based on form page
            if(self.DOMObject.find('form').data('formStep') != undefined && self.DOMObject.find('form').data('formStep') > 1) {
                self.DOMObject.find('button#back').show();
            } else {
                self.DOMObject.find('button#back').hide();
            }

            // Handle keyboard keys
            self.DOMObject.off('keydown').keydown(function(handler) {
                if(handler.key == 'Enter' && !$(handler.target).is('textarea')) {
                    self.save(element, $(this).data('subAction'));
                }
            });
        }

        // Call Xibo Init for this form
        XiboInitialise("#" + self.DOMObject.attr("id"));

        // For the layout properties, call background Setup
        if(element.type == 'layout') {
            backGroundFormSetup(self.DOMObject);
        }

        if(app.readOnlyMode != undefined && app.readOnlyMode === true) {
            self.makeFormReadOnly();
        }

        if(self.openTabOnRender != '') {
            // Open tab
            self.DOMObject.find(self.openTabOnRender).tab('show');

            // Reset flag
            self.openTabOnRender = '';
        }

        // Populate the drawer select if exists
        if($('.form-editor-controls-target-region').length > 0) {
            const $selectOptionContainer = self.DOMObject.find('.form-editor-controls-target-region #drawerWidgetTargetRegion');

            // Clear container
            $selectOptionContainer.find('option:not(.default-option)').remove();
            const elementTargetRegion = element.targetRegionId || '';
            for(regionID in lD.layout.regions) {
                const region = lD.layout.regions[regionID];
                let $newOption = $('<option value="' + region.regionId + '">' + region.name + '</option>');
                if(elementTargetRegion == region.regionId) {
                    $newOption.attr('selected', 'selected');
                }
                $newOption.appendTo($selectOptionContainer);
            }
        }

        // Toggler
        self.DOMObject.parents('.toggle-panel').find('.toggle').off().click(function(e) {
            e.stopPropagation();
            $(this).parents('.toggle-panel').toggleClass('opened');

            // Refresh navigator or viewer
            if (lD.navigatorMode) {
                lD.renderContainer(lD.navigator);
            } else {
                lD.renderContainer(lD.viewer, lD.selectedObject);
            }
        });
    }).fail(function(data) {

        // Clear request var after response
        self.renderRequest = undefined;

        if(data.statusText != 'requestAborted') {
            toastr.error(errorMessagesTrans.getFormFailed, errorMessagesTrans.error);
        }
    });

};

module.exports = PropertiesPanel;
