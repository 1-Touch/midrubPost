<?php
/**
 * Subscribers Helpers
 * 
 * PHP Version 7.3
 *
 * This file contains the class Subscribers
 * with methods to process the subscribers data
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
 * Subscribers class provides the methods to process the subscribers data
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
*/
class Subscribers {
    
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
     * The public method load_subscribers loads subscribers
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function load_subscribers() {

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
                    'message' => $this->CI->lang->line('chatbot_no_subscribers_found')
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
                $get_subscribers = $this->CI->base_model->get_data_where(
                    'chatbot_subscribers',
                    'chatbot_subscribers.subscriber_id, chatbot_subscribers.net_id, chatbot_subscribers.name, networks.secret',
                    array(
                        'chatbot_subscribers.user_id' => $this->CI->user_id
                    ),
                    array(),
                    array('chatbot_subscribers.name' => $this->CI->db->escape_like_str($key)),
                    array(array(
                        'table' => 'networks',
                        'condition' => 'chatbot_subscribers.page_id=networks.network_id',
                        'join_from' => 'LEFT'
                    )),
                    array(
                        'order' => array('chatbot_subscribers.subscriber_id', 'desc'),
                        'start' => ($page * $limit),
                        'limit' => $limit
                    )
                );

                // Verify if subscribers exists
                if ( $get_subscribers ) {

                    // Get total number of subscribers with base model
                    $total = $this->CI->base_model->get_data_where(
                        'chatbot_subscribers',
                        'COUNT(subscriber_id) AS total',
                        array(
                            'user_id' => $this->CI->user_id
                        ),
                        array(),
                        array('name' => $this->CI->db->escape_like_str($key)),
                        array(),
                        array()
                    );

                    // Create new subscribers array
                    $subscribers = array();

                    // List subscribers
                    foreach ( $get_subscribers as $subscriber ) {

                        // Sub array
                        $sub_array = array(
                            'subscriber_id' => $subscriber['subscriber_id'],
                            'name' => $subscriber['name']
                        );

                        // Get user's image
                        $image = get(MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . $subscriber['net_id'] . '/picture?type=square&access_token=' . $subscriber['secret']);

                        // Verify if user has image
                        if ($image !== FALSE) {

                            // Set user's image
                            $sub_array['image'] = MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . $subscriber['net_id'] . '/picture?type=large&access_token=' . $subscriber['secret'];

                        } else {

                            // Set default user's image
                            $sub_array['image'] = base_url('assets/img/avatar-placeholder.png');

                        }

                        // Add subscriber to array
                        $subscribers[] = $sub_array;

                    }

                    // Prepare the response
                    $data = array(
                        'success' => TRUE,
                        'subscribers' => $subscribers,
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
                        'message' => $this->CI->lang->line('chatbot_no_subscribers_found')
                    );

                    // Display the false response
                    echo json_encode($data);

                }
                
            }
            
        }
        
    }

    /**
     * The public method load_reply_subscribers loads subscribers for a reply
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function load_reply_subscribers() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('reply_id', 'Reply ID', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');

            // Get data
            $reply_id = $this->CI->input->post('reply_id', TRUE);
            $page = $this->CI->input->post('page', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() === false ) {

                // Prepare the false response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('chatbot_no_subscribers_found')
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
                $get_subscribers = $this->CI->base_model->get_data_where(
                    'chatbot_subscribers',
                    'chatbot_subscribers.subscriber_id, chatbot_subscribers.net_id, chatbot_subscribers.name, networks.secret, chatbot_subscribers_history.history_id, chatbot_subscribers_history.created',
                    array(
                        'chatbot_subscribers.user_id' => $this->CI->user_id,
                        'chatbot_subscribers_history.reply_id' => $reply_id,
                        'chatbot_subscribers_history.source' => 'facebook_conversations'
                    ),
                    array(),
                    array(),
                    array(array(
                        'table' => 'networks',
                        'condition' => 'chatbot_subscribers.page_id=networks.network_id',
                        'join_from' => 'LEFT'
                    ), array(
                        'table' => 'chatbot_subscribers_history',
                        'condition' => 'chatbot_subscribers.subscriber_id=chatbot_subscribers_history.subscriber_id',
                        'join_from' => 'LEFT'
                    )),
                    array(
                        'order' => array('chatbot_subscribers.subscriber_id', 'desc'),
                        'start' => ($page * $limit),
                        'limit' => $limit
                    )
                );

                // Verify if subscribers exists
                if ( $get_subscribers ) {

                    // Get total number of subscribers with base model
                    $total = $this->CI->base_model->get_data_where(
                        'chatbot_subscribers',
                        'COUNT(chatbot_subscribers.subscriber_id) AS total',
                        array(
                            'chatbot_subscribers.user_id' => $this->CI->user_id,
                            'chatbot_subscribers_history.reply_id' => $reply_id,
                            'chatbot_subscribers_history.source' => 'facebook_conversations'
                        ),
                        array(),
                        array(),
                        array(array(
                            'table' => 'chatbot_subscribers_history',
                            'condition' => 'chatbot_subscribers.subscriber_id=chatbot_subscribers_history.subscriber_id',
                            'join_from' => 'LEFT'
                        )),
                        array()
                    );

                    // Create new subscribers array
                    $subscribers = array();

                    // List subscribers
                    foreach ( $get_subscribers as $subscriber ) {

                        // Sub array
                        $sub_array = array(
                            'subscriber_id' => $subscriber['subscriber_id'],
                            'name' => $subscriber['name'],
                            'created' => $subscriber['created'],
                            'history_id' => $subscriber['history_id']
                        );

                        // Get user's image
                        $image = get(MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . $subscriber['net_id'] . '/picture?type=square&access_token=' . $subscriber['secret']);

                        // Verify if user has image
                        if ($image !== FALSE) {

                            // Set user's image
                            $sub_array['image'] = MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . $subscriber['net_id'] . '/picture?type=large&access_token=' . $subscriber['secret'];

                        } else {

                            // Set default user's image
                            $sub_array['image'] = base_url('assets/img/avatar-placeholder.png');

                        }

                        // Add subscriber to array
                        $subscribers[] = $sub_array;

                    }

                    // Prepare the response
                    $data = array(
                        'success' => TRUE,
                        'subscribers' => $subscribers,
                        'total' => $total[0]['total'],
                        'page' => ($page + 1),
                        'date' => time()
                    );

                    // Display the response
                    echo json_encode($data);

                } else {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('chatbot_no_subscribers_found')
                    );

                    // Display the false response
                    echo json_encode($data);

                }
                
            }
            
        }
        
    }

    /**
     * The public method get_all_subscriber_categories gets subscribers categories
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function get_all_subscriber_categories() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('subscriber_id', 'Subscriber ID', 'trim|numeric|required');

            // Get data
            $subscriber_id = $this->CI->input->post('subscriber_id', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() === false ) {

                // Prepare the false response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('chatbot_no_valid_subscriber')
                );

                // Display the false response
                echo json_encode($data);
                
            } else {

                // Use the base model to get all subscriber's categories
                $categories = $this->CI->base_model->get_data_where(
                    'chatbot_subscribers_categories',
                    '*',
                    array(
                        'subscriber_id' => $subscriber_id
                    )
                );

                // Verify if categories exists
                if ( $categories ) {

                    // Prepare the response
                    $data = array(
                        'success' => TRUE,
                        'categories' => $categories
                    );

                    // Display the response
                    echo json_encode($data);

                } else {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE
                    );

                    // Display the false response
                    echo json_encode($data);

                }
                
            }
            
        }
        
    }

    /**
     * Selects/Unselects Subscriber Category
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function select_subscriber_category() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('subscriber_id', 'Subscriber ID', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('category_id', 'Category ID', 'trim|numeric|required');

            // Get data
            $subscriber_id = $this->CI->input->post('subscriber_id', TRUE);
            $category_id = $this->CI->input->post('category_id', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() === false ) {

                // Prepare the false response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('chatbot_subscriber_or_category_wrong')
                );

                // Display the false response
                echo json_encode($data);
                
            } else {

                // Use the base model to verify if user is the owner of the subscriber
                $get_subscriber = $this->CI->base_model->get_data_where(
                    'chatbot_subscribers',
                    '*',
                    array(
                        'subscriber_id' => $subscriber_id,
                        'user_id' => $this->CI->user_id
                    )
                );

                // Verify if user is owner of the subscriber
                if ( $get_subscriber ) {

                    // Use the base model to verify if user is the owner of the category
                    $get_category = $this->CI->base_model->get_data_where(
                        'chatbot_categories',
                        '*',
                        array(
                            'category_id' => $category_id,
                            'user_id' => $this->CI->user_id
                        )
                    );

                    // Verify if the category and user exists
                    if ( $get_category ) {

                        // Prepare the success response
                        $data = array(
                            'success' => TRUE,
                            'category_id' => $category_id
                        );

                        // Display the success response
                        echo json_encode($data);

                    } else {

                        // Prepare the false response
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('chatbot_you_are_not_owner_category')
                        );

                        // Display the false response
                        echo json_encode($data);
                        
                    }

                } else {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('chatbot_no_valid_subscriber')
                    );

                    // Display the false response
                    echo json_encode($data);

                } 

            }

        }
        
    }

    /**
     * Gets the subscriber's messages
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function get_all_subscriber_messages() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('subscriber_id', 'Subscriber ID', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');

            // Get data
            $subscriber_id = $this->CI->input->post('subscriber_id', TRUE);
            $page = $this->CI->input->post('page', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() === false ) {

                // Prepare the false response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('chatbot_no_messages_found')
                );

                // Display the false response
                echo json_encode($data);
                
            } else {

                // Use the base model to verify if user is the owner of the subscriber
                $get_subscriber = $this->CI->base_model->get_data_where(
                    'chatbot_subscribers',
                    '*',
                    array(
                        'subscriber_id' => $subscriber_id,
                        'user_id' => $this->CI->user_id
                    )
                );

                // Verify if user is owner of the subscriber
                if ( !$get_subscriber ) {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('chatbot_no_messages_found')
                    );

                    // Display the false response
                    echo json_encode($data);
                    exit();

                }

                // If $page is false, set 1
                if (!$page) {
                    $page = 1;
                }

                // Set the limit
                $limit = 10;
                $page--;

                // Use the base model for a simply sql query
                $get_messages = $this->CI->base_model->get_data_where(
                    'chatbot_subscribers_history',
                    'chatbot_subscribers_history.history_id, chatbot_subscribers_history.question, chatbot_subscribers_history.response, chatbot_subscribers_history.group_id, chatbot_subscribers_history.type, chatbot_subscribers_history.created, chatbot_groups.group_name',
                    array(
                        'chatbot_subscribers_history.subscriber_id' => $subscriber_id
                    ),
                    array(),
                    array(),
                    array(array(
                        'table' => 'chatbot_groups',
                        'condition' => 'chatbot_subscribers_history.group_id=chatbot_groups.group_id',
                        'join_from' => 'LEFT'
                    )),
                    array(
                        'order' => array('history_id', 'desc'),
                        'start' => ($page * $limit),
                        'limit' => $limit
                    )
                );

                // Verify if messages exists
                if ( $get_messages ) {

                    // Get total number of replies with base model
                    $total = $this->CI->base_model->get_data_where(
                        'chatbot_subscribers_history',
                        'COUNT(history_id) AS total',
                        array(
                            'subscriber_id' => $subscriber_id
                        )
                    );

                    // Prepare the response
                    $data = array(
                        'success' => TRUE,
                        'messages' => $get_messages,
                        'total' => $total[0]['total'],
                        'page' => ($page + 1),
                        'date' => time(),
                        'words' => array(
                            'messages' => $this->CI->lang->line('chatbot_message_reply'),
                            'group_deleted' => $this->CI->lang->line('chatbot_group_deleted')
                        )
                    );

                    // Display the response
                    echo json_encode($data);

                } else {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('chatbot_no_messages_found')
                    );

                    // Display the false response
                    echo json_encode($data);

                }
                
            }
            
        }
        
    }

    /**
     * The public method save_subscriber_categories removes or adds categories to a user
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function save_subscriber_categories() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('subscriber_id', 'Subscriber ID', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('categories', 'categories', 'trim');

            // Get data
            $subscriber_id = $this->CI->input->post('subscriber_id', TRUE);
            $categories = $this->CI->input->post('categories', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() === false ) {

                // Prepare the false response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('chatbot_no_valid_subscriber')
                );

                // Display the false response
                echo json_encode($data);
                
            } else {

                // Use the base model to verify if user is the owner of the subscriber
                $get_subscriber = $this->CI->base_model->get_data_where(
                    'chatbot_subscribers',
                    '*',
                    array(
                        'subscriber_id' => $subscriber_id,
                        'user_id' => $this->CI->user_id
                    )
                );

                // Verify if user is owner of the subscriber
                if ( !$get_subscriber ) {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('chatbot_no_valid_subscriber')
                    );

                    // Display the false response
                    echo json_encode($data);
                    exit();

                }

                // Check deletion
                $check_deletion = FALSE;

                // Delete all subscriber's categories
                if ( $this->CI->base_model->delete('chatbot_subscribers_categories', array('subscriber_id' => $subscriber_id) ) ) {
                    $check_deletion = TRUE;
                }

                // If $check_deletion is false and $categories is empty means an error has occurred
                if ( ( $check_deletion === FALSE ) && !$categories ) {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('chatbot_error_occurred_no_categories_deleted')
                    );

                    // Display the false response
                    echo json_encode($data);                

                } else {

                    // Verify if categories exists
                    if ( $categories ) {

                        // Count categories
                        $count = 0;

                        // List all categories and verify if user is the owner
                        foreach ($categories as $category) {

                            // Category should be numeric
                            if (is_numeric($category)) {

                                // Use the base model to verify if user has this category
                                $get_category = $this->CI->base_model->get_data_where(
                                    'chatbot_categories',
                                    'category_id',
                                    array(
                                        'category_id' => $category,
                                        'user_id' => $this->CI->user_id
                                    )
                                );

                                // Verify if category exists
                                if ($get_category) {

                                    // Prepare the Category
                                    $category = array(
                                        'subscriber_id' => $subscriber_id,
                                        'category_id' => $category,
                                    );

                                    // Save the Category
                                    if ($this->CI->base_model->insert('chatbot_subscribers_categories', $category)) {
                                        $count++;
                                    }

                                }

                            }
                            
                        }

                        // Verify if at least one category were saved
                        if ( $count > 0 ) {

                            // Prepare the true response
                            $data = array(
                                'success' => TRUE,
                                'message' => $this->CI->lang->line('chatbot_changes_were_saved')
                            );

                            // Display the true response
                            echo json_encode($data);
                            
                        } else {

                            // Prepare the false response
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('chatbot_changes_were_not_saved')
                            );

                            // Display the false response
                            echo json_encode($data);

                        }

                    } else {

                        // Prepare the true response
                        $data = array(
                            'success' => TRUE,
                            'message' => $this->CI->lang->line('chatbot_changes_were_saved')
                        );

                        // Display the true response
                        echo json_encode($data);

                    }

                }
                
            }
            
        }
        
    }
    
}

/* End of file subsscribers.php */