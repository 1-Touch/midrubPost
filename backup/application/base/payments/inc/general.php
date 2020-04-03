<?php
/**
 * General Inc
 *
 * This file contains the general functions
 * used in the Payments
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| DEFAULTS FUNCTIONS WHICH RETURNS DATA
|--------------------------------------------------------------------------
*/

if ( !function_exists('the_incomplete_transaction') ) {
    
    /**
     * The function the_incomplete_transaction returns the incomplete transaction
     * 
     * @since 0.0.8.0
     * 
     * @return array with the incomplete transaction or boolean false
     */
    function the_incomplete_transaction() {

        // Get codeigniter object instance
        $CI = &get_instance();

        // Verify if flash data exists
        if ( $CI->session->flashdata('incomplete_transaction') ) {

            // Get incomplete transaction
            $transaction = $CI->session->flashdata('incomplete_transaction');

            // Verify if pay's data exists
            if ( isset($transaction['pay']['amount']) && isset($transaction['pay']['currency']) ) {

                // Prepare data to save
                $transaction_params = array(
                    'user_id' => $CI->user_id,
                    'amount' => $CI->security->xss_clean($transaction['pay']['amount']),
                    'currency' => $CI->security->xss_clean($transaction['pay']['currency']),
                    'created' => time()
                );

                // Try to save the transaction
                $transaction_id = $CI->base_model->insert('transactions', $transaction_params);

                // Verify if the transaction was created
                if ( $transaction_id ) {

                    // Verify if the transaction has fields
                    if ( !empty($transaction['fields']) ) {

                        // List all fields
                        foreach ( $transaction['fields'] as $field_name => $field_value ) {

                            // Prepare data to save
                            $transaction_field = array(
                                'transaction_id' => $transaction_id,
                                'field_name' => $CI->security->xss_clean($field_name),
                                'field_value' => $CI->security->xss_clean($field_value)
                            );

                            // Try to save the transaction's field
                            $CI->base_model->insert('transactions_fields', $transaction_field);

                        }

                    }

                    // Verify if the transaction has options
                    if ( !empty($transaction['options']) ) {

                        // List all options
                        foreach ( $transaction['options'] as $option_name => $option_value ) {

                            // Prepare data to save
                            $transaction_option = array(
                                'transaction_id' => $transaction_id,
                                'option_name' => $CI->security->xss_clean($option_name),
                                'option_value' => $CI->security->xss_clean($option_value)
                            );

                            // Try to save the transaction's option
                            $CI->base_model->insert('transactions_options', $transaction_option);

                        }
                        
                    }

                    // Set the transaction's id
                    $transaction['transaction_id'] = $transaction_id;

                    // Set transaction data which will be used if gateway uses ajax for a better security
                    $CI->session->set_flashdata('incomplete_transaction_saved', $transaction);

                    // Return transaction
                    return $transaction;

                }

            }

        }

        return false;
        
    }
    
}

if ( !function_exists('save_complete_transaction') ) {
    
    /**
     * The function save_complete_transaction saves complete transaction
     * 
     * @param integer $transaction_id contains the transaction's ID
     * @param integer $user_id contains the user's ID
     * @param array $args contains the params to update
     * 
     * @since 0.0.8.0
     * 
     * @return boolean true or false
     */
    function save_complete_transaction($transaction_id, $user_id, $args) {

        // Get codeigniter object instance
        $CI = &get_instance();

        // Set transaction's data
        $CI->session->set_flashdata('complete_transaction', array(
            'transaction_id' => $transaction_id
        ));

        // Params array
        $params = array();

        // Verify if net's id exists
        if ( isset($args['net_id']) ) {
            $params['net_id'] = $args['net_id'];
        }

        // Verify if gateway exists
        if ( isset($args['gateway']) ) {
            $params['gateway'] = $args['gateway'];
        }
        
        // Verify if status exists
        if ( isset($args['status']) ) {
            $params['status'] = $args['status'];
        }        

        // Save the error
        $updated = $CI->base_model->update('transactions', array(
            'transaction_id' => $transaction_id,
            'user_id' => $user_id,
        ),
            $params
        );

        // Verify if the transaction was updated
        if ( $updated ) {

            return true;

        } else {

            return false;

        }
        
    }
    
}

