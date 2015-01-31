INSERT INTO `version` (`app_ver`, `XmdsVersion`, `XlfVersion`, `DBVersion`) VALUES
('1.7.0', 4, 2, 84);

INSERT INTO `group` (`groupID`, `group`, `IsUserSpecific`, `IsEveryone`) VALUES
(1, 'Users', 0, 0),
(2, 'Everyone', 0, 1),
(3, 'xibo_admin', 1, 0);

INSERT INTO `help` (`HelpID`, `Topic`, `Category`, `Link`) VALUES
(1, 'Layout', 'General', 'layouts.html'),
(2, 'Content', 'General', 'media.html'),
(4, 'Schedule', 'General', 'scheduling.html'),
(5, 'Group', 'General', 'users_groups.html'),
(6, 'Admin', 'General', 'cms_settings.html'),
(7, 'Report', 'General', 'troubleshooting.html'),
(8, 'Dashboard', 'General', 'tour.html'),
(9, 'User', 'General', 'users.html'),
(10, 'Display', 'General', 'displays.html'),
(11, 'DisplayGroup', 'General', 'displays_groups.html'),
(12, 'Layout', 'Add', 'layouts.html#Add_Layout'),
(13, 'Layout', 'Background', 'layouts_designer.html#Background'),
(14, 'Content', 'Assign', 'layouts_playlists.html#Assigning_Content'),
(15, 'Layout', 'RegionOptions', 'layouts_regions.html'),
(16, 'Content', 'AddtoLibrary', 'media_library.html'),
(17, 'Display', 'Edit', 'displays.html#Display_Edit'),
(18, 'Display', 'Delete', 'displays.html#Display_Delete'),
(19, 'Displays', 'Groups', 'displays_groups.html#Group_Members'),
(20, 'UserGroup', 'Add', 'users_groups.html#Adding_Group'),
(21, 'User', 'Add', 'users_administration.html#Add_User'),
(22, 'User', 'Delete', 'users_administration.html#Delete_User'),
(23, 'Content', 'Config', 'cms_settings.html#Content'),
(24, 'LayoutMedia', 'Permissions', 'users_permissions.html'),
(25, 'Region', 'Permissions', 'users_permissions.html'),
(26, 'Library', 'Assign', 'layouts_playlists.html#Add_From_Library'),
(27, 'Media', 'Delete', 'media_library.html#Delete'),
(28, 'DisplayGroup', 'Add', 'displays_groups.html#Add_Group'),
(29, 'DisplayGroup', 'Edit', 'displays_groups.html#Edit_Group'),
(30, 'DisplayGroup', 'Delete', 'displays_groups.html#Delete_Group'),
(31, 'DisplayGroup', 'Members', 'displays_groups.html#Group_Members'),
(32, 'DisplayGroup', 'Permissions', 'users_permissions.html'),
(34, 'Schedule', 'ScheduleNow', 'scheduling_now.html'),
(35, 'Layout', 'Delete', 'layouts.html#Delete_Layout'),
(36, 'Layout', 'Copy', 'layouts.html#Copy_Layout'),
(37, 'Schedule', 'Edit', 'scheduling_events.html#Edit'),
(38, 'Schedule', 'Add', 'scheduling_events.html#Add'),
(39, 'Layout', 'Permissions', 'users_permissions.html'),
(40, 'Display', 'MediaInventory', 'displays.html#Media_Inventory'),
(41, 'User', 'ChangePassword', 'users.html#Change_Password'),
(42, 'Schedule', 'Delete', 'scheduling_events.html'),
(43, 'Layout', 'Edit', 'layouts_designer.html#Edit_Layout'),
(44, 'Media', 'Permissions', 'users_permissions.html'),
(45, 'Display', 'DefaultLayout', 'displays.html#DefaultLayout'),
(46, 'UserGroup', 'Edit', 'users_groups.html#Edit_Group'),
(47, 'UserGroup', 'Members', 'users_groups.html#Group_Member'),
(48, 'User', 'PageSecurity', 'users_permissions.html#Page_Security'),
(49, 'User', 'MenuSecurity', 'users_permissions.html#Menu_Security'),
(50, 'UserGroup', 'Delete', 'users_groups.html#Delete_Group'),
(51, 'User', 'Edit', 'users_administration.html#Edit_User'),
(52, 'User', 'Applications', 'users_administration.html#Users_MyApplications'),
(53, 'User', 'SetHomepage', 'users_administration.html#Media_Dashboard'),
(54, 'DataSet', 'General', 'media_datasets.html'),
(55, 'DataSet', 'Add', 'media_datasets.html#Create_Dataset'),
(56, 'DataSet', 'Edit', 'media_datasets.html#Edit_Dataset'),
(57, 'DataSet', 'Delete', 'media_datasets.html#Delete_Dataset'),
(58, 'DataSet', 'AddColumn', 'media_datasets.html#Dataset_Column'),
(59, 'DataSet', 'EditColumn', 'media_datasets.html#Dataset_Column'),
(60, 'DataSet', 'DeleteColumn', 'media_datasets.html#Dataset_Column'),
(61, 'DataSet', 'Data', 'media_datasets.html#Dataset_Row'),
(62, 'DataSet', 'Permissions', 'users_permissions.html'),
(63, 'Fault', 'General', 'troubleshooting.html#Report_Fault'),
(65, 'Stats', 'General', 'displays_metrics.html'),
(66, 'Resolution', 'General', 'layouts_resolutions.html'),
(67, 'Template', 'General', 'layouts_templates.html'),
(68, 'Services', 'Register', '#Registered_Applications'),
(69, 'OAuth', 'General', 'api_oauth.html'),
(70, 'Services', 'Log', 'api_oauth.html#oAuthLog'),
(71, 'Module', 'Edit', 'media_modules.html'),
(72, 'Module', 'General', 'media_modules.html'),
(73, 'Campaign', 'General', 'layouts_campaigns.html'),
(74, 'License', 'General', 'licence_information.html'),
(75, 'DataSet', 'ViewColumns', 'media_datasets.html#Dataset_Column'),
(76, 'Campaign', 'Permissions', 'users_permissions.html'),
(77, 'Transition', 'Edit', 'layouts_transitions.html'),
(78, 'User', 'SetPassword', 'users_administration.html#Set_Password'),
(79, 'DataSet', 'ImportCSV', 'media_datasets.htmlmedia_datasets.html#Import_CSV'),
(80, 'DisplayGroup', 'FileAssociations', 'displays_fileassociations.html'),
(81, 'Statusdashboard', 'General', 'tour_status_dashboard.html'),
(82, 'Displayprofile', 'General', 'displays_settings.html'),
(83, 'DisplayProfile', 'Edit', 'displays_settings.html#edit'),
(84, 'DisplayProfile', 'Delete', 'displays_settings.html#delete');

