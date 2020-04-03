<?php
/**
 * Phone Numbers Helpers
 * 
 * PHP Version 7.3
 *
 * This file contains the class Phone Numbers
 * with methods to process the phone numbers data
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.1
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Chatbot\Helpers;

// Constats
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Phone_numbers class provides the methods to process the phone numbers data
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.1
*/
class Phone_numbers {
    
    /**
     * Class variables
     *
     * @since 0.0.8.1
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.1
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Load the FB Chatbot Phone Numbers Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_CHATBOT . 'models/', 'Fb_chatbot_phone_numbers_model', 'fb_chatbot_phone_numbers_model' );
        
    }

    //-----------------------------------------------------
    // Main class's methods
    //-----------------------------------------------------

    /**
     * The public method load_phone_numbers loads phone numbers
     * 
     * @since 0.0.8.1
     * 
     * @return void
     */ 
    public function load_phone_numbers() {

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
                    'message' => $this->CI->lang->line('chatbot_no_phone_numbers_found')
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
                $get_phone_numbers = $this->CI->base_model->get_data_where(
                    'chatbot_phone_numbers',
                    'phone_id, history_id, body, new',
                    array(
                        'user_id' => $this->CI->user_id
                    ),
                    array(),
                    array('body' => $this->CI->db->escape_like_str($key)),
                    array(),
                    array(
                        'order' => array('phone_id', 'desc'),
                        'start' => ($page * $limit),
                        'limit' => $limit
                    )
                );

                // Verify if phone numbers exists
                if ( $get_phone_numbers ) {

                    // List all phone numbers
                    foreach ( $get_phone_numbers as $phone_number ) {

                        // Verify if if a new phone number
                        if ( $phone_number['new'] > 0 ) {

                            // Save as seen
                            $this->CI->base_model->update_ceil('chatbot_phone_numbers', array(
                                'phone_id' => $phone_number['phone_id']
                            ), array(
                                'new' => 0
                            ));

                        }

                    }

                    // Get total number of phone numbers with base model
                    $total = $this->CI->base_model->get_data_where(
                        'chatbot_phone_numbers',
                        'COUNT(phone_id) AS total',
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
                        'phone_numbers' => $get_phone_numbers,
                        'total' => $total[0]['total'],
                        'page' => ($page + 1)
                    );

                    // Display the response
                    echo json_encode($data);

                } else {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('chatbot_no_phone_numbers_found')
                    );

                    // Display the false response
                    echo json_encode($data);

                }
                
            }
            
        }
        
    }

    /**
     * The public method check_for_phone_numbers verifies if user has phone numbers
     * 
     * @since 0.0.8.1
     * 
     * @return void
     */ 
    public function check_for_phone_numbers() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Use the base model for a simply sql query
            $get_phone_numbers = $this->CI->base_model->get_data_where(
                'chatbot_phone_numbers',
                'phone_id, history_id, body, new',
                array(
                    'user_id' => $this->CI->user_id
                )
            );

            // Verify if phone numbers exists
            if ( $get_phone_numbers ) {

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
                    'message' => $this->CI->lang->line('chatbot_no_phone_numbers_found')
                );

                // Display the false response
                echo json_encode($data);

            }
            
        }
        
    }

    /**
     * The public method delete_phone_numbers deletes phone numbers
     * 
     * @since 0.0.8.1
     * 
     * @return void
     */ 
    public function delete_phone_numbers() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('phone_numbers', 'Phone Numbers', 'trim');
            
            // Get data
            $phone_numbers = $this->CI->input->post('phone_numbers');

            // Verify if request is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Default count
                $count = 0;

                // List all phone numbers
                foreach ( $phone_numbers as $phone ) {

                    // Verify if the phone id is numeric
                    if (is_numeric($phone[1])) {

                        // Try to delete phone number
                        if ( $this->CI->base_model->delete('chatbot_phone_numbers', array('phone_id' => $phone[1], 'user_id' => $this->CI->user_id)) ) {

                            // Increase count
                            $count++;

                        }

                    }

                }

                if ( $count ) {

                    $data = array(
                        'success' => TRUE,
                        'message' => $count . $this->CI->lang->line('chatbot_phone_numbers_were_deleted')
                    );

                    echo json_encode($data);

                } else {

                    $data = array(
                        'success' => FALSE,
                        'message' => '0' . $this->CI->lang->line('chatbot_phone_numbers_were_deleted')
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
    
}

/* End of file phone_numbers.php */