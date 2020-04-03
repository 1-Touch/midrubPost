<?php
/**
 * Ajax Controller
 *
 * This file processes the Update's ajax calls
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */

// Define the page namespace
namespace MidrubBase\Admin\Collection\Update\Controllers;

defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\Admin\Collection\Update\Helpers as MidrubBaseAdminCollectionUpdateHelpers;

/*
 * Ajax class processes the Update's component's ajax calls
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */
class Ajax {
    
    /**
     * Class variables
     *
     * @since 0.0.8.0
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.0
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();

        // Load the component's language files
        $this->CI->lang->load( 'update', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_ADMIN_UPDATE );

    }
    
    /**
     * The public method update_midrub verifies if the update code is valid
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function update_midrub() {
        
        // Verify if Midrub can be updated
        (new MidrubBaseAdminCollectionUpdateHelpers\Midrub)->verify();
        
    }

    /**
     * The public method download_midrub_update starts to download the Midrub's update
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function download_midrub_update() {
        
        // Download Update
        (new MidrubBaseAdminCollectionUpdateHelpers\Midrub)->download_update();
        
    }

    /**
     * The public method extract_midrub_update extracts the Midrub's update
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function extract_midrub_update() {
        
        // Extract Update
        (new MidrubBaseAdminCollectionUpdateHelpers\Midrub)->extract_update();
        
    }

    /**
     * The public method start_midrub_backup starts backup creation
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function start_midrub_backup() {
        
        // Backup
        (new MidrubBaseAdminCollectionUpdateHelpers\Midrub)->start_backup();
        
    }

    /**
     * The public method restore_midrub_backup restores a backup
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function restore_midrub_backup() {
        
        // Restore Backup
        (new MidrubBaseAdminCollectionUpdateHelpers\Midrub)->restore_backup();
        
    }

}

/* End of file ajax.php */