INSERT INTO `menu` (`MenuID`, `Menu`) VALUES
(8, 'Administration Menu'),
(9, 'Advanced Menu'),
(2, 'Dashboard'),
(6, 'Design Menu'),
(7, 'Display Menu'),
(5, 'Library Menu'),
(1, 'Top Nav');

INSERT INTO `module` (`ModuleID`, `Module`, `Name`, `Enabled`, `RegionSpecific`, `Description`, `ImageUri`, `SchemaVersion`, `ValidExtensions`, `PreviewEnabled`, `assignable`, `render_as`, `settings`) VALUES
(1, 'Image', 'Image', 1, 0, 'Images. PNG, JPG, BMP, GIF', 'forms/image.gif', 1, 'jpg,jpeg,png,bmp,gif', 1, 1, NULL, NULL),
(2, 'Video', 'Video', 1, 0, 'Videos - support varies depending on the client hardware you are using.', 'forms/video.gif', 1, 'wmv,avi,mpg,mpeg,webm,mp4', 1, 1, NULL, NULL),
(3, 'Flash', 'Flash', 1, 0, 'Flash', 'forms/flash.gif', 1, 'swf', 1, 1, NULL, NULL),
(4, 'PowerPoint', 'PowerPoint', 1, 0, 'Powerpoint. PPT, PPS', 'forms/powerpoint.gif', 1, 'ppt,pps,pptx', 1, 1, NULL, NULL),
(5, 'Webpage', 'Webpage', 1, 1, 'Webpages.', 'forms/webpage.gif', 1, NULL, 1, 1, NULL, NULL),
(6, 'Ticker', 'Ticker', 1, 1, 'RSS Ticker.', 'forms/ticker.gif', 1, NULL, 1, 1, NULL, '[]'),
(7, 'Text', 'Text', 1, 1, 'Text. With Directional Controls.', 'forms/text.gif', 1, NULL, 1, 1, NULL, NULL),
(8, 'Embedded', 'Embedded', 1, 1, 'Embedded HTML', 'forms/webpage.gif', 1, NULL, 1, 1, NULL, NULL),
(10, 'Counter', 'Counter', 0, 1, 'Customer Counter connected to a Remote Control', 'forms/counter.gif', 1, NULL, 1, 1, NULL, NULL),
(11, 'datasetview', 'Data Set', 1, 1, 'A view on a DataSet', 'forms/datasetview.gif', 1, NULL, 1, 1, NULL, NULL),
(12, 'shellcommand', 'Shell Command', 1, 1, 'Execute a shell command on the client', 'forms/shellcommand.gif', 1, NULL, 1, 1, NULL, NULL),
(13, 'localvideo', 'Local Video', 1, 1, 'Play a video locally stored on the client', 'forms/video.gif', 1, NULL, 1, 1, NULL, NULL),
(14, 'genericfile', 'Generic File', 1, 0, 'A generic file to be stored in the library', 'forms/library.gif', 1, 'apk,js,html,htm', 0, 0, NULL, NULL),
(15, 'clock', 'Clock', 1, 1, 'Display a Clock', 'forms/library.gif', 1, NULL, 1, 1, 'html', '[]'),
(16, 'font', 'Font', 1, 0, 'A font to use in other Modules', 'forms/library.gif', 1, 'ttf,otf,eot,svg,woff', 0, 0, NULL, NULL);

