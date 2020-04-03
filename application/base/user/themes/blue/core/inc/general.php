<?php
/**
 * General Inc
 *
 * This file contains the general functions
 * used in the theme
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| DEFAULTS FUNCTIONS WHICH RETURNS DATA
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
| DEFAULTS FUNCTIONS WHICH DISPLAYS DATA
|--------------------------------------------------------------------------
*/

if ( !function_exists('get_the_notifications') ) {
    
    /**
     * The function get_the_notifications loads the notifications
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function get_the_notifications() {

        // Run the hook
        run_hook('the_notifications', array());
        
    }
    
}

if ( !function_exists('get_the_tickets') ) {
    
    /**
     * The function get_the_tickets loads the tickets
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function get_the_tickets() {

        // Run the hook
        run_hook('the_tickets', array());
        
    }
    
}

if ( !function_exists('get_the_user_profile') ) {
    
    /**
     * The function get_the_user_profile loads the user's profile
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function get_the_user_profile() {

        // Run the hook
        run_hook('the_user_profile', array());
        
    }
    
}

if ( !function_exists('get_the_site_logo') ) {
    
    /**
     * The function get_the_site_logo displays the site's logo
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function get_the_site_logo() {

        // Verify if logo exists
        if ( get_option('main_logo') ) {

            echo '<a class="home-page-link" href="' . site_url() . '">'
                . '<img src="' . get_option('main_logo') . '">'
            . '</a>';

        }
        
    }
    
}

if ( !function_exists('get_the_site_favicon') ) {
    
    /**
     * The function get_the_site_favicon displays the site's favicon
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function get_the_site_favicon() {

        // Verify if favicon exists
        if ( get_option('favicon') ) {

            echo '<link rel="shortcut icon" href="' . get_option('favicon') . '" />';

        }
        
    }
    
}

/*
|--------------------------------------------------------------------------
| DEFAULT FUNCTIONS TO SAVE DATA
|--------------------------------------------------------------------------
*/

/**
 * The public method add_hook registers a hook
 * 
 * @since 0.0.7.8
 */
add_hook(
    'the_footer',
    function () {

        echo "<script src=\"" . base_url('assets/base/user/themes/blue/js/main.js') . "\"></script>\n";

    }

);

/**
 * The public method add_hook registers a hook
 * 
 * @since 0.0.7.9
 */
add_hook(
    'the_notifications',
    function () {

        // Get codeigniter object instance
        $CI = get_instance();
        
        // Load Notifications Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER . 'themes/blue/models/', 'Blue_notifications_model', 'blue_notifications_model' );

        // Get notifications
        $notifications = $CI->blue_notifications_model->get_notifications( $CI->user_id, 0, 10 );
        
        $all_notifications = '';
        
        $count = 0;
        
        // Verify if notifications exists
        if ($notifications) {
            
            // List all notifications
            foreach ($notifications as $notification) {
                
                // Define variable new
                $new = '';
                
                // Verify if user has read the notification
                if ( $notification['user_id'] != $CI->user_id ) {
                    
                    $new = 'new';
                    $count++;
                    
                }
                
                $all_notifications .= '<li class="' . $new . '">'
                            . '<div class="row">'
                                . '<div class="col-lg-2 col-xs-3 col-3">'
                                    . '<i class="icon-bell"></i>'
                                . '</div>'
                                . '<div class="col-lg-10 col-xs-9 col-9">'
                                    . '<p>'
                                        . '<a href="' . site_url('user/notifications?p=notifications&notification=' . $notification['notification_id']) . '">'
                                            . $notification['notification_title']
                                        . '</a>'
                                    . '</p>'
                                    . '<span>'
                                        . calculate_time($notification['sent_time'], time())
                                    . '</span>'
                                . '</div>'
                            . '</div>'
                        . '</li>';
                        
            }
            
        } else {
            
            // No notifications message
            $all_notifications = '<li>'
                . '<div class="col-xl-12 clean col-xs-12">'
                    . '<p class="no-results">'
                        . $CI->lang->line('theme_no_notifications_found')
                    . '</p>'
                . '</div>'
            . '</li>';
            
        }

        echo '<li class="dropdown">'
                . '<a class="dropdown-toggle" data-toggle="dropdown">'
                    . '<i class="icon-bell"></i>'
                    . '<span class="label label-primary">'
                        . $count
                    . '</span>'
                . '</a>'
                . '<ul class="dropdown-menu notificationss">'
                    . '<li>'
                        . $CI->lang->line('theme_notifications')
                    . '</li>'
                    . $all_notifications
                    . '<li>'
                        . '<a href="' . site_url('user/notifications') . '">'
                            . $CI->lang->line('theme_see_all')
                        . '</a>'
                    . '</li>'
                . '</ul>'
            . '</li>';

    }

);

/**
 * The public method add_hook registers a hook
 * 
 * @since 0.0.7.9
 */
add_hook(
    'the_tickets',
    function () {

        // Get codeigniter object instance
        $CI = get_instance();
        
        // Load Tickets Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER . 'themes/blue/models/', 'Blue_tickets_model', 'blue_tickets_model' );

        // Get all tickets
        $all_tickets = $CI->blue_tickets_model->get_all_tickets_for( $CI->user_id );
        
        $tickets = '';
        
        $count = 0;
        
        // Verify if tickets exists
        if ( $all_tickets ) {
            
            // List all tickets
            foreach ( $all_tickets as $ticket ) {
                
                // Define new variable
                $new = '';
                
                // Verify if the ticket was read already
                if ( $ticket['status'] == 2 ) {
                    
                    $new = 'new';
                    $count++;
                    
                }
                
                $tickets .= '<li class="' . $new . '">
                                <div class="row">
                                    <div class="col-lg-2 col-xs-3 col-3"><i class="icon-question"></i></div>
                                    <div class="col-lg-10 col-xs-9 col-9">'
                                        . '<p>'
                                            . '<a href="' . site_url('user/faq?p=tickets&ticket=' . $ticket['ticket_id']) . '">'
                                                . $ticket['subject']
                                            . '</a>'
                                        . '</p>'
                                        . '<span>'
                                            . calculate_time($ticket['created'], time())
                                        . '</span>'
                                    . '</div>'
                                . '</div>'
                            . '</li>';
            }
            
        } else {
            
            // No tickets found
            $tickets = '<li>'
                . '<div class="col-lg-12 clean col-xs-12">'
                    . '<p class="no-results">'
                        . $CI->lang->line('theme_no_tickets_found')
                    . '</p>'
                . '</div>'
            . '</li>';
            
        }

        echo '<li class="dropdown">'
                . '<a class="dropdown-toggle" data-toggle="dropdown">'
                    . '<i class="icon-question"></i>'
                    . '<span class="label label-success">'
                        . $count
                    . '</span>'
                . '</a>'
                . '<ul class="dropdown-menu show-tickets-lists">'
                    . '<li>'
                        . $CI->lang->line('theme_my_tickets')
                    . '</li>'
                    . $tickets
                    . '<li>'
                        . '<a href="' . site_url('user/faq') . '">'
                            . $CI->lang->line('theme_support_center')
                        . '</a>'
                    . '</li>'
                . '</ul>'
            . '</li>';

    }

);

/**
 * The public method add_hook registers a hook
 * 
 * @since 0.0.7.9
 */
add_hook(
    'the_user_profile',
    function () {

        // Get codeigniter object instance
        $CI = get_instance();
        
        $user_profile = array();
        
        if ( !$CI->session->userdata( 'member' ) ) {
        
            // Gets current user's information
            $user_info = $CI->user->get_user_info( $CI->user_id );

            if ( $user_info['first_name'] ) {

                $user_profile['name'] = $user_info['first_name'] . ' ' . $user_info['last_name'];

            } else {

                $user_profile['name'] = $user_info['username'];

            }

            $user_profile['email'] = $user_info['email'];
            
        } else {
            
            // Load Team Model
            $CI->load->model('team');
            
            // Get member team info
            $member_info = $CI->team->get_member( $CI->user_id, 0, $CI->session->userdata( 'member' ) );

            $user_profile['name'] = $member_info[0]->member_username;

            $user_profile['email'] = $member_info[0]->member_email;
            
        }

        echo '<li class="dropdown profile-menu">'
            . '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'
                . '<img src="https://www.gravatar.com/avatar/' . md5($user_profile['email']) . '">'
                . '<strong>'
                    . $user_profile['name']
                . '</strong>'
                . '<i class="fas fa-sort-down"></i>'
            . '</a>'
            . '<ul class="dropdown-menu">';

            get_menu(
                'user_top_menu',
                array(
                    'before_menu' => '',
                    'before_single_item' => '<li[active]><a href="[url]"><i class="[class]"></i>',
                    'after_single_item' => '</a></li>',
                    'after_menu' => ''
                )
            );

                echo '<li>'
                    . '<a href="' . site_url('logout') . '">'
                        . '<i class="icon-logout"></i>'
                        . $CI->lang->line('theme_sign_out')
                    . '</a>'
                . '</li>'
            . '</ul>'
        . '</li>';

    }

);