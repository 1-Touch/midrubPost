/*
 * Main javascript file
*/

jQuery(document).ready( function ($) {
    'use strict';
    
    /*
     * Get the website's url
     */
    var url =  $('meta[name=url]').attr('content');
    
    // Define preview
    Main.preview = {};
    
    
    /*******************************
    METHODS
    ********************************/
   
    /*
     * Reload accounts
     * 
     * @since   0.0.7.6
     */
    Main.reload_accounts = function () {
        
        // Load all ADS Accounts
        Main.quick_ad_accounts();
        Main.load_ad_accounts(1);
        
    };
   
    /*
     * Load AD Accounts by page
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.7.6
     */
    Main.load_ad_accounts = function ( page ) {
            
        var data = {
            action: 'load_ad_accounts',
            key: $('.main .accounts-manager-search-for-accounts').val(),
            page: page
        };

        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_ad_accounts');
        
    };
    
    /*
     * Load posts for boosting
     * 
     * @since   0.0.7.7
     */
    Main.load_posts_for_boosting = function () {
            
        var data = {
            action: 'load_posts_for_boosting'
        };
        
        // Get selected social network
        if ( $('.main .modal.show #post-engagement-from-facebook').hasClass('active') ) {
            
            data['network'] = 'facebook';
            data['key'] = $('.main .modal.show #post-engagement-from-facebook .post-engagement-search-posts').val();
            
        } else if ( $('.main .modal.show #post-engagement-from-instagram').hasClass('active') ) {
            
            data['network'] = 'instagram';
            data['key'] = $('.main .modal.show #post-engagement-from-instagram .post-engagement-search-posts').val();
            
        }
        
        // Get facebook page id
        if ( $('.main .modal.show #campaign-create-ads-post-engagement .ads-fb-page-id').attr('data-id') ) {
            
            // Set Facebook Page Id
            data['fb_page_id'] = $('.main .modal.show #campaign-create-ads-post-engagement .ads-fb-page-id').attr('data-id');
            
        }
        
        // Get instagram account id
        if ( $('.main .modal.show #campaign-create-ads-post-engagement .ads-instagram-id').attr('data-id') ) {
            
            // Set instagram account id
            data['instagram_id'] = $('.main .modal.show #campaign-create-ads-post-engagement .ads-instagram-id').attr('data-id');
            
        }

        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_posts_for_boosting');
        
    };
    
    /*
     * Load AD Accounts in the selection area
     * 
     * @since   0.0.7.6
     */
    Main.quick_ad_accounts = function () {
            
        var data = {
            action: 'quick_ad_accounts',
            key: $('.main .available-accounts-search-for-accounts').val()
        };

        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'quick_ad_accounts');
        
    }; 
    
    /*
     * Load AD Campaigns
     * 
     * @since   0.0.7.6
     */
    Main.load_ad_campaigns = function () {
            
        var data = {
            action: 'load_campaigns_by_pagination'
        };

        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_campaigns_by_pagination');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    };
    
    /*
     * Load AD Sets
     * 
     * @since   0.0.7.6
     */
    Main.load_ad_sets = function () {
            
        var data = {
            action: 'load_ad_sets_by_pagination'
        };

        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_ad_sets_by_pagination');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    };
    
    /*
     * Load Pixel's conversions
     * 
     * @since   0.0.7.6
     */
    Main.load_pixel_conversions = function () {
            
        var data = {
            action: 'load_pixel_conversions_by_pagination'
        };

        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_pixel_conversions_by_pagination');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    };
    
    /*
     * Get content by url
     * 
     * @param string geturl contains the url
     * 
     * @since   0.0.7.6
     */ 
    Main.get_page_content_by_url = function(geturl) {

        // Delete preview
        if ( typeof Main.preview === 'undefined' ) {

            // Define preview
            Main.preview = {};

        }
        
        // Set the preview url
        Main.preview.url = geturl;
        
        var data = {
            action: 'parse_url_content',
            url: 'url: ' + geturl
        };

        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/ajax/parse_url', 'POST', data, 'ads_display_url_preview');
        
    };
    
    /*
     * Load pixel's conversions
     * 
     * @since   0.0.7.6
     */ 
    Main.load_all_pixel_coversions = function() {
        
        var data = {
            action: 'load_all_pixel_coversions'
        };

        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_all_pixel_coversions');
        
    };  
    
    /*
     * Load Ad's identity
     * 
     * @since   0.0.7.6
     */ 
    Main.load_ad_identity = function() {
        
        var data = {
            action: 'load_ad_identity'
        };

        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_ad_identity');
        
    }; 
    
    /*
     * Load Ad's Account Details
     * 
     * @since   0.0.7.6
     */ 
    Main.load_ad_account_details = function() {
        
        var data = {
            action: 'load_ad_account_details'
        };

        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_ad_account_details');
        
    };    
  
    /*
     * Reload Ad's link preview
     * 
     * @since   0.0.7.6
    */
    Main.reload_ad_link_preview = function () {
        
        var preview_media = '';
        var preview_url = '';
        var preview_title = '';
        var preview_text = '';
        
        if ( $('.main #ads-create-campaign').hasClass('show') ) {

            var modal = '.main #myTabContent5 > .tab-pane.active';
            
            // Verify which kind of campaign objective we have
            switch ( $('.main .modal.show').find('.ads-campaign-objective').attr('data-id') ) {

                case 'LINK_CLICKS':

                    // Verify if url exists
                    if ( !$(modal + ' .website_url').val() ) {

                        // Empty preview
                        $(modal + ' .ad-preview-display .panel-body').empty();
                        return;

                    }

                    break;

            }

        } else if ( $('.main #ads-create-ad-set').hasClass('show') ) {

            var modal = '.main #myTabContent6 > .tab-pane.active';

        } else if ( $('.main #ads-create-new-ad').hasClass('show') ) {

            var modal = '.main #myTabContent7 > .tab-pane.active';

        }
 
        if ( typeof Main.preview.url_cover !== 'undefined' ) {

            // Set preview's media
            preview_media = '<img src="' + Main.preview.url_cover + '">';

        }
        
        // Verify if user wants to use a photo
        if ( $(modal + ' .ads-uploaded-photo').closest('.tab-pane').hasClass('active') ) {

            if ( typeof Main.preview.image !== 'undefined' ) {

                // Set preview's media
                preview_media = '<img src="' + Main.preview.image + '">';

            }
            
        }
        
        // Verify if user wants to use a video
        if ( $(modal + ' .ads-uploaded-video').closest('.tab-pane').hasClass('active') ) {

            if ( typeof Main.preview.video_source !== 'undefined' ) {

                // Set preview's media
                preview_media = '<video controls="true" style="width:100%;height:300px"><source src="' + Main.preview.video_source + '" type="video/mp4" /></video>';

            }
            
        }        

        if ( typeof Main.preview.url !== 'undefined' ) {

            // Set preview's url
            preview_url = Main.preview.url;

        }

        if ( typeof Main.preview.title !== 'undefined' ) {

            // Set preview's title
            preview_title = Main.preview.title;

        }    
        
        // Get text
        preview_text = $(modal + ' .text').val();
        
        var active = '';
        
        if ( $(modal).find('#links-clicks-preview-fb-desktop-feed').length < 1 ) {
            active = ' show active';
        } else if ( $(modal).find('#links-clicks-preview-fb-desktop-feed').hasClass('active') ) {
            active = ' show active';
        }
        
        // Set default headline
        var headline = preview_title;
        
        // Verify if headline is not empty
        if ( $(modal + ' .headline').val() ) {
            headline = $(modal + ' .headline').val();
        }
        
        // Set default description
        var description = '';
        
        // Verify if description is not empty
        if ( $(modal + ' .description').val() ) {
            description = $(modal + ' .description').val();
        }        
        
        var content_preview = '<div class="tab-content" id="links-clicks-preview">'
                                    + '<div class="tab-pane fade' + active + '" id="links-clicks-preview-fb-desktop-feed" role="tabpanel" aria-labelledby="links-clicks-preview-fb-desktop-feed-tab">'
                                        + '<table>'
                                            + '<thead>'
                                                + '<tr>'
                                                    + '<th colspan="3">'
                                                        + '<img src="' + url + 'assets/img/avatar-placeholder.png">'
                                                        + '<h3>'
                                                            + '<a href="#">'
                                                                + words.your_page_name
                                                            + '</a>'
                                                            + '<span>' + words.sponsored + ' - <i class="fas fa-globe-americas"></i></span>'
                                                        + '</h3>'
                                                    + '</th>'
                                                + '</tr>'
                                            + '</thead>'
                                            + '<tbody>'
                                                + '<tr>'
                                                    + '<td colspan="3">'
                                                        + '<p>'
                                                            + preview_text
                                                        + '</p>'
                                                    + '</td>'
                                                + '</tr>'
                                                + '<tr>'
                                                    + '<td colspan="3" class="clean">'
                                                        + '<table class="full">'
                                                            + '<tbody>'
                                                                + '<tr>'
                                                                    + '<td colspan="2">'
                                                                        + preview_media
                                                                    + '</td>'
                                                                + '</tr>'
                                                                + '<tr>'
                                                                    + '<td>'
                                                                        + '<p>' + preview_url + '</p>'
                                                                        + '<h3>' + headline + '</h3>'
                                                                        + '<div>' + description + '</div>'
                                                                    + '</td>'
                                                                    + '<td>'
                                                                        + '<button type="button" class="btn btn-primary">'
                                                                            + words.learn_more
                                                                        + '</button>'
                                                                    + '</td>'                                                                                              
                                                                + '</tr>'
                                                            + '</tbody>'
                                                        + '</table>'
                                                    + '</td>'
                                                + '</tr>'
                                            + '</tbody>'
                                            + '<tfoot>'
                                                + '<tr>'
                                                    + '<td>'
                                                        + '<i class="far fa-thumbs-up"></i>'
                                                        + words.like
                                                    + '</td>'
                                                    + '<td>'
                                                        + '<i class="far fa-comment-alt"></i>'
                                                        + words.comment
                                                    + '</td>'
                                                    + '<td>'
                                                        + '<i class="fas fa-share"></i>'
                                                        + words.share
                                                    + '</td>'
                                                + '</tr>'
                                            + '</tfoot>'
                                        + '</table>'
                                    + '</div>';
                            
        active = '';
        
        if ( $(modal).find('#links-clicks-preview-instagram-feed').hasClass('active') ) {
            active = ' show active';
        }             
                            
        content_preview += '<div class="tab-pane fade' + active + '" id="links-clicks-preview-instagram-feed" role="tabpanel" aria-labelledby="links-clicks-preview-instagram-feed-tab">'
                                + '<table>'
                                    + '<thead>'
                                        + '<tr>'
                                            + '<th colspan="3">'
                                                + '<img src="' + url + 'assets/img/avatar-placeholder.png">'
                                                + '<h3>'
                                                    + '<a href="#">'
                                                        + words.your_name
                                                    + '</a>'
                                                    + '<span>'
                                                        + words.sponsored
                                                    + '</span>'
                                                    + '<i class="fas fa-ellipsis-h"></i>'
                                                + '</h3>'
                                            + '</th>'
                                        + '</tr>'
                                    + '</thead>'
                                    + '<tbody>'
                                        + '<tr>'
                                            + '<td colspan="3" class="clean">'
                                                + '<table class="full">'
                                                    + '<tbody>'
                                                        + '<tr>'
                                                            + '<td class="clean">'
                                                                + preview_media
                                                            + '</td>'
                                                        + '</tr>'
                                                        + '<tr>'
                                                            + '<td>'
                                                                + '<a href="#">'
                                                                    + words.learn_more
                                                                + '</a>'
                                                                + '<i class="fas fa-angle-right"></i>'
                                                            + '</td>'                                                              
                                                        + '</tr>'
                                                    + '</tbody>'
                                                + '</table>'
                                            + '</td>'
                                        + '</tr>'
                                    + '</tbody>'
                                    + '<tfoot>'
                                        + '<tr>'
                                            + '<td colspan="2">'
                                                + '<i class="icon-heart"></i>'
                                                + '<i class="icon-bubble"></i>'
                                                + '<i class="icon-paper-plane"></i>'
                                            + '</td>'
                                            + '<td class="text-right">'
                                                + '<i class="far fa-bookmark"></i>'
                                            + '</td>'
                                        + '</tr>'
                                        + '<tr>'
                                            + '<td colspan="3">'
                                                + '<p>' + preview_text + '</p>'
                                            + '</td>'
                                        + '</tr>'                                                                               
                                    + '</tfoot>'
                                + '</table>'
                            + '</div>'
                        + '</div>';
                
        active = '';
        
        if ( $(modal).find('#links-clicks-preview-messenger-inbox').hasClass('active') ) {
            active = ' show active';
        }             
                            
        content_preview += '<div class="tab-content" id="links-clicks-preview">'
                                    + '<div class="tab-pane fade' + active + '" id="links-clicks-preview-messenger-inbox" role="tabpanel" aria-labelledby="links-clicks-preview-messenger-inbox-tab">'
                                        + '<table>'
                                            + '<thead>'
                                                + '<tr>'
                                                    + '<th>'
                                                        + '<img src="' + url + 'assets/img/avatar-placeholder.png">'
                                                    + '</th>'                                       
                                                    + '<th colspan="2">'
                                                        + '<h3>'
                                                            + '<a href="#">'
                                                                + words.your_page_name
                                                            + '</a>'
                                                            + '<span>' + words.sponsored + '</span>'
                                                            + '<i class="fas fa-ellipsis-h pull-right"></i>'
                                                        + '</h3>'
                                                    + '</th>'
                                                + '</tr>'
                                            + '</thead>'
                                            + '<tbody>'
                                                + '<tr>'
                                                    + '<td>'
                                                    + '</td>'                                        
                                                    + '<td colspan="2">'
                                                        + '<p>'
                                                            + preview_text
                                                        + '</p>'
                                                    + '</td>'
                                                + '</tr>'
                                                + '<tr>'
                                                    + '<td>'
                                                    + '</td>'
                                                    + '<td colspan="2" class="clean">'
                                                        + '<table class="full">'
                                                            + '<tbody>'
                                                                + '<tr>'
                                                                    + '<td colspan="2">'
                                                                        + preview_media
                                                                    + '</td>'
                                                                + '</tr>'
                                                                + '<tr>'
                                                                    + '<td>'
                                                                        + '<h3>'
                                                                            + words.connect_in_messenger
                                                                        + '</h3>'
                                                                    + '</td>'
                                                                    + '<td>'
                                                                        + '<button type="button" class="btn btn-primary">'
                                                                            + '<i class="fab fa-facebook-messenger"></i>'
                                                                            + words.send_message
                                                                        + '</button>'
                                                                    + '</td>'                                                                                              
                                                                + '</tr>'
                                                            + '</tbody>'
                                                        + '</table>'
                                                    + '</td>'
                                                + '</tr>'
                                            + '</tbody>'
                                        + '</table>'
                                    + '</div>';
                            
        // Display preview
        $(modal + ' .ad-preview-display .panel-body').html(content_preview);
        
    };
    
    /*
     * Load Ad Account overview
     * 
     * @since   0.0.7.6
     */ 
    Main.load_ad_account_overview = function() {
        
        var data = {
            action: 'load_ad_account_overview'
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'load_ad_account_overview');
        
    };
    
    /*
     * Load Ad Account campaigns
     * 
     * @since   0.0.7.6
     */ 
    Main.load_ad_account_campaigns = function() {
        
        var data = {
            action: 'load_ad_account_campaigns'
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'load_ad_account_campaigns');
        
    };
    
    /*
     * Load Ad Account adsets
     * 
     * @since   0.0.7.6
     */ 
    Main.load_ad_account_adsets = function() {
        
        var data = {
            action: 'load_ad_account_adsets'
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'load_ad_account_adsets');
        
    };
    
    /*
     * Load Ad Account ads
     * 
     * @since   0.0.7.6
     */ 
    Main.load_ad_account_ads = function() {
        
        var data = {
            action: 'load_ad_account_ads',
            status: $('.main .ads-status-filter-btn').attr('data-order')
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'load_ad_account_ads');
        
    };
    
    /*
     * Load Ad Account Insights
     * 
     * @since   0.0.7.7
    */ 
    Main.load_ad_account_insights = function() {
        
        var insights = '<div class="row">'
                            + '<div class="col-xl-12">'
                                + '<div class="table-responsive">'
                                    + '<table class="table">'
                                        + '<thead>'
                                            + '<tr>'
                                                + '<th scope="row" colspan="6">'
                                                    + '<div class="dropdown">'
                                                        + '<button class="btn btn-secondary dropdown-toggle insights-filter-btn" data-type="1" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                                                            + '<i class="far fa-calendar-alt"></i>'
                                                            + words.today
                                                        + '</button>'
                                                        + '<div class="dropdown-menu insights-filter-list" aria-labelledby="dropdownMenuButton">'
                                                            + '<a class="dropdown-item" href="#" data-type="1">'
                                                                + '<i class="far fa-calendar-alt"></i>'
                                                                + words.today
                                                            + '</a>'                                                
                                                            + '<a class="dropdown-item" href="#" data-type="2">'
                                                                + '<i class="far fa-calendar-alt"></i>'
                                                                + words.week
                                                            + '</a>'
                                                            + '<a class="dropdown-item" href="#" data-type="3">'
                                                                + '<i class="far fa-calendar-alt"></i>'
                                                                + words.month
                                                            + '</a>'
                                                        + '</div>'
                                                    + '</div>'
                                                    + '<button type="button" class="btn btn-success insights-btn-show-insights">'
                                                        + '<i class="fas fa-file-download"></i>'
                                                        + words.show
                                                    + '</button>'                      
                                                + '</th>'
                                                + '<th scope="row" colspan="2">'
                                                    + '<button type="button" class="btn btn-dark pull-right btn-ads-reports btn-insights-download">'
                                                        + '<i class="icon-cloud-download"></i>'
                                                        + words.download
                                                    + '</button>'
                                                + '</th>'
                                            + '</tr>'
                                            + '<tr>'
                                                + '<th scope="col">'
                                                    + words.date
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + words.impressions
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + words.reach
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + words.clicks
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + words.cpm
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + words.cpc
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + words.ctr
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + words.ctr
                                                + '</th>'
                                            + '</tr>'
                                        + '</thead>'
                                        + '<tbody>'
                                            + '<tr>'
                                                + '<td colspan="8">'
                                                    + words.no_insights_found
                                                + '</td>'
                                            + '</tr>'
                                        + '</tbody>'
                                    + '</table>'
                                + '</div>'
                            + '</div>'
                        + '</div>';
                
        $('.main #insights').html(insights);
        $('.main #insights').removeClass('no-account-result');  
        
    };
    
    /*
     * Load Ad Account Pixel Conversions
     * 
     * @since   0.0.7.6
     */ 
    Main.load_ad_account_pixel_conversions = function() {
        
        var data = {
            action: 'load_ad_account_pixel_conversions'
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'load_ad_account_pixel_conversions');
        
    };   
    
    /*
     * Load Ad Campaigns for select dropdown
     * 
     * @since   0.0.7.6
     */ 
    Main.load_select_ad_campaigns = function() {
        
        var key = '';
        
        if ( $('.main #ads-create-ad-set').hasClass('show') ) {

            key = $('.main #ads-create-ad-set .ad-creation-filter-fb-campaigns').val();

        } else if ( $('.main #ads-create-new-ad').hasClass('show') ) {

            key = $('.main #ads-create-new-ad .ad-creation-filter-fb-campaigns').val();

        } else if ( $('.main #ads-campaigns-insights').hasClass('show') ) {

            key = $('.main #ads-campaigns-insights .ads-insights-filter-fb-campaigns').val();

        }
        
        var data = {
            action: 'load_select_ad_campaigns',
            key: key
        };
        
        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_select_ad_campaigns');
        
    };
    
    /*
     * Load Ad Sets for select dropdown
     * 
     * @since   0.0.7.7
     */ 
    Main.load_select_ad_sets = function() {
        
        var key = '';
        
        if ( $('.main #ads-select-ad-set').hasClass('show') ) {

            key = $('.main #ads-select-ad-set .ad-creation-filter-fb-adsets').val();

        } else if ( $('.main #ads-ad-sets-insights').hasClass('show') ) {

            key = $('.main #ads-ad-sets-insights .ads-insights-filter-fb-ad-sets').val();

        }
        
        var data = {
            action: 'load_select_ad_sets',
            campaign_id: $('.main #ads-create-new-ad .ads-selected-ad-campaign').attr('data-id'),
            key: key
        };
        
        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_select_ad_sets');
        
    };
    
    /*
     * Load Ad Sets for select dropdown
     * 
     * @since   0.0.7.7
    */ 
    Main.load_select_all_ad_sets = function() {
        
        var key = $('.main #ads-ad-sets-insights .ads-insights-filter-fb-ad-sets').val();
        
        var data = {
            action: 'load_select_all_ad_sets',
            key: key
        };
        
        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_select_all_ad_sets');
        
    }; 
    
    /*
     * Load Ad Sets for select dropdown
     * 
     * @since   0.0.7.7
    */ 
    Main.load_select_ads = function() {
        
        var key = $('.main #ads-ad-insights .ads-insights-filter-fb-ad').val();
        
        var data = {
            action: 'load_select_ads',
            key: key
        };
        
        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_select_ads');
        
    }; 
    
    /*
     * Reload contents
     * 
     * @since   0.0.7.7
    */ 
    Main.reload_content = function() {
        
        // Load Ad Account overview
        Main.load_ad_account_overview();
        
        setTimeout(function() {

            var data = {
                action: 'load_campaigns_by_pagination'
            };

            data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_campaigns_by_pagination');

        }, 10000);
        
        setTimeout(function() {

            // Load Ad Account adsets
            Main.load_ad_account_adsets();

        }, 20000);
        
        setTimeout(function() {

            // Load Ad Account ads
            Main.load_ad_account_ads();

        }, 30000);
        
    }; 

    /*
     * Load regions based on country code
     * 
     * @since   0.0.7.8
     */ 
    Main.load_regions = function() {

        // Country code
        var code = '';

        // Get all selected countries
        var countries = $('.main .modal.show .select-countries input[type="checkbox"]');
        
        // List all selected countries
        for ( var d = 0; d < countries.length; d++ ) {
            
            if ( countries[d].checked ) {
                code = $(countries[d]).attr('data-id');
                break;
            }
            
        }

        var data = {
            action: 'load_regions',
            code: code
        };

        if ( $('.main .modal.show .search_for_region').length > 0 ) {
            data['key'] = $('.main .modal.show .search_for_region').val();
        }
        
        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_regions');
        
    };

    /*
     * Load cities based on region name
     * 
     * @since   0.0.7.9
     */ 
    Main.load_cities = function() {

        var data = {
            action: 'load_cities',
            region: $('.main .modal.show .selected-region').text()
        };

        if ( $('.main .modal.show .search_for_city').length > 0 ) {
            data['key'] = $('.main .modal.show .search_for_city').val();
        }
        
        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_cities');
        
    };

    /*******************************
    ACTIONS
    ********************************/
   
    /*
     * Search for available accounts in the selection area
     * 
     * @since   0.0.7.6
     */
    $(document).on('keyup', '.main .available-accounts-search-for-accounts', function () {
        
        if ( $( this ).val() === '' ) {
            
            // Hide cancel search button
            $( '.cancel-available-accounts-search' ).fadeOut('slow');
            
        } else {
         
            // Display cancel search button
            $( '.cancel-available-accounts-search' ).fadeIn('slow');
            
        }
        
        Main.quick_ad_accounts();
        
    });
    
    /*
     * Search for available accounts in accounts manager modal
     * 
     * @since   0.0.7.6
     */
    $(document).on('keyup', '.main .accounts-manager-search-for-accounts', function () {
        
        if ( $( this ).val() === '' ) {
            
            // Hide cancel search button
            $( '.cancel-accounts-manager-search' ).fadeOut('slow');
            
        } else {
         
            // Display cancel search button
            $( '.cancel-accounts-manager-search' ).fadeIn('slow');
            
        }
        
        Main.load_ad_accounts(1);
        
    });
    
    /*
     * Track search keyup
     * 
     * @since   0.0.7.6
     */        
    $(document).on('keyup', '.main .ad-creation-options .ad-creation-filter-fb-pages', function () {
        
        // Get value
        var value = $(this).val().toLowerCase();
        
        // List facebook pages
        $('.main .ad-creation-options .ad-creation-filter-fb-pages-list .list-group-item a').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });

    });
    
    /*
     * Track search keyup
     * 
     * @since   0.0.7.6
     */        
    $(document).on('keyup', '.main .ad-creation-options .ad-creation-filter-instagram-account', function () {
        
        // Get value
        var value = $(this).val().toLowerCase();
        
        // List facebook pages
        $('.main .ad-creation-options .ad-creation-filter-instagram-accounts-list .list-group-item a').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });

    });
    
    /*
     * Detect text editing
     * 
     * @since   0.0.7.6
     */        
    $(document).on('keyup', '.main #myTabContent5 > .tab-pane.active .text', function () {

        // Generate preview
        Main.reload_ad_link_preview();

    });
    
    /*
     * Detect headline editing
     * 
     * @since   0.0.7.6
     */        
    $(document).on('keyup', '.main #myTabContent5 > .tab-pane.active .headline', function () {

        // Generate preview
        Main.reload_ad_link_preview();

    });
    
    /*
     * Detect link description editing
     * 
     * @since   0.0.7.6
     */        
    $(document).on('keyup', '.main #myTabContent5 > .tab-pane.active .description', function () {

        // Generate preview
        Main.reload_ad_link_preview();

    });

    /*
     * Detect text editing
     * 
     * @since   0.0.8.1
     */        
    $(document).on('keyup', '.main #myTabContent6 > .tab-pane.active .text', function () {

        // Generate preview
        Main.reload_ad_link_preview();

    });
    
    /*
     * Detect headline editing
     * 
     * @since   0.0.8.1
     */        
    $(document).on('keyup', '.main #myTabContent6 > .tab-pane.active .headline', function () {

        // Generate preview
        Main.reload_ad_link_preview();

    });
    
    /*
     * Detect link description editing
     * 
     * @since   0.0.8.1
     */        
    $(document).on('keyup', '.main #myTabContent6 > .tab-pane.active .description', function () {

        // Generate preview
        Main.reload_ad_link_preview();

    });

    /*
     * Detect text editing
     * 
     * @since   0.0.8.1
     */        
    $(document).on('keyup', '.main #myTabContent7 > .tab-pane.active .text', function () {

        // Generate preview
        Main.reload_ad_link_preview();

    });
    
    /*
     * Detect headline editing
     * 
     * @since   0.0.8.1
     */        
    $(document).on('keyup', '.main #myTabContent7 > .tab-pane.active .headline', function () {

        // Generate preview
        Main.reload_ad_link_preview();

    });
    
    /*
     * Detect link description editing
     * 
     * @since   0.0.8.1
     */        
    $(document).on('keyup', '.main #myTabContent7 > .tab-pane.active .description', function () {

        // Generate preview
        Main.reload_ad_link_preview();

    });
    
    /*
     * Filter the Pixel's Conversions Tracking
     * 
     * @since   0.0.7.6
     */        
    $(document).on('keyup', '.main .ad-creation-filter-pixel-conversions', function () {

        // Get key
        var key = $(this).val();
        
        var data = {
            action: 'filter_pixel_coversions',
            key: key
        };

        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'filter_pixel_coversions');

    });
    
    /*
     * Filter the Campaigns
     * 
     * @since   0.0.7.6
     */        
    $(document).on('keyup', '.main .ad-creation-filter-fb-campaigns', function () {

        // Load Campaigns
        Main.load_select_ad_campaigns();

    });  
    
    /*
     * Filter the Ad Sets
     * 
     * @since   0.0.7.7
     */        
    $(document).on('keyup', '.main .ad-creation-filter-fb-adsets', function () {

        // Load Ad Sets
        Main.load_select_ad_sets();

    }); 
    
    /*
     * Filter the Campaigns
     * 
     * @since   0.0.7.7
     */        
    $(document).on('keyup', '.main .ads-insights-filter-fb-campaigns', function () {

        // Load Ad's Campaigns for Select list
        Main.load_select_ad_campaigns();  

    }); 
    
    /*
     * Filter the Ad Sets
     * 
     * @since   0.0.7.7
     */        
    $(document).on('keyup', '.main .ads-insights-filter-fb-ad-sets', function () {

        // Load Ad's Sets for Select list
        Main.load_select_all_ad_sets();

    });
    
    /*
     * Filter the Ads
     * 
     * @since   0.0.7.7
     */        
    $(document).on('keyup', '.main .ads-insights-filter-fb-ad', function () {

        // Load Ads for Select list
        Main.load_select_ads();  

    });
    
    /*
     * Search for posts
     * 
     * @since   0.0.7.7
     */        
    $(document).on('keyup', '.main .post-engagement-search-posts', function () {
        
        // Load posts for boosting
        Main.load_posts_for_boosting();

    });    

    /*
     * Search for regions
     * 
     * @since   0.0.7.8
     */        
    $(document).on('keyup', '.main .modal.show .search_for_region', function () {
        
        // Load regions
        Main.load_regions();

    });

    /*
     * Search for cities
     * 
     * @since   0.0.7.9
     */        
    $(document).on('keyup', '.main .modal.show .search_for_city', function () {
        
        // Load cities
        Main.load_cities();

    });
    
    /*
     * Detect url enter
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */
    $( document ).on( 'change', '.main #myTabContent5 > .tab-pane.active .website_url, .main #myTabContent6 > .tab-pane.active .website_url, .main #myTabContent7 > .tab-pane.active .website_url', function (e) {
        
        // Get input content
        var input = $( this ).val();
        
        var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
        
        // Verify if url is valid
        if (!regex.test(input)) {

            // Display alert
            Main.popup_fon('sube', words.please_enter_valid_url, 1500, 2000);

        } else {
            
            // Get url preview
            Main.get_page_content_by_url(input);

        }
        
    });    
    
    /*
     * Submit media upload
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */
    $( document ).on( 'change', '.main #file', function (e) {
        
        // Submit form
        $('.main #upim').submit();
        
    });
   
    /*
     * Detect modal open
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */   
    $( '.main #ads-create-campaign' ).on('shown.bs.modal', function (e) {
        
        // Empty response
        $('.main #ads-create-campaign .alerts-display-reports').empty(); 
        
        // Load Pixel's conversion
        Main.load_all_pixel_coversions();
        
        // Load Ad's Identity
        Main.load_ad_identity();  
        
        // Load Ad's Account details
        Main.load_ad_account_details();  

        setTimeout(

            function() {

                // Load regions
                Main.load_regions();

            }, 2000

        );

        setTimeout(

            function() {

                // Load cities
                Main.load_cities();

            }, 3000

        );
        
    });
    
    /*
     * Detect modal open
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */   
    $( '.main #ads-create-ad-set, .main #ads-create-new-ad' ).on('shown.bs.modal', function (e) {
        
        if ( $('.main #ads-create-ad-set').hasClass('show') ) {

            $('.main #ads-create-ad-set .ad-creation-filter-fb-campaigns').val('');
            
            $('.main #adset-create-ad-set').removeClass('show');
            
            $('.main a[href="#adset-create-ad-set"]').removeAttr('aria-expanded');
            
            $('.main #adset-create-ads').removeClass('show');
            
            $('.main a[href="#adset-create-ads"]').removeAttr('aria-expanded');
            
            $('.main #ads-create-ad-set .ads-selected-ad-campaign').removeAttr('data-id');
            
            $('.main #ads-create-ad-set .ads-selected-ad-campaign').html(words.ad_campaigns);            

        } else if ( $('.main #ads-create-new-ad').hasClass('show') ) {

            $('.main #ads-create-new-ad .ad-creation-filter-fb-campaigns').val('');
            
            $('.main #ads-select-ad-set').removeClass('show');
            
            $('.main a[href="#ads-select-ad-set"]').removeAttr('aria-expanded');
            
            $('.main #ads-create-ads').removeClass('show');
            
            $('.main a[href="#ads-create-ads"]').removeAttr('aria-expanded');    
            
            $('.main #ads-create-new-ad .ads-selected-ad-campaign').removeAttr('data-id');
            
            $('.main #ads-create-new-ad .ads-selected-ad-campaign').html(words.ad_campaigns);

        } 
        
        // Load Ad's Campaigns for Select list
        Main.load_select_ad_campaigns();  

        setTimeout(

            function() {

                // Load regions
                Main.load_regions();

            }, 2000

        );

        setTimeout(

            function() {

                // Load cities
                Main.load_cities();

            }, 3000

        );
        
    });
    
    /*
     * Detect modal open
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */   
    $( '.main #ads-campaigns-insights' ).on('shown.bs.modal', function (e) {
        
        // Load Ad's Campaigns for Select list
        Main.load_select_ad_campaigns();  
        
    });
    
    /*
     * Detect modal open
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */   
    $( '.main #ads-ad-sets-insights' ).on('shown.bs.modal', function (e) {
        
        // Load Ad's Sets for Select list
        Main.load_select_all_ad_sets();  
        
    });
    
    /*
     * Detect modal open
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */   
    $( '.main #ads-ad-insights' ).on('shown.bs.modal', function (e) {
        
        // Load Ads for Select list
        Main.load_select_ads();  
        
    });
   
    /*
     * Display automatizations menu
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */
    $(document).on('click', '.main .ads-dropdown-toggle', function (e) {
        e.preventDefault();
        
        // Verify if Automatizations menu is opened
        if ( $(this).closest('li').hasClass('menu-show') ) {
            
            $(this).closest('li').removeClass('menu-show');
            $(this).closest('li').find('.nav-tabs').fadeOut('slow');
            
        } else {
            
            $(this).closest('li').addClass('menu-show');
            $(this).closest('li').find('.nav-tabs').fadeIn('slow');
            
        }
        
    });
    
    /*
     * Show available accounts
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */
    $(document).on('click', '.main .ads-select-account', function (e) {
        e.preventDefault();
        
        // Verify if Automatizations menu is opened
        if ( $('.main .ads-available-accounts').hasClass('show-available-accounts') ) {
            
            $('.main .ads-select-account').removeClass('ads-select-account-open');
            $('.main .ads-available-accounts').removeClass('show-available-accounts');
            $('.main .ads-available-accounts').fadeOut('slow');
            
        } else {
            
            $('.main .ads-select-account').addClass('ads-select-account-open');
            $('.main .ads-available-accounts').addClass('show-available-accounts');
            $('.main .ads-available-accounts').fadeIn('slow');
            
        }
        
    });    
    
    /*
     * Detect automatizations menu click
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */
    $(document).on('click', '.main .ads-automatizations a, .main .ads-pixel a', function (e) {
        e.preventDefault();
        
        $('.main #myTab > li > a').removeClass('active show');
        $('.main #myTab > li > a').attr('aria-selected', 'false');
        
    });
    
    /*
     * Detect categories menu click
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */
    $(document).on('click', '.main #myTab > li > a', function (e) {
        e.preventDefault();
        
        $('.main #myTab2 > li > a').removeClass('active show');
        $('.main #myTab2 > li > a').attr('aria-selected', 'false');
        $('.main #automatizations-tab > li > a').removeClass('active show');
        $('.main #automatizations-tab > li > a').attr('aria-selected', 'false');
        
    });
    
    /*
     * Connect Facebook Ad Accounts
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */
    $(document).on('click', '.main .connect-add-accounts', function (e) {
        e.preventDefault();
        
        var popup_url = url + 'user/app/facebook-ads?q=facebook-ad-accounts';
        var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
        var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

        var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        var left = ((width / 2) - ((width/2) / 2)) + dualScreenLeft;
        var top = 50;
        var expiredWindow = window.open(popup_url, 'Facebook Ads', 'scrollbars=yes, width=' + (width/2) + ', height=' + (height/1.3) + ', top=' + top + ', left=' + left);

        if (window.focus) {
            expiredWindow.focus();
        }
        
    }); 
    
    /*
     * Cancel search for accounts in the selection accounts area
     * 
     * @since   0.0.7.6
     */     
    $( document ).on( 'click', '.main .cancel-available-accounts-search', function() {
        
        // Hide cancel search button
        $('.main .cancel-available-accounts-search').fadeOut('slow');

        $('.main .available-accounts-search-for-accounts').val('');
            
        Main.quick_ad_accounts();
        
    });
    
    /*
     * Cancel search for accounts in accounts manager modal
     * 
     * @since   0.0.7.6
     */     
    $( document ).on( 'click', '.main .cancel-accounts-manager-search', function() {
        
        // Hide cancel search button
        $('.main .cancel-accounts-manager-search').fadeOut('slow');

        $('.main .accounts-manager-search-for-accounts').val('');
            
        Main.load_ad_accounts(1);
        
    });    
    
    /*
     * Select account in the selection accounts area
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */
    $(document).on('click', '.ads-available-accounts li a', function (e) {
        e.preventDefault();
        
        // Get account_id
        var account_id = $(this).attr('data-id');        

        // Verify if account was selected
        if ( $( this ).closest( 'li' ).hasClass( 'account-selected' ) ) {

            var data = {
                action: 'unselect_ad_account',
                account_id: account_id
            };

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'unselect_ad_account');
            
        } else {
            
            var data = {
                action: 'select_ad_account',
                account_id: account_id
            };

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'select_ad_account');
            
        }
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Displays pagination by page click
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */    
    $( document ).on( 'click', 'body .pagination li a', function (e) {
        e.preventDefault();
        
        // Get the page number
        var page = $(this).attr('data-page');
        
        // Display results
        switch ( $(this).closest('ul').attr('data-type') ) {
            
            case 'available-accounts':
                Main.load_ad_accounts(page);
                break;              
            
        }
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Delete ad account
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */    
    $( document ).on( 'click', '.main .btn-delete-account', function (e) {
        e.preventDefault();
        
        // Get account_id
        var account_id = $(this).closest('li').attr('data-id');
        
        var data = {
            action: 'delete_ad_account',
            account_id: account_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'delete_ad_account');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    }); 
    
    /*
     * Display campaigns by pagination
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */    
    $( document ).on( 'click', '.main .btn-campaign-pagination', function (e) {
        e.preventDefault();
        
        var data = {
            action: 'load_campaigns_by_pagination',
            url: $(this).attr('data-url')
        };

        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_campaigns_by_pagination');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Display ad sets by pagination
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */    
    $( document ).on( 'click', '.main .btn-adsets-pagination', function (e) {
        e.preventDefault();
        
        var data = {
            action: 'load_ad_sets_by_pagination',
            url: $(this).attr('data-url')
        };

        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_ad_sets_by_pagination');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Display ads by pagination
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */    
    $( document ).on( 'click', '.main .btn-ad-pagination', function (e) {
        e.preventDefault();
        
        var data = {
            action: 'load_ads_by_pagination',
            url: $(this).attr('data-url')
        };

        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_ads_by_pagination');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Display pixel's conversions by pagination
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */    
    $( document ).on( 'click', '.main .btn-conversions-pagination', function (e) {
        e.preventDefault();
        
        var data = {
            action: 'load_pixel_conversions_by_pagination',
            url: $(this).attr('data-url')
        };

        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'load_pixel_conversions_by_pagination');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Detect all Campaigns selection
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', 'main #ads-campaigns-all', function (e) {
        
        setTimeout(function(){
            
            if ( $( 'main #ads-campaigns-all' ).is(':checked') ) {

                $( '.main #campaigns tbody input[type="checkbox"]' ).prop('checked', true);

            } else {

                $( '.main #campaigns tbody input[type="checkbox"]' ).prop('checked', false);

            }
        
        },500);
        
    });
    
    /*
     * Detect all Ad Sets selection
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', 'main #ads-adsets-all', function (e) {
        
        setTimeout(function(){
            
            if ( $( 'main #ads-adsets-all' ).is(':checked') ) {

                $( '.main #ad-sets tbody input[type="checkbox"]' ).prop('checked', true);

            } else {

                $( '.main #ad-sets tbody input[type="checkbox"]' ).prop('checked', false);

            }
        
        },500);
        
    });
    
    /*
     * Delete ads campaigns
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', 'main .ads-delete-campaigns', function (e) {
        
        // Get all selected campaigns
        var campaigns = $('.main #campaigns tbody tr .checkbox-option-select input[type="checkbox"]');
        
        var selected = [];
        
        // List all campaigns
        for ( var d = 0; d < campaigns.length; d++ ) {

            if ( campaigns[d].checked ) {
                selected.push($(campaigns[d]).attr('data-id'));
            }
            
        }
        
        if ( selected.length < 1 ) {
            
            // Display alert
            Main.popup_fon('sube', words.please_select_a_campaign, 1500, 2000);
            return;
            
        }
        
        // Create an object with form data
        var data = {
            action: 'delete_ad_campaigns',
            campaigns: Object.entries(selected),
            account: $('.main .ads-select-account').attr('data-id')
        };

        // Set CSRF
        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'delete_ad_campaigns');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Delete ad sets
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', 'main .ads-delete-adsets', function (e) {
        
        // Get all selected adsets
        var adsets = $('.main #ad-sets tbody tr .checkbox-option-select input[type="checkbox"]');
        
        var selected = [];
        
        // List all adsets
        for ( var d = 0; d < adsets.length; d++ ) {

            if ( adsets[d].checked ) {
                selected.push($(adsets[d]).attr('data-id'));
            }
            
        }
        
        if ( selected.length < 1 ) {
            
            // Display alert
            Main.popup_fon('sube', words.please_select_an_ad_sets, 1500, 2000);
            return;
            
        }
        
        // Create an object with form data
        var data = {
            action: 'delete_ad_sets',
            adsets: Object.entries(selected),
            account: $('.main .ads-select-account').attr('data-id')
        };

        // Set CSRF
        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'delete_ad_sets');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Delete ads
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', 'main .ads-delete-ad', function (e) {
        
        // Get all selected ads
        var ads = $('.main #ads tbody tr .checkbox-option-select input[type="checkbox"]');
        
        var selected = [];
        
        // List all ads
        for ( var d = 0; d < ads.length; d++ ) {

            if ( ads[d].checked ) {
                selected.push($(ads[d]).attr('data-id'));
            }
            
        }
        
        if ( selected.length < 1 ) {
            
            // Display alert
            Main.popup_fon('sube', words.please_select_an_ad, 1500, 2000);
            return;
            
        }
        
        // Create an object with form data
        var data = {
            action: 'delete_ads',
            ads: Object.entries(selected),
            account: $('.main .ads-select-account').attr('data-id')
        };

        // Set CSRF
        data[$('.facebook-ads-create-ad').attr('data-csrf')] = $('input[name="' + $('.facebook-ads-create-ad').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'delete_ads');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Display hidden contents
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', 'main .show-more a', function (e) {
        e.preventDefault();
        
        // Display hidden inputs
        $(this).closest('.row-input').find('.more-content').addClass('less-content');
        $(this).closest('.row-input').find('.show-more').hide();
        
    }); 
    
    /*
     * Change campaign dropdown
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', 'main .ads-campaign-dropdown a', function (e) {
        e.preventDefault();

        $(this).closest('.dropdown').find('.dropdown-toggle').attr('data-id', $(this).attr('data-id'));
        $(this).closest('.dropdown').find('.dropdown-toggle').html($(this).html());

        if ( $(this).attr('data-id') === 'LINK_CLICKS' ) {
            
            $('#ads-create-campaign #myTabContent5 > .tab-pane').removeClass('show active');
            $('#ads-create-campaign #campaign-create-ads-links').addClass('show active');
            
        } else if ( $(this).attr('data-id') === 'POST_ENGAGEMENT' ) {
            
            $('#ads-create-campaign #myTabContent5 > .tab-pane').removeClass('show active');
            $('#ads-create-campaign #campaign-create-ads-post-engagement').addClass('show active');
            
            // Load posts for boosting
            Main.load_posts_for_boosting();
            
        } else if ( $(this).closest('.col-12').hasClass('ad-creation-preview') ) {
            
            $(this).closest('.col-12').find('.tab-pane').removeClass('show active');
            $($(this).attr('href')).addClass('show active');
            
        } else if ( $(this).closest('.col-12').hasClass('links-clicks-preview-settings') ) {

            $(this).closest('.panel-body').find('.tab-content#links-clicks-preview-settings > .tab-pane').removeClass('show active');
            $($(this).attr('href')).addClass('show active');
            
            if ( $('.main .modal #campaign-create-ads-post-engagement').hasClass('active') ) {
                
                // Load posts for boosting
                Main.load_posts_for_boosting();                
                
            }
            
        } else if ( $(this).closest('.col-12').hasClass('post-engagement-list') ) {

            $(this).closest('.panel-body').find('.tab-content#post-engagement-list > .tab-pane').removeClass('show active');
            $($(this).attr('href')).addClass('show active');
            
            // Load posts for boosting
            Main.load_posts_for_boosting();
            
        } else if ( $(this).closest('ul').hasClass('regions-list') ) {

            // Empty cities
            $('.main .modal.show .select-cities').empty();

            // Load cities
            Main.load_cities();
            
        }
        
    });
    
    /*
     * Change the Facebook Page
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', 'main .ad-creation-filter-fb-pages-list li a', function (e) {
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
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'display_connected_instagram_accounts');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Select image to upload
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', '.main .upload-ads-image', function (e) {
        e.preventDefault();
        
        // Add file's type
        $('#type').val('image');
        
        // Select file
        $('#file').click();
        
    }); 
    
    /*
     * Select video to upload
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', '.main .upload-ads-video', function (e) {
        e.preventDefault();
        
        // Add file's type
        $('#type').val('video');
        
        // Select file
        $('#file').click();
        
    });    
    
    /*
     * Delete uploaded photo
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', '.main .delete-ad-media-by-hash', function (e) {
        e.preventDefault();
        
        if ( $('.main #ads-create-campaign').hasClass('show') ) {

            // Get hash
            var hash = $('.main #myTabContent5 > .tab-pane.active').find('.ads-uploaded-photo-single').attr('data-hash');

        } else if ( $('.main #ads-create-ad-set').hasClass('show') ) {

            // Get hash
            var hash = $('.main #myTabContent6 > .tab-pane.active').find('.ads-uploaded-photo-single').attr('data-hash');

        } else if ( $('.main #ads-create-new-ad').hasClass('show') ) {

            // Get hash
            var hash = $('.main #myTabContent7 > .tab-pane.active').find('.ads-uploaded-photo-single').attr('data-hash');

        }
        
        // Create an object with form data
        var data = {
            action: 'delete_ad_media',
            hash: hash,
            type: 'image'
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'delete_ad_media');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Delete uploaded video
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', '.main .delete-ad-media-by-id', function (e) {
        e.preventDefault();
        
        // Get video's id
        var id = $('.main #myTabContent5 > .tab-pane.active').find('.ads-uploaded-video-single').attr('data-id');
        
        // Create an object with form data
        var data = {
            action: 'delete_ad_media',
            id: id,
            type: 'video'
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'delete_ad_media');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    }); 
    
    /*
     * Filter stats in the overview tab
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', '.main .overview-filter-list a', function (e) {
        e.preventDefault();
        
        // Get filter's type
        var type = $(this).attr('data-type');
        
        // Set button text
        $('.main .overview-filter-btn').html($(this).html());
        
        // Create an object with form data
        var data = {
            action: 'load_account_overview',
            type: type
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'load_account_overview');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    }); 

    /*
     * Filter ads in by status tab
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.1
     */ 
    $( document ).on( 'click', '.main .ads-status-filter-list a', function (e) {
        e.preventDefault();
        
        // Get filter's type
        var type = $(this).attr('data-type');
        
        // Set button text
        $('.main .ads-status-filter-btn').html($(this).html());

        // Set order by
        $('.main .ads-status-filter-btn').attr('data-order', type);

        // Load Ad Account ads
        Main.load_ad_account_ads();
        
    }); 
    
    /*
     * Filter reports in the Insights tab
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $( document ).on( 'click', '.main .insights-filter-list a', function (e) {
        e.preventDefault();
        
        // Get filter's type
        var type = $(this).attr('data-type');
        
        // Set button type
        $('.main .insights-filter-btn').attr('data-type', type);
        
        // Set button text
        $('.main .insights-filter-btn').html($(this).html());
        
    });  
    
    /*
     * Get inssigts in the Insights tab
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', '.main .insights-btn-show-insights', function (e) {
        e.preventDefault();
        
        // Get filter's type
        var type = $('.main .insights-filter-btn').attr('data-type');
        
        // Create an object with form data
        var data = {
            action: 'load_account_insights',
            type: type
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'load_account_insights');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Select date
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */    
    $( document ).on( 'click', '.main .midrub-planner .calendar-dates a', function (e) {
        e.preventDefault();
        
        // Get date
        var date = $(this).attr('data-date');
        
        // Split date
        var parse = date.split('-');
        
        $('.main').find('.open-midrub-planner').html('<i class="fas fa-calendar-alt"></i>' + parse[1] + '-' + parse[2] + '-' + parse[0] + ' 00:00:00' );
        $('.main').find('.ads-campaign-start-date').val(parse[1] + '-' + parse[2] + '-' + parse[0]);
        
        // Hide calendar
        $('.midrub-planner').fadeOut('fast');
        
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
        
    });
    
    /*
     * Change the Facebook Campaign
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', 'main .ad-creation-filter-fb-campaigns-list li a', function (e) {
        e.preventDefault();
        
        // Get campaign id
        var campaign_id = $(this).attr('data-id');
        
        // Create an object with form data
        var data = {
            action: 'select_facebook_campaign',
            campaign_id: campaign_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'select_facebook_campaign');

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
    $( document ).on( 'click', 'main .ad-creation-filter-fb-adsets-list li a', function (e) {
        e.preventDefault();
        
        $('.main #ads-create-new-ad .ads-selected-ad-set').text($(this).text());
        $('.main #ads-create-new-ad .ads-selected-ad-set').attr('data-id', $(this).attr('data-id'));
        
    });
    
    /*
     * Change the Facebook Ad Campaign
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $( document ).on( 'click', 'main .ad-insights-fb-campaigns-list li a', function (e) {
        e.preventDefault();
        
        $('.main #ads-campaigns-insights .ads-campaign-insights-by-campaign').html('<i class="icon-basket-loaded"></i> ' + $(this).html());
        $('.main #ads-campaigns-insights .ads-campaign-insights-by-campaign').attr('data-id', $(this).attr('data-id'));
        
    });
    
    /*
     * Change the Facebook Ad Set
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $( document ).on( 'click', 'main .ads-campaign-insights-by-ad-sets-list li a', function (e) {
        e.preventDefault();
        
        $('.main #ads-ad-sets-insights .ads-campaign-insights-by-ad-sets').html('<i class="icon-wallet"></i> ' + $(this).html());
        $('.main #ads-ad-sets-insights .ads-campaign-insights-by-ad-sets').attr('data-id', $(this).attr('data-id'));
        
    });
    
    /*
     * Change the Facebook Ad
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $( document ).on( 'click', 'main .ads-campaign-insights-by-ad-list li a', function (e) {
        e.preventDefault();
        
        $('.main #ads-ad-insights .ads-campaign-insights-by-ad').html('<i class="icon-puzzle"></i> ' + $(this).html());
        $('.main #ads-ad-insights .ads-campaign-insights-by-ad').attr('data-id', $(this).attr('data-id'));
        
    });
    
    /*
     * Change the Campaigns Insights Interval
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $( document ).on( 'click', 'main .ads-campaign-insights-by-time-list li a', function (e) {
        e.preventDefault();
        
        $('.main #ads-campaigns-insights .ads-campaign-insights-by-time').html($(this).html());
        $('.main #ads-campaigns-insights .ads-campaign-insights-by-time').attr('data-time', $(this).attr('data-time'));
        
    });
    
    /*
     * Change the Ad Sets Insights Interval
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $( document ).on( 'click', 'main .ads-ad-sets-insights-by-time-list li a', function (e) {
        e.preventDefault();
        
        $('.main #ads-ad-sets-insights .ads-ad-sets-insights-by-time').html($(this).html());
        $('.main #ads-ad-sets-insights .ads-ad-sets-insights-by-time').attr('data-time', $(this).attr('data-time'));
        
    });
    
    /*
     * Change the Ad Insights Interval
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $( document ).on( 'click', 'main .ads-ad-insights-by-time-list li a', function (e) {
        e.preventDefault();
        
        $('.main #ads-ad-insights .ads-ad-insights-by-time').html($(this).html());
        $('.main #ads-ad-insights .ads-ad-insights-by-time').attr('data-time', $(this).attr('data-time'));
        
    });
    
    /*
     * Expand Ad Sets
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $('.main #ads-create-ad-set #adset-create-ad-set').on('show.bs.collapse', function (e) {

        if (!$('.main #ads-create-ad-set .ads-selected-ad-campaign').attr('data-id')) {

            // Display alert
            Main.popup_fon('sube', words.please_select_ad_campaign, 1500, 2000);
            return false;

        }

    });
    
    /*
     * Expand Ads
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $('.main #ads-create-ad-set #adset-create-ads').on('show.bs.collapse', function (e) {

        if (!$('.main #ads-create-ad-set .ads-selected-ad-campaign').attr('data-id')) {

            // Display alert
            Main.popup_fon('sube', words.please_select_ad_campaign, 1500, 2000);
            return false;

        }

    });
    
    /*
     * Expand Ad Sets
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $('.main #ads-create-new-ad #ads-select-ad-set').on('show.bs.collapse', function (e) {

        if (!$('.main #ads-create-new-ad .ads-selected-ad-campaign').attr('data-id')) {

            // Display alert
            Main.popup_fon('sube', words.please_select_ad_campaign, 1500, 2000);
            return false;

        }

    });
    
    /*
     * Expand Ad
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */ 
    $('.main #ads-create-new-ad #ads-create-ads').on('show.bs.collapse', function (e) {

        if (!$('.main #ads-create-new-ad .ads-selected-ad-set').attr('data-id')) {

            // Display alert
            Main.popup_fon('sube', words.please_select_ad_set, 1500, 2000);
            return false;

        }

    });
    
    /*
     * Detect modal open
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */   
    $( '.main #ads-create-ad-set' ).on('shown.bs.modal', function (e) {
        
        // Empty response
        $('.main #ads-create-ad-set .alerts-display-reports').empty(); 
        
        // Load Pixel's conversion
        Main.load_all_pixel_coversions();
        
        // Load Ad's Identity
        Main.load_ad_identity();  
        
        // Load Ad's Account details
        Main.load_ad_account_details();  
        
    });
    
    /*
     * Detect modal open
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */   
    $( '.main #ads-create-new-ad' ).on('shown.bs.modal', function (e) {
        
        // Empty response
        $('.main #ads-create-new-ad .alerts-display-reports').empty(); 
        
        // Load Pixel's conversion
        Main.load_all_pixel_coversions();
        
        // Load Ad's Identity
        Main.load_ad_identity();
        
    });
    
    /*
     * Download insights
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
    */   
    $( document ).on( 'click', '.main .btn-insights-download', function (e) {
        
        // Get type
        var type = $('.main .insights-filter-btn').attr('data-type');
        
        // Create an object with form data
        var data = {
            action: 'insights_download_for_account',
            type: type
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'insights_download_for_account');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Download insights for campaigns
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
    */   
    $( document ).on( 'click', '.main .btn-insights-campaign-download', function (e) {
        
        // Get time
        var time = $('.main .ads-campaign-insights-by-time').attr('data-time');
        
        // Get campaign's id
        var campaign_id = $('.main .ads-campaign-insights-by-campaign').attr('data-id');  
        
        if ( typeof campaign_id === 'undefined' ) {
            
            // Display alert
            Main.popup_fon('sube', words.please_select_ad_campaign, 1500, 2000);
            return false;
            
        }
        
        // Create an object with form data
        var data = {
            action: 'insights_download_for_campaigns',
            order: time,
            campaign_id: campaign_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'insights_download_for_campaigns');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Download insights for Ad Sets
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
    */   
    $( document ).on( 'click', '.main .btn-insights-ad-set-download', function (e) {
        
        // Get time order
        var time = $('.main .ads-ad-sets-insights-by-time').attr('data-time');
        
        // Get ad set's id
        var ad_set_id = $('.main .ads-campaign-insights-by-ad-sets').attr('data-id');  
        
        if ( typeof ad_set_id === 'undefined' ) {
            
            // Display alert
            Main.popup_fon('sube', words.please_select_ad_set, 1500, 2000);
            return false;
            
        }
        
        // Create an object with form data
        var data = {
            action: 'insights_download_for_ad_sets',
            order: time,
            ad_set_id: ad_set_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'insights_download_for_ad_sets');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Download insights for Ads
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
    */   
    $( document ).on( 'click', '.main .btn-insights-ad-download', function (e) {
        
        // Get time order
        var time = $('.main .ads-ad-insights-by-time').attr('data-time');
        
        // Get ad's id
        var ad_id = $('.main .ads-campaign-insights-by-ad').attr('data-id');  
        
        if ( typeof ad_id === 'undefined' ) {
            
            // Display alert
            Main.popup_fon('sube', words.please_select_ad, 1500, 2000);
            return false;
            
        }
        
        // Create an object with form data
        var data = {
            action: 'insights_download_for_ad',
            order: time,
            ad_id: ad_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'insights_download_for_ad');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Get post's data 
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
    */   
    $( document ).on( 'click', '.main .post-engagement-boost-it', function (e) {
        
        // Create an object with form data
        var data = {
            action: 'get_post_data_for_boost',
            post_id: $(this).attr('data-id') 
        };
        
        // Get selected social network
        if ( $('.main .modal.show #post-engagement-from-facebook').hasClass('active') ) {
            
            data['network'] = 'facebook';
            
        } else if ( $('.main .modal.show #post-engagement-from-instagram').hasClass('active') ) {
            
            data['network'] = 'instagram';
            
        }

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'get_post_data_for_boost');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });  
    
    /*
     * Reset regions list
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.8
    */   
    $( document ).on( 'click', '.main .modal.show .ads-campaign-set-countries-list input[type="checkbox"]', function (e) {

        if ( $('.main .modal.show .search_for_region').length > 0 ) {
            $('.main .modal.show .search_for_region').val('');
        }
        
        // Empty region
        $('.main .modal.show .select-regions').empty();

        // Load regions
        Main.load_regions();

        // Empty cities
        $('.main .modal.show .select-cities').empty();

        // Load cities
        Main.load_cities();
        
    });    
    
    /*******************************
    RESPONSES
    ********************************/
   
    /*
     * Display Ad Accounts by page
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.load_ad_accounts = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            Main.pagination.page = data.page;
            Main.show_pagination('#nav-ad-accounts', data.total);
            
            // Display available accounts by page
            var accounts_by_page = '';

            for ( var d = 0; d < data.accounts.length; d++ ) {

                accounts_by_page += '<li class="row" data-id="' + data.accounts[d].network_id + '">'
                                        + '<div class="col-6">'
                                            + '<i class="fab fa-facebook"></i>'
                                            + data.accounts[d].user_name
                                        + '</div>'
                                        + '<div class="col-6 text-right">'
                                            + '<button type="button" class="btn btn-delete-account">'
                                                + '<i class="icon-trash"></i> ' + data.delete
                                            + '</button>'
                                        + '</div>'
                                    + '</li>';

            }

            $('.main .accounts-manager-connected-accounts').html(accounts_by_page);            
            
        } else {
            
            $('#nav-ad-accounts .pagination').empty();
                
            var accounts_by_page = '<li class="row">'
                                        + '<div class="col-12">'
                                            + data.message
                                        + '</div>'
                                    + '</li>';  
                
            $('.main .accounts-manager-connected-accounts').html(accounts_by_page);   
            
        }
        
        Main.reload_content();
        
    };
    
    /*
     * Display Ad Accounts in the selection area
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.quick_ad_accounts = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display available accounts
            var accounts = '';

            for ( var d = 0; d < data.accounts.length; d++ ) {
                
                var selected = '';
                
                if ( $('.main .ads-select-account').attr('data-id') === data.accounts[d].network_id  ) {
                    
                    selected = ' account-selected';
                    
                }

                accounts += '<li class="nav-item' + selected + '">'
                                + '<a href="#" class="nav-link" data-id="' + data.accounts[d].network_id + '">'
                                    + '<i class="fab fa-facebook"></i>'
                                    + data.accounts[d].user_name
                                    + '<i class="icon-check"></i>'
                                + '</a>'
                            + '</li>';

            }

            $('.main .ads-available-accounts ul').html(accounts);           
            
        } else {
            
            var accounts = '<li class="nav-item">'
                                + data.message
                            + '</li>';

            $('.main .ads-available-accounts ul').html(accounts);  
            
        }
        
    };
    
    /*
     * Display deletion ad account status
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.delete_ad_account = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            Main.load_ad_accounts(1);
            Main.quick_ad_accounts();
            
            if ( $( '.main .ads-select-account[data-id="' + data.account_id + '"]' ).length > 0 ) {
                
                // Add selected account status
                $( '.main .ads-available-accounts li a[data-id="' + data.account_id + '"]' ).closest( 'li' ).removeClass( 'account-selected' );
                $( '.main .ads-select-account' ).html(data.select_ad_account);
                $( '.main .ads-select-account' ).removeAttr('data-id');
            
                var pane = '<div class="row">'
                                    + '<div class="col-xl-12">'
                                        + '<div class="input-group no-account-selected">'
                                            + '<div class="input-group-prepend">'
                                                + '<span class="input-group-text">'
                                                    + '<i class="icon-user-unfollow"></i>'
                                                + '</span>'
                                            + '</div>'
                                            + '<div class="form-control">'
                                                + '<h3>'
                                                    + data.no_account_selected
                                                + '</h3>'
                                                + '<p>'
                                                    + data.please_select_ad_account
                                                + '</p>'  
                                            + '</div>'
                                        + '</div>'
                                    + '</div>'
                                + '</div>';

                // Show Overview
                $('.main .tab-pane#overview').html( pane ); 

                // Show Campaigns
                $('.main .tab-pane#campaigns').html( pane ); 
                $('.main .tab-pane#campaigns').addClass('no-account-result'); 

                // Show Adsets
                $('.main .tab-pane#ad-sets').html( pane );
                $('.main .tab-pane#ad-sets').addClass('no-account-result');

                // Show Ads
                $('.main .tab-pane#ads').html( pane );
                $('.main .tab-pane#ads').addClass('no-account-result');

                // Show Insights
                $('.main .tab-pane#insights').html( pane );
                $('.main .tab-pane#insights').addClass('no-account-result');            

                // Show Pixel's conversions
                $('.main .tab-pane#pixel-conversion').html( pane );
                $('.main .tab-pane#pixel-conversion').addClass('no-account-result'); 

                // Get all automatizations
                var automatizations = $('.main .ads-automatizations > li > a');

                // List all automatizations
                for ( var d = 0; d < automatizations.length; d++ ) {

                    // Show No Selected account message
                    $('.main ' + $(automatizations[d]).attr('href')).html( pane );
                    $('.main ' + $(automatizations[d]).attr('href')).addClass('no-account-result'); 

                }
                
            }
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
    };
    
    /*
     * Display selection ad account status
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.select_ad_account = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Unselect all accounts
            $( '.main .ads-available-accounts li').removeClass( 'account-selected' );
            
            // Add selected account status
            $( '.main .ads-available-accounts li a[data-id="' + data.account_id + '"]' ).closest( 'li' ).addClass( 'account-selected' );
            
            // Add selected account in the main account switcher button
            $( '.main .ads-select-account' ).html($( '.main .ads-available-accounts li a[data-id="' + data.account_id + '"]' ).html());
            $( '.main .ads-select-account .icon-check' ).remove();
            $( '.main .ads-select-account' ).attr('data-id', data.account_id);
            
            // Load Ad Account overview
            Main.load_ad_account_overview();
            
            // Load Ad Account campaigns
            Main.load_ad_account_campaigns();
            
            // Load Ad Account adsets
            Main.load_ad_account_adsets();
            
            // Load Ad Account ads
            Main.load_ad_account_ads();
            
            // Load Ad Account insights
            Main.load_ad_account_insights();
            
            // Load Ad Account Pixel Conversions
            Main.load_ad_account_pixel_conversions();
            
            // Get all automatizations
            var automatizations = $('.main .ads-automatizations > li > a');

            // List all automatizations
            for ( var d = 0; d < automatizations.length; d++ ) {

                var automatization = $(automatizations[d]).attr('href').replace(new RegExp('-', 'g'), '_').replace(new RegExp('#', 'g'), '');

                if ( Main.hasOwnProperty(automatization) ) {

                    Main[automatization]();
                    
                }

            }
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
    }; 
    
    /*
     * Display unselection ad account status
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.unselect_ad_account = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Add selected account status
            $( '.main .ads-available-accounts li a[data-id="' + data.account_id + '"]' ).closest( 'li' ).removeClass( 'account-selected' );
            $( '.main .ads-select-account' ).html(data.select_ad_account);
            
            var pane = '<div class="row">'
                                + '<div class="col-xl-12">'
                                    + '<div class="input-group no-account-selected">'
                                        + '<div class="input-group-prepend">'
                                            + '<span class="input-group-text">'
                                                + '<i class="icon-user-unfollow"></i>'
                                            + '</span>'
                                        + '</div>'
                                        + '<div class="form-control">'
                                            + '<h3>'
                                                + data.no_account_selected
                                            + '</h3>'
                                            + '<p>'
                                                + data.please_select_ad_account
                                            + '</p>'  
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                            + '</div>';
                    
            // Show Overview
            $('.main #overview').html( pane ); 
            
            // Show Campaigns
            $('.main .tab-pane#campaigns').html( pane ); 
            $('.main .tab-pane#campaigns').addClass('no-account-result'); 
            
            // Show Adsets
            $('.main .tab-pane#ad-sets').html( pane );
            $('.main .tab-pane#ad-sets').addClass('no-account-result');
            
            // Show Ads
            $('.main .tab-pane#ads').html( pane );
            $('.main .tab-pane#ads').addClass('no-account-result');
            
            // Show Insights
            $('.main .tab-pane#insights').html( pane );
            $('.main .tab-pane#insights').addClass('no-account-result');            
            
            // Show Pixel's conversions
            $('.main .tab-pane#pixel-conversion').html( pane );
            $('.main .tab-pane#pixel-conversion').addClass('no-account-result'); 
            
            // Get all automatizations
            var automatizations = $('.main .ads-automatizations > li > a');

            // List all automatizations
            for ( var d = 0; d < automatizations.length; d++ ) {

                // Show No Selected account message
                $('.main ' + $(automatizations[d]).attr('href')).html( pane );
                $('.main ' + $(automatizations[d]).attr('href')).addClass('no-account-result'); 

            }
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
    }; 
    
    /*
     * Display campaigns by page
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.load_campaigns_by_pagination = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var campaigns = data.campaigns;
            
            var all_campaigns = '';
            
            for ( var e = 0; e < campaigns.length; e++ ) {
                
                var impressions = 0;
                
                if (typeof campaigns[e].insights !== 'undefined') {
                    
                    if (typeof campaigns[e].insights.data[0] !== 'undefined') {
                        
                        if (typeof campaigns[e].insights.data[0].impressions !== 'undefined') {
                            
                            impressions = campaigns[e].insights.data[0].impressions;

                        }                        
                        
                    }
                    
                }
                
                var spend = 0;
                
                if (typeof campaigns[e].insights !== 'undefined') {
                    
                    if (typeof campaigns[e].insights.data[0] !== 'undefined') {
                        
                        if (typeof campaigns[e].insights.data[0].spend !== 'undefined') {
                            
                            spend = campaigns[e].insights.data[0].spend;

                        }                        
                        
                    }
                    
                }   
                
                if ( typeof Main.account_currency !== 'undefined' ) {

                    spend = spend + ' ' + Main.account_currency;

                }
                
                var objective = campaigns[e].objective;
                
                switch ( objective ) {
                    
                    case 'APP_INSTALLS':
                        
                        objective = words.app_installs;
                        
                        break;
                    
                    case 'BRAND_AWARENESS':
                        
                        objective = words.brand_awareness;
                        
                        break;
                        
                    case 'CONVERSIONS':
                        
                        objective = words.conversions;
                        
                        break;
                        
                    case 'EVENT_RESPONSES':
                        
                        objective = words.event_responses;
                        
                        break;
                        
                    case 'LEAD_GENERATION':
                        
                        objective = words.lead_generation;
                        
                        break;
                        
                    case 'LINK_CLICKS':
                        
                        objective = words.link_clicks;
                        
                        break;
                        
                    case 'LOCAL_AWARENESS':
                        
                        objective = words.local_awareness;
                        
                        break;
                        
                    case 'LOCAL_AWARENESS':
                        
                        objective = words.local_awareness;
                        
                        break;
                        
                    case 'MESSAGES':
                        
                        objective = words.messages;
                        
                        break;
                    
                    case 'OFFER_CLAIMS':
                        
                        objective = words.offer_claims;
                        
                        break;
                        
                    case 'PAGE_LIKES':
                        
                        objective = words.page_likes;
                        
                        break;  
                    
                    case 'POST_ENGAGEMENT':
                        
                        objective = words.post_engagement;
                        
                        break;
                    
                    case 'PRODUCT_CATALOG_SALES':
                        
                        objective = words.product_catalog_sales;
                        
                        break;
                        
                    case 'REACH':
                        
                        objective = words.reach;
                        
                        break;
                        
                    case 'VIDEO_VIEWS':
                        
                        objective = words.video_views;
                        
                        break;                        
                    
                }
                
                all_campaigns += '<tr>'
                                    + '<th scope="row">'
                                        + '<div class="checkbox-option-select">'
                                            + '<input id="ads-campaigns-' + campaigns[e].id + '" name="ads-campaigns-' + campaigns[e].id + '" type="checkbox" data-id="' + campaigns[e].id + '">'
                                            + '<label for="ads-campaigns-' + campaigns[e].id + '"></label>'
                                        + '</div>'
                                    + '</th>'
                                    + '<td>'
                                        + campaigns[e].name
                                    + '</td>'
                                    + '<td>'
                                        + campaigns[e].status
                                    + '</td>'
                                    + '<td>'
                                        + objective
                                    + '</td>'
                                    + '<td>'
                                        + impressions
                                    + '</td>'
                                    + '<td>'
                                        + spend
                                    + '</td>'
                                + '</tr>';
                
            }
            
            $('.main #campaigns tbody').html(all_campaigns);
            
            if ( data.previous ) {
                
                $('.main #campaigns .btn-previous').attr('data-url', data.previous);
                $('.main #campaigns .btn-previous').removeClass('btn-disabled');
                
            } else {
                
                $('.main #campaigns .btn-previous').addClass('btn-disabled');
                
            }
            
            if ( data.next ) {
                
                $('.main #campaigns .btn-next').attr('data-url', data.next);
                $('.main #campaigns .btn-next').removeClass('btn-disabled');                
                
            } else {
                
                $('.main #campaigns .btn-next').addClass('btn-disabled');  
                
            }
            
            $('.main #ads-campaigns-all').prop( 'checked', false );
            
        } else {
            
            var data = '<tr>'
                            + '<td colspan="6" class="p-3">'
                                + words.no_campaigns_found
                            + '</td>'
                        + '</tr>';
            
            $('.main #campaigns tbody').html(data);
            
            $('.main #campaigns .btn-previous').addClass('btn-disabled');
            $('.main #campaigns .btn-next').addClass('btn-disabled'); 
            
            $('.main #ads-campaigns-all').prop( 'checked', false );            
            
        }
        
        $('.main #campaigns').removeClass('no-account-result'); 
        
    };
    
    /*
     * Display ad sets by page
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.load_ad_sets_by_pagination = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var adsets = data.adsets;
            
            var all_adsets = '';
            
            for ( var e = 0; e < adsets.length; e++ ) {
                
                var impressions = 0;
                
                if (typeof adsets[e].insights !== 'undefined') {
                    
                    if (typeof adsets[e].insights.data[0] !== 'undefined') {
                        
                        if (typeof adsets[e].insights.data[0].impressions !== 'undefined') {
                            
                            impressions = adsets[e].insights.data[0].impressions;

                        }                        
                        
                    }
                    
                }
                
                var spend = 0;
                
                if (typeof adsets[e].insights !== 'undefined') {
                    
                    if (typeof adsets[e].insights.data[0] !== 'undefined') {
                        
                        if (typeof adsets[e].insights.data[0].spend !== 'undefined') {
                            
                            spend = adsets[e].insights.data[0].spend;

                        }                        
                        
                    }
                    
                }
                
                if ( typeof Main.account_currency !== 'undefined' ) {

                    spend = spend + ' ' + Main.account_currency;

                }
                
                all_adsets += '<tr>'
                                    + '<th scope="row">'
                                        + '<div class="checkbox-option-select">'
                                            + '<input id="ads-adsets-' + adsets[e].id + '" name="ads-adsets-' + adsets[e].id + '" type="checkbox" data-id="' + adsets[e].id + '">'
                                            + '<label for="ads-adsets-' + adsets[e].id + '"></label>'
                                        + '</div>'
                                    + '</th>'
                                    + '<td>'
                                        + adsets[e].name
                                    + '</td>'
                                    + '<td>'
                                        + adsets[e].status
                                    + '</td>'
                                    + '<td>'
                                        + adsets[e].campaign.name
                                    + '</td>'                            
                                    + '<td>'
                                        + impressions
                                    + '</td>'
                                    + '<td>'
                                        + spend
                                    + '</td>'
                                + '</tr>';
                
            }
            
            $('.main #ad-sets tbody').html(all_adsets);
            
            if ( data.previous ) {
                
                $('.main #ad-sets .btn-previous').attr('data-url', data.previous);
                $('.main #ad-sets .btn-previous').removeClass('btn-disabled');
                
            } else {
                
                $('.main #ad-sets .btn-previous').addClass('btn-disabled');
                
            }
            
            if ( data.next ) {
                
                $('.main #ad-sets .btn-next').attr('data-url', data.next);
                $('.main #ad-sets .btn-next').removeClass('btn-disabled');                
                
            } else {
                
                $('.main #ad-sets .btn-next').addClass('btn-disabled');  
                
            }
            
            $('.main #ads-adsets-all').prop( 'checked', false );
            
        } else {
            
            var data = '<tr>'
                            + '<td colspan="6" class="p-3">'
                                + words.no_adsets_found
                            + '</td>'
                        + '</tr>';
            
            $('.main #ad-sets tbody').html(data);
            
            $('.main #ad-sets .btn-previous').addClass('btn-disabled');
            $('.main #ad-sets .btn-next').addClass('btn-disabled'); 
            
            $('.main #ads-adsets-all').prop( 'checked', false );            
            
        }
        
    };
    
    /*
     * Display ads by page
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.load_ads_by_pagination = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var ads = data.ads;
            
            var all_ads = '';
            
            for ( var e = 0; e < ads.length; e++ ) {
                
                var impressions = 0;
                
                if (typeof ads[e].insights !== 'undefined') {
                    
                    if (typeof ads[e].insights.data[0] !== 'undefined') {
                        
                        if (typeof ads[e].insights.data[0].impressions !== 'undefined') {
                            
                            impressions = ads[e].insights.data[0].impressions;

                        }                        
                        
                    }
                    
                }
                
                var spend = 0;
                
                if (typeof ads[e].insights !== 'undefined') {
                    
                    if (typeof ads[e].insights.data[0] !== 'undefined') {
                        
                        if (typeof ads[e].insights.data[0].spend !== 'undefined') {
                            
                            spend = ads[e].insights.data[0].spend;

                        }                        
                        
                    }
                    
                } 
                
                if ( typeof Main.account_currency !== 'undefined' ) {

                    spend = spend + ' ' + Main.account_currency;

                }

                 all_ads += '<tr>'
                            + '<th scope="row">'
                                + '<div class="checkbox-option-select">'
                                    + '<input id="ads-ad-' + ads[e].id + '" name="ads-ad-' + ads[e].id + '" type="checkbox" data-id="' + ads[e].id + '">'
                                    + '<label for="ads-ad-' + ads[e].id + '"></label>'
                                + '</div>'
                            + '</th>'
                            + '<td>'
                                + ads[e].name
                            + '</td>'
                            + '<td>'
                                + ads[e].status
                            + '</td>'
                            + '<td>'
                                + ads[e].adset.name
                            + '</td>'                            
                            + '<td>'
                                + impressions
                            + '</td>'
                            + '<td>'
                                + spend
                            + '</td>'
                        + '</tr>';
                
            }
            
            $('.main #ads tbody').html(all_ads);
            
            if ( data.previous ) {
                
                $('.main #ads').find('.btn-previous').attr('data-url', data.previous);
                $('.main #ads').find('.btn-previous').removeClass('btn-disabled');
                
            } else {
                
                $('.main #ads').find('.btn-previous').addClass('btn-disabled');
                
            }
            
            if ( data.next ) {
                
                $('.main #ads').find('.btn-next').attr('data-url', data.next);
                $('.main #ads').find('.btn-next').removeClass('btn-disabled');                
                
            } else {
                
                $('.main #ads').find('.btn-next').addClass('btn-disabled');  
                
            }
            
            $('.main #ads-ad-all').prop( 'checked', false );
            
        } else {
            
            var data = '<tr>'
                            + '<td colspan="6" class="p-3">'
                                + words.no_ads_found
                            + '</td>'
                        + '</tr>';
            
            $('.main #ads tbody').html(data);
            
            $('.main #ads .btn-previous').addClass('btn-disabled');
            $('.main #ads .btn-next').addClass('btn-disabled'); 
            
            $('.main #ads-ad-all').prop( 'checked', false );            
            
        }
        
    };
    
    /*
     * Display pixel's conversions by page
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.load_pixel_conversions_by_pagination = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var conversions = data.conversions;
            
            var all_conversions = '';
            
            for ( var e = 0; e < conversions.length; e++ ) {
                
                var custom_event_type = '';
                
                switch ( conversions[e].custom_event_type ) {
                    
                    case 'CONTENT_VIEW':
                        
                        custom_event_type = words.view_content;
                        
                        break;
                        
                    case 'SEARCH':
                        
                        custom_event_type = words.search;
                        
                        break; 
                    
                    case 'ADD_TO_CART':
                        
                        custom_event_type = words.add_to_cart;
                        
                        break; 
                        
                    case 'ADD_TO_WISHLIST':
                        
                        custom_event_type = words.add_to_wishlist;
                        
                        break;                     
                    
                    case 'INITIATED_CHECKOUT':
                        
                        custom_event_type = words.initiate_checkout;
                        
                        break;
                    
                    case 'ADD_PAYMENT_INFO':
                        
                        custom_event_type = words.add_payment_info;
                        
                        break;
                        
                    case 'PURCHASE':
                        
                        custom_event_type = words.purchase;
                        
                        break;
                    
                    case 'LEAD':
                        
                        custom_event_type = words.lead;
                        
                        break;
                    
                    case 'COMPLETE_REGISTRATION':
                        
                        custom_event_type = words.complete_registration;
                        
                        break;                    
                    
                }
                
                var rule = '';
                
                if ( typeof conversions[e].rule !== 'undefined' ) {
                    
                    var parse = JSON.parse(conversions[e].rule);
                    
                    if ( typeof parse.url !== 'undefined' ) {
                        
                        if ( typeof parse.url.i_contains !== 'undefined' ) {
                            
                            rule = parse.url.i_contains;

                        }
                        
                    }
                    
                }
                
                all_conversions += '<tr>'
                                    + '<td>'
                                        + conversions[e].name
                                    + '</td>'
                                    + '<td>'
                                        + custom_event_type
                                    + '</td>'
                                    + '<td>'
                                        + rule
                                    + '</td>'
                                + '</tr>';
                
            }
            
            $('.main #pixel-conversion tbody').html(all_conversions);
            
            if ( data.previous ) {
                
                $('.main #pixel-conversion .btn-previous').attr('data-url', data.previous);
                $('.main #pixel-conversion .btn-previous').removeClass('btn-disabled');
                
            } else {
                
                $('.main #pixel-conversion .btn-previous').addClass('btn-disabled');
                
            }
            
            if ( data.next ) {
                
                $('.main #pixel-conversion .btn-next').attr('data-url', data.next);
                $('.main #pixel-conversion .btn-next').removeClass('btn-disabled');                
                
            } else {
                
                $('.main #pixel-conversion .btn-next').addClass('btn-disabled');  
                
            }
            
        } else {
            
            var data = '<tr>'
                            + '<td colspan="6" class="p-3">'
                                + words.no_conversion_tracking_found
                            + '</td>'
                        + '</tr>';
            
            $('.main #pixel-conversion tbody').html(data);
            
            $('.main #pixel-conversion .btn-previous').addClass('btn-disabled');
            $('.main #pixel-conversion .btn-next').addClass('btn-disabled');           
            
        }
        
    };
    
    /*
     * Display campaigns deletion response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.delete_ad_campaigns = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Load AD Campaigns
            Main.load_ad_campaigns();

            setTimeout(function(){
                    
                // Load all Ad Sets
                Main.load_ad_sets();
                
            }, 2000);

            setTimeout(function(){
                
                // Load all Ads
                Main.load_ad_account_ads();
                
            }, 4000);
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
    };
    
    /*
     * Display ad sets deletion response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.delete_ad_sets = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Load all Ad Sets
            Main.load_ad_sets();

            setTimeout(function(){
                
                // Load all Ads
                Main.load_ad_account_ads();
                
            }, 2000);
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
    };
    
    /*
     * Display ads deletion response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.delete_ads = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Load all Ads
            Main.load_ad_account_ads();
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
    };
    
    /*
     * Display campaigns creation response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.create_ad_campaigns = function ( status, data ) {
        
        // Empty response
        $('.main #ads-create-campaign .alerts-display-reports').empty();        
        
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
                
                // Load AD Campaigns
                Main.load_ad_campaigns();
                
                setTimeout(function(){
                    
                    // Load all Ad Sets
                    Main.load_ad_sets();
                    
                }, 2000);

                setTimeout(function(){
                    
                    // Load all Ads
                    Main.load_ad_account_ads();
                    
                }, 4000);

                // Reset Form
                $('.main .facebook-ads-create-campaign')[0].reset();
                
                // Reset Campaign's objective
                $('.main #ads-create-campaign .ads-campaign-objective' ).attr('data-id', 'LINK_CLICKS');
                $('.main #ads-create-campaign .ads-campaign-objective' ).html(words.link_clicks);  
                
                // Check all placements
                $( '.main #ads-create-campaign #ads-campaign-ad-set-placements input[type="checkbox"]' ).prop('checked', true);
                
                // Empty image field
                $('.main #ads-create-campaign #myTabContent5 > .tab-pane .ads-uploaded-photo').empty();
                
                // Empty video field
                $('.main #ads-create-campaign #myTabContent5 > .tab-pane .ads-uploaded-video').empty();  
                
                // Empty preview
                $('.main #ads-create-campaign #myTabContent5 > .tab-pane .ad-preview-display .panel-body').empty();
                
                if ( $('.main #ads-create-campaign a[href="#campaign-create-ad-set"]').attr('aria-expanded') === 'true' ) {
                
                    // Hide Campaign Ad Sets tab
                    $('.main #ads-create-campaign a[href="#campaign-create-ad-set"]').click();
                
                }
                
                if ( $('.main #ads-create-campaign a[href="#campaign-create-ads"]').attr('aria-expanded') === 'true' ) {
                
                    // Hide Campaign Ad tab
                    $('.main #ads-create-campaign a[href="#campaign-create-ads"]').click();
                
                }
                
                // Load Ad's Account details
                Main.load_ad_account_details();  
                
                // Reset genders
                $('.main #ads-create-campaign .ads-campaign-ad-genders input[type="checkbox"]' ).prop('checked', true);
                
                // Reset age
                $('.main #ads-create-campaign .ads-campaign-age-from' ).attr('data-id', '0');
                $('.main #ads-create-campaign .ads-campaign-age-from' ).text(words.age_from);
                $('.main #ads-create-campaign .ads-campaign-age-to' ).attr('data-id', '0');
                $('.main #ads-create-campaign .ads-campaign-age-to' ).text(words.age_to);
                
                // Reset devices
                $('.main #ads-create-campaign .ads-campaign-select-type input[type="checkbox"]' ).prop('checked', true);
                
                // Reset Optimization Goal
                $('.main #ads-create-campaign .ads-campaign-optimization-goal' ).attr('data-id', 'IMPRESSIONS');
                $('.main #ads-create-campaign .ads-campaign-optimization-goal' ).html(words.impressions);
                
                // Reset Billing Event
                $('.main #ads-create-campaign .ads-campaign-billing-event' ).attr('data-id', 'IMPRESSIONS');
                $('.main #ads-create-campaign .ads-campaign-billing-event' ).html(words.impressions);

                // Remove boost selection
                $('.main #ads-create-campaign .post-engagement-boost-it' ).remove('boost-this-post');

                // Reset Ad Campaign objective
                $('.main #ads-create-campaign .ads-campaign-objective-list a[data-id="LINK_CLICKS"]' ).click();
            
            }
            
            // Display response
            $('.main #ads-create-campaign .alerts-display-reports').html(response);

            if ( $('.main .modal.show .search_for_region').length > 0 ) {
                $('.main .modal.show .search_for_region').val('');
            }

            // Reset regions
            setTimeout(function() {

                // Empty region
                $('.main .modal.show .select-regions').empty();

                // Load regions
                Main.load_regions();

            }, 3000);

            // Reset cities
            setTimeout(function() {

                // Empty city
                $('.main .modal.show .select-cities').empty();

                // Load cities
                Main.load_cities();

            }, 3500);
            
        }
        
    }; 
    
    /*
     * Display ad set creation response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.create_ad_set = function ( status, data ) {

        // Empty response
        $('.main #ads-create-ad-set .alerts-display-reports').empty();        
        
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

                // Load all Ad Sets
                Main.load_ad_sets();

                setTimeout(function(){
                    
                    // Load all Ads
                    Main.load_ad_account_ads();
                    
                }, 2000);
                
                // Display All Campaigns 
                $( '.main #ads-create-ad-set .ads-selected-ad-campaign' ).text(words.ad_campaigns);
                $( '.main #ads-create-ad-set .ads-selected-ad-campaign' ).removeAttr('data-id');

                // Check all placements
                $( '.main .ads-adset-ad-set-placements input[type="checkbox"]' ).prop('checked', true);
                
                // Empty image field
                $('.main #ads-create-ad-set #myTabContent6 > .tab-pane .ads-uploaded-photo').empty();

                // Empty video field
                $('.main #ads-create-ad-set #myTabContent6 > .tab-pane .ads-uploaded-video').empty();  

                // Empty preview
                $('.main #ads-create-ad-set #myTabContent6 > .tab-pane .ad-preview-display .panel-body').empty();

                if ( $('.main #ads-create-ad-set a[href="#adset-create-ad-set"]').attr('aria-expanded') === 'true' ) {

                    // Hide Adset Ad Sets tab
                    $('.main #ads-create-ad-set a[href="#adset-create-ad-set"]').click();

                }

                if ( $('.main #ads-create-ad-set a[href="#adset-create-ads"]').attr('aria-expanded') === 'true' ) {

                    // Hide Adset Ad tab
                    $('.main #ads-create-ad-set a[href="#adset-create-ads"]').click();

                }
                
                // Load Ad's Account details
                Main.load_ad_account_details();  
                
                // Reset genders
                $('.main #ads-create-ad-set .ads-ad-set-ad-genders input[type="checkbox"]' ).prop('checked', true);
                
                // Reset age
                $('.main #ads-create-ad-set .ads-ad-set-age-from' ).attr('data-id', '0');
                $('.main #ads-create-ad-set .ads-ad-set-age-from' ).text(words.age_from);
                $('.main #ads-create-ad-set .ads-ad-set-age-to' ).attr('data-id', '0');
                $('.main #ads-create-ad-set .ads-ad-set-age-to' ).text(words.age_to);
                
                // Reset devices
                $('.main #ads-create-ad-set .ads-set-select-type input[type="checkbox"]' ).prop('checked', true);
                
                // Reset Optimization Goal
                $('.main #ads-create-ad-set .ads-ad-set-optimization-goal' ).attr('data-id', 'IMPRESSIONS');
                $('.main #ads-create-ad-set .ads-ad-set-optimization-goal' ).html(words.impressions);
                
                // Reset Billing Event
                $('.main #ads-create-ad-set .ads-ad-set-billing-event' ).attr('data-id', 'IMPRESSIONS');
                $('.main #ads-create-ad-set .ads-ad-set-billing-event' ).html(words.impressions);

                if ( $('.main .modal.show .search_for_region').length > 0 ) {
                    $('.main .modal.show .search_for_region').val('');
                }
    
                // Reset regions
                setTimeout(function() {
    
                    // Empty region
                    $('.main .modal.show .select-regions').empty();
    
                    // Load regions
                    Main.load_regions();
    
                }, 3000);

                // Reset cities
                setTimeout(function () {

                    // Empty city
                    $('.main .modal.show .select-cities').empty();

                    // Load cities
                    Main.load_cities();

                }, 3500);

            }

            // Display response
            $('.main #ads-create-ad-set .alerts-display-reports').html(response);

        }
        
    };
    
    /*
     * Display ad creation response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.create_ad = function ( status, data ) {

        // Empty response
        $('.main #ads-create-new-ad .alerts-display-reports').empty();        
        
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
                $('.main .facebook-ads-create-new-ad')[0].reset();                

                // Load all Ads
                Main.load_ad_account_ads();
                
                // Display All Campaigns 
                $( '.main #ads-create-new-ad .ads-selected-ad-campaign' ).text(words.ad_campaigns);
                $( '.main #ads-create-new-ad .ads-selected-ad-campaign' ).removeAttr('data-id');

                // Display All Adsets
                $( '.main #ads-create-new-ad .ads-selected-ad-set' ).text(words.ad_sets);
                $( '.main #ads-create-new-ad .ads-selected-ad-set' ).removeAttr('data-id');                

                // Empty image field
                $('.main #ads-create-new-ad #myTabContent7 > .tab-pane .ads-uploaded-photo').empty();

                // Empty video field
                $('.main #ads-create-new-ad #myTabContent7 > .tab-pane .ads-uploaded-video').empty();  

                // Empty preview
                $('.main #ads-create-new-ad #myTabContent7 > .tab-pane .ad-preview-display .panel-body').empty();

                if ( $('.main #ads-create-new-ad a[href="#ads-select-ad-set"]').attr('aria-expanded') === 'true' ) {

                    // Hide Ads Ad Sets tab
                    $('.main #ads-create-new-ad a[href="#ads-select-ad-set"]').click();

                }

                if ( $('.main #ads-create-new-ad a[href="#ads-create-ads"]').attr('aria-expanded') === 'true' ) {

                    // Hide Ads Ad tab
                    $('.main #ads-create-new-ad a[href="#ads-create-ads"]').click();

                }

            }

            // Display response
            $('.main #ads-create-new-ad .alerts-display-reports').html(response);

        }
        
    };
    
    /*
     * Display connected Instagram accounts
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.display_connected_instagram_accounts = function ( status, data ) {
        
        // Hide accounts
        $('.main .connect-instagram-account .btn-select').empty();
        $('.main .connect-instagram-account .btn-select').removeAttr('data-id');
        $('.main .connect-instagram-account ul').empty();

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            if ( typeof data.accounts !== 'undefined' ) {
                
                var all_accounts = '';
                
                // List all accounts
                for ( var g = 0; g < data.accounts.data.length; g++ ) {
                    
                    var avatar = url + 'assets/img/avatar-placeholder.png';

                    if ( typeof data.accounts.data[g].profile_pic !== 'undefined' ) {
                        avatar = data.accounts.data[g].profile_pic;
                    }
                    
                    if ( g < 1 ) {
                        
                        var text = '<img src="' + avatar + '">'
                                    + data.accounts.data[g].username;
                        
                        // Add first account as selected
                        $('.main .connect-instagram-account .btn-select').html(text);
                        $('.main .connect-instagram-account .btn-select').attr('data-id', data.accounts.data[g].id);
                        
                    }
                    
                    all_accounts += '<li class="list-group-item">'
                                        + '<a href="#" data-id="' + data.accounts.data[g].id + '">'
                                            + '<img src="' + avatar + '">'
                                            + data.accounts.data[g].username
                                        + '</a>'
                                    + '</li>';
                    
                }
                
                // Add accounts
                $('.main .connect-instagram-account ul').html(all_accounts);
                
            } 
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }        
        
    };
    
    /*
     * Display url's preview
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.ads_display_url_preview = function ( status, data ) {

        // Set preview headline
        Main.preview.title = data.response.title;
        
        // Set preview description
        Main.preview.description = data.response.description;

        // Set preview cover
        Main.preview.url_cover = data.response.img;

        // Generate preview
        Main.reload_ad_link_preview();
        
    };
    
    /*
     * Display ad media deletion response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.delete_ad_media = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            if ( data.type === 'image' ) {
                
                if ( $('.main #ads-create-campaign').hasClass('show') ) {

                    // Empty image field
                    $('.main #myTabContent5 > .tab-pane.active .ads-uploaded-photo').empty();

                } else if ( $('.main #ads-create-ad-set').hasClass('show') ) {

                    // Empty image field
                    $('.main #myTabContent6 > .tab-pane.active .ads-uploaded-photo').empty();

                } else if ( $('.main #ads-create-new-ad').hasClass('show') ) {

                    // Empty image field
                    $('.main #myTabContent7 > .tab-pane.active .ads-uploaded-photo').empty();

                }
                
                // Delete preview's image
                delete Main.preview.image;
            
            } else {
                
                if ( $('.main #ads-create-campaign').hasClass('show') ) {

                    // Empty image field
                    $('.main #myTabContent5 > .tab-pane.active .ads-uploaded-photo').empty();

                } else if ( $('.main #ads-create-ad-set').hasClass('show') ) {

                    // Empty image field
                    $('.main #myTabContent6 > .tab-pane.active .ads-uploaded-photo').empty();

                } else if ( $('.main #ads-create-new-ad').hasClass('show') ) {

                    // Empty image field
                    $('.main #myTabContent7 > .tab-pane.active .ads-uploaded-photo').empty();

                }
                
                // Delete preview's image
                delete Main.preview.video_source;
                
            }

            // Generate preview
            Main.reload_ad_link_preview();
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };
    
    /*
     * Display Pixel's conversion creation response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.create_pixel_conversion = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Reset form
            $('.main .facebook-ads-create-pixel-conversion')[0].reset();
            
            // Add default select type
            $('.main .facebook-ads-create-pixel-conversion #ads-select-conversion-type').removeAttr('data-id');
            $('.main .facebook-ads-create-pixel-conversion #ads-select-conversion-type').html('<i class="far fa-arrow-alt-circle-right"></i> ' + words.select_type);
            
            // Load Pixel's conversions
            Main.load_pixel_conversions();
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };
    
    /*
     * Display Pixel's conversions response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.load_all_pixel_coversions = function ( status, data ) {
        
        if ( $('.main #ads-create-campaign').hasClass('show') ) {
        
            $('.main #ads-create-campaign .pixel-conversion-tracking .panel-body').empty();
            $('.main #ads-create-campaign .pixel-conversion-tracking').hide();
        
        } else if ( $('.main #ads-create-ad-set').hasClass('show') ) {
            
            $('.main #ads-create-ad-set .pixel-conversion-tracking .panel-body').empty();
            $('.main #ads-create-ad-set .pixel-conversion-tracking').hide();            
            
        } else {
            
            $('.main #ads-create-new-ad .pixel-conversion-tracking .panel-body').empty();
            $('.main #ads-create-new-ad .pixel-conversion-tracking').hide();            
            
        }

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            if ( data.conversions.length ) {
            
                var conversions = '';

                for ( var c = 0; c < data.conversions.length; c++ ) {

                    conversions += '<li class="list-group-item">'
                                        + '<a href="#" data-id="' + data.conversions[c].id + '">'
                                            + data.conversions[c].name
                                        + '</a>'
                                    + '</li>';

                }

                var pixel = '<div class="row">'
                                + '<div class="col-12">'
                                    + '<h3>'
                                        + 'Facebook Pixel'
                                    + '</h3>'
                                    + '<p>'
                                        + words.your_facebook_pixel_account
                                    + '</p>'
                                + '</div>'
                            + '</div>'
                            + '<div class="row">'
                                + '<div class="col-12 links-clicks-preview-settings text-center">' 
                                    + '<div class="dropdown">'
                                        + '<button class="btn btn-secondary btn-select pixel-id" data-id="' + data.conversions[0].pixel.id + '" type="button">'
                                            + data.conversions[0].pixel.name
                                        + '</button>'
                                    + '</div>'                                                                      
                                + '</div>'
                            + '</div>'
                            + '<div class="row">'
                                + '<div class="col-12">'
                                    + '<h3>'
                                        + words.conversion_tracking
                                    + '</h3>'
                                    + '<p>'
                                        + words.select_a_conversion_tracking
                                    + '</p>'
                                + '</div>'
                            + '</div>'
                            + '<div class="row">'
                                + '<div class="col-12 links-clicks-preview-settings text-center">'
                                    + '<div class="dropdown">'
                                        + '<button class="btn btn-secondary dropdown-toggle pixel-conversion-id btn-select" data-id="0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                                            + 'No conversion selected'
                                        + '</button>'
                                        + '<div class="dropdown-menu ads-campaign-dropdown" aria-labelledby="dropdownMenuButton">'
                                            + '<div class="card">'
                                                + '<div class="card-head">'
                                                    + '<input type="text" class="ad-creation-filter-pixel-conversions" placeholder="' + words.search_pixel_conversions + '">'
                                                + '</div>'
                                                + '<div class="card-body">'
                                                    + '<ul class="list-group ad-creation-filter-pixel-conversions-list">'
                                                        + conversions
                                                    + '</ul>'
                                                + '</div>'
                                            + '</div>'
                                        + '</div>'
                                    + '</div>'                                                                         
                                + '</div>'
                            + '</div>';

                if ( $('.main #ads-create-campaign').hasClass('show') ) {

                    $('.main #ads-create-campaign .pixel-conversion-tracking .panel-body').html(pixel);
                    $('.main #ads-create-campaign .pixel-conversion-tracking').show();

                }  else if ( $('.main #ads-create-ad-set').hasClass('show') ) {
                    
                    $('.main #ads-create-ad-set .pixel-conversion-tracking .panel-body').html(pixel);
                    $('.main #ads-create-ad-set .pixel-conversion-tracking').show();                           
                    
                } else {
            
                    $('.main #ads-create-new-ad .pixel-conversion-tracking .panel-body').empty();
                    $('.main #ads-create-new-ad .pixel-conversion-tracking').hide();

                }
                
            }
            
        }       

    };
    
    /*
     * Display Ad's identity response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.load_ad_identity = function ( status, data ) {

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
                    
                    if ( typeof data.connected_instagram !== 'undefined' ) {

                        if (data.connected_instagram.data.length > 0) {

                            var avatar = url + 'assets/img/avatar-placeholder.png';

                            if (typeof data.connected_instagram.data[0].profile_pic !== 'undefined') {
                                avatar = data.connected_instagram.data[0].profile_pic;
                            }

                            instagram_button = '<button class="btn btn-secondary dropdown-toggle ads-instagram-id btn-select" data-id="' + data.connected_instagram.data[0].id + '" type="button" id="dropdownInstagramSelect" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                                + '<img src="' + avatar + '">'
                                + data.connected_instagram.data[0].username
                                + '</button>';

                            instagram_accounts += '<li class="list-group-item">'
                                + '<a href="#" data-id="' + data.connected_instagram.data[0].id + '">'
                                + '<img src="' + avatar + '">'
                                + data.connected_instagram.data[0].username
                                + '</a>'
                                + '</li>';

                        }
                        
                    }
                    
                }
                
                all_pages += '<li class="list-group-item">'
                                + '<a href="#" data-id="' + data.account_pages.data[p].id + '">'
                                    + '<img src="' + data.account_pages.data[p].picture.data.url + '">'
                                    + data.account_pages.data[p].name
                                + '</a>'
                            + '</li>';
                
            }
            
            // Set identity
            var identity = '<div class="panel">'
                                + '<div class="panel-heading">'
                                    + '<div class="row">'
                                        + '<div class="col-6">'                                                                    
                                            + '<h4 class="panel-title">'
                                                + data.words.identity
                                            + '</h4>'
                                        + '</div>'
                                        + '<div class="col-6 text-right">'                                                                         
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                                + '<div class="panel-body">'
                                    + '<div class="row">'
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
                                                            + '<ul class="list-group ad-creation-filter-fb-pages-list">'
                                                                + all_pages
                                                            + '</ul>'
                                                        + '</div>'
                                                    + '</div>'
                                                + '</div>'
                                            + '</div>'
                                        + '</div>'
                                    + '</div>'
                                    + '<div class="row">'
                                        + '<div class="col-12">'
                                            + '<h3>'
                                                + 'Instagram'
                                            + '</h3>'
                                            + '<p>'
                                                + data.words.instagram_below_connected_facebook
                                            + '</p>'
                                        + '</div>'
                                    + '</div>'
                                    + '<div class="row connect-instagram-account">'
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
                                    + '</div>'
                                + '</div>'
                            + '</div>';
                    
            $('.main .modal.show').find('.ad-identity').html(identity);
            
        } else {
            
            // Hide identity
            $('.main .modal.show').find('.ad-identity').hide();    
            $('.main .modal.show').find('.ad-identity').empty();
            
        }        

    };
    
    /*
     * Display account's information response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.load_ad_account_details = function ( status, data ) {
        
        // Empty fields
        $( '.main .ad_set_target_cost' ).empty();
        $( '.main .ad_set_daily_budget' ).empty();
        $( '.main .ad_set_default_country' ).empty();
        $('.main input[type="checkbox"]').prop( 'checked', false ); 
        
        // Reset Campaign's objective
        $('.main #ads-create-campaign .ads-campaign-objective' ).attr('data-id', 'LINK_CLICKS');
        $('.main #ads-create-campaign .ads-campaign-objective' ).html(words.link_clicks);        
        
        // Reset genders
        $('.main #ads-create-campaign .ads-campaign-ad-genders input[type="checkbox"]' ).prop('checked', true);
        $('.main #ads-create-ad-set .ads-ad-set-ad-genders input[type="checkbox"]' ).prop('checked', true);
        
        // Reset devices
        $('.main #ads-create-campaign .ads-campaign-select-type input[type="checkbox"]' ).prop('checked', true);
        $('.main #ads-create-ad-set .ads-set-select-type input[type="checkbox"]' ).prop('checked', true);
        
        // Reset Optimization Goal
        $('.main #ads-create-campaign .ads-campaign-optimization-goal' ).attr('data-id', 'IMPRESSIONS');
        $('.main #ads-create-campaign .ads-campaign-optimization-goal' ).html(words.impressions);
        $('.main #ads-create-ad-set .ads-ad-set-optimization-goal' ).attr('data-id', 'IMPRESSIONS');
        $('.main #ads-create-ad-set .ads-ad-set-optimization-goal' ).html(words.impressions);        
        
        // Reset Billing Event
        $('.main #ads-create-campaign .ads-campaign-billing-event' ).attr('data-id', 'IMPRESSIONS');
        $('.main #ads-create-campaign .ads-campaign-billing-event' ).html(words.impressions);
        $('.main #ads-create-ad-set .ads-ad-set-billing-event' ).attr('data-id', 'IMPRESSIONS');
        $('.main #ads-create-ad-set .ads-ad-set-billing-event' ).html(words.impressions);

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Get currency code
            if ( typeof data.account_details.currency !== 'undefined' ) {
            
                // Set currency
                var currency = data.account_details.currency;
                
                if ( typeof data.account_details.minimum_budgets !== 'undefined' ) {
                    
                    for ( var d = 0; d < data.account_details.minimum_budgets.data.length; d++ ) {
                        
                        if ( data.account_details.minimum_budgets.data[d].currency === currency ) {
                            
                            $( '.main .ad_set_target_cost' ).html('<em>(' + data.words.min_target_cost + ': ' + data.account_details.minimum_budgets.data[d].min_daily_budget_high_freq + ')</em>');
                            $( '.main .ad_set_daily_budget' ).html('<em>(' + data.words.min_daily_budget + ': ' + data.account_details.minimum_budgets.data[d].min_daily_budget_high_freq + ')</em>');
                            
                        }
                        
                    }
                    
                }               
            
            }
            
            if ( typeof data.account_details.business_country_code !== 'undefined' ) {
                
                // Get country
                var country = $('.main #ads-create-campaign .ads-campaign-set-countries-list input[data-id="' + data.account_details.business_country_code + '"]').closest('.list-group-item').text();
                
                // Check country
                $('.main .ads-campaign-set-countries-list input[data-id="' + data.account_details.business_country_code + '"]').prop( 'checked', true ); 

                $( '.main .ad_set_default_country' ).html('<em>(' + data.words.default_country + ': ' + country + ')</em>');

            } 
            
            if ( $('.main #ads-create-campaign').hasClass('show') ) {

                // Check all placements
                $( '.main #ads-create-campaign .ads-campaign-ad-set-placements input[type="checkbox"]' ).prop('checked', true);

            } else if ( $('.main #ads-create-ad-set').hasClass('show') ) {

                // Check all placements
                $( '.main #ads-create-ad-set .ads-adset-ad-set-placements input[type="checkbox"]' ).prop('checked', true);

            }
            
        } 

    };    
    
    /*
     * Display Pixel's conversions response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.filter_pixel_coversions = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var conversions = '';
            
            for ( var c = 0; c < data.conversions.length; c++ ) {
                
                conversions += '<li class="list-group-item">'
                                    + '<a href="#" data-id="' + data.conversions[c].id + '">'
                                        + data.conversions[c].name
                                    + '</a>'
                                + '</li>';
                
            }
                
            $('.main #ads-create-campaign .pixel-conversion-tracking .panel-body .ad-creation-filter-pixel-conversions-list').html(conversions);
            
        } else {
            
            // Display no conversions found message
            $('.main #ads-create-campaign .pixel-conversion-tracking .panel-body .ad-creation-filter-pixel-conversions-list').html('<li class="list-group-item">&nbsp;&nbsp;&nbsp;' + data.message + '</li>');
            
        }  

    };
    
    /*
     * Display Account Insights response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.load_account_overview = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Get spent amunt
            var spent = data.account_insights.spend;
            
            // Get account currency
            var account_currency = data.account_insights.account_currency;
            
            // Get social spent amunt
            var social_spent = data.account_insights.social_spend;
            
            // Get impressions
            var impressions = data.account_insights.impressions;
            
            // Get clicks
            var clicks = data.account_insights.clicks;  
            
            // Get reach
            var reach = data.account_insights.reach;  
            
            // Get frequency
            var frequency = data.account_insights.frequency;  
            
            // Get cpm
            var cpm = data.account_insights.cpm; 
            
            var cpp = 0;
            
            if ( typeof data.account_insights.cpp !== 'undefined' ) {
                cpp = data.account_insights.cpp;
            }
            
            // Get ctr
            var ctr = data.account_insights.ctr; 
            
            // Display spent amount
            $('.main .overview-stats-total-spent').html( spent + ' ' + account_currency );
            
            // Display social spent amount
            $('.main .overview-stats-social-spent').html( social_spent + ' ' + account_currency );  
            
            // Display impressions
            $('.main .overview-stats-impressions').html( impressions );  
            
            // Display clicks
            $('.main .overview-stats-clicks').html( clicks ); 
            
            // Display reach
            $('.main .overview-stats-reach').html( reach ); 
            
            // Display reach
            $('.main .overview-stats-frequency').html( frequency );  
            
            // Display cpm
            $('.main .overview-stats-cpm').html( cpm );
            
            // Display cpp
            $('.main .overview-stats-cpp').html( cpp );
            
            // Display ctr
            $('.main .overview-stats-ctr').html( ctr );
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };
    
    /*
     * Display Account Insights response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.load_account_insights = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var insights = '';
            
            for ( var e = 0; e < data.account_insights.length; e++ ) {

                var cpc = 0;

                if ( typeof data.account_insights[e].cpc !== 'undefined' ) {
                    cpc = data.account_insights[e].cpc;
                }
                
                insights += '<tr>'
                               + '<td>'
                                   + data.account_insights[e].date_start
                               + '</td>'
                               + '<td>'
                                   + data.account_insights[e].impressions
                               + '</td>'
                               + '<td>'
                                   + data.account_insights[e].reach
                               + '</td>'
                               + '<td>'
                                   + data.account_insights[e].unique_clicks
                               + '</td>'
                               + '<td>'
                                   + data.account_insights[e].cpm
                               + '</td>'
                               + '<td>'
                                   + cpc
                               + '</td>'
                               + '<td>'
                                   + data.account_insights[e].ctr
                               + '</td>'
                               + '<td>'
                                   + data.account_insights[e].account_currency + ' ' + data.account_insights[e].spend
                               + '</td>'
                           + '</tr>';
                
            }
            
            $('.main #insights tbody').html(insights);
            
        } else {
            
            var message = '<tr>'
                           + '<td>'
                               + words.no_insights_found
                           + '</td>'
                       + '</tr>';
            
            $('.main #insights tbody').html(message);
            
        }

    };
    
    /*
     * Display Ad Account overview tab
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.load_ad_account_overview = function ( status, data ) {
        
        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var cpp = 0;
            
            if ( typeof data.account_insights.cpp !== 'undefined' ) {
                cpp = data.account_insights.cpp;
            }
            
            if ( typeof data.account_insights.account_currency !== 'undefined' ) {

                // Set account currency variable
                Main.account_currency = data.account_insights.account_currency;
                
                $('.main .ads-account-currency').text(data.account_insights.account_currency);
            
            }
            
            // Set overview content
            var overview = '<div class="row page-titles">'
                                + '<div class="col-xl-6">'
                                    + '<div class="dropdown">'
                                        + '<button class="btn btn-secondary dropdown-toggle overview-filter-btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                                            + '<i class="far fa-calendar-alt"></i>'
                                            + data.words.today
                                        + '</button>'
                                        + '<div class="dropdown-menu overview-filter-list" aria-labelledby="dropdownMenuButton">'
                                            + '<a class="dropdown-item" href="#" data-type="1">'
                                                + '<i class="far fa-calendar-alt"></i>'
                                                + data.words.today
                                            + '</a>'
                                            + '<a class="dropdown-item" href="#" data-type="2">'
                                                + '<i class="far fa-calendar-alt"></i>'
                                                + data.words.week
                                            + '</a>'
                                            + '<a class="dropdown-item" href="#" data-type="3">'
                                                + '<i class="far fa-calendar-alt"></i>'
                                                + data.words.month
                                            + '</a>'
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                                + '<div class="col-xl-6 text-right clean">'
                                    + '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#ads-create-new-ad">'
                                        + '<i class="icon-puzzle"></i>'
                                        + data.words.new_ad
                                    + '</button>'
                                + '</div>'                            
                            + '</div>'
                            + '<div class="row">'
                                + '<div class="col-xl-4">'
                                    + '<div class="col-12 overview-insights-single">'
                                        + '<div class="row">'
                                            + '<div class="col-8">'
                                                + '<h4>'
                                                    + data.words.total_spent
                                                + '</h4>'
                                                + '<p class="overview-stats-total-spent">' + data.account_insights.spend + ' ' + data.account_insights.account_currency + '</p>'
                                            + '</div>'
                                            + '<div class="col-4 text-center">'
                                                + '<i class="fas fa-hand-holding-usd"></i>'
                                            + '</div>'                                  
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                                + '<div class="col-xl-4">'
                                    + '<div class="col-12 overview-insights-single">'
                                        + '<div class="row">'
                                            + '<div class="col-8">'
                                                + '<h4>'
                                                    + data.words.social_spent
                                                + '</h4>'
                                                + '<p class="overview-stats-social-spent">'
                                                    + data.account_insights.social_spend + ' ' + data.account_insights.account_currency
                                                + '</p>'
                                            + '</div>'
                                            + '<div class="col-4 text-center">'
                                                + '<i class="fab fa-facebook"></i>'
                                            + '</div>'                                          
                                        + '</div>'
                                    + '</div>'
                                + '</div>'                               
                                + '<div class="col-xl-4">'
                                    + '<div class="col-12 overview-insights-single">'
                                        + '<div class="row">'
                                            + '<div class="col-8">'
                                                + '<h4>'
                                                    + data.words.impressions
                                                + '</h4>'
                                                + '<p class="overview-stats-impressions">'
                                                    + data.account_insights.impressions
                                                + '</p>'
                                            + '</div>'
                                            + '<div class="col-4 text-center">'
                                                + '<i class="far fa-eye"></i>'
                                            + '</div>'                    
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                                + '<div class="col-xl-4">'
                                    + '<div class="col-12 overview-insights-single">'
                                        + '<div class="row">'
                                            + '<div class="col-8">'
                                                + '<h4>'
                                                    + data.words.clicks
                                                + '</h4>'
                                                + '<p class="overview-stats-clicks">'
                                                    + data.account_insights.clicks
                                                + '</p>'
                                            + '</div>'
                                            + '<div class="col-4 text-center">'
                                                + '<i class="fas fa-street-view"></i>'
                                            + '</div>'            
                                       + '</div>'
                                    + '</div>'
                                + '</div>'
                                + '<div class="col-xl-4">'
                                    + '<div class="col-12 overview-insights-single">'
                                        + '<div class="row">'
                                            + '<div class="col-8">'
                                                + '<h4>'
                                                    + data.words.reach
                                                + '</h4>'
                                                + '<p class="overview-stats-reach">'
                                                    + data.account_insights.reach
                                                + '</p>'
                                            + '</div>'
                                            + '<div class="col-4 text-center">'
                                                + '<i class="fas fa-chalkboard-teacher"></i>'
                                            + '</div>'     
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                                + '<div class="col-xl-4">'
                                    + '<div class="col-12 overview-insights-single">'
                                        + '<div class="row">'
                                            + '<div class="col-8">'
                                                + '<h4>'
                                                    + data.words.frequency
                                                + '</h4>'
                                                + '<p class="overview-stats-frequency">'
                                                    + data.account_insights.frequency
                                                + '</p>'
                                            + '</div>'
                                            + '<div class="col-4 text-center">'
                                                + '<i class="fas fa-chart-line"></i>'
                                            + '</div>'
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                                + '<div class="col-xl-4">'
                                    + '<div class="col-12 overview-insights-single">'
                                        + '<div class="row">'
                                            + '<div class="col-8">'
                                                + '<h4>'
                                                    + data.words.cpm
                                                + '</h4>'
                                                + '<p class="overview-stats-cpm">'
                                                    + data.account_insights.cpm
                                                + '</p>'
                                            + '</div>'
                                            + '<div class="col-4 text-center">'
                                                + '<i class="fas fa-file-contract"></i>'
                                            + '</div>'          
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                                + '<div class="col-xl-4">'
                                    + '<div class="col-12 overview-insights-single">'
                                        + '<div class="row">'
                                            + '<div class="col-8">'
                                                + '<h4>'
                                                    + data.words.cpp
                                                + '</h4>'
                                                + '<p class="overview-stats-cpp">'
                                                    + cpp
                                                + '</p>'
                                            + '</div>'
                                            + '<div class="col-4 text-center">'
                                                + '<i class="fas fa-cart-plus"></i>'
                                            + '</div>'              
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                                + '<div class="col-xl-4">'
                                    + '<div class="col-12 overview-insights-single">'
                                        + '<div class="row">'
                                            + '<div class="col-8">'
                                                + '<h4>'
                                                    + data.words.ctr
                                                + '</h4>'
                                                + '<p class="overview-stats-ctr">'
                                                    + data.account_insights.ctr
                                                + '</p>'
                                            + '</div>'
                                            + '<div class="col-4 text-center">'
                                                + '<i class="fas fa-user-tag"></i>'
                                            + '</div>'               
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                            + '</div>';
                    
            // Show Overview
            $('.main #overview').html( overview );  
            
        } else {
            
            var overview = '<div class="row">'
                                + '<div class="col-xl-12">'
                                    + '<div class="input-group no-account-selected">'
                                        + '<div class="input-group-prepend">'
                                            + '<span class="input-group-text">'
                                                + '<i class="icon-user-unfollow"></i>'
                                            + '</span>'
                                        + '</div>'
                                        + '<div class="form-control">'
                                            + '<h3>'
                                                + data.no_account_selected
                                            + '</h3>'
                                            + '<p>'
                                                + data.please_select_ad_account
                                            + '</p>'  
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                            + '</div>';
                    
            // Show Overview
            $('.main #overview').html( overview );  
            
        }        

    };
    
    /*
     * Display Ad Account Campaigns tab
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.load_ad_account_campaigns = function ( status, data ) {

        var campaigns = '<div class="row">'
                            + '<div class="col-xl-12">'
                                + '<div class="table-responsive">'
                                    + '<table class="table">'
                                        + '<thead>'
                                            + '<tr>'
                                                + '<th scope="row" colspan="3">'
                                                    + '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#ads-create-campaign">'
                                                        + '<i class="icon-basket-loaded"></i>'
                                                            + data.words.new_campaign
                                                        + '</button>'
                                                    + '<button type="button" class="btn btn-dark ads-delete-campaigns">'
                                                        + '<i class="icon-trash"></i>'
                                                        + data.words.delete
                                                    + '</button>'
                                                + '</th>'
                                                + '<th scope="row" colspan="3">'
                                                    + '<button type="button" class="btn btn-dark pull-right btn-ads-reports">'
                                                        + '<i class="icon-pie-chart"></i>'
                                                        + data.words.reports
                                                    + '</button>'
                                                + '</th>'
                                            + '</tr>'                                               
                                            + '<tr>'
                                                + '<th scope="row">'
                                                    + '<div class="checkbox-option-select">'
                                                        + '<input id="ads-campaigns-all" name="ads-campaigns-all" type="checkbox">'
                                                        + '<label for="ads-campaigns-all"></label>'
                                                    + '</div>'
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + data.words.name
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + data.words.status
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + words.campaign_objective
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + words.impressions
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + words.spent
                                                + '</th>'
                                            + '</tr>'
                                        + '</thead>'
                                        + '<tbody>'
                                        + '</tbody>'
                                        + '<tfoot>'
                                            + '<tr>'
                                                + '<td colspan="8" class="text-right">'
                                                    + '<button type="button" class="btn btn-dark btn-previous btn-campaign-pagination btn-disabled">'
                                                        + '<i class="far fa-arrow-alt-circle-left"></i>'
                                                        + data.words.previous
                                                    + '</button>'
                                                    + '<button type="button" class="btn btn-dark btn-next btn-campaign-pagination btn-disabled">'
                                                        + data.words.next
                                                        + '<i class="far fa-arrow-alt-circle-right"></i>'
                                                    + '</button>'
                                                + '</td>'
                                            + '</tr>'
                                        + '</tfoot>'
                                    + '</table>'
                                + '</div>'                                 
                            + '</div>'
                        + '</div>';
                
        $('.main #campaigns').html(campaigns);
        $('.main #campaigns').removeClass('no-account-result'); 
        
        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var campaigns = data.campaigns;
            
            var all_campaigns = '';
            
            for ( var e = 0; e < campaigns.length; e++ ) {
                
                var impressions = 0;
                
                if (typeof campaigns[e].insights !== 'undefined') {
                    
                    if (typeof campaigns[e].insights.data[0] !== 'undefined') {
                        
                        if (typeof campaigns[e].insights.data[0].impressions !== 'undefined') {
                            
                            impressions = campaigns[e].insights.data[0].impressions;

                        }                        
                        
                    }
                    
                }
                
                var spend = 0;
                
                if (typeof campaigns[e].insights !== 'undefined') {
                    
                    if (typeof campaigns[e].insights.data[0] !== 'undefined') {
                        
                        if (typeof campaigns[e].insights.data[0].spend !== 'undefined') {
                            
                            spend = campaigns[e].insights.data[0].spend;

                        }                        
                        
                    }
                    
                }  
                
                if ( typeof Main.account_currency !== 'undefined' ) {

                    spend = spend + ' ' + Main.account_currency;

                }
                
                var objective = campaigns[e].objective;
                
                switch ( objective ) {
                    
                    case 'APP_INSTALLS':
                        
                        objective = words.app_installs;
                        
                        break;
                    
                    case 'BRAND_AWARENESS':
                        
                        objective = words.brand_awareness;
                        
                        break;
                        
                    case 'CONVERSIONS':
                        
                        objective = words.conversions;
                        
                        break;
                        
                    case 'EVENT_RESPONSES':
                        
                        objective = words.event_responses;
                        
                        break;
                        
                    case 'LEAD_GENERATION':
                        
                        objective = words.lead_generation;
                        
                        break;
                        
                    case 'LINK_CLICKS':
                        
                        objective = words.link_clicks;
                        
                        break;
                        
                    case 'LOCAL_AWARENESS':
                        
                        objective = words.local_awareness;
                        
                        break;
                        
                    case 'LOCAL_AWARENESS':
                        
                        objective = words.local_awareness;
                        
                        break;
                        
                    case 'MESSAGES':
                        
                        objective = words.messages;
                        
                        break;
                    
                    case 'OFFER_CLAIMS':
                        
                        objective = words.offer_claims;
                        
                        break;
                        
                    case 'PAGE_LIKES':
                        
                        objective = words.page_likes;
                        
                        break;  
                    
                    case 'POST_ENGAGEMENT':
                        
                        objective = words.post_engagement;
                        
                        break;
                    
                    case 'PRODUCT_CATALOG_SALES':
                        
                        objective = words.product_catalog_sales;
                        
                        break;
                        
                    case 'REACH':
                        
                        objective = words.reach;
                        
                        break;
                        
                    case 'VIDEO_VIEWS':
                        
                        objective = words.video_views;
                        
                        break;                        
                    
                }
                
                all_campaigns += '<tr>'
                                    + '<th scope="row">'
                                        + '<div class="checkbox-option-select">'
                                            + '<input id="ads-campaigns-' + campaigns[e].id + '" name="ads-campaigns-' + campaigns[e].id + '" type="checkbox" data-id="' + campaigns[e].id + '">'
                                            + '<label for="ads-campaigns-' + campaigns[e].id + '"></label>'
                                        + '</div>'
                                    + '</th>'
                                    + '<td>'
                                        + campaigns[e].name
                                    + '</td>'
                                    + '<td>'
                                        + campaigns[e].status
                                    + '</td>'
                                    + '<td>'
                                        + objective
                                    + '</td>'
                                    + '<td>'
                                        + impressions
                                    + '</td>'
                                    + '<td>'
                                        + spend
                                    + '</td>'
                                + '</tr>';
                
            }
            
            $('.main #campaigns tbody').html(all_campaigns);
            
            if ( data.previous ) {
                
                $('.main #campaigns').find('.btn-previous').attr('data-url', data.previous);
                $('.main #campaigns').find('.btn-previous').removeClass('btn-disabled');
                
            } else {
                
                $('.main #campaigns').find('.btn-previous').addClass('btn-disabled');
                
            }
            
            if ( data.next ) {
                
                $('.main #campaigns').find('.btn-next').attr('data-url', data.next);
                $('.main #campaigns').find('.btn-next').removeClass('btn-disabled');                
                
            } else {
                
                $('.main #campaigns').find('.btn-next').addClass('btn-disabled');  
                
            }
            
        } else {
            
            var all_campaigns = '<tr>'
                            + '<td colspan="6" class="p-3">'
                                + words.no_campaigns_found
                            + '</td>'
                        + '</tr>';
            
            $('.main #campaigns tbody').html(all_campaigns);
            
            $('.main #campaigns .btn-previous').addClass('btn-disabled');
            $('.main #campaigns .btn-next').addClass('btn-disabled'); 
            
            $('.main #ads-campaigns-all').prop( 'checked', false ); 
            
        }        

    };
    
    /*
     * Display Ad Account adsets tab
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.load_ad_account_adsets = function ( status, data ) {

        var adsets = '<div class="row">'
                            + '<div class="col-xl-12">'
                                + '<div class="table-responsive">'
                                    + '<table class="table">'
                                        + '<thead>'
                                            + '<tr>'
                                                + '<th scope="row" colspan="3">'
                                                    + '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#ads-create-ad-set">'
                                                        + '<i class="icon-puzzle"></i>'
                                                            + data.words.new_ad_set
                                                        + '</button>'
                                                    + '<button type="button" class="btn btn-dark ads-delete-adsets">'
                                                        + '<i class="icon-trash"></i>'
                                                        + data.words.delete
                                                    + '</button>'
                                                + '</th>'
                                                + '<th scope="row" colspan="3">'
                                                    + '<button type="button" class="btn btn-dark pull-right btn-load-ad-sets-insights" data-toggle="modal" data-target="#ads-ad-sets-insights">'
                                                        + '<i class="icon-graph"></i>'
                                                        + words.insights
                                                    + '</button>'
                                                + '</th>'
                                            + '</tr>'                                               
                                            + '<tr>'
                                                + '<th scope="row">'
                                                    + '<div class="checkbox-option-select">'
                                                        + '<input id="ads-adsets-all" name="ads-adsets-all" type="checkbox">'
                                                        + '<label for="ads-adsets-all"></label>'
                                                    + '</div>'
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + data.words.name
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + data.words.status
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + words.campaign
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + words.impressions
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + words.spent
                                                + '</th>'
                                            + '</tr>'
                                        + '</thead>'
                                        + '<tbody>'
                                        + '</tbody>'
                                        + '<tfoot>'
                                            + '<tr>'
                                                + '<td colspan="8" class="text-right">'
                                                    + '<button type="button" class="btn btn-dark btn-previous btn-adsets-pagination btn-disabled">'
                                                        + '<i class="far fa-arrow-alt-circle-left"></i>'
                                                        + data.words.previous
                                                    + '</button>'
                                                    + '<button type="button" class="btn btn-dark btn-next btn-adsets-pagination btn-disabled">'
                                                        + data.words.next
                                                        + '<i class="far fa-arrow-alt-circle-right"></i>'
                                                    + '</button>'
                                                + '</td>'
                                            + '</tr>'
                                        + '</tfoot>'
                                    + '</table>'
                                + '</div>'                                 
                            + '</div>'
                        + '</div>';
                
        $('.main #ad-sets').html(adsets);
        $('.main #ad-sets').removeClass('no-account-result'); 
        
        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var adsets = data.adsets;
            
            var all_adsets = '';
            
            for ( var e = 0; e < adsets.length; e++ ) {
                
                var impressions = 0;
                
                if (typeof adsets[e].insights !== 'undefined') {
                    
                    if (typeof adsets[e].insights.data[0] !== 'undefined') {
                        
                        if (typeof adsets[e].insights.data[0].impressions !== 'undefined') {
                            
                            impressions = adsets[e].insights.data[0].impressions;

                        }                        
                        
                    }
                    
                }
                
                var spend = 0;
                
                if (typeof adsets[e].insights !== 'undefined') {
                    
                    if (typeof adsets[e].insights.data[0] !== 'undefined') {
                        
                        if (typeof adsets[e].insights.data[0].spend !== 'undefined') {
                            
                            spend = adsets[e].insights.data[0].spend;

                        }                        
                        
                    }
                    
                } 
                
                if ( typeof Main.account_currency !== 'undefined' ) {

                    spend = spend + ' ' + Main.account_currency;

                }
                
                all_adsets += '<tr>'
                                    + '<th scope="row">'
                                        + '<div class="checkbox-option-select">'
                                            + '<input id="ads-adsets-' + adsets[e].id + '" name="ads-adsets-' + adsets[e].id + '" type="checkbox" data-id="' + adsets[e].id + '">'
                                            + '<label for="ads-adsets-' + adsets[e].id + '"></label>'
                                        + '</div>'
                                    + '</th>'
                                    + '<td>'
                                        + adsets[e].name
                                    + '</td>'
                                    + '<td>'
                                        + adsets[e].status
                                    + '</td>'
                                    + '<td>'
                                        + adsets[e].campaign.name
                                    + '</td>'                            
                                    + '<td>'
                                        + impressions
                                    + '</td>'
                                    + '<td>'
                                        + spend
                                    + '</td>'
                                + '</tr>';
                
            }
            
            $('.main #ad-sets tbody').html(all_adsets);
            
            if ( data.previous ) {
                
                $('.main #ad-sets').find('.btn-previous').attr('data-url', data.previous);
                $('.main #ad-sets').find('.btn-previous').removeClass('btn-disabled');
                
            } else {
                
                $('.main #ad-sets').find('.btn-previous').addClass('btn-disabled');
                
            }
            
            if ( data.next ) {
                
                $('.main #ad-sets').find('.btn-next').attr('data-url', data.next);
                $('.main #ad-sets').find('.btn-next').removeClass('btn-disabled');                
                
            } else {
                
                $('.main #ad-sets').find('.btn-next').addClass('btn-disabled');  
                
            }
            
        } else {
            
            var data = '<tr>'
                            + '<td colspan="6" class="p-3">'
                                + words.no_adsets_found
                            + '</td>'
                        + '</tr>';
            
            $('.main #ad-sets tbody').html(data);
            
            $('.main #ad-sets .btn-previous').addClass('btn-disabled');
            $('.main #ad-sets .btn-next').addClass('btn-disabled');          
            
        }     

    };
    
    /*
     * Display Ad Account ads tab
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.load_ad_account_ads = function ( status, data ) {

        // Status button text
        var status_text = words.status;

        // Status order
        var status_order = 0;

        // Verify if user has selected a status
        if ( typeof data.status !== 'undefined' ) {

            // Set status order
            status_order = data.status;

            // Set status button text
            switch ( data.status ) {

                case '1':

                    status_text = 'ACTIVE';

                    break;

                case '2':

                    status_text = 'PAUSED';

                    break;     
                    
                case '3':

                    status_text = 'DELETED';

                    break;
                    
                case '4':

                    status_text = 'ARCHIVED';

                    break;  

            }

        }
        
        // Create the ads list
        var ads = '<div class="row">'
                            + '<div class="col-xl-12">'
                                + '<div class="table-responsive">'
                                    + '<table class="table">'
                                        + '<thead>'
                                            + '<tr>'
                                                + '<th scope="row" colspan="3">'
                                                    + '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#ads-create-new-ad">'
                                                        + '<i class="icon-puzzle"></i>'
                                                            + data.words.new_ad
                                                        + '</button>'
                                                    + '<button type="button" class="btn btn-dark ads-delete-ad">'
                                                        + '<i class="icon-trash"></i>'
                                                        + data.words.delete
                                                    + '</button>'
                                                + '</th>'
                                                + '<th scope="row" colspan="3">'
                                                    + '<button type="button" class="btn btn-dark pull-right btn-load-ad-insights" data-toggle="modal" data-target="#ads-ad-sets-insights">'
                                                        + '<i class="icon-graph"></i>'
                                                        + words.insights
                                                    + '</button>'
                                                + '</th>'
                                            + '</tr>'                                               
                                            + '<tr>'
                                                + '<th scope="row">'
                                                    + '<div class="checkbox-option-select">'
                                                        + '<input id="ads-ad-all" name="ads-ad-alll" type="checkbox">'
                                                        + '<label for="ads-ad-all"></label>'
                                                    + '</div>'
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + data.words.name
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + '<div class="dropdown">'
                                                        + '<button class="btn btn-secondary dropdown-toggle ads-status-filter-btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-order="' + status_order + '">'
                                                            + status_text
                                                        + '</button>'
                                                        + '<div class="dropdown-menu ads-status-filter-list" aria-labelledby="dropdownMenuButton" x-placement="bottom-start">'
                                                            + '<a class="dropdown-item" href="#" data-type="1">'
                                                                + 'ACTIVE'
                                                            + '</a>'
                                                            + '<a class="dropdown-item" href="#" data-type="2">'
                                                                + 'PAUSED'
                                                            + '</a>'
                                                            + '<a class="dropdown-item" href="#" data-type="3">'
                                                                + 'DELETED'
                                                            + '</a>'
                                                            + '<a class="dropdown-item" href="#" data-type="4">'
                                                                + 'ARCHIVED'
                                                            + '</a>'                                                                                                                                                                                                                                                                         
                                                        + '</div>'
                                                    + '</div>'
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + words.ad_set
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + words.impressions
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + words.spent
                                                + '</th>'
                                            + '</tr>'
                                        + '</thead>'
                                        + '<tbody>'
                                        + '</tbody>'
                                        + '<tfoot>'
                                            + '<tr>'
                                                + '<td colspan="6" class="text-right">'
                                                    + '<button type="button" class="btn btn-dark btn-previous btn-ad-pagination btn-disabled">'
                                                        + '<i class="far fa-arrow-alt-circle-left"></i>'
                                                        + data.words.previous
                                                    + '</button>'
                                                    + '<button type="button" class="btn btn-dark btn-next btn-ad-pagination btn-disabled">'
                                                        + data.words.next
                                                        + '<i class="far fa-arrow-alt-circle-right"></i>'
                                                    + '</button>'
                                                + '</td>'
                                            + '</tr>'
                                        + '</tfoot>'
                                    + '</table>'
                                + '</div>'                                 
                            + '</div>'
                        + '</div>';
                
        $('.main #ads').html(ads);
        $('.main #ads').removeClass('no-account-result'); 
        
        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var ads = data.ads;
            
            var all_ads = '';
            
            for ( var e = 0; e < ads.length; e++ ) {
                
                var impressions = 0;
                
                if (typeof ads[e].insights !== 'undefined') {
                    
                    if (typeof ads[e].insights.data[0] !== 'undefined') {
                        
                        if (typeof ads[e].insights.data[0].impressions !== 'undefined') {
                            
                            impressions = ads[e].insights.data[0].impressions;

                        }                        
                        
                    }
                    
                }
                
                var spend = 0;
                
                if (typeof ads[e].insights !== 'undefined') {
                    
                    if (typeof ads[e].insights.data[0] !== 'undefined') {
                        
                        if (typeof ads[e].insights.data[0].spend !== 'undefined') {
                            
                            spend = ads[e].insights.data[0].spend;

                        }                        
                        
                    }
                    
                } 
                
                if ( typeof Main.account_currency !== 'undefined' ) {

                    spend = spend + ' ' + Main.account_currency;

                }
                
                all_ads += '<tr>'
                                + '<th scope="row">'
                                    + '<div class="checkbox-option-select">'
                                        + '<input id="ads-ad-' + ads[e].id + '" name="ads-ad-' + ads[e].id + '" type="checkbox" data-id="' + ads[e].id + '">'
                                        + '<label for="ads-ad-' + ads[e].id + '"></label>'
                                    + '</div>'
                                + '</th>'
                                + '<td>'
                                    + ads[e].name
                                + '</td>'
                                + '<td>'
                                    + ads[e].status
                                + '</td>'
                                + '<td>'
                                    + ads[e].adset.name
                                + '</td>'                            
                                + '<td>'
                                    + impressions
                                + '</td>'
                                + '<td>'
                                    + spend
                                + '</td>'
                            + '</tr>';
                
            }
            
            $('.main #ads tbody').html(all_ads);
            
            if ( data.previous ) {
                
                $('.main #ads').find('.btn-previous').attr('data-url', data.previous);
                $('.main #ads').find('.btn-previous').removeClass('btn-disabled');
                
            } else {
                
                $('.main #ads').find('.btn-previous').addClass('btn-disabled');
                
            }
            
            if ( data.next ) {
                
                $('.main #ads').find('.btn-next').attr('data-url', data.next);
                $('.main #ads').find('.btn-next').removeClass('btn-disabled');                
                
            } else {
                
                $('.main #ads').find('.btn-next').addClass('btn-disabled');  
                
            }
            
        } else {        
            
            var data = '<tr>'
                            + '<td colspan="6" class="p-3">'
                                + words.no_ads_found
                            + '</td>'
                        + '</tr>';
            
            $('.main #ads tbody').html(data);
            
            $('.main #ads .btn-previous').addClass('btn-disabled');
            $('.main #ads .btn-next').addClass('btn-disabled'); 
            
        }     

    };
    
    /*
     * Display Pixel Conversions tab
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.load_ad_account_pixel_conversions = function ( status, data ) {

        var adsets = '<div class="row">'
                            + '<div class="col-xl-12">'
                                + '<div class="table-responsive">'
                                    + '<table class="table">'
                                        + '<thead>'
                                            + '<tr>'
                                                + '<th scope="row">'
                                                    + '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#pixel-new-coversion">'
                                                        + '<i class="fas fa-chart-line"></i>'
                                                        + data.words.new_conversion
                                                    + '</button>'
                                                + '</th>'
                                                + '<th scope="row">'
                                                + '</th>'
                                                + '<th scope="row">'
                                                + '</th>'
                                            + '</tr>'                                               
                                            + '<tr>'
                                                + '<th scope="col">'
                                                    + data.words.name
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + data.words.type
                                                + '</th>'
                                                + '<th scope="col">'
                                                    + data.words.url
                                                + '</th>'
                                            + '</tr>'
                                        + '</thead>'
                                        + '<tbody>'
                                        + '</tbody>'
                                        + '<tfoot>'
                                            + '<tr>'
                                                + '<td colspan="8" class="text-right">'
                                                    + '<button type="button" class="btn btn-dark btn-previous btn-conversions-pagination btn-disabled">'
                                                        + '<i class="far fa-arrow-alt-circle-left"></i>'
                                                        + data.words.previous
                                                    + '</button>'
                                                    + '<button type="button" class="btn btn-dark btn-next btn-conversions-pagination btn-disabled">'
                                                        + data.words.next
                                                        + '<i class="far fa-arrow-alt-circle-right"></i>'
                                                    + '</button>'
                                                + '</td>'
                                            + '</tr>'
                                        + '</tfoot>'
                                    + '</table>'
                                + '</div>'                                 
                            + '</div>'
                        + '</div>';
                
        $('.main #pixel-conversion').html(adsets);
        $('.main #pixel-conversion').removeClass('no-account-result'); 
        
        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var conversions = data.conversions;
            
            var all_conversions = '';
            
            for ( var e = 0; e < conversions.length; e++ ) {
                
                var custom_event_type = '';
                
                switch ( conversions[e].custom_event_type ) {
                    
                    case 'CONTENT_VIEW':
                        
                        custom_event_type = words.view_content;
                        
                        break;
                        
                    case 'SEARCH':
                        
                        custom_event_type = words.search;
                        
                        break; 
                    
                    case 'ADD_TO_CART':
                        
                        custom_event_type = words.add_to_cart;
                        
                        break; 
                        
                    case 'ADD_TO_WISHLIST':
                        
                        custom_event_type = words.add_to_wishlist;
                        
                        break;                     
                    
                    case 'INITIATED_CHECKOUT':
                        
                        custom_event_type = words.initiate_checkout;
                        
                        break;
                    
                    case 'ADD_PAYMENT_INFO':
                        
                        custom_event_type = words.add_payment_info;
                        
                        break;
                        
                    case 'PURCHASE':
                        
                        custom_event_type = words.purchase;
                        
                        break;
                    
                    case 'LEAD':
                        
                        custom_event_type = words.lead;
                        
                        break;
                    
                    case 'COMPLETE_REGISTRATION':
                        
                        custom_event_type = words.complete_registration;
                        
                        break;                    
                    
                }
                
                var rule = '';
                
                if ( typeof conversions[e].rule !== 'undefined' ) {
                    
                    var parse = JSON.parse(conversions[e].rule);
                    
                    if ( typeof parse.url !== 'undefined' ) {
                        
                        if ( typeof parse.url.i_contains !== 'undefined' ) {
                            
                            rule = parse.url.i_contains;

                        }
                        
                    }
                    
                }
                
                all_conversions += '<tr>'
                                    + '<td>'
                                        + conversions[e].name
                                    + '</td>'
                                    + '<td>'
                                        + custom_event_type
                                    + '</td>'
                                    + '<td>'
                                        + rule
                                    + '</td>'
                                + '</tr>';
                
            }
            
            $('.main #pixel-conversion tbody').html(all_conversions);
            
            if ( data.previous ) {
                
                $('.main #pixel-conversion .btn-previous').attr('data-url', data.previous);
                $('.main #pixel-conversion .btn-previous').removeClass('btn-disabled');
                
            } else {
                
                $('.main #pixel-conversion .btn-previous').addClass('btn-disabled');
                
            }
            
            if ( data.next ) {
                
                $('.main #pixel-conversion .btn-next').attr('data-url', data.next);
                $('.main #pixel-conversion .btn-next').removeClass('btn-disabled');                
                
            } else {
                
                $('.main #pixel-conversion .btn-next').addClass('btn-disabled');  
                
            }
            
        } else {
            
            var data = '<tr>'
                            + '<td colspan="6" class="p-3">'
                                + words.no_conversion_tracking_found
                            + '</td>'
                        + '</tr>';
            
            $('.main #pixel-conversion tbody').html(data);
            
            $('.main #pixel-conversion .btn-previous').addClass('btn-disabled');
            $('.main #pixel-conversion .btn-next').addClass('btn-disabled'); 
            
        }     

    };   
    
    /*
     * Display Campaign list in the opened modal
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.load_select_ad_campaigns = function ( status, data ) {       

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
            
            if ( $('.main #ads-create-ad-set').hasClass('show') ) {
                
                $('.main #ads-create-ad-set .ad-creation-filter-fb-campaigns-list').html(all_campaigns);
                
            } else if ( $('.main #ads-create-new-ad').hasClass('show') ) {
                
                $('.main #ads-create-new-ad .ad-creation-filter-fb-campaigns-list').html(all_campaigns);
                
            } else if ( $('.main #ads-campaigns-insights').hasClass('show') ) {
                
                $('.main #ads-campaigns-insights .ad-insights-fb-campaigns-list').html(all_campaigns);
                
            }
            
        } else {
            
            var message = '<li class="list-group-item no-results">'
                               + data.message
                           + '</li>';
            
            if ( $('.main #ads-create-ad-set').hasClass('show') ) {
                
                $('.main #ads-create-ad-set .ad-creation-filter-fb-campaigns-list').html(message);
                
            } else if ( $('.main #ads-create-new-ad').hasClass('show') ) {
                
                $('.main #ads-create-new-ad .ad-creation-filter-fb-campaigns-list').html(message);
                
            } else if ( $('.main #ads-campaigns-insights').hasClass('show') ) {
                
                $('.main #ads-campaigns-insights .ad-insights-fb-campaigns-list').html(message);
                
            }
            
        }

    };
    
    /*
     * Display Ad Sets list in the opened modal
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.load_select_ad_sets = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var all_adsets = '';
            
            for ( var c = 0; c < data.ad_sets.data.length; c++ ) {
                
                all_adsets += '<li class="list-group-item">'
                                   + '<a href="#" data-id="' + data.ad_sets.data[c].id + '">'
                                       + data.ad_sets.data[c].name
                                   + '</a>'
                               + '</li>';
                
            }
            
            if ( $('.main #ads-create-new-ad').hasClass('show') ) {
                
                $('.main #ads-create-new-ad .ad-creation-filter-fb-adsets-list').html(all_adsets);
                
            }
            
        } else {
            
            var message = '<li class="list-group-item no-results">'
                               + data.message
                           + '</li>';
            
            if ( $('.main #ads-create-new-ad').hasClass('show') ) {
                
                $('.main #ads-create-new-ad .ad-creation-filter-fb-adsets-list').html(message);
                
            }
            
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
    Main.methods.load_select_all_ad_sets = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var all_adsets = '';
            
            for ( var c = 0; c < data.ad_sets.data.length; c++ ) {
                
                all_adsets += '<li class="list-group-item">'
                                   + '<a href="#" data-id="' + data.ad_sets.data[c].id + '">'
                                       + data.ad_sets.data[c].name
                                   + '</a>'
                               + '</li>';
                
            }
            
            $('.main #ads-ad-sets-insights .ads-campaign-insights-by-ad-sets-list').html(all_adsets);
            
        } else {
            
            var message = '<li class="list-group-item no-results">'
                               + data.message
                           + '</li>';
            
            $('.main #ads-ad-sets-insights .ads-campaign-insights-by-ad-sets-list').html(message);
            
        }

    };
    
    /*
     * Display Ads list in the opened modal
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
    */
    Main.methods.load_select_ads = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var all_ads = '';
            
            for ( var c = 0; c < data.ad_ads.data.length; c++ ) {
                
                all_ads += '<li class="list-group-item">'
                                   + '<a href="#" data-id="' + data.ad_ads.data[c].id + '">'
                                       + data.ad_ads.data[c].name
                                   + '</a>'
                               + '</li>';
                
            }
            
            $('.main #ads-ad-insights .ads-campaign-insights-by-ad-list').html(all_ads);
            
        } else {
            
            var message = '<li class="list-group-item no-results">'
                               + data.message
                           + '</li>';
            
            $('.main #ads-ad-insights .ads-campaign-insights-by-ad-list').html(message);
            
        }

    };
    
    /*
     * Display selected campaign in the opened modal
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.select_facebook_campaign = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {

            if ( $('.main #ads-create-ad-set').hasClass('show') ) {
                
                $('.main #ads-create-ad-set .ads-selected-ad-campaign').text(data.campaign.name);
                $('.main #ads-create-ad-set .ads-selected-ad-campaign').attr('data-id', data.campaign.id);
                $('.main #ads-create-ad-set .ads-selected-ad-campaign').attr('data-objective', data.campaign.objective);

                if ( data.campaign.objective === 'LINK_CLICKS' ) {
            
                    $('.main #ads-create-ad-set #myTabContent6 > .tab-pane#campaign-create-ads-post-engagement').removeClass('show active');
                    $('.main #ads-create-ad-set #myTabContent6 > .tab-pane#campaign-create-ads-links').addClass('show active');
                    
                } else if ( data.campaign.objective === 'POST_ENGAGEMENT' ) {
                    
                    $('.main #ads-create-ad-set #myTabContent6 > .tab-pane#campaign-create-ads-links').removeClass('show active');
                    $('.main #ads-create-ad-set #myTabContent6 > .tab-pane#campaign-create-ads-post-engagement').addClass('show active');
                    
                }

                // Load Posts
                Main.load_posts_for_boosting();
                
            } else if ( $('.main #ads-create-new-ad').hasClass('show') ) {
                
                if ( data.ad_sets.data.length ) {
                    
                    var ad_sets = '';
                    
                    for ( var e = 0; e < data.ad_sets.data.length; e++ ) {
                        
                        ad_sets += '<li class="list-group-item">'
                                       + '<a href="#" data-id="' + data.ad_sets.data[e].id + '">'
                                           + data.ad_sets.data[e].name
                                       + '</a>'
                                   + '</li>';
                        
                    }
                    
                    $('.main #ads-create-new-ad .ad-creation-filter-fb-adsets-list').html(ad_sets);
                    $('.main #ads-create-new-ad .ads-selected-ad-campaign').text(data.campaign.name);
                    $('.main #ads-create-new-ad .ads-selected-ad-campaign').attr('data-id', data.campaign.id);
                    $('.main #ads-create-new-ad .ads-selected-ad-campaign').attr('data-objective', data.campaign.objective);

                    if ( data.campaign.objective === 'LINK_CLICKS' ) {
                
                        $('.main #ads-create-new-ad #myTabContent7 > .tab-pane#campaign-create-ads-post-engagement').removeClass('show active');
                        $('.main #ads-create-new-ad #myTabContent7 > .tab-pane#campaign-create-ads-links').addClass('show active');
                        
                    } else if ( data.campaign.objective === 'POST_ENGAGEMENT' ) {
                        
                        $('.main #ads-create-new-ad #myTabContent7 > .tab-pane#campaign-create-ads-links').removeClass('show active');
                        $('.main #ads-create-new-ad #myTabContent7 > .tab-pane#campaign-create-ads-post-engagement').addClass('show active');
                        
                    }
                    
                    // Load posts for boosting
                    Main.load_posts_for_boosting();
                    
                } else {
                    
                    // Display alert
                    Main.popup_fon('sube', words.selected_campaign_not_has_ad_sets, 1500, 2000);                    
                
                }
                
            }
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
        // Display All Ad Sets
        $( '.main #ads-create-new-ad .ads-selected-ad-set' ).text(words.ad_sets);
        $( '.main #ads-create-new-ad .ads-selected-ad-set' ).removeAttr('data-id');
        $( '.main #ads-create-new-ad .ad-creation-filter-fb-adsets' ).val('');

    };
    
    /*
     * Download Insights for Ad Accounts
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.insights_download_for_account = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'false' || status === 'error' ) {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        } else {
            
            // Get type
            var type = $('.main .insights-filter-btn').attr('data-type');
            
            // Download
            document.location.href = url + 'user/app-ajax/facebook-ads?action=insights_download_for_account&type=' + type + '&download=1';
            
        }

    };
    
    /*
     * Download Insights for Ad Campaigns
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.insights_download_for_campaigns = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'false' || status === 'error' ) {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        } else {
            
            // Get time
            var time = $('.main .ads-campaign-insights-by-time').attr('data-time');
            
            // Get campaign's id
            var campaign_id = $('.main .ads-campaign-insights-by-campaign').attr('data-id');  
            
            // Download
            document.location.href = url + 'user/app-ajax/facebook-ads?action=insights_download_for_campaigns&order=' + time + '&campaign_id=' + campaign_id + '&download=1';
            
        }

    };
    
    /*
     * Download Insights for Ad Sets
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.insights_download_for_ad_sets = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'false' || status === 'error' ) {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        } else {
            
            // Get time order
            var time = $('.main .ads-ad-sets-insights-by-time').attr('data-time');

            // Get ad set's id
            var ad_set_id = $('.main .ads-campaign-insights-by-ad-sets').attr('data-id');
            
            // Download
            document.location.href = url + 'user/app-ajax/facebook-ads?action=insights_download_for_ad_sets&order=' + time + '&ad_set_id=' + ad_set_id + '&download=1';
            
        }

    };
    
    /*
     * Download Insights for Ads
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.insights_download_for_ad = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'false' || status === 'error' ) {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        } else {
            
            // Get time order
            var time = $('.main .ads-ad-insights-by-time').attr('data-time');

            // Get ad's id
            var ad_id = $('.main .ads-campaign-insights-by-ad').attr('data-id');  
            
            // Download
            document.location.href = url + 'user/app-ajax/facebook-ads?action=insights_download_for_ad&order=' + time + '&ad_id=' + ad_id + '&download=1';
            
        }

    };
    
    /*
     * Get Insights for Ad Campaigns
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.ad_campaigns_insights_by_time = function ( status, data ) {

       // Verify if the success response exists
        if ( status === 'success' ) {
            
            var insights = '';
            
            for ( var e = 0; e < data.campaign_insights.length; e++ ) {

                var cpc = 0;

                if ( typeof data.campaign_insights[e].cpc !== 'undefined' ) {
                    cpc = data.campaign_insights[e].cpc;
                }
                
                insights += '<tr>'
                               + '<td>'
                                   + data.campaign_insights[e].date_start
                               + '</td>'
                               + '<td>'
                                   + data.campaign_insights[e].impressions
                               + '</td>'
                               + '<td>'
                                   + data.campaign_insights[e].reach
                               + '</td>'
                               + '<td>'
                                   + data.campaign_insights[e].unique_clicks
                               + '</td>'
                               + '<td>'
                                   + data.campaign_insights[e].cpm
                               + '</td>'
                               + '<td>'
                                   + cpc
                               + '</td>'
                               + '<td>'
                                   + data.campaign_insights[e].ctr
                               + '</td>'
                               + '<td>'
                                   + data.campaign_insights[e].account_currency + ' ' + data.campaign_insights[e].spend
                               + '</td>'
                           + '</tr>';
                
            }
            
            $('.main #ads-campaigns-insights tbody').html(insights);
            
        } else {
            
            var message = '<tr>'
                           + '<td colspan="8">'
                               + words.no_insights_found
                           + '</td>'
                       + '</tr>';
            
            $('.main #ads-campaigns-insights tbody').html(message);
            
        }

    };
    
    /*
     * Get Insights for Ad Sets
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.ad_sets_insights_by_time = function ( status, data ) {

       // Verify if the success response exists
        if ( status === 'success' ) {
            
            var insights = '';
            
            for ( var e = 0; e < data.ad_set_insights.length; e++ ) {

                var cpc = 0;

                if ( typeof data.ad_set_insights[e].cpc !== 'undefined' ) {
                    cpc = data.ad_set_insights[e].cpc;
                }
                
                insights += '<tr>'
                               + '<td>'
                                   + data.ad_set_insights[e].date_start
                               + '</td>'
                               + '<td>'
                                   + data.ad_set_insights[e].impressions
                               + '</td>'
                               + '<td>'
                                   + data.ad_set_insights[e].reach
                               + '</td>'
                               + '<td>'
                                   + data.ad_set_insights[e].unique_clicks
                               + '</td>'
                               + '<td>'
                                   + data.ad_set_insights[e].cpm
                               + '</td>'
                               + '<td>'
                                   + cpc
                               + '</td>'
                               + '<td>'
                                   + data.ad_set_insights[e].ctr
                               + '</td>'
                               + '<td>'
                                   + data.ad_set_insights[e].account_currency + ' ' + data.ad_set_insights[e].spend
                               + '</td>'
                           + '</tr>';
                
            }
            
            $('.main #ads-ad-sets-insights tbody').html(insights);
            
        } else {
            
            var message = '<tr>'
                           + '<td colspan="8">'
                               + words.no_insights_found
                           + '</td>'
                       + '</tr>';
            
            $('.main #ads-ad-sets-insights tbody').html(message);
            
        }

    };
    
    /*
     * Get Insights for Ads
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.ad_insights_by_time = function ( status, data ) {

       // Verify if the success response exists
        if ( status === 'success' ) {
            
            var insights = '';
            
            for ( var e = 0; e < data.ad_insights.length; e++ ) {

                var cpc = 0;

                if ( typeof data.ad_insights[e].cpc !== 'undefined' ) {
                    cpc = data.ad_insights[e].cpc;
                }
                
                insights += '<tr>'
                               + '<td>'
                                   + data.ad_insights[e].date_start
                               + '</td>'
                               + '<td>'
                                   + data.ad_insights[e].impressions
                               + '</td>'
                               + '<td>'
                                   + data.ad_insights[e].reach
                               + '</td>'
                               + '<td>'
                                   + data.ad_insights[e].unique_clicks
                               + '</td>'
                               + '<td>'
                                   + data.ad_insights[e].cpm
                               + '</td>'
                               + '<td>'
                                   + cpc
                               + '</td>'
                               + '<td>'
                                   + data.ad_insights[e].ctr
                               + '</td>'
                               + '<td>'
                                   + data.ad_insights[e].account_currency + ' ' + data.ad_insights[e].spend
                               + '</td>'
                           + '</tr>';
                
            }
            
            $('.main #ads-ad-insights tbody').html(insights);
            
        } else {
            
            var message = '<tr>'
                           + '<td colspan="8">'
                               + words.no_insights_found
                           + '</td>'
                       + '</tr>';
            
            $('.main #ads-ad-insights tbody').html(message);
            
        }

    };
    
    /*
     * Display posts for boosting
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.load_posts_for_boosting = function ( status, data ) {

       // Verify if the success response exists
        if ( status === 'success' ) {
            
            var all_posts = '';
            
            for ( var e = 0; e < data.posts.length; e++ ) {
                
                all_posts += '<tr>'
                                + '<td style="width:10%">'
                                    + '<img src="' + data.posts[e].picture + '">'
                                + '</td>'
                                + '<td>'
                                    + '<p>'
                                        + data.posts[e].message
                                    + '</p>'
                                + '</td>'
                                + '<td>'
                                    + '<button type="button" class="btn post-engagement-boost-it btn-success" data-id="' + data.posts[e].id + '">'
                                        + '<i class="fas fa-tachometer-alt"></i>'
                                        + words.boost
                                    + '</button>'
                                + '</td>'
                            + '</tr>';
                
            }
            
            // Get selected social network
            if ( $('.main .modal.show #post-engagement-from-facebook').hasClass('active') ) {

                $('.main .modal.show #post-engagement-from-facebook tbody').html(all_posts);

            } else if ( $('.main .modal.show #post-engagement-from-instagram').hasClass('active') ) {

                $('.main .modal.show #post-engagement-from-instagram tbody').html(all_posts);

            }
            
        } else {
            
            var message = '<tr>'
                            + '<td colspan="3">'
                                + '<p class="text-left">'
                                    + data.message
                                + '</p>'
                            + '</td>'
                        + '</tr>';
            
            // Get selected social network
            if ( $('.main .modal.show #post-engagement-from-facebook').hasClass('active') ) {

                $('.main .modal.show #post-engagement-from-facebook tbody').html(message);

            } else if ( $('.main .modal.show #post-engagement-from-instagram').hasClass('active') ) {

                $('.main .modal.show #post-engagement-from-instagram tbody').html(message);

            }
            
        }

    };
    
    /*
     * Display post for boosting
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.get_post_data_for_boost = function ( status, data ) {

       // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Verify if preview exists
            if ( typeof Main.post_preview !== 'undefined' ) {
                delete Main.post_preview;
            }
            
            // Define post's preview
            Main.post_preview = {};

            // Verify if image exists
            if ( data.picture ) {
                
                Main.post_preview.picture = data.picture;

            }
          
            // Verify if message exists
            if ( data.message ) {
                
                Main.post_preview.message = data.message;

            }

            // Verify if link exists
            if ( data.link ) {
                
                Main.post_preview.link = data.link;
                
                // Parse url
                Main.get_page_content_by_url(data.link);

            }

            var preview_media = '';
            var preview_url = '';
            var preview_title = '';
            var preview_text = '';

            if ( $('.main #ads-create-campaign').hasClass('show') ) {

                var modal = '.main #myTabContent5 > .tab-pane.active';

            } else if ( $('.main #ads-create-ad-set').hasClass('show') ) {

                var modal = '.main #myTabContent6 > .tab-pane.active';

            } else if ( $('.main #ads-create-new-ad').hasClass('show') ) {

                var modal = '.main #myTabContent7 > .tab-pane.active';

            }

            if ( typeof Main.preview !== 'undefined' && typeof Main.post_preview.link !== 'undefined' ) {

                if ( Main.preview.url_cover !== 'undefined' ) {

                    // Set preview's media
                    preview_media = '<img src="' + Main.preview.url_cover + '">';

                }

            }

            if ( typeof Main.post_preview.picture !== 'undefined' ) {

                // Set preview's media
                preview_media = '<img src="' + Main.post_preview.picture + '">';

            }       

            if ( typeof Main.post_preview.link !== 'undefined' && typeof Main.post_preview.link !== 'undefined' ) {

                // Set preview's url
                preview_url = Main.post_preview.link;

            }

            if ( typeof Main.preview !== 'undefined' && typeof Main.post_preview.link !== 'undefined' ) {

                if ( Main.preview.url_cover !== 'undefined' ) {

                    // Set preview's title
                    preview_title = Main.preview.title;

                }


            }    

            if ( typeof Main.post_preview.message !== 'undefined' ) {

                // Set preview's text
                preview_text = Main.post_preview.message;

            }              

            var active = '';

            if ( $(modal).find('#links-clicks-preview-fb-desktop-feed').length < 1 ) {
                active = ' show active';
            } else if ( $(modal).find('#links-clicks-preview-fb-desktop-feed').hasClass('active') ) {
                active = ' show active';
            }

            // Set default headline
            var headline = preview_title;

            // Verify if headline is not empty
            if ( $(modal + ' .headline').val() ) {
                headline = $(modal + ' .headline').val();
            }

            // Set default description
            var description = '';

            // Verify if description is not empty
            if ( $(modal + ' .description').val() ) {
                description = $(modal + ' .description').val();
            }       
            
            var learn_more = '';
            
            if ( preview_url ) {
            
                learn_more = '<tr>'
                                + '<td>'
                                    + '<p>' + preview_url + '</p>'
                                    + '<h3>' + headline + '</h3>'
                                    + '<div>' + description + '</div>'
                                + '</td>'
                                + '<td>'
                                    + '<button type="button" class="btn btn-primary">'
                                        + words.learn_more
                                    + '</button>'
                                + '</td>'                                                                                              
                            + '</tr>';
                                                            
            }

            var content_preview = '<div class="tab-content" id="links-clicks-preview">'
                                        + '<div class="tab-pane fade' + active + '" id="links-clicks-preview-fb-desktop-feed" role="tabpanel" aria-labelledby="links-clicks-preview-fb-desktop-feed-tab">'
                                            + '<table>'
                                                + '<thead>'
                                                    + '<tr>'
                                                        + '<th colspan="3">'
                                                            + '<img src="' + url + 'assets/img/avatar-placeholder.png">'
                                                            + '<h3>'
                                                                + '<a href="#">'
                                                                    + words.your_page_name
                                                                + '</a>'
                                                                + '<span>' + words.sponsored + ' - <i class="fas fa-globe-americas"></i></span>'
                                                            + '</h3>'
                                                        + '</th>'
                                                    + '</tr>'
                                                + '</thead>'
                                                + '<tbody>'
                                                    + '<tr>'
                                                        + '<td colspan="3">'
                                                            + '<p>'
                                                                + preview_text
                                                            + '</p>'
                                                        + '</td>'
                                                    + '</tr>'
                                                    + '<tr>'
                                                        + '<td colspan="3" class="clean">'
                                                            + '<table class="full">'
                                                                + '<tbody>'
                                                                    + '<tr>'
                                                                        + '<td colspan="2">'
                                                                            + preview_media
                                                                        + '</td>'
                                                                    + '</tr>'
                                                                    + learn_more
                                                                + '</tbody>'
                                                            + '</table>'
                                                        + '</td>'
                                                    + '</tr>'
                                                + '</tbody>'
                                                + '<tfoot>'
                                                    + '<tr>'
                                                        + '<td>'
                                                            + '<i class="far fa-thumbs-up"></i>'
                                                            + words.like
                                                        + '</td>'
                                                        + '<td>'
                                                            + '<i class="far fa-comment-alt"></i>'
                                                            + words.comment
                                                        + '</td>'
                                                        + '<td>'
                                                            + '<i class="fas fa-share"></i>'
                                                            + words.share
                                                        + '</td>'
                                                    + '</tr>'
                                                + '</tfoot>'
                                            + '</table>'
                                        + '</div>';

            active = '';

            if ( $(modal).find('#links-clicks-preview-instagram-feed').hasClass('active') ) {
                active = ' show active';
            }             

            content_preview += '<div class="tab-pane fade' + active + '" id="links-clicks-preview-instagram-feed" role="tabpanel" aria-labelledby="links-clicks-preview-instagram-feed-tab">'
                                    + '<table>'
                                        + '<thead>'
                                            + '<tr>'
                                                + '<th colspan="3">'
                                                    + '<img src="' + url + 'assets/img/avatar-placeholder.png">'
                                                    + '<h3>'
                                                        + '<a href="#">'
                                                            + words.your_name
                                                        + '</a>'
                                                        + '<span>'
                                                            + words.sponsored
                                                        + '</span>'
                                                        + '<i class="fas fa-ellipsis-h"></i>'
                                                    + '</h3>'
                                                + '</th>'
                                            + '</tr>'
                                        + '</thead>'
                                        + '<tbody>'
                                            + '<tr>'
                                                + '<td colspan="3" class="clean">'
                                                    + '<table class="full">'
                                                        + '<tbody>'
                                                            + '<tr>'
                                                                + '<td class="clean">'
                                                                    + preview_media
                                                                + '</td>'
                                                            + '</tr>'
                                                            + '<tr>'
                                                                + '<td>'
                                                                    + '<a href="#">'
                                                                        + words.learn_more
                                                                    + '</a>'
                                                                    + '<i class="fas fa-angle-right"></i>'
                                                                + '</td>'                                                              
                                                            + '</tr>'
                                                        + '</tbody>'
                                                    + '</table>'
                                                + '</td>'
                                            + '</tr>'
                                        + '</tbody>'
                                        + '<tfoot>'
                                            + '<tr>'
                                                + '<td colspan="2">'
                                                    + '<i class="icon-heart"></i>'
                                                    + '<i class="icon-bubble"></i>'
                                                    + '<i class="icon-paper-plane"></i>'
                                                + '</td>'
                                                + '<td class="text-right">'
                                                    + '<i class="far fa-bookmark"></i>'
                                                + '</td>'
                                            + '</tr>'
                                            + '<tr>'
                                                + '<td colspan="3">'
                                                    + '<p>' + preview_text + '</p>'
                                                + '</td>'
                                            + '</tr>'                                                                               
                                        + '</tfoot>'
                                    + '</table>'
                                + '</div>'
                            + '</div>';

            active = '';

            if ( $(modal).find('#links-clicks-preview-messenger-inbox').hasClass('active') ) {
                active = ' show active';
            }             

            content_preview += '<div class="tab-content" id="links-clicks-preview">'
                                        + '<div class="tab-pane fade' + active + '" id="links-clicks-preview-messenger-inbox" role="tabpanel" aria-labelledby="links-clicks-preview-messenger-inbox-tab">'
                                            + '<table>'
                                                + '<thead>'
                                                    + '<tr>'
                                                        + '<th>'
                                                            + '<img src="' + url + 'assets/img/avatar-placeholder.png">'
                                                        + '</th>'                                       
                                                        + '<th colspan="2">'
                                                            + '<h3>'
                                                                + '<a href="#">'
                                                                    + words.your_page_name
                                                                + '</a>'
                                                                + '<span>' + words.sponsored + '</span>'
                                                                + '<i class="fas fa-ellipsis-h pull-right"></i>'
                                                            + '</h3>'
                                                        + '</th>'
                                                    + '</tr>'
                                                + '</thead>'
                                                + '<tbody>'
                                                    + '<tr>'
                                                        + '<td>'
                                                        + '</td>'                                        
                                                        + '<td colspan="2">'
                                                            + '<p>'
                                                                + preview_text
                                                            + '</p>'
                                                        + '</td>'
                                                    + '</tr>'
                                                    + '<tr>'
                                                        + '<td>'
                                                        + '</td>'
                                                        + '<td colspan="2" class="clean">'
                                                            + '<table class="full">'
                                                                + '<tbody>'
                                                                    + '<tr>'
                                                                        + '<td colspan="2">'
                                                                            + preview_media
                                                                        + '</td>'
                                                                    + '</tr>'
                                                                    + '<tr>'
                                                                        + '<td>'
                                                                            + '<h3>'
                                                                                + words.connect_in_messenger
                                                                            + '</h3>'
                                                                        + '</td>'
                                                                        + '<td>'
                                                                            + '<button type="button" class="btn btn-primary">'
                                                                                + '<i class="fab fa-facebook-messenger"></i>'
                                                                                + words.send_message
                                                                            + '</button>'
                                                                        + '</td>'                                                                                              
                                                                    + '</tr>'
                                                                + '</tbody>'
                                                            + '</table>'
                                                        + '</td>'
                                                    + '</tr>'
                                                + '</tbody>'
                                            + '</table>'
                                        + '</div>';

            // Display preview
            $(modal + ' .ad-preview-display .panel-body').html(content_preview);
            
            // Verify which boost tab is selected
            if ( $('.main .modal.show #post-engagement-from-facebook').hasClass('active') ) {

                // Remove selected class from all buttons
                $( '.main #post-engagement-from-facebook .post-engagement-boost-it' ).removeClass( 'boost-this-post' );
                
                // Add selected class
                $( '.main #post-engagement-from-facebook .post-engagement-boost-it[data-id="' + data.post_id + '"]' ).addClass( 'boost-this-post' );

            } else if ( $('.main .modal.show #post-engagement-from-instagram').hasClass('active') ) {

                // Remove selected class from all buttons
                $( '.main #post-engagement-from-instagram .post-engagement-boost-it' ).removeClass( 'boost-this-post' );
                
                // Add selected class
                $( '.main #post-engagement-from-instagram .post-engagement-boost-it[data-id="' + data.post_id + '"]' ).addClass( 'boost-this-post' );

            }
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };

    /*
     * Display regions
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.load_regions = function ( status, data ) {

        // All regions
        var all_regions = '';

        // Verify if the success response exists
        if ( status === 'success' ) {

            // List all regions
            for ( var d = 0; d < data.regions.length; d++ ) {

                all_regions += '<li class="list-group-item">'
                                + '<a href="#" data-id="' + data.regions[d].key + '">'
                                    + data.regions[d].name
                                + '</a>'
                            + '</li>';

            }
             
        } else {
             
            all_regions += '<li class="no-results">'
                    + data.words.no_regions_found
                + '</li>';
             
        }

        if ( $('.main .modal.show .search_for_region').length > 0 ) {

            $('.main .modal.show .regions-list').html(all_regions);  

        } else {

            var dropdown  =  '<div class="dropdown">'
                + '<button class="btn btn-secondary dropdown-toggle selected-region btn-select" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                    + data.words.select_region
                + '</button>'
                + '<div class="dropdown-menu ads-campaign-dropdown" aria-labelledby="dropdownMenuButton">'
                    + '<div class="card">'
                        + '<div class="card-head">'
                            + '<input type="text" class="search_for_region" placeholder="' + data.words.search_for_regions + '">'
                        + '</div>'
                        + '<div class="card-body">'
                            + '<ul class="list-group regions-list">'
                                + all_regions
                            + '</ul>'
                        + '</div>'
                    + '</div>'
                + '</div>'
            + '</div>';

            $('.main .modal.show .select-regions').html(dropdown);  
        
        }
 
     };

    /*
     * Display cities
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.9
     */
    Main.methods.load_cities = function ( status, data ) {

        // All cities
        var all_cities = '';

        // Verify if the success response exists
        if ( status === 'success' ) {

            // List all cities
            for ( var d = 0; d < data.cities.length; d++ ) {

                all_cities += '<li class="list-group-item">'
                                + '<a href="#" data-id="' + data.cities[d].key + '">'
                                    + data.cities[d].name
                                + '</a>'
                            + '</li>';

            }
             
        } else {
             
            all_cities += '<li class="no-results">'
                    + data.words.no_cities_found
                + '</li>';
             
        }

        if ( $('.main .modal.show .search_for_city').length > 0 ) {

            $('.main .modal.show .cities-list').html(all_cities);  

        } else {

            var dropdown  =  '<div class="dropdown">'
                + '<button class="btn btn-secondary dropdown-toggle selected-city btn-select" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                    + data.words.select_city
                + '</button>'
                + '<div class="dropdown-menu ads-campaign-dropdown" aria-labelledby="dropdownMenuButton">'
                    + '<div class="card">'
                        + '<div class="card-head">'
                            + '<input type="text" class="search_for_city" placeholder="' + data.words.search_for_cities + '">'
                        + '</div>'
                        + '<div class="card-body">'
                            + '<ul class="list-group cities-list">'
                                + all_cities
                            + '</ul>'
                        + '</div>'
                    + '</div>'
                + '</div>'
            + '</div>';

            $('.main .modal.show .select-cities').html(dropdown);  
        
        }

    };
    
    /*******************************
    FORMS
    ********************************/ 
   
    /*
     * Save the Ad's Campaign
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */
    $(document).on('submit', '.main .facebook-ads-create-campaign', function (e) {
        e.preventDefault();
        
        // Get campaign's name
        var name = $(this).find('.ads-campaign-name').val();
        
        // Get campaign's objective
        var objective = $(this).find('.ads-campaign-objective').attr('data-id');
        
        // Get campaign's status
        var status = $(this).find('.ads-campaign-status').attr('data-id');

        // Get campaign's special category
        var special_ad_category = $(this).find('.special-ad-category').attr('data-id');
        
        // Get ad set name
        var ad_set_name = $(this).find('.ads-adset-name').val();
        
        // Get all selected placements
        var placements = $('.ads-campaign-ad-set-placements input[type="checkbox"]');
        
        // Define selected placements
        var selected_placements = [];
        
        // List all ad's placements
        for ( var d = 0; d < placements.length; d++ ) {
            
            if ( placements[d].checked ) {
                selected_placements.push($(placements[d]).attr('id'));
            }
            
        }
        
        // Get all selected countries
        var countries = $('.main #campaign_creation_show_select_more_countries input[type="checkbox"]');
        
        // Define selected countries
        var selected_countries = [];
        
        // List all selected countries
        for ( var d = 0; d < countries.length; d++ ) {
            
            if ( countries[d].checked ) {
                selected_countries.push($(countries[d]).attr('data-id'));
            }
            
        }
        
        // Get ad set target cost
        var target_cost = $(this).find('.ads-adset-target-cost').val();
        
        // Get ad set daily budget
        var daily_budget = $(this).find('.ads-adset-daily-budget').val();
        
        // Get audience age from
        var age_from = $(this).find('.ads-campaign-age-from').attr('data-id');
        
        // Get audience age to
        var age_to = $(this).find('.ads-campaign-age-to').attr('data-id');
        
        // Get optimization's goal
        var optimization_goal = $(this).find('.ads-campaign-optimization-goal').attr('data-id');
        
        // Get billing's event
        var billing_event = $(this).find('.ads-campaign-billing-event').attr('data-id');
        
        // Create an object with form data
        var data = {
            action: 'create_ad_campaigns',
            name: name,
            objective: objective,
            status: status,
            special_ad_category: special_ad_category,
            ad_set_name: ad_set_name,
            selected_placements: Object.entries(selected_placements),
            target_cost: target_cost,
            daily_budget: daily_budget,
            countries: Object.entries(selected_countries),
            age_from: age_from,
            age_to: age_to,
            optimization_goal: optimization_goal,
            billing_event: billing_event
        };

        if ( $('.main .modal.show .selected-region').length > 0 ) {
            data['region'] = $('.main .modal.show .selected-region').attr('data-id');
        }

        if ( $('.main .modal.show .selected-city').length > 0 ) {
            data['city'] = $('.main .modal.show .selected-city').attr('data-id');
        }
        
        if ( $('.main .facebook-ads-create-campaign #campaign-create-ads').hasClass('show') ) {
        
            // Get ad's fields
            switch(objective) {

                case 'LINK_CLICKS':

                    // Set ad's text
                    data['ad_text'] = $('.main #myTabContent5 > .tab-pane.active').find('.text').val();

                    if ( $('.main #myTabContent5 > .tab-pane.active').find('.website_url').val() ) {

                        // Set ad's url
                        data['website_url'] = $('.main #myTabContent5 > .tab-pane.active').find('.website_url').val();

                    } else {

                        // Display alert
                        Main.popup_fon('sube', words.please_enter_website_url, 1500, 2000);
                        return;

                    }

                    // Set ad's headline
                    data['headline'] = $('.main #myTabContent5 > .tab-pane.active').find('.headline').val();  

                    // Set ad's description
                    data['description'] = $('.main #myTabContent5 > .tab-pane.active').find('.description').val();

                    // Verify if user wants to use a photo
                    if ( $('.main #myTabContent5 > .tab-pane.active .ads-uploaded-photo').closest('.tab-pane').hasClass('active') ) {

                        // Set ad's image
                        data['adimage'] = $('.main #myTabContent5 > .tab-pane.active').find('.ads-uploaded-photo-single').attr('data-hash');

                    }
                    
                    // Set ad's title
                    data['ad_name'] = $('.main #myTabContent5 > .tab-pane.active').find('.ad_name').val();             

                    break;
                    
                case 'POST_ENGAGEMENT':
                    
                    // Verify which boost tab is selected
                    if ( $('.main .modal.show #post-engagement-from-facebook').hasClass('active') ) {
                        
                        // Set post's id
                        if ( $( '.main #post-engagement-from-facebook .post-engagement-boost-it.boost-this-post' ).length > 0 ) {
                            
                            data['post_id'] = $( '.main .modal.show #post-engagement-from-facebook .post-engagement-boost-it.boost-this-post' ).attr( 'data-id' );  
                            
                        }

                    } else if ( $('.main .modal.show #post-engagement-from-instagram').hasClass('active') ) {

                        // Set post's id
                        if ( $( '.main #post-engagement-from-instagram .post-engagement-boost-it.boost-this-post' ).length > 0 ) {
                            
                            data['post_id'] = $( '.main .modal.show #post-engagement-from-instagram .post-engagement-boost-it.boost-this-post' ).attr( 'data-id' );  
                            
                        }

                    }

                    // Set ad's title
                    data['ad_name'] = $('.main #myTabContent5 > .tab-pane.active').find('.ad_name').val(); 

                    break;

            }
            
        }
        
        // Verify if Ad Account has a Pixel
        if ( $(this).find( '.pixel-id' ).length > 0 ) {
            
            // Verify if a conversion was selected
            if ( $(this).find( '.pixel-conversion-id' ).attr('data-id') ) {
            
                // Set ad's pixel id
                data['pixel_id'] = $(this).find( '.pixel-id' ).attr('data-id');
                
                // Set ad's pixel conversion id
                data['pixel_conversion_id'] = $(this).find( '.pixel-conversion-id' ).attr('data-id');                
                
            }
            
        }
        
        // Verify if female gender checkbox is checked
        if ( $(this).find( '#ad-campaign-gender-female' ).is(':checked') ) {
            
            // Set female gender
            data['female_gender'] = 1;               
            
        }
        
        // Verify if male gender checkbox is checked
        if ( $(this).find( '#ad-campaign-gender-male' ).is(':checked') ) {
            
            // Set male gender
            data['male_gender'] = 1;               
            
        }
        
        // Verify if mobile type checkbox is checked
        if ( $(this).find( '#ad-campaign-mobile-type' ).is(':checked') ) {
            
            // Set mobile type
            data['mobile_type'] = 1;               
            
        }
        
        // Verify if desktop type checkbox is checked
        if ( $(this).find( '#ad-campaign-desktop-type' ).is(':checked') ) {
            
            // Set desktop type
            data['desktop_type'] = 1;               
            
        }
        
        // Get Facebook Page ID
        data['fb_page_id'] = $('.main #myTabContent5 > .tab-pane.active').find('.ads-fb-page-id').attr('data-id');
        
        // Verify if instagram account is selected
        if ( $('.main #myTabContent5 > .tab-pane.active').find('.ads-instagram-id').attr('data-id') ) {
            data['instagram_id'] = $('.main #myTabContent5 > .tab-pane.active').find('.ads-instagram-id').attr('data-id');
        }
        
        // Verify if preview exists
        if ( typeof Main.preview !== 'undefined' ) {
        
            // Verify if preview image exists
            if ( typeof Main.preview.image !== 'undefined' ) {

                // Set preview's media
                data['preview_image'] = Main.preview.image;

            }
            
        }

        // Set CSRF
        data[$(this).attr('data-csrf')] = $('.main input[name="' + $(this).attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'create_ad_campaigns');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Save the Ad's Set
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */
    $(document).on('submit', '.main .facebook-ads-create-ad-set', function (e) {
        e.preventDefault();
        
        // Get campaign id
        var campaign_id = $(this).find('.ads-selected-ad-campaign').attr('data-id');
        
        // Get ad set name
        var ad_set_name = $(this).find('.ads-adset-name').val();
        
        // Get all selected placements
        var placements = $('.main .ads-adset-ad-set-placements input[type="checkbox"]');
        
        // Define selected placements
        var selected_placements = [];
        
        // List all ad's placements
        for ( var d = 0; d < placements.length; d++ ) {
            
            if ( placements[d].checked ) {
                selected_placements.push($(placements[d]).attr('id'));
            }
            
        }
        
        // Get all selected countries
        var countries = $('.main #adset_creation_show_select_more_countries input[type="checkbox"]');
        
        // Define selected countries
        var selected_countries = [];
        
        // List all selected countries
        for ( var d = 0; d < countries.length; d++ ) {
            
            if ( countries[d].checked ) {
                selected_countries.push($(countries[d]).attr('data-id'));
            }
            
        }
        
        // Get ad set target cost
        var target_cost = $(this).find('.ads-adset-target-cost').val();
        
        // Get ad set daily budget
        var daily_budget = $(this).find('.ads-adset-daily-budget').val();
        
        // Get audience age from
        var age_from = $(this).find('.ads-ad-set-age-from').attr('data-id');
        
        // Get audience age to
        var age_to = $(this).find('.ads-ad-set-age-to').attr('data-id');
        
        // Get optimization's goal
        var optimization_goal = $(this).find('.ads-ad-set-optimization-goal').attr('data-id');
        
        // Get billing's event
        var billing_event = $(this).find('.ads-ad-set-billing-event').attr('data-id');

        // Get campaign's objective
        var objective = $('.main #ads-create-ad-set .ads-selected-ad-campaign').attr('data-objective');
        
        // Create an object with form data
        var data = {
            action: 'create_ad_set',
            campaign_id: campaign_id,
            ad_set_name: ad_set_name,
            selected_placements: Object.entries(selected_placements),
            target_cost: target_cost,
            daily_budget: daily_budget,
            countries: Object.entries(selected_countries),
            age_from: age_from,
            age_to: age_to,
            optimization_goal: optimization_goal,
            billing_event: billing_event,
            objective: objective
        };
        
        if ( $('.main .modal.show .selected-region').length > 0 ) {
            data['region'] = $('.main .modal.show .selected-region').attr('data-id');
        }

        if ( $('.main .modal.show .selected-city').length > 0 ) {
            data['city'] = $('.main .modal.show .selected-city').attr('data-id');
        }

        if ($(this).find('#adset-create-ads').hasClass('show')) {

            // Get ad's fields
            switch (objective) {

                case 'LINK_CLICKS':

                    // Set ad's text
                    data['ad_text'] = $('.main #myTabContent6 > .tab-pane.active').find('.text').val();

                    if ($('.main #myTabContent6 > .tab-pane.active').find('.website_url').val()) {

                        // Set ad's url
                        data['website_url'] = $('.main #myTabContent6 > .tab-pane.active').find('.website_url').val();

                    } else {

                        // Display alert
                        Main.popup_fon('sube', words.please_enter_website_url, 1500, 2000);
                        return;

                    }

                    // Set ad's headline
                    data['headline'] = $('.main #myTabContent6 > .tab-pane.active').find('.headline').val();

                    // Set ad's description
                    data['description'] = $('.main #myTabContent6 > .tab-pane.active').find('.description').val();

                    // Verify if user wants to use a photo
                    if ($('.main #myTabContent6 > .tab-pane.active .ads-uploaded-photo').closest('.tab-pane').hasClass('active')) {

                        // Set ad's image
                        data['adimage'] = $('.main #myTabContent6 > .tab-pane.active').find('.ads-uploaded-photo-single').attr('data-hash');

                    }

                    // Set ad's title
                    data['ad_name'] = $('.main #myTabContent6 > .tab-pane.active').find('.ad_name').val();

                    break;

                case 'POST_ENGAGEMENT':

                    // Set post's id
                    if ($('.main #ads-create-ad-set .post-engagement-boost-it.boost-this-post').length > 0) {

                        data['post_id'] = $('.main #ads-create-ad-set .post-engagement-boost-it.boost-this-post').attr('data-id');

                    }

                    // Set ad's title
                    data['ad_name'] = $('.main #myTabContent6 > .tab-pane.active').find('.ad_name').val();

                    break;

            }

        }
        
        // Verify if Ad Account has a Pixel
        if ( $(this).find( '.pixel-id' ).length > 0 ) {
            
            // Verify if a conversion was selected
            if ( $(this).find( '.pixel-conversion-id' ).attr('data-id') ) {
            
                // Set ad's pixel id
                data['pixel_id'] = $(this).find( '.pixel-id' ).attr('data-id');
                
                // Set ad's pixel conversion id
                data['pixel_conversion_id'] = $(this).find( '.pixel-conversion-id' ).attr('data-id');                
                
            }
            
        }
        
        // Get Facebook Page ID
        if ( $('.main #myTabContent6 > .tab-pane.active').find('.ads-fb-page-id').attr('data-id') ) {
            data['fb_page_id'] = $('.main #myTabContent6 > .tab-pane.active').find('.ads-fb-page-id').attr('data-id');
        }
        
        // Verify if instagram account is selected
        if ( $('.main #myTabContent6 > .tab-pane.active').find('.ads-instagram-id').attr('data-id') ) {
            data['instagram_id'] = $('.main #myTabContent6 > .tab-pane.active').find('.ads-instagram-id').attr('data-id');
        }
        
        // Verify if female gender checkbox is checked
        if ( $(this).find( '#ad-set-gender-female' ).is(':checked') ) {
            
            // Set female gender
            data['female_gender'] = 1;               
            
        }
        
        // Verify if male gender checkbox is checked
        if ( $(this).find( '#ad-set-gender-male' ).is(':checked') ) {
            
            // Set male gender
            data['male_gender'] = 1;               
            
        }
        
        // Verify if mobile type checkbox is checked
        if ( $(this).find( '#ad-set-mobile-type' ).is(':checked') ) {
            
            // Set mobile type
            data['mobile_type'] = 1;               
            
        }
        
        // Verify if desktop type checkbox is checked
        if ( $(this).find( '#ad-set-desktop-type' ).is(':checked') ) {
            
            // Set desktop type
            data['desktop_type'] = 1;               
            
        }
        
        // Verify if preview exists
        if ( typeof Main.preview !== 'undefined' ) {
        
            // Verify if preview image exists
            if ( typeof Main.preview.image !== 'undefined' ) {

                // Set preview's media
                data['preview_image'] = Main.preview.image;

            }
            
        }

        // Set CSRF
        data[$(this).attr('data-csrf')] = $('.main input[name="' + $(this).attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'create_ad_set');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Save the Ad
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */
    $(document).on('submit', '.main .facebook-ads-create-new-ad', function (e) {
        e.preventDefault();
        
        // Get campaign's ID
        var campaign_id = $(this).find('.ads-selected-ad-campaign').attr('data-id');
        
        // Get Ad Set's ID
        var ad_set_id = $(this).find('.ads-selected-ad-set').attr('data-id');

        // Get campaign's objective
        var objective = $('.main #ads-create-new-ad .ads-selected-ad-campaign').attr('data-objective');
        
        // Create an object with form data
        var data = {
            action: 'create_ad',
            campaign_id: campaign_id,
            ad_set_id: ad_set_id,
            objective: objective
        };
        
        if ( $(this).find('#ads-create-ads').hasClass('show') ) {
        
            // Get ad's fields
            switch(objective) {

                case 'LINK_CLICKS':

                    // Set ad's text
                    data['ad_text'] = $('.main #myTabContent7 > .tab-pane.active').find('.text').val();

                    if ( $('.main #myTabContent7 > .tab-pane.active').find('.website_url').val() ) {

                        // Set ad's url
                        data['website_url'] = $('.main #myTabContent7 > .tab-pane.active').find('.website_url').val();

                    } else {

                        // Display alert
                        Main.popup_fon('sube', words.please_enter_website_url, 1500, 2000);
                        return;

                    }

                    // Set ad's headline
                    data['headline'] = $('.main #myTabContent7 > .tab-pane.active').find('.headline').val();  

                    // Set ad's description
                    data['description'] = $('.main #myTabContent7 > .tab-pane.active').find('.description').val();

                    // Verify if user wants to use a photo
                    if ( $('.main #myTabContent7 > .tab-pane.active .ads-uploaded-photo').closest('.tab-pane').hasClass('active') ) {

                        // Set ad's image
                        data['adimage'] = $('.main #myTabContent7 > .tab-pane.active').find('.ads-uploaded-photo-single').attr('data-hash');

                    }
                    
                    // Set ad's title
                    data['ad_name'] = $('.main #myTabContent7 > .tab-pane.active').find('.ad_name').val();             

                    break;

                case 'POST_ENGAGEMENT':

                    // Set post's id
                    if ($('.main #ads-create-new-ad .post-engagement-boost-it.boost-this-post').length > 0) {

                        data['post_id'] = $('.main #ads-create-new-ad .post-engagement-boost-it.boost-this-post').attr('data-id');

                    }

                    // Set ad's title
                    data['ad_name'] = $('.main #myTabContent7 > .tab-pane.active').find('.ad_name').val();

                    break;

            }
            
        }
        
        // Verify if Ad Account has a Pixel
        if ( $(this).find( '.pixel-id' ).length > 0 ) {
            
            // Verify if a conversion was selected
            if ( $(this).find( '.pixel-conversion-id' ).attr('data-id') ) {
            
                // Set ad's pixel id
                data['pixel_id'] = $(this).find( '.pixel-id' ).attr('data-id');
                
                // Set ad's pixel conversion id
                data['pixel_conversion_id'] = $(this).find( '.pixel-conversion-id' ).attr('data-id');                
                
            }
            
        }
        
        // Get Facebook Page ID
        data['fb_page_id'] = $('.main #myTabContent7 > .tab-pane.active').find('.ads-fb-page-id').attr('data-id');
        
        // Verify if instagram account is selected
        if ( $('.main #myTabContent7 > .tab-pane.active').find('.ads-instagram-id').attr('data-id') ) {
            data['instagram_id'] = $('.main #myTabContent7 > .tab-pane.active').find('.ads-instagram-id').attr('data-id');
        }
        
        // Verify if preview exists
        if ( typeof Main.preview !== 'undefined' ) {
        
            // Verify if preview image exists
            if ( typeof Main.preview.image !== 'undefined' ) {

                // Set preview's media
                data['preview_image'] = Main.preview.image;

            }
            
        }

        // Set CSRF
        data[$(this).attr('data-csrf')] = $('.main input[name="' + $(this).attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'create_ad');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });    
    
    /*
     * Upload media file
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */
    $(document).on('submit', '.main #upim', function (e) {
        e.preventDefault();
        
        // Get allowed size
        var size = $( '.main .facebook-ads-page' ).attr( 'data-up' );
        
        // Verify if uploaded file is bigger than limit
        if ( parseInt(size) * 1000000 < $('.main #file')[0].files[0].size ) {

            // Display alert error
            Main.popup_fon('sube', Main.translation.mm118 + ' ' + size + 'MB.', 1500, 2000);
            e.preventDefault();
            return;

        }
        
        var form = new FormData();
        form.append('path', '/');
        form.append('file', $('.main #file')[0].files[0]);
        form.append('type', $('.main #type').val());
        form.append('enctype', 'multipart/form-data');
        form.append('action', 'upload_media_on_facebook');
        form.append($('.upim').attr('data-csrf'), $('input[name="' + $('.upim').attr('data-csrf') + '"]').val());
        
        // Upload media
        $.ajax({
            url: url + 'user/app-ajax/facebook-ads',
            type: 'POST',
            data: form,
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function () {

                // Display loading animation
                $('.page-loading').fadeIn('slow');

            },
            success: function (data) {

                // Delete preview
                if (typeof Main.preview === 'undefined') {

                    // Define preview
                    Main.preview = {};

                }
                
                // Verify if the success response exists
                if ( data.success === true ) {
                    
                    // Display alert
                    Main.popup_fon('subi', data.message, 1500, 2000);
                    
                    if ( data.type === 'image' ) {
                    
                        var image_preview = '<div class="col-xl-12">'
                                                + '<div class="ads-uploaded-photo-single" data-hash="' + data.hash + '">'
                                                    + '<div class="row">'
                                                        + '<div class="col-4">'
                                                            + '<img src="' + data.url_128 + '">'
                                                        + '</div>'
                                                        + '<div class="col-7">'
                                                            + '<h3>' + data.name + '</h3>'
                                                            + '<p>' + data.width + ' x ' + data.height + '</p>'
                                                            + '<div>'
                                                                + '<button class="btn btn-default upload-ads-image" type="button">' + data.words.change + '</button>'
                                                                + '<button class="btn btn-default delete-ad-media-by-hash" type="button">' + data.words.delete + '</button>'
                                                            + '</div>'
                                                        + '</div>'
                                                    + '</div>'
                                                + '</div>'
                                            + '</div>';

                        if ( $('.main #ads-create-campaign').hasClass('show') ) {

                            $('.main #myTabContent5 > .tab-pane.active .ads-uploaded-photo').html(image_preview);

                        } else if ( $('.main #ads-create-ad-set').hasClass('show') ) {
                            
                            $('.main #myTabContent6 > .tab-pane.active .ads-uploaded-photo').html(image_preview);
                            
                        } else if ( $('.main #ads-create-new-ad').hasClass('show') ) {

                            $('.main #myTabContent7 > .tab-pane.active .ads-uploaded-photo').html(image_preview);

                        }
                        
                        // Set the preview image's url
                        Main.preview.image = data.url;
                    
                    } else if ( data.type === 'video' ) {
                        
                        var video_preview = '<div class="col-xl-12">'
                                                + '<div class="ads-uploaded-video-single" data-id="' + data.id + '">'
                                                    + '<div class="row">'
                                                        + '<div class="col-4">'
                                                            + '<img src="' + data.url + '">'
                                                        + '</div>'
                                                        + '<div class="col-7">'
                                                            + '<h3>' + data.name + '</h3>'
                                                            + '<div>'
                                                                + '<button class="btn btn-default upload-ads-video" type="button">' + data.words.change + '</button>'
                                                                + '<button class="btn btn-default delete-ad-media-by-id" type="button">' + data.words.delete + '</button>'
                                                            + '</div>'
                                                        + '</div>'
                                                    + '</div>'
                                                + '</div>'
                                            + '</div>';

                        if ( $('.main #ads-create-campaign').hasClass('show') ) {

                            $('.main #myTabContent5 > .tab-pane.active .ads-uploaded-video').html(video_preview);

                        } else if ( $('.main #ads-create-ad-set').hasClass('show') ) {
                            
                            $('.main #myTabContent6 > .tab-pane.active .ads-uploaded-video').html(video_preview);
                            
                        } else if ( $('.main #ads-create-new-ad').hasClass('show') ) {

                            $('.main #myTabContent7 > .tab-pane.active .ads-uploaded-video').html(image_preview);

                        }
                        
                        // Set the preview video source
                        Main.preview.video_source = data.source;
                        
                    }
                    
                    // Generate preview
                    Main.reload_ad_link_preview();

                } else {

                    // Display alert
                    Main.popup_fon('sube', data.message, 1500, 2000);

                }
                
            },
            error: function (jqXHR, textStatus, errorThrown) {
                
                console.log('ERRORS: ' + textStatus);
                
            },
            complete: function () {
                
                // Hide loading animation
                $('.page-loading').fadeOut('slow');
                
            }
            
        });
        
        e.preventDefault();
        
    });
    
    /*
     * Save the Pixel's Conversion
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */
    $(document).on('submit', '.main .facebook-ads-create-pixel-conversion', function (e) {
        e.preventDefault();
        
        // Get conversion's name
        var name = $(this).find('.ads-pixel-conversion-name').val();
        
        // Get conversion's url
        var conversion_url = $(this).find('.ads-pixel-conversion-url').val();
        
        // Get conversion's type
        var conversion_type = $(this).find('#ads-select-conversion-type').attr('data-id');
        
        if ( typeof conversion_type === 'undefined' ) {
            
            // Display alert
            Main.popup_fon('sube', words.please_select_conversion_type, 1500, 2000);
            return;
            
        }
        
        // Create an object with form data
        var data = {
            action: 'create_pixel_conversion',
            name: name,
            conversion_url: conversion_url,
            conversion_type: conversion_type
        };

        // Set CSRF
        data[$(this).attr('data-csrf')] = $('.main input[name="' + $(this).attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'POST', data, 'create_pixel_conversion');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Show Campaign's Insights
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */
    $(document).on('submit', '.main .ads-campaign-insights', function (e) {
        e.preventDefault();
        
        // Get time order
        var time = $(this).find('.ads-campaign-insights-by-time').attr('data-time');
        
        // Get campaign's id
        var campaign_id = $(this).find('.ads-campaign-insights-by-campaign').attr('data-id');  
        
        if ( typeof campaign_id === 'undefined' ) {
            
            // Display alert
            Main.popup_fon('sube', words.please_select_ad_campaign, 1500, 2000);
            return false;
            
        }
        
        // Create an object with form data
        var data = {
            action: 'ad_campaigns_insights_by_time',
            order: time,
            campaign_id: campaign_id
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'ad_campaigns_insights_by_time');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Show Ad Set's Insights
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */
    $(document).on('submit', '.main .ads-ad-sets-insights', function (e) {
        e.preventDefault();
        
        // Get time order
        var time = $(this).find('.ads-ad-sets-insights-by-time').attr('data-time');
        
        // Get ad set's id
        var ad_set_id = $(this).find('.ads-campaign-insights-by-ad-sets').attr('data-id');  
        
        if ( typeof ad_set_id === 'undefined' ) {
            
            // Display alert
            Main.popup_fon('sube', words.please_select_ad_set, 1500, 2000);
            return false;
            
        }
        
        // Create an object with form data
        var data = {
            action: 'ad_sets_insights_by_time',
            order: time,
            ad_set_id: ad_set_id
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'ad_sets_insights_by_time');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Show Ad's Insights
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */
    $(document).on('submit', '.main .ads-ad-insights', function (e) {
        e.preventDefault();
        
        // Get time order
        var time = $(this).find('.ads-ad-insights-by-time').attr('data-time');
        
        // Get ad's id
        var ad_id = $(this).find('.ads-campaign-insights-by-ad').attr('data-id');  
        
        if ( typeof ad_id === 'undefined' ) {
            
            // Display alert
            Main.popup_fon('sube', words.please_select_ad, 1500, 2000);
            return false;
            
        }
        
        // Create an object with form data
        var data = {
            action: 'ad_insights_by_time',
            order: time,
            ad_id: ad_id
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/facebook-ads', 'GET', data, 'ad_insights_by_time');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*******************************
    DEPENDENCIES
    ********************************/
   
    // Load all ADS Accounts
    Main.quick_ad_accounts();
    Main.load_ad_accounts(1);
    
});