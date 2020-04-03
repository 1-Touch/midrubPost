<?php
/**
 * Groups Helpers
 *
 * This file contains the class Groups
 * with methods to process the groups methods
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.4
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Posts\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Groups class provides the methods to process the groups data
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.4
*/
class Groups {
    
    /**
     * Class variables
     *
     * @since 0.0.7.4
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.4
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
    }
    
    /**
     * The public method save_group saves a new accounts group
     * 
     * @since 0.0.7.4
     * 
     * @return void
     */ 
    public function save_group() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('group_name', 'Group Name', 'trim|required');
            
            // Get data
            $group_name = $this->CI->input->post('group_name');
            
            if ( $this->CI->form_validation->run() == false ) {
                
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('error_occurred')
                );

                echo json_encode($data);   
                
            } else {
                
                // Load the lists model
                $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Lists_model', 'lists_model' );
                
                // Create a new group
                $save_group = $this->CI->lists_model->save_group( $this->CI->user_id, 'social', $group_name, '' );
                
                // Verify if the group was created
                if ( $save_group ) {
                    
                    // Get groups list
                    $groups_list = $this->CI->lists_model->get_groups($this->CI->user_id, 0, 1000);

                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('group_was_created'),
                        'group_id' => $save_group,
                        'groups' => $groups_list
                   );

                    echo json_encode($data);  
                    
                } else {
                    
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('error_occurred')
                    );

                    echo json_encode($data);                     
                    
                }
                
            }
            
        }
        
    }
    
    /**
     * The public method available_group_accounts gets available group's accounts from the database
     * 
     * @since 0.0.7.4
     * 
     * @return void
     */ 
    public function available_group_accounts() {
        
        // Get the group's id
        $group_id = $this->CI->input->get('group_id', TRUE);
        
        if ( $group_id ) {
            
            // Load the lists model
            $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Lists_model', 'lists_model' );

            // Get group's metas
            $group_metas = $this->CI->lists_model->get_lists_meta( $this->CI->user_id, $group_id );
            
            // Verify if the group has metas 
            if ( $group_metas ) {
                
                $data = array(
                    'success' => TRUE,
                    'accounts' => $group_metas
                );

                echo json_encode($data);
            
            } else {
                
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('no_accounts_found')
                );

                echo json_encode($data); 
                
            }
            
        } else {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('no_accounts_found')
            );

            echo json_encode($data); 
            
        }
        
    }   
    
    /**
     * The public method delete_group deletes a group
     * 
     * @since 0.0.7.4
     * 
     * @return void
     */ 
    public function delete_group() {
        
        // Get the group's id
        $group_id = $this->CI->input->get('group_id', TRUE);
        
        if ( $group_id ) {
            
            // Load the lists model
            $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Lists_model', 'lists_model' );

            // Delete Group
            $group_delete = $this->CI->lists_model->delete_list( $this->CI->user_id, $group_id, 'social' );
            
            // Verify if the group was deleted
            if ( $group_delete ) {
                
                // Get groups list
                $groups_list = $this->CI->lists_model->get_groups($this->CI->user_id, 0, 1000);
                
                $data = array(
                    'success' => TRUE,
                    'message' => $this->CI->lang->line('group_was_deleted'),
                    'select_group' => $this->CI->lang->line('select_group'),
                    'groups' => $groups_list
                );

                echo json_encode($data);
            
            } else {
                
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('error_occurred')
                );

                echo json_encode($data);   
                
            }
            
        } else {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('error_occurred')
            );

            echo json_encode($data);   
            
        }
        
    }   
    
    /**
     * The public method add_account adds account to group
     * 
     * @since 0.0.7.4
     * 
     * @return void
     */ 
    public function add_account() {
        
        // Get the account id
        $account_id = trim($this->CI->input->get('account_id', TRUE));
        
        // Get the group id
        $group_id = trim($this->CI->input->get('group_id', TRUE));
        
        if ( is_numeric($account_id) && is_numeric($group_id) ) {
            
            // Load the lists model
            $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Lists_model', 'lists_model' );
            
            // Verify if the user is owner of the group
            $owner = $this->CI->lists_model->if_user_has_list( $this->CI->user_id, $group_id, 'social' );
            
            // Get the account data
            $get_account = $this->CI->networks_model->get_account( $account_id );
            
            // Verify if the account was added before in the group
            $if_account_exists = $this->CI->lists_model->if_item_is_in_list( $this->CI->user_id, $group_id, $account_id );
            
            if ( ( $owner === TRUE ) && ( $get_account[0]->user_id === $this->CI->user_id ) && ( $if_account_exists === FALSE) ) {
                
                // Save the account
                $save = $this->CI->lists_model->save_group_account( $group_id, $this->CI->user_id, $account_id );
                
                // Verify if the account was saved
                if ( $save ) {
                    
                    $accounts = $this->CI->lists_model->get_lists_meta( $this->CI->user_id, $group_id );
                
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('selected_account_was_saved'),
                        'accounts' => $accounts
                    );

                    echo json_encode($data);
                
                } else {
                    
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('error_occurred')
                    );

                    echo json_encode($data);                     
                    
                }
                
            } else {
                
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('error_occurred')
                );

                echo json_encode($data);                 
                
            }
            
        } else {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('no_group_selected')
            );

            echo json_encode($data); 
            
        }
        
    } 

    /**
     * The public method remove_account removes a group's account
     * 
     * @since 0.0.7.4
     * 
     * @return void
     */ 
    public function remove_account() {
        
        // Get the account id
        $account_id = trim($this->CI->input->get('account_id', TRUE));
        
        // Get the group id
        $group_id = trim($this->CI->input->get('group_id', TRUE));
        
        if ( is_numeric($account_id) && is_numeric($group_id) ) {
            
            // Load the lists model
            $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Lists_model', 'lists_model' );
            
            // Verify if the user is owner of the group
            $owner = $this->CI->lists_model->if_user_has_list( $this->CI->user_id, $group_id, 'social' );
            
            // Get the account data
            $get_account = $this->CI->networks_model->get_account( $account_id );
            
            // Verify if the account was added before in the group
            $if_account_exists = $this->CI->lists_model->if_item_is_in_list( $this->CI->user_id, $group_id, $account_id );
            
            if ( ( $owner === TRUE ) && ( $get_account[0]->user_id === $this->CI->user_id ) && ( $if_account_exists === TRUE) ) {
                
                // Remove the account
                $remove = $this->CI->lists_model->remove_group_account( $group_id, $this->CI->user_id, $account_id );
                
                // Verify if the account was deleted
                if ( $remove ) {
                    
                    $accounts = $this->CI->lists_model->get_lists_meta( $this->CI->user_id, $group_id );
                    
                    // Verify if accounts not exists
                    if ( !$accounts ) {
                        $accounts = '<li class="no-accounts-found">' . $this->CI->lang->line('no_accounts_found') . '</li>';
                    }
                
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('selected_account_was_removed'),
                        'accounts' => $accounts
                    );

                    echo json_encode($data);
                
                } else {
                    
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('error_occurred')
                    );

                    echo json_encode($data);                     
                    
                }
                
            } else {
                
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('error_occurred')
                );

                echo json_encode($data);                 
                
            }
            
        } else {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('no_group_selected')
            );

            echo json_encode($data); 
            
        }
        
    }

}

/* End of file groups.php */