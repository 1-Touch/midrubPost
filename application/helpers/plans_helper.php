<?php
/**
 * Plans helper
 *
 * This file contains the methods
 * for Admin's plans control
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.4
 */

defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('create_new_plan')) {
    
    /**
     * The function create_new_plan creates a new plan
     * 
     * @since 0.0.7.4
     * 
     * @return void
     */
    function create_new_plan() {
        
        // Get codeigniter object instance
        $CI = get_instance();
        
        // Verify if the user is admin
        if ( $CI->user_role != 1 ) {
            exit();
        }
        
        // Load Plans Model
        $CI->load->model( 'plans' );
        
        // Check if data was submitted
        if ( $CI->input->post() ) {

            // Add form validation
            $CI->form_validation->set_rules('plan_name', 'Plan Name', 'trim|required');

            // Get data
            $plan_name = $CI->input->post('plan_name');

            // Check form validation
            if ($CI->form_validation->run() === false) {

                $data = array(
                    'success' => FALSE,
                    'message' => $CI->lang->line('mm3')
                );

                echo json_encode($data);

            } else {

                $plan_id = $CI->plans->save_plan($plan_name);
                
                if( $plan_id ) {
                    
                    $data = array(
                        'success' => TRUE,
                        'message' => $CI->lang->line('mm86'),
                        'plan_id' => $plan_id
                    );

                    echo json_encode($data);                    
                    
                } else {
                    
                    $data = array(
                        'success' => FALSE,
                        'message' => $CI->lang->line('mm3')
                    );

                    echo json_encode($data);                    
                    
                }
                
            }
            
        }
        
    }
    
}

if (!function_exists('update_a_plan')) {
    
    /**
     * The function update_a_plan updates a plan
     * 
     * @since 0.0.7.4
     * 
     * @return void
     */
    function update_a_plan() {
        
        // Get codeigniter object instance
        $CI = get_instance();
        
        // Verify if the user is admin
        if ( $CI->user_role != 1 ) {
            exit();
        }
        
        // Load Plans Model
        $CI->load->model( 'plans' );
        
        // Check if data was submitted
        if ( $CI->input->post() ) {

            // Add form validation
            $CI->form_validation->set_rules('plan_id', 'Plan ID', 'trim|required');
            $CI->form_validation->set_rules('all_inputs', 'All Inputs', 'trim');
            $CI->form_validation->set_rules('all_options', 'All Options', 'trim');

            // Get data
            $plan_id = $CI->input->post('plan_id');
            $all_inputs = $CI->input->post('all_inputs');
            $all_options = $CI->input->post('all_options');

            // Check form validation
            if ($CI->form_validation->run() === false) {

                $data = array(
                    'success' => FALSE,
                    'message' => $CI->lang->line('mm3')
                );

                echo json_encode($data);

            } else {
                    
                $data = array();
                
                $plan_metas = array();
                        
                foreach( $all_inputs as $input ) {

                    if ( $input[0] === 'plan_name' ) {
                        $data['plan_name'] = $input[1];
                    } else if ( $input[0] === 'plan_price' ) {
                        $data['plan_price'] = $input[1];
                    } else if ( $input[0] === 'currency_sign' ) {
                        $data['currency_sign'] = $input[1];
                    } else if ( $input[0] === 'currency_code' ) {
                        $data['currency_code'] = $input[1];
                    } else if ( $input[0] === 'network_accounts' ) {
                        $data['network_accounts'] = $input[1];
                    } else if ( $input[0] === 'storage' ) {
                        $data['storage'] = $input[1];
                    } else if ( $input[0] === 'features' ) {
                        $data['features'] = $input[1];
                    } else if ( $input[0] === 'teams' ) {
                        $data['teams'] = $input[1];
                    } else if ( $input[0] === 'header' ) {
                        $data['header'] = $input[1];
                    } else if ( $input[0] === 'period' ) {
                        $data['period'] = $input[1];
                    } else {
                        $plan_metas[$input[0]] = $input[1];
                    }

                }
                
                foreach( $all_options as $option ) {

                    if ( $option[0] === 'visible' ) {
                        $data['visible'] = $option[1];
                    } else if ( $option[0] === 'popular' ) {
                        $data['popular'] = $option[1];
                    } else if ( $option[0] === 'featured' ) {
                        $data['featured'] = $option[1];
                    } else if ( $option[0] === 'trial' ) {
                        $data['trial'] = $option[1];
                    } else {
                        $plan_metas[$option[0]] = $option[1];
                    }

                }
                
                $plan_update = 0;
                
                if ( $data ) {
                
                    if ( $CI->plans->update_plan($plan_id, $data) ) {
                        $plan_update++;
                    }
                
                }
                
                if ( $plan_metas ) {
                
                    if ( $CI->plans->update_plan_meta($plan_id, $plan_metas) ) {
                        $plan_update++;
                    }
                
                } 
                
                if( $plan_update ) {
                    
                    $data = array(
                        'success' => TRUE,
                        'message' => $CI->lang->line('mm2')
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

if (!function_exists('delete_plan')) {
    
    /**
     * The function delete_plan deletes an existing plan
     * 
     * @since 0.0.7.4
     * 
     * @return void
     */
    function delete_plan() {
        
        // Get codeigniter object instance
        $CI = get_instance();
        
        // Verify if the user is admin
        if ( $CI->user_role != 1 ) {
            exit();
        }
        
        // Load Plans Model
        $CI->load->model( 'plans' );
        
        // Get the plan_id's get input
        $plan_id = $CI->input->get('plan_id');
        
        // Try to delete the plan
        if ( $CI->plans->delete_plan($plan_id) ) {

            $data = array(
                'success' => TRUE,
                'message' => $CI->lang->line('mm87')
            );

            echo json_encode($data);

        } else {

            $data = array(
                'success' => FALSE,
                'message' => $CI->lang->line('mm88')
            );

            echo json_encode($data);

        }
        
    }
    
}