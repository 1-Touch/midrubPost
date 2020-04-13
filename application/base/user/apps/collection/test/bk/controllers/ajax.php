<?php
/**
 * Ajax Controller
 *
 * This file processes the app's ajax calls
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Storage\Controllers;

defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Storage\Helpers as MidrubBaseUserAppsCollectionStorageHelpers;

/*
 * Ajax class processes the app's ajax calls
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */
class Ajax {
    
    /**
     * Class variables
     *
     * @since 0.0.7.6
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.6
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();

        // Load language
        $this->CI->lang->load( 'storage_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STORAGE );
        
    }
    
    /**
     * The public method storage_create_new_category creates a new category
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function storage_create_new_category() {
        
        (new MidrubBaseUserAppsCollectionStorageHelpers\Categories)->storage_create_new_category();
        
    }
    
    /**
     * The public method get_categories gets all media's categories
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function get_categories() {

        (new MidrubBaseUserAppsCollectionStorageHelpers\Categories)->get_categories();
        
    }
    
    /**
     * The public method storage_add_media_to_category adds medias to category
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function storage_add_media_to_category() {
        
        (new MidrubBaseUserAppsCollectionStorageHelpers\Categories)->adds_medias_to_category();
        
    }
    
    /**
     * The public method remove_from_category removes medias from category
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function remove_from_category() {
        
        (new MidrubBaseUserAppsCollectionStorageHelpers\Categories)->remove_from_category();
        
    }    
    
    /**
     * The public method delete_media_category deletes media category
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function delete_media_category() {
        
        (new MidrubBaseUserAppsCollectionStorageHelpers\Categories)->delete_media_category();
        
    }    
    
    /**
     * The public method download_images_from_urls downloads images from urls
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function download_images_from_urls() {

        // Download and save images
        (new MidrubBaseUserAppsCollectionStorageHelpers\Save_images)->download_images_from_urls();
        
    }
    
}
