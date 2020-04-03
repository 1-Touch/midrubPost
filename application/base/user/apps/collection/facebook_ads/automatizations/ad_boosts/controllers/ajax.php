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
namespace MidrubBase\User\Apps\Collection\Facebook_ads\Automatizations\Ad_boosts\Controllers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Facebook_ads\Automatizations\Ad_boosts\Helpers as MidrubBaseUserAppsCollectionFacebook_adsAutomatizationsAd_boostsHelpers;

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
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_BOOSTS_PATH . 'models/', 'Ads_boosts_model', 'ads_boosts_model' );
        
        // Load the automatization's language files
        $this->CI->lang->load( 'facebook_ads_boosts_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_BOOSTS_PATH );
        
    }
    
    /**
     * The public method create_new_ad_boost creates a new Ad's boost
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function create_new_ad_boost() {
        
        // Create new Ad Boost
        (new MidrubBaseUserAppsCollectionFacebook_adsAutomatizationsAd_boostsHelpers\Boosts)->create_new_boost();
        
    }
    
    /**
     * The public method fb_boosts_load_all loads all Ad's boosts
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function fb_boosts_load_all() {
        
        // Load all Ad's boosts
        (new MidrubBaseUserAppsCollectionFacebook_adsAutomatizationsAd_boostsHelpers\Boosts)->fb_boosts_load_all();
        
    }

    /**
     * The public method fb_boosts_load_single loads single Ad's boost
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function fb_boosts_load_single() {
        
        // Load single Ad's boost by id
        (new MidrubBaseUserAppsCollectionFacebook_adsAutomatizationsAd_boostsHelpers\Boosts)->fb_boosts_load_single();
        
    }
    
    /**
     * The public method delete_Ad_boosts deletes Ad's boosts
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function delete_ad_boosts() {
        
        // Delete Ad's boosts
        (new MidrubBaseUserAppsCollectionFacebook_adsAutomatizationsAd_boostsHelpers\Boosts)->delete_Ad_boosts();
        
    }
    
    /**
     * The public method Ad_boosts_reports_by_time displays ad boosts reports
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function ad_boosts_reports_by_time() {

        // Display reports
        (new MidrubBaseUserAppsCollectionFacebook_adsAutomatizationsAd_boostsHelpers\Boosts)->order_reports_by_time();
        
    }

}

/* End of file ajax.php */