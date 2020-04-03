<?php
/**
 * Team Helper
 *
 * This file contains the class Team
 * with methods to manage the team
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */

// Define the page namespace
namespace MidrubBase\User\Components\Collection\Team\Helpers; 

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Team class provides the methods to manage the team
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
*/
class Team {
    
    /**
     * Class variables
     *
     * @since 0.0.7.9
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.9
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Load Team Model
        $this->CI->load->ext_model( MIDRUB_BASE_PATH . 'models/', 'Base_teams', 'base_teams' );

        // Load the Base Users Model
        $this->CI->load->ext_model( MIDRUB_BASE_PATH . 'models/', 'Base_users', 'base_users' );

    }

    /**
     * The public method team_new_member creates a new member
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */ 
    public function team_new_member() {
        
        if ( team_members_total() >= plan_feature('teams') ) {
            
            // Display error message
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line( 'reached_maximum_number_allowed_members' )
            );

            echo json_encode($data);
            exit();
            
        }
        
        // Check if data was submitted
        if ( $this->CI->input->post() ) {

            // Add form validation
            $this->CI->form_validation->set_rules('username', 'Username', 'trim|min_length[6]|required');
            $this->CI->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
            $this->CI->form_validation->set_rules('role_id', 'Role ID', 'trim|integer|required');
            $this->CI->form_validation->set_rules('status', 'Status', 'trim|integer|required');
            $this->CI->form_validation->set_rules('about', 'About', 'trim');
            $this->CI->form_validation->set_rules('password', 'Password', 'trim|min_length[6]|required');

            // Get data
            $username = $this->CI->input->post('username', TRUE);
            $email = $this->CI->input->post('email', TRUE);
            $role_id = $this->CI->input->post('role_id', TRUE);
            $status = $this->CI->input->post('status', TRUE);
            $about = $this->CI->input->post('about', TRUE);
            $password = $this->CI->input->post('password', TRUE);

            // Check form validation
            if ($this->CI->form_validation->run() === false) {

                // Display error message
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line( 'username_password_short' )
                );

                echo json_encode($data);

            } else {



                // Verify if the user is the owner of the role
                $get_role = $this->CI->base_model->get_data_where('teams_roles', 'role_id', array(
                    'role_id' => $role_id,
                    'user_id' => $this->CI->user_id
                ));

                if ( !$get_role ) {

                    // Display error message
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('you_are_not_the_role_owner')
                    );

                    echo json_encode($data);
                    exit();
                    
                }
                
                // Verify if email address already exists in teams
                if ( $this->CI->base_teams->check_member_email( $email ) ) {
                    
                    // Display error message
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line( 'email_used_another_team_member' )
                    );

