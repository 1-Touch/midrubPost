/*
 * Settings javascript file
*/

jQuery(document).ready( function ($) {
    'use strict';
    
    // Get home page url
    var url = $('.navbar-brand').attr('href');
    
    /*******************************
    METHODS
    ********************************/
   
    /*
     * Get pages by category
     * 
     * @param string drop_class contains the dropdown's class
     * 
     * @since   0.0.7.8
     */    
    Main.frontend_settings_load_pages_by_category =  function (drop_class) {

        // Prepare data
        var data = {
            action: 'settings_auth_pages_list',
            drop_class: drop_class,
            key: $('.frontend-page .' + drop_class + '_search').val()
        };

        // Set CSRF
        data[$('.save-settings').attr('data-csrf')] = $('input[name="' + $('.save-settings').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/frontend', 'POST', data, 'settings_auth_show_pages');
        
    };

    /*
     * Load selected options
     * 
     * @since   0.0.7.8
     */    
    Main.load_selected_settings =  function () {

        // Prepare data
        var data = {
            action: 'settings_all_options'
        };
        
        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/frontend', 'GET', data, 'settings_all_options');
        
    };

    /*******************************
    ACTIONS
    ********************************/

    /*
     * Search pages by category
     * 
     * @since   0.0.7.8
     */
    $(document).on('keyup', '.frontend-page .settings-dropdown-search-input', function () {
        
        // Load pages
        Main.frontend_settings_load_pages_by_category($(this).closest('.dropdown').attr('data-option'));
        
    });

    /*
     * Display save button
     * 
     * @since   0.0.7.8
     */
    $(document).on('change', '.frontend-page textarea', function (e) {
        e.preventDefault();

        // Display save button
        $('.settings-save-changes').fadeIn('slow');
        
    });

    /*
     * Get auth pages
     * 
     * @since   0.0.7.8
     */
    $(document).on('click', '.frontend-page .settings-dropdown-btn', function (e) {
        e.preventDefault();

        // Load pages
        Main.frontend_settings_load_pages_by_category($(this).closest('.dropdown').attr('data-option'));
        
    });

    /*
     * Select a page
     * 
     * @since   0.0.7.8
     */
    $(document).on('click', '.frontend-page .settings-dropdown-list-ul a', function (e) {
        e.preventDefault();

        // Get item's id
        var item_id = $(this).attr('data-id'); 
        
        // Get item's title
        var item_title = $(this).text();

        // Add item's title and item's id
        $(this).closest('.dropdown').find('.settings-dropdown-btn').text(item_title);
        $(this).closest('.dropdown').find('.settings-dropdown-btn').attr('data-id', item_id);

        // Display save button
        $('.settings-save-changes').fadeIn('slow');
        
    });   
    
    /*
     * Save settings
     * 
     * @since   0.0.7.8
     */ 
    $( document ).on( 'click', '.settings-save-changes', function () {
        
        // Hide save button
        $('.settings-save-changes').fadeOut('slow');
        
        // Get all dropdowns
        var dropdowns = $('.frontend-page .settings-dropdown-btn').length;
        
        var all_dropdowns = [];

        if (dropdowns > 0) {

            for (var d = 0; d < dropdowns; d++) {

                if ($('.frontend-page .settings-dropdown-btn').eq(d).attr('data-id')) {

                    all_dropdowns[$('.frontend-page .settings-dropdown-btn').eq(d).closest('.dropdown').attr('data-option')] = $('.frontend-page .settings-dropdown-btn').eq(d).attr('data-id');

                }

            }

        }

        // Get all textareas
        var textareas = $('.frontend-page .settings-textarea-value').length;
        
        var all_textareas = [];

        if (textareas > 0) {

            for (var t = 0; t < textareas; t++) {

                all_textareas[$('.frontend-page .settings-textarea-value').eq(t).attr('data-option')] = $('.frontend-page .settings-textarea-value').eq(t).val().replace(/</g,"&lt;").replace(/>/g,"&gt;");

            }

        }
        
        // Prepare data to send
        var data = {
            action: 'save_frontend_settings',
            all_dropdowns: Object.entries(all_dropdowns),
            all_textareas: Object.entries(all_textareas)
        };
        
        data[$('.save-settings').attr('data-csrf')] = $('input[name="' + $('.save-settings').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/frontend', 'POST', data, 'save_frontend_settings');

        // Show loading animation
        $('.page-loading').fadeIn('slow');
        
    }); 
   
    /*******************************
    RESPONSES
    ********************************/ 
   
    /*
     * Display auth pages
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.8
     */
    Main.methods.settings_auth_show_pages = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {

            // Pages list
            var pages = '';

            // List all pages
            for ( var p = 0; p < data.pages.length; p++ ) {

                pages += '<li class="list-group-item">'
                            + '<a href="#" data-id="' + data.pages[p].content_id + '">'
                                + data.pages[p].meta_value
                            + '</a>'
                        + '</li>';

            }

            // Display all pages
            $('.frontend-page .' + data.drop_class + '_list').html(pages);
            
        } else {

            var message = '<li class="list-group-item no-results-found">'
                + data.message
            + '</li>';

            // Display no contents found
            $('.frontend-page .' + data.drop_class + '_list').html(message);
            
        }

    };
 
    /*
     * Display settings saving response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.8
     */
    Main.methods.save_frontend_settings = function ( status, data ) { 

        // Verify if the success response exists
        if ( status === 'success' ) {

            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
        } else {

            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
    };

    /*
     * Display selected options
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.8
     */
    Main.methods.settings_all_options = function ( status, data ) { 

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Verify if pages by role exists
            if ( typeof data.response.pages_by_role !== 'undefined' ) {

                // List all pages
                for (let index = 0; index < data.response.pages_by_role.length; index++) {
                    
                    // Verify if class exists
                    if ( $('.' + data.response.pages_by_role[index].meta_value).length > 0 ) {

                        // Set text
                        $('.' + data.response.pages_by_role[index].meta_value).text(data.response.pages_by_role[index].title);

                        // Set content's id
                        $('.' + data.response.pages_by_role[index].meta_value).attr('data-id', data.response.pages_by_role[index].content_id);

                    }

                }

            }
            
        }
        
    };
    
    /*******************************
    FORMS
    ********************************/
   

    
    /*******************************
    DEPENDENCIES
    ********************************/

    // Hide loading animation
    $('.page-loading').fadeOut('slow');

    // Load selected options
    Main.load_selected_settings();
 
});