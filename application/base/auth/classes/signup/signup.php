<?php
/**
 * Signup Class
 *
 * This file loads the Signup Class with properties and methods for signup process
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */

// Define the page namespace
namespace MidrubBase\Auth\Classes\Signup;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\Classes\Email as MidrubBaseClassesEmail;

/*
 * Signup class loads the properties and methods for signup process
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */
class Signup {
    
    /**
     * Class variables
     *
     * @since 0.0.7.8
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.8
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();

        // Load the Base Users Model
        $this->CI->load->ext_model( MIDRUB_BASE_PATH . 'models/', 'Base_users', 'base_users' );

        // Load Base Plans Model
        $this->CI->load->ext_model( MIDRUB_BASE_PATH . 'models/', 'Base_plans', 'base_plans' );

        // Load Plans Model
        $this->CI->load->model('plans');

        // Load the bcrypt library
        $this->CI->load->library('bcrypt');
        
    }

    /**
     * The public method save_user_data saves user data
     * 
     * @param array $args contains the user information
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */
    public function save_user_data($args) {

        // Verify if data is correct
        if ( !isset($args['username']) || !isset($args['email']) || !isset($args['password']) ) {

            // Display error message
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('auth_invalid_value')
            );

            echo json_encode($data);
            exit();

        }

        // Check if the password has less than six characters
        if ( (strlen($args['username']) < 6) || (strlen($args['password']) < 6) ) {

            // Display error message
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('auth_username_or_password_too_short')
            );