INSERT INTO `pagegroup` (`pagegroupID`, `pagegroup`) VALUES
(1, 'Schedule'),
(2, 'Homepage and Login'),
(3, 'Layouts'),
(4, 'Content'),
(7, 'Displays'),
(8, 'Users and Groups'),
(9, 'Reports'),
(10, 'License and Settings'),
(11, 'Updates'),
(12, 'Templates'),
(13, 'Web Services'),
(14, 'DataSets');

INSERT INTO `pages` (`pageID`, `name`, `pagegroupID`) VALUES
(1, 'dashboard', 2),
(2, 'schedule', 1),
(3, 'mediamanager', 2),
(5, 'layout', 3),
(7, 'content', 4),
(11, 'display', 7),
(12, 'update', 11),
(14, 'admin', 10),
(15, 'group', 8),
(16, 'log', 9),
(17, 'user', 8),
(18, 'license', 10),
(19, 'index', 2),
(24, 'module', 4),
(25, 'template', 3),
(26, 'fault', 10),
(27, 'stats', 9),
(28, 'manual', 2),
(29, 'resolution', 12),
(30, 'help', 2),
(31, 'clock', 2),
(32, 'displaygroup', 7),
(33, 'oauth', 13),
(34, 'help', 2),
(35, 'clock', 2),
(36, 'dataset', 14),
(37, 'campaign', 3),
(38, 'transition', 4),
(39, 'timeline', 3),
(40, 'sessions', 9),
(41, 'preview', 3),
(42, 'statusdashboard', 2),
(43, 'displayprofile', 7);

