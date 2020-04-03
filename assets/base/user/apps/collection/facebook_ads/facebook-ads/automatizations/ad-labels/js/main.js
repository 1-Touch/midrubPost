/*
 * Main javascript file
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
     * Load Ad Account for select dropdown
     * 
     * @since   0.0.7.7
     */ 
    Main.load_campaigs_list_for_ad_label = function() {
        
        var key = '';
        
        key = $('.main #fb-labels-create-ad-label .ad-label-filter-fb-campaigns').val();
        
        var data = {
            action: 'load_select_ad_campaigns',
            key: key
        };
        
        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_campaigs_list_for_ad_label');
        
    };
    
    /*
     * Load Ad Sets for select dropdown
     * 
     * @since   0.0.7.7
     */ 
    Main.load_list_ad_sets_for_ad_label = function() {
        
        var key = $('.main #fb-labels-create-ad-label .fb-labels-filter-fb-adsets').val();
        
        var data = {
            action: 'load_select_ad_sets',
            campaign_id: $('.main #fb-labels-create-ad-label .fb-labels-selected-ad-campaign').attr('data-id'),
            key: key
        };
        
        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_select_ad_sets_for_ad_label');
        
    };
    
    /*
     * Load Facebook Pages and Instagram Accounts for select dropdown
     * 
     * @since   0.0.7.7
    */ 
    Main.load_identity_for_ad_label = function() {
        
        var data = {
            action: 'load_ad_identity'
        };

        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'ad_labels_load_ad_identity');
        
    };
    
    /*
     * Load Facebook Ad Labels
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.7.7
     */ 
    Main.fb_labels_load_all = function(page) {
        
        var data = {
            action: 'fb_labels_load_all',
            page: page
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads?automatization=ad_labels', 'GET', data, 'fb_labels_load_all');
        
    };

    /*
     * Load Automatization's content
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.7.7
     */ 
    Main.automatization_ad_labels = function(page) {
        
        var ad_labels = '<div class="row">'
                           + '<div class="col-xl-12">'
                              + '<div class="table-responsive">'
                                 + '<table class="table">'
                                    + '<thead>'
                                       + '<tr>'
                                          + '<th scope="row" colspan="3">'
                                             + '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#fb-labels-create-ad-label">'
                                                 + '<i class="icon-rocket"></i>'
                                                 + ad_label_words.fb_labels_new_ad_label
                                             + '</button>'
                                             + '<button type="button" class="btn btn-dark ads-delete-ad-labels">'
                                                 + '<i class="icon-trash"></i>'
                                                 + ad_label_words.delete_this
                                             + '</button>'
                                          + '</th>'
                                          + '<th scope="row" colspan="2">'
                                             + '<button type="button" class="btn btn-dark pull-right btn-ads-reports" data-toggle="modal" data-target="#fb-labels-generate-reports">'
                                                 + '<i class="icon-pie-chart"></i>'
                                                 + ad_label_words.reports
                                             + '</button>'
                                          + '</th>'
                                       + '</tr>'
                                       + '<tr>'
                                          + '<th scope="row">'
                                             + '<div class="checkbox-option-select">'
                                                + '<input id="ad-labels-select-all" name="ad-labels-select-all" type="checkbox">'
                                                + '<label for="ad-labels-select-all"></label>'
                                             + '</div>'
                                          + '</th>'
                                          + '<th scope="col">'
                                             + ad_label_words.fb_labels_name
                                          + '</th>'
                                          + '<th scope="col">'
                                             + ad_label_words.fb_labels_category  
                                          + '</th>'
                                          + '<th scope="col">'
                                             + ad_label_words.fb_labels_created_ads  
                                          + '</th>'
                                          + '<th scope="col">'
                                             + ad_label_words.fb_labels_active_ads              
                                          + '</th>'
                                       + '</tr>'
                                    + '</thead>'
                                    + '<tbody>'
                                    + '</tbody>'
                                    + '<tfoot>'
                                       + '<tr>'
                                          + '<td colspan="5" class="text-right">'
                                             + '<button type="button" class="btn btn-dark btn-previous btn-ad-labels-pagination btn-disabled">'
                                                 + '<i class="far fa-arrow-alt-circle-left"></i>'
                                                 + ad_label_words.previous
                                             + '</button>'
                                             + '<button type="button" class="btn btn-dark btn-next btn-ad-labels-pagination btn-disabled" data-page="2">'
                                                 + ad_label_words.next
                                             + '<i class="far fa-arrow-alt-circle-right"></i>'
                                             + '</button>'
                                          + '</td>'
                                       + '</tr>'
                                    + '</tfoot>'
                                 + '</table>'
                              + '</div>'
                           + '</div>'
                        + '</div>';
                
        $('.main #automatization-ad-labels').html(ad_labels);
        
        // Load all AD Labels
        Main.fb_labels_load_all(1);
        
    };

    /*******************************
    ACTIONS
    ********************************/
   
    /*
     * Filter the Campaigns
     * 
     * @since   0.0.7.7
     */        
    $(document).on('keyup', '.main .ad-label-filter-fb-campaigns', function () {

        // Load Ad's campaaigns for Ad Label
        Main.load_campaigs_list_for_ad_label();

    });  

    /*
     * Detect modal open
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */   
    $( '.main #fb-labels-create-ad-label' ).on('shown.bs.modal', function (e) {
        
        // Empty response
        $('.main #fb-labels-create-ad-label .alerts-display-reports').empty(); 
        
        // Load Ad's campaaigns for Ad Label
        Main.load_campaigs_list_for_ad_label();
        
        // Load Ad Sets
        Main.load_list_ad_sets_for_ad_label();
        
        // Load Facebook and Instagram accounts
        Main.load_identity_for_ad_label();
        
    });
    
    /*
     * Expand Ad Sets
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $('.main #fb-labels-create-ad-label #fb-labels-select-ad-set').on('show.bs.collapse', function (e) {

        if (!$('.main #fb-labels-create-ad-label .fb-labels-selected-ad-campaign').attr('data-id')) {

            // Display alert
            Main.popup_fon('sube', words.please_select_ad_campaign, 1500, 2000);
            return false;

        }

    });
    
    /*
     * Expand Label's preferences
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $('.main #fb-labels-create-ad-label #fb-labels-add-preferences').on('show.bs.collapse', function (e) {

        if (!$('.main #fb-labels-create-ad-label .fb-labels-selected-ad-set').attr('data-id')) {

            // Display alert
            Main.popup_fon('sube', words.please_select_ad_set, 1500, 2000);
            return false;

        }

    });    
    
    /*
     * Change the Facebook Campaign
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $( document ).on( 'click', '.main #fb-labels-create-ad-label .ad-label-filter-fb-campaigns-list li a', function (e) {
        e.preventDefault();
        
        // Get campaign id
        var campaign_id = $(this).attr('data-id');
        
        // Create an object with form data
        var data = {
            action: 'select_facebook_campaign',
            campaign_id: campaign_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'select_facebook_campaign_for_ad_label');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Change the Facebook Ad Set
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $( document ).on( 'click', '.main .fb-labels-filter-fb-adsets-list li a', function (e) {
        e.preventDefault();
        
        $('.main #fb-labels-create-ad-label .fb-labels-selected-ad-set').text($(this).text());
        $('.main #fb-labels-create-ad-label .fb-labels-selected-ad-set').attr('data-id', $(this).attr('data-id'));
        
    });
    
    /*
     * Detect all Ad's labels selection
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', '.main #ad-labels-select-all', function (e) {
        
        setTimeout(function(){
            
            if ( $( 'main #ad-labels-select-all' ).is(':checked') ) {

                $( '.main #automatization-ad-labels tbody input[type="checkbox"]' ).prop('checked', true);

            } else {

                $( '.main #automatization-ad-labels tbody input[type="checkbox"]' ).prop('checked', false);

            }
        
        },500);
        
    });

    /*
     * Detect Ad's labels pagination click
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $( document ).on( 'click', '.main .btn-ad-labels-pagination', function (e) {
        e.preventDefault();
        
        // Get page number
        var page = $(this).attr('data-page');

        // Load all AD Labels
        Main.fb_labels_load_all(page);
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Delete ad labels
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $( document ).on( 'click', 'main .ads-delete-ad-labels', function (e) {
        
        // Get all selected ad's labels
        var labels = $('.main #automatization-ad-labels tbody input[type="checkbox"]');
        
        var selected = [];
        
        // List all ad's labels
        for ( var d = 0; d < labels.length; d++ ) {

            if ( labels[d].checked ) {
                selected.push($(labels[d]).attr('data-id'));
            }
            
        }
        
        // Create an object with form data
        var data = {
            action: 'delete_ad_labels',
            labels: Object.entries(selected)
        };

        // Set CSRF
        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads?automatization=ad_labels', 'POST', data, 'delete_ad_labels');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Select order reports time
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $( document ).on( 'click', '.main .ad-labels-history-reports-by-time a', function (e) {
        e.preventDefault();
        
        // Display selected time
        $('.main .ad-labels-order-reports-by-time').html($(this).html());
        $('.main .ad-labels-order-reports-by-time').attr('data-time', $(this).attr('data-time'));
        
    });
    
    /*
     * Change the Facebook Page
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $( document ).on( 'click', 'main .ad-labels-filter-fb-pages-list li a', function (e) {
        e.preventDefault();
        
        // Get page id
        var page_id = $(this).attr('data-id');
        
        // Create an object with form data
        var data = {
            action: 'display_connected_instagram_accounts',
            account_id: $('.main .ads-select-account').attr('data-id'),
            page_id: page_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'ad_labels_display_connected_instagram_accounts');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*******************************
    RESPONSES
    ********************************/
   
    /*
     * Display connected Instagram accounts
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.ad_labels_display_connected_instagram_accounts = function ( status, data ) {
        
        // Hide accounts
        $('.main .ad-labels-connect-instagram-account .btn-select').empty();
        $('.main .ad-labels-connect-instagram-account .btn-select').removeAttr('data-id');
        $('.main .ad-labels-connect-instagram-account ul').empty();

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            if ( typeof data.accounts !== 'undefined' ) {
                
                var all_accounts = '';
                
                // List all accounts
                for ( var g = 0; g < data.accounts.data.length; g++ ) {
                    
                    if ( g < 1 ) {
                        
                        var text = '<img src="' + data.accounts.data[g].profile_pic + '">'
                                    + data.accounts.data[g].username;
                        
                        // Add first account as selected
                        $('.main .ad-labels-connect-instagram-account .btn-select').html(text);
                        $('.main .ad-labels-connect-instagram-account .btn-select').attr('data-id', data.accounts.data[g].id);
                        
                    }
                    
                    all_accounts += '<li class="list-group-item">'
                                        + '<a href="#" data-id="' + data.accounts.data[g].id + '">'
                                            + '<img src="' + data.accounts.data[g].profile_pic + '">'
                                            + data.accounts.data[g].username
                                        + '</a>'
                                    + '</li>';
                    
                }
                
                // Add accounts
                $('.main .ad-labels-connect-instagram-account ul').html(all_accounts);
                
            } 
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }        
        
    };

    /*
     * Display Campaign list in the opened modal
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.load_campaigs_list_for_ad_label = function ( status, data ) {
        
        // Uncheck all checboxes
        $( '.main #automatization-ad-labels tbody input[type="checkbox"]' ).prop('checked', false);
        $( '.main #ad-labels-select-all' ).prop('checked', false);

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var all_campaigns = '';
            
            for ( var c = 0; c < data.campaigns.data.length; c++ ) {
                
                all_campaigns += '<li class="list-group-item">'
                                   + '<a href="#" data-id="' + data.campaigns.data[c].id + '">'
                                       + data.campaigns.data[c].name
                                   + '</a>'
                               + '</li>';
                
            }
            
            $('.main #fb-labels-create-ad-label .ad-label-filter-fb-campaigns-list').html(all_campaigns);
            
        } else {
            
            var message = '<li class="list-group-item no-results">'
                               + data.message
                           + '</li>';
                
            $('.main #fb-labels-create-ad-label .ad-label-filter-fb-campaigns-list').html(message);
            
        }

    };
    
    /*
     * Display Ad Sets list in the opened modal
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.load_select_ad_sets_for_ad_label = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var all_campaigns = '';
            
            for ( var c = 0; c < data.ad_sets.data.length; c++ ) {
                
                all_campaigns += '<li class="list-group-item">'
                                   + '<a href="#" data-id="' + data.ad_sets.data[c].id + '">'
                                       + data.ad_sets.data[c].name
                                   + '</a>'
                               + '</li>';
                
            }
            
            $('.main #fb-labels-create-ad-label .fb-labels-filter-fb-adsets-list').html(all_campaigns);
            
        } else {
            
            var message = '<li class="list-group-item no-results">'
                               + data.message
                           + '</li>';
            
            $('.main #fb-labels-create-ad-label .fb-labels-filter-fb-adsets-list').html(message);
            
        }

    };
    
    /*
     * Display selected campaign in the opened modal
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.select_facebook_campaign_for_ad_label = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {

            if ( data.campaign.objective !== 'LINK_CLICKS' ) {
                
                // Display alert
                Main.popup_fon('sube', words.campaign_objective_not_supported, 1500, 2000);
                return;
                
            }
            
            if ( data.ad_sets.data.length ) {

                var ad_sets = '';

                for ( var e = 0; e < data.ad_sets.data.length; e++ ) {

                    ad_sets += '<li class="list-group-item">'
                                   + '<a href="#" data-id="' + data.ad_sets.data[e].id + '">'
                                       + data.ad_sets.data[e].name
                                   + '</a>'
                               + '</li>';

                }

                $('.main #fb-labels-create-ad-label .fb-labels-filter-fb-adsets-list').html(ad_sets);
                $('.main #fb-labels-create-ad-label .fb-labels-selected-ad-campaign').text(data.campaign.name);
                $('.main #fb-labels-create-ad-label .fb-labels-selected-ad-campaign').attr('data-id', data.campaign.id);                  

            } else {

                // Display alert
                Main.popup_fon('sube', words.selected_campaign_not_has_ad_sets, 1500, 2000);                    

            }
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
        // Display All Ad Sets
        $( '.main #fb-labels-create-ad-label .ads-selected-ad-set' ).text(words.ad_sets);
        $( '.main #fb-labels-create-ad-label .ads-selected-ad-set' ).removeAttr('data-id');
        $( '.main #fb-labels-create-ad-label .fb-labels-filter-fb-adsets' ).val('');

    };
    
    /*
     * Display ad label creation response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.fb_labels_create_new_ad_label = function ( status, data ) {
        
        // Empty response
        $('.main #fb-labels-create-ad-label .alerts-display-reports').empty();        
        
        if ( data.response ) {
            
            var response = '';
            
            var success = 0;
            
            for ( var d = 0; d < data.response.length; d++ ) {
                
                var status = 'error';
                
                if ( typeof data.response[d].ads !== 'undefined' ) {
                    
                    if ( typeof data.response[d].ads.length !== 'undefined' ) {
                        
                        for ( var a = 0; a < data.response[d].ads.length; a++ ) {
                            
                            if ( data.response[d].ads[a].success ) {
                                status = 'success';
                                success++;
                            }

                            response += '<div class="row alert-' + status + ' clean">'
                                            + '<div class="col-1">'
                                                + '<i class="icon-bell"></i>'
                                            + '</div>'
                                            + '<div class="col-11">'
                                                + '<h3>'
                                                    + data.response[d].ads[a].message
                                                + '</h3>'
                                                + '<p>'
                                                    + data.response[d].ads[a].description
                                                + '</p>'
                                            + '</div>'                         
                                        + '</div>';
                            
                        }
                        
                    }
                    
                } else {
                
                    if ( data.response[d].success ) {
                        status = 'success';
                        success++;
                    }

                    response += '<div class="row alert-' + status + ' clean">'
                                    + '<div class="col-1">'
                                        + '<i class="icon-bell"></i>'
                                    + '</div>'
                                    + '<div class="col-11">'
                                        + '<h3>'
                                            + data.response[d].message
                                        + '</h3>'
                                        + '<p>'
                                            + data.response[d].description
                                        + '</p>'
                                    + '</div>'                         
                                + '</div>';
                        
                }
                
            }

            if ( success ) {
                
                // Delete preview
                if ( typeof Main.preview !== 'undefined' ) {

                    // Delete preview's media
                    delete Main.preview;

                }
                
                // Reset Form
                $('.main .facebook-ads-create-ad-set')[0].reset();                

                // Load Ad's campaaigns for Ad Label
                Main.load_campaigs_list_for_ad_label();

                // Load Ad Sets
                Main.load_list_ad_sets_for_ad_label();
                
                // Display All Campaigns 
                $( '.main #fb-labels-create-ad-label .fb-labels-selected-ad-campaign' ).text(words.ad_campaigns);
                $( '.main #fb-labels-create-ad-label .fb-labels-selected-ad-campaign' ).removeAttr('data-id');

                // Display All Adsets
                $( '.main #fb-labels-create-ad-label .fb-labels-selected-ad-set' ).text(words.ad_sets);
                $( '.main #fb-labels-create-ad-label .fb-labels-selected-ad-set' ).removeAttr('data-id');

                if ( $('.main #fb-labels-create-ad-label a[href="#fb-labels-select-ad-set"]').attr('aria-expanded') === 'true' ) {

                    // Hide Adset Ad Sets tab
                    $('.main #fb-labels-create-ad-label a[href="#fb-labels-select-ad-set"]').click();

                }

                if ( $('.main #fb-labels-create-ad-label a[href="#fb-labels-add-preferences"]').attr('aria-expanded') === 'true' ) {

                    // Hide Preferences tab
                    $('.main #fb-labels-create-ad-label a[href="#fb-labels-add-preferences"]').click();

                }
                
                // Empty label's name input
                $( '.main #fb-labels-create-ad-label .fb-labels-ad-label-name' ).val('');
                
                // Load all AD Labels
                Main.fb_labels_load_all(1);

            }

            // Display response
            $('.main #fb-labels-create-ad-label .alerts-display-reports').html(response);

        }
        
    };
    
    /*
     * Display facebook ad labels
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.fb_labels_load_all = function ( status, data ) {
        
        $('.main #automatization-ad-labels .btn-previous').addClass('btn-disabled');
        $('.main #automatization-ad-labels .btn-next').addClass('btn-disabled');
        

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var all_labels = '';
            
            for ( var e = 0; e < data.labels.length; e++ ) {
                
                all_labels += '<tr>'
                                + '<th scope="row">'
                                    + '<div class="checkbox-option-select">'
                                        + '<input id="ad-labels-' + data.labels[e].label_id + '" name="ad-labels-' + data.labels[e].label_id + '" type="checkbox" data-id="' + data.labels[e].label_id + '">'
                                        + '<label for="ad-labels-' + data.labels[e].label_id + '"></label>'
                                    + '</div>'
                                + '</th>'
                                + '<td>'
                                    + data.labels[e].label_name
                                + '</td>'
                                + '<td>'
                                    + words.link_clicks
                                + '</td>'
                                + '<td>'
                                    + '0'
                                + '</td>'
                                + '<td>'
                                    + '0'
                                + '</td>' 
                            + '</tr>';
                
            }
            
            // Display all Ad Labels
            $('.main #automatization-ad-labels tbody').html(all_labels);
            
            if ( data.page > 1 ) {

                $('.main #automatization-ad-labels .btn-previous').removeClass('btn-disabled');
                $('.main #automatization-ad-labels .btn-previous').attr('data-page', (parseInt(data.page) - 1));

            }
            
            if ( (parseInt(data.page) * 10 ) < data.total_labels ) {

                $('.main #automatization-ad-labels .btn-next').removeClass('btn-disabled');
                $('.main #automatization-ad-labels .btn-next').attr('data-page', (parseInt(data.page) + 1));

            }
            
        } else {
            
            var message = '<tr>'
                            + '<td colspan="5" class="p-3">'
                                + data.message
                            + '</td>' 
                        + '</tr>';
                
            // Display message
            $('.main #automatization-ad-labels tbody').html(message);
            
        }

    };
    
    /*
     * Display Ad Labels deletion response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.delete_ad_labels = function ( status, data ) {
        
        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Uncheck all checboxes
            $( '.main #ad-labels-select-all' ).prop('checked', false);
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Load all AD Labels
            Main.fb_labels_load_all(1);
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };
    
    /*
     * Display Ad Identity
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.ad_labels_load_ad_identity = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Show identity
            $('.main .modal.show').find('.ad-identity').show();
            
            var all_pages = '';
            var instagram_button = '<button class="btn btn-secondary dropdown-toggle ads-instagram-id btn-select" data-id="" type="button" id="dropdownInstagramSelect" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                                    + '</button>';
            var instagram_accounts = '';
            
            for ( var p = 0; p < data.account_pages.data.length; p++ ) {
                
                if ( p < 1 ) {
                    
                    if ( data.connected_instagram.data.length > 0 ) {
                        
                        instagram_button = '<button class="btn btn-secondary dropdown-toggle ads-instagram-id btn-select" data-id="' + data.connected_instagram.data[0].id + '" type="button" id="dropdownInstagramSelect" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                                                + '<img src="' + data.connected_instagram.data[0].profile_pic + '">'
                                                + data.connected_instagram.data[0].username
                                            + '</button>';                        
                        
                        instagram_accounts += '<li class="list-group-item">'
                                        + '<a href="#" data-id="' + data.connected_instagram.data[0].id + '">'
                                            + '<img src="' + data.connected_instagram.data[0].profile_pic + '">'
                                            + data.connected_instagram.data[0].username
                                        + '</a>'
                                    + '</li>';                        
                        
                    }
                    
                }
                
                all_pages += '<li class="list-group-item">'
                                + '<a href="#" data-id="' + data.account_pages.data[p].id + '">'
                                    + '<img src="' + data.account_pages.data[p].picture.data.url + '">'
                                    + data.account_pages.data[p].name
                                + '</a>'
                            + '</li>';
                
            }
            
            // Set facebook's identity
            var facebook_identity = '<div class="row">'
                                + '<div class="col-12">'
                                    + '<h3>'
                                        + 'Facebook Page'
                                    + '</h3>'
                                    + '<p>'
                                        + data.words.your_facebook_page_represents_business
                                    + '</p>'
                                + '</div>'
                            + '</div>'
                            + '<div class="row">'
                                + '<div class="col-12 links-clicks-preview-settings text-center">'
                                    + '<div class="dropdown">'
                                        + '<button class="btn btn-secondary dropdown-toggle ads-fb-page-id btn-select" data-id="' + data.account_pages.data[0].id + '" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                                            + '<img src="' + data.account_pages.data[0].picture.data.url + '">'
                                            + data.account_pages.data[0].name
                                        + '</button>'
                                        + '<div class="dropdown-menu ads-campaign-dropdown" aria-labelledby="dropdownMenuButton">'
                                            + '<div class="card">'
                                                + '<div class="card-head">'
                                                    + '<input type="text" class="ad-creation-filter-fb-pages" placeholder="' + data.words.search_for_pages + '">'
                                                + '</div>'
                                                + '<div class="card-body">'
                                                    + '<ul class="list-group ad-labels-filter-fb-pages-list">'
                                                        + all_pages
                                                    + '</ul>'
                                                + '</div>'
                                            + '</div>'
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                            + '</div>';
                    
            $('.main .modal.show').find('.ad-identity-facebook-pages').html(facebook_identity);
            
            // Set instagram's identity
            var instagram_identity = '<div class="row">'
                                        + '<div class="col-12">'
                                            + '<h3>'
                                                + 'Instagram'
                                            + '</h3>'
                                            + '<p>'
                                                + data.words.instagram_below_connected_facebook
                                            + '</p>'
                                        + '</div>'
                                    + '</div>'
                                    + '<div class="row ad-labels-connect-instagram-account">'
                                        + '<div class="col-12 links-clicks-preview-settings text-center">'
                                            + '<div class="dropdown">'
                                                + instagram_button
                                                + '<div class="dropdown-menu ads-campaign-dropdown" aria-labelledby="dropdownInstagramSelect">'
                                                    + '<div class="card">'
                                                        + '<div class="card-head">'
                                                            + '<input type="text" class="ad-creation-filter-instagram-account" placeholder="' + data.words.search_for_accounts + '">'
                                                        + '</div>'
                                                        + '<div class="card-body">'
                                                            + '<ul class="list-group ad-creation-filter-instagram-accounts-list">'
                                                                + instagram_accounts
                                                            + '</ul>'
                                                        + '</div>'
                                                    + '</div>'
                                                + '</div>'
                                            + '</div>'
                                        + '</div>'
                                    + '</div>';
                    
            $('.main .modal.show').find('.ad-identity-instagram-accounts').html(instagram_identity);            
            
        } else {
            
            // Hide identity
            $('.main .modal.show').find('.ad-identity').hide();    
            $('.main .modal.show').find('.ad-identity').empty();
            
        }

    };
    
    /*
     * Display reports response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.
     */
    Main.methods.ad_labels_reports_by_time = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var reports = '';
            
            for( var a = 0; a < data.reports.length; a++ ) {
                
                reports += '<tr>'
                                + '<td>'
                                    + data.reports[a].datetime
                                + '</td>'
                                + '<td>'
                                    + data.reports[a].label_name
                                + '</td>'
                                + '<td>'
                                    + data.reports[a].success
                                + '</td>'
                                + '<td>'
                                    + data.reports[a].errors
                                + '</td>'
                            + '</tr>';
                
            } 
            
            // Show results
            $('.main #fb-labels-generate-reports tbody').html(reports);
            
        } else {
            
            var message = '<tr>'
                              + '<td colspan="4">'
                                  + '<p>'
                                      + data.message
                                  + '</p>'
                              + '</td>'
                          + '</tr>';
                  
            // Show results
            $('.main #fb-labels-generate-reports tbody').html(message);                  
            
        }        
        
    };
    
    /*******************************
    FORMS
    ********************************/ 
   
    /*
     * Save Ad's Label
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */
    $(document).on('submit', '.main .fb-labels-create-new-ad-label', function (e) {
        e.preventDefault();
        
        // Get Campaign's ID
        var campaign_id = $( '.main .fb-labels-selected-ad-campaign' ).attr( 'data-id' );
        
        // Get Ad Set's ID
        var ad_set_id = $( '.main .fb-labels-selected-ad-set' ).attr( 'data-id' );
        
        // Get Ad Label's name
        var label_name = $( '.main .fb-labels-ad-label-name' ).val();
        
        // Get Spending Limit
        var spending_limit = $( '.main .fb-labels-ad-spending-limit' ).val();
        
        // Create an object with form data
        var data = {
            action: 'create_new_ad_label',
            campaign_id: campaign_id,
            ad_set_id: ad_set_id,
            label_name: label_name,
            spending_limit: spending_limit
        };
        
        // Get Facebook Page ID
        if ( $('.main #fb-labels-add-preferences').find('.ads-fb-page-id').attr('data-id') ) {
            data['fb_page_id'] = $('.main #fb-labels-add-preferences').find('.ads-fb-page-id').attr('data-id');
        }
        
        // Verify if instagram account is selected
        if ( $('.main #fb-labels-add-preferences').find('.ads-instagram-id').attr('data-id') ) {
            data['instagram_id'] = $('.main #fb-labels-add-preferences').find('.ads-instagram-id').attr('data-id');
        }

        // Set CSRF
        data[$(this).attr('data-csrf')] = $('.main input[name="' + $(this).attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads?automatization=ad_labels', 'POST', data, 'fb_labels_create_new_ad_label');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Show reports
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */
    $(document).on('submit', '.main .ad-labels-generate-report', function (e) {
        e.preventDefault();
        
        // Get time order
        var time = $(this).find('.ad-labels-order-reports-by-time').attr('data-time');
        
        // Create an object with form data
        var data = {
            action: 'ad_labels_reports_by_time',
            order: time
        };
        
        // Set CSRF
        data[$(this).attr('data-csrf')] = $('.main input[name="' + $(this).attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads?automatization=ad_labels', 'POST', data, 'ad_labels_reports_by_time');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*******************************
    DEPENDENCIES
    ********************************/
   
    
});