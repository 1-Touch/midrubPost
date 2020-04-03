<?php
/**
 * Load Helper
 *
 * This file contains the class Load Helper
 * with methods to provide different kinds of information
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Facebook_ads\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Load class provides the methods to provide different kinds of information
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
*/
class Load {
    
    /**
     * Class variables
     *
     * @since 0.0.7.6
     */
    protected $CI, $fb, $app_id, $app_secret;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.6
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Load models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'models/', 'Ads_networks_model', 'ads_networks_model' );
            
        // Get the Facebook App ID
        $this->app_id = get_option('facebook_pages_app_id');

        // Get the Facebook App Secret
        $this->app_secret = get_option('facebook_pages_app_secret');
        
        // Load the Vendor dependencies
        require_once FCPATH . 'vendor/autoload.php';
        
        // Verify if Facebook Pages was configured
        if ( ($this->app_id != '') AND ( $this->app_secret != '') ) {
            
            $this->fb = new \Facebook\Facebook([
                'app_id' => $this->app_id,
                'app_secret' => $this->app_secret,
                'default_graph_version' => MIDRUB_ADS_FACEBOOK_GRAPH_VERSION,
                'default_access_token' => '{access-token}',
            ]);
            
        }
        
    }

    /**
     * The public method account_details loads ad's account details
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function account_details() {
        
        // Get selected account
        $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');
        
        if ( $account ) {
            
            // Get account's details
            $account_details = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '?fields=business_country_code,currency,min_daily_budget,minimum_budgets&access_token=' . $account[0]->token), true);
            
            $data = array(
                'success' => TRUE,
                'account_details' => $account_details,
                'words' => array(
                    'min_daily_budget' => $this->CI->lang->line('min_daily_budget'),
                    'min_target_cost' => $this->CI->lang->line('min_target_cost'),
                    'default_country' => $this->CI->lang->line('default_country'),
                )
            );

            echo json_encode($data);
            
        }
        
    }
    
}

/* End of file load.php */
