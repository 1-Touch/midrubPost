<?php
/**
 * General Theme Ajax
 *
 * This file contains the main theme's ajax functions
 * used in the theme
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */

defined('BASEPATH') OR exit('No direct script access allowed');

if ( !function_exists('contact_us') ) {
    
    /**
     * The function contact_us processes ajax submission
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */
    function contact_us() {

        // Get the string
        $CI =& get_instance();

        // Check if data was submitted
        if ( $CI->input->post() ) {

            // Add form validation
            $CI->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
            $CI->form_validation->set_rules('email', 'Email', 'trim|required');
            $CI->form_validation->set_rules('subject', 'Subject', 'trim|required');
            $CI->form_validation->set_rules('message', 'Message', 'trim|required');
            $CI->form_validation->set_rules('recaptcha', 'Catcha', 'trim|required');
            $CI->form_validation->set_rules('content_id', 'Content ID', 'trim|numeric|required');
            
            // Get received data
            $full_name = $CI->input->post('full_name');
            $email = $CI->input->post('email');
            $subject = $CI->input->post('subject');
            $message = $CI->input->post('message');
            $g_recaptcha_response = $CI->input->post('recaptcha');
            $content_id = $CI->input->post('content_id');

            $secret_key = '';

            if ( is_numeric($content_id) ) {

                // Get recaptcha secret key
                $res = $CI->base_model->get_data_where('contents_meta', 'meta_value', array(
                    'content_id' => $content_id,
                    'meta_name' => 'theme_contact_recaptcha_site_secret'
                ));

                if ( $res ) {

                    $secret_key = $res[0]['meta_value'];

                }

            }

            // Check if the catcha code is valid
            $response = json_decode(post(
                'https://www.google.com/recaptcha/api/siteverify',
                array(
                    'secret' => $secret_key,
                    'response' => $g_recaptcha_response
                )
            ), true);

            // Check form validation
            if ($CI->form_validation->run() !== false && isset($response['success']) ) {

                $body = "From: " . $full_name . "<br><br>";
                $body .= "Email: " . $email . "<br><br>";
                $body .= $message;

                // Create email
                $email_args = array(
                    'from_name' => $CI->config->item('site_name'),
                    'from_email' => $CI->config->item('contact_mail'),
                    'to_email' => $CI->config->item('notification_mail'),
                    'subject' => $subject,
                    'body' => $body
                );

                // Send notification template
                if ( (new MidrubBase\Classes\Email\Send())->send_mail($email_args) ) {

                    // Display error message
                    $data = array(
                        'success' => TRUE,
                        'message' => $CI->lang->line('theme_thank_you_for_email')
                    );

                    echo json_encode($data);
                    exit();             
                    
                }

            }

        }

        // Display error message
        $data = array(
            'success' => FALSE,
            'message' => $CI->lang->line('theme_an_error_send_email')
        );

        echo json_encode($data);
        
    }
    
}

/* End of file main.php */