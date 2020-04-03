<?php
/**
 * Email Addresses Helpers
 * 
 * PHP Version 7.3
 *
 * This file contains the class Email Addresses
 * with methods to process the email addresses data
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
 * Email_addresses class provides the methods to process the email addresses data
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.1
*/
class Email_addresses {
    
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
        
        // Load the FB Chatbot email addresses Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_CHATBOT . 'models/', 'Fb_chatbot_email_addresses_model', 'fb_chatbot_email_addresses_model' );
        
    }

    //-----------------------------------------------------
    // Main class's methods
    //-----------------------------------------------------

    /**
     * The public method load_email_addresses loads email addresses
     * 
     * @since 0.0.8.1
     * 
     * @return void
     */ 
    public function load_email_addresses() {

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
                    'message' => $this->CI->lang->line('chatbot_no_email_addresses_found')
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
                $get_email_addresses = $this->CI->base_model->get_data_where(
                    'chatbot_email_addresses',
                    'email_id, history_id, body, new',
                    array(
                        'user_id' => $this->CI->user_id
                    ),
                    array(),
                    array('body' => $this->CI->db->escape_like_str($key)),
                    array(),
                    array(
                        'order' => array('email_id', 'desc'),
                        'start' => ($page * $limit),
                        'limit' => $limit
                    )
                );

                // Verify if email addresses exists
                if ( $get_email_addresses ) {

                    // List all email addresses
                    foreach ( $get_email_addresses as $email_number ) {

                        // Verify if if a new email number
                        if ( $email_number['new'] > 0 ) {

                            // Save as seen
                            $this->CI->base_model->update_ceil('chatbot_email_addresses', array(
                                'email_id' => $email_number['email_id']
                            ), array(
                                'new' => 0
                            ));

                        }

                    }

                    // Get total number of email addresses with base model
                    $total = $this->CI->base_model->get_data_where(
                        'chatbot_email_addresses',
                        'COUNT(email_id) AS total',
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
                        'email_addresses' => $get_email_addresses,
                        'total' => $total[0]['total'],
                        'page' => ($page + 1)
                    );

                    // Display the response
                    echo json_encode($data);

                } else {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('chatbot_no_email_addresses_found')
                    );

                    // Display the false response
                    echo json_encode($data);

                }
                
            }
            
        }
        
    }

    /**
     * The public method check_for_email_addresses verifies if user has email addresses
     * 
     * @since 0.0.8.1
     * 
     * @return void
     */ 
    public function check_for_email_addresses() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Use the base model for a simply sql query
            $get_email_addresses = $this->CI->base_model->get_data_where(
                'chatbot_email_addresses',
                'email_id, history_id, body, new',
                array(
                    'user_id' => $this->CI->user_id
                )
            );

            // Verify if email addresses exists
            if ( $get_email_addresses ) {

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
                    'message' => $this->CI->lang->line('chatbot_no_email_addresses_found')
                );

                // Display the false response
                echo json_encode($data);

            }
            
        }
        
    }

    /**
     * The public method delete_email_addresses deletes email addresses
     * 
     * @since 0.0.8.1
     * 
     * @return void
     */ 
    public function delete_email_addresses() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('email_addresses', 'Email Addresses', 'trim');
            
            // Get data
            $email_addresses = $this->CI->input->post('email_addresses');

            // Verify if request is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Default count
                $count = 0;

                // List all email addresses
                foreach ( $email_addresses as $email ) {

                    // Verify if the email id is numeric
                    if (is_numeric($email[1])) {

                        // Try to delete email number
                        if ( $this->CI->base_model->delete('chatbot_email_addresses', array('email_id' => $email[1], 'user_id' => $this->CI->user_id)) ) {

                            // Increase count
                            $count++;

                        }

                    }

                }

                if ( $count ) {

                    $data = array(
                        'success' => TRUE,
                        'message' => $count . $this->CI->lang->line('chatbot_email_addresses_were_deleted')
                    );

                    echo json_encode($data);

                } else {

                    $data = array(
                        'success' => FALSE,
                        'message' => '0' . $this->CI->lang->line('chatbot_email_addresses_were_deleted')
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

/* End of file email_addresses.php */