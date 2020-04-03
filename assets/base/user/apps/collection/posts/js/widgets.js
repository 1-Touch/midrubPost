/*
 * Widgets javascript file
*/

jQuery(document).ready( function ($) {
    'use strict';
    
    /*
     * Get the website's url
     */
    var url =  $('meta[name=url]').attr('content');
    
    /*******************************
    METHODS
    ********************************/
   
    /*
     * Load published posts
     * 
     * @since   0.0.7.0
     */
    Main.dashboard_get_published_posts = function () {
        
        var data = {
            action: 'dashboard_get_published_posts'
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'dashboard_get_published_posts');
        
    };
    
    /*******************************
    RESPONSES
    ********************************/
   
     /*
     * Display graph
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.0
     */
    Main.methods.dashboard_get_published_posts = function ( status, data ) {

        var labels = [];
        var values = [];
        var rss_values = []; 

        for ( var d = 29; d > -1; d-- ) {

            var date = new Date();
            if ( d > 0 ) {
                date.setDate(date.getDate() - d);
            }
            var day = (date.getDate() < 10)?'0'+date.getDate():date.getDate();
            var month = ((date.getMonth()+1) < 10)?'0' + (date.getMonth()+1):(date.getMonth()+1);

            if ( data.posts ) {

                var keys = Object.keys(data.posts);
                var cp = 0;
                
                for ( var p = 0; p < keys.length; p++ ) {

                    if ( keys[p] === day + '/' + month ) {

                        // Add label
                        labels.push(keys[p]);

                        // Add values
                        values.push(data.posts[keys[p]]);
                        
                        cp++;

                    }

                }
                
                if ( cp < 1 ) {
                    values.push('0');
                }

            } else {
                values.push('0');
            }

            if ( data.rss_posts ) {

                var rss_keys = Object.keys(data.rss_posts);

                var cr = 0;
                for ( var f = 0; f < rss_keys.length; f++ ) {

                    if ( rss_keys[f] === day + '/' + month ) {

                        if ( !$.inArray(rss_keys[f], labels) ) {

                            // Add label
                            labels.push(rss_keys[f]);

                        }

                        // Add values
                        rss_values.push(data.rss_posts[rss_keys[f]]);
                        
                        cr++;

                    }

                }
                
                if ( cr < 1 ) {
                    rss_values.push('0');
                }

            } else {
                
                rss_values.push('0');
                
            }
            
            if ( labels.indexOf(day + '/' + month) < 0 ) {
                labels.push(day + '/' + month);
            }

        }
        
        var timeFormat = 'MM/DD/YYYY HH:mm';
        
        var color = Chart.helpers.color;
        var config = {
            scaleLineColor: "rgba(0,0,0,0)",
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: data.words.posts,
                        backgroundColor: "#54C1F2",
                        data: values
                    }, {
                        label: data.words.rss_posts,
                        backgroundColor: "#ffb463",
                        data: rss_values
                    }
                ]
            },
            options: {
                legend: {
                    position: "bottom"
                },
                scales: {
                    yAxes: [{
                            ticks: {
                                padding: 20,
                                fontColor: "rgba(0,0,0,0.5)",
                                font: "14px Arial",
                                beginAtZero: true,
                                maxTicksLimit: 5
                            },
                            gridLines: {
                                drawBorder: !1,
                                zeroLineColor: "transparent"
                            },
                            categoryPercentage: .8,
                            barPercentage: .9,
                            offset: !0
                        }],
                    xAxes: [{
                            gridLines: {
                                color: 'transparent',
                                drawOnChartArea: false
                            },
                            ticks: {
                                fontColor: "rgba(0,0,0,0.5)",
                                font: "14px Arial"
                            },
                            barPercentage: 0.5
                        }]
                },
                legend: {
                    display: false
                }
            }
        };
        
        Chart.plugins.register({
            beforeDraw: function (chartInstance) {
                var ctx = chartInstance.chart.ctx;
                ctx.fillStyle = "white";
                ctx.fillRect(0, 0, chartInstance.chart.width, chartInstance.chart.height);
            }
        });
        
        var ctx = document.getElementById('canvas').getContext('2d', {alpha: false});
        window.myLine = new Chart(ctx, config);

    };
    
    /*
     * Display post response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.0
     */
    Main.methods.dashboard_post_delete_response = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            var data = {
                action: 'dashboard_scheduled_posts'
            };

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'dashboard_scheduled_posts');
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };
    
    /*
     * Display scheduled posts
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.0
     */
    Main.methods.dashboard_scheduled_posts = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Define all_posts variable
            var all_posts = '';
            
            for ( var p = 0; p < data.posts.length; p++ ) {
                
                // Set post content
                var text = data.posts[p].body.substring(0, 45) + ' ...';
                
                // Set date
                var date = data.posts[p].sent_time;
                
                // Set time
                var gettime = Main.calculate_time(date, data.date);
                
                all_posts += '<li>'
                                + '<div class="row">'
                                    + '<div class="col-xl-8 col-6">'
                                        + '<h4>'
                                            + '<i class="icon-clock"></i>'
                                            + text
                                        + '</h4>'
                                        + '<p>' + gettime + '</p>'
                                    + '</div>'
                                    + '<div class="col-xl-4 col-6 text-right">'
                                        + '<a href="#" class="btn btn-outline-info delete-scheduled-post" data-id="' + data.posts[p].post_id + '">' + data.delete_btn + '</a>'
                                    + '</div>'                                                            
                                + '</div>'
                            + '</li>';
                
            }
            
            $('.schedule-list .col-xl-12').html('<ul>' + all_posts + '</ul>');
            
        } else {
            
            $('.schedule-list .col-xl-12').html('<p class="no-results-found">' + data.message + '</p>');
            
        }

    };    
    
    /*******************************
    ACTIONS
    ********************************/
   
    /*
     * Delete post
     * 
     * @since   0.0.7.0
     */
    $(document).on('click', '.schedule-list .delete-scheduled-post', function (e) {
        e.preventDefault();

        // Get post id
        var post_id = $(this).attr('data-id');
        
        var data = {
            action: 'history_delete_post',
            post_id: post_id
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'dashboard_post_delete_response');
        
    });
    
    // Get published posts
    Main.dashboard_get_published_posts();
    
});