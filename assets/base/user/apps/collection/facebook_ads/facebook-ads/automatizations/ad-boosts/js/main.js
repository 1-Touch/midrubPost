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
    Main.load_campaigns_list_for_ad_boost = function() {
        
        var key = '';
        
        key = $('.main #fb-boosts-create-ad-boost .ad-boost-filter-fb-campaigns').val();
        
        var data = {
            action: 'load_select_ad_campaigns',
            key: key
        };
        
        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_campaigns_list_for_ad_boost');
        
    };
    
    /*
     * Load Ad Sets for select dropdown
     * 
     * @since   0.0.7.7
     */ 
    Main.load_list_ad_sets_for_ad_boost = function() {
        
        var key = $('.main #fb-boosts-create-ad-boost .fb-boosts-filter-fb-adsets').val();
        
        var data = {
            action: 'load_select_ad_sets',
            campaign_id: $('.main #fb-boosts-create-ad-boost .fb-boosts-selected-ad-campaign').attr('data-id'),
            key: key
        };
        
        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_select_ad_sets_for_ad_boost');
        
    };
    
    /*
     * Load Facebook Pages and Instagram Accounts for select dropdown
     * 
     * @since   0.0.7.7
    */ 
    Main.load_identity_for_ad_boost = function() {
        
        var data = {
            action: 'load_ad_identity'
        };

        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'ad_boosts_load_ad_identity');
        
    };
    
    /*
     * Load Facebook Ad boosts
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.7.7
     */ 
    Main.fb_boosts_load_all = function(page) {
        
        var data = {
            action: 'fb_boosts_load_all',
            page: page
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads?automatization=ad_boosts', 'GET', data, 'fb_boosts_load_all');
        
    };

    /*
     * Load Automatization's content
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.7.7
     */ 
    Main.automatization_ad_boosts = function(page) {
        
        var ad_boosts = '<div class="row">'
                           + '<div class="col-xl-12">'
                              + '<div class="table-responsive">'
                                 + '<table class="table">'
                                    + '<thead>'
                                       + '<tr>'
                                          + '<th scope="row" colspan="3">'
                                             + '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#fb-boosts-create-ad-boost">'
                                                 + '<i class="icon-rocket"></i>'
                                                 + ad_boost_words.fb_boosts_new_ad_boost
                                             + '</button>'
                                             + '<button type="button" class="btn btn-dark ads-delete-ad-boosts">'
                                                 + '<i class="icon-trash"></i>'
                                                 + ad_boost_words.delete_this
                                             + '</button>'
                                          + '</th>'
                                          + '<th scope="row" colspan="2">'
                                             + '<button type="button" class="btn btn-dark pull-right btn-ads-reports" data-toggle="modal" data-target="#fb-boosts-generate-reports">'
                                                 + '<i class="icon-pie-chart"></i>'
                                                 + ad_boost_words.reports
                                             + '</button>'
                                          + '</th>'
                                       + '</tr>'
                                       + '<tr>'
                                          + '<th scope="row">'
                                             + '<div class="checkbox-option-select">'
                                                + '<input id="ad-boosts-select-all" name="ad-boosts-select-all" type="checkbox">'
                                                + '<label for="ad-boosts-select-all"></label>'
                                             + '</div>'
                                          + '</th>'
                                          + '<th scope="col">'
                                             + ad_boost_words.fb_boosts_name
                                          + '</th>'
                                          + '<th scope="col">'
                                             + ad_boost_words.fb_boosts_category  
                                          + '</th>'
                                          + '<th scope="col">'
                                             + ad_boost_words.fb_boosts_created_ads  
                                          + '</th>'
                                          + '<th scope="col">'
                                             + ad_boost_words.fb_boosts_active_ads              
                                          + '</th>'
                                       + '</tr>'
                                    + '</thead>'
                                    + '<tbody>'
                                    + '</tbody>'
                                    + '<tfoot>'
                                       + '<tr>'
                                          + '<td colspan="5" class="text-right">'
                                             + '<button type="button" class="btn btn-dark btn-previous btn-ad-boosts-pagination btn-disabled">'
                                                 + '<i class="far fa-arrow-alt-circle-left"></i>'
                                                 + ad_boost_words.previous
                                             + '</button>'
                                             + '<button type="button" class="btn btn-dark btn-next btn-ad-boosts-pagination btn-disabled" data-page="2">'
                                                 + ad_boost_words.next
                                             + '<i class="far fa-arrow-alt-circle-right"></i>'
                                             + '</button>'
                                          + '</td>'
                                       + '</tr>'
                                    + '</tfoot>'
                                 + '</table>'
                              + '</div>'
                           + '</div>'
                        + '</div>';
                
        $('.main #automatization-ad-boosts').html(ad_boosts);
        
        // Load all AD boosts
        Main.fb_boosts_load_all(1);
        
    };

    /*******************************
    ACTIONS
    ********************************/
   
    /*
     * Filter the Campaigns
     * 
     * @since   0.0.7.7
     */        
    $(document).on('keyup', '.main .ad-boost-filter-fb-campaigns', function () {

        // Load Ad's campaaigns for Ad boost
        Main.load_campaigns_list_for_ad_boost();

    });  

    /*
     * Detect modal open
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */   
    $( '.main #fb-boosts-create-ad-boost' ).on('shown.bs.modal', function (e) {
        
        // Empty response
        $('.main #fb-boosts-create-ad-boost .alerts-display-reports').empty(); 
        
        // Load Ad's campaaigns for Ad boost
        Main.load_campaigns_list_for_ad_boost();
        
        // Load Ad Sets
        Main.load_list_ad_sets_for_ad_boost();
        
        // Load Facebook and Instagram accounts
        Main.load_identity_for_ad_boost();
        
    });
    
    /*
     * Expand Ad Sets
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $('.main #fb-boosts-create-ad-boost #fb-boosts-select-ad-set').on('show.bs.collapse', function (e) {

        if (!$('.main #fb-boosts-create-ad-boost .fb-boosts-selected-ad-campaign').attr('data-id')) {

            // Display alert
            Main.popup_fon('sube', words.please_select_ad_campaign, 1500, 2000);
            return false;

        }

    });
    
    /*
     * Expand boost's preferences
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $('.main #fb-boosts-create-ad-boost #fb-boosts-add-preferences').on('show.bs.collapse', function (e) {

        if (!$('.main #fb-boosts-create-ad-boost .fb-boosts-selected-ad-set').attr('data-id')) {

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
    $( document ).on( 'click', '.main #fb-boosts-create-ad-boost .ad-boost-filter-fb-campaigns-list li a', function (e) {
        e.preventDefault();
        
        // Get campaign id
        var campaign_id = $(this).attr('data-id');
        
        // Create an object with form data
        var data = {
            action: 'select_facebook_campaign',
            campaign_id: campaign_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'select_facebook_campaign_for_ad_boost');

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
    $( document ).on( 'click', '.main .fb-boosts-filter-fb-adsets-list li a', function (e) {
        e.preventDefault();
        
        $('.main #fb-boosts-create-ad-boost .fb-boosts-selected-ad-set').text($(this).text());
        $('.main #fb-boosts-create-ad-boost .fb-boosts-selected-ad-set').attr('data-id', $(this).attr('data-id'));
        
    });
    
    /*
     * Detect all Ad's boosts selection
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', '.main #ad-boosts-select-all', function (e) {
        
        setTimeout(function(){
            
            if ( $( 'main #ad-boosts-select-all' ).is(':checked') ) {

                $( '.main #automatization-ad-boosts tbody input[type="checkbox"]' ).prop('checked', true);

            } else {

                $( '.main #automatization-ad-boosts tbody input[type="checkbox"]' ).prop('checked', false);

            }
        
        },500);
        
    });

    /*
     * Detect Ad's boosts pagination click
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $( document ).on( 'click', '.main .btn-ad-boosts-pagination', function (e) {
        e.preventDefault();
        
        // Get page number
        var page = $(this).attr('data-page');

        // Load all AD boosts
        Main.fb_boosts_load_all(page);
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Delete ad boosts
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $( document ).on( 'click', 'main .ads-delete-ad-boosts', function (e) {
        
        // Get all selected ad's boosts
        var boosts = $('.main #automatization-ad-boosts tbody input[type="checkbox"]');
        
        var selected = [];
        
        // List all ad's boosts
        for ( var d = 0; d < boosts.length; d++ ) {

            if ( boosts[d].checked ) {
                selected.push($(boosts[d]).attr('data-id'));
            }
            
        }
        
        // Create an object with form data
        var data = {
            action: 'delete_ad_boosts',
            boosts: Object.entries(selected)
        };

        // Set CSRF
        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads?automatization=ad_boosts', 'POST', data, 'delete_ad_boosts');

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
    $( document ).on( 'click', '.main .ad-boosts-history-reports-by-time a', function (e) {
        e.preventDefault();
        
        // Display selected time
        $('.main .ad-boosts-order-reports-by-time').html($(this).html());
        $('.main .ad-boosts-order-reports-by-time').attr('data-time', $(this).attr('data-time'));
        
    });
    
    /*
     * Change the Facebook Page
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $( document ).on( 'click', 'main .ad-boosts-filter-fb-pages-list li a', function (e) {
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
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'ad_boosts_display_connected_instagram_accounts');

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
    Main.methods.ad_boosts_display_connected_instagram_accounts = function ( status, data ) {
        
        // Hide accounts
        $('.main .ad-boosts-connect-instagram-account .btn-select').empty();
        $('.main .ad-boosts-connect-instagram-account .btn-select').removeAttr('data-id');
        $('.main .ad-boosts-connect-instagram-account ul').empty();

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
                        $('.main .ad-boosts-connect-instagram-account .btn-select').html(text);
                        $('.main .ad-boosts-connect-instagram-account .btn-select').attr('data-id', data.accounts.data[g].id);
                        
                    }
                    
                    all_accounts += '<li class="list-group-item">'
                                        + '<a href="#" data-id="' + data.accounts.data[g].id + '">'
                                            + '<img src="' + data.accounts.data[g].profile_pic + '">'
                                            + data.accounts.data[g].username
                                        + '</a>'
                                    + '</li>';
                    
                }
                
                // Add accounts
                $('.main .ad-boosts-connect-instagram-account ul').html(all_accounts);
                
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
    Main.methods.load_campaigns_list_for_ad_boost = function ( status, data ) {
        
        // Uncheck all checboxes
        $( '.main #automatization-ad-boosts tbody input[type="checkbox"]' ).prop('checked', false);
        $( '.main #ad-boosts-select-all' ).prop('checked', false);

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
            
            $('.main #fb-boosts-create-ad-boost .ad-boost-filter-fb-campaigns-list').html(all_campaigns);
            
        } else {
            
            var message = '<li class="list-group-item no-results">'
                               + data.message
                           + '</li>';
                
            $('.main #fb-boosts-create-ad-boost .ad-boost-filter-fb-campaigns-list').html(message);
            
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
    Main.methods.load_select_ad_sets_for_ad_boost = function ( status, data ) {

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
            
            $('.main #fb-boosts-create-ad-boost .fb-boosts-filter-fb-adsets-list').html(all_campaigns);
            
        } else {
            
            var message = '<li class="list-group-item no-results">'
                               + data.message
                           + '</li>';
            
            $('.main #fb-boosts-create-ad-boost .fb-boosts-filter-fb-adsets-list').html(message);
            
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
    Main.methods.select_facebook_campaign_for_ad_boost = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {

            if ( data.campaign.objective !== 'POST_ENGAGEMENT' ) {
                
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

                $('.main #fb-boosts-create-ad-boost .fb-boosts-filter-fb-adsets-list').html(ad_sets);
                $('.main #fb-boosts-create-ad-boost .fb-boosts-selected-ad-campaign').text(data.campaign.name);
                $('.main #fb-boosts-create-ad-boost .fb-boosts-selected-ad-campaign').attr('data-id', data.campaign.id);                  

            } else {

                // Display alert
                Main.popup_fon('sube', words.selected_campaign_not_has_ad_sets, 1500, 2000);                    

            }
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
        // Display All Ad Sets
        $( '.main #fb-boosts-create-ad-boost .ads-selected-ad-set' ).text(words.ad_sets);
        $( '.main #fb-boosts-create-ad-boost .ads-selected-ad-set' ).removeAttr('data-id');
        $( '.main #fb-boosts-create-ad-boost .fb-boosts-filter-fb-adsets' ).val('');

    };
    
    /*
     * Display ad boost creation response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.fb_boosts_create_new_ad_boost = function ( status, data ) {
        
        // Empty response
        $('.main #fb-boosts-create-ad-boost .alerts-display-reports').empty();        
        
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

                // Load Ad's campaaigns for Ad boost
                Main.load_campaigns_list_for_ad_boost();

                // Load Ad Sets
                Main.load_list_ad_sets_for_ad_boost();
                
                // Display All Campaigns 
                $( '.main #fb-boosts-create-ad-boost .fb-boosts-selected-ad-campaign' ).text(words.ad_campaigns);
                $( '.main #fb-boosts-create-ad-boost .fb-boosts-selected-ad-campaign' ).removeAttr('data-id');

                // Display All Adsets
                $( '.main #fb-boosts-create-ad-boost .fb-boosts-selected-ad-set' ).text(words.ad_sets);
                $( '.main #fb-boosts-create-ad-boost .fb-boosts-selected-ad-set' ).removeAttr('data-id');

                if ( $('.main #fb-boosts-create-ad-boost a[href="#fb-boosts-select-ad-set"]').attr('aria-expanded') === 'true' ) {

                    // Hide Adset Ad Sets tab
                    $('.main #fb-boosts-create-ad-boost a[href="#fb-boosts-select-ad-set"]').click();

                }

                if ( $('.main #fb-boosts-create-ad-boost a[href="#fb-boosts-add-preferences"]').attr('aria-expanded') === 'true' ) {

                    // Hide Preferences tab
                    $('.main #fb-boosts-create-ad-boost a[href="#fb-boosts-add-preferences"]').click();

                }
                
                // Empty boost's name input
                $( '.main #fb-boosts-create-ad-boost .fb-boosts-ad-boost-name' ).val('');
                
                // Load all AD boosts
                Main.fb_boosts_load_all(1);

            }

            // Display response
            $('.main #fb-boosts-create-ad-boost .alerts-display-reports').html(response);

        }
        
    };
    
    /*
     * Display facebook ad boosts
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.fb_boosts_load_all = function ( status, data ) {
        
        $('.main #automatization-ad-boosts .btn-previous').addClass('btn-disabled');
        $('.main #automatization-ad-boosts .btn-next').addClass('btn-disabled');
        

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var all_boosts = '';
            
            for ( var e = 0; e < data.boosts.length; e++ ) {
                
                all_boosts += '<tr>'
                                + '<th scope="row">'
                                    + '<div class="checkbox-option-select">'
                                        + '<input id="ad-boosts-' + data.boosts[e].boost_id + '" name="ad-boosts-' + data.boosts[e].boost_id + '" type="checkbox" data-id="' + data.boosts[e].boost_id + '">'
                                        + '<label for="ad-boosts-' + data.boosts[e].boost_id + '"></label>'
                                    + '</div>'
                                + '</th>'
                                + '<td>'
                                    + data.boosts[e].boost_name
                                + '</td>'
                                + '<td>'
                                    + 'Post Engagement'
                                + '</td>'
                                + '<td>'
                                    + '0'
                                + '</td>'
                                + '<td>'
                                    + '0'
                                + '</td>' 
                            + '</tr>';
                
            }
            
            // Display all Ad boosts
            $('.main #automatization-ad-boosts tbody').html(all_boosts);
            
            if ( data.page > 1 ) {

                $('.main #automatization-ad-boosts .btn-previous').removeClass('btn-disabled');
                $('.main #automatization-ad-boosts .btn-previous').attr('data-page', (parseInt(data.page) - 1));

            }
            
            if ( (parseInt(data.page) * 10 ) < data.total_boosts ) {

                $('.main #automatization-ad-boosts .btn-next').removeClass('btn-disabled');
                $('.main #automatization-ad-boosts .btn-next').attr('data-page', (parseInt(data.page) + 1));

            }
            
        } else {
            
            var message = '<tr>'
                            + '<td colspan="5" class="p-3">'
                                + data.message
                            + '</td>' 
                        + '</tr>';
                
            // Display message
            $('.main #automatization-ad-boosts tbody').html(message);
            
        }

    };
    
    /*
     * Display Ad boosts deletion response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.delete_ad_boosts = function ( status, data ) {
        
        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Uncheck all checboxes
            $( '.main #ad-boosts-select-all' ).prop('checked', false);
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Load all AD boosts
            Main.fb_boosts_load_all(1);
            
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
    Main.methods.ad_boosts_load_ad_identity = function ( status, data ) {

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
                                        + '<div class="dropdown-menu ads-campaign-dropdown" aria-boostledby="dropdownMenuButton">'
                                            + '<div class="card">'
                                                + '<div class="card-head">'
                                                    + '<input type="text" class="ad-creation-filter-fb-pages" placeholder="' + data.words.search_for_pages + '">'
                                                + '</div>'
                                                + '<div class="card-body">'
                                                    + '<ul class="list-group ad-boosts-filter-fb-pages-list">'
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
                                    + '<div class="row ad-boosts-connect-instagram-account">'
                                        + '<div class="col-12 links-clicks-preview-settings text-center">'
                                            + '<div class="dropdown">'
                                                + instagram_button
                                                + '<div class="dropdown-menu ads-campaign-dropdown" aria-boostledby="dropdownInstagramSelect">'
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
    Main.methods.ad_boosts_reports_by_time = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var reports = '';
            
            for( var a = 0; a < data.reports.length; a++ ) {
                
                reports += '<tr>'
                                + '<td>'
                                    + data.reports[a].datetime
                                + '</td>'
                                + '<td>'
                                    + data.reports[a].boost_name
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
            $('.main #fb-boosts-generate-reports tbody').html(reports);
            
        } else {
            
            var message = '<tr>'
                              + '<td colspan="4">'
                                  + '<p>'
                                      + data.message
                                  + '</p>'
                              + '</td>'
                          + '</tr>';
                  
            // Show results
            $('.main #fb-boosts-generate-reports tbody').html(message);                  
            
        }        
        
    };
    
    /*******************************
    FORMS
    ********************************/ 
   
    /*
     * Save Ad's boost
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */
    $(document).on('submit', '.main .fb-boosts-create-new-ad-boost', function (e) {
        e.preventDefault();
        
        // Get Campaign's ID
        var campaign_id = $( '.main .fb-boosts-selected-ad-campaign' ).attr( 'data-id' );
        
        // Get Ad Set's ID
        var ad_set_id = $( '.main .fb-boosts-selected-ad-set' ).attr( 'data-id' );
        
        // Get Ad boost's name
        var boost_name = $( '.main .fb-boosts-ad-boost-name' ).val();
        
        // Get Spending Limit
        var spending_limit = $( '.main .fb-boosts-ad-spending-limit' ).val();
        
        // Create an object with form data
        var data = {
            action: 'create_new_ad_boost',
            campaign_id: campaign_id,
            ad_set_id: ad_set_id,
            boost_name: boost_name,
            spending_limit: spending_limit
        };
        
        // Get Facebook Page ID
        if ( $('.main #fb-boosts-add-preferences').find('.ads-fb-page-id').attr('data-id') ) {
            data['fb_page_id'] = $('.main #fb-boosts-add-preferences').find('.ads-fb-page-id').attr('data-id');
        }
        
        // Verify if instagram account is selected
        if ( $('.main #fb-boosts-add-preferences').find('.ads-instagram-id').attr('data-id') ) {
            data['instagram_id'] = $('.main #fb-boosts-add-preferences').find('.ads-instagram-id').attr('data-id');
        }

        // Set CSRF
        data[$(this).attr('data-csrf')] = $('.main input[name="' + $(this).attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads?automatization=ad_boosts', 'POST', data, 'fb_boosts_create_new_ad_boost');

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
    $(document).on('submit', '.main .ad-boosts-generate-report', function (e) {
        e.preventDefault();
        
        // Get time order
        var time = $(this).find('.ad-boosts-order-reports-by-time').attr('data-time');
        
        // Create an object with form data
        var data = {
            action: 'ad_boosts_reports_by_time',
            order: time
        };
        
        // Set CSRF
        data[$(this).attr('data-csrf')] = $('.main input[name="' + $(this).attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads?automatization=ad_boosts', 'POST', data, 'ad_boosts_reports_by_time');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*******************************
    DEPENDENCIES
    ********************************/
   
    
});