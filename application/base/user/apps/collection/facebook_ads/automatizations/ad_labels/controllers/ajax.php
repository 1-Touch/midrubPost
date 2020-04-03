<?php
/**
 * Ajax Controller
 *
 * This file processes the app's ajax calls
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Facebook_ads\Automatizations\Ad_labels\Controllers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Facebook_ads\Automatizations\Ad_labels\Helpers as MidrubBaseUserAppsCollectionFacebook_adsAutomatizationsAd_labelsHelpers;

/*
 * Ajaz class processes the app's ajax calls
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
 */
class Ajax {
    
    /**
     * Class variables
     *
     * @since 0.0.7.7
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.7
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Load models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_LABELS_PATH . 'models/', 'Ads_labels_model', 'ads_labels_model' );
        
        // Load the automatization's language files
        $this->CI->lang->load( 'facebook_ads_labels_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_LABELS_PATH);
        
    }
    
    /**
     * The public method create_new_ad_label creates a new Ad's label
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function create_new_ad_label() {
        
        // Create new Ad Label
        (new MidrubBaseUserAppsCollectionFacebook_adsAutomatizationsAd_labelsHelpers\Labels)->create_new_label();
        
    }
    
    /**
     * The public method fb_labels_load_all loads all Ad's labels
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function fb_labels_load_all() {
        
        // Load all Ad's labels
        (new MidrubBaseUserAppsCollectionFacebook_adsAutomatizationsAd_labelsHelpers\Labels)->fb_labels_load_all();
        
    }
    
    /**
     * The public method delete_ad_labels deletes Ad's labels
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function delete_ad_labels() {
        
        // Delete Ad's labels
        (new MidrubBaseUserAppsCollectionFacebook_adsAutomatizationsAd_labelsHelpers\Labels)->delete_ad_labels();
        
    }
    
    /**
     * The public method ad_labels_reports_by_time displays ad labels reports
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function ad_labels_reports_by_time() {

        // Display reports
        (new MidrubBaseUserAppsCollectionFacebook_adsAutomatizationsAd_labelsHelpers\Labels)->order_reports_by_time();
        
    }

}

/* End of file ajax.php */
