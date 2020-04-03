<?php
/**
 * Categories Helpers
 * 
 * PHP Version 7.3
 *
 * This file contains the class categories
 * with methods to process the categories data
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
 * Categories class provides the methods to process the categories data
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
*/
class Categories {
    
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
        
        // Load the FB Chatbot Categories Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_CHATBOT . 'models/', 'Fb_chatbot_categories_model', 'fb_chatbot_categories_model' );
        
    }

    //-----------------------------------------------------
    // Main class's methods
    //-----------------------------------------------------
    
    /**
     * The public method create_category creates a new category
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function create_category() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('category_name', 'Category Name', 'trim|required');
            
            // Get data
            $category_name = $this->CI->input->post('category_name', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() == false ) {
                
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('please_enter_valid_category')
                );

                echo json_encode($data);   
                
            } else {

                // Clear the cateory's name
                $category_name = trim($category_name);
                
                // Create a new category
                $save_category= $this->CI->fb_chatbot_categories_model->save_category( $this->CI->user_id, $category_name );
                
                if ( $save_category ) {
                
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('category_was_saved')
                    );

                    echo json_encode($data);
                
                } else {
                    
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('category_was_not_saved')
                    );

                    echo json_encode($data); 
                    
                }
                
            }
            
        }
        
    }
    
    /**
     * The public method get_categories_by_page gets categories by page
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function get_categories_by_page() {

        // Get page's input
        $page = $this->CI->input->get('page');

        // If $page is false, set 1
        if ( !$page ) {
            $page = 1;
        }
        
        // Set the limit
        $limit = 10;
        $page--;
        
        // Use the base model for a simply sql query
        $get_categories = $this->CI->base_model->get_data_where('chatbot_categories', 'category_id, name', array(
            'user_id' => $this->CI->user_id
        ),
        array(),
        array(),
        array(),
        array(
            'order' => array('category_id', 'desc'),
            'start' => ($page * $limit),
            'limit' => $limit
        ));

        // Verify if categories exists
        if ( $get_categories ) {

            // Get total number of categories with base model
            $total = $this->CI->base_model->get_data_where('chatbot_categories', 'COUNT(category_id) AS total', array(
                'user_id' => $this->CI->user_id
            ));

            // Prepare the response
            $data = array(
                'success' => TRUE,
                'categories' => $get_categories,
                'total' => $total[0]['total'],
                'page' => ($page + 1)
            );

            // Display the response
            echo json_encode($data);

        } else {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('no_categories_found')
            );

            // Display the false response
            echo json_encode($data); 

        }
        
    }

    /**
     * The public method get_all_categories gets all categories
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function get_all_categories() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            
            // Get data
            $key = $this->CI->input->post('key', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Use the base model for a simply sql query
                $get_categories = $this->CI->base_model->get_data_where(
                    'chatbot_categories',
                    'category_id, name',
                    array(
                        'user_id' => $this->CI->user_id
                    ),
                    array(),
                    array('name' => $this->CI->db->escape_like_str($key)),
                    array(),
                    array(
                        'order' => array('category_id', 'desc')
                    )

                );

                // Verify if categories exists
                if ($get_categories) {

                    // Prepare the response
                    $data = array(
                        'success' => TRUE,
                        'categories' => $get_categories
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
            'message' => $this->CI->lang->line('no_categories_found')
        );

        // Display the false response
        echo json_encode($data);

    }
    
    /**
     * The public method delete_category deletes a category
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function delete_category() {
        
        // Get category_id's input
        $category_id = $this->CI->input->get('category_id', TRUE);
        
        // Check if category's ID is numeric
        if ( is_numeric($category_id) ) {

            // Try to delete the category by using the base model due to a basic sql query
            if ( $this->CI->base_model->delete('chatbot_categories', array('category_id' => $category_id, 'user_id' => $this->CI->user_id)) ) {

                // Delete all category's records
                run_hook(
                    'delete_chatbot_category',
                    array(
                        'category_id' => $category_id
                    )

                );

                // Prepare success response
                $data = array(
                    'success' => TRUE,
                    'message' => $this->CI->lang->line('category_was_deleted')
                );

                // Display response
                echo json_encode($data);

            } else {

                // Prepare error response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('category_was_not_deleted')
                );

                // Display response
                echo json_encode($data);

            }

            exit();
            
        }
        
        // Prepare no category found message
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('no_category_found')
        );

        // Display response
        echo json_encode($data);         
        
    }    
    
}

/* End of file categories.php */