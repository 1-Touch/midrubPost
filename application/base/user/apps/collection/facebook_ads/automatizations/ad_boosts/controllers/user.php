<?php
/**
 * User Controller
 *
 * This file loads the Ad Boosts automatizations in the user panel
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Facebook_ads\Automatizations\Ad_boosts\Controllers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * User class loads the Facebook_ads app loader
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
 */
class User {
    
    /**
     * Class variables
     *
     * @since 0.0.7.7
     */
    protected $CI, $css_urls_widgets = array(), $js_urls_widgets = array();

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
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'models/', 'Ads_account_model', 'ads_account_model' );
        
        // Load the automatization's language files
        $this->CI->lang->load( 'facebook_ads_boosts_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_BOOSTS_PATH );
        
    }
    
    /**
     * The public method view loads the app's template
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function view() {
        
        // Get the user selected account
        $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');
        
        $account_id = 0;
        
        if ( $account ) {
            
            $account_id = $account[0]->network_id;
            
        }

        // Set Facebook Ads Boosts styles
        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/facebook-ads/automatizations/ad-boosts/styles/css/styles.css?ver=' . MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_VERSION), 'text/css', 'all'));

        // Set Facebook Ads Boosts Js
        set_js_urls(array(base_url('assets/base/user/apps/collection/facebook-ads/automatizations/ad-boosts/js/main.js?ver=' . MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_VERSION)));  

        // Get Ad's boosts
        $this->CI->boosts = $this->CI->ads_boosts_model->get_boosts($this->CI->user_id, $account_id, 0, 10);
        
        // Get Total Ad's boosts
        $this->CI->total_boosts = $this->CI->ads_boosts_model->get_boosts($this->CI->user_id, $account_id);
        
        // Load the main view file
        $this->CI->load->file( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_BOOSTS_PATH . 'views/main.php' );
        
    }
    
    /**
     * The public method view loads the app's template
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function modals() {

        // Load modals view file
        $this->CI->load->file( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_BOOSTS_PATH . 'views/modals.php' );
        
    }    

}

/* End of file user.php */