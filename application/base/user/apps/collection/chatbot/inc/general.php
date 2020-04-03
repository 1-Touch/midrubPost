<?php
/**
 * General Inc
 *
 * PHP Version 7.3
 *
 * This files contains the hooks for
 * the Chatbot's app
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
 * @since 0.0.8.0
 */
add_hook(
    'delete_chatbot_category',
    function ($args) {

        // Verify if category's id exists
        if ( isset($args['category_id']) ) {

            // Get codeigniter object instance
            $CI = get_instance();

            // Use the Base's model to delete the category's records
            $CI->base_model->delete('chatbot_categories', array('category_id' => $args['category_id']));
            $CI->base_model->delete('chatbot_replies_categories', array('category_id' => $args['category_id']));
            $CI->base_model->delete('chatbot_subscribers_categories', array('category_id' => $args['category_id']));
            $CI->base_model->delete('chatbot_suggestions_categories', array('category_id' => $args['category_id']));
            $CI->base_model->delete('chatbot_pages_categories', array('category_id' => $args['category_id']));

        }

    }

);

/**
 * The public method add_hook registers a hook
 * 
 * @since 0.0.8.0
 */
add_hook(
    'delete_chatbot_suggestions_group',
    function ($args) {

        // Verify if group's id exists
        if ( isset($args['group_id']) ) {

            // Get codeigniter object instance
            $CI = get_instance();

            // Use the Base's model to delete the group's records
            $CI->base_model->delete('chatbot_pages_meta', array('meta_name' => 'selected_menu', 'meta_value' => $args['group_id']));

        }

    }

);

/**
 * The public method add_hook registers a hook
 * 
 * @since 0.0.8.0
 */
add_hook(
    'delete_network_account',
    function ($args) {

        // Verify if account's id exists
        if ( isset($args['account_id']) ) {

            // Get codeigniter object instance
            $CI = get_instance();

            // Use the Base's model to get all page's subscribers
            $get_subscribers = $CI->base_model->get_data_where(
                'chatbot_subscribers',
                'subscriber_id',
                array(
                    'page_id' => $args['account_id']
                )
            );

            // Verify if subscribers exists
            if ( $get_subscribers ) {

                // List subscribers
                foreach ( $get_subscribers as $subscriber ) {

                    // Delete subscriber's categories
                    $CI->base_model->delete('chatbot_subscribers_categories', array(
                        'subscriber_id' => $subscriber['subscriber_id']
                    ));

                }

            }

            // Use the Base's model to delete the page's records
            $CI->base_model->delete('chatbot_pages_categories', array('page_id' => $args['account_id']));
            $CI->base_model->delete('chatbot_pages_meta', array('page_id' => $args['account_id']));   
            $CI->base_model->delete('chatbot_subscribers', array('page_id' => $args['account_id']));
            $CI->base_model->delete('chatbot_subscribers_history', array('page_id' => $args['account_id']));

        }

    }

);

/* End of file general.php */