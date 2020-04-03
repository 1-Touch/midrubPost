<?php
/**
 * Groups Helpers
 * 
 * PHP Version 7.3
 *
 * This file contains the class groups
 * with methods to process the groups
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
 * Groups class provides the methods to process the groups
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
*/
class Groups {
    
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
        
        // Load the FB Chatbot Suggestions Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_CHATBOT . 'models/', 'Fb_chatbot_suggestions_model', 'fb_chatbot_suggestions_model' );
        
    }

    //-----------------------------------------------------
    // Main class's methods
    //-----------------------------------------------------
    
    /**
     * The public method suggestions_groups gets the suggestions groups
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function suggestions_groups() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');

            // Get data
            $key = $this->CI->input->post('key', TRUE);
            $page = $this->CI->input->post('page', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // If $page is false, set 1
                if (!$page) {
                    $page = 1;
                }

                // Set the limit
                $limit = 10;
                $page--;

                // Use the base model for a simply sql query
                $get_groups = $this->CI->base_model->get_data_where(
                    'chatbot_groups',
                    'group_id, group_name',
                    array(
                        'user_id' => $this->CI->user_id
                    ),
                    array(),
                    array('group_name' => $this->CI->db->escape_like_str($key)),
                    array(),
                    array(
                        'order' => array('group_id', 'desc'),
                        'start' => ($page * $limit),
                        'limit' => $limit
                    )
                );

                // Verify if groups exists
                if ( $get_groups ) {

                    // Get total number of groups with base model
                    $total = $this->CI->base_model->get_data_where(
                        'chatbot_groups',
                        'COUNT(group_id) AS total',
                        array(
                            'user_id' => $this->CI->user_id
                        ),
                        array(),
                        array('group_name' => $this->CI->db->escape_like_str($key)),
                        array(),
                        array()
                    );

                    // Prepare the response
                    $data = array(
                        'success' => TRUE,
                        'groups' => $get_groups,
                        'total' => $total[0]['total'],
                        'page' => ($page + 1),
                        'words' => array(
                            'delete' => $this->CI->lang->line('chatbot_delete')
                        )
                    );

                    // Display the response
                    echo json_encode($data);
                    exit();

                }

            }
            
        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('no_groups_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method delete_group deletes the suggestions groups
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function delete_group() {

        // Get group_id's input
        $group_id = $this->CI->input->get('group_id');

        // Verify if $group_id is numeric
        if ( is_numeric($group_id) ) {

            // Delete suggestion
            if ( $this->delete_group_records($group_id, $this->CI->user_id) ) {

                // Delete all category's records
                run_hook(
                    'delete_chatbot_suggestions_group',
                    array(
                        'group_id' => $group_id
                    )

                );

                // Prepare the success response
                $data = array(
                    'success' => TRUE,
                    'message' => $this->CI->lang->line('group_was_deleted')
                );

                // Display the success response
                echo json_encode($data);
                exit();              

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('group_was_not_deleted')
        );

        // Display the false response
        echo json_encode($data);
        
    }


    /**
     * The public method delete_group_records deletes the group's records
     * 
     * @param integer $group_id contains the group's id
     * @param integer $user_id contains the user's id
     * 
     * @since 0.0.8.0
     * 
     * @return boolean true or false
     */ 
    public function delete_group_records($group_id, $user_id) {

        // Use the base model for a simply sql query
        $get_suggestions = $this->CI->base_model->get_data_where(
            'chatbot_suggestions',
            'suggestion_id',
            array(
                'group_id' => $group_id
            )
        );

        // Delete the group's id
        if ( $this->CI->base_model->delete( 'chatbot_groups', array('group_id' => $group_id, 'user_id' => $user_id) ) ) {

            // Delete group's categories
            $this->CI->base_model->delete( 'chatbot_suggestions_categories', array('group_id' => $group_id) );

            // Verify if suggestions exists
            if ( $get_suggestions ) {

                // List all suggestions
                foreach( $get_suggestions as $get_suggestion ) {

                    // Delete suggestions
                    $this->CI->base_model->delete( 'chatbot_suggestions', array('suggestion_id' => $get_suggestion['suggestion_id']) );
                    $this->CI->base_model->delete( 'chatbot_suggestions_meta', array('suggestion_id' => $get_suggestion['suggestion_id']) );

                }

            }

            return true;

        } else {

            return false;

        }
        
    }    

}

/* End of file groups.php */