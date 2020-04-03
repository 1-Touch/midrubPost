/*
 * Main javascript file
*/

jQuery(document).ready( function ($) {
    'use strict';
    
    /*
     * Get the website's url
     */
    var url =  $('meta[name=url]').attr('content'), intervalInt;
    
    /*******************************
    METHODS
    ********************************/
   
    /*
     * Load tabs
     * 
     * @since   0.0.7.5
     */
    Main.stream_load_tabs = function () {

        setInterval(function() {

            let new_tabs_list = $( document ).find( '.main .stream-tabs-list li' );

            if ( new_tabs_list.length > 0 ) {

                var time = Math.ceil(Date.now()/1000);

                for ( var n = 0; n < new_tabs_list.length; n++ ) {

                    // Get id
                    let id = $(new_tabs_list[n]).find('a').attr('aria-controls');

                    // Get refresh interval
                    let interval = parseInt($( '.main #' + id ).attr( 'data-refresh' ));
                    
                    // Get last refreshed
                    let refreshed = $( '.main #' + id ).attr( 'data-refreshed' );

                    if ( interval > 0 ) {

                        console.log(time);
                        console.log((time + (60 * interval)));

                        // Verify if refreshed exists
                        if ( refreshed ) {

                            if ( parseInt(refreshed) < time ) {

                                // Set refresh time
                                $( '.main #' + id ).attr( 'data-refreshed', (time + (60 * interval)) );

                                // Refresh tab
                                Main.display_tab_streams($( '.main #' + id ).attr( 'data-tab' ), 1);

                                break;

                            }

                        } else {

                            // Set refresh time
                            $( '.main #' + id ).attr( 'data-refreshed', (time + (60 * interval)) );

                            // Refresh tab
                            Main.display_tab_streams($( '.main #' + id ).attr( 'data-tab' ), 1);

                            break;

                        }

                    }

                }
            
            }

        }, 10000);
        
    };
    
    /*
     * Integrate tabs
     * 
     * @since   0.0.7.5
     */
    Main.integrate_tabs = function () {
        
        var tabs_list = $( document ).find( '.main .stream-tabs-list li' );
        
        var tabs = $( document ).find( '.main .stream-tabs-list' );

        if ( tabs_list.length > 0 ) {
            
            var total_width = 0;
            var hidden = 0;
            
            for ( var t = 0; t < tabs_list.length; t++ ) {
                
                total_width = total_width + $(tabs_list[t]).width();
                
                if ( $($(tabs_list[t]).find('a').attr('href')).find('.stream-mark-seen-item-active').length > 0 ) {
                    
                    $(tabs_list[t]).find('small').text($($(tabs_list[t]).find('a').attr('href')).find('.stream-mark-seen-item-active').length);
                    $(tabs_list[t]).find('small').css('display', 'inline-block');
                    
                } else {
                    
                    $(tabs_list[t]).find('small').text('');
                    $(tabs_list[t]).find('small').css('display', 'none');
                    
                }
 
                if ( total_width > ( tabs.width() - 80 ) ) {
                    
                    $(tabs_list[t]).addClass('d-none');
                    hidden++;
                    
                } else {
                    
                    $(tabs_list[t]).removeClass('d-none');
                    
                }
                
            }
            
            if ( hidden > 0 ) {
                
                // Add arrow down
                var more_tabs = '<li class="nav-item dropdown">'
                                    + '<a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownTabs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                                        + '<i class="fas fa-angle-down"></i>'
                                    + '</a>';
                        
                var tabs_hidden = $( document ).find( '.main .stream-tabs-list li.d-none' );

                var list = '';

                for ( var h = 0; h < tabs_hidden.length; h++ ) {
                    
                    list += $(tabs_hidden[h]).html();

                }
                        
                more_tabs += '<div class="dropdown-menu dropdownTabs" aria-labelledby="dropdownTabs" x-placement="bottom-start">'
                                + list
                            + '</div>';
                    
                more_tabs += '</li>'; 
                
                $( document ).find( '.main .stream-tabs-list' ).append(more_tabs);
                
            }
            
        }
        
    };
    
    /*
     * Load tabs
     * 
     * @param integer type contains the connection's type
     * @param array value contains the connection's information
     * 
     * @since   0.0.7.5
     */
    Main.stream_connection = function (type, value) {
        
        switch ( type ) {
            
            case 1:
                
                var display_hidden_content = '';
                
                if ( typeof value.display_hidden_content !== 'undefined' ) {
                    display_hidden_content = ' display-hidden-content';
                }
                
                var hidden_content = '';
                
                if ( typeof value.hidden_content !== 'undefined' ) {
                    hidden_content = '<div class="row hidden-content-area">' + value.hidden_content + '</div>';
                }                

                var data = '<div class="row">'
                                + '<div class="col-xl-7">'
                                    + '<div class="row">'
                                        + '<div class="col-xl-8 col-sm-8 col-8 input-group stream-connect-new-accounts-search">'
                                            + '<div class="input-group-prepend">'
                                                + '<i class="icon-magnifier"></i>'
                                            + '</div>'
                                            + '<input type="text" class="form-control stream-connect-search-for-accounts" placeholder="' + value.placeholder + '" data-network="' + value.network + '">'
                                            + '<button type="button" class="stream-cancel-search-for-accounts">'
                                                + '<i class="icon-close"></i>'
                                            + '</button>'
                                        + '</div>'
                                        + '<div class="col-xl-4 col-sm-4 col-4">'
                                            + '<button type="button" class="stream-connect-new-account' + display_hidden_content + '">'
                                                + value.new_account
                                            + '</button>'
                                        + '</div>'
                                    + '</div>'
                                    + hidden_content
                                    + '<div class="row">'
                                        + '<div class="col-xl-12 new-stream-accounts-list">'
                                            + value.expired_accounts
                                            + value.active_accounts
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                                + '<div class="col-xl-5">'
                                    + '<div class="col-xl-12 manage-accounts-network-instructions">'
                                        + '<ul>'
                                            + value.instructions
                                        + '</ul>'
                                    + '</div>'
                                + '</div>'                           
                            + '</div>';
                    
                return data;
                
            case 2:

                var data = '<div class="row">'
                                + '<div class="col-xl-7">'
                                    + '<div class="row">'
                                        + '<div class="col-xl-8 col-sm-8 col-8 input-group stream-connect-stream-data">'
                                            + '<div class="input-group-prepend">'
                                                + value.icon
                                            + '</div>'
                                            + '<input type="text" class="form-control stream-connect-stream-data-input" placeholder="' + value.placeholder + '" data-network="' + value.network + '">'
                                        + '</div>'
                                        + '<div class="col-xl-4 col-sm-4 col-4">'
                                            + '<button type="button" class="stream-connect-stream-data-save">'
                                                + value.save
                                            + '</button>'
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                                + '<div class="col-xl-5">'
                                    + '<div class="col-xl-12 manage-accounts-network-instructions">'
                                        + '<ul>'
                                            + value.instructions
                                        + '</ul>'
                                    + '</div>'
                                + '</div>'                           
                            + '</div>';
                    
                return data;
            
        }
        
    };
    
    /*
     * Display stream
     * 
     * @param array data contains the stream's data
     * 
     * @since   0.0.7.5
     */
    Main.display_stream = function ( data ) {

        // Create stream's content
        var content = '<div class="panel panel-default">'
                        + '<div class="panel-heading">'
                            + data.stream.header
                        + '</div>'
                        + '<div class="panel-body">'
                            + data.stream.content
                        + '</div>'
                        + '<div class="panel-footer">'
                            + data.stream.footer
                        + '</div>'                
                    + '</div>';
            
        // Search for covers
        var covers = $( '.main .tab-pane.active .stream-all-tab-streams .stream-single' );
        
        // List all covers
        for ( var c = 0; c < covers.length; c++ ) {
            
            if ( $(covers[c]).hasClass('stream-cover') ) {
                
                $('.main .tab-pane.active .stream-all-tab-streams .stream-single').eq(c).html(content);

                $('.main .tab-pane.active .stream-all-tab-streams .stream-single').eq(c).removeClass('stream-cover');
                
                $('.main .tab-pane.active .stream-all-tab-streams .stream-single').eq(c).attr('data-stream', data.stream_id);

                // Save the stream order
                Main.save_stream_order(c, data.stream_id);

                setTimeout(function() {

                    // Close popup
                    $('#create-new-stream').modal('hide');
                    Main.display_tab_streams($( '.main .stream-content #nav-tabContent-streams > .tab-pane.active ' ).attr( 'data-tab' ), 1);

                }, 1000);
        
                break;
                
            }
            
        }
        
    };
    
    /*
     * Save the stream order
     * 
     * @param integer order_id contains the order's id
     * @param integer stream_id contains the stream's id
     * 
     * @since   0.0.7.5
     */
    Main.save_stream_order = function ( order_id, stream_id ) {
        
        var data = {
            action: 'stream_save_stream_order',
            stream_id: stream_id,
            order_id: order_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'GET', data, 'stream_save_stream_order');  
        
    };
    
    /*
     * Display Tab's streams
     * 
     * @param integer tab_id contains the tab's id
     * @param integer load contains the option to load animation
     * 
     * @since   0.0.7.5
     */
    Main.display_tab_streams = function ( tab_id, load ) {
        
        // Prepare data to send
        var data = {
            action: 'stream_connect_tab_steams',
            tab_id: tab_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'GET', data, 'stream_connect_tab_steams');
        
        if ( !load ) {
        
            // Display loading animation
            $('.page-loading').fadeIn('slow');
            
        }
        
    };
    
    /*
     * Reload accounts
     * 
     * @since   0.0.7.6
     */
    Main.reload_accounts = function () {

        if ( $('.main #nav-connect-stream').hasClass('active') ) {
        
            // Get network
            var network = $('.main #nav-connect-stream .stream-connect-search-for-accounts').attr('data-network');
            
            // Empty the search field
            $('.main #nav-connect-stream .stream-connect-search-for-accounts').val('');

            var data = {
                action: 'stream_search_for_social_accounts',
                network: network,
                key: $('.main #nav-connect-stream .stream-connect-search-for-accounts').val()
            };

            // Set CSRF
            data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_search_for_social_accounts'); 
        
        } else {
            
            var network = $('#nav-accounts-manager').find('.network-selected a').attr('data-network');

            $('.manage-accounts-all-accounts').empty();

            Main.account_manager_get_accounts(network, 'accounts_manager');
            
        }
        
    };
    
    /*
     * Load graph
     * 
     * @param integer tab_id contains the tab's id
     * 
     * @since   0.0.7.6
     */
    Main.load_graph = function ( tab_id ) {
        
        var streams = $( '.main .stream-content #nav-tabContent-streams > #tab-' + tab_id + ' .stream-all-tab-streams > .row' ).find('.stream-template-graph');
        
        if ( streams.length > 0 ) {
            
            for ( var s = 0; s < streams.length; s++ ) {
        
                var labels = [];

                var values = [];

                var backgrounds = [];

                var borders = [];

                var data = JSON.parse(atob($(streams[s]).attr('data-content')));

                var densityCanvas = document.getElementById($(streams[s]).attr('id'));

                for ( var l = 0; l < data.length; l++ ) {

                    // Add label
                    labels.push(data[l].name);

                    // Add values
                    values.push(data[l].value);

                    // Add color
                    backgrounds.push(data[l].background_color);

                    // Add color
                    borders.push(data[l].border_color);

                }

                var densityData = {
                    label: 'Value',
                    data: values,
                    backgroundColor: backgrounds,
                    borderColor: borders,
                    borderWidth: 0,
                    hoverBorderWidth: 0
                };

                var chartOptions = {
                    scales: {
                        yAxes: [{
                            ticks: {
                                padding: 5,
                                fontColor: "rgba(0,0,0,0.7)",
                                font: "14px Arial",
                                beginAtZero: true,
                                suggestedMax: 10
                            },
                            gridLines: {
                                color: 'transparent'
                            },
                            categoryPercentage:.8,
                            offset:!0,
                            barPercentage: 0.7
                        }],
                        xAxes: [{
                            gridLines: {
                                zeroLineWidth: 0.3,
                                color: "rgba(0, 0, 0, 0.05)"
                            },
                            ticks: {
                                fontColor: "rgba(0,0,0,0.7)",
                                font: "14px Arial",
                                beginAtZero: true,
                                suggestedMax: 10,
                                zeroLineColor: "rgba(0, 0, 0, 0.05)"
                            },
                            barPercentage: 0.7
                        }]
                    },
                    legend: {
                        display: false
                    },
                    elements: {
                        rectangle: {
                            borderSkipped: 'left'
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

                var barChart = new Chart(densityCanvas, {
                    type: 'horizontalBar',
                    data: {
                        labels: labels,
                        datasets: [densityData],
                    },
                    options: chartOptions
                });
                
            }
            
        }
        
    };
    
    /*
     * Load available networks
     * 
     * @since   0.0.7.5
     */
    Main.account_manager_load_networks = function () {
        
        var data = {
            action: 'account_manager_load_networks'
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'GET', data, 'account_manager_load_networks');
        
    };
    
    /*
     * Load network's accounts
     * 
     * @since   0.0.7.5
     * 
     * @param string type contains the tab name
     * 
     * @param string network contains the network's name
     */
    Main.account_manager_get_accounts = function (network, type) {
        
        var data = {
            action: 'account_manager_get_accounts',
            network: network,
            type: type
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'GET', data, 'account_manager_get_accounts');
        
    };
    
    /*
     * Reload accounts in the select list
     * 
     * @since   0.0.7.5
     */
    Main.reload_accounts_list = function () {
        
        var data = {
            action: 'stream_search_accounts',
            key: $( '.stream-share-search-for-accounts' ).val()
        };
        
        // Set CSRF
        data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_accounts_results_by_search');
        
    };
    
    /*
     * Verify if a text has urls
     * 
     * @param string text contains the text
     * 
     * @since   0.0.7.5
     */ 
    Main.verify_for_url = function(text) {
        
        var urlRegex = /(https?:\/\/[^\s]+)/g;

        return text.replace(urlRegex, function (url) {
            
            var links = '';

            var urls = url.split('http');
            
            for ( var d = 0; d < urls.length; d++ ) {
                
                var verify = urls[d].split('<br>');

                var link = verify[0];

                if ( link ) {
                    
                    links += 'http' + link + '<br>';
                    
                    if ( typeof verify[1] !== 'undefined' ) {
                        links += verify[1];
                    }

                    if (typeof Main.selected_post_url === 'undefined' && (d + 1) === urls.length ) {

                        Main.selected_post_url = 'http' + link;

                    } else {

                        if ( Main.selected_post_url !== 'http' + link && (d + 1) === urls.length ) {
                            Main.selected_post_url = 'http' + link;
                        }

                    }
                    
                }
                
            }
            
            return links;
            
        });

    };
    
    /*******************************
    ACTIONS
    ********************************/
   
    /*
     * Search for accounts in the new stream
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */ 
    $( document ).on( 'keyup', 'main .stream-connect-search-for-accounts', function (e) {
        e.preventDefault();
            
        // Get network
        var network = $(this).attr('data-network');

        // Get search keys
        var key = $(this).val();

        // Display cancel search icon
        $(this).closest( '.row' ).find( '.stream-cancel-search-for-accounts' ).fadeIn( 'slow' );

        var data = {
            action: 'stream_search_for_social_accounts',
            network: network,
            key: key
        };

        // Set CSRF
        data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_search_for_social_accounts');  
        
    });
    
    /*
     * Search for accounts in the accounts manager popup
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */ 
    $( document ).on( 'keyup', 'main .accounts-manager-search-for-accounts', function (e) {
        e.preventDefault();
        
        // Verify if search is in the accounts tab
        if ( $(this).closest('.tab-pane').attr('id') === 'nav-accounts-manager' ) {
            
            // Get network
            var network = $('#nav-accounts-manager').find('.network-selected a').attr('data-network');
            
            // Get search keys
            var key = $('#nav-accounts-manager').find('.accounts-manager-search-for-accounts').val();
            
            // Display cancel search icon
            $(this).closest( '.row' ).find( '.cancel-accounts-manager-search' ).fadeIn( 'slow' );
            
            var data = {
                action: 'account_manager_search_for_accounts',
                network: network,
                key: key,
                type: 'accounts_manager'
            };
            
            // Set CSRF
            data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'account_manager_search_for_accounts');
            
        } else if ( $(this).closest('.tab-pane').attr('id') === 'nav-groups-manager' ) {
            
            // Get network
            var network = $('#nav-groups-manager').find('.network-selected a').attr('data-network');
            
            // Get search keys
            var key = $('#nav-groups-manager').find('.accounts-manager-search-for-accounts').val();
            
            // Display cancel search icon
            $(this).closest( '.row' ).find( '.cancel-accounts-manager-search' ).fadeIn( 'slow' );

            var data = {
                action: 'account_manager_search_for_accounts',
                network: network,
                key: key,
                type: 'groups_manager'
            };
            
            // Set CSRF
            data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'account_manager_search_for_accounts');
            
        }
        
    });
    
    /*
     * Search for accounts
     * 
     * @since   0.0.7.5
     */
    $(document).on('keyup', '.stream-share-search-for-accounts', function () {
        
        if ( $( this ).val() === '' ) {
            
            // Hide cancel search button
            $( '.stream-share-cancel-search-for-accounts' ).fadeOut('slow');
            
        } else {
         
            // Display cancel search button
            $( '.stream-share-cancel-search-for-accounts' ).fadeIn('slow');
            
        }
        
        var data = {
            action: 'stream_search_accounts',
            key: $( this ).val()
        };
        
        // Set CSRF
        data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_accounts_results_by_search');
        
    });
    
    /*
     * Search for groups
     * 
     * @since   0.0.7.5
     */
    $(document).on('keyup', '.stream-share-search-for-groups', function () {
        
        if ( $( this ).val() === '' ) {
            
            // Hide cancel search button
            $( '.stream-share-cancel-search-for-groups' ).fadeOut('slow');
            
        } else {
         
            // Display cancel search button
            $( '.stream-share-cancel-search-for-groups' ).fadeIn('slow');
            
        }
        
        var data = {
            action: 'stream_search_groups',
            key: $( this ).val()
        };
        
        // Set CSRF
        data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_search_groups');
        
    });
    
    /*
     * Detect colors change
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */ 
    $( document ).on( 'change', '.main .stream-settings-input', function (e) {
        e.preventDefault();
        
        if ( $(this).val().toLowerCase() !== $(this).attr('data-color').toLowerCase() ) {
            
            $(this).attr('data-color', $(this).val());
            
            var data = {
                action: 'stream_change_settings_color',
                stream_id: Main.selected_stream_id,
                name: $(this).attr('id'),
                value: $(this).val()
            };

            // Set CSRF
            data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_change_settings_color');  
            
        }

        
    });
    
    /*
     * Detect stream connect
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */ 
    $( document ).on( 'shown.bs.modal', '.main #create-new-stream', function (e) {
        e.preventDefault();
        
        // Show default tab
        $('.main #create-new-stream a[href="#nav-all-streams"]').tab('show')
        
    });
   
    /*
     * Select tab's icon
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */
    $(document).on('click', '.main .stream-select-tab-icon a', function (e) {
        e.preventDefault();

        // Get the icon
        var icon = $( this ).attr('href');

        // Set selected icon
        $('.main .stream-selected-tab-icon i').attr('class', icon);
        
        // Remove selected class from the table
        $('.main .stream-select-tab-icon td').removeClass('icon-selected');
        
        // Add selected class
        $(this).closest('td').addClass('icon-selected');
        
    });
    
    /*
     * Remove all other active tabs
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */
    $(document).on('click', '.main .dropdownTabs a', function (e) {
        e.preventDefault();
        
        // Remove active class
        $('.main .dropdownTabs a').removeClass('active');
        $('.main .dropdownTabs a').attr('aria-selected', 'false');
        
        // Add active class
        $( this ).addClass('active');
        $( this ).attr('aria-selected', 'true');        
        
    });
    
    /*
     * Change tab
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */
    $(document).on('click', '.main #nav-connect-stream-tab', function (e) {
        e.preventDefault();
        
        // Set template name
        Main.template_name = $( this ).attr( 'data-network' );
        
        // Prepare data to send
        var data = {
            action: 'stream_load_connection_settings',
            template_name: $( this ).attr( 'data-network' )
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'GET', data, 'stream_load_connection_settings');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Cancel search for accounts
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.main .stream-cancel-search-for-accounts', function() {
        
        // Hide cancel search button
        $( '.main .stream-cancel-search-for-accounts' ).fadeOut('slow');
            
        // Empty search for accounts input
        $('.main .stream-connect-search-for-accounts').val('');

        // Prepare data to send
        var data = {
            action: 'stream_search_for_social_accounts',
            network: $( '.main .stream-connect-search-for-accounts' ).attr( 'data-network' ),
            key: $('.main .stream-connect-search-for-accounts').val()
        };
        
        // Set CSRF
        data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_search_for_social_accounts');  
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Connect stream with network
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.main .stream-connect-stream-with-network', function() {
        
        // Prepare data to send
        var data = {
            action: 'stream_connect_new_stream',
            template_name: Main.template_name,
            network: $( this ).closest( '.tab-pane' ).find( '.stream-connect-search-for-accounts' ).attr( 'data-network' ),
            tab_id: $( '.main .stream-content #nav-tabContent-streams > .tab-pane.active ' ).attr( 'data-tab' ),
            network_id: $( this ).attr( 'data-id' )
        };
        
        // Set CSRF
        data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_connect_new_stream');  
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Change the refresh interval
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.main .stream-tab-refresh-interval-options .dropdown-item', function() {
        
        // Get interval
        var interval = $(this).attr('data-interval');
        
        // Get name
        var name = $(this).html();
        
        // Add interval
        $(this).closest('.tab-pane').find('.stream-tab-refresh-interval').attr('data-interval', interval);
        $( '.main .stream-content #nav-tabContent-streams > .tab-pane.active ' ).attr( 'data-refresh', interval );
        
        // Add name
        $(this).closest('.tab-pane').find('.stream-tab-refresh-interval').html(name);
        
        // Prepare data to send
        var data = {
            action: 'stream_tab_refresh',
            tab_id: $( '.main .stream-content #nav-tabContent-streams > .tab-pane.active ' ).attr( 'data-tab' ),
            interval: interval
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'GET', data, 'stream_tab_refresh');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Delete a Streams Tab
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.main .stream-delete-tab-btn', function(e) {
        e.preventDefault();
        
        // Get tab's ID
        var tab_id = $( '.main .stream-content #nav-tabContent-streams > .tab-pane.active ' ).attr( 'data-tab' );
        
        // Prepare data to send
        var data = {
            action: 'stream_delete_tab_streams',
            tab_id: tab_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'GET', data, 'stream_delete_tab_streams');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Open Stream Settings Modal
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.main .stream-open-settings', function(e) {
        e.preventDefault();
        
        // Hide sounds list
        $('.main .stream-settings-sounds').hide();
        
        // Display Settings tab
        $('.main [href="#nav-stream-settings"]').tab('show');
        
        // Get Stream's ID
        var stream_id = $( this ).closest('.stream-single').attr( 'data-stream' );
        
        // Set selected Stream's ID
        Main.selected_stream_id = stream_id;
        
        // Prepare data to send
        var data = {
            action: 'stream_get_setup',
            stream_id: stream_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'GET', data, 'stream_get_setup');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Mark stream as seen
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.main .stream-mark-seen-item', function(e) {
        e.preventDefault();
        
        // Get Stream's ID
        var stream_id = $( this ).closest('.stream-single').attr( 'data-stream' );

        // Prepare data to send
        var data = {
            action: 'stream_mark_seen',
            stream_id: stream_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'GET', data, 'stream_mark_seen');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Delete Stream
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.main .stream-delete-btn', function(e) {
        e.preventDefault();
        
        // Prepare data to send
        var data = {
            action: 'stream_delete_selected_stream',
            stream_id: Main.selected_stream_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'GET', data, 'stream_delete_selected_stream');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Delete Stream Setup
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.main .delete-stream-setup', function(e) {
        e.preventDefault();
        
        // Get setup's id
        var setup_id = $(this).attr('data-id');
        
        // Get template's name
        var template_name = $(this).attr('data-template');
        
        // Get stream's id
        var stream_id = $(this).attr('data-stream-id');   
        
        // Prepare data to send
        var data = {
            action: 'stream_delete_setup',
            setup_id: setup_id,
            template_name: template_name,
            stream_id: stream_id
        };
        
        // Set CSRF
        data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_delete_setup');  
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Detect item action
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.main .stream-item-action', function(e) {
        e.preventDefault();
        
        // Get item's id
        var item_id = $(this).attr('data-id');
        
        // Get item's type
        var item_type = $(this).attr('data-type');
        
        // Get stream's id
        var stream_id = $(this).attr('data-stream');   
        
        // Prepare data to send
        var data = {
            action: 'stream_item_action_link',
            stream_id: stream_id,
            item_id: item_id,
            item_type: item_type
        };
        
        // Set CSRF
        data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_item_action_link');  
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Refresh tab's streams
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.main .stream-tab-refresh', function(e) {
        e.preventDefault();
        
        // Refresh the tab
        Main.display_tab_streams($( '.main .stream-content #nav-tabContent-streams > .tab-pane.active ' ).attr( 'data-tab' ));
        
    });  
    
    /*
     * Show sounds list
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.main .select-a-sound', function(e) {
        e.preventDefault();
        
        $('.main .stream-settings-sounds').toggle('slow');
        
    }); 
    
    /*
     * Select a sound
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.main .stream-select-sound', function(e) {
        e.preventDefault();
        
        // Prepare data to send
        var data = {
            action: 'stream_select_sound_alert',
            stream_id: Main.selected_stream_id,
            name: $(this).attr('name')
        };
        
        // Set CSRF
        data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_select_sound_alert');  
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Select a sound
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */     
    $( document ).on( 'click', '.main .stream-setup-checkbox', function(e) {
        
        // Prepare data to send
        var data = {
            'action': 'stream_update_setup',
            'stream-id': Main.selected_stream_id,
            'name': $(this).attr('name'),
            'stream-template': $(this).attr('data-template')
        };
        
        // Set CSRF
        data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_update_setup');  
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    }); 
    
    /*
     * Display streams by category
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.main .stream-available-stream-categories li a', function(e) {
        e.preventDefault();
        
        // Prepare data to send
        var data = {
            action: 'stream_get_streams_templates',
            category: $(this).attr('data-category')
        };
        
        // Set CSRF
        data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_get_streams_templates');  
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Connect stream based on url
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.main .stream-connect-stream-data-save', function(e) {
        e.preventDefault();
        
        // Get url
        var input_url = $('.main .stream-connect-stream-data-input').val();
        
        // Prepare data to send
        var data = {
            action: 'stream_save_new_stream_with_url',
            template_name: Main.template_name,
            network: $('.main .stream-connect-stream-data-input').attr( 'data-network' ),
            tab_id: $( '.main .stream-content #nav-tabContent-streams > .tab-pane.active ' ).attr( 'data-tab' ),
            input_url: input_url
        };
        
        // Set CSRF
        data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_connect_new_stream');  
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
        
    });
    
    /*
     * Load react form
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.main .stream-item-react', function(e) {
        e.preventDefault();
        
        // Get react type
        var type = $(this).attr('data-type');
        
        // Get stream
        var stream_id = $(this).attr('data-stream');
        
        // Get id 
        var id = $(this).attr('data-id');
        
        // Set template name
        Main.template_name = $( this ).attr( 'data-network' );
        
        // Prepare data to send
        var data = {
            action: 'stream_template_content_single',
            template_name: Main.template_name,
            tab_id: $( '.main .stream-content #nav-tabContent-streams > .tab-pane.active ' ).attr( 'data-tab' ),
            type: type,
            stream_id: stream_id,
            id: id
        };
        
        // Set CSRF
        data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_template_content_single');  
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
        
    });    
    
    /*
     * Connect new account
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.main .stream-connect-new-account', function(e) {
        e.preventDefault();
        
        // Verify if should be displayed hidden content
        if ( $( this ).hasClass('manage-accounts-display-hidden-content') ) {
            $( '.main .manage-accounts-hidden-content' ).fadeIn('slow');
        } 
        
        if ( $( this ).hasClass('display-hidden-content') ) {
            $( '.main .hidden-content-area' ).fadeIn('slow');
        } 
        
        // Get network
        var network = $('.main #nav-connect-stream .stream-connect-search-for-accounts').attr('data-network');
        
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
     * Load streams tab
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.main .tab-stream-load', function(e) {
        e.preventDefault();
        
        // Get refresh interval
        var interval = $( '.main .stream-content #nav-tabContent-streams > .tab-pane.active ' ).attr( 'data-refresh' );

        // Verify if interval exists 
        if ( interval ) {
        
            // Set refresh interval
            $('.main .stream-tab-refresh-interval').html( $('.main .stream-tab-refresh-interval-options .dropdown-item[data-interval="' + interval + '"]').html() );

        } else {

            // Set refresh interval
            $('.main .stream-tab-refresh-interval').html( $('.main .stream-tab-refresh-interval-options .dropdown-item[data-interval="0"]').html() );
            
        }
        
    }); 
    
    /*
     * Display the accounts manager
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */ 
    $( document ).on( 'click', '.main .stream-share-manage-members', function (e) {
        e.preventDefault();
        
        // Verify if accounts manager is open
        if ( $(this).hasClass('accounts-manager-open') ) {
            
            // Hide accounts manager
            $('.stream-share-accounts-manager').fadeOut('slow');
            
            // Remove open class
            $(this).removeClass('accounts-manager-open');
            
        } else {
        
            Main.account_manager_load_networks();

            // Display loading animation
            $('.page-loading').fadeIn('slow');
            
            // Add open class
            $(this).addClass('accounts-manager-open');
            
            // Show accounts manager
            $('.stream-share-accounts-manager').fadeIn('slow');            
        
        }
        
    });
    
    /*
     * Load accounts by network
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */ 
    $( document ).on( 'click', '.main .accounts-manager-available-networks li a', function (e) {
        e.preventDefault();
        
        var network = $(this).attr('data-network');
        
        if ( $('#nav-accounts-manager').hasClass('show') ) {
        
            $('.manage-accounts-all-accounts').empty();

            $('#nav-accounts-manager .accounts-manager-available-networks li').removeClass('network-selected');

            $(this).closest('li').addClass('network-selected');

            Main.account_manager_get_accounts(network, 'accounts_manager');
        
        } else {
            
            $( '.manage-accounts-groups-all-accounts' ).empty();
            
            $('#nav-groups-manager .accounts-manager-available-networks li').removeClass('network-selected');
            
            $(this).closest('li').addClass('network-selected');
            
            Main.account_manager_get_accounts(network, 'groups_manager');
            
        }
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    }); 
    
    /*
     * Connect a new account
     * 
     * @since   0.0.7.5
     */ 
    $(document).on('click', '.main .manage-accounts-new-account', function() {
        
        // Verify if should be displayed hidden content
        if ( $( this ).hasClass('manage-accounts-display-hidden-content') ) {
            $( '.main .manage-accounts-hidden-content' ).fadeIn('slow');
        } 
        
        // Get network
        var network = $('#nav-accounts-manager').find('.network-selected a').attr('data-network');
        
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
     * Delete accounts from the accounts manager popup
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */ 
    $( document ).on( 'click', '.main .accounts-manager-active-accounts-list li a', function (e) {
        e.preventDefault();
            
        // Get the account's id
        var account_id = $(this).attr('data-id');

        var data = {
            action: 'account_manager_delete_accounts',
            account_id: account_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'GET', data, 'account_manager_delete_accounts');
        
    });
    
    /*
     * Cancel accounts manager search
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */ 
    $( document ).on( 'click', '.main .cancel-accounts-manager-search', function (e) {
        e.preventDefault();
            
        // Hide cancel search button
        $(this).closest('.tab-pane').find('.cancel-accounts-manager-search').fadeOut('slow');
        
        // Verify if search is in the accounts tab
        if ( $(this).closest('.tab-pane').attr('id') === 'nav-accounts-manager' ) {
        
            // Get network
            var network = $('#nav-accounts-manager').find('.network-selected a').attr('data-network');

            // Empty the search input
            $('#nav-accounts-manager').find('.accounts-manager-search-for-accounts').val('');
            
            var data = {
                action: 'account_manager_search_for_accounts',
                network: network,
                key: '',
                type: 'accounts_manager'
            };

            // Set CSRF
            data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'account_manager_search_for_accounts');            
            
        } else if ( $(this).closest('.tab-pane').attr('id') === 'nav-groups-manager' ) {
            
            // Get network
            var network = $('#nav-groups-manager').find('.network-selected a').attr('data-network');
            
            // Empty the search input
            $('#nav-groups-manager').find('.accounts-manager-search-for-accounts').val('');

            var data = {
                action: 'account_manager_search_for_accounts',
                network: network,
                key: '',
                type: 'groups_manager'
            };
            
            // Set CSRF
            data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'account_manager_search_for_accounts');
            
        }
        
    });
    
    /*
     * Delete accounts from the accounts manager popup
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */ 
    $( document ).on( 'click', 'main .accounts-manager-groups-select-group .dropdown-menu .dropdown-item', function (e) {
        e.preventDefault();
            
        // Get the group's id
        var group_id = $( this ).attr('data-id');
        
        // Add selected text
        $( 'main .accounts-manager-groups-select-group .btn-secondary' ).html( $( this ).text() );
        $( 'main .accounts-manager-groups-select-group .btn-secondary' ).attr( 'data-id', $( this ).attr('data-id') );
        
        // Remove active class
        $( 'main .accounts-manager-groups-select-group .dropdown-menu .dropdown-item' ).removeClass( 'active' );
        
        // Remove selected accounts
        $( '.main #nav-groups-manager .accounts-manager-groups-active-accounts li' ).removeClass( 'select-account-in-group' );
        
        // Add active class
        $( this ).addClass( 'active' );

        var data = {
            action: 'accounts_manager_groups_available_accounts',
            group_id: group_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'GET', data, 'accounts_manager_groups_available_accounts');
        
    });
    
    /*
     * Delete accounts group
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */ 
    $( document ).on( 'click', 'main .accounts-manager-groups-select-group .accounts-manager-delete-group', function (e) {
        e.preventDefault();
            
        // Get the group's id
        var group_id = $( 'main .accounts-manager-groups-select-group .btn-secondary' ).attr( 'data-id' );
        
        var data = {
            action: 'accounts_manager_groups_delete_group',
            group_id: group_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'GET', data, 'accounts_manager_groups_delete_group');
        
    }); 
    
    /*
     * Add account to group
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */ 
    $( document ).on( 'click', 'main .manage-accounts-groups-all-accounts li a', function (e) {
        e.preventDefault();
        
        // Get account id
        var account_id = $(this).attr('data-id');
        
        // Get the group's id
        var group_id = $( 'main .accounts-manager-groups-select-group .btn-secondary' ).attr( 'data-id' );
        
        var data = {
            action: 'account_manager_add_account_to_group',
            account_id: account_id,
            group_id: group_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'GET', data, 'account_manager_add_account_to_group');
        
    });
    
    /*
     * Remove account from group
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */ 
    $( document ).on( 'click', 'main .create-new-group-form .accounts-manager-groups-available-accounts li a', function (e) {
        e.preventDefault();
        
        // Get account id
        var account_id = $(this).attr('data-id');
        
        // Get the group's id
        var group_id = $( 'main .accounts-manager-groups-select-group .btn-secondary' ).attr( 'data-id' );
        
        var data = {
            action: 'account_manager_remove_account_from_group',
            account_id: account_id,
            group_id: group_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'GET', data, 'account_manager_remove_account_from_group');
        
    });
    
    /*
     * Cancel search for groups
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.stream-share-cancel-search-for-groups', function() {
        
        // Hide cancel search button
        $( '.stream-share-cancel-search-for-groups' ).fadeOut('slow');
            
        $('.stream-share-search-for-groups').val('');

        var data = {
            action: 'stream_search_groups',
            key: $('.stream-share-search-for-groups').val()
        };

        // Set CSRF
        data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_search_groups');
        
    });
    
    /*
     * Cancel search for accounts
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.stream-share-cancel-search-for-accounts', function() {
        
        // Hide cancel search button
        $( '.stream-share-cancel-search-for-accounts' ).fadeOut('slow');
            
        $('.main .stream-share-search-for-accounts').val('');

        var data = {
            action: 'stream_search_accounts',
            key: $('.main .stream-share-search-for-accounts').val()
        };

        // Set CSRF
        data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_accounts_results_by_search');
        
    });    
    
    /*
     * Save the VK token
     * 
     * @param object e with global object
     * 
     * @since   0.0.0.1
     */ 
    $( document ).on( 'click', 'main .save-token', function (e) {
        
        if ( $('#stream-item-share').hasClass('show') ) {

            var $this = $(this);
            var network = $('#nav-accounts-manager').find('.network-selected a').attr('data-network');
            var token = $this.closest('.manage-accounts-hidden-content').find('.token').val();
            var encode = btoa(token);
            encode = encode.replace('/', '-');
            var cleanURL = encode.replace(/=/g, '');

            $.ajax({
                url: url + 'user/save-token/' + network + '/' + cleanURL,
                dataType: 'json',
                type: 'GET',
                success: function (data) {

                    if (data === 1) {

                        $this.closest('.manage-accounts-hidden-content').find('.token').val('');

                        $( '.main .manage-accounts-hidden-content' ).fadeOut('fast');

                        Main.account_manager_get_accounts(network, 'accounts_manager');

                    } else {

                        $this.closest('.manage-accounts-hidden-content').find('.token').val('');

                        // Display alert
                        Main.popup_fon('sube', data, 1500, 2000);

                    }

                },
                error: function (data, jqXHR, textStatus) {
                    console.log(data);
                }

            });
            
        } else {
            
            var $this = $(this);
            var network = $('#nav-connect-stream').find('.stream-connect-search-for-accounts').attr('data-network');
            var token = $this.closest('.hidden-content-area').find('.token').val();
            var encode = btoa(token);
            encode = encode.replace('/', '-');
            var cleanURL = encode.replace(/=/g, '');

            $.ajax({
                url: url + 'user/save-token/' + network + '/' + cleanURL,
                dataType: 'json',
                type: 'GET',
                success: function (data) {

                    if (data === 1) {

                        $this.closest('.hidden-content-area').find('.token').val('');

                        $( '.main .hidden-content-area' ).fadeOut('fast');

                        Main.reload_accounts();

                    } else {

                        $this.closest('.hidden-content-area').find('.token').val('');

                        // Display alert
                        Main.popup_fon('sube', data, 1500, 2000);

                    }

                },
                error: function (data, jqXHR, textStatus) {
                    console.log(data);
                }

            });
            
        }
        
    });
    
    /*
     * Show title field in the composer tab
     * 
     * @since   0.0.7.5
     */
    $(document).on('click', '#stream-item-share .show-title', function () {

        $('#stream-item-share .composer-title').toggle('slow');
        
    });
    
    /*
     * Add stream's item content
     * 
     * @since   0.0.7.5
     */
    $(document).on('click', '.main .stream-item-share', function () {

        if ( $(this).closest('li').find('p[data-type="stream-item-content"]').length > 0 ) {

            $('#stream-item-share .emojionearea-editor').html($(this).closest('li').find('p[data-type="stream-item-content"]').text().trim());

            $('#stream-item-share .new-post').val($(this).closest('li').find('p[data-type="stream-item-content"]').text().trim());
            
        } else if ( $(this).closest('li').find('a[data-type="stream-item-title"]').length > 0 ) {
            
            var text = $(this).closest('li').find('a[data-type="stream-item-title"]').text().trim() + ' ' + $(this).closest('li').find('a[data-type="stream-item-title"]').attr('href');

            $('#stream-item-share .emojionearea-editor').html(text);

            $('#stream-item-share .new-post').val(text);
            
        }
        
        $('#stream-item-share .stream-media-post-save-area').hide();
        
        if ( $(this).closest('li').find('p[data-type="stream-item-media"] img').length > 0 ) {

            $('#stream-item-share .stream-media-post-save-btn').attr('data-url', $(this).closest('li').find('p[data-type="stream-item-media"] img').attr('src'));
            
            $('#stream-item-share .stream-media-post-save-area').show();
            
        }        
        
    });    
    
    /*
     * Select account in the composer tab
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */
    $(document).on('click', '.stream-schedule-accounts-list li a', function (e) {
        e.preventDefault();
        
        // Verify if selected_post_accounts is defined
        if ( typeof Main.selected_post_accounts === 'undefined' ) {
            Main.selected_post_accounts = {};
        }
        
        // Get network
        var network = $( this ).attr( 'data-network' );

        // Verify if mobile app is required
        if ( network === 'instagram_insights' ) {

            if ( $('.main .stream-page').attr('data-mobile-installed') !== '1' ) {

                // Display alert
                Main.popup_fon('sube', words.please_install_the_mobile_client, 1500, 2000);
                return;

            }

        }
        
        // Get category option
        var category = $( this ).attr( 'data-category' );        
        
        // Get account's id
        var network_id = $( this ).attr( 'data-id' );
        
        // Get net's id
        var net_id = $( this ).attr( 'data-net' );
        
        // Define accounts count
        if ( typeof Main.selected_accounts === 'undefined' ) {
            Main.selected_accounts = 0;
        }

        // Verify if account was selected
        if ( $( this ).closest( 'li' ).hasClass( 'account-selected' ) ) {

            var post_accounts = JSON.parse(Main.selected_post_accounts[network]);

            if ( post_accounts.length ) {
                
                delete Main.selected_post_accounts[network];
                
                for (var d = 0; d < post_accounts.length; d++) {

                    if ( post_accounts[d] === network_id ) {
                        
                        var selected = $( '.stream-share-modal-colapse-selected-accounts-list a[data-id="' + post_accounts[d] + '"]' );
                        
                        selected.closest( 'li' ).remove();
                        
                        delete post_accounts[d];
                        
                    } else {
                        
                        if ( typeof Main.selected_post_accounts[network] !== 'undefined' ) {

                            var extract = JSON.parse(Main.selected_post_accounts[network]);

                            if ( extract.indexOf(post_accounts[d]) < 0 ) {

                                extract[extract.length] = post_accounts[d];
                                Main.selected_post_accounts[network] = JSON.stringify(extract);

                            }

                        } else {

                            Main.selected_post_accounts[network] = JSON.stringify([post_accounts[d]]);

                        }
                        
                    }

                }

            }
            
            $( this ).closest( 'li' ).removeClass( 'account-selected' );
            
            Main.selected_accounts--;
            
        } else {

            if ( typeof Main.selected_post_accounts[network] !== 'undefined' ) {

                var extract = JSON.parse(Main.selected_post_accounts[network]);

                if ( extract.indexOf(network_id) < 0 ) {

                    extract[extract.length] = network_id;
                    Main.selected_post_accounts[network] = JSON.stringify(extract);
                    
                    $( '<li>' + $( this ).closest( 'li' ).html() + '</li>' ).appendTo( '.stream-share-modal-colapse-selected-accounts-list ul' );

                }

            } else {

                Main.selected_post_accounts[network] = JSON.stringify([network_id]);
                
                $( '<li>' + $( this ).closest( 'li' ).html() + '</li>' ).appendTo( '.stream-share-modal-colapse-selected-accounts-list ul' );

            }
                
            $( this ).closest( 'li' ).addClass( 'account-selected' );
            
            Main.selected_accounts++;
            
        }
        
        $('.stream-share-modal-colapse-selected-accounts-count').html(Main.selected_accounts + ' ' + words.selected_accounts);
        
    });
    
    /*
     * Schedule post
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.composer-schedule-post', function(e) {
        e.preventDefault();
        
        // Remove class add-date
        $('.midrub-calendar').find('a').removeClass('add-date');
        
        // Set hour
        $('.midrub-calendar-time-hour').val('08');

        // Set minutes
        $('.midrub-calendar-time-minutes').val('00');
        
        // Set period
        if ( $('.midrub-calendar-time-period').length > 0 ) {
            $('.midrub-calendar-time-period').val('AM');
        }
        
        // Hide calendar
        $('.midrub-planner').fadeOut('fast');
        
        $('.stream-share-post').submit();
        
    });
    
    /*
     * Unselect account or group
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */
    $(document).on('click', '.stream-share-modal-colapse-selected-accounts-list ul li a', function (e) {
        e.preventDefault();
        
        if ( $('.main .stream-schedule-groups-list').length > 0 ) {
        
            // Remove selected account
            $( this ).closest( 'li' ).remove();

             // Empty selected group
            Main.selected_post_group = {};

            // Remove selected group
            $( '.main .stream-schedule-groups-list li').removeClass( 'group-selected' );

            $('.main .stream-share-modal-colapse-selected-accounts-count').html('0 ' + words.selected_groups);
        
        } else {
        
            // Get account's id
            var network_id = $( this ).attr( 'data-id' );

            // Get network
            var network = $( this ).attr( 'data-network' );

            // Remove selected account
            $( this ).closest( 'li' ).remove();

            // Get account from the list
            var selected = $( '.stream-schedule-accounts-list li a[data-id="' + network_id + '"]' );        

            // Verify if account was selected
            if ( selected.closest( 'li' ).length > 0 ) {

                selected.closest( 'li' ).removeClass( 'account-selected' );

            }

            var post_accounts = JSON.parse(Main.selected_post_accounts[network]);

            if (post_accounts.length) {

                delete Main.selected_post_accounts[network];

                for (var d = 0; d < post_accounts.length; d++) {

                    if (post_accounts[d] === network_id) {

                        var selected = $('.stream-share-modal-colapse-selected-accounts-list a[data-id="' + post_accounts[d] + '"]');

                        selected.closest('li').remove();

                        delete post_accounts[d];

                    } else {

                        if (typeof Main.selected_post_accounts[network] !== 'undefined') {

                            var extract = JSON.parse(Main.selected_post_accounts[network]);

                            if (extract.indexOf(post_accounts[d]) < 0) {

                                extract[extract.length] = post_accounts[d];
                                Main.selected_post_accounts[network] = JSON.stringify(extract);

                            }

                        } else {

                            Main.selected_post_accounts[network] = JSON.stringify([post_accounts[d]]);

                        }

                    }

                }

            }
            
            Main.selected_accounts--;

            $('.stream-share-modal-colapse-selected-accounts-count').html(Main.selected_accounts + ' ' + words.selected_accounts);
            
        }
        
    });
    
    /*
     * Select group in the composer tab
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */
    $(document).on('click', '.stream-schedule-groups-list li a', function (e) {
        e.preventDefault();
        
        if ( $( this ).closest('li').hasClass('group-selected') ) {

            // Define $this
            var $this = $(this);

            // Remove selected group
            $this.closest( 'ul' ).find('li').removeClass( 'group-selected' );            
            
            // Empty selected group
            delete Main.selected_post_group;  
            
            $( '.main .stream-share-modal-colapse-selected-accounts-list ul' ).empty();
            
            $('.main .stream-share-modal-colapse-selected-accounts-count').html('0 ' + words.selected_groups);
            
        } else {
        
            // Get group's id
            var group_id = $( this ).attr( 'data-id' );

            // Define $this
            var $this = $(this);

            // Empty selected group
            Main.selected_post_group = {};

            // Remove selected group
            $this.closest( 'ul' ).find('li').removeClass( 'group-selected' );

            // Add group-selected class
            $this.closest( 'li' ).addClass('group-selected');

            // Add group's id
            Main.selected_post_group = $this.attr('data-id');

            $( '.main .stream-share-modal-colapse-selected-accounts-list ul' ).empty();

            $( '<li>' + $this.closest( 'li' ).html() + '</li>' ).appendTo( '.main .stream-share-modal-colapse-selected-accounts-list ul' );   
            
            $('.main .stream-share-modal-colapse-selected-accounts-count').html('1 ' + words.selected_groups);
            
        }
        
    }); 
    
    /*
     * Save post's media
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */
    $(document).on('click', '#stream-item-share .stream-media-post-save-btn', function (e) {
        e.preventDefault();
        
        // Get url
        var imageUrl = $(this).attr('data-url');
                
        var extension = imageUrl.slice((imageUrl.lastIndexOf('.') - 1 >>> 0) + 2);

        if ( extension === 'png' || extension === 'jpg' || extension === 'jpeg' || extension === 'png' || extension === 'gif' ) {
            var format = 'image/' + extension.replace('jpg', 'jpeg');
        } else {
            var format = 'video/mp4';
        }

        // Create object to pass
        var data = {
            action: 'save_media_in_storage',
            link: 'url: ' + imageUrl,
            cover: 'url: ' + imageUrl,
            type: format,
            size: 100,
            name: imageUrl.substring(url.lastIndexOf('/')+1)
        };

        // Set CSRF
        data[$('.upim').attr('data-csrf')] = $('input[name="' + $('.upim').attr('data-csrf') + '"]').val();

        // Upload media
        $.ajax({
            url: $.fn.midrubGallery.options.url + 'user/ajax/media',
            type: 'POST',
            data: data,
            dataType: 'JSON',
            beforeSend: function () {

                // Display loading animation
                $('.page-loading').fadeIn('slow');

            },
            success: function (data) {

                if ( data.success ) {

                    $('#stream-item-share .stream-media-post-save-area').hide();

                    $( '.multimedia-gallery' ).midrubGallery();

                    // Display alert
                    Main.popup_fon('subi', words.select_saved_media, 1500, 2000);                            

                } else {

                    // Display alert
                    Main.popup_fon('sube', data.message, 1500, 2000);

                }

            },
            error: function (jqXHR, textStatus, errorThrown) {

                console.log(jqXHR);

                // Display alert
                Main.popup_fon('sube', words.media_can_not_be_downloaded, 1500, 2000);

            },
            complete: function () {

                // Hide loading animation
                $('.page-loading').fadeOut('slow');

                setTimeout(function(){

                    if ( typeof Main.medias !== 'undefined' ) {

                        var medias = Object.values(Main.medias);

                        for ( var m = 0; m < medias.length; m++ ) {

                            $('.main .multimedia-gallery ul li a[data-id="' + medias[m].id + '"]').addClass('media-selected');

                        }

                    }

                }, 3000);

            }

        });
        
    }); 
    
    /*
     * Save post as draft
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.main .composer-draft-post', function(e) {
        e.preventDefault();
        
        Main.publish = 0;
        
        $('.main .stream-share-post').submit();
        
    });
    
    /*
     * Detect setup action click
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */     
    $( document ).on( 'click', '.main #stream-settings .stream-select-setup-list a', function(e) {
        e.preventDefault();
        
        // Set selected item
        $(this).closest('.dropdown').find('.dropdown-toggle').attr('data-id', $(this).attr('data-id'));
        $(this).closest('.dropdown').find('.dropdown-toggle').html($(this).html());
        
        // Get item's id
        var item_id = $(this).attr('data-id');
        
        // Get item's type
        var item_type = $(this).closest('.dropdown').find('.dropdown-toggle').attr('data-type');
        
        // Get stream's id
        var stream_id = $(this).closest('.dropdown').find('.dropdown-toggle').attr('data-stream');   
        
        // Prepare data to send
        var data = {
            action: 'stream_item_action_link',
            stream_id: stream_id,
            item_id: item_id,
            item_type: item_type
        };

        // Set CSRF
        data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_item_action_link');  
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });

    /*
     * Detect setup action keyup
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */     
    $( document ).on( 'keyup', '.main #stream-settings .stream-select-setup-list input[type="text"]', function(e) {
        e.preventDefault();
        
        // Get item's id
        var item_id = $(this).val();
        
        // Get item's type
        var item_type = $(this).attr('data-type');
        
        // Get stream's id
        var stream_id = $(this).closest('.dropdown').find('.dropdown-toggle').attr('data-stream');   
        
        // Prepare data to send
        var data = {
            action: 'stream_item_action_link',
            stream_id: stream_id,
            item_id: item_id,
            item_type: item_type
        };

        // Set CSRF
        data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_item_action_keyup');  
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*******************************
    RESPONSES
    ********************************/
   
    /*
     * Display creation stream tab response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.stream_create_new_stream_tab = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            var active = '';
            var is_active = '';
            var is_true = 'false';
            
            if ( $( '.main .stream-tabs-list li' ).length < 1 ) {
                
                active = ' active';
                is_active = ' show active';
                is_true = 'true';
                
            }
            
            // Create the new tab
            var new_tab = '<li class="nav-item">'
                                + '<a class="nav-link' + active + '" id="nav-' + data.tab_id + '" data-toggle="tab" href="#tab-' + data.tab_id + '" role="tab" aria-controls="tab-' + data.tab_id + '" aria-selected="' + is_true + '">'
                                    + '<i class="' + data.tab_icon + '"></i> ' + data.tab_name
                                    + '<small></small>'
                                + '</a>'
                            + '</li>';
                    
            $( document ).find( '.main .stream-tabs-list li.dropdown' ).remove();
            $( document ).find( '.main .stream-tabs-list li' ).removeClass('d-none');
            
            // Append new tab
            $( '.main .stream-tabs-list' ).append( new_tab );
            
            var all_tabs_body = '<div class="tab-pane fade' + is_active + '" data-tab="' + data.tab_id + '" id="tab-' + data.tab_id + '" role="tabpanel" aria-labelledby="tab-' + data.tab_id + '">'
                                    + '<div class="panel panel-default">'
                                        + '<div class="panel-heading">'
                                            + '<a href="#create-new-stream" data-toggle="modal">'
                                                + '<i class="icon-doc"></i> ' + data.new_stream
                                            + '</a>'
                                            + '<a href="#stream-tab-settings" data-toggle="modal" class="tab-stream-load">'
                                                + '<i class="icon-settings"></i> ' + data.settings
                                            + '</a>'
                                            + '<a href="#" class="stream-tab-refresh pull-right">'
                                                + '<i class="fas fa-sync-alt"></i>'
                                            + '</a>'
                                        + '</div>'
                                        + '<div class="panel-body stream-all-tab-streams">'
                                            + '<div class="row">'
                                                + '<div class="col-xl-3">'
                                                    + '<div class="col-xl-12 stream-single stream-cover">'
                                                    + '</div>'
                                                + '</div>'
                                                + '<div class="col-xl-3">'
                                                    + '<div class="col-xl-12 stream-single stream-cover">'
                                                    + '</div>'
                                                + '</div>'
                                                + '<div class="col-xl-3">'
                                                    + '<div class="col-xl-12 stream-single stream-cover">'
                                                    + '</div>'
                                                + '</div> '
                                                + '<div class="col-xl-3">'
                                                    + '<div class="col-xl-12 stream-single stream-cover">'
                                                    + '</div>'
                                                + '</div>'
                                            + '</div>'
                                        + '</div>'
                                    + '</div>'
                                + '</div>';
            
            // Add tab's body
            $( '.main .stream-content #nav-tabContent-streams' ).append(all_tabs_body);
            
            // Integrate tabs
            Main.integrate_tabs();
            
            setTimeout(function() {
                
                // Empty tab name field
                $( '.main .stream-tab-name' ).val('');
                
                // Add default icon
                $('.main .stream-selected-tab-icon i').attr('class', 'icon-flag');

                // Remove selected class from the table
                $('.main .stream-select-tab-icon td').removeClass('icon-selected');
                
                // Close popup
                $('#create-new-stream-tab').modal('hide');
                
            }, 1000);
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    }; 
    
    /*
     * Load stream settings
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.stream_load_connection_settings = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            switch ( data.connection_rules.type.toString() ) {
                
                case '1':
                    
                    // Get connection content
                    var connection = Main.stream_connection(data.connection_rules.type, data.connection_rules);

                    // Display content
                    $('.main #nav-connect-stream').html(connection);

                    $('.main #create-new-stream .modal-header #nav-all-streams-tab').removeClass('active show');
                    $('.main #create-new-stream .modal-header #nav-all-streams-tab').attr('aria-selected', 'false');
                    $('.main #nav-all-streams').removeClass('active show');
                    $('.main #nav-connect-stream').addClass('active show');
                    
                    break;
                    
                case '2':
                    
                    // Get connection content
                    var connection = Main.stream_connection(data.connection_rules.type, data.connection_rules);

                    // Display content
                    $('.main #nav-connect-stream').html(connection);

                    $('.main #create-new-stream .modal-header #nav-all-streams-tab').removeClass('active show');
                    $('.main #create-new-stream .modal-header #nav-all-streams-tab').attr('aria-selected', 'false');
                    $('.main #nav-all-streams').removeClass('active show');
                    $('.main #nav-connect-stream').addClass('active show');
                    
                    break;
                
            }
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };
    
    /*
     * Display search accounts result
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.stream_search_for_social_accounts = function ( status, data ) {
        
        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display found social accounts
            $('.main .new-stream-accounts-list').html(data.social_data);
            
        }

    };  
    
    /*
     * Display new connected stream result
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.stream_connect_new_stream = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {

            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Add new stream
            Main.display_stream(data);
            
            // Display the streams tab
            $('.main [href="#nav-all-streams"]').tab('show');
        
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);            
            
        }

    };
    
    /*
     * Display deletion tab response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.stream_delete_tab_streams = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {

            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Remove tab
            $( document ).find( '.main .stream-tabs-list li .active' ).closest( '.nav-item' ).remove();
            
            // Remove tab's body
            $( '.main .stream-content #nav-tabContent-streams > .tab-pane.active ' ).remove();
            
            if ( $( document ).find( '.main .stream-tabs-list li' ).eq(0).length > 0 ) {
            
                // Show first tab
                $('[href="' + $( document ).find( '.main .stream-tabs-list li' ).eq(0).find('a').attr('href') + '"]').tab('show');
            
            }
            
            setTimeout(function() {

                // Close popup
                $('#stream-tab-settings').modal('hide');

            }, 1000);
        
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);            
            
        }

    };
    
    /*
     * Save stream order
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.stream_save_stream_order = function ( status, data ) {

    };
    
    /*
     * Get Stream's setup
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.stream_get_setup = function ( status, data ) {

        if ( data.message ) {

            $('.main .select-a-sound').html( data.message );
            $( '.main .stream-settings-sounds input[type="checkbox"]' ).prop('checked', false);

        } else {

            $( '.main .stream-settings-sounds input[type="checkbox"]' ).prop('checked', false);
            $( '.main .stream-settings-sounds #' + data.alert_sound ).prop('checked', true);
            $('.main .select-a-sound').html( $( '.main .stream-settings-sounds #' + data.alert_sound ).closest('.row').find('span').text() );

        }
        
        if ( typeof data.stream !== 'undefined' ) {
        
            // Add header text color
            $('.main #header_text_color').val(data.stream[0].header_text_color);
        
        }
        
        if ( typeof data.stream !== 'undefined' ) {
        
            // Add item text color
            $('.main #item_text_color').val(data.stream[0].item_text_color);   
        
        }
        
        if ( typeof data.stream !== 'undefined' ) {
        
            // Add links color
            $('.main #links_color').val(data.stream[0].links_color); 
        
        }
        
        if ( typeof data.stream !== 'undefined' ) {
        
            // Add icons color
            $('.main #icons_color').val(data.stream[0].icons_color);
            
        }
        
        if ( typeof data.stream !== 'undefined' ) {
        
            // Add background color
            $('.main #background_color').val(data.stream[0].background_color); 
        
        }
        
        if ( typeof data.stream !== 'undefined' ) {
        
            // Add border color
            $('.main #border_color').val(data.stream[0].border_color);  
            
        }

        $('.main #nav-stream-setup').html(data.setup_data);
        
    };  
    
    /*
     * Display deletion stream response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.stream_delete_selected_stream = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {

            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Hide the modal
            setTimeout(function() {

                // Close popup
                $('#stream-settings').modal('hide');

            }, 1000);
            
            // Display Tab's streams
            Main.display_tab_streams( $( '.main .stream-content #nav-tabContent-streams > .tab-pane.active ' ).attr( 'data-tab' ) );
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);            
            
        }
        
    };
    
    /*
     * Display streams per tab
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.stream_connect_tab_steams = function ( status, data ) {
        
        if ( $(data.tab_streams).find('.stream-mark-seen-item-active').length > 0 ) {
            
            $('.main .stream-tabs-list li a[id="nav-' + data.tab_id + '"]').find('small').text($(data.tab_streams).find('.stream-mark-seen-item-active').length);
            $('.main .stream-tabs-list li a[id="nav-' + data.tab_id + '"]').find('small').css('display', 'inline-block');

        } else {

            $('.main .stream-tabs-list li a[id="nav-' + data.tab_id + '"]').find('small').text('');
            $('.main .stream-tabs-list li a[id="nav-' + data.tab_id + '"]').find('small').css('display', 'none');

        }
        
        var refresh = $( '.main .stream-content #nav-tabContent-streams > #tab-' + data.tab_id ).attr('data-refresh');

        if ( refresh > 0 ) { 
        
            if ( typeof Main.tabs === 'undefined' ) {

                Main.tabs = {
                    ['tab_' + data.tab_id]: Date.now()/1000 + (60 * refresh)
                };

            } else {

                Main.tabs['tab_' + data.tab_id] = Date.now()/1000 + (60 * refresh);

            }
            
        } else {
            
            if ( typeof Main.tabs !== 'undefined' ) {
                
                if ( typeof Main.tabs['tab_' + data.tab_id] !== 'undefined' ) {
                    
                    delete Main.tabs['tab_' + data.tab_id];
                    
                }
                
            }
            
        }
        
        $( '.main .stream-content #nav-tabContent-streams > #tab-' + data.tab_id + ' .stream-all-tab-streams > .row' ).html(data.tab_streams);
        
        Main.load_graph(data.tab_id);

    };
    
    /*
     * Display setup saving response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.stream_update_setup = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Refresh the tab
            Main.display_tab_streams(data.tab_id);
            
            // Display setup data
            $('.main #nav-stream-setup').html(data.setup_data);
        
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);            
            
        }
        
    };
    
    /*
     * Display setup deletion response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.stream_delete_setup = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Refresh the tab
            Main.display_tab_streams(data.tab_id);
            
            // Display setup data
            $('.main #nav-stream-setup').html(data.setup_data);
        
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);            
            
        }
        
    };
    
    /*
     * Display setup deletion response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.stream_change_settings_color = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            switch ( data.name ) {
                
                case 'header_text_color':
                    
                    // Display color
                    $('.main .stream-single[data-stream="' + data.stream_id + '"] .panel-heading').css('color', data.value);
            
                    break;
                    
                case 'item_text_color':
                    
                    // Display color
                    $('.main .stream-single[data-stream="' + data.stream_id + '"] .panel-body p').css('color', data.value);
            
                    break;
                    
                case 'links_color':
                    
                    // Display color
                    $('.main .stream-single[data-stream="' + data.stream_id + '"] .panel-body p a').css('color', data.value);
                    $('.main .stream-single[data-stream="' + data.stream_id + '"] .panel-body .stream-post strong a').css('color', data.value);
            
                    break;
                    
                case 'icons_color':
                    
                    // Display color
                    $('.main .stream-single[data-stream="' + data.stream_id + '"] .panel-body .stream-post-footer a').css('color', data.value);
                    $('.main .stream-single[data-stream="' + data.stream_id + '"] .panel-body small').css('color', data.value);
            
                    break;
                    
                case 'background_color':
                    
                    // Display color
                    $('.main .stream-single[data-stream="' + data.stream_id + '"] .panel').css('background-color', data.value);
            
                    break;
                    
                case 'border_color':
                    
                    // Display color
                    $('.main .stream-single[data-stream="' + data.stream_id + '"] .panel-heading').css('border-bottom-color', data.value);
                    $('.main .stream-single[data-stream="' + data.stream_id + '"] .panel-body ul li').css('border-bottom-color', data.value);
            
                    break;
                
            }
        
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);            
            
        }

    };
    
    /*
     * Display item action link response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.stream_item_action_link = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);   
            
            // Refresh the tab
            Main.display_tab_streams(data.tab_id);
        
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);            
            
        }

    };

    /*
     * Display item action keyup response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.stream_item_action_keyup = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Items var
            var items = '';
    
            // List 10 items
            for (var c = 0; c < data.items.length; c++) {
    
                items += '<li class="list-group-item">'
                    + '<a href="#" data-id="' + data.items[c].value + '">'
                        + data.items[c].text
                    + '</a>'
                + '</li>';
    
            } 
    
            // Display items
            $('.modal.show .dropdown-menu.show .list-group').html(items);

        } else {
            
            // Create no results found message
            var message = '<li class="no-results">'
                + data.message
            + '</li>';

            // Display items
            $('.modal.show .dropdown-menu.show .list-group').html(message);          
            
        }

    };
    
    /*
     * Display marking stream as seen response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.stream_mark_seen = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) { 
            
            // Refresh the tab
            Main.display_tab_streams(data.tab_id);
        
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);            
            
        }

    };
    
    /*
     * Display select alert response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.stream_select_sound_alert = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            if ( data.unselect_all ) {
                
                $('.main .select-a-sound').html( data.message );
                $( '.main .stream-settings-sounds input[type="checkbox"]' ).prop('checked', false);
                
            } else {
                
                $( '.main .stream-settings-sounds input[type="checkbox"]' ).prop('checked', false);
                $( '.main .stream-settings-sounds #' + data.message ).prop('checked', true);
                $('.main .select-a-sound').html( $( '.main .stream-settings-sounds #' + data.message ).closest('.row').find('span').text() );
                
            }
            
            $('.main .stream-settings-sounds').hide();
        
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);            
            
        }

    };
    
    /*
     * Display category's streams templates response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.stream_get_streams_templates = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Get Stream's templates
            var templates = data.templates;
            
            // Define all_templates variable
            var all_templates = '';
            
            for ( var d = 0; d < templates.length; d++ ) {
                
                all_templates += '<li>'
                                + '<div class="row">'
                                    + '<div class="col-xl-1">'
                                        + '<button type="button" class="btn btn-default" style="background-color:' + templates[d].color + ';">' + templates[d].icon + '</button>'
                                    + '</div>'
                                    + '<div class="col-xl-8">'
                                        + '<h3>' + templates[d].displayed_name + '</h3>'
                                        + '<p>' + templates[d].description + '</p>'
                                    + '</div>'
                                    + '<div class="col-xl-3 text-right">'
                                        + '<button type="button" class="btn btn-success" id="nav-connect-stream-tab" data-network="' + templates[d].template_name + '">'
                                            + '<i class="fas fa-plug"></i> ' + data.connect
                                        + '</button>'
                                    + '</div>'
                                + '</div>'
                            + '</li>';
                
            }
            
            // Displays streams by category
            $('.main .stream-available-streams-by-category').html(all_templates);
            
            // Remove selected class
            $('.main .stream-available-stream-categories li').removeClass('network-selected');
            
            // Add selected class
            $('.main .stream-available-stream-categories li a[data-category="' + data.category + '"]').closest('li').addClass('network-selected');
        
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);            
            
        }

    };
    
    /*
     * Get single stream's item
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.stream_template_content_single = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Show modal
            $('#stream-item-react').modal('show');
            
            // Display head text
            $('.main #nav-tab-stream-react').html(data.menu_text);

            // Default form value
            var form = '';

            // Verify if form exists
            if ( typeof data.form !== 'undefined' ) {

                form = '<div class="panel-body">'
                    + data.form
                + '</div>';

            }
            
            // Display body
            var body = '<div class="panel panel-default">'
                            + '<div class="panel-heading">' + data.content + '</div>'
                            + form
                        + '</div>';
                
            $('.main #stream-item-react .modal-body').html(body);    
        
        } else {
        
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);   
            
        }

    };
    
    /*
     * Display sent reaction response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.stream_send_react = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000); 
            
            $('#stream-item-react').modal('hide');
            
            // Refresh the tab
            Main.display_tab_streams(data.tab_id);
        
        } else {
        
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);   
            
        }

    };
    
    /*
     * Display refresh update response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.stream_tab_refresh = function ( status, data ) {

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
     * Display social networks
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.account_manager_load_networks = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            $( '#nav-accounts-manager' ).html( data.social_data );
            
            $( '#nav-groups-manager' ).html( data.groups_data );
            
        }
        
    };
    
    /*
     * Display network's accounts
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.account_manager_get_accounts = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            if ( data.type === 'accounts_manager' ) {
                
                // Verify if hidden content exists
                if ( data.hidden ) {
                    $( '.main .manage-accounts-hidden-content' ).html(data.hidden);
                } else {
                    $( '.main .manage-accounts-hidden-content' ).empty();
                }
                
                $( '.main .manage-accounts-hidden-content' ).fadeOut('fast');
            
                // Display accounts
                $( '#stream-item-share .manage-accounts-all-accounts' ).html(data.active);

                // Display network's instructions
                $( '#stream-item-share .manage-accounts-network-instructions' ).html(data.instructions);

                // Display search form
                $( '#stream-item-share .manage-accounts-search-form' ).html(data.search_form);
            
            } else {
                
                // Display accounts
                $( '#stream-item-share .manage-accounts-groups-all-accounts' ).html(data.active);

                if ( $('.accounts-manager-groups-select-group .btn-secondary').attr('data-id') ) {
                    
                    // Remove selected accounts
                    $( '.main #nav-groups-manager .accounts-manager-groups-active-accounts li' ).removeClass( 'select-account-in-group' );
                    
                    var group_accounts = $('main .create-new-group-form .accounts-manager-groups-available-accounts li');

                    for ( var g = 0; g < group_accounts.length; g++ ) {
                        
                        $( '.main #nav-groups-manager .accounts-manager-groups-active-accounts li a[data-id="' + group_accounts.eq(g).find('a').attr('data-id') + '"]' ).closest( 'li' ).addClass( 'select-account-in-group' );
                        
                    }
                    
                }
                
            }
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
    };
    
    /*
     * Display account deletion status
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.account_manager_delete_accounts = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Remove account from the list
            $('#nav-accounts-manager .accounts-manager-active-accounts-list li a[data-id="' + data.account_id + '"]').closest('li').remove();
            $('.stream-share-modal-colapse-selected-accounts-list ul li a[data-id="' + data.account_id + '"]').click();
            
            Main.reload_accounts_list();
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
    };
    
    /*
     * Display accounts results
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.stream_accounts_results_by_search = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var accounts = '';
            
            // List all accounts
            for ( var f = 0; f < data.accounts_list.length; f++ ) {
                
                var icon = data.accounts_list[f].network_info.icon;
                
                var new_icon = icon.replace(' class', ' style="color: ' + data.accounts_list[f].network_info.color + '" class');
                
                var account_selected = '';
                
                if ( typeof Main.selected_post_accounts !== 'undefined' ) {
                
                    if (typeof Main.selected_post_accounts[data.accounts_list[f].network_name] !== 'undefined') {

                        var extract = JSON.parse(Main.selected_post_accounts[data.accounts_list[f].network_name]);

                        if (extract.indexOf(data.accounts_list[f].network_id) > -1) {
                            account_selected = ' class="account-selected"';
                        }

                    }
                
                }
                
                accounts += '<li' + account_selected + '>'
                                + '<a href="#" data-id="' + data.accounts_list[f].network_id + '" data-net="' + data.accounts_list[f].net_id + '" data-network="' + data.accounts_list[f].network_name + '" data-category="' + data.accounts_list[f].network_info.categories + '">'
                                    + new_icon
                                    + data.accounts_list[f].user_name
                                    + '<i class="icon-check"></i>'
                                + '</a>'
                            + '</li>';
                
            }
            
            $( '.stream-schedule-accounts-list ul' ).html( accounts );
            
        } else {
            
            $( '.stream-schedule-accounts-list ul' ).html( '<li class="no-accounts-found">' + data.message + '</li>' );
            
        }

    };
    
     /*
     * Display groups results
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.stream_search_groups = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var groups = '';
            
            // List all accounts
            for ( var f = 0; f < data.groups_list.length; f++ ) {
                
                var group_selected = '';
                
                if ( typeof Main.selected_post_group !== 'undefined' ) {

                    if ( Main.selected_post_group === data.groups_list[f].list_id ) {
                        group_selected = ' class="group-selected"';
                    }
                
                }
                
                groups += '<li' + group_selected + '>'
                                + '<a href="#" data-id="' + data.groups_list[f].list_id + '">'
                                    + '<i class="icon-folder-alt"></i>'
                                    + data.groups_list[f].name
                                    + '<i class="icon-check"></i>'
                                + '</a>'
                            + '</li>';
                
            }
            
            $( '.stream-schedule-groups-list ul' ).html( groups );
            
        } else {
            
            $( '.stream-schedule-groups-list ul' ).html( '<li class="no-groups-found">' + data.message + '</li>' );
            
        }

    };
    
    /*
     * Display search results in accounts manager
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.account_manager_search_for_accounts = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            if ( data.type === 'accounts_manager' ) {

                $( document ).find( '#nav-accounts-manager .manage-accounts-all-accounts' ).html( data.social_data );
                
            } else {
                
                $( document ).find( '#nav-groups-manager .manage-accounts-groups-all-accounts' ).html( data.social_data );
                
            }
            
        } else {
            
            if ( $('#nav-accounts-manager').hasClass('show') ) {
                
                $( document ).find('#nav-accounts-manager .manage-accounts-all-accounts').html( data.message );
                
            } else {
                
                $( document ).find( '#nav-groups-manager .manage-accounts-groups-all-accounts' ).html( data.social_data );
                
            }         
            
        }
        
        if ( $('.accounts-manager-groups-select-group .btn-secondary').attr('data-id') ) {

            // Remove selected accounts
            $( '.main #nav-groups-manager .accounts-manager-groups-active-accounts li' ).removeClass( 'select-account-in-group' );

            var group_accounts = $('main .create-new-group-form .accounts-manager-groups-available-accounts li');

            for ( var g = 0; g < group_accounts.length; g++ ) {

                $( '.main #nav-groups-manager .accounts-manager-groups-active-accounts li a[data-id="' + group_accounts.eq(g).find('a').attr('data-id') + '"]' ).closest( 'li' ).addClass( 'select-account-in-group' );

            }

        }
        
    };
    
    /*
     * Gets all available group's accounts
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.accounts_manager_groups_available_accounts = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var accounts = '';

            for ( var a = 0; a < data.accounts.length; a++ ) {
                
                $( '.main #nav-groups-manager .accounts-manager-groups-active-accounts li a[data-id="' + data.accounts[a].network_id + '"]' ).closest( 'li' ).addClass( 'select-account-in-group' );
                
                accounts += '<li>'
                                + '<a href="#" data-id="' + data.accounts[a].network_id + '">' + data.accounts[a].user_name + ' <i class="icon-trash"></i></a>'
                            + '</li>';
                
            }
            
            // Display accounts
            $('.main #nav-groups-manager .accounts-manager-groups-available-accounts').html( accounts );
            
        } else {
            
            var accounts = '<li class="no-accounts-found">'
                                + data.message
                            + '</li>';
            
            // Display no accounts found message
            $('.main #nav-groups-manager .accounts-manager-groups-available-accounts').html( accounts );
            
        }
        
        $( '.main .accounts-manager-groups-select-group .col-xl-12' ).eq(1).fadeIn('slow');
        $( '.main .accounts-manager-groups-select-group .col-xl-12' ).eq(2).fadeIn('slow');
        
    };
    
    /*
     * Removing account from a group response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.account_manager_remove_account_from_group = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            if (typeof data.accounts === 'string' || data.accounts instanceof String) {
                
                accounts = data.accounts;
                
            } else {
                
                // Remove selected accounts
                $( '.main #nav-groups-manager .accounts-manager-groups-active-accounts li' ).removeClass( 'select-account-in-group' );
            
                var accounts = '';

                for ( var a = 0; a < data.accounts.length; a++ ) {
                    
                    $( '.main #nav-groups-manager .accounts-manager-groups-active-accounts li a[data-id="' + data.accounts[a].network_id + '"]' ).closest( 'li' ).addClass( 'select-account-in-group' );

                    accounts += '<li>'
                                    + '<a href="#" data-id="' + data.accounts[a].network_id + '">'
                                        + data.accounts[a].user_name + ' <i class="icon-trash"></i>'
                                    + '</a>'
                                + '</li>';

                }
            
            }
            
            $( document ).find( '.create-new-group-form .accounts-manager-groups-available-accounts' ).html( accounts );
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
    };
    
    /*
     * Display adding account to grup status
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.account_manager_add_account_to_group = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Remove selected accounts
            $( '.main #nav-groups-manager .accounts-manager-groups-active-accounts li' ).removeClass( 'select-account-in-group' );
            
            var accounts = '';
            
            for ( var a = 0; a < data.accounts.length; a++ ) {
                
                $( '.main #nav-groups-manager .accounts-manager-groups-active-accounts li a[data-id="' + data.accounts[a].network_id + '"]' ).closest( 'li' ).addClass( 'select-account-in-group' );
                
                accounts += '<li>'
                                    + '<a href="#" data-id="' + data.accounts[a].network_id + '">'
                                        + data.accounts[a].user_name + ' <i class="icon-trash"></i>'
                                    + '</a>'
                                + '</li>';
                
            }
            
            $( document ).find( '.create-new-group-form .accounts-manager-groups-available-accounts' ).html( accounts );
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
    };
    
    /*
     * Display group deletion status
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.accounts_manager_groups_delete_group = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Unselect selected group
            $('.stream-schedule-groups-list li a[data-id="' + $('.accounts-manager-groups-select-group .btn-secondary').attr('data-id') + '"]').click();
            
            // Change the selector text
            $('.accounts-manager-groups-select-group .btn-secondary').text(data.select_group);
            $('.accounts-manager-groups-select-group .btn-secondary').removeAttr('data-id');
            
            // Remove active class
            $( 'main .accounts-manager-groups-select-group .dropdown-menu .dropdown-item' ).removeClass( 'active' );
            $( 'main .accounts-manager-groups-active-accounts li' ).removeClass( 'select-account-in-group' );

            // Hide accounts and deletion button area
            $('.accounts-manager-groups-select-group .accounts-manager-groups-available-accounts').empty();
            $( '.main .accounts-manager-groups-select-group .col-xl-12' ).eq(1).fadeOut('slow');
            $( '.main .accounts-manager-groups-select-group .col-xl-12' ).eq(2).fadeOut('slow');
            
            // Get groups
            var groups = data.groups;
            
            var all_groups = '';
            
            var groups_list = '';
            
            for ( var w = 0; w < groups.length; w++ ) {
                
                all_groups += '<button class="dropdown-item" type="button" data-id="' + groups[w].list_id + '">'
                                + groups[w].name
                            + '</button>';
                    
                var group_selected = '';
                
                if ( typeof Main.selected_post_group !== 'undefined' ) {

                    if ( Main.selected_post_group === groups[w].list_id ) {
                        group_selected = ' class="group-selected"';
                    }
                
                }
                
                groups_list += '<li' + group_selected + '>'
                                + '<a href="#" data-id="' + groups[w].list_id + '">'
                                    + '<i class="icon-folder-alt"></i>'
                                    + groups[w].name
                                    + '<i class="icon-check"></i>'
                                + '</a>'
                            + '</li>';
                
            }
            
            $( '.stream-schedule-groups-list ul' ).html( groups_list );
            
            $( document ).find( '.create-new-group-form .dropdown-menu' ).html( all_groups );
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
    };
    
    /*
     * Display group creation status
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.account_manager_create_accounts_group = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Empty the group name field
            $('.accounts-manager-groups-enter-group-name').val('');
            
            // Get groups
            var groups = data.groups;
            
            var all_groups = '';
            
            var groups_list = '';
            
            for ( var w = 0; w < groups.length; w++ ) {
                
                all_groups += '<button class="dropdown-item" type="button" data-id="' + groups[w].list_id + '">'
                                + groups[w].name
                            + '</button>';
                
                var group_selected = '';
                
                if ( typeof Main.selected_post_group !== 'undefined' ) {

                    if ( Main.selected_post_group === groups[w].list_id ) {
                        group_selected = ' class="group-selected"';
                    }
                
                }
                
                groups_list += '<li' + group_selected + '>'
                                + '<a href="#" data-id="' + groups[w].list_id + '">'
                                    + '<i class="icon-folder-alt"></i>'
                                    + groups[w].name
                                    + '<i class="icon-check"></i>'
                                + '</a>'
                            + '</li>';
                
            }
            
            $( '.stream-schedule-groups-list ul' ).html( groups_list );
            
            $( document ).find( '.create-new-group-form .dropdown-menu' ).html( all_groups );
            
            $( document ).find( '.create-new-group-form .dropdown-menu button[data-id="' + data.group_id + '"]' ).click();
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
    };
    
    /*
     * Display publish post status
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.5
     */
    Main.methods.stream_publish_post_status = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Reset the form
            $('.stream-share-modal-colapse-selected-accounts-count').html('0 ' + words.selected_accounts);
            $('#stream-item-share .stream-share-modal-colapse-selected-accounts-list ul' ).empty();
            $('#stream-item-share .composer-title input[type="text"], #stream-item-share textarea.new-post' ).val( '' );
            $('#stream-item-share .emojionearea-editor').empty();
            $('#stream-item-share .multimedia-gallery li a').removeClass( 'media-selected' );
            $('#stream-item-share .stream-media-post-save-area').hide();
            
            if ( $('#stream-item-share .stream-schedule-accounts-list').length > 0 ) {
            
                $( '#stream-item-share .stream-schedule-accounts-list li' ).removeClass( 'account-selected' );
                
                if ( typeof Main.selected_post_accounts !== 'undefined' ) {

                    // Delete selected accounts
                    delete Main.selected_post_accounts;

                }
            
            } else {
                
                $( '#stream-item-share .stream-schedule-groups-list li' ).removeClass( 'group-selected' );
                
                if ( typeof Main.selected_post_group !== 'undefined' ) {

                    // Delete selected group
                    delete Main.selected_post_group ;

                }
                
            }
            
            if ( typeof Main.medias !== 'undefined' ) {

                // Delete selected medias
                delete Main.medias;

            }
            
            if ( typeof Main.selected_post_url !== 'undefined' ) {
                
                // Delete post url
                delete Main.selected_post_url;
                
            }
            
            if ( typeof Main.selected_accounts !== 'undefined' ) {
                
                // Delete accounts count
                delete Main.selected_accounts;
                
            }            
            
            // Set current date
            Main.ctime = new Date();

            // Set current months
            Main.month = Main.ctime.getMonth() + 1;

            // Set current day
            Main.day = Main.ctime.getDate();

            // Set current year
            Main.year = Main.ctime.getFullYear();

            // Set current year
            Main.cyear = Main.year;

            // Set date/hour format
            Main.format = 0;

            // Set selected_date
            Main.selected_date = '';

            // Set selected time
            Main.selected_time = '08:00';

            // Reset scheduler
            Main.show_calendar( Main.month, Main.day, Main.year, Main.format );
            
            // Set default status
            Main.publish = 1;

            // Empty datetime input
            $('.datetime').val('');
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };
   
    /*******************************
    FORMS
    ********************************/
   
    /*
     * Create a stream tab
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */
    $(document).on('submit', '.main .stream-create-tab', function (e) {
        e.preventDefault();
        
        // Get the tab's icon
        var tab_icon = $(this).find('.stream-selected-tab-icon i').attr('class');
        
        // Get the tab's name
        var tab_name = $(this).find('.stream-tab-name').val();
        
        var data = {
            action: 'stream_create_new_stream_tab',
            tab_icon: tab_icon,
            tab_name: tab_name
        };

        // Set CSRF
        data[$('.stream-create-tab').attr('data-csrf')] = $('input[name="' + $('.stream-create-tab').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_create_new_stream_tab');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Save the Stream's setup
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */
    $(document).on('submit', '.main .stream-update-stream-setup', function (e) {
        e.preventDefault();

        var data = {};
        
        $.each($('.main .stream-update-stream-setup').serializeArray(), function(i, field) {
            data[field.name] = field.value;
        });
        
        data['action'] = 'stream_update_setup';

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_update_setup');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Send react
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */
    $(document).on('submit', '.main .stream-send-react', function (e) {
        e.preventDefault();

        var data = {};
        
        $.each($('.main .stream-send-react').serializeArray(), function(i, field) {
            data[field.name] = field.value;
        });
        
        data['action'] = 'stream_send_react';

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_send_react');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    }); 
    
    /*
     * Create a group
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */
    $(document).on('submit', '.main .create-new-group-form', function (e) {
        e.preventDefault();
        
        // Get the group name
        var group_name = $('.main .accounts-manager-groups-enter-group-name').val();
        
        // Create an object with form data
        var data = {
            action: 'account_manager_create_accounts_group',
            group_name: group_name
        };
        
        // Set CSRF
        data[$('.main .create-new-group-form').attr('data-csrf')] = $('input[name="' + $('.main .create-new-group-form').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'account_manager_create_accounts_group');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Share or schedule a post
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */
    $(document).on('submit', '.main .stream-share-post', function (e) {
        e.preventDefault();
        
         // Set current time
        var currentdate = new Date();
        
        // Set date time
        var datetime = currentdate.getFullYear() + '-' + (currentdate.getMonth() + 1) + '-' + currentdate.getDate() + ' ' + currentdate.getHours() + ':' + currentdate.getMinutes() + ':' + currentdate.getSeconds();
        
        // Set post's title
        var post_title = $('.main .composer-title input[type="text"]').val();
        
        // Set message
        var post = $('#stream-item-share textarea.new-post').val();
        
        Main.verify_for_url(post);
        
        // Encode the post
        post = btoa(encodeURIComponent(post));
        
        // Remove non necessary characters
        post = post.replace('/', '-');
        post = post.replace(/=/g, '');
        
        // Set post's publish date
        var date = $('.datetime').val();
        
        // Verify if user has scheduled the post
        if ( !date ) {
            date = datetime;
        }
        
        if ( typeof Main.selected_post_url !== 'undefined' ) {
            
            // Set url
            var post_url = Main.selected_post_url;
            
        } else {
            
            var post_url = '';
            
        }        
        
        if ( typeof Main.selected_post_accounts !== 'undefined' ) {
            
            // Set networks
            var networks = Main.selected_post_accounts;
            
        } else {
            
            var networks = [];
            
        }
        
        if ( typeof Main.selected_post_group !== 'undefined' ) {
            
            // Set group's id
            var group_id = Main.selected_post_group;
            
        } else {
            
            var group_id = [];
            
        }
        
        if ( typeof Main.medias !== 'undefined' ) {
            
            // Set medias
            var medias = Object.values(Main.medias);
            
        } else {
            
            var medias = [];
            
        }        

        // Set default status
        var status = 1;
        
        // Verify if status already exists
        if ( typeof Main.publish !== 'undefined' ) {
            status = Main.publish;
        }
        
        // Set default category value
        var category = {};
        
        // Verify if categories is defined
        if ( typeof Main.categories !== 'undefined' ) {
            category = Main.categories;
        }
        
        // Create an object with form data
        var data = {
            action: 'composer_publish_post',
            post: post,
            post_title: post_title,
            url: post_url,
            medias: medias,
            networks: networks,
            group_id: group_id,
            publish: status,
            date: date,
            current_date: datetime,
            category: category
        };
        
        // Set CSRF
        data[$(this).attr('data-csrf')] = $('input[name="' + $(this).attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/stream', 'POST', data, 'stream_publish_post_status');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });    
    
    /*******************************
    DEPENDENCIES
    ********************************/
   
    // Basic instantiation:
    $('.main .stream-settings-input').colorpicker({
        format: 'hex'
    });
    
    /*
     * Show emojis icon
     * 
     * @since   0.0.7.6
     */
    $( '.new-post' ).emojioneArea({
        pickerPosition: 'bottom',
        tonesStyle: 'bullet',
        attributes: {
            spellcheck : true,
            autocomplete   : 'on'
        }
        
    });
    
    // Load all user's tabs
    Main.stream_load_tabs();

});