INSERT INTO `menuitem` (`MenuItemID`, `MenuID`, `PageID`, `Args`, `Text`, `Class`, `Img`, `Sequence`, `External`) VALUES
(1, 1, 2, NULL, 'Schedule', NULL, NULL, 1, 0),
(2, 1, 5, NULL, 'Design', NULL, NULL, 2, 0),
(3, 1, 7, NULL, 'Library', NULL, NULL, 3, 0),
(4, 1, 17, NULL, 'Administration', NULL, NULL, 5, 0),
(7, 7, 11, NULL, 'Displays', NULL, NULL, 1, 0),
(8, 8, 15, NULL, 'User Groups', NULL, NULL, 2, 0),
(9, 8, 17, NULL, 'Users', NULL, NULL, 1, 0),
(10, 9, 16, NULL, 'Log', NULL, NULL, 1, 0),
(11, 9, 18, NULL, 'About', NULL, NULL, 4, 0),
(12, 9, 40, NULL, 'Sessions', NULL, NULL, 2, 0),
(13, 8, 14, NULL, 'Settings', NULL, NULL, 3, 0),
(14, 2, 2, 'sp=month', 'Schedule', 'schedule_button', 'dashboard/scheduleview.png', 1, 0),
(15, 2, 5, NULL, 'Layouts', 'playlist_button', 'dashboard/presentations.png', 2, 0),
(16, 2, 7, NULL, 'Library', 'content_button', 'dashboard/content.png', 3, 0),
(17, 2, 25, NULL, 'Templates', 'layout_button', 'dashboard/layouts.png', 4, 0),
(18, 2, 17, NULL, 'Users', 'user_button', 'dashboard/users.png', 5, 0),
(19, 2, 14, NULL, 'Settings', 'settings_button', 'dashboard/settings.png', 6, 0),
(20, 2, 18, NULL, 'About', 'license_button', 'dashboard/license.png', 7, 0),
(22, 9, 26, NULL, 'Report Fault', NULL, NULL, 3, 0),
(23, 7, 27, NULL, 'Statistics', NULL, NULL, 3, 0),
(24, 2, 28, 'manual/index.php', 'Manual', 'help_button', 'dashboard/help.png', 10, 1),
(25, 6, 29, NULL, 'Resolutions', NULL, NULL, 4, 0),
(26, 6, 25, NULL, 'Templates', NULL, NULL, 3, 0),
(27, 7, 32, NULL, 'Display Groups', NULL, NULL, 2, 0),
(28, 8, 33, NULL, 'Applications', NULL, NULL, 4, 0),
(29, 5, 36, NULL, 'DataSets', NULL, NULL, 2, 0),
(30, 5, 7, NULL, 'Media', NULL, NULL, 1, 0),
(33, 6, 5, NULL, 'Layouts', NULL, NULL, 2, 0),
(34, 1, 11, NULL, 'Displays', NULL, NULL, 4, 0),
(35, 1, 16, NULL, 'Advanced', NULL, NULL, 6, 0),
(36, 8, 24, NULL, 'Modules', NULL, NULL, 5, 0),
(37, 6, 37, NULL, 'Campaigns', NULL, NULL, 1, 0),
(38, 8, 38, NULL, 'Transitions', NULL, NULL, 6, 0),
(39, 9, 30, NULL, 'Help Links', NULL, NULL, 6, 0),
(40, 7, 43, NULL, 'Display Settings', NULL, NULL, 4, 0);


INSERT INTO `resolution` (`resolutionID`, `resolution`, `width`, `height`, `intended_width`, `intended_height`, `version`, `enabled`) VALUES
(9, '1080p HD Landscape', 800, 450, 1920, 1080, 2, 1),
(10, '720p HD Landscape', 800, 450, 1280, 720, 2, 1),
(11, '1080p HD Portrait', 450, 800, 1080, 1920, 2, 1),
(12, '720p HD Portrait', 450, 800, 720, 1280, 2, 1),
(13, '4k', 800, 450, 4096, 2304, 2, 1),
(14, 'Common PC Monitor 4:3', 800, 600, 1024, 768, 2, 1);

