<?php
/**
 * User Options Inc
 *
 * This file contains the user functions with 
 * options for user's components
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */

defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\Classes\Components as MidrubBaseClassesComponents;

if ( !function_exists('md_set_user_component_options') ) {
    
    /**
     * The function md_set_user_component_options adds component's options for user
     * 
     * @param array $args contains the component's options for user
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function md_set_user_component_options($args) {
        
        // Call the user_options class
        $user_options = (new MidrubBaseClassesComponents\User_options);

        // Set component's options in the queue
        $user_options->set_options($args);
        
    }
    
}

if ( !function_exists('md_the_user_component_options') ) {
    
    /**
     * The function md_the_user_component_options gets the component's options
     * 
     * @since 0.0.7.9
     * 
     * @return array with component's options or boolean false
     */
    function md_the_user_component_options() {

        // Call the user_options class
        $user_options = (new MidrubBaseClassesComponents\User_options);

        // Return component's options
        return $user_options->load_options();
        
    }
    
}

if ( !function_exists('md_get_user_component_options') ) {
    
    /**
     * The function md_get_user_component_options generates component's options
     * 
     * @param array $option contains an array with option
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function md_get_user_component_options($option) {

        // Verify if class has the method
        if (method_exists((new MidrubBaseClassesComponents\User_options_templates), $option['type'])) {

            // Set the method to call
            $method = $option['type'];

            // Display input
            echo (new MidrubBaseClassesComponents\User_options_templates)->$method($option);
        }
        
    }
    
}

/* End of file user_options.php */