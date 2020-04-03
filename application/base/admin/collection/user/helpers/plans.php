<?php
/**
 * Plans Helper
 *
 * This file contains the class Plans
 * with methods to manage the plans's data
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */

// Define the page namespace
namespace MidrubBase\Admin\Collection\User\Helpers;

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Plans class provides the methods to manage the plans's data
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
*/
class Plans {
    
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

        // Load Base Plans Model
        $this->CI->load->ext_model( MIDRUB_BASE_PATH . 'models/', 'Base_plans', 'base_plans' );
        
    }

    /**
     * The public method create_new_plan creates a new plan
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */ 
    public function create_new_plan() {
        
        // Check if data was submitted
        if ( $this->CI->input->post() ) {

            // Add form validation
            $this->CI->form_validation->set_rules('plan_name', 'Plan Name', 'trim|required');

            // Get data
            $plan_name = $this->CI->input->post('plan_name');

            // Check form validation
            if ($this->CI->form_validation->run() === false) {

                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('user_plan_was_not_saved')
                );

                echo json_encode($data);

            } else {

                // Prepare the plan data
                $plan = array(
                    'plan_name' => $plan_name
                );

                // Save plan
                $plan_id = $this->CI->base_model->insert('plans', $plan);

                // Save the contents classifications
                if ( $plan_id ) {
                    
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('user_plan_was_saved'),
                        'plan_id' => $plan_id
                    );

                    echo json_encode($data);                    
                    
                } else {
                    
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('user_plan_was_not_saved')
                    );

                    echo json_encode($data);                    
                    
                }
                
            }
            
        }
        
    }

    /**
     * The public method update_a_plan updates a plan
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */ 
    public function update_a_plan() {

        // Check if data was submitted
        if ( $this->CI->input->post() ) {

            // Add form validation
            $this->CI->form_validation->set_rules('plan_id', 'Plan ID', 'trim|required');
            $this->CI->form_validation->set_rules('all_inputs', 'All Inputs', 'trim');
            $this->CI->form_validation->set_rules('all_options', 'All Options', 'trim');

            // Get data
            $plan_id = $this->CI->input->post('plan_id');
            $all_inputs = $this->CI->input->post('all_inputs');
            $all_options = $this->CI->input->post('all_options');

            // Check form validation
            if ($this->CI->form_validation->run() === false) {

                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('mm3')
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
                
                    if ( $this->CI->base_model->update_ceil( 'plans', array( 'plan_id' => $plan_id ), $data ) ) {
                        $plan_update++;
                    }
                
                }
                
                if ( $plan_metas ) {
                
                    if ( $this->CI->base_plans->update_plan_meta($plan_id, $plan_metas) ) {
                        $plan_update++;
                    }
                
                } 
                
                if( $plan_update ) {
                    
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('user_settings_changes_were_saved')
                    );

                    echo json_encode($data);                    
                    
                } else {
                    
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('user_settings_changes_were_not_saved')
                    );

                    echo json_encode($data);                    
                    
                }
                
            }
            
        }
        
    }

    /**
     * The public method load_all_plans loads plans by page
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */ 
    public function load_all_plans() {
        
        // Check if data was submitted
        if ( $this->CI->input->post() ) {

            // Add form validation
            $this->CI->form_validation->set_rules('page', 'Page', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            
            // Get received data
            $page = $this->CI->input->post('page');
            $key = $this->CI->input->post('key');
           
            // Check form validation
            if ($this->CI->form_validation->run() !== false ) {

                // Set the limit
                $limit = 20;
                $page--;

                // Prepare arguments for request
                $args = array(
                    'start' => ($page * $limit),
                    'limit' => $limit,
                    'key' => $key
                );
                
                // Get plans by page
                $plans = $this->CI->base_plans->get_plans($args);

                // Verify if plans exists
                if ( $plans ) {

                    // Get total plans
                    $total = $this->CI->base_plans->get_plans(array(
                        'key' => $key
                    ));                    

                    // Display plans
                    $data = array(
                        'success' => TRUE,
                        'plans' => $plans,
                        'total' => $total,
                        'page' => ($page + 1)
                    );

                    echo json_encode($data);
                    exit();

                }

            }
            
        }

        // Display error message
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('user_no_plans_found')
        );

        echo json_encode($data);
        exit();
        
    }

    /**
     * The public method delete_plans deletes plans by plan's ids
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */ 
    public function delete_plans() {

        // Check if data was submitted
        if ( $this->CI->input->post() ) {

            // Add form validation
            $this->CI->form_validation->set_rules('plans_ids', 'Plans Ids', 'trim');
           
            // Get received data
            $plans_ids = $this->CI->input->post('plans_ids');
           
            // Check form validation
            if ($this->CI->form_validation->run() !== false ) {

                // Verify if plans ids exists
                if ( $plans_ids ) {

                    // Count number of deleted plans
                    $count = 0;

                    // List all plans
                    foreach ( $plans_ids as $id ) {

                        // Default plan can't be deleted
                        if ( $id < 2 ) {
                            continue;
                        }

                        // Delete the plan
                        $delete_plan = $this->CI->base_model->delete('plans', array(
                            'plan_id' => $id
                        )); 

                        // Delete plan
                        if ( $delete_plan ) {

                            // Delete the plan's meta
                            $delete_plan = $this->CI->base_model->delete('plans_meta', array(
                                'plan_id' => $id
                            )); 

                            // Delete plans records
                            md_run_hook(

                                'delete_plan',

                                array(
                                    'plan_id' => $id
                                )

                            );

                            $count++;

                        }

                    }

                    if ( $count > 0 ) {

                        // Display success message
                        $data = array(
                            'success' => TRUE,
                            'message' => $this->CI->lang->line('user_plans_were_deleted')
                        );

                        echo json_encode($data);
                        exit();

                    }

                } else {

                    // Display error message
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('user_plans_were_not_deleted')
                    );

                    echo json_encode($data);
                    exit();

                }

            }

        }

        // Display error message
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('user_plans_were_not_deleted')
        );

        echo json_encode($data); 

    }

}

/* End of file social.php */