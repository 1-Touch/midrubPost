/*
 * Chatbot Audit Logs javascript file
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
     * Load replies stats chart
     * 
     * @since   0.0.8.0
     */
    Main.replies_stats_chart = function () {

        // Create an object with form data
        var data = {
            action: 'replies_for_graph',
            page_id: $('.chatbot-select-stats-facebook-page').attr('data-id')
        };

        // Set CSRF
        data[$('.main .chatbot-page').attr('data-csrf-name')] = $('.main .chatbot-page').attr('data-csrf-hash');

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'replies_for_graph');

    };

    /*
     * Load replies by popularity
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.8.0
     */
    Main.replies_by_popularity = function (page) {

        // Create an object with form data
        var data = {
            action: 'replies_by_popularity',
            page_id: $('.main .chatbot-select-keywords-facebook-page').attr('data-id'),
            page: page
        };

        // Set CSRF
        data[$('.main .chatbot-page').attr('data-csrf-name')] = $('.main .chatbot-page').attr('data-csrf-hash');

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'replies_by_popularity');

    };

    /*******************************
    ACTIONS
    ********************************/

    /*
     * Search for Facebook Pages in the Keywords Stats
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('keyup', '.main .chatbot-search-for-stats-facebook-page', function (e) {
        e.preventDefault();

        // Create an object with form data
        var data = {
            action: 'load_connected_pages',
            key: $(this).val()
        };

        // Set CSRF
        data[$('.main .chatbot-page').attr('data-csrf-name')] = $('.main .chatbot-page').attr('data-csrf-hash');

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'facebook_pages_list_graph');

    });

    /*
     * Search for Facebook Pages in Graph Stats
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('keyup', '.main .chatbot-search-for-keywords-facebook-page', function (e) {
        e.preventDefault();

        // Create an object with form data
        var data = {
            action: 'load_connected_pages',
            key: $(this).val()
        };

        // Set CSRF
        data[$('.main .chatbot-page').attr('data-csrf-name')] = $('.main .chatbot-page').attr('data-csrf-hash');

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'facebook_pages_list');

    });

    /*
     * Get Facebook Pages for Keywords Stats
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main .chatbot-select-keywords-facebook-page', function (e) {
        e.preventDefault();

        // Create an object with form data
        var data = {
            action: 'load_connected_pages'
        };

        // Set CSRF
        data[$('.main .chatbot-page').attr('data-csrf-name')] = $('.main .chatbot-page').attr('data-csrf-hash');

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'facebook_pages_list');

    });

    /*
     * Get Facebook Pages for Graph Stats
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main .chatbot-select-stats-facebook-page', function (e) {
        e.preventDefault();

        // Create an object with form data
        var data = {
            action: 'load_connected_pages'
        };

        // Set CSRF
        data[$('.main .chatbot-page').attr('data-csrf-name')] = $('.main .chatbot-page').attr('data-csrf-hash');

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'facebook_pages_list_graph');

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

            case 'popularity-replies':

                // Load replies by popularity
                Main.replies_by_popularity(page);

                break;

        }

        // Display loading animation
        $('.page-loading').fadeIn('slow');

    });

    /*
     * Change dropdown option
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */ 
    $( document ).on( 'click', '.main .chatbot-page .dropdown-menu a', function (e) {
        e.preventDefault();
        
        // Get Dropdown's ID
        var id = $(this).attr('data-id');
        
        // Set id
        $(this).closest('.dropdown').find('.btn-secondary').attr('data-id', id);

        // Set specifi text
        $(this).closest('.dropdown').find('.btn-secondary').html($(this).html());

        // Reload the results
        if ( $(this).closest('ul').hasClass('chatbot-keywords-stats-pages-list') ) {

            // Load replies by popularity
            Main.replies_by_popularity(1);

        } else {

            // Load replies stats chart
            Main.replies_stats_chart();

        }

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });

    /*******************************
    RESPONSES
    ********************************/

    /*
     * Display Facebook Pages for Keywords Stats
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.facebook_pages_list = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Pages var
            var pages = '';
    
            // List 10 pages
            for (var c = 0; c < data.pages.length; c++) {
    
                pages += '<li class="list-group-item">'
                    + '<a href="#" data-id="' + data.pages[c].network_id + '">'
                        + data.pages[c].user_name
                    + '</a>'
                + '</li>';
    
            } 

            // Display Facebook Pages
            $('.main .chatbot-keywords-stats-pages-list').html(pages);

        } else {

            // No found pages message
            var message = '<li class="no-results">'
                    + data.message
                + '</li>';

            // Display no pages message
            $('.main .chatbot-keywords-stats-pages-list').html(message);

        }

    };

    /*
     * Display Facebook Pages for Graph Stats
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.facebook_pages_list_graph = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Pages var
            var pages = '';
    
            // List 10 pages
            for (var c = 0; c < data.pages.length; c++) {
    
                pages += '<li class="list-group-item">'
                    + '<a href="#" data-id="' + data.pages[c].network_id + '">'
                        + data.pages[c].user_name
                    + '</a>'
                + '</li>';
    
            } 

            // Display Facebook Pages
            $('.main .chatbot-stats-pages-list').html(pages);

        } else {

            // No found pages message
            var message = '<li class="no-results">'
                    + data.message
                + '</li>';

            // Display no pages message
            $('.main .chatbot-stats-pages-list').html(message);

        }

    };

    /*
     * Display Replies By Popularity
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.replies_by_popularity = function (status, data) {

        // Hide pagination
        $('.main .table-responsive .pagination').hide();

        // Verify if the success response exists
        if (status === 'success') {

            // Show pagination
            $('.main .table-responsive .pagination').fadeIn('slow');

            // Display the pagination
            Main.pagination.page = data.page;
            Main.show_pagination('.main .table-responsive', data.total);

            // Replies
            var replies = '';

            // List all replies
            for ( var r = 0; r < data.replies.length; r++ ) {

                // Set the group span
                var span = '<span class="span-group">'
                                + 'group'
                            + '</span>';

                // Verify if the reply's response is text
                if ( data.replies[r].type === '1' ) {

                    // Set the text span
                    span = '<span class="span-text">'
                                + 'text'
                            + '</span>';
                    
                }

                // Set reply to the list
                replies += '<tr>'
                            + '<td>'
                                + '<i class="lni-line-double"></i>'
                                + data.replies[r].question
                            + '</td>'
                            + '<td>'
                                + span
                            + '</td>'
                            + '<td>'
                                + data.replies[r].number
                            + '</td>'
                        + '</tr>';
            
            }

            // Display the replies
            $('.main .table-responsive tbody').html(replies);

        } else {

            // Set no replies message
            var no_replies = '<tr>'
                            + '<td colspan="4">'
                                + data.message
                            + '</td>'
                        + '</tr>';

            // Display no replies message
            $('.main .table-responsive tbody').html(no_replies);

        }

    };

    /*
     * Generate a Graph
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.replies_for_graph = function (status, data) {

        // Labels array
        var labels = [];

        // Count array
        var count = [];
        
        // Colors array
        var colors = [];

        // Get the graph id
        var ctx = document.getElementById('replies-stats-chart');

        // Verify if the success response exists
        if (status === 'success') {
            
            // List replies
            for ( var r = 0; r < data.replies.length; r++ ) {

                // Explode date
                var dat = data.replies[r].datetime.split('-');

                // Set date
                labels.push(dat[2] + '/' + dat[1]);

                // Set count
                count.push(data.replies[r].number);

                // Set color
                colors.push('rgba(75, 192, 192, 0.2)');

            }
            
        } else {

            var date = new Date();

            var day = (date.getDate() < 10)?'0'+date.getDate():date.getDate();

            var month = ((date.getMonth()+1) < 10)?'0' + (date.getMonth()+1):(date.getMonth()+1);

            // Set date
            labels.push(day + '/' + month);

            // Set count
            count.push('0');

            // Set color
            colors.push('rgba(75, 192, 192, 0.2)');

        }
        
        // Generate and display Graph
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: data.words.number_bot_replies,
                    data: count,
                    backgroundColor: colors,
                    borderColor: colors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: false,
                scales: {
                    xAxes: [{
                        categoryPercentage: .35,
                        barPercentage: .7,
                        display: !0,
                        scaleLabel: {
                            display: !1,
                            labelString: "Month"
                        },
                        gridLines: !1,
                        ticks: {
                            display: !0,
                            beginAtZero: !0,
                            fontColor: "#373f50",
                            fontSize: 13,
                            padding: 10
                        }
                    }],
                    yAxes: [{
                        categoryPercentage: .35,
                        barPercentage: .2,
                        display: !0,
                        scaleLabel: {
                            display: !1,
                            labelString: "Value"
                        },
                        gridLines: {
                            color: "#c3d1e6",
                            drawBorder: !1,
                            offsetGridLines: !1,
                            drawTicks: !1,
                            borderDash: [3, 4],
                            zeroLineWidth: 1,
                            zeroLineColor: "#c3d1e6",
                            zeroLineBorderDash: [3, 4]
                        },
                        ticks: {
                            max: 70,
                            stepSize: 10,
                            display: !0,
                            beginAtZero: !0,
                            fontColor: "#7d879c",
                            fontSize: 13,
                            padding: 10
                        }

                    }]

                },

                title: {
                    display: !1
                },
                hover: {
                    mode: "index"
                },
                tooltips: {
                    enabled: !0,
                    intersect: !1,
                    mode: "nearest",
                    bodySpacing: 5,
                    yPadding: 10,
                    xPadding: 10,
                    caretPadding: 0,
                    displayColors: !1,
                    backgroundColor: "#95aac9",
                    titleFontColor: "#ffffff",
                    cornerRadius: 4,
                    footerSpacing: 0,
                    titleSpacing: 0
                },
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 5,
                        bottom: 5
                    }

                }

            }

        });

    };

    /*******************************
    FORMS
    ********************************/

    // Load replies stats chart
    Main.replies_stats_chart();

    // Load replies by popularity
    Main.replies_by_popularity(1);

});