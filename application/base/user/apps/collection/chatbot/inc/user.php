<?php
/**
 * User Inc
 *
 * PHP Version 7.3
 *
 * This file contains the function
 * to delete the user's data from Midrub Facebook Chatbot
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */

 // Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

if ( !function_exists('delete_user_from_facebook_chatbot') ) {

    /**
     * The function delete_user_from_facebook_chatbot deletes the user's records from Midrub Facebook Chatbot
     * 
     * @param integer $user_id has the user's ID
     * 
     * @return void
     */
    function delete_user_from_facebook_chatbot($user_id) {

        // Get codeigniter object instance
        $CI = get_instance();

        // Load Base Model
        $CI->load->ext_model( MIDRUB_BASE_PATH . 'models/', 'Base_model', 'base_model' );

        // Use the Base's model to get all user's categories
        $get_categories = $CI->base_model->get_data_where(
            'chatbot_categories',
            'category_id',
            array(
                'user_id' => $user_id
            )
        );

        // Verify if categories exists
        if ( $get_categories ) {

            // List categories
            foreach ( $get_categories as $category ) {

                // Use the Base's model to delete the category's records
                $CI->base_model->delete('chatbot_categories', array('category_id' => $category['category_id']));
                $CI->base_model->delete('chatbot_subscribers_categories', array('category_id' => $category['category_id']));
                $CI->base_model->delete('chatbot_suggestions_categories', array('category_id' => $category['category_id']));
                $CI->base_model->delete('chatbot_replies_categories', array('category_id' => $category['category_id']));
                $CI->base_model->delete('chatbot_pages_categories', array('category_id' => $category['category_id']));

            }

        }

        // Use the Base's model to delete the Chatbot's groups
        $CI->base_model->delete('chatbot_groups', array('user_id' => $user_id));
        
        // Use the Base's model to delete the Chatbot's Pages meta
        $CI->base_model->delete('chatbot_pages_meta', array('user_id' => $user_id));

        // Use the Base's model to delete the Chatbot's Phone Numbers
        $CI->base_model->delete('chatbot_phone_numbers', array('user_id' => $user_id));

        // Use the Base's model to delete the Chatbot's Email Addresses
        $CI->base_model->delete('chatbot_email_addresses', array('user_id' => $user_id));
        
        // Use the Base's model to get all user's replies
        $get_replies = $CI->base_model->get_data_where(
            'chatbot_replies',
            'reply_id',
            array(
                'user_id' => $user_id
            )
        );

        // Verify if replies exists
        if ( $get_replies ) {

            // List replies
            foreach ( $get_replies as $reply ) {

                // Use the Base's model to delete the reply's records
                $CI->base_model->delete('chatbot_replies', array('reply_id' => $reply['reply_id']));
                $CI->base_model->delete('chatbot_replies_response', array('reply_id' => $reply['reply_id']));

            }

        }

        // Use the Base's model to get all user's subscribers
        $get_subscribers = $CI->base_model->get_data_where(
            'chatbot_subscribers',
            'subscriber_id',
            array(
                'user_id' => $user_id
            )
        );

        // Verify if subscribers exists
        if ($get_subscribers) {

            // List subscribers
            foreach ($get_subscribers as $subscriber) {

                // Delete user's subscribers
                $CI->base_model->delete('chatbot_subscribers', array(
                    'subscriber_id' => $subscriber['subscriber_id']
                ));

                // Delete subscriber's history
                $CI->base_model->delete('chatbot_subscribers_history', array(
                    'subscriber_id' => $subscriber['subscriber_id']
                ));                

            }

        }

        // Use the Base's model to get all user's suggestions
        $get_suggestions = $CI->base_model->get_data_where(
            'chatbot_suggestions',
            'suggestion_id',
            array(
                'user_id' => $user_id
            )
        );

        // Verify if suggestions exists
        if ( $get_suggestions ) {

            // List suggestions
            foreach ($get_suggestions as $suggestion) {

                // Delete user's suggestions
                $CI->base_model->delete('chatbot_suggestions', array(
                    'suggestion_id' => $suggestion['suggestion_id']
                ));

                // Delete user's suggestions meta
                $CI->base_model->delete('chatbot_suggestions_meta', array(
                    'suggestion_id' => $suggestion['suggestion_id']
                ));                

            }

        }        
        
    }

}

/* End of file user.php */