                    echo json_encode($data);   
                    exit();
                }

                // Verify if email address already exists in users
                $get_email = $this->CI->base_model->get_data_where('users', 'user_id', array(
                    'email' => $email
                ));

                // Verify if the email exists
                if ( $get_email ) {

                    // Display error message
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line( 'email_used_another_team_member' )
                    );

                    echo json_encode($data);   
                    exit();

                }

                // Save the member
                if ( $this->CI->base_teams->save_member( $this->CI->user_id, $username, $email, $role_id, $status, $about, $password ) ) {

                    // Display success message
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line( 'member_was_saved_successfully' )
                    );

                    echo json_encode($data);                           

                } else {

                    // Display error message
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line( 'member_was_not_saved_successfully' )
                    );

                    echo json_encode($data);                         

                }

            }

        }
        
    }

    /**
     * The public method team_update_member updates member's info
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */ 
    public function team_update_member() {
        
        // Check if data was submitted
        if ( $this->CI->input->post() ) {

            // Add form validation
            $this->CI->form_validation->set_rules('username', 'Username', 'trim|min_length[6]|required');
            $this->CI->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
            $this->CI->form_validation->set_rules('role_id', 'Role ID', 'trim|integer|required');
            $this->CI->form_validation->set_rules('status', 'Status', 'trim|integer|required');
            $this->CI->form_validation->set_rules('about', 'About', 'trim');
            $this->CI->form_validation->set_rules('password', 'Password', 'trim');

            // Get data
            $username = $this->CI->input->post('username', TRUE);
            $email = $this->CI->input->post('email', TRUE);
            $role_id = $this->CI->input->post('role_id', TRUE);
            $status = $this->CI->input->post('status', TRUE);
            $about = $this->CI->input->post('about', TRUE);
            $password = $this->CI->input->post('password', TRUE);

            // Check form validation
            if ($this->CI->form_validation->run() === false) {

                // Display error message
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line( 'username_password_short' )
                );

                echo json_encode($data);

            } else {

                // Verify if the user is the owner of the role
                $get_role = $this->CI->base_model->get_data_where('teams_roles', 'role_id', array(
                    'role_id' => $role_id,
                    'user_id' => $this->CI->user_id
                ));

                if ( !$get_role ) {

                    // Display error message
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('you_are_not_the_role_owner')
                    );

                    echo json_encode($data);
                    exit();
                    
                }
                
                // Verify if email address already exists
                $check_email = $this->CI->base_teams->check_member_email( $email );
                
                if ( $check_email ) {
                    
                    if ( $check_email[0]->member_username != $username ) {
                    
                        // Display error message
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line( 'email_used_another_team_member' )
                        );

                        echo json_encode($data);   
                        exit();
                    
                    }
                    
                }

                // Verify if email address already exists in users
                $get_email = $this->CI->base_model->get_data_where('users', 'user_id', array(
                    'email' => $email,
                    'user_id !=' => $this->CI->user_id
                ));

                // Verify if the email exists
                if ( $get_email ) {

                    // Display error message
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line( 'email_used_another_team_member' )
                    );

                    echo json_encode($data);   
                    exit();

                }

                if ( $password ) {

                    if ( strlen($password) < 6 ) {

                        // Display error message
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('username_password_short')
                        );

                        echo json_encode($data);
                        exit();

                    }
                    
                }

                // Save the member
                if ( $this->CI->base_teams->update_member( $this->CI->user_id, $username, $email, $role_id, $status, $about, $password ) ) {

                    // Display success message
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line( 'member_was_updated_successfully' )
                    );

                    echo json_encode($data);                           

                } else {

                    // Display error message
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line( 'member_was_not_updated_successfully' )
                    );

                    echo json_encode($data);                         

                }

            }

        }
        
    }

    /**
     * The public method team_all_members returns all team's members
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */ 
    public function team_all_members() {
        
        // Get page's input
        $page = $this->CI->input->get('page');
        
        // Set the limit
        $limit = 100;
        $page--;
        
        // Count number of members
        $total = $this->CI->base_teams->get_members($this->CI->user_id);
        
        // Get members
        $get_members = $this->CI->base_teams->get_members( $this->CI->user_id, $page * $limit, $limit );
        
        // If members exists
        if ( $get_members ) {
            
            $members = array();
            
            foreach ( $get_members as $member ) {
                
                $members[] = array(
                    'member_id' => $member->member_id,
                    'username' => $member->member_username,
                    'picture' => '//gravatar.com/avatar/' . md5($member->member_email) . '?s=200'
                );
                
            }
            
            // Display success message
            $data = array(
                'success' => TRUE,
                'total' => $total,
                'members' => $members
            );
            
            echo json_encode($data);
            
        } else {

            // Display error message
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line( 'no_members_found' )
            );

            echo json_encode($data);                         

        }
        
    }

    /**
     * The public method team_member_info returns the member's info
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */ 
    public function team_member_info() {
        
        // Get member_id's input
        $member_id = $this->CI->input->get('member_id');
        
        // Get member details
        $get_member = $this->CI->base_teams->get_member( $this->CI->user_id, $member_id );
        
        // If members exists
        if ( $get_member ) {
            
            // Display success message
            $data = array(
                'success' => TRUE,
                'member_info' => $get_member,
                'date' => time(),
                'never' => $this->CI->lang->line( 'never' )
            );
            
            echo json_encode($data);
            
        } else {

            // Display error message
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line( 'an_error_occurred' )
            );

            echo json_encode($data);                         

        }

    }

    /**
     * The public method team_member_delete deletes a team's member
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */ 
    public function team_member_delete() {

        // Get member_id's input
        $member_id = $this->CI->input->get('member_id');
        
        // Delete member
        $delete_member = $this->CI->base_teams->delete_member( $this->CI->user_id, $member_id );
        
        // Verify if the member was deleted
        if ( $delete_member ) {
            
            // Display success message
            $data = array(
                'success' => TRUE,
                'message' => $this->CI->lang->line( 'team_member_deleted' )
            );
            
            echo json_encode($data);
            
        } else {

            // Display error message
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line( 'team_member_not_deleted' )
            );

            echo json_encode($data);                         

        }

    }

}

/* End of file team.php */