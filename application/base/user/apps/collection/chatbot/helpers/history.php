<?php
/**
 * History Helpers
 * 
 * PHP Version 7.3
 *
 * This file contains the class History
 * with methods to process the history data
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Chatbot\Helpers;

// Constats
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * History class provides the methods to process the history data
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
*/
class History {
    
    /**
     * Class variables
     *
     * @since 0.0.8.0
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.0
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();

        // Load the FB Chatbot Subcribers Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_CHATBOT . 'models/', 'Fb_chatbot_subscribers_model', 'fb_chatbot_subscribers_model' );
        
    }

    //-----------------------------------------------------
    // Main class's methods
    //-----------------------------------------------------

    /**
     * The public method load_history loads the chatbot's history
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function load_history() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');

            // Get data
            $key = $this->CI->input->post('key', TRUE);
            $page = $this->CI->input->post('page', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() === false ) {

                // Prepare the false response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('chatbot_no_conversations_found')
                );

                // Display the false response
                echo json_encode($data);
                
            } else {

                // If $page is false, set 1
                if (!$page) {
                    $page = 1;
                }

                // Set the limit
                $limit = 10;
                $page--;

                // Use the base model for a simply sql query
                $get_conversations = $this->CI->base_model->get_data_where(
                    'chatbot_subscribers_history',
                    'chatbot_subscribers_history.history_id, chatbot_subscribers.subscriber_id, chatbot_subscribers.net_id, chatbot_subscribers.name, networks.secret',
                    array(
                        'chatbot_subscribers.user_id' => $this->CI->user_id,
                        'chatbot_subscribers_history.source' => 'facebook_conversations'
                    ),
                    array(),
                    array('chatbot_subscribers.name' => $this->CI->db->escape_like_str($key)),
                    array(array(
                        'table' => 'chatbot_subscribers',
                        'condition' => 'chatbot_subscribers_history.subscriber_id=chatbot_subscribers.subscriber_id',
                        'join_from' => 'LEFT'
                    ), array(
                        'table' => 'networks',
                        'condition' => 'chatbot_subscribers.page_id=networks.network_id',
                        'join_from' => 'LEFT'
                    )),
                    array(
                        'order' => array('chatbot_subscribers_history.history_id', 'desc'),
                        'start' => ($page * $limit),
                        'limit' => $limit
                    )
                );

                // Verify if conversations exists
                if ( $get_conversations ) {

                    // Get total number of conversations with base model
                    $total = $this->CI->base_model->get_data_where(
                        'chatbot_subscribers_history',
                        'COUNT(chatbot_subscribers_history.history_id) AS total',
                        array(
                            'chatbot_subscribers.user_id' => $this->CI->user_id,
                            'chatbot_subscribers_history.source' => 'facebook_conversations'
                        ),
                        array(),
                        array('chatbot_subscribers.name' => $this->CI->db->escape_like_str($key)),
                        array(array(
                            'table' => 'chatbot_subscribers',
                            'condition' => 'chatbot_subscribers_history.subscriber_id=chatbot_subscribers.subscriber_id',
                            'join_from' => 'LEFT'
                        ), array(
                            'table' => 'networks',
                            'condition' => 'chatbot_subscribers.page_id=networks.network_id',
                            'join_from' => 'LEFT'
                        ))
                    );

                    // Create new conversations array
                    $conversations = array();

                    // List conversations
                    foreach ( $get_conversations as $conversation ) {

                        // Sub array
                        $sub_array = array(
                            'history_id' => $conversation['history_id'],
                            'subscriber_id' => $conversation['subscriber_id'],
                            'name' => $conversation['name']
                        );

                        // Get user's image
                        $image = get(MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . $conversation['net_id'] . '/picture?type=square&access_token=' . $conversation['secret']);

                        // Verify if user has image
                        if ($image !== FALSE) {

                            // Set user's image
                            $sub_array['image'] = MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . $conversation['net_id'] . '/picture?type=large&access_token=' . $conversation['secret'];

                        } else {

                            // Set default user's image
                            $sub_array['image'] = base_url('assets/img/avatar-placeholder.png');

                        }

                        // Add subscriber to array
                        $conversations[] = $sub_array;

                    }

                    // Prepare the response
                    $data = array(
                        'success' => TRUE,
                        'conversations' => $conversations,
                        'total' => $total[0]['total'],
                        'page' => ($page + 1),
                        'words' => array(
                            'details' => $this->CI->lang->line('chatbot_details')
                        )
                    );

                    // Display the response
                    echo json_encode($data);

                } else {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('chatbot_no_conversations_found')
                    );

                    // Display the false response
                    echo json_encode($data);

                }
                
            }
            
        }
        
    }
    
}

/* End of file history.php */