            echo json_encode($data);
            
        } else if ( strlen($args['password']) > 20 ) {

            // Display error message
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('auth_password_to_short_or_long')
            );

            echo json_encode($data);
            

        } elseif (preg_match('/\s/', $args['username']) || preg_match('/\s/', $args['password'])) {

            // Display error message
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('auth_usename_password_without_white_spaces')
            );

            echo json_encode($data);

        } elseif ($this->CI->base_users->get_user_ceil('email', $args['email'])) {

            // Display error message
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('auth_email_was_found_in_the_database')
            );

            echo json_encode($data);

        } elseif ($this->CI->base_users->get_user_ceil('username', $args['username'])) {

            // Display error message
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('auth_username_already_in_use')
            );

            echo json_encode($data);
            
        } else {

            // Create $user_args array
            $user_args = array();

            // Set the user name
            $user_args['username'] = $args['username'];

            // Set the email
            $user_args['email'] = $args['email'];

            // Set the password
            $user_args['password'] = $this->CI->bcrypt->hash_password($args['password']);

            // Verify if role exists
            if ( isset($args['role']) ) {

                // Set the role
                $user_args['role'] = $args['role'];

            } else {

                // Set the default role
                $user_args['role'] = 0;

            }

            // Verify if first name exists
            if ( isset($args['first_name']) ) {

                // Set first name
                $user_args['first_name'] = $args['first_name'];

            }

            // Verify if last name exists
            if ( isset($args['last_name']) ) {

                // Set last name
                $user_args['last_name'] = $args['last_name'];

            }

            // Verify if user should confirm his signup
            if ( get_option('signup_confirm') AND !isset($args['status']) ) {
            
                // Set the status
                $user_args['status'] = 0;
                
            } else {

                // Set the default status
                $user_args['status'] = 1;                

            }

            // Set date when user joined
            $user_args['date_joined'] = date('Y-m-d H:i:s');

            // Set user's ip
            $user_args['ip_address'] = $this->CI->input->ip_address();

            // Save the user
            $user_id = $this->CI->base_model->insert('users', $user_args);

            // Verify if user has signed up successfully
            if ( $user_id ) {

                // Default plan's id
                $plan_id = 1;

                // Verify if user has selected a plan
                if (isset($args['plan_id'])) {

                    // Get plan's data
                    $plan_data = $this->CI->base_plans->get_plan($args['plan_id']);

                    // Verify if plan exists
                    if ($plan_data) {

                        // Set selected plan_id
                        $plan_id = $args['plan_id'];

                        // Set the user plan
                        $this->CI->plans->change_plan($plan_id, $user_id);

                    }

                } else {

                    // Get plan's data
                    $plan_data = $this->CI->base_plans->get_plan($plan_id);

                }

                // Verify if user has a paid plan
                if ( (int)$plan_data[0]['plan_price'] && !$plan_data[0]['trial'] ) {

                    // Set non paid data for the user
                    update_user_option($user_id, 'nonpaid', 1);

                }

                // Verify if the user has a referrer
                if ( $this->CI->session->userdata('referrer') ) {
                        
                    // Get referrer
                    $referrer = base64_decode( $this->CI->session->userdata('referrer') );
                    
                    // Verify if referrer is valid
                    if ( is_numeric( $referrer ) ) {
                        
                        // Load Referrals model
                        $this->CI->load->model('referrals');
                        
                        // Save referral
                        $this->CI->referrals->save_referrals($referrer, $user_id, $plan_id);
                        
                        // Delete session
                        $this->CI->session->unset_userdata('referrer');
                        
                    }
                    
                }

                // Load Notifications Model
                $this->CI->load->model('notifications');

                // Verify if the administrator wants to receive a notification about new users
                if ( get_option('enable_new_user_notification') ) {

                    // Get the new-user-notification notification template and send it
                    $notification_args = array(
                        '[username]' => $args['username'],
                        '[site_name]' => '<a href="' . base_url() . '">' . $this->CI->config->item('site_name') . '</a>',
                        '[login_address]' => '<a href="' . $this->CI->config->item('login_url') . '">' . $this->CI->config->item('login_url') . '</a>',
                        '[site_url]' => '<a href="' . base_url() . '">' . base_url() . '</a>'
                    );

                    // Get template
                    $template = $this->CI->notifications->get_template('new-user-notification', $notification_args);

                    // Verify if template exists
                    if ($template) {

                        // Create email
                        $email_args = array(
                            'from_name' => $this->CI->config->item('site_name'),
                            'from_email' => $this->CI->config->item('contact_mail'),
                            'to_email' => $this->CI->config->item('notification_mail'),
                            'subject' => $template['title'],
                            'body' => $template['body']
                        );

                        // Send template
                        (new MidrubBaseClassesEmail\Send())->send_mail($email_args);


                    }
                }

                // Check if sign up need confirm
                if ( get_option('signup_confirm') ) {

                    // Create activation code
                    $activate = time();

                    // Save activation code in user's data from database
                    $add_activate = $this->CI->base_model->update_ceil( 'users', array('email' => $args['email']), array('activate' => $activate) );

                    // Prepare notification
                    $notification_args = array(
                        '[username]' => $args['username'],
                        '[site_name]' => $this->CI->config->item('site_name'),
                        '[confirmation_link]' => '<a href="' .base_url() . 'auth/confirmation?code=' . $activate . '&f=' . $user_id . '">' . base_url() . 'auth/confirmation?code=' . $activate . '&f=' . $user_id . '</a>',
                        '[login_address]' => '<a href="' . $this->CI->config->item('login_url') . '">' . $this->CI->config->item('login_url') . '</a>',
                        '[site_url]' => '<a href="' . base_url() . '">' . base_url() . '</a>'
                    );

                    // Get the welcome-message-with-confirmation notification template
                    $template = $this->CI->notifications->get_template('welcome-message-with-confirmation', $notification_args);

                    // Verify if notification's template exists
                    if ($template) {

                        // Create email
                        $email_args = array(
                            'from_name' => $this->CI->config->item('site_name'),
                            'from_email' => $this->CI->config->item('contact_mail'),
                            'to_email' => $args['email'],
                            'subject' => $template['title'],
                            'body' => $template['body']
                        );

                        // Send notification template
                        if ( (new MidrubBaseClassesEmail\Send())->send_mail($email_args) ) {

                            // Default redirect
                            $redirect = base_url('user/app/dashboard');

                            // Display success message
                            $data = array(
                                'success' => TRUE,
                                'message' => $this->CI->lang->line('auth_signup_success_signup_confirmation'),
                                'redirect' => $redirect
                            );

                            echo json_encode($data);

                        } else {

                            // Display error message
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('auth_signup_success_but_no_confirmation_sent')
                            );

                            echo json_encode($data);

                        }

                    } else {

                        // Display error message
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('auth_signup_success_but_no_confirmation_sent')
                        );

                        echo json_encode($data);

                    }

                } else {

                    // Default redirect
                    $redirect = base_url('user/app/dashboard');

                    // Display success message
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('auth_signup_success_signup'),
                        'redirect' => $redirect
                    );

                    echo json_encode($data);

                    // Prepare notification
                    $notification_args = array(
                        '[username]' => $args['username'],
                        '[site_name]' => $this->CI->config->item('site_name'),
                        '[login_address]' => '<a href="' . $this->CI->config->item('login_url') . '">' . $this->CI->config->item('login_url') . '</a>',
                        '[site_url]' => '<a href="' . base_url() . '">' . base_url() . '</a>'
                    );

                    // Get the welcome-message-no-confirmation notification template
                    $template = $this->CI->notifications->get_template('welcome-message-no-confirmation', $notification_args);

                    // Verify if template exists
                    if ($template) {

                        // Create email
                        $email_args = array(
                            'from_name' => $this->CI->config->item('site_name'),
                            'from_email' => $this->CI->config->item('contact_mail'),
                            'to_email' => $args['email'],
                            'subject' => $template['title'],
                            'body' => $template['body']
                        );

                        // Send notification template
                        (new MidrubBaseClassesEmail\Send())->send_mail($email_args);
                        
                    }

                }

            } else {

                // Display error message
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('auth_signup_failed_signup')
                );

                echo json_encode($data);
                
            }

        }

    } 

}