if ( !function_exists('create_subscription') ) {
    
    /**
     * The function create_subscription saves a new subscription
     * 
     * @param array $subscription contains the subscribtion
     * 
     * @since 0.0.8.0
     * 
     * @return boolean true or false
     */
    function create_subscription($subscription) {

        // Get codeigniter object instance
        $CI = &get_instance();

        // Try to save the subscription
        if ( $CI->base_model->insert('subscriptions', $subscription) ) {

            return true;

        } else {

            return false;

        }
        
    }
    
}

/*
|--------------------------------------------------------------------------
| DEFAULTS FUNCTIONS WHICH DISPLAYS DATA
|--------------------------------------------------------------------------
*/

if ( !function_exists('get_the_js_urls') ) {
    
    /**
     * The function get_the_js_urls gets the js links
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    function get_the_js_urls() {

        md_get_the_js_urls();
        
    }
    
}

if ( !function_exists('get_the_title') ) {
    
    /**
     * The functionget_the_title gets the page's title
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    function get_the_title() {

        md_get_the_title();
        
    }
    
}

if ( !function_exists('get_payment_view') ) {
    
    /**
     * The function get_payment_view gets the payment's view
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    function get_payment_view() {

        // Verify if view exists
        if ( md_the_component_variable('payment_content_view') ) {

            // Display view
            echo md_the_component_variable('payment_content_view');

        }
        
    }
    
}

if ( !function_exists('get_the_css_urls') ) {
    
    /**
     * The function get_the_css_urls gets the css links
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    function get_the_css_urls() {

        md_get_the_css_urls();
        
    }
    
}

if ( !function_exists('get_the_file') ) {
    
    /**
     * The function get_the_file gets a file
     * 
     * @param string $file_path contains the file's path
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    function get_the_file($file_path) {

        md_include_component_file($file_path);

    }
    
}

if ( !function_exists('add_hook') ) {
    
    /**
     * The function add_hook registers a hook
     * 
     * @param string $hook_name contains the hook's name
     * @param function $function contains the function to call
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    function add_hook($hook, $function) {

        md_add_hook($hook, $function);

    }
    
}

if ( !function_exists('run_hook') ) {
    
    /**
     * The function run_hook runs a hook based on hook name
     * 
     * @param string $hook_name contains the hook's name
     * @param array $args contains the function's args
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    function run_hook($hook_name, $args) {

        // Run a hook
        md_run_hook($hook_name, $args);
        
    }
    
}

/*
|--------------------------------------------------------------------------
| DEFAULT FUNCTIONS TO SAVE DATA
|--------------------------------------------------------------------------
*/

if ( !function_exists('set_css_urls') ) {
    
    /**
     * The function set_css_urls sets the css links
     * 
     * @param array $css_url contains the css link parameters
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    function set_css_urls($css_url) {

        md_set_css_urls($css_url);
        
    }
    
}

if ( !function_exists('set_js_urls') ) {
    
    /**
     * The function set_js_urls sets the js links
     * 
     * @param array $js_url contains the js link parameters
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    function set_js_urls($js_url) {

        md_set_js_urls($js_url);
        
    }
    
}

if ( !function_exists('set_the_title') ) {
    
    /**
     * The function set_the_title sets the page's title
     * 
     * @param string $title contains the title
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    function set_the_title($title) {

        md_set_the_title($title);
        
    }
    
}

if ( !function_exists('set_payment_view') ) {
    
    /**
     * The function set_payment_view sets the payment's view
     * 
     * @param array $view contains the view parameters
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    function set_payment_view($view) {

        // Set content view
        md_set_component_variable('payment_content_view', $view);
        
    }
    
}

if ( !function_exists('set_gateway') ) {
    
    /**
     * The function set_gateway adds user's gateways
     * 
     * @param string $gateway_slug contains the gateway's slug
     * @param array $args contains the gateway's arguments
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    function set_gateway($gateway_slug, $args) {

        // Set payments gateway
        md_set_gateway($gateway_slug, $args);
        
    }
    
}

/*
|--------------------------------------------------------------------------
| REGISTER DEFAULT HOOKS
|--------------------------------------------------------------------------
*/