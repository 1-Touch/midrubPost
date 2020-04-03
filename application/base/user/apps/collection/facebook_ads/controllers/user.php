<?php
/**
 * User Controller
 *
 * This file loads the Facebook_ads app in the user panel
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Facebook_ads\Controllers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Facebook_ads\Helpers as MidrubBaseUserAppsCollectionFacebook_adsHelpers;

// Require the functions file
require_once MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'inc/functions.php';

/*
 * User class loads the Facebook_ads app loader
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */
class User {
    
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
        $this->CI->lang->load( 'facebook_ads_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_FACEBOOK_ADS );
        
    }
    
    /**
     * The public method view loads the app's template
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function view() {

        // Set the page's title
        set_the_title($this->CI->lang->line('advertising'));
        
        // Making temlate and send data to view.
        if ( $this->CI->input->get('q') ) {
            
            switch ( $this->CI->input->get('q') ) {
                
                case 'facebook-ad-accounts':
                    
                    // Redirect user to login
                    (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_connector)->connect();
                    
                    break;
                
                case 'facebook-save-accounts':
                    
                    // Save user token
                    (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_connector)->save();
                    
                    break;                
                
                default:
                    
                    show_404();
                    
                    break;
                    
            }
            
        } else {

            // Set Facebook Ads styles
            set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/facebook-ads/styles/css/styles.css?ver=' . MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_VERSION), 'text/css', 'all'));

            // Set Facebook Ads Js
            set_js_urls(array(base_url('assets/base/user/apps/collection/facebook-ads/js/main.js?ver=' . MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_VERSION)));  
            
            // Get selected account
            $selected_account = (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Load_account)->load_selected_account();

            // Set views params
            set_user_view(
                $this->CI->load->ext_view(
                    MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'views',
                    'main',
                    array(
                        'selected_account' => $selected_account,
                        'reached_the_maximum_api_limit' => isset($selected_account['reached_the_maximum_api_limit'])?$selected_account['reached_the_maximum_api_limit']:''
                    ),
                    true
                )
                
            );
        
        }
        
    }

}

/* End of file user.php */