INSERT INTO `setting` (`settingid`, `setting`, `value`, `fieldType`, `helptext`, `options`, `cat`, `userChange`, `title`, `validation`, `ordering`, `default`, `userSee`, `type`) VALUES
(1, 'MEDIA_DEFAULT', 'private', 'dropdown', 'Media will be created with these settings. If public everyone will be able to view and use this media.', 'private|public', 'permissions', 1, 'Media Permissions', '', 20, 'private', 1, 'word'),
(2, 'LAYOUT_DEFAULT', 'private', 'dropdown', 'New layouts will be created with these settings. If public everyone will be able to view and use this layout.', 'private|public', 'permissions', 1, 'Layout Permissions', '', 10, 'private', 1, 'word'),
(3, 'defaultUsertype', 'User', 'dropdown', 'Sets the default user type selected when creating a user.\r\n<br />\r\nWe recommend that this is set to "User"', 'User|Group Admin|Super Admin', 'users', 1, 'Default User Type', '', 10, 'User', 1, 'string'),
(7, 'userModule', 'module_user_general.php', 'dirselect', 'This sets which user authentication module is currently being used.', NULL, 'users', 0, 'User Module', '', 0, 'module_user_general.php', 0, 'string'),
(11, 'defaultTimezone', 'Europe/London', 'timezone', 'Set the default timezone for the application', 'Europe/London', 'regional', 1, 'Timezone', '', 20, 'Europe/London', 1, 'string'),
(18, 'mail_to', 'mail@yoursite.com', 'email', 'Errors will be mailed here', NULL, 'maintenance', 1, 'Admin email address', '', 30, 'mail@yoursite.com', 1, 'string'),
(19, 'mail_from', 'mail@yoursite.com', 'email', 'Mail will be sent from this address', NULL, 'maintenance', 1, 'Sending email address', '', 40, 'mail@yoursite.com', 1, 'string'),
(23, 'jpg_length', '10', 'number', 'Default length for JPG files (in seconds)', NULL, 'content', 1, 'Default Image Duration', '', 30, '10', 1, 'int'),
(26, 'ppt_length', '10', 'number', 'Default length for PPT files (in seconds)', NULL, 'content', 1, 'Default PowerPoint Duration', '', 10, '10', 1, 'int'),
(29, 'swf_length', '10', 'number', 'Default length for SWF files', NULL, 'content', 1, 'Default Flash Duration', '', 20, '10', 1, 'int'),
(30, 'audit', 'error', 'dropdown', 'Set the level of logging the CMS should record. In production systems "error" is recommended.', 'error|info|audit|off', 'troubleshooting', 1, 'Log Level', '', 20, 'error', 1, 'word'),
(33, 'LIBRARY_LOCATION', '', 'text', 'The fully qualified path to the CMS library location.', NULL, 'configuration', 1, 'Library Location', 'required', 10, '', 1, 'string'),
(34, 'SERVER_KEY', '', 'text', NULL, NULL, 'configuration', 1, 'CMS Secret Key', 'required', 20, '', 1, 'string'),
(35, 'HELP_BASE', 'http://www.xibo.org.uk/manual/', 'text', NULL, NULL, 'general', 1, 'Location of the Manual', 'required', 10, 'http://www.xibo.org.uk/manual/', 1, 'string'),
(36, 'PHONE_HOME', 'On', 'dropdown', 'Should the server send anonymous statistics back to the Xibo project?', 'On|Off', 'general', 1, 'Allow usage tracking?', '', 10, 'On', 1, 'word'),
(37, 'PHONE_HOME_KEY', '', 'text', 'Key used to distinguish each Xibo instance. This is generated randomly based on the time you first installed Xibo, and is completely untraceable.', NULL, 'general', 0, 'Phone home key', '', 20, '', 0, 'string'),
(38, 'PHONE_HOME_URL', 'http://www.xibo.org.uk/stats/track.php', 'text', 'The URL to connect to to PHONE_HOME (if enabled)', NULL, 'network', 0, 'Phone home URL', '', 60, 'http://www.xibo.org.uk/stats/track.php', 0, 'string'),
(39, 'PHONE_HOME_DATE', '0', 'text', 'The last time we PHONED_HOME in seconds since the epoch', NULL, 'general', 0, 'Phone home time', '', 30, '0', 0, 'int'),
(40, 'SERVER_MODE', 'Production', 'dropdown', 'This should only be set if you want to display the maximum allowed error messaging through the user interface. <br /> Useful for capturing critical php errors and environment issues.', 'Production|Test', 'troubleshooting', 1, 'Server Mode', '', 30, 'Production', 1, 'word'),
(41, 'MAINTENANCE_ENABLED', 'Off', 'dropdown', 'Allow the maintenance script to run if it is called?', 'Protected|On|Off', 'maintenance', 1, 'Enable Maintenance?', '', 10, 'Off', 1, 'word'),
(42, 'MAINTENANCE_EMAIL_ALERTS', 'On', 'dropdown', 'Global switch for email alerts to be sent', 'On|Off', 'maintenance', 1, 'Enable Email Alerts?', '', 20, 'On', 1, 'word'),
(43, 'MAINTENANCE_KEY', 'changeme', 'text', 'String appended to the maintenance script to prevent malicious calls to the script.', NULL, 'maintenance', 1, 'Maintenance Key', '', 50, 'changeme', 1, 'string'),
(44, 'MAINTENANCE_LOG_MAXAGE', '30', 'number', 'Maximum age for log entries. Set to 0 to keep logs indefinitely.', NULL, 'maintenance', 1, 'Max Log Age', '', 60, '30', 1, 'int'),
(45, 'MAINTENANCE_STAT_MAXAGE', '30', 'number', 'Maximum age for statistics entries. Set to 0 to keep statistics indefinitely.', NULL, 'maintenance', 1, 'Max Statistics Age', '', 70, '30', 1, 'int'),
(46, 'MAINTENANCE_ALERT_TOUT', '12', 'number', 'How long in minutes after the last time a client connects should we send an alert? Can be overridden on a per client basis.', NULL, 'maintenance', 1, 'Max Display Timeout', '', 80, '12', 1, 'int'),
(47, 'SHOW_DISPLAY_AS_VNCLINK', '0', 'text', 'Turn the display name in display management into a VNC link using the IP address last collected. The %s is replaced with the IP address. Leave blank to disable.', NULL, 'displays', 1, 'Display a VNC Link?', '', 30, '', 1, 'string'),
(48, 'SHOW_DISPLAY_AS_VNC_TGT', '_top', 'text', 'If the display name is shown as a link in display management, what target should the link have? Set _top to open the link in the same window or _blank to open in a new window.', NULL, 'displays', 1, 'Open VNC Link in new window?', '', 40, '_top', 1, 'string'),
(49, 'MAINTENANCE_ALWAYS_ALERT', 'Off', 'dropdown', 'Should Xibo send an email if a display is in an error state every time the maintenance script runs?', 'On|Off', 'maintenance', 1, 'Send repeat Display Timeouts', '', 80, 'Off', 1, 'word'),
(50, 'SCHEDULE_LOOKAHEAD', 'On', 'dropdown', 'Should Xibo send future schedule information to clients?', 'On|Off', 'general', 0, 'Send Schedule in advance?', '', 40, 'On', 1, 'word'),
(51, 'REQUIRED_FILES_LOOKAHEAD', '172800', 'number', 'How many seconds in to the future should the calls to RequiredFiles look?', NULL, 'general', 1, 'Send files in advance?', '', 50, '172800', 1, 'int'),
(52, 'REGION_OPTIONS_COLOURING', 'Media Colouring', 'dropdown', NULL, 'Media Colouring|Permissions Colouring', 'permissions', 1, 'How to colour Media on the Region Timeline', '', 30, 'Media Colouring', 1, 'string'),
(53, 'LAYOUT_COPY_MEDIA_CHECKB', 'Unchecked', 'dropdown', 'Default the checkbox for making duplicates of media when copying layouts', 'Checked|Unchecked', 'defaults', 1, 'Default copy media when copying a layout?', '', 20, 'Unchecked', 1, 'word'),
(54, 'MAX_LICENSED_DISPLAYS', '0', 'number', 'The maximum number of licensed clients for this server installation. 0 = unlimited', NULL, 'displays', 0, 'Number of display slots', '', 50, '0', 0, 'int'),
(55, 'LIBRARY_MEDIA_UPDATEINALL_CHECKB', 'Unchecked', 'dropdown', 'Default the checkbox for updating media on all layouts when editing in the library', 'Checked|Unchecked', 'defaults', 1, 'Default update media in all layouts', '', 10, 'Unchecked', 1, 'word'),
(56, 'USER_PASSWORD_POLICY', '', 'text', 'Regular Expression for password complexity, leave blank for no policy.', '', 'users', 1, 'Password Policy Regular Expression', '', 20, '', 1, 'string'),
(57, 'USER_PASSWORD_ERROR', '', 'text', 'A text description of this password policy. Will be show to users when their password does not meet the required policy', '', 'users', 1, 'Description of Password Policy', '', 30, '', 1, 'string'),
(58, 'MODULE_CONFIG_LOCKED_CHECKB', 'Unchecked', 'dropdown', 'Is the module config locked? Useful for Service providers.', 'Checked|Unchecked', 'defaults', 0, 'Lock Module Config', '', 30, 'Unchecked', 0, 'word'),
(59, 'LIBRARY_SIZE_LIMIT_KB', '0', 'number', 'The Limit for the Library Size in KB', NULL, 'network', 0, 'Library Size Limit', '', 50, '0', 1, 'int'),
(60, 'MONTHLY_XMDS_TRANSFER_LIMIT_KB', '0', 'number', 'XMDS Transfer Limit in KB/month', NULL, 'network', 0, 'Monthly bandwidth Limit', '', 40, '0', 1, 'int'),
(61, 'DEFAULT_LANGUAGE', 'en_GB', 'text', 'The default language to use', NULL, 'regional', 1, 'Default Language', '', 10, 'en_GB', 1, 'string'),
(62, 'TRANSITION_CONFIG_LOCKED_CHECKB', 'Unchecked', 'dropdown', 'Is the Transition config locked?', 'Checked|Unchecked', 'defaults', 0, 'Allow modifications to the transition configuration?', '', 40, 'Unchecked', 1, 'word'),
(63, 'GLOBAL_THEME_NAME', 'default', 'text', 'The Theme to apply to all pages by default', NULL, 'configuration', 1, 'CMS Theme', '', 30, 'default', 1, 'word'),
(64, 'DEFAULT_LAT', '51.504', 'number', 'The Latitude to apply for any Geo aware Previews', NULL, 'displays', 1, 'Default Latitude', '', 10, '51.504', 1, 'double'),
(65, 'DEFAULT_LONG', '-0.104', 'number', 'The Longitude to apply for any Geo aware Previews', NULL, 'displays', 1, 'Default Longitude', '', 20, '-0.104', 1, 'double'),
(66, 'SCHEDULE_WITH_VIEW_PERMISSION', 'No', 'dropdown', 'Should users with View permissions on displays be allowed to schedule to them?', 'Yes|No', 'permissions', 1, 'Schedule with view permissions?', '', 40, 'No', 1, 'word'),
(67, 'SETTING_IMPORT_ENABLED', '1', 'checkbox', NULL, NULL, 'general', 1, 'Allow Import?', '', 80, '1', 1, 'checkbox'),
(68, 'SETTING_LIBRARY_TIDY_ENABLED', '1', 'checkbox', NULL, NULL, 'general', 1, 'Enable Library Tidy?', '', 90, '1', 1, 'checkbox'),
(69, 'SENDFILE_MODE', 'Off', 'dropdown', 'When a user downloads a file from the library or previews a layout, should we attempt to use Apache X-Sendfile, Nginx X-Accel, or PHP (Off) to return the file from the library?', 'Off|Apache|Nginx', 'general', 1, 'File download mode', '', 60, 'Off', 1, 'word'),
(70, 'EMBEDDED_STATUS_WIDGET', '', 'text', 'HTML to embed in an iframe on the Status Dashboard', NULL, 'general', 0, 'Status Dashboard Widget', '', 70, '', 1, 'htmlstring'),
(71, 'PROXY_HOST', '', 'text', 'The Proxy URL', NULL, 'network', 1, 'Proxy URL', '', 10, '', 1, 'string'),
(72, 'PROXY_PORT', '0', 'number', 'The Proxy Port', NULL, 'network', 1, 'Proxy Port', '', 20, '0', 1, 'int'),
(73, 'PROXY_AUTH', '', 'text', 'The Authentication information for this proxy. username:password', NULL, 'network', 1, 'Proxy Credentials', '', 30, '', 1, 'string'),
(74, 'DATE_FORMAT',  'Y-m-d H:i',  'text',  'The Date Format to use when displaying dates in the CMS.', NULL ,  'regional',  '1',  'Date Format',  'required',  30,  'Y-m-d',  '1',  'string'),
(75, 'DETECT_LANGUAGE',  '1',  'checkbox',  'Detect the browser language?', NULL ,  'regional',  '1',  'Detect Language',  '',  40,  '1',  1,  'checkbox'),
(76, 'DEFAULTS_IMPORTED', '0', 'text', 'Has the default layout been imported?', NULL, 'general', 0, 'Defaults Imported?', 'required', 100, '0', 0, 'checkbox'),
(77, 'FORCE_HTTPS', '0', 'checkbox', 'Force the portal into HTTPS?', NULL, 'network', 1, 'Force HTTPS?', '', 70, '0', 1, 'checkbox'),
(78, 'ISSUE_STS', '0', 'checkbox', 'Add STS to the response headers? Make sure you fully understand STS before turning it on as it will prevent access via HTTP after the first successful HTTPS connection.', NULL, 'network', 1, 'Enable STS?', '', 80, '0', 1, 'checkbox'),
(79, 'STS_TTL', '600', 'text', 'The Time to Live (maxage) of the STS header expressed in minutes.', NULL, 'network', 1, 'STS Time out', '', 90, '600', 1, 'int'),
(80, 'MAINTENANCE_ALERTS_FOR_VIEW_USERS', '0', 'checkbox', 'Email maintenance alerts for users with view permissions to effected Displays.', NULL, 'displays', 1, 'Maintenance Alerts for Users', '', 60, '0', 1, 'checkbox'),
(81, 'CALENDAR_TYPE', 'Gregorian', 'dropdown', 'Which Calendar Type should the CMS use?', 'Gregorian|Jalali', 'regional', 1, 'Calendar Type', '', 50, 'Gregorian', 1, 'string');

