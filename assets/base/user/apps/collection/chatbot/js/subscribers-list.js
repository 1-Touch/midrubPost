/*
 * Chatbot Subscribers List javascript file
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
     * Loads subscribers from database
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.8.0
     */
    Main.load_subscribers = function (page) {

        // Prepare data to send
        var data = {
            action: 'load_subscribers',
            key: $('.main .search-subscribers .subscribers-key').val(),
            page: page
        };

        // Set CSRF
        data[$('.main .search-subscribers').attr('data-csrf')] = $('input[name="' + $('.main .search-subscribers').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'load_subscribers');

    };

    /*******************************
    ACTIONS
    ********************************/

    /*
     * Search for subscribers
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('keyup', '.main .subscribers-key', function (e) {
        e.preventDefault();

        if ($(this).val() === '') {

            // Hide button
            $('.main .cancel-subscribers-search').fadeOut('slow');

        } else {

            // Display the cancel button
            $('.main .cancel-subscribers-search').fadeIn('slow');

        }

        // Load subscribers
        Main.load_subscribers(1);

    });

    /*
     * Cancel the subscribers search
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main .cancel-subscribers-search', function (e) {
        e.preventDefault();

        // Empty the input
        $('.main .subscribers-key').val('');

        // Hide button
        $('.main .cancel-subscribers-search').fadeOut('slow');

        // Load subscribers
        Main.load_subscribers(1);

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

            case 'subscribers':

                // Load subscribers by page
                Main.load_subscribers(page);

                break;

        }

        // Display loading animation
        $('.page-loading').fadeIn('slow');

    });

    /*******************************
    RESPONSES
    ********************************/

    /*
     * Display the subscribers
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.load_subscribers = function (status, data) {

        // Hide pagination
        $('.main .chatbot-user-list .pagination').hide();

        // Verify if the success response exists
        if (status === 'success') {

            // Show pagination
            $('.main .chatbot-user-list .pagination').fadeIn('slow');

            // Display the pagination
            Main.pagination.page = data.page;
            Main.show_pagination('.main .chatbot-user-list', data.total);

            // Subscribers var
            var subscribers = '';

            // List 10 subscribers
            for (var c = 0; c < data.subscribers.length; c++) {

                subscribers += '<li>'
                                + '<div class="row">'
                                    + '<div class="col-xl-8 col-6">'
                                        + '<h3>'
                                            + '<img src="' + data.subscribers[c].image + '">'
                                            + data.subscribers[c].name
                                        + '</h3>'
                                    + '</div>'
                                    + '<div class="col-xl-4 col-6 text-right">'
                                        + '<a href="' + url + 'user/app/chatbot?p=subscribers&subscriber=' + data.subscribers[c].subscriber_id + '" class="btn btn-outline-info inbox-account-details">'
                                            + '<i class="lni-pie-chart"></i>'
                                            + data.words.details
                                        + '</a>'
                                    + '</div>'
                                + '</div>'
                            + '</li>';

            }

            // Display subscribers
            $('.main .chatbot-user-list .subscribers-list').html(subscribers);

        } else {

            // No found subscribers message
            var message = '<li class="no-results">'
                    + data.message
                + '</li>';

            // Display no subscribers message
            $('.main .chatbot-user-list .subscribers-list').html(message);

        }

    };

    /*******************************
    FORMS
    ********************************/

    // Load subscribers
    Main.load_subscribers(1);

});