/*
 * Chatbot Suggestions List javascript file
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
     * Gets the suggestions groups from database
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.8.0
     */
    Main.load_suggestions_groups = function (page) {

        // Prepare data to send
        var data = {
            action: 'suggestions_groups',
            key: $('.main .search-suggestions .suggestions-key').val(),
            page: page
        };

        // Set CSRF
        data[$('.main .search-suggestions').attr('data-csrf')] = $('input[name="' + $('.main .search-suggestions').attr('data-csrf') + '"]').val();
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'load_suggestions_groups');

    };

    /*******************************
    ACTIONS
    ********************************/

    /*
     * Search for suggestions
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('keyup', '.main .suggestions-key', function (e) {
        e.preventDefault();

        if ($(this).val() === '') {

            // Hide button
            $('.main .cancel-suggestions-search').fadeOut('slow');

        } else {

            // Display the cancel button
            $('.main .cancel-suggestions-search').fadeIn('slow');

        }

        // Load suggestions groups
        Main.load_suggestions_groups(1);

    });

    /*
     * Cancel the suggestions search
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main .cancel-suggestions-search', function (e) {
        e.preventDefault();

        // Empty the input
        $('.main .suggestions-key').val('');

        // Hide button
        $('.main .cancel-suggestions-search').fadeOut('slow');

        // Load suggestions groups
        Main.load_suggestions_groups(1);

    });

    /*
     * Delete Suggestions Group
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main .delete-group', function (e) {
        e.preventDefault();

        // Get group's ID
        var group_id = $(this).attr('data-id');

        // Create an object with form data
        var data = {
            action: 'delete_group',
            group_id: group_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'GET', data, 'delete_group');

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

            case 'suggestions-group':

                // Load suggestions groups
                Main.load_suggestions_groups(page);

                break;

        }

        // Display loading animation
        $('.page-loading').fadeIn('slow');

    });

    /*******************************
    RESPONSES
    ********************************/

    /*
     * Display the suggestions groups
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.load_suggestions_groups = function (status, data) {

        // Hide pagination
        $('.main .pagination').hide();

        // Verify if the success response exists
        if (status === 'success') {

            // Show pagination
            $('.main .pagination').fadeIn('slow');

            // Display the pagination
            Main.pagination.page = data.page;
            Main.show_pagination('.main', data.total);

            // Groups var
            var groups = '';

            // List 10 groups
            for (var c = 0; c < data.groups.length; c++) {

                groups += '<li>'
                            + '<div class="row">'
                                + '<div class="col-11">'
                                    + '<a href="' + url + 'user/app/chatbot?p=suggestions&group=' + data.groups[c].group_id + '" class="show-group">'
                                        + '<i class="lni-line-spacing"></i>'
                                        + data.groups[c].group_name
                                    + '</a>'
                                + '</div>'
                                + '<div class="col-1">'
                                    + '<div class="btn-group">'
                                        + '<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                                            + '<i class="icon-arrow-down"></i>'
                                        + '</button>'
                                        + '<div class="dropdown-menu dropdown-menu-action">'
                                            + '<a href="#" class="delete-group" data-id="' + data.groups[c].group_id + '">'
                                                + '<i class="icon-trash"></i>'
                                                + data.words.delete
                                            + '</a>'
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                            + '</div>'
                        + '</li>'

            }

            // Display groups
            $('.main .suggestions-list').html(groups);

        } else {

            // No found groups message
            var message = '<li class="found-results">'
                    + data.message
                + '</li>';

            // Display no groups message
            $('.main .suggestions-list').html(message);

        }

    };

    /*
     * Display the groups deletion response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.delete_group = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Load suggestions groups
            Main.load_suggestions_groups(1);

        } else {

            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);

        }

    };

    /*******************************
    FORMS
    ********************************/

    // Load suggestions groups
    Main.load_suggestions_groups(1);

});