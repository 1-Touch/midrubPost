<?php
/**
 * User Meta helper
 *
 * This file contains the methods
 * for User's metas
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.2
 */

defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('usermeta_add_value')) {
    
    /**
     * The function usermeta_add_value saves the user option
     * 
     * @return void
     */
    function usermeta_add_value() {
        
        // Get codeigniter object instance
        $CI = get_instance();
        
        // Check if data was submitted
        if ( $CI->input->post() ) {

            // Add form validation
            $CI->form_validation->set_rules('meta_name', 'Meta Name', 'trim|required');
            $CI->form_validation->set_rules('meta_value', 'Meta Value', 'trim|required');

            // Get data
            $meta_name = $CI->input->post('meta_name');
            $meta_value = $CI->input->post('meta_value');

            // Check form validation
            if ($CI->form_validation->run() === false) {

                // Display error message
                $data = array(
                    'success' => FALSE,
                    'message' => $CI->lang->line( 'mm3' )
                );

                echo json_encode($data);

            } else {

                // Save new value
                if ( $CI->user_meta->update_user_meta( $CI->user_id, $meta_name, $meta_value ) ) {

                    $data = array(
                        'success' => TRUE,
                        'message' => $CI->lang->line('mm2'),
                        'refresh' => TRUE
                    );

                    echo json_encode($data);

                } else {

                    $data = array(
                        'success' => FALSE,
                        'message' => $CI->lang->line('mm1')
                    );

                    echo json_encode($data);

                }
                
            }
            
        }
        
    }
    
}