INSERT INTO `usertype` (`usertypeid`, `usertype`) VALUES
(1, 'Super Admin'),
(2, 'Group Admin'),
(3, 'User');

INSERT INTO `user` (`UserID`, `usertypeid`, `UserName`, `UserPassword`, `loggedin`, `lastaccessed`, `email`, `homepage`, `Retired`) VALUES
(1, 1, 'xibo_admin', '21232f297a57a5a743894a0e4a801fc3', 1, NOW(), '', 'statusdashboard', 0);

INSERT INTO `lkmenuitemgroup` (`LkMenuItemGroupID`, `GroupID`, `MenuItemID`) VALUES
(1, 1, 33),
(2, 1, 14),
(3, 1, 15),
(4, 1, 16),
(5, 1, 20),
(6, 1, 24),
(7, 1, 1),
(8, 1, 2),
(9, 1, 3),
(10, 1, 29),
(11, 1, 30),
(12, 1, 26);

INSERT INTO `lkpagegroup` (`lkpagegroupID`, `pageID`, `groupID`) VALUES
(1, 2, 1),
(2, 1, 1),
(3, 3, 1),
(4, 19, 1),
(5, 5, 1),
(6, 7, 1),
(7, 24, 1),
(8, 39, 1),
(9, 41, 1),
(10, 42, 1);

