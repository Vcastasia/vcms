describe('Layout Designer (Populated/Unchanged)', function() {

    before(function() {
        // Import existing
        cy.importLayout('../assets/export_test_layout.zip').as('testLayoutId').then((res) => {
            cy.checkoutLayout(res);
        });
    });

    /* Disabled for testing speed reasons
        after(function() {
            // Remove the created layout
            cy.deleteLayout(this.testLayoutId);
        });
    */

    beforeEach(function() {
        cy.login();
        cy.goToLayoutAndLoadPrefs(this.testLayoutId);
    });

    it('should load all the layout designer elements', function() {

        // Check if the basic elements of the designer loaded
        cy.get('#layout-editor').should('be.visible');
        cy.get('#layout-timeline').should('be.visible');
        cy.get('#layout-viewer-container').should('be.visible');
        cy.get('#properties-panel').should('be.visible');
    });

    it('shows widget properties in the properties panel when clicking on a widget in the timeline', function() {

        // Select the first widget from the first region on timeline ( image )
        cy.get('#layout-timeline .designer-region:first [data-type="widget"]:first-child').click();

        // Check if the properties panel title is Edit Image
        cy.get('#properties-panel').contains('Edit Image');
    });

    it.skip('should revert a saved form to a previous state', () => {
        let oldName;

        // Create and alias for reload widget
        cy.server();
        cy.route('/playlist/widget/form/edit/*').as('reloadWidget');
        cy.route('PUT', '/playlist/widget/*').as('saveWidget');

        // Select the first widget on timeline ( image )
        cy.get('#layout-timeline .designer-region:first [data-type="widget"]:first-child').click();

        // Wait for the widget to load
        cy.wait('@reloadWidget');

        // Get the input field
        cy.get('#properties-panel input[name="name"]').then(($input) => {

            // Save old name
            oldName = $input.val();

            //Type the new name in the input
            cy.get('#properties-panel input[name="name"]').clear().type('newName');

            // Save form
            cy.get('#properties-panel button[data-action="save"]').click();

            // Should show a notification for the name change
            cy.get('.toast-success');

            // Wait for the widget to save
            cy.wait('@reloadWidget');

            // Click the revert button
            cy.get('#layout-editor-toolbar #undoContainer').click();

            // Wait for the widget to save
            cy.wait('@saveWidget');

            // Test if the revert made the name go back to the old name
            cy.get('#properties-panel input[name="name"]').should('have.attr', 'value').and('equal', oldName);
        });
    });

    it.skip('shows the file upload form by using the Add button on a card with uploadable media from the toolbar to layout-timeline region', () => {

        cy.populateLibraryWithMedia();

        // Open toolbar Widgets tab
        cy.get('#layout-editor-toolbar #btn-menu-0').should('be.visible').click({force:true});
        cy.get('#layout-editor-toolbar #btn-menu-1').should('be.visible').click({force:true});

        // Activate the Add button
        cy.get('#layout-editor-toolbar #content-2 .toolbar-pane-content [data-sub-type="audio"] .add-area').invoke('show').click({force:true});

        // Click on the region to add
        cy.get('#layout-timeline .designer-region:first').click();

        // Check if the form opened
        cy.get('[data-test="uploadFormModal"]').contains('Upload media');
    });


    it.skip('should revert the widgets order when using the undo feature', () => {
        cy.server();
        cy.route('POST', '**/playlist/order/*').as('saveOrder');
        cy.route('/layout?layoutId=*').as('reloadLayout');

        cy.get('#layout-timeline .designer-region:first [data-type="widget"]:first-child').then(($oldWidget) => {

            const offsetX = 50;

            // Move to the second widget position ( plus offset )
            cy.wrap($oldWidget)
                .trigger('mousedown', {
                    which: 1
                })
                .trigger('mousemove', {
                    which: 1,
                    pageX: $oldWidget.offset().left + $oldWidget.width() * 1.5 + offsetX
                })
                .trigger('mouseup', {force: true});

            cy.wait('@saveOrder');

            // Should show a notification for the order change
            cy.get('.toast-success').contains('Order Changed');

            // Reload layout and check if the new first widget has a different Id
            cy.wait('@reloadLayout');

            cy.get('#layout-timeline .designer-region:first [data-type="widget"]:first-child').then(($newWidget) => {
                expect($oldWidget.attr('id')).not.to.eq($newWidget.attr('id'));
            });

            // Click the revert button
            cy.get('#layout-editor-toolbar #undoContainer').click();

            // Wait for the order to save
            cy.wait('@saveOrder');
            cy.wait('@reloadLayout');

            // Test if the revert made the name go back to the first widget
            cy.get('#layout-timeline .designer-region:first [data-type="widget"]:first-child').then(($newWidget) => {
                expect($oldWidget.attr('id')).to.eq($newWidget.attr('id'));
            });
        });
    });

    it.skip('should play a preview in the viewer, in fullscreen mode', () => {

        // Click fullscreen button
        cy.get('#main-panel #fullscreenBtn').click();

        // Viewer should have a fullscreen class, and click play
        cy.get('#layout-viewer-container.fullscreen #layout-viewer-navbar #play-btn').click();


        // Check if the fullscreen iframe has a scr for preview layout
        cy.get('#layout-viewer-container.fullscreen #layout-viewer iframe').should('have.attr', 'src').and('include', '/layout/preview/');
    });
});