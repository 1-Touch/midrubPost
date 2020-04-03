/*
 * Chatbot History Javascript File
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
     * Loads history from database
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.8.0
     */
    Main.load_history = function (page) {

        // Prepare data to send
        var data = {
            action: 'load_history',
            key: $('.main .search-conversations .conversations-key').val(),
            page: page
        };

        // Set CSRF
        data[$('.main .search-conversations').attr('data-csrf')] = $('input[name="' + $('.main .search-conversations').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'load_history');

    };

    /*******************************
    ACTIONS
    ********************************/

    /*
     * Search for conversations
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('keyup', '.main .conversations-key', function (e) {
        e.preventDefault();

        if ($(this).val() === '') {

            // Hide button
            $('.main .cancel-conversations-search').fadeOut('slow');

        } else {

            // Display the cancel button
            $('.main .cancel-conversations-search').fadeIn('slow');

        }

        // Load history
        Main.load_history(1);

    });

    /*
     * Cancel the conversations search
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main .cancel-conversations-search', function (e) {
        e.preventDefault();

        // Empty the input
        $('.main .conversations-key').val('');

        // Hide button
        $('.main .cancel-conversations-search').fadeOut('slow');

        // Load history
        Main.load_history(1);

    });
    
    /*
     * Displays pagination by page click
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.1
     */
    $(document).on('click', 'body .pagination li a', function (e) {
        e.preventDefault();

        // Verify which pagination it is based on data's type 
        var page = $(this).attr('data-page');

        // Display results
        switch ($(this).closest('ul').attr('data-type')) {

            case 'conversations':

                // Load history
                Main.load_history(page);

                break;

        }

        // Display loading animation
        $('.page-loading').fadeIn('slow');

    });

    /*******************************
    RESPONSES
    ********************************/

    /*
     * Display the history's conversations
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.load_history = function (status, data) {

        // Hide pagination
        $('.main .chatbot-user-list .pagination').hide();

        // Verify if the success response exists
        if (status === 'success') {

            // Show pagination
            $('.main .chatbot-user-list .pagination').fadeIn('slow');

            // Display the pagination
            Main.pagination.page = data.page;
            Main.show_pagination('.main .chatbot-user-list', data.total);

            // Conversations var
            var conversations = '';

            // List 10 conversations
            for (var c = 0; c < data.conversations.length; c++) {

                conversations += '<li>'
                                + '<div class="row">'
                                    + '<div class="col-xl-8 col-6">'
                                        + '<h3>'
                                            + '<img src="' + data.conversations[c].image + '">'
                                            + data.conversations[c].name
                                        + '</h3>'
                                    + '</div>'
                                    + '<div class="col-xl-4 col-6 text-right">'
                                        + '<a href="' + url + 'user/app/chatbot?p=history&conversation=' + data.conversations[c].history_id + '" class="btn btn-outline-info inbox-account-details">'
                                            + '<i class="lni-pie-chart"></i>'
                                            + data.words.details
                                        + '</a>'
                                    + '</div>'
                                + '</div>'
                            + '</li>';

            }

            // Display conversations
            $('.main .chatbot-user-list .subscribers-list').html(conversations);

        } else {

            // No found conversations message
            var message = '<li class="no-results">'
                    + data.message
                + '</li>';

            // Display no conversations message
            $('.main .chatbot-user-list .subscribers-list').html(message);

        }

    };

    /*******************************
    FORMS
    ********************************/

    // Load history
    Main.load_history(1);

});