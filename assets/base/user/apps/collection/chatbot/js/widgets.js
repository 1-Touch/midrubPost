/*
 * Chatbot Widgets javascript file
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
     * Load 30 days replies stats chart
     * 
     * @since   0.0.8.0
     */
    Main.replies_stats_chart = function () {

        // Create an object with form data
        var data = {
            action: 'dashboard_replies_for_graph'
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'GET', data, 'replies_for_graph');

    };

    /*
     * Load all replies stats chart
     * 
     * @since   0.0.8.0
     */
    Main.total_replies_stats_chart = function () {

        // Create an object with form data
        var data = {
            action: 'dashboard_total_replies_for_graph'
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'GET', data, 'total_replies_stats_chart');

    };

    /*******************************
    ACTIONS
    ********************************/

    /*
     * Show menu
     */
    $('header .toggle-btn').click(function (e) {
        e.preventDefault();

        // Load 30 days replies stats chart
        Main.replies_stats_chart();

        // Load total replies stats chart
        Main.total_replies_stats_chart();
        
    });

    /*******************************
    RESPONSES
    ********************************/

    /*
     * Generate a 30 days Graph
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
                    label: data.words.number_bot_activities,
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

    /*
     * Generate all time Graph
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.total_replies_stats_chart = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Get Id
            var ctx = document.getElementById('pi-chart').getContext('2d');

            // Labels array
            var labels = [];

            // Replies array
            var replies = [];

            // List replies
            for ( var r = 0; r < data.replies.length; r++ ) {

                // Get source
                var source = data.replies[r].source.replace('_', ' ');

                // Set uppercase
                var upp = source.charAt(0).toUpperCase() + source.slice(1);

                // Set label
                labels.push(upp);

                // Set number
                replies.push(data.replies[r].number);                

            }

            // Draw Chart
            var myChart = new Chart(ctx, {
              type: 'pie',
              data: {
                labels: labels,
                datasets: [{
                  backgroundColor: [
                    "#fad3d1",
                    "#9cc2f4"
                  ],
                  data: replies
                }]
              }
            });

        }

    };

    /*******************************
    FORMS
    ********************************/

    // Load 30 days replies stats chart
    Main.replies_stats_chart();

    // Load total replies stats chart
    Main.total_replies_stats_chart();

});