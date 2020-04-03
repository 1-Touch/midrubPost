<?php
/**
 * User Controller
 *
 * This file loads the Ad Labels automatizations in the user panel
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Facebook_ads\Automatizations\Ad_labels\Controllers;

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
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'models/', 'Ads_account_model', 'ads_account_model' );
        
        // Load the automatization's language files
        $this->CI->lang->load( 'facebook_ads_labels_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_LABELS_PATH);
        
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

        // Set Facebook Ads Labels styles
        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/facebook-ads/automatizations/ad-labels/styles/css/styles.css?ver=' . MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_VERSION), 'text/css', 'all'));

        // Set Facebook Ads Labels Js
        set_js_urls(array(base_url('assets/base/user/apps/collection/facebook-ads/automatizations/ad-labels/js/main.js?ver=' . MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_VERSION)));  
        
        // Get Ad's labels
        $this->CI->labels = $this->CI->ads_labels_model->get_labels($this->CI->user_id, $account_id, 0, 10);
        
        // Get Total Ad's labels
        $this->CI->total_labels = $this->CI->ads_labels_model->get_labels($this->CI->user_id, $account_id);
        
        // Load the main view file
        $this->CI->load->file( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_LABELS_PATH . 'views/main.php' );
        
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
        $this->CI->load->file( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_LABELS_PATH . 'views/modals.php' );
        
    }    

}

/* End of file user.php */