/*
 * Rss javascript file
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
     * Get selected rss's social accounts
     * 
     * @since   0.0.7.4
     */
    Main.get_rss_feed_selected_account = function () {
        
        // Get the RSS's id
        var rss_id = $( '.rss-page' ).attr('data-id');        
        
        // Verify if user wants select groups or accounts
        if ( $( '.rss-feed-groups-list' ).length > 0 ) {

            var data = {
                action: 'rss_feed_get_selected_group',
                rss_id: rss_id
            };

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'rss_feed_get_selected_group');
            
        } else {

            var data = {
                action: 'rss_feed_get_selected_accounts',
                rss_id: rss_id
            };

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'rss_feed_get_selected_accounts');
        
        }
        
    };
    
    /*
     * Get RSS's posts
     * 
     * @since   0.0.7.4
     */
    Main.get_rss_feed_posts = function (page) {
        
        // Get the RSS's id
        var rss_id = $( '.rss-page' ).attr('data-id');

        var data = {
            action: 'rss_feed_get_rss_posts',
            key: $( '.main .history-search-for-posts' ).val(),
            page: page,
            rss_id: rss_id
        };
        
        data[$('.send-post').attr('data-csrf')] = $('input[name="' + $('.send-post').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/posts', 'POST', data, 'rss_feed_get_rss_posts');
        
    };    
    
    /*
     * Unmarks and marks as selected accounts in the list
     * 
     * @since   0.0.7.4
     */
    Main.get_rss_feed_mark_selected = function () {
        
        // Verify if user wants select groups or accounts
        if ( $( '.rss-feed-groups-list' ).length > 0 ) {
            
            // Unmarks all accounts
            $( '.rss-feed-groups-list ul li' ).removeClass('group-selected');

            // Get all available groups
            var rss_feed_accounts_list = $( '.rss-feed-groups-list ul li' );

            for ( var l = 0; l < rss_feed_accounts_list.length; l++ ) {

                var network_id = rss_feed_accounts_list.eq(l).find('a').attr('data-id');

                if ( $('.rss-selected-group ul li a[data-id="' + network_id + '"]').length > 0 ) {

                    rss_feed_accounts_list.eq(l).addClass( 'group-selected' );

                }

            }            
            
        } else {
        
            // Unmarks all accounts
            $( '.rss-feed-accounts-list ul li' ).removeClass('account-selected');

            // Get all available accounts
            var rss_feed_accounts_list = $( '.rss-feed-accounts-list ul li' );

            for ( var l = 0; l < rss_feed_accounts_list.length; l++ ) {

                var network_id = rss_feed_accounts_list.eq(l).find('a').attr('data-id');

                if ( $('.rss-selected-accounts ul li a[data-id="' + network_id + '"]').length > 0 ) {

                    rss_feed_accounts_list.eq(l).addClass( 'account-selected' );

                }

            }
            
        }
        
    };
    
    /*
     * Load available networks
     * 
     * @since   0.0.7.4
     */
    Main.account_manager_load_networks = function () {
        
        var data = {
            action: 'account_manager_load_networks'
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'account_manager_load_networks');
        
    };
    
    /*
     * Load network's accounts
     * 
     * @since   0.0.7.4
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
        Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'account_manager_get_accounts');
        
    };
    
    /*
     * Load available networks
     * 
     * @since   0.0.7.4
     */
    Main.account_manager_load_networks = function () {
        
        var data = {
            action: 'account_manager_load_networks'
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'account_manager_load_networks');
        
    };
    
    /*
     * Reload accounts
     * 
     * @since   0.0.7.4
     */
    Main.reload_accounts = function () {
        
        var network = $('#nav-accounts-manager').find('.network-selected a').attr('data-network');
        
        $('.manage-accounts-all-accounts').empty();
        
        Main.account_manager_get_accounts(network, 'accounts_manager');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    };
    
    /*
     * Get content by url
     * 
     * @param string geturl contains the url
     * 
     * @since   0.0.7.4
     */ 
    Main.get_page_content_by_url = function(geturl) {
        
        var data = {
            action: 'parse_url_content',
            url: 'url: ' + geturl
        };

        data[$('.send-post').attr('data-csrf')] = $('input[name="' + $('.send-post').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/ajax/parse_url', 'POST', data, 'rss_feed_display_url_preview');
        
    };
    
    /*
     * Load the accounts for RSS Feed
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.7.6
     */
    Main.rss_search_accounts = function (page) {
        
        var data = {
            action: 'composer_search_accounts',
            key: $( '.main #nav-accounts .rss-search-for-accounts' ).val(),
            page: page
        };
        
        // Set CSRF
        data[$('.send-post').attr('data-csrf')] = $('input[name="' + $('.send-post').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/posts', 'POST', data, 'rss_feed_search_accounts');
        
    };
    
    /*
     * Load the groups for RSS Feed
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.7.6
    */
    Main.rss_search_groups = function (page) {
        
        var data = {
            action: 'composer_search_groups',
            key: $( '.main #nav-accounts .rss-search-for-groups' ).val(),
            page: page
        };
        
        // Set CSRF
        data[$('.send-post').attr('data-csrf')] = $('input[name="' + $('.send-post').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/posts', 'POST', data, 'rss_feed_search_groups');
        
    };    
    
    /*******************************
    ACTIONS
    ********************************/
   
    /*
     * Search for accounts in the RSS page
     * 
     * @since   0.0.7.5
     */
    $(document).on('keyup', '.main .history-search-for-posts', function () {
        
        if ( $( this ).val() === '' ) {
            
            // Hide cancel search button
            $( '.history-cancel-search-for-posts' ).fadeOut('slow');
            
        } else {
         
            // Display cancel search button
            $( '.history-cancel-search-for-posts' ).fadeIn('slow');
            
        }
        
        // Get RSS's posts
        Main.get_rss_feed_posts(1);
        
    });   
    
    /*
     * Search for accounts in the RSS page
     * 
     * @since   0.0.7.4
     */
    $(document).on('keyup', '.main .rss-search-for-accounts', function () {
        
        if ( $( this ).val() === '' ) {
            
            // Hide cancel search button
            $( '.rss-cancel-search-for-accounts' ).fadeOut('slow');
            
        } else {
         
            // Display cancel search button
            $( '.rss-cancel-search-for-accounts' ).fadeIn('slow');
            
        }
        
        // Get accounts by search
        Main.rss_search_accounts(1);
        
    });
    
    /*
     * Search for groups in the composer tab
     * 
     * @since   0.0.7.4
     */
    $(document).on('keyup', '.main .rss-search-for-groups', function () {
        
        if ( $( this ).val() === '' ) {
            
            // Hide cancel search button
            $( '.rss-cancel-search-for-accounts' ).fadeOut('slow');
            
        } else {
         
            // Display cancel search button
            $( '.rss-cancel-search-for-accounts' ).fadeIn('slow');
            
        }
        
        // Get groups by search
        Main.rss_search_groups(1);        
        
    }); 
    
    /*
     * Count characters in the quick schedule popup
     * 
     * @since   0.0.7.4
     */
    $(document).on('keyup', '.send-post .new-post', function () {
        
        // Verify if character count is enabled
        if ( $('.send-post .numchar').length > 0 ) {
            $('.send-post .numchar').text($(this).val().length);
        }
        
        // Get post's content
        var post_content = $(this).val();
        
        // Replace </p> with break line for preview
        var content = post_content.replace(/<\/p>/g, '\n');
        
        // Remove <p> for preview
        content = content.replace(/<p>/g, '');
        
        // Add paragraphs for preview
        var new_preview = content.replace(/\n/g, '</p><p>');
        
        // Add post body preview
        $('.post-preview-body').html('<div class="row"><p>' + new_preview + '</p></div>');
        
    });
    
    /*
     * Add post title to preview
     * 
     * @since   0.0.7.4
     */
    $(document).on('keyup', '.composer-title input[type="text"]', function () {
        
        // Add post title preview
        $( '.post-preview-title' ).html( '<div class="row">' + $(this).val() + '</div>' );
        
    });
    
    /*
     * Search for accounts in the accounts manager popup
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.4
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
            data[$('.send-post').attr('data-csrf')] = $('input[name="' + $('.send-post').attr('data-csrf') + '"]').val();

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/posts', 'POST', data, 'account_manager_search_for_accounts');
            
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
            data[$('.send-post').attr('data-csrf')] = $('input[name="' + $('.send-post').attr('data-csrf') + '"]').val();

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/posts', 'POST', data, 'account_manager_search_for_accounts');
            
        }
        
    });
    
    /*
     * Set RSS option value
     * 
     * @since   0.0.7.4
     */
    $(document).on('change', '.rss-page .rss-settings-input', function () {
        
        // Get option's id
        var option_id = $(this).attr('id');
        
        // Get option's value
        var option_value = $(this).val();
        
        // Get the RSS's id
        var rss_id = $( '.rss-page' ).attr('data-id');
        
        var data = {
            action: 'rss_feed_settings_input',
            rss_id: rss_id,
            option_id: option_id,
            option_value: option_value
        };

        // Set CSRF
        data[$('.send-post').attr('data-csrf')] = $('input[name="' + $('.send-post').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/posts', 'POST', data, 'rss_feed_option_action');
        
    });
   
    /*
     * Select or unselect account
     * 
     * @since   0.0.7.4
     */
    $(document).on('click', '.rss-feed-accounts-list li a', function (e) {
        e.preventDefault();

        // Get the RSS's id
        var rss_id = $( '.rss-page' ).attr('data-id');
        
        // Get account's id
        var network_id = $( this ).attr( 'data-id' );

        var network = $(this).attr('data-network');

        // Verify if mobile app is required
        if ( network === 'instagram_insights' || network === 'instagram_profiles' ) {

            if ( $('.main .rss-page').attr('data-mobile-installed') !== '1' ) {

                // Display alert
                Main.popup_fon('sube', words.please_install_the_mobile_client, 1500, 2000);
                return;

            }

        }
        
        // Verify if account was selected
        if ( $( this ).closest( 'li' ).hasClass( 'account-selected' ) ) {
            
            var data = {
                action: 'rss_feed_delete_selected_account',
                rss_id: rss_id,
                network_id: network_id
            };

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'rss_feed_delete_selected_account');
            
        } else {

            var data = {
                action: 'rss_feed_add_selected_account',
                rss_id: rss_id,
                network_id: network_id
            };

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'rss_feed_add_selected_account');
            
        }
        
    });
    
    /*
     * Select or unselect group
     * 
     * @since   0.0.7.4
     */
    $(document).on('click', '.rss-feed-groups-list li a', function (e) {
        e.preventDefault();

        // Get the RSS's id
        var rss_id = $( '.rss-page' ).attr('data-id');
        
        // Get group's id
        var group_id = $( this ).attr( 'data-id' );
        
        // Verify if account was selected
        if ( $( this ).closest( 'li' ).hasClass( 'group-selected' ) ) {
            
            var data = {
                action: 'rss_feed_delete_selected_group',
                rss_id: rss_id,
                group_id: group_id
            };

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'rss_feed_delete_selected_group');
            
        } else {

            var data = {
                action: 'rss_feed_add_selected_group',
                rss_id: rss_id,
                group_id: group_id
            };

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'rss_feed_add_selected_group');
            
        }
        
    });    
    
    /*
     * Unselect account
     * 
     * @since   0.0.7.4
     */
    $(document).on('click', '.rss-selected-accounts li a', function (e) {
        e.preventDefault();
        
        // Get the RSS's id
        var rss_id = $( '.rss-page' ).attr('data-id');
        
        // Get account's id
        var network_id = $( this ).attr( 'data-id' );

        var data = {
            action: 'rss_feed_delete_selected_account',
            rss_id: rss_id,
            network_id: network_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'rss_feed_delete_selected_account');
        
    });
    
    /*
     * Unselect account
     * 
     * @since   0.0.7.4
     */
    $(document).on('click', '.rss-selected-group li a', function (e) {
        e.preventDefault();
        
        // Get the RSS's id
        var rss_id = $( '.rss-page' ).attr('data-id');
        
        // Get group's id
        var group_id = $( this ).attr( 'data-id' );
            
        var data = {
            action: 'rss_feed_delete_selected_group',
            rss_id: rss_id,
            group_id: group_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'rss_feed_delete_selected_group');
        
    });
    
    /*
     * Cancel search for accounts in the RSS Feed page
     * 
     * @since   0.0.7.4
     */     
    $( document ).on( 'click', '.rss-cancel-search-for-accounts', function() {
        
        // Hide cancel search button
        $( '.rss-cancel-search-for-accounts' ).fadeOut('slow');        
        
        if ( $('.rss-search-for-groups').length > 0 ) {
            
            $( '.rss-search-for-groups' ).val( '' );
            
            // Load groups
            Main.rss_search_groups(1);
        
        } else {
            
            $( '.rss-search-for-accounts' ).val( '' );

            // Load accounts
            Main.rss_search_accounts(1);
        
        }
        
    });
    
    /*
     * Cancel search for posts in the RSS Feed page
     * 
     * @since   0.0.7.5
     */     
    $( document ).on( 'click', '.history-cancel-search-for-posts', function() {
        
        // Hide cancel search button
        $( '.history-cancel-search-for-posts' ).fadeOut('slow');
        
        // Empty search for posts input
        $( '.history-search-for-posts' ).val( '' );

        // Get RSS's posts
        Main.get_rss_feed_posts(1);
        
    });
    
    /*
     * Scroll the page
     * 
     * @since   0.0.7.4
     */     
    $( document ).on( 'click', '.main .rss-feeds-rss-content-single-share-button', function() {
        
        // Scroll page to the composer
        $([document.documentElement, document.body]).animate({
            scrollTop: $('.post-composer').offset().top - 170
        }, 500);
        
        // Get post url
        var post_url = $( this ).closest( '.rss-feeds-rss-content-single' ).find( '.rss-feeds-post-url' ).attr( 'href' );
        
        // Get post's title
        var post_title = $( this ).closest( '.rss-feeds-rss-content-single' ).find( '.rss-feeds-post-title' ).text();
        
        // Get post's content
        var post_content = $( this ).closest( '.rss-feeds-rss-content-single' ).find( '.rss-feeds-post-content' ).html();
        
        // Get post's image
        var post_image = $( this ).closest( '.rss-feeds-rss-content-single' ).find( '.rss-post-image' ).attr('src');
        
        // Add post's title
        $( '.composer-title input[type="text"]' ).val( post_title );
        
        // Add post title preview
        $( '.post-preview-title' ).html( '<div class="row">' + post_title + '</div>' );
        
        // Verify if the post's image exists
        if ( post_image ) {
            
            // Add post image in the post's preview
            $('.post-preview-medias img').attr('src', post_image);    
            
            // Display the post's image
            $('.post-preview-medias').show();
            
            // Select post's image url
            Main.selected_post_image = post_image;
            
        } else {
            
            // Hide the post's image
            $('.post-preview-medias').hide();
            
            if ( typeof Main.selected_post_image !== 'undefined' ) {
                delete Main.selected_post_image;
            }
            
        }
        
        // Verify if content exists
        if ( post_content.trim() ) {
        
            // Add post body preview
            $('.post-preview-body').html('<div class="row">' + post_content + '</div>');

            // Replace </p> with break line
            var content = post_content.replace(/<\/p>/g, '\n');

            // Remove <p>
            content = content.replace(/<p>/g, '');

            // Add post's content
            $( '.composer textarea' ).val( content.trim() );

            // Count body characters
            $('.send-post .numchar').text( $( '.composer textarea' ).val().trim().length );
            
        } else {
            
            // Add post's content
            $( '.composer textarea' ).val( '' );

            // Count body characters
            $('.send-post .numchar').text( $( '.composer textarea' ).val().trim().length );            
            
        }
        
        // Display post preview url
        Main.get_page_content_by_url(post_url);
        
        // Select url for post
        Main.selected_post_url = post_url;
        
    });
    
    /*
     * Load available networks
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.4
     */ 
    $( document ).on( 'click', 'main .rss-manage-members', function (e) {
        e.preventDefault();
        
        if ( $('.accounts-manager-search').length < 1 ) {
        
            Main.account_manager_load_networks();

            // Display loading animation
            $('.page-loading').fadeIn('slow');
        
        }
        
    });
    
    /*
     * Select account where will be published rss
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.4
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
     * Cancel accounts manager search
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.4
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
            data[$('.send-post').attr('data-csrf')] = $('input[name="' + $('.send-post').attr('data-csrf') + '"]').val();

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/posts', 'POST', data, 'account_manager_search_for_accounts');            
            
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
            data[$('.send-post').attr('data-csrf')] = $('input[name="' + $('.send-post').attr('data-csrf') + '"]').val();

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/posts', 'POST', data, 'account_manager_search_for_accounts');
            
        }
        
    });
    
    /*
     * Delete accounts from the accounts manager popup
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.4
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
        Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'account_manager_delete_accounts');
        
    });
    
    /*
     * Renew session
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.4
     */ 
    $( document ).on( 'click', '.main .accounts-manager-expired-accounts-list li a', function (e) {
        e.preventDefault();
            
        // Get the account's id
        var account_id = $(this).attr('data-id');
        
        // Get network
        var network = $('#nav-accounts-manager').find('.network-selected a').attr('data-network');
        
        var popup_url = url + 'user/connect/' + network + '?account=' + account_id;
        var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
        var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

        var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        var left = ((width / 2) - ((width/2) / 2)) + dualScreenLeft;
        var top = ((height / 2) - ((height/2) / 2)) + dualScreenTop;
        var expiredWindow = window.open(popup_url, 'Pixabay', 'scrollbars=yes, width=' + (width/2) + ', height=' + (height/1.3) + ', top=' + top + ', left=' + left);

        if (window.focus) {
            expiredWindow.focus();
        }
        
    });
    
    /*
     * Connect a new account
     * 
     * @since   0.0.7.4
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
        var networkWindow = window.open(popup_url, 'Pixabay', 'scrollbars=yes, width=' + (width/2) + ', height=' + (height/1.3) + ', top=' + top + ', left=' + left);

        if (window.focus) {
            networkWindow.focus();
        }
        
    });
    
    
    /*
     * Delete accounts from the accounts manager popup
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.4
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
        Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'accounts_manager_groups_available_accounts');
        
    });
    
    /*
     * Delete accounts group
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.4
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
        Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'accounts_manager_groups_delete_group');
        
    }); 
    
    /*
     * Add account to group
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.4
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
        Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'account_manager_add_account_to_group');
        
    });
    
    /*
     * Remove account from group
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.4
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
        Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'account_manager_remove_account_from_group');
        
    });
    
    /*
     * Save the VK token
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.4
     */ 
    $( document ).on( 'click', 'main .save-token', function () {

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
                    
                    Main.reload_accounts();
                    
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
        
    });
    
    /*
     * Detect when accounts manager is closed
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.4
     */
    $('#accounts-manager-popup').on('hidden.bs.modal', function (e) {
        
        // Hide cancel search button
        $( '.rss-cancel-search-for-accounts' ).fadeOut('slow');        
        
        if ( $( '.rss-search-for-groups' ).length > 0 ) {
            
            $('.rss-search-for-groups').val('');
        
            // Load groups
            Main.rss_search_groups(1);
        
        } else {
            
            $('.rss-search-for-accounts').val('');

            // Load accounts
            Main.rss_search_accounts(1);
        
        }      
        
    });
    
    /*
     * Schedule post
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.4
     */     
    $( document ).on( 'click', '.rss-schedule-post', function(e) {
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
        
        $('.send-post').submit();
        
    });
    
    /*
     * Get post content in the history's tab
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.0
     */     
    $( document ).on( 'click', '.history-posts .history-post-details', function(e) {
        e.preventDefault();
        
        // Remove active class
        $( '.history-posts li' ).removeClass( 'history-post-details-active' );
        
        // Add active class
        $( this ).closest('li').addClass( 'history-post-details-active' );
        
        var data = {
            action: 'rss_feed_get_post',
            post_id: $(this).attr('data-id')
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'rss_feed_get_post');
        
    });
    
    /*
     * Enable or disable an option
     * 
     * @since   0.0.7.4
     */     
    $( document ).on( 'click', '.rss-page .set_rss_opt', function() {
        
        // Get option's id
        var option_id = $(this).attr('id');
        
        // Get the RSS's id
        var rss_id = $( '.rss-page' ).attr('data-id');
        
        var data = {
            action: 'rss_feed_option_action',
            rss_id: rss_id,
            option_id: option_id
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'rss_feed_option_action');
        
    });
    
    /*
     * Display the delete RSS's option
     * 
     * @since   0.0.7.4
     */     
    $( document ).on( 'click', '.rss-page .delete-rss', function(e) {
        e.preventDefault();
        
        $('.confirm').show();
        
    });
    
    /*
     * Hide the delete RSS's option
     * 
     * @since   0.0.7.4
     */     
    $( document ).on( 'click', '.rss-page .confirm .no', function(e) {
        e.preventDefault();
        
        $('.confirm').hide();
        
    });
    
    /*
     * Delete RSS's option
     * 
     * @since   0.0.7.4
     */     
    $( document ).on( 'click', '.rss-page .confirm .yes', function(e) {
        e.preventDefault();
        
        // Get RSS's ID
        var rss_id = $('.rss-page').attr('data-id');
        
        var data = {
            action: 'rss_delete_rss_feed',
            rss_id: rss_id
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'rss_delete_rss_feed');
        
    });
    
    /*
     * Displays pagination by page click
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.5
     */    
    $( document ).on( 'click', 'body .pagination li a', function (e) {
        e.preventDefault();
        
        // Get the page number
        var page = $(this).attr('data-page');
        
        // Display results
        switch ( $(this).closest('ul').attr('data-type') ) {
            
            case 'history-posts':
                Main.get_rss_feed_posts(page);
                break;              
            
        }
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Detect accounts pagination click
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', '.main #nav-accounts .next-button, .main #nav-accounts .back-button', function (e) {
        e.preventDefault();
        
        // Get page number
        var page = $(this).attr('data-page');
        
        if ( $('.main #nav-accounts .rss-search-for-groups').length > 0 ) {
        
            // Loads groups
            Main.rss_search_groups(page);
            
        } else {

            // Load accounts
            Main.rss_search_accounts(page);
            
        }
        
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
    $( document ).on( 'click', '.main .history-reports-by-time a', function (e) {
        e.preventDefault();
        
        // Display selected time
        $('.main .order-reports-by-time').html($(this).html());
        $('.main .order-reports-by-time').attr('data-time', $(this).attr('data-time'));
        
    });
    
    /*
     * Delete selected RSS's post
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.7
     */
    $(document).on('click', '.btn-delete-post', function (e) {
        e.preventDefault();
        
        // Get post's id
        var post_id = $(this).attr('data-id');
        
        var data = {
            action: 'history_delete_rss_post',
            post_id: post_id
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/posts', 'GET', data, 'history_rss_post_delete_response');
        
    }); 
    
    /*******************************
    RESPONSES
    ********************************/
   
    /*
     * Add account to the RSS Feed
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.4
     */
    Main.methods.rss_feed_add_selected_account = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Get selected accounts
            Main.get_rss_feed_selected_account();
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };
    
    /*
     * Add group to the RSS Feed
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.4
     */
    Main.methods.rss_feed_add_selected_group = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Get selected accounts
            Main.get_rss_feed_selected_account();
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };
    
    /*
     * Remove group from the RSS Feed
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.4
     */
    Main.methods.rss_feed_delete_selected_group = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Get selected accounts
            Main.get_rss_feed_selected_account();
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };    
    
    /*
     * Remove account from the RSS Feed
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.4
     */
    Main.methods.rss_feed_delete_selected_account = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Get selected accounts
            Main.get_rss_feed_selected_account();
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };
    
    /*
     * Display accounts found by search
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.4
     */
    Main.methods.rss_feed_search_accounts = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            if ( $('.main #nav-accounts .next-button').length > 0 ) {
            
                if ( data.page < 2 ) {
                    
                    $('.main #nav-accounts .back-button').addClass('btn-disabled');
                    
                } else {
                    
                    $('.main #nav-accounts .back-button').removeClass('btn-disabled');
                    $('.main #nav-accounts .back-button').attr('data-page', (parseInt(data.page) - 1));
                    
                }
                
                if ( (parseInt(data.page) * 10 ) < data.total ) {
                    
                    $('.main #nav-accounts .next-button').removeClass('btn-disabled');
                    $('.main #nav-accounts .next-button').attr('data-page', (parseInt(data.page) + 1));
                    
                } else {
                    
                    $('.main #nav-accounts .next-button').addClass('btn-disabled');
                    
                }
            
            }

            var accounts = '';
            
            // List all accounts
            for ( var f = 0; f < data.accounts_list.length; f++ ) {
                
                var icon = data.accounts_list[f].network_info.icon;
                
                var new_icon = icon.replace(' class', ' style="color: ' + data.accounts_list[f].network_info.color + '" class');
                
                accounts += '<li>'
                                + '<a href="#" data-id="' + data.accounts_list[f].network_id + '" data-net="' + data.accounts_list[f].net_id + '" data-network="' + data.accounts_list[f].network_name + '" data-category="' + data.accounts_list[f].network_info.categories + '">'
                                    + new_icon
                                    + data.accounts_list[f].user_name
                                    + '<span><i class="icon-user"></i> ' + data.accounts_list[f].display_network_name + '</span>'
                                    + '<i class="icon-check"></i>'
                                + '</a>'
                            + '</li>';
                
            }
            
            $( '.rss-feed-accounts-list ul' ).html( accounts );
            
        } else {
            
            $( '.rss-feed-accounts-list ul' ).html( '<li class="no-accounts-found">' + data.message + '</li>' );
            
        }
        
        Main.get_rss_feed_mark_selected();

    };
    
    /*
     * Display groups found by search
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.4
     */
    Main.methods.rss_feed_search_groups = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            if ( $('.main #nav-accounts .next-button').length > 0 ) {
            
                if ( data.page < 2 ) {
                    
                    $('.main #nav-accounts .back-button').addClass('btn-disabled');
                    
                } else {
                    
                    $('.main #nav-accounts .back-button').removeClass('btn-disabled');
                    $('.main #nav-accounts .back-button').attr('data-page', (parseInt(data.page) - 1));
                    
                }
                
                if ( (parseInt(data.page) * 10 ) < data.total ) {
                    
                    $('.main #nav-accounts .next-button').removeClass('btn-disabled');
                    $('.main #nav-accounts .next-button').attr('data-page', (parseInt(data.page) + 1));
                    
                } else {
                    
                    $('.main #nav-accounts .next-button').addClass('btn-disabled');
                    
                }
            
            }
            
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
            
            $( '.rss-feed-groups-list ul' ).html( groups );
            
        } else {
            
            $( '.rss-feed-groups-list ul' ).html( '<li class="no-groups-found">' + data.message + '</li>' );
            
        }
        
        Main.get_rss_feed_mark_selected();

    };    
    
    /*
     * Display selected rss's accounts
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.4
     */
    Main.methods.rss_feed_get_selected_accounts = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            if ( data.selected_accounts.length > 0 ) {
            
                var profiles = '';

                for ( var p = 0; p < data.selected_accounts.length; p++ ) {
                    
                    var icon = data.selected_accounts[p].network_info.icon;

                    var new_icon = icon.replace(' class', ' style="color: ' + data.selected_accounts[p].network_info.color + '" class');

                    profiles += '<li>'
                                    + '<a href="#" data-id="' + data.selected_accounts[p].network_id + '" data-network="' + data.selected_accounts[p].network_name + '">'
                                        + new_icon
                                        + ' ' + data.selected_accounts[p].user_name + ' <i class="icon-check"></i>'
                                    + '</a>'
                                + '</li>';

                }

                $('.rss-selected-accounts ul').html(profiles);

            }
            
        } else {
            
            $('.rss-selected-accounts ul').html( '<li class="no-accounts-found">' + data.message + "</li>" );
            
        }
        
        Main.get_rss_feed_mark_selected();

    };
    
    /*
     * Display selected rss's group
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.4
     */
    Main.methods.rss_feed_get_selected_group = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var group = '';

            for ( var g = 0; g < data.group.length; g++ ) {

                group += '<li>'
                            + '<a href="#" data-id="' + data.group[g].list_id + '">'
                                + '<i class="icon-folder-alt"></i>  '
                                + ' ' + data.group[g].name
                                + ' <i class="icon-check"></i>'
                            + '</a>'
                        + '</li>';

            }

            $('.rss-selected-group ul').html( group );
            
        } else {
            
            $('.rss-selected-group ul').html( '<li class="no-group-found">' + data.message + "</li>" );
            
        }
        
        Main.get_rss_feed_mark_selected();

    };
    
    /*
     * Display social networks
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.4
     */
    Main.methods.account_manager_load_networks = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            $( '#nav-accounts-manager' ).html( data.social_data );
            
            if ( $( '#nav-groups-manager' ).length > 0 ) {
                
                $( '#nav-groups-manager' ).html( data.groups_data );
            
            }
            
        }
        
    };
    
    /*
     * Display search results in accounts manager
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.2
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
     * Display account deletion status
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.2
     */
    Main.methods.account_manager_delete_accounts = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Remove account from the list
            $('#nav-accounts-manager .accounts-manager-active-accounts-list li a[data-id="' + data.account_id + '"]').closest('li').remove();
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
    };
    
    /*
     * Display post publishing status
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.4
     */
    Main.methods.rss_publish_post = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Get RSS's posts posts
            Main.get_rss_feed_posts(1);
            
            // Hide share button
            $('a[href$="' + Main.selected_post_url + '"]').closest('.rss-feeds-rss-content-single').find('.rss-feeds-rss-content-single-share-button').hide();
            
            // Delete selected post's url
            delete Main.selected_post_url;
            
            // Empty post's body
            $('.new-post').val('');
            
            // Empty post's title
            $('.send-post .composer-title input[type="text"]').val('');
            
            if ( typeof Main.selected_post_image !== 'undefined' ) {
                delete Main.selected_post_image;
            }
            
            // Create post's preview
            var post_preview = '<div class="row">'
                                    + '<div class="col-xl-12 post-preview-title">'
                                        + '<div class="row">'
                                            + '<div class="col-xl-8"></div>'
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                                + '<div class="row">'
                                    + '<div class="col-xl-12 post-preview-body">'
                                        + '<div class="row">'
                                            + '<div class="col-xl-11"></div>'
                                        + '</div>'
                                        + '<div class="row">'
                                            + '<div class="col-xl-11"></div>'
                                        + '</div>'
                                        + '<div class="row">'
                                            + '<div class="col-xl-11"></div>'
                                        + '</div>'
                                        + '<div class="row">'
                                            + '<div class="col-xl-7"></div>'
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                                + '<div class="row">'
                                    + '<div class="col-xl-12 post-preview-medias">'
                                        + '<div>'
                                            + '<img src="">'
                                        + '</div>' 
                                    + '</div>'
                                + '</div>'
                                + '<div class="row">'
                                    + '<div class="col-xl-12 post-preview-url">'

                                    + '</div>'
                                + '</div>';
                        
            $('.post-footer').html(post_preview);
            
            // Empty datetime input
            $('.datetime').val('');
            
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
     * @since   0.0.7.2
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
            
            for ( var w = 0; w < groups.length; w++ ) {
                
                all_groups += '<button class="dropdown-item" type="button" data-id="' + groups[w].list_id + '">'
                                + groups[w].name
                            + '</button>';
                
            }
            
            $( document ).find( '.create-new-group-form .dropdown-menu' ).html( all_groups );
            
            $( document ).find( '.create-new-group-form .dropdown-menu button[data-id="' + data.group_id + '"]' ).click();
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
    };
    
    /*
     * Gets all available group's accounts
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.2
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
     * Display group deletion status
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.2
     */
    Main.methods.accounts_manager_groups_delete_group = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
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
            
            for ( var w = 0; w < groups.length; w++ ) {
                
                all_groups += '<button class="dropdown-item" type="button" data-id="' + groups[w].list_id + '">'
                                + groups[w].name
                            + '</button>';
                
            }
            
            $( document ).find( '.create-new-group-form .dropdown-menu' ).html( all_groups );
            
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
     * @since   0.0.7.2
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
     * Removing account from a group response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.2
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
     * Display url preview
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.4
     */
    Main.methods.rss_feed_display_url_preview = function ( status, data ) {
        
        if (data.response.error) {

            console.log(data.response.error);

        } else {
            
            var domain = '';
            
            if ( typeof data.response.domain !== 'undefined' ) {
                domain = data.response.domain.replace(/(<([^>]+)>)/ig,'');
            }
            
            var title = '';
            
            if ( typeof data.response.title !== 'undefined' ) {
                title = data.response.title.replace(/(<([^>]+)>)/ig,'');
            }            
            
            if ( data.response.img.search('no-image') > -1 ) {
                
                var data = '<table class="partial">'
                                + '<tbody>'
                                    + '<tr>'
                                        + '<td>'
                                            + '<img src="' + data.response.img + '">'
                                        + '</td>'
                                        + '<td>'
                                            + '<h3>' + title + '</h3>'
                                            + '<a href="#" target="_blank">' + domain + '</a>'
                                            + '<p>' + data.response.description + '</p>'
                                        + '</td>'
                                    + '</tr>'
                                + '</tbody>'
                            + '</table>';
                    
                $( '.post-preview-url' ).html( data );
                
            } else {
                
                var data = '<table class="full">'
                                + '<tbody>'
                                    + '<tr>'
                                        + '<td>'
                                            + '<img src="' + data.response.img + '">'
                                        + '</td>'
                                    + '</tr>'
                                    + '<tr>'
                                        + '<td>'
                                            + '<h3>' + title + '</h3>'
                                            + '<a href="#" target="_blank">' + domain + '</a>'
                                            + '<p>' + data.response.description + '</p>'
                                        + '</td>'
                                    + '</tr>'
                                + '</tbody>'
                            + '</table>';
                    
                $( '.post-preview-url' ).html( data );
                
            }

        }

    };
    
    /*
     * Displays RSS Posts
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.4
     */
    Main.methods.rss_feed_get_rss_posts = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var allposts = '';

            Main.pagination.page = data.page;
            Main.show_pagination('#nav-history', data.total);

            for (var u = 0; u < data.posts.length; u++) {

                // Set date
                var date = data.posts[u].scheduled;

                // Set time
                var gettime = Main.calculate_time(date, data.date);

                // Set status
                var status = (data.posts[u].status == 1) ? '<span class="badge badge-success">' + Main.translation.mm130 + '</span>' : (data.posts[u].status == 2) ? (data.posts[u].status == 2 && date > data.date) ? '<span class="badge badge-warning">' + Main.translation.mm111 + '</span>' : '<span class="badge badge-danger">' + Main.translation.mm112 + '</span>' : '<span class="badge badge-secondary">' + Main.translation.mm113 + '</span>';

                // Set post content
                var text = data.posts[u].content.substring(0, 50);

                // Verify if text exists
                if ( !text.trim() ) {
                    text = data.posts[u].title.substring(0, 50);
                }
                    
                // Add post
                allposts += '<li>'
                                + '<div class="row">'
                                    + '<div class="col-xl-8 col-6">'
                                        + '<h4>'
                                            + '<i class="fas fa-history"></i>'
                                            + text
                                        + '</h4>'
                                        + '<p>' + gettime + '</p>'
                                    + '</div>'
                                    + '<div class="col-xl-4 col-6 text-right">'
                                        + '<a href="#" class="btn btn-outline-info history-post-details" data-id="' + data.posts[u].post_id + '"><i class="icon-info"></i> ' + data.details + '</a>'
                                    + '</div>'                                                            
                                + '</div>'
                            + '</li>';                    

            }

            $('.history-posts-results').html( '<ul class="history-posts">' + allposts + '</ul>' );
            
        } else {
            
            $('#history .pagination').empty();
            
            $('.history-posts-results').html('<p class="no-posts-found">' + data.message + '</p>');
            
        }
        
    };
    
    /*
     * Displays RSS Post's data
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.4
     */
    Main.methods.rss_feed_get_post = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var post = '';

            if ( data.content.title !== '' ) {
                
                post += '<div class="row">'
                            + '<div class="col-xl-12">'
                                + '<h3>' + data.content.title + '</h3>'
                            + '</div>'
                        + '</div>';

            }
            
            if ( data.content.body !== '' ) {
                
                var body = data.content.body;
                
                body = body.replace(/(?:\r\n|\r|\n)/g, '<br>');
                
                post += '<div class="row">'
                            + '<div class="col-xl-12 mb-3">'
                                + body
                            + '</div>'
                        + '</div>';

            }

            if ( data.content.img ) {
                 
                post += '<div class="row">'
                            + '<div class="col-xl-12">'
                                + '<div class="post-history-media">'
                                    + '<img src="' + data.content.img + '">'
                                + '</div>'
                            + '</div>'
                        + '</div>';

            }
            
            if ( data.content.url ) {
                 
                post += '<div class="row">'
                            + '<div class="col-xl-12">'
                                + '<a href="' + data.content.url + '" target="_blank">' + data.content.url + '</a>'
                            + '</div>'
                        + '</div>';

            }

            var profiles_list = '';
            
            if ( data.content.profiles.length > 0 ) {
                
                profiles_list += '<ul>'; 
                
                for ( var p = 0; p < data.content.profiles.length; p++ ) {
                    
                    var status = '<i class="icon-check"></i>';
                    
                    if ( data.content.profiles[p].status === '0' || data.content.profiles[p].status === '2' ) {
                        status = '<i class="icon-close"></i>';
                    }
                 
                    profiles_list += '<li>'
                                + '<div class="row">'
                                    + '<div class="col-2">'
                                        + data.content.profiles[p].icon
                                    + '</div>'
                                    + '<div class="col-7 clean">'
                                        + '<h3>' + data.content.profiles[p].user_name + '</h3>'
                                        + '<p><i class="icon-user"></i> ' + data.content.profiles[p].network_name + '</p>'
                                    + '</div>'                              
                                    + '<div class="col-2 text-right">'
                                        + status
                                    + '</div>'
                                + '</div>';
                        
                    if ( data.content.profiles[p].network_status ) {

                        profiles_list += '<div class="row">'
                                    + '<div class="col-xl-12 publish-error">'
                                        + '<div class="publish-error-status">'
                                            + '<div class="publish-error-status-header"></div>'
                                            + '<div class="publish-error-status-body">'
                                                + '<p>' + data.content.profiles[p].network_status + '</p>'
                                            + '</div>'
                                        + '</div>'
                                    + '</div>'
                                + '</div>';                

                    }
                        
                    profiles_list += '</li>';
                    
                }
                
                profiles_list += '</ul>';

            }
            
            var time;

            if ( isNaN(data.content.datetime) ) {
                time = data.content.datetime;
            } else {
                time = Main.calculate_time(data.content.datetime, data.content.time);
            }

            var actions = '<div class="row">'
                            + '<div class="col-xl-12">'
                                + '<div class="history-status-actions">'
                                    + '<div class="row">'
                                        + '<div class="col-xl-6">'
                                            + '<p>' + time + '</p>'
                                        + '</div>'
                                        + '<div class="col-xl-6 text-right">'
                                            + '<button type="button" class="btn btn-delete-post" data-id="' + data.content.post_id + '"><i class="icon-trash"></i> '
                                                + data.content.delete_post
                                            + '</button>'
                                        + '</div>'            
                                    + '</div>'
                                + '</div>'
                            + '</div>'
                        + '</div>';
            
            $('.history-profiles-list').html( actions+ profiles_list );
            
            $('.history-post-content').html(post);
            
        }
        
    };
    
    /*
     * Enable or disable RSS's option response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.4
     */
    Main.methods.rss_feed_option_action = function ( status, data ) {

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
     * Display the RSS Feed response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.4
     */
    Main.methods.rss_delete_rss_feed = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            setTimeout(function(){
                // Redirect to the Posts's page
                document.location.href = url + 'user/app/posts';
            }, 3000);
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
    };
    
    /*
     * Display network's accounts
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.4
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
                $( '#accounts-manager-popup .manage-accounts-all-accounts' ).html(data.active);

                // Display network's instructions
                $( '#accounts-manager-popup .manage-accounts-network-instructions' ).html(data.instructions);

                // Display search form
                $( '#accounts-manager-popup .manage-accounts-search-form' ).html(data.search_form);
            
            } else {
                
                // Display accounts
                $( '#accounts-manager-popup .manage-accounts-groups-all-accounts' ).html(data.active);

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
     * Display reports response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.order_reports_by_time = function ( status, data ) {
        
        // Verify if the success response exists
        if ( status === 'success' ) {
            
            var all_accounts = [];
            
            for( var a = 0; a < data.reports.accounts.length; a++ ) {
                
                all_accounts[data.reports.accounts[a].datetime] = {
                    total: data.reports.accounts[a].total,
                    errors: data.reports.accounts[a].errors
                };
                
            }
            
            var reports = '';

            for( var p = 0; p < data.reports.posts.length; p++ ) {
                
                var accounts = 0;
                var errors = 0;
                
                if ( (data.reports.posts[p].datetime in all_accounts) ) {
                    
                    accounts = all_accounts[data.reports.posts[p].datetime].total;
                    errors = all_accounts[data.reports.posts[p].datetime].errors;
                    
                }
                
                reports += '<tr>'
                                + '<td>'
                                    + data.reports.posts[p].datetime
                                + '</td>'
                                + '<td>'
                                    + data.reports.posts[p].total
                                + '</td>'
                                + '<td>'
                                    + accounts
                                + '</td>'
                                + '<td>'
                                    + errors
                                + '</td>'
                            + '</tr>';
                
            }  
            
            // Show results
            $('.main #history-generate-reports tbody').html(reports);
            
        } else {
            
            var message = '<tr>'
                              + '<td colspan="4">'
                                  + '<p>'
                                      + data.message
                                  + '</p>'
                              + '</td>'
                          + '</tr>';
                  
            // Show results
            $('.main #history-generate-reports tbody').html(message);                  
            
        }        
        
    };
    
    /*
     * Display RSS's post response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.7
     */
    Main.methods.history_rss_post_delete_response = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Get RSS's posts
            Main.get_rss_feed_posts(1);
            
            $( '.history-post-content' ).html( '<p class="no-post-selected">' + data.no_post_selected + '<p>' );
            $( '.history-profiles-list' ).html( '<p class="no-post-selected">' + data.no_post_selected + '<p>' );
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };
    
    /*******************************
    FORMS
    ********************************/
   
    /*
     * Publish a post
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.0
     */
    $('.send-post').submit(function (e) {
        e.preventDefault();

        // Set current time
        var currentdate = new Date();
        
        // Set date time
        var datetime = currentdate.getFullYear() + '-' + (currentdate.getMonth() + 1) + '-' + currentdate.getDate() + ' ' + currentdate.getHours() + ':' + currentdate.getMinutes() + ':' + currentdate.getSeconds();
        
        // Set post's title
        var post_title = $('.send-post .composer-title input[type="text"]').val();
        
        // set message
        var post = btoa(encodeURIComponent($('.new-post').val()));
        
        // Remove non necessary characters
        post = post.replace('/', '-');
        post = post.replace(/=/g, '');
        
        // Set post's publish date
        var date = $('.datetime').val();
        
        // Verify if user has scheduled the post
        if ( !date ) {
            date = datetime;
        }
        
        var image = '';
        
        if ( typeof Main.selected_post_image !== 'undefined' ) {
            image = Main.selected_post_image;
        }
        
        // Get Url
        var post_url = Main.selected_post_url;
        
        // Create an object with form data
        var data = {
            action: 'rss_publish_post',
            post: post,
            post_title: post_title,
            url: post_url,
            image: image,
            date: date,
            current_date: datetime,
            rss_id: $( '.rss-page' ).attr('data-id')
        };
        
        // Set CSRF
        data[$('.send-post').attr('data-csrf')] = $('input[name="' + $('.send-post').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/posts', 'POST', data, 'rss_publish_post');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
   
    /*
     * Create a group
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.2
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
        Main.ajax_call(url + 'user/app-ajax/posts', 'POST', data, 'account_manager_create_accounts_group');
        
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
    $(document).on('submit', '.main .posts-generate-report', function (e) {
        e.preventDefault();
        
        // Get time order
        var time = $(this).find('.order-reports-by-time').attr('data-time');
        
        // Create an object with form data
        var data = {
            action: 'order_rss_reports_by_time',
            order: time,
            rss_id: $( '.rss-page' ).attr('data-id')
        };
        
        // Set CSRF
        data[$(this).attr('data-csrf')] = $('input[name="' + $(this).attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/posts', 'POST', data, 'order_reports_by_time');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    // Get selected accounts
    Main.get_rss_feed_selected_account();
    
    // Get RSS's posts
    Main.get_rss_feed_posts(1);
    
});