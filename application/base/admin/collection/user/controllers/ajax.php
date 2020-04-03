<?php
/**
 * Ajax Controller
 *
 * This file processes the User's ajax calls
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */

// Define the page namespace
namespace MidrubBase\Admin\Collection\User\Controllers;

defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\Admin\Collection\User\Helpers as MidrubBaseAdminCollectionUserHelpers;

/*
 * Ajax class processes the admin component's ajax calls
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */
class Ajax {
    
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

    }
    
    /**
     * The public method save_social_data saves social auth data
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function save_social_data() {
        
        // Save data
        (new MidrubBaseAdminCollectionUserHelpers\Social)->save_social_data();
        
    }

    /**
     * The public method create_new_plan creates a new plan
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function create_new_plan() {
        
        // Create new plan
        (new MidrubBaseAdminCollectionUserHelpers\Plans)->create_new_plan();
        
    }

    /**
     * The public method update_a_plan updates a new plan
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function update_a_plan() {
        
        // Update a plan
        (new MidrubBaseAdminCollectionUserHelpers\Plans)->update_a_plan();
        
    }

    /**
     * The public method load_all_plans loads plans
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function load_all_plans() {
        
        // Loads plans
        (new MidrubBaseAdminCollectionUserHelpers\Plans)->load_all_plans();
        
    }

    /**
     * The public method delete_plans deletes plans
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function delete_plans() {
        
        // Deletes plans
        (new MidrubBaseAdminCollectionUserHelpers\Plans)->delete_plans();
        
    }

    /**
     * The public method new_menu_item creates a new menu item
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function new_menu_item() {
        
        // Get menu's item parameters
        (new MidrubBaseAdminCollectionUserHelpers\Menu)->new_menu_item();
        
    }

    /**
     * The public method save_menu_items saves menu's items
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function save_menu_items() {
        
        // Save menu items
        (new MidrubBaseAdminCollectionUserHelpers\Menu)->save_menu_items();
        
    }

    /**
     * The public method get_menu_items gets menu's items
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function get_menu_items() {
        
        // Gets menu items
        (new MidrubBaseAdminCollectionUserHelpers\Menu)->get_menu_items();
        
    }

    /**
     * The public method settings_components_and_apps_list gets components and apps
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function settings_components_and_apps_list() {
        
        // Gets components and apps
        (new MidrubBaseAdminCollectionUserHelpers\Components)->settings_components_and_apps_list();
        
    }

    /**
     * The public method load_selected_components gets components and apps
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function load_selected_components() {
        
        // Gets components and apps
        (new MidrubBaseAdminCollectionUserHelpers\Components)->load_selected_components();
        
    }

    /**
     * The public method activate_theme activates a theme
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function activate_theme() {
        
        // Activate theme
        (new MidrubBaseAdminCollectionUserHelpers\Themes)->activate();
        
    }

    /**
     * The public method deactivate_theme deactivates a theme
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function deactivate_theme() {
        
        // Deactivate theme
        (new MidrubBaseAdminCollectionUserHelpers\Themes)->deactivate();
        
    }

    /**
     * The public method save_user_settings saves user changes
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */
    public function save_user_settings() {
        
        // Saves user settings
        (new MidrubBaseAdminCollectionUserHelpers\Settings)->save_user_settings();
        
    }

}

/* End of file ajax.php */