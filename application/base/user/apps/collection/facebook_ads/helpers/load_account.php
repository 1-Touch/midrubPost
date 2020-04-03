<?php
/**
 * Load Account Helper
 *
 * This file contains the class Load_account
 * with methods to process when the Facebook Ads's page loads
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
 * Load_account class provides the methods to process the methods when the Facebook Ads's page loads
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
*/
class Load_account {
    
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
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Load models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'models/', 'Ads_networks_model', 'ads_networks_model' );
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'models/', 'Ads_account_model', 'ads_account_model' );
            
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
     * The public method load_selected_account loads selected account
     *
     * @since 0.0.7.6
     * 
     * @return array with selected account data or false
     */
    public function load_selected_account() {
        
        // Get selected account
        $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');

        // Verify if user has selected account
        if ( $account ) {
            
            $check_availibility = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/campaigns?access_token=' . $account[0]->token), true);
            
            if ( !$check_availibility ) {
                
                return array(
                    'account' => $account,
                    'reached_the_maximum_api_limit' => $this->CI->lang->line('reached_the_maximum_api_limit')
                );
                
            }
            
            // Get account's campaigns by page
            $campaigns = $this->load_account_campaigns($account, false);
            
            // Get all account's campaigns
            $all_campaigns = $this->load_account_campaigns($account, true);
            
            // Get acount's adsets
            $adsets = $this->load_account_adsets($account);
            
            // Get acount's ads
            $ads = $this->load_account_ads($account);
            
            // Get tracking conversion
            $tracking_conversion = $this->load_tracking_conversion($account);
            
            if ( file_exists(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-overview.json') ) {

                // Get json's cache
                $content = json_decode(file_get_contents(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-overview.json'), true);

                // Get account insights
                $account_insights = $content;

            } else {
            
                // Get account insights
                $account_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/insights?fields=cpc,ctr,cpp,cpm,impressions,clicks,reach,spend,social_spend,account_currency,frequency&time_range[since]=' . date('Y-m-d', strtotime('-1 day')) . '&time_range[until]=' . date('Y-m-d', strtotime('now')) . '&access_token=' . $account[0]->token), true);
                
                $this->create_cache(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-overview.json', json_encode($account_insights, JSON_PRETTY_PRINT));
                
            }
            
            return array(
                'account' => $account,
                'campaigns' => $campaigns,
                'adsets' => $adsets,
                'ads' => $ads,
                'all_campaigns' => $all_campaigns,
                'tracking_conversion' => $tracking_conversion,
                'account_insights' => $account_insights
                
            );
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method load_account_campaigns loads ad account's campaigns
     * 
     * @param object $account contains the account's data
     * @param boolean $all contains the option to return all or by page campaigns
     *
     * @since 0.0.7.6
     * 
     * @return object with account campaigns or false
     */
    public function load_account_campaigns($account, $all) {

        try {

            if ( $all ) {
                
                $response = $this->fb->get(
                    '/' . $account[0]->net_id . '/campaigns?fields=name,status,insights,objective&limit=1000',
                    $account[0]->token
                );                
                
            } else {
                
                if ( file_exists(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-campaigns.json') ) {
                    
                    // Get json's cache
                    $content = json_decode(file_get_contents(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-campaigns.json'), true);

                    return $content;
                    
                } else {
            
                    $response = $this->fb->get(
                        '/' . $account[0]->net_id . '/campaigns?fields=name,status,insights,objective&limit=10',
                        $account[0]->token
                    );
                
                }
            
            }
            
        } catch (Facebook\Exceptions\FacebookResponseException $e) {

            return false;
            
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            
            return false;
            
        }

        return $response->getDecodedBody();
        
    }
    
    /**
     * The public method load_account_adsets loads ad account's adsets
     * 
     * @param object $account contains the account's data
     *
     * @since 0.0.7.6
     * 
     * @return object with account adsets or false
     */
    public function load_account_adsets($account) {
        
        try {

            if ( file_exists(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-adsets.json') ) {

                // Get json's cache
                $content = json_decode(file_get_contents(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-adsets.json'), true);

                return $content;

            } else {
                
                $response = $this->fb->get(
                    '/' . $account[0]->net_id . '/adsets?fields=status,insights,name,campaign{name}&limit=10',
                    $account[0]->token
                );

            }
            
        } catch (Facebook\Exceptions\FacebookResponseException $e) {

            return false;
            
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            
            return false;
            
        }

        return $response->getDecodedBody();
        
    }
    
    /**
     * The public method load_account_pages loads account's pages
     *
     * @since 0.0.7.6
     * 
     * @return object with account's pages or false
     */
    public function load_account_pages() {
        
        // Get selected account
        $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');
        
        if ( $account ) {
            
            // Get all pages
            $account_pages = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . 'me/accounts?fields=connected_instagram_account{ig_id,username},name,picture,access_token&limit=1000&access_token=' . $account[0]->token), true);

            if ( isset($account_pages['data'][0]) ) {

                $connected_instagram = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account_pages['data'][0]['id'] . '/instagram_accounts?fields=id,username,profile_pic&access_token=' . $account_pages['data'][0]['access_token']), true);

                $data = array(
                    'success' => TRUE,
                    'account_pages' => $account_pages,
                    'connected_instagram' => $connected_instagram,
                    'words' => array(
                        'identity' => $this->CI->lang->line('identity'),
                        'your_facebook_page_represents_business' => $this->CI->lang->line('your_facebook_page_represents_business'),
                        'instagram_below_connected_facebook' => $this->CI->lang->line('instagram_below_connected_facebook'),
                        'search_for_pages' => $this->CI->lang->line('search_for_pages'),
                        'search_for_accounts' => $this->CI->lang->line('search_for_accounts'),
                    )
                );

                echo json_encode($data);
                exit();
                
            }
            
        }
 
        $data = array(
            'success' => FALSE,
        );

        echo json_encode($data);
        
    }  
    
    /**
     * The public method load_tracking_conversion loads tracking conversions
     * 
     * @param object $account contains the account's data
     *
     * @since 0.0.7.6
     * 
     * @return object with account's pages or false
     */
    public function load_tracking_conversion($account) {
        
        try {
            
            if ( file_exists(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-conversions.json') ) {

                // Get json's cache
                $content = json_decode(file_get_contents(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-conversions.json'), true);

                return $content;

            } else {

                $response = $this->fb->get(
                    '/' . $account[0]->net_id . '/customconversions?fields=id,name,data_sources,aggregation_rule,custom_event_type,rule,pixel{name}&limit=10',
                    $account[0]->token
                );
            
            }
            
        } catch (Facebook\Exceptions\FacebookResponseException $e) {

            return false;
            
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            
            return false;
            
        }

        return $response->getDecodedBody();
        
    }      

    /**
     * The public method load_account_ads loads ad account's ads
     * 
     * @param object $account contains the account's data
     *
     * @since 0.0.7.6
     * 
     * @return object with account adsets or false
     */
    public function load_account_ads($account) {
        
        try {
            
            if ( file_exists(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-ads.json') ) {

                // Get json's cache
                $content = json_decode(file_get_contents(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-ads.json'), true);

                return $content;

            } else {

                $response = $this->fb->get(
                    '/' . $account[0]->net_id . '/ads?fields=insights,status,name,adset{name}&limit=10',
                    $account[0]->token
                );
            
            }
            
        } catch (Facebook\Exceptions\FacebookResponseException $e) {

            return false;
            
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            
            return false;
            
        }

        return $response->getDecodedBody();
        
    } 
    
    /**
     * The public method load_account_overview loads account stats
     *
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_account_overview() {
        
        // Get type
        $type = $this->CI->input->get('type', TRUE);
        
        // Get selected account
        $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');     
               
        if ( is_numeric($type) && $account ) {
            
            // Get user Currency
            $get_user_currency = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '?fields=currency,min_daily_budget&access_token=' . $account[0]->token), true);
            
            switch($type) {
                
                case '1':
            
                    // Get account insights
                    $account_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/insights?fields=cpc,ctr,cpp,cpm,impressions,clicks,reach,spend,social_spend,account_currency,frequency&time_range[since]=' . date('Y-m-d', strtotime('-1 day')) . '&time_range[until]=' . date('Y-m-d', strtotime('now')) . '&access_token=' . $account[0]->token), true);
                    
                    if ( isset($account_insights['data'][0]) ) {
                    
                        $data = array(
                            'success' => TRUE,
                            'account_insights' => $account_insights['data'][0]
                        );

                        echo json_encode($data);
                        exit();
                        
                    }
                    
                    break;
                
                case '2':
            
                    // Get account insights
                    $account_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/insights?fields=cpc,ctr,cpp,cpm,impressions,clicks,reach,spend,social_spend,account_currency,frequency&time_range[since]=' . date('Y-m-d', strtotime('-7 days')) . '&time_range[until]=' . date('Y-m-d', strtotime('now')) . '&access_token=' . $account[0]->token), true);
                    
                    if ( isset($account_insights['data'][0]) ) {
                    
                        $data = array(
                            'success' => TRUE,
                            'account_insights' => $account_insights['data'][0]
                        );

                        echo json_encode($data);
                        exit();
                        
                    }
                    
                    break;
                
                case '3':
            
                    // Get account insights
                    $account_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/insights?fields=cpc,ctr,cpp,cpm,impressions,clicks,reach,spend,social_spend,account_currency,frequency&time_range[since]=' . date('Y-m-d', strtotime('-30 days')) . '&time_range[until]=' . date('Y-m-d', strtotime('now')) . '&access_token=' . $account[0]->token), true);

                    if ( isset($account_insights['data'][0]) ) {
                    
                        $data = array(
                            'success' => TRUE,
                            'account_insights' => $account_insights['data'][0]
                        );

                        echo json_encode($data);
                        exit();
                        
                    }
                    
                    break;
                
            }
            
            $data = array(
                'success' => TRUE,
                'account_insights' => array(
                    'ctr' => '0',
                    'cpp' => '0',
                    'cpm' => '0',
                    'impressions' => '0',
                    'clicks' => '0',
                    'reach' => '0',
                    'spend' => '0',
                    'social_spend' => '0',
                    'account_currency' => @$get_user_currency['currency'],
                    'frequency' => '0'
                ),
                'words' => array(
                    'today' => $this->CI->lang->line('today'),
                    'week' => $this->CI->lang->line('week'),
                    'month' => $this->CI->lang->line('month'),
                    'new_ad' => $this->CI->lang->line('new_ad'),
                    'total_spent' => $this->CI->lang->line('total_spent'),
                    'social_spent' => $this->CI->lang->line('social_spent'),
                    'impressions' => $this->CI->lang->line('impressions'),
                    'clicks' => $this->CI->lang->line('clicks'),
                    'reach' => $this->CI->lang->line('reach'),
                    'frequency' => $this->CI->lang->line('frequency'),
                    'cpm' => $this->CI->lang->line('cpm'),
                    'cpp' => $this->CI->lang->line('cpp'),
                    'ctr' => $this->CI->lang->line('ctr')
                )
            );

            echo json_encode($data);
            exit();
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('error_occurred')
        );

        echo json_encode($data); 
        
    }     
    
    /**
     * The public method create_cache creates cache
     * 
     * @param string $file_name contains the file's name
     * @param string $content contains the file's content
     *
     * @since 0.0.7.7
     * 
     * @return boolean true or false
     */
    public function create_cache($file_name, $content) {
        
        if (!file_put_contents($file_name, $content)) {
            return true;
        } else {
            return false;
        }
        
    } 
    
}

/* End of file load_account.php */
