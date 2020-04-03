<?php
/**
 * General Inc
 *
 * PHP Version 7.2
 *
 * This files contains the hooks for
 * the User's Panel
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */

 // Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * The public method add_hook registers a hook
 * 
 * @since 0.0.7.9
 */
add_hook(
    'delete_social_post',
    function ($args) {

        // Get codeigniter object instance
        $CI = get_instance();

        // Load the Posts Model
        $CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Posts_model', 'posts_model' );
        
        // Delete the post's records
        $CI->posts_model->delete_post_records( $CI->user_id, $args['post_id'] ); 

    }

);

/**
 * The public method add_hook registers a hook
 * 
 * @since 0.0.7.9
 */
add_hook(
    'delete_user_media',
    function ($args) {

        // Get codeigniter object instance
        $CI = get_instance();

        // Load the Posts Model
        $CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Posts_model', 'posts_model' );
        
        // Delete the media's records
        $CI->posts_model->delete_media_records( $CI->user_id, $args['media_id'] ); 

    }

);

/**
 * The public method add_hook registers a hook
 * 
 * @since 0.0.7.9
 */
add_hook(
    'delete_network_group',
    function ($args) {

        // Get codeigniter object instance
        $CI = get_instance();

        // Load the Lists Model
        $CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Lists_model', 'lists_model' );
        
        // Delete the media's records
        $CI->lists_model->delete_group_records( $CI->user_id, $args['group_id'] ); 

    }

);

/**
 * The public method add_hook registers a hook
 * 
 * @since 0.0.7.9
 */
add_hook(
    'delete_network_account',
    function ($args) {

        // Get codeigniter object instance
        $CI = get_instance();

        // Load Networks Model
        $CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Networks_model', 'networks_model' );
        
        // Delete the account's records
        $CI->networks_model->delete_account_records( $CI->user_id, $args['account_id'] ); 

    }

);