INSERT INTO `lkusergroup` (`LkUserGroupID`, `GroupID`, `UserID`) VALUES
(10, 3, 1);

INSERT INTO `transition` (`TransitionID`, `Transition`, `Code`, `HasDuration`, `HasDirection`, `AvailableAsIn`, `AvailableAsOut`) VALUES
(1, 'Fade In', 'fadeIn', 1, 0, 0, 0),
(2, 'Fade Out', 'fadeOut', 1, 0, 0, 0),
(3, 'Fly', 'fly', 1, 1, 0, 0);

INSERT INTO `datatype` (`DataTypeID`, `DataType`) VALUES
(1, 'String'),
(2, 'Number'),
(3, 'Date'),
(4, 'Image');

INSERT INTO `datasetcolumntype` (`DataSetColumnTypeID`, `DataSetColumnType`) VALUES
(1, 'Value'),
(2, 'Formula');

INSERT INTO `bandwidthtype` (`bandwidthtypeid`, `name`) VALUES
(1, 'Register'),
(2, 'Required Files'),
(3, 'Schedule'),
(4, 'Get File'),
(5, 'Get Resource'),
(6, 'Media Inventory'),
(7, 'Notify Status'),
(8, 'Submit Stats'),
(9, 'Submit Log'),
(10, 'Blacklist'),
(11, 'Screen Shot');


INSERT INTO `tag` (`tagId`, `tag`) VALUES
(1, 'template'),
(2, 'background'),
(3, 'thumbnail');