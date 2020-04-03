<?php
/**
 * User Options Class
 *
 * This file loads the User_options Class with properties used to displays components options in the user panel
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */

// Define the page namespace
namespace MidrubBase\Classes\Components;

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * User_options class loads the properties used to displays components options in the User panel
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */
class User_options {
    
    /**
     * Contains and array with saved options
     *
     * @since 0.0.7.9
     */
    public static $the_options = array(); 

    /**
     * The public method set_options adds a collection with options to the list
     * 
     * @param array $args contains the page's arguments
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function set_options($args) {

        // Verify if options exists
        if ( $args ) {
            
            self::$the_options[] = $args;

        }

    } 

    /**
     * The public method load_options loads all components options
     * 
     * @since 0.0.7.9
     * 
     * @return array with options or boolean false
     */
    public function load_options() {

        // Verify if options exists
        if ( self::$the_options ) {

            return self::$the_options;

        } else {

            return false;

        }

    }

}

/* End of file user_options.php */