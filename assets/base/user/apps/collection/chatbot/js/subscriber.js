/*
 * Chatbot Subscriber javascript file
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
        data[$('.main .chatbot-page').attr('data-csrf-name')] = $('.main .chatbot-page').attr('data-csrf-hash');

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'get_all_categories');

    }

    /*
     * Get all subscriber's categories
     * 
     * @since   0.0.8.0
     */
    Main.get_all_subscriber_categories = function () {

        // Prepare data to request
        var data = {
            action: 'get_all_subscriber_categories',
            subscriber_id: $('.main .chatbot-page').attr('data-subscriber')
        };

        // Set CSRF
        data[$('.main .chatbot-page').attr('data-csrf-name')] = $('.main .chatbot-page').attr('data-csrf-hash');

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'get_all_subscriber_categories');

    }

    /*
     * Get all subscriber's messages
     * 
     * @param integer page contains the page's number
     * 
     * @since   0.0.8.0
     */
    Main.get_all_messages = function (page) {

        // Prepare data to request
        var data = {
            action: 'get_all_subscriber_messages',
            subscriber_id: $('.main .chatbot-page').attr('data-subscriber'),
            page: page

        };

        // Set CSRF
        data[$('.main .chatbot-page').attr('data-csrf-name')] = $('.main .chatbot-page').attr('data-csrf-hash');

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'get_all_messages');

    }

    /*
     * Turn array to object
     * 
     * @param array suggestions contains the suggestions list
     * 
     * @since   0.0.8.0
     * 
     * @return object with suggestions
     */
    Main.to_object = function (suggestions) {

        // Create new object
        var newObj = new Object();

        // Verify if suggestions is an object
        if (typeof suggestions == "object") {

            // List all arrays
            for (var i in suggestions) {

                // Turn array to object
                var thisArray = Main.to_object(suggestions[i]);

                // Add object to newObj
                newObj[i] = thisArray;

            }

        } else {

            // Add object to newObj
            newObj = suggestions;

        }

        return newObj;

    };

    /*******************************
    ACTIONS
    ********************************/

    /*
     * Select categories
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main .all-categories-list .select-category', function () {

        // Get the category
        var category_id = $(this).attr('data-id');

        // Create an object with form data
        var data = {
            action: 'select_subscriber_category',
            category_id: category_id,
            subscriber_id: $('.main .chatbot-page').attr('data-subscriber')
        };

        // Set CSRF
        data[$('.main .chatbot-page').attr('data-csrf-name')] = $('.main .chatbot-page').attr('data-csrf-hash');

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'select_subscriber_category');

        // Display loading animation
        $('.page-loading').fadeIn('slow');        

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

            case 'messages':

                // Get subscriber's messages
                Main.get_all_messages(page);

                break;

        }

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

        // Get categories
        var selected_categories = $('.main .all-categories-list .selected-category');

        // Categories
        var categories = [];    

        // List all categories
        if ( selected_categories.length > 0 ) {

            // List all categories
            for ( var d = 0; d < selected_categories.length; d++ ) {

                // Set category
                categories.push($(selected_categories[d]).attr('data-id'));

            }

            // Turn categories to object
            categories = Main.to_object(categories);

        }

        // Create an object with form data
        var data = {
            action: 'save_subscriber_categories',
            subscriber_id: $('.main .chatbot-page').attr('data-subscriber'),
            categories: categories
        };

        // Set CSRF
        data[$('.main .chatbot-page').attr('data-csrf-name')] = $('.main .chatbot-page').attr('data-csrf-hash');

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'save_subscriber_categories');

        // Display loading animation
        $('.page-loading').fadeIn('slow');    

    });

    /*******************************
    RESPONSES
    ********************************/

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

            // Get the subscriber's categories
            Main.get_all_subscriber_categories();

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
     * Display the subscriber's categories
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.get_all_subscriber_categories = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // List all categories
            for (var c = 0; c < data.categories.length; c++) {

                // Verify if the category exists
                if ($('.main .all-categories-list .select-category[data-id="' + data.categories[c].category_id + '"]').length > 0) {

                    // Add selected class
                    $('.main .all-categories-list .select-category[data-id="' + data.categories[c].category_id + '"]').addClass('selected-category');

                }

            }

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
    Main.methods.select_subscriber_category = function (status, data) {

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
     * Display the subscriber's messages
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.get_all_messages = function (status, data) {

        // Hide pagination
        $('.main .messages .pagination').hide();

        // Verify if the success response exists
        if (status === 'success') {

            // Show pagination
            $('.main .messages .pagination').fadeIn('slow');

            // Display the pagination
            Main.pagination.page = data.page;
            Main.show_pagination('.main .messages', data.total);

            // Subscriber's messages
            var messages = '';

            // List all messages
            for ( var b = 0; b < data.messages.length; b++ ) {

                // Set date
                var date = data.messages[b].created;

                // Set time
                var gettime = Main.calculate_time(date, data.date);

                // Default response var
                var response = '';

                // Verify if response was a group
                if ( data.messages[b].group_id > 0 ) {

                    // Verify if group's name exists
                    if ( data.messages[b].group_name ) {

                        // Set response
                        response = '<i class="lni-line-spacing"></i> ' + data.messages[b].group_name;

                    } else {

                        // Set response
                        response = '<i class="lni-line-spacing"></i> ' + data.words.group_deleted;

                    }

                } else {

                    // Set response
                    response = '<textarea class="form-control reply-text-message" rows="3">' + data.messages[b].response + '</textarea>';

                }

                // Add message to the list
                messages += '<div class="accordion" id="accordion">'
                                + '<div class="card">'
                                    + '<div class="card-header" id="headingOne">'
                                        + '<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#menu-text-reply" aria-expanded="true" aria-controls="menu-text-reply">'
                                            + data.words.messages
                                            + '<span>'
                                                + gettime
                                            + '</span>'
                                        + '</button>'
                                    + '</div>'
                                    + '<div id="menu-text-reply" class="collapse show" aria-labelledby="menu-text-reply" data-parent="#accordion" data-type="text-reply">'
                                        + '<div class="card-body">'
                                            + '<div class="row mb-4">'
                                                + '<div class="col-12">'
                                                    + '<div class="form-group">'
                                                        + '<textarea class="form-control text-message" rows="3">' + data.messages[b].question + '</textarea>'
                                                    + '</div>'
                                                + '</div>'
                                            + '</div>'
                                            + '<div class="row">'
                                                + '<div class="col-12">'
                                                    + '<div class="form-group">'
                                                        + response
                                                    + '</div>'
                                                + '</div>'
                                            + '</div>'
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                            + '</div>'
                        + '<hr>';

            }

            // Display messages
            $('.main .messages-list').html(messages);

        } else {

            // No messages
            var message = '<div class="row">'
                + '<div class="col-12">'
                    + data.message
                + '</div>'
            + '</div>';

            // Display no messages message
            $('.main .messages-list').html(message);

        }
        
    };

    /*
     * Display the categories saving response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.save_subscriber_categories = function (status, data) {

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

    /*******************************
    FORMS
    ********************************/

    // Get ALL Categories
    Main.get_all_categories();

    // Get subscriber's messages
    Main.get_all_messages(1);

});