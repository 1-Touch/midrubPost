<?php
/**
 * Replies Helpers
 * 
 * PHP Version 7.3
 *
 * This file contains the class Replies
 * with methods to process the replies data
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
 * Replies class provides the methods to process the replies data
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
*/
class Replies {
    
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
        
        // Load the FB Chatbot Replies Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_CHATBOT . 'models/', 'Fb_chatbot_replies_model', 'fb_chatbot_replies_model' );
        
    }

    //-----------------------------------------------------
    // Main class's methods
    //-----------------------------------------------------
    
    /**
     * The public method save_reply saves a reply
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function save_reply() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('keywords', 'Keywords', 'trim|required');
            $this->CI->form_validation->set_rules('response_type', 'Response Type', 'trim');
            $this->CI->form_validation->set_rules('message', 'Message', 'trim');
            $this->CI->form_validation->set_rules('group', 'Group', 'trim');

            // Get data
            $keywords = $this->CI->input->post('keywords', TRUE);
            $response_type = $this->CI->input->post('response_type', TRUE);
            $message = $this->CI->input->post('message', TRUE);
            $group = $this->CI->input->post('group', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() === false ) {

                // Prepare no category found message
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('chatbot_keywords_too_short')
                );

                // Display response
                echo json_encode($data);
                exit();
                
            } else {

                // Try to create the reply's parameters
                $reply = array(
                    'user_id' => $this->CI->user_id,
                    'body' => $keywords,
                    'accuracy' => 100,
                    'created' => time()
                );

                // Save Reply's parameters by using the Base's Model
                $reply_id = $this->CI->base_model->insert('chatbot_replies', $reply);

                // Verify if the reply was saved
                if ( $reply_id ) {

                    // Try to create the reply's response
                    $response = array(
                        'reply_id' => $reply_id,
                        'type' => $response_type
                    );

                    // Verify what kind of response has the reply's response
                    if ( $response_type === '1' ) {

                        $response['body'] = $message;

                    } else if ( $response_type === '2' ) {

                        $response['group_id'] = $group;

                    }

                    // Save Reply's response by using the Base's Model
                    $response_id = $this->CI->base_model->insert('chatbot_replies_response', $response);

                    // Verify if the response was saved
                    if ( $response_id ) {

                        // Prepare the success message
                        $data = array(
                            'success' => TRUE,
                            'message' => $this->CI->lang->line('chatbot_reply_was_saved')
                        );

                        // Display error message
                        echo json_encode($data);

                    } else {

                        // Prepare the success message
                        $data = array(
                            'success' => TRUE,
                            'message' => $this->CI->lang->line('chatbot_reply_was_saved_without_response')
                        );

                        // Display success message
                        echo json_encode($data);

                    }
                    
                } else {

                    // Prepare the error message
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('chatbot_reply_was_not_saved')
                    );

                    // Display error message
                    echo json_encode($data);

                }

                exit();
                
            }
            
        }

        // Prepare the error message
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('chatbot_error_occurred')
        );

        // Display error message
        echo json_encode($data);
        
    }

    /**
     * The public method update_reply updates a reply
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function update_reply() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('reply_id', 'Reply ID', 'trim');
            $this->CI->form_validation->set_rules('keywords', 'Keywords', 'trim|required');
            $this->CI->form_validation->set_rules('accuracy', 'Accuracy', 'trim');
            $this->CI->form_validation->set_rules('response_type', 'Response Type', 'trim');
            $this->CI->form_validation->set_rules('message', 'Message', 'trim');
            $this->CI->form_validation->set_rules('group', 'Group', 'trim');
            $this->CI->form_validation->set_rules('categories', 'Categories', 'trim');

            // Get data
            $reply_id = $this->CI->input->post('reply_id', TRUE);
            $keywords = $this->CI->input->post('keywords', TRUE);
            $accuracy = $this->CI->input->post('accuracy', TRUE);
            $response_type = $this->CI->input->post('response_type', TRUE);
            $message = $this->CI->input->post('message', TRUE);
            $group = $this->CI->input->post('group', TRUE);
            $categories = $this->CI->input->post('categories', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() === false ) {

                // Prepare no category found message
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('chatbot_keywords_too_short')
                );

                // Display response
                echo json_encode($data);
                exit();
                
            } else {

                // Verify if the Reply ID is numeric
                if ( is_numeric($reply_id) ) {

                    // Use the base model for a simply sql query
                    $get_reply = $this->CI->base_model->get_data_where(
                        'chatbot_replies',
                        'reply_id',
                        array(
                            'reply_id' => $reply_id,
                            'user_id' => $this->CI->user_id
                        )
                    );

                    // Verify if the reply is of the current user
                    if ( $get_reply ) {

                        // Prepare the reply's keywords
                        $reply = array(
                            'body' => $keywords
                        );

                        // Verify if accuracy exists
                        if ( is_numeric($accuracy) && ( $accuracy > 9 && $accuracy < 101 ) ) {

                            // Set accuracy
                            $reply['accuracy'] = $accuracy;

                        } else {

                            // Set accuracy
                            $reply['accuracy'] = 100;
                            
                        }

                        // Try to update the reply
                        $this->CI->base_model->update_ceil('chatbot_replies', array('reply_id' => $reply_id), $reply);

                        // Use the base model for a simply sql query
                        $get_updated_reply = $this->CI->base_model->get_data_where(
                            'chatbot_replies',
                            '*',
                            array(
                                'reply_id' => $reply_id,
                                'user_id' => $this->CI->user_id
                            )
                        );

                        // Very if keywords were updated
                        if ( ($get_updated_reply[0]['body'] === $reply['body']) && ($get_updated_reply[0]['accuracy'] === $reply['accuracy']) ) {

                            // Delete the Reply's responses
                            $this->CI->base_model->delete('chatbot_replies_response', array('reply_id' => $reply_id));

                            // Verify which kind of response has the reply
                            switch ( $response_type ) {

                                case '1':

                                    // Try to create the reply's response
                                    $response = array(
                                        'reply_id' => $reply_id,
                                        'body' => $message,
                                        'type' => $response_type
                                    );

                                    // Save Reply's response by using the Base's Model
                                    $response_id = $this->CI->base_model->insert('chatbot_replies_response', $response);

                                    // If reply's response wasn't saved notify the user
                                    if ( !$response_id ) {

                                        // Prepare the error message
                                        $data = array(
                                            'success' => FALSE,
                                            'message' => $this->CI->lang->line('chatbot_reply_was_updated_successfully_without_response')
                                        );

                                        // Display error message
                                        echo json_encode($data);
                                        exit();

                                    }

                                    break;

                                case '2':

                                    // Verify if group exists
                                    if ( $group ) {

                                        // Try to create the reply's response
                                        $response = array(
                                            'reply_id' => $reply_id,
                                            'group_id' => $group,
                                            'type' => $response_type
                                        );

                                        // Save Reply's response by using the Base's Model
                                        $response_id = $this->CI->base_model->insert('chatbot_replies_response', $response);

                                        // If reply's response wasn't saved notify the user
                                        if ( !$response_id ) {

                                            // Prepare the error message
                                            $data = array(
                                                'success' => FALSE,
                                                'message' => $this->CI->lang->line('chatbot_reply_was_updated_successfully_without_response')
                                            );

                                            // Display error message
                                            echo json_encode($data);
                                            exit();

                                        }

                                    }

                                    break;

                            }

                            // Delete the reply's categories
                            $this->CI->base_model->delete('chatbot_replies_categories', array('reply_id' => $reply_id));

                            // Verify if categories exists
                            if ( $categories ) {

                                // Count categories
                                $count = 0;

                                // List all categories
                                foreach ($categories as $category_id) {

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

                                        // Prepare the Category
                                        $category = array(
                                            'reply_id' => $reply_id,
                                            'category_id' => $category_id
                                        );

                                        // Save the Category
                                        if ($this->CI->base_model->insert('chatbot_replies_categories', $category)) {
                                            $count++;
                                        }

                                    }

                                }

                                // Verify if all categories were saved
                                if (count($categories) > $count) {

                                    // Prepare the error message
                                    $data = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('chatbot_reply_was_updated_successfully_without_categories')
                                    );

                                    // Display error message
                                    echo json_encode($data);
                                    exit();
                                    
                                }

                            }

                            // Prepare the success message
                            $data = array(
                                'success' => TRUE,
                                'message' => $this->CI->lang->line('chatbot_reply_was_updated_successfully')
                            );

                            // Display success message
                            echo json_encode($data);

                        } else {

                            // Prepare the error message
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('chatbot_reply_was_not_updated_successfully')
                            );

                            // Display error message
                            echo json_encode($data);

                        }

                    } else {

                        // Prepare the error message
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('chatbot_reply_id_missing')
                        );

                        // Display error message
                        echo json_encode($data);

                    }
                    
                } else {

                    // Prepare the error message
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('chatbot_reply_id_missing')
                    );

                    // Display error message
                    echo json_encode($data);

                }

                exit();
                
            }
            
        }

        // Prepare the error message
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('chatbot_error_occurred')
        );

        // Display error message
        echo json_encode($data);
        
    }

    /**
     * The public method load_replies loads replies
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function load_replies() {

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
                    'message' => $this->CI->lang->line('chatbot_no_replies_found')
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
                $get_replies = $this->CI->base_model->get_data_where(
                    'chatbot_replies',
                    'reply_id, body',
                    array(
                        'user_id' => $this->CI->user_id
                    ),
                    array(),
                    array('body' => $this->CI->db->escape_like_str($key)),
                    array(),
                    array(
                        'order' => array('reply_id', 'desc'),
                        'start' => ($page * $limit),
                        'limit' => $limit
                    )
                );

                // Verify if replies exists
                if ( $get_replies ) {

                    // Get total number of replies with base model
                    $total = $this->CI->base_model->get_data_where(
                        'chatbot_replies',
                        'COUNT(reply_id) AS total',
                        array(
                            'user_id' => $this->CI->user_id
                        ),
                        array(),
                        array('body' => $this->CI->db->escape_like_str($key)),
                        array(),
                        array()
                    );

                    // Prepare the response
                    $data = array(
                        'success' => TRUE,
                        'replies' => $get_replies,
                        'total' => $total[0]['total'],
                        'page' => ($page + 1)
                    );

                    // Display the response
                    echo json_encode($data);

                } else {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('chatbot_no_replies_found')
                    );

                    // Display the false response
                    echo json_encode($data);

                }
                
            }
            
        }
        
    }

    /**
     * The public method check_for_replies verifies if user has replies
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function check_for_replies() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Use the base model for a simply sql query
            $get_replies = $this->CI->base_model->get_data_where(
                'chatbot_replies',
                'reply_id, body',
                array(
                    'user_id' => $this->CI->user_id
                )
            );

            // Verify if replies exists
            if ($get_replies) {

                // Prepare the response
                $data = array(
                    'success' => TRUE
                );

                // Display the response
                echo json_encode($data);

            } else {

                // Prepare the false response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('chatbot_no_replies_found')
                );

                // Display the false response
                echo json_encode($data);

            }
            
        }
        
    }

    /**
     * The public method delete_replies deletes replies
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function delete_replies() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('replies', 'Replies', 'trim');
            
            // Get data
            $replies = $this->CI->input->post('replies');

            // Verify if request is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Default count
                $count = 0;

                // List all replies
                foreach ( $replies as $reply ) {

                    // Verify if the reply id is numeric
                    if (is_numeric($reply[1])) {

                        // Try to delete reply
                        if ( $this->CI->base_model->delete('chatbot_replies', array('reply_id' => $reply[1], 'user_id' => $this->CI->user_id)) ) {

                            // Delete the reply response
                            $this->CI->base_model->delete('chatbot_replies_response', array('reply_id' => $reply[1]) );

                            // Increase count
                            $count++;

                        }

                    }

                }

                if ( $count ) {

                    $data = array(
                        'success' => TRUE,
                        'message' => $count . $this->CI->lang->line('chatbot_replies_were_deleted')
                    );

                    echo json_encode($data);

                } else {

                    $data = array(
                        'success' => FALSE,
                        'message' => '0' . $this->CI->lang->line('chatbot_replies_were_deleted')
                    );

                    echo json_encode($data);

                }
                
                exit();
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('chatbot_error_occurred')
        );

        echo json_encode($data);
        
    }

    /**
     * The public method replies_for_graph loads replies for graph
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function replies_for_graph() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('page_id', 'Page ID', 'trim');

            // Get data
            $page_id = $this->CI->input->post('page_id', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Loads replies by popularity
                $replies = $this->CI->fb_chatbot_replies_model->replies_for_graph($this->CI->user_id, $page_id);

                // Verify if replies exists
                if ( $replies ) {

                    // Prepare the success response
                    $data = array(
                        'success' => TRUE,
                        'replies' => $replies,
                        'words' => array(
                            'number_bot_replies' => $this->CI->lang->line('chatbot_number_bot_replies')
                        )
                    );

                    // Display the success response
                    echo json_encode($data);
                    exit();

                }

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'words' => array(
                'number_bot_replies' => $this->CI->lang->line('chatbot_number_bot_replies')
            )
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method dashboard_replies_for_graph loads replies for graph
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function dashboard_replies_for_graph() {

        // Loads replies by popularity
        $replies = $this->CI->fb_chatbot_replies_model->replies_for_dashboard_graph($this->CI->user_id);

        // Verify if replies exists
        if ($replies) {

            // Prepare the success response
            $data = array(
                'success' => TRUE,
                'replies' => $replies,
                'words' => array(
                    'number_bot_activities' => $this->CI->lang->line('chatbot_number_bot_activities')
                )
            );

            // Display the success response
            echo json_encode($data);
            
        } else {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'words' => array(
                    'number_bot_activities' => $this->CI->lang->line('chatbot_number_bot_activities')
                )
            );

            // Display the false response
            echo json_encode($data);

        }
        
    }

    /**
     * The public method dashboard_total_replies_for_graph loads all replies for graph
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function dashboard_total_replies_for_graph() {

        // Loads replies by popularity
        $replies = $this->CI->fb_chatbot_replies_model->total_replies_for_dashboard_graph($this->CI->user_id);

        // Verify if replies exists
        if ($replies) {

            // Prepare the success response
            $data = array(
                'success' => TRUE,
                'replies' => $replies
            );

            // Display the success response
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

    /**
     * The public method reply_activity_graph loads activities for a reply
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function reply_activity_graph() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('reply_id', 'Reply ID', 'trim|numeric|required');

            // Get data
            $reply_id = $this->CI->input->post('reply_id', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Loads reply's activity for graph
                $activities = $this->CI->fb_chatbot_replies_model->reply_activity_graph($this->CI->user_id, $reply_id);

                // Verify if activity exists
                if ( $activities ) {

                    // Prepare the success response
                    $data = array(
                        'success' => TRUE,
                        'activities' => $activities,
                        'words' => array(
                            'number_bot_replies' => $this->CI->lang->line('chatbot_number_bot_replies')
                        )
                    );

                    // Display the success response
                    echo json_encode($data);
                    exit();

                }

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'words' => array(
                'number_bot_replies' => $this->CI->lang->line('chatbot_number_bot_replies')
            )
        );

        // Display the false response
        echo json_encode($data);
        
    }
    
    /**
     * The public method replies_by_popularity loads replies by popularity
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function replies_by_popularity() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('page_id', 'Page ID', 'trim');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');

            // Get data
            $page_id = $this->CI->input->post('page_id', TRUE);
            $page = $this->CI->input->post('page', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // If $page is false, set 1
                if (!$page) {
                    $page = 1;
                }

                // Decrease the page
                $page--;

                // Loads replies by popularity
                $replies = $this->CI->fb_chatbot_replies_model->replies_by_popularity($this->CI->user_id, $page_id, $page);

                // Verify if replies exists
                if ( $replies ) {

                    // Calculate total number of replies
                    $total_replies = $this->CI->fb_chatbot_replies_model->replies_by_popularity($this->CI->user_id, $page_id);

                    // Prepare the success response
                    $data = array(
                        'success' => TRUE,
                        'replies' => $replies,
                        'total' => count($total_replies),
                        'page' => ($page + 1)
                    );

                    // Display the success response
                    echo json_encode($data);
                    exit();

                }

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('chatbot_no_replies_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }
    
}

/* End of file replies.php */