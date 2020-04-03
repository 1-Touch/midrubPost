/*
 * Chatbot Pages javascript file
*/

jQuery(document).ready(function ($) {
    'use strict';

    /*
     * Get the website's url
     */
    var url = $('meta[name=url]').attr('content');

    /*******************************
    METHODS
    ********************************/

    /*
     * Search and display connected Facebook pages
     * 
     * @since   0.0.8.0
     */
    Main.load_all_connected_pages = function () {

        // Prepare data to send
        var data = {
            action: 'load_all_connected_pages',
            key: $('.main #accounts-manager-popup .search-for-pages').val()
        };

        // Set CSRF
        data[$('.main .search-all-facebook-pages').attr('data-csrf')] = $('input[name="' + $('.main .search-all-facebook-pages').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'load_all_connected_pages');

    };

    /*
     * Search and display connected Facebook pages
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.8.0
     */
    Main.load_connected_pages = function (page) {

        // Prepare data to send
        var data = {
            action: 'load_connected_pages',
            key: $('.main .search-pages .search-for-pages').val(),
            page: page
        };

        // Set CSRF
        data[$('.main .search-pages').attr('data-csrf')] = $('input[name="' + $('.main .search-pages').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'load_connected_pages');

    };

    /*
     * Reload accounts
     * 
     * @since   0.0.8.0
     */
    Main.reload_accounts = function () {
        
        // Load all connected Facebook Pages
        Main.load_all_connected_pages();
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    };
    
    /*
     * Get all categories
     * 
     * @since   0.0.8.0
     */
    Main.get_all_categories = function () {

        // Prepare data to request
        var data = {
            action: 'get_all_categories'
        };

        // Set CSRF
        data[$('.main .save-page-configuration').attr('data-csrf')] = $('input[name="' + $('.main .save-page-configuration').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'get_all_categories');

    }

    /*******************************
    ACTIONS
    ********************************/

    /*
     * Search for all connected Facebook Pages
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('keyup', '.main #accounts-manager-popup .search-for-pages', function (e) {
        e.preventDefault();

        if ($(this).val() === '') {

            // Hide button
            $('.main #accounts-manager-popup .cancel-pages-search').fadeOut('slow');

        } else {

            // Display the cancel button
            $('.main #accounts-manager-popup .cancel-pages-search').fadeIn('slow');

        }

        // Load all connected Facebook Pages
        Main.load_all_connected_pages();

    });

    /*
     * Search for connected Facebook Pages and display by page
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('keyup', '.main .search-pages .search-for-pages', function (e) {
        e.preventDefault();

        if ($(this).val() === '') {

            // Hide button
            $('.main .search-pages .cancel-pages-search').fadeOut('slow');

        } else {

            // Display the cancel button
            $('.main .search-pages .cancel-pages-search').fadeIn('slow');

        }

        // Load Facebook Pages by page
        Main.load_connected_pages(1);

    });
    
    /*
     * Display popup save
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('keyup', '.main .form-control', function (e) {
        e.preventDefault();

        // Show the save button
        $('.main .settings-save-changes').css('display', 'flex');   

    });

    /*
     * Load connected Facebook Pages
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
    */
    $(document).on('show.bs.modal', '#accounts-manager-popup', function () {

        // Load all connected Facebook Pages
        Main.load_all_connected_pages();

    });

    /*
     * Load connected Facebook Pages
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
    */
    $(document).on('hide.bs.modal', '#accounts-manager-popup', function () {

        // Load Facebook Pages by page
        Main.load_connected_pages(1);

    });    

    /*
     * Cancel the all connected pages search
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main #accounts-manager-popup .cancel-pages-search', function (e) {
        e.preventDefault();

        // Empty the input
        $('.main #accounts-manager-popup .search-for-pages').val('');

        // Hide button
        $('.main #accounts-manager-popup .cancel-pages-search').fadeOut('slow');

        // Load all connected Facebook Pages
        Main.load_all_connected_pages();

    });

    /*
     * Cancel the connected pages search
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main .search-pages .cancel-pages-search', function (e) {
        e.preventDefault();

        // Empty the input
        $('.main .search-for-pages').val('');

        // Hide button
        $('.main .search-pages .cancel-pages-search').fadeOut('slow');

        // Load Facebook Pages by page
        Main.load_connected_pages(1);

    });

    /*
     * Connect a new account
     * 
     * @since   0.0.8.0
     */ 
    $(document).on('click', '.main .connect-new-facebook-page', function() {
        
        // Get network
        var network = 'facebook_pages';
        
        var popup_url = url + 'user/connect/' + network;
        var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
        var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

        var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        var left = ((width / 2) - ((width/2) / 2)) + dualScreenLeft;
        var top = ((height / 1.3) - ((height/1.3) / 1.3)) + dualScreenTop;
        var networkWindow = window.open(popup_url, 'Connect Account', 'scrollbars=yes, width=' + (width/2) + ', height=' + (height/1.3) + ', top=' + top + ', left=' + left);

        if (window.focus) {
            networkWindow.focus();
        }
        
    });

    /*
     * Try to connect a Facebook Page
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main .connect-facebook-page-btn', function (e) {
        e.preventDefault();

        // Prepare data to send
        var data = {
            action: 'connect_facebook_page',
            page_id: $(this).attr('data-id')
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'GET', data, 'connect_facebook_page');

        // Display loading animation
        $('.page-loading').fadeIn('slow');

    });

    /*
     * Save configuration
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main .settings-save-changes .btn', function (e) {
        e.preventDefault();

        // Submit form
        $('.main .save-page-configuration').submit();

    });

    /*
     * Display popup save
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main input[type="checkbox"]', function () {

        // Show the save button
        $('.main .settings-save-changes').css('display', 'flex');        

    });

    /*
     * Disconnect Facebook Page from Facebook Bot
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main .disconnect-from-bot-btn', function () {

        // Get Facebook Page Id
        var page_id = $('.main .save-page-configuration').attr('data-id');

        // Create an object with form data
        var data = {
            action: 'disconnect_from_bot',
            page_id: page_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'GET', data, 'disconnect_from_bot');

        // Display loading animation
        $('.page-loading').fadeIn('slow');

    });

    /*
     * Connect Facebook Page to Facebook Bot
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main .connect-to-bot-btn', function () {

        // Get Facebook Page Id
        var page_id = $('.main .save-page-configuration').attr('data-id');

        // Create an object with form data
        var data = {
            action: 'connect_to_bot',
            page_id: page_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'GET', data, 'connect_to_bot');

        // Display loading animation
        $('.page-loading').fadeIn('slow');

    });

    /*
     * Select categories
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main .all-categories-list .select-category', function () {

        // Get the category
        var category_id = $(this).attr('data-id');

        // Get Facebook Page Id
        var page_id = $('.main .save-page-configuration').attr('data-id');

        // Create an object with form data
        var data = {
            action: 'select_facebook_page_category',
            page_id: page_id,
            category_id: category_id
        };

        // Set CSRF
        data[$('.main .save-page-configuration').attr('data-csrf')] = $('input[name="' + $('.main .save-page-configuration').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'select_facebook_page_category');

        // Display loading animation
        $('.page-loading').fadeIn('slow');        

    });

    /*
     * Show groups with suggestions
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main .chatbot-select-menu', function () {

        // Create an object with form data
        var data = {
            action: 'suggestions_groups'
        };

        // Set CSRF
        data[$('.main .save-page-configuration').attr('data-csrf')] = $('input[name="' + $('.main .save-page-configuration').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'load_suggestions_groups');       

    });

    /*
     * Search for suggestions
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('keyup', '.main .chatbot-menu-list .chatbot-search-for-suggestions', function (e) {
        e.preventDefault();

        // Create an object with form data
        var data = {
            action: 'suggestions_groups',
            key: $(this).val(),
        };

        // Set CSRF
        data[$('.main .save-page-configuration').attr('data-csrf')] = $('input[name="' + $('.main .save-page-configuration').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'load_suggestions_groups');

    });

    /*
     * Change dropdown option
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */ 
    $( document ).on( 'click', '.main #menu-suggestions .dropdown-menu a', function (e) {
        e.preventDefault();
        
        // Get Dropdown's ID
        var id = $(this).attr('data-id');
        
        // Set id
        $(this).closest('.dropdown').find('.btn-secondary').attr('data-id', id);

        // Set specifi text
        $(this).closest('.dropdown').find('.btn-secondary').html($(this).html());

        // Show the save button
        $('.main .settings-save-changes').css('display', 'flex');   
        
    });

    /*
     * Delete facebook page from the accounts manager popup
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */ 
    $( document ).on( 'click', '.main .accounts-manager-accounts-list li a', function (e) {
        e.preventDefault();
            
        // Get the page's id
        var page_id = $(this).attr('data-id');

        // Prepare data to send
        var data = {
            action: 'account_manager_delete_accounts',
            page_id: page_id
        };

        // Set CSRF
        data[$('.main .save-page-configuration').attr('data-csrf')] = $('input[name="' + $('.main .save-page-configuration').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'account_manager_delete_accounts');
        
    });

    /*
     * Displays pagination by page click
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', 'body .pagination li a', function (e) {
        e.preventDefault();

        // Verify which pagination it is based on data's type 
        var page = $(this).attr('data-page');

        // Display results
        switch ($(this).closest('ul').attr('data-type')) {

            case 'pages':

                // Load Facebook Pages by page
                Main.load_connected_pages(page);

                break;

        }

        // Display loading animation
        $('.page-loading').fadeIn('slow');

    });

    /*******************************
    RESPONSES
    ********************************/

    /*
     * Display the facebook pages response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.load_all_connected_pages = function (status, data) {

        // Response default value
        var response = '';

        // Verify if the success response exists
        if (status === 'success') {

            // List all pages
            for ( var p = 0; p < data.pages.length; p++ ) {

                // Set page
                response += '<li>'
                            + '<a href="#" data-id="' + data.pages[p].network_id + '">'
                            + data.pages[p].user_name + ' <i class="icon-trash"></i>'
                        + '</a>'
                    + '</li>';

            }

        } else {

            // Set no found message
            response += '<li class="no-results">'
                    + data.message
                + '</li>';            

        }

        // Display response
        $('.main #accounts-manager-popup .accounts-manager-accounts-list').html(response);

    };

    /*
     * Display the facebook pages by page response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.load_connected_pages = function (status, data) {

        // Hide pagination
        $('.main .chatbot-list .pagination').hide();

        // Response default value
        var response = '';

        // Verify if the success response exists
        if (status === 'success') {

            // Hide pagination
            $('.main .chatbot-list .pagination').show();

            // Display the pagination
            Main.pagination.page = data.page;
            Main.show_pagination('.main .chatbot-list', data.total);

            // List all pages
            for ( var p = 0; p < data.pages.length; p++ ) {

                // Set page
                response += '<li>'
                            + '<div class="row">'
                                + '<div class="col-xl-8 col-6">'
                                    + '<h3>'
                                        + '<img src="' + data.pages[p].user_picture + '">'
                                        + data.pages[p].user_name
                                    + '</h3>'
                                + '</div>'
                                + '<div class="col-xl-4 col-6 text-right">'
                                    + '<a href="#" class="btn btn-outline-info connect-facebook-page-btn" data-id="' + data.pages[p].network_id + '">'
                                        + '<i class="icon-user-following"></i>'
                                        + data.words.connect
                                    + '</a>'
                                + '</div>'
                            + '</div>'
                        + '</li>';                    

            }

        } else {

            // Set no found message
            response += '<li class="no-results">'
                    + data.message
                + '</li>';            

        }

        // Display response
        $('.main .chatbot-list .list-pages').html(response);

    };

    /*
     * Display the facebook pages by page response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.connect_facebook_page = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Unselect selected categories
            $('.main .all-categories-list .select-category').removeClass('selected-category');

            // Uncheck Checkboxes
            $( '.main .greeting input[type="checkbox"]' ).prop('checked', false);
            $( '.main .default-response input[type="checkbox"]' ).prop('checked', false);

            // Show the boxes
            $('.main .connect-facebook-page').hide();
            $('.main .connect-to-bot').show();
            $('.main .menu').show();
            $('.main .greeting').show();
            $('.main .default-response').show();
            $('.main .categories-list').show();

            // Hide the error box
            $('.main .error-connect-facebook-page').hide();
            
            // Remove the connected class
            $('.main .list-pages .btn-outline-info').removeClass('connected-page');

            // Add the connected class
            $('.main .list-pages .btn-outline-info[data-id=' + data.page_id + ']').addClass('connected-page');
            
            // Set the form id
            $('.main .save-page-configuration').attr('data-id', data.page_id);

            // Verify if meta exists
            if ( typeof data.meta !== 'undefined' ) {

                // List all meta
                for ( var m = 0; m < data.meta.length; m++ ) {

                    switch ( data.meta[m].meta_name ) {

                        case 'default_message':

                            $('.main .default-text-message').val(data.meta[m].meta_value);

                            break;

                        case 'greeting_message':

                            $('.main .greeting-text-message').val(data.meta[m].meta_value);

                            break;

                        case 'selected_menu':

                            $('.main .chatbot-select-menu').attr('data-id', data.meta[m].meta_value);
                            $('.main .chatbot-select-menu').text(data.group_name);

                            break;

                        case 'default_message_enable':

                            $( '.main .default-response input[type="checkbox"]' ).prop('checked', true);

                            break;

                        case 'greeting_message_enable':

                            $( '.main .greeting input[type="checkbox"]' ).prop('checked', true);

                            break;

                        case 'menu_enable':

                            $('.main .enable-menu').prop('checked', true);

                            break;

                    }

                }

            }

            // Verify if the Facebook Pages is subscribed
            if ( data.subscribed ) {

                $( '.main .connect-to-bot-btn' ).hide();
                $( '.main .disconnect-from-bot-btn' ).show();

            } else {

                $( '.main .connect-to-bot-btn' ).show();
                $( '.main .disconnect-from-bot-btn' ).hide();

            }

            // Verify if categories exists
            if ( typeof data.categories !== 'undefined' ) {

                // List all categories
                for ( var c = 0; c < data.categories.length; c++ ) {

                    // Verify if the category exists
                    if ( $('.main .all-categories-list .select-category[data-id="' + data.categories[c].category_id + '"]').length > 0 ) {

                        // Add selected class
                        $('.main .all-categories-list .select-category[data-id="' + data.categories[c].category_id + '"]').addClass('selected-category');

                    }

                }

            }

        } else {

            // Remove the connected class
            $('.main .list-pages .btn-outline-info').removeClass('connected-page');

            // Hide the boxes
            $('.main .connect-facebook-page').hide();
            $('.main .connect-to-bot').hide();
            $('.main .menu').hide();
            $('.main .greeting').hide();
            $('.main .default-response').hide();
            $('.main .categories-list').hide();

            // Show the error box
            $('.main .error-connect-facebook-page').show();
            $('.main .error-connect-facebook-page p').html('<i class="lni-alarm"></i> ' + data.message);

            // Remove form id
            $('.main .save-page-configuration').removeAttr('data-id');

        }
        
    };

    /*
     * Display the configuration saving response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.save_page_configuration = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Hide the save button
            $('.main .settings-save-changes').css('display', 'none');  

        } else {

            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);

        }
        
    };

    /*
     * Display the connect page response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.connect_to_bot = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            $( '.main .connect-to-bot-btn' ).hide();
            $( '.main .disconnect-from-bot-btn' ).show();

        } else {

            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);

        }
        
    };

    /*
     * Display the disconnect page response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.disconnect_from_bot = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            $( '.main .connect-to-bot-btn' ).show();
            $( '.main .disconnect-from-bot-btn' ).hide();

        } else {

            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);

        }
        
    };

    /*
     * Display the category selection response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.select_facebook_page_category = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Verify if the category exists
            if ( $('.main .all-categories-list .select-category[data-id="' + data.category_id + '"]').length > 0 ) {

                // Verify if the category is selected
                if ( $('.main .all-categories-list .select-category[data-id="' + data.category_id + '"]').hasClass('selected-category') ) {

                    // Add selected class
                    $('.main .all-categories-list .select-category[data-id="' + data.category_id + '"]').removeClass('selected-category');

                } else {

                    // Add selected class
                    $('.main .all-categories-list .select-category[data-id="' + data.category_id + '"]').addClass('selected-category');

                }

                // Show the save button
                $('.main .settings-save-changes').css('display', 'flex');     

            }

        } else {

            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);

        }
        
    };

    /*
     * Display the categories
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.get_all_categories = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Categories var
            var categories = '';

            // List 10 categories
            for (var c = 0; c < data.categories.length; c++) {
                
                // Add category to list
                categories += '<button class="btn btn-primary select-category" type="button" data-id="' + data.categories[c].category_id + '">'
                    + '<i class="far fa-bookmark"></i>'
                    + data.categories[c].name
                    + '</button>';

            }

            // Display categories
            $('.main .all-categories-list').html(categories);

        } else {

            // No categories
            var message = '<div class="row">'
                + '<div class="col-10">'
                + data.message
                + '</div>'
                + '</div>';

            // Display no categories message
            $('.main .all-categories-list').html(message);

        }

    };

    /*
     * Display the suggestions groups
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.load_suggestions_groups = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Groups var
            var groups = '';
    
            // List 10 groups
            for (var c = 0; c < data.groups.length; c++) {
    
                groups += '<li class="list-group-item">'
                    + '<a href="#" data-id="' + data.groups[c].group_id + '">'
                        + data.groups[c].group_name
                    + '</a>'
                + '</li>';
    
            } 

            // Display groups
            $('.main .chatbot-menu-list .chatbot-suggestions-list').html(groups);

        } else {

            // No found groups message
            var message = '<li class="no-results">'
                    + data.message
                + '</li>';

            // Display no groups message
            $('.main .chatbot-menu-list .chatbot-suggestions-list').html(message);

        }

    };

    /*
     * Display account deletion status
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.account_manager_delete_accounts = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Load all connected Facebook Pages
            Main.load_all_connected_pages();
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
    };

    /*******************************
    FORMS
    ********************************/

    /*
     * Save Facebook Page Configuration
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('submit', '.main .save-page-configuration', function (e) {
        e.preventDefault();

        // Create an object with form data
        var data = {
            action: 'save_page_configuration',
            page_id: $(this).attr('data-id'),
            default_message: $(this).find('.default-text-message').val()
        };

        // Set greeting message
        data['greeting_message'] = $(this).find('.greeting-text-message').val();

        // Verify if the greeting message is enabled
        if ( $('.main .enable-greeting').is(':checked') ) {

            // Set enabled greeting text message
            data['greeting_message_enabled'] = 1;

        }   

        // Verify if default text message is enabled
        if ( $(this).find('.enable-default-text-message').is(':checked') ) {

            // Set enabled default text message
            data['default_message_enabled'] = 1;

        }

        // Verify if menu is enabled
        if ( $(this).find('.enable-menu').is(':checked') ) {

            // Set enabled menu
            data['menu_enabled'] = 1;

        }
        
        // Verify if is user has selected a menu
        if ( $(this).find('.chatbot-select-menu').attr('data-id') ) {

            // Set selected menu
            data['selected_menu'] = $(this).find('.chatbot-select-menu').attr('data-id');

        }
        
        // Get all selected categories
        var all_categories = $('.main .all-categories-list .selected-category');

        // Verify if selected categories exists
        if ( all_categories.length > 0 ) {

            // Categories array
            var categories = [];

            // List all selected categories
            for ( var c = 0; c < all_categories.length; c++ ) {

                // Add category ID
                categories.push($(all_categories[c]).attr('data-id'));

            }

            // If the categories array isn't empty
            if ( categories.length > 0 ) {
                data['categories'] = categories;
            }

        }

        // Set CSRF
        data[$(this).attr('data-csrf')] = $('input[name="' + $(this).attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'save_page_configuration');

        // Display loading animation
        $('.page-loading').fadeIn('slow');

    });

    // Load Facebook Pages by page
    Main.load_connected_pages(1);

    // Get ALL Categories
    Main.get_all_categories();

});