<?php
/**
 * Facebook Ad Campaign Helper
 *
 * This file contains the class Ad_campaign
 * with methods to manage the Ad Campaigns
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Facebook_ads\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Facebook_ads\Helpers as MidrubBaseUserAppsCollectionFacebook_adsHelpers;
use FacebookAds\Object\AdAccountActivity;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdAccountFields;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\Values\AdObjectives;

/*
 * Ad_campaign class provides the methods to manage the Ad Campaigns
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
*/
class Ad_campaign {
    
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
     * The public method create_ad_campaigns creates ad campaigns
     * 
     * @param array $args contains the arguments to create an ad's campaign
     * 
     * @since 0.0.7.6
     * 
     * @return array with response
     */ 
    public function create_ad_campaigns($args) {
        
        if ( $args ) {
                
            switch ( $args['objective'] ) {

                case 'LINK_CLICKS':

                    $account = new AdAccount($args['account_id']);

                    $campaign = $account->createCampaign(
                        array(),
                        array(
                            CampaignFields::NAME => $args['name'],
                            CampaignFields::OBJECTIVE => $args['objective'],
                            'status' => $args['status'],
                            'special_ad_category' => $args['special_ad_category']
                        )
                    );
                    
                    if ( @$campaign->id ) {
                        
                        return array(
                            'success' => TRUE,
                            'campaign_id' => $campaign->id
                        );
                        
                    } else {
                        
                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('campaign_not_created_successfully')
                        );
                        
                    }
                    
                    break;
                    
                case 'POST_ENGAGEMENT':

                    $account = new AdAccount($args['account_id']);

                    $campaign = $account->createCampaign(
                        array(),
                        array(
                            CampaignFields::NAME => $args['name'],
                            CampaignFields::OBJECTIVE => $args['objective'],
                            'status' => $args['status']
                        )
                    );
                    
                    if ( @$campaign->id ) {
                        
                        return array(
                            'success' => TRUE,
                            'campaign_id' => $campaign->id
                        );
                        
                    } else {
                        
                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('campaign_not_created_successfully')
                        );
                        
                    }
                    
                    break;

            }
            
        } else {
         
            return array(
                'success' => FALSE,
                'message' => 'Invalid parametrs.'
            );
            
        }        
        
    }
    
    /**
     * The public method load_select_campaigns loads campaigns for select dropdown
     *
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_select_campaigns() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            
            // Get data
            $key = $this->CI->input->post('key');

            if ( $this->CI->form_validation->run() !== false ) {
            
                // Get selected account
                $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');

                if ( $account ) {
                    
                    if ( $key ) {
                        
                        $campaigns = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/campaigns?fields=name,start_time,budget_remaining,stop_time,created_time,status&limit=1000&access_token=' . $account[0]->token), true);

                        if ( $campaigns ) {
                            
                            $all_campaigns = array(
                                'data'
                            );

                            $i = 0;

                            foreach ( $campaigns['data'] as $campaign ) {

                                if ( preg_match("/{$key}/i", $campaign['name'] ) ) {
                                    $all_campaigns['data'][] = $campaign;
                                    $i++;
                                }

                                if ( $i > 9 ) {
                                    break;
                                }

                            }
                            
                            if ( isset($all_campaigns['data']) ) {

                                $data = array(
                                    'success' => TRUE,
                                    'campaigns' => $all_campaigns
                                );

                                echo json_encode($data);
                                exit();
                                
                            }

                        }                        
                        
                    } else {

                        $campaigns = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/campaigns?fields=name,start_time,budget_remaining,stop_time,created_time,status&limit=10&access_token=' . $account[0]->token), true);

                        if ( $campaigns ) {

                            $data = array(
                                'success' => TRUE,
                                'campaigns' => $campaigns
                            );

                            echo json_encode($data);
                            exit();

                        }
                        
                    }

                }
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('no_campaigns_found')
        );

        echo json_encode($data);
        
    }
    
    /**
     * The public method select_facebook_campaign selects Ad's Campaign
     *
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function select_facebook_campaign() {
        
        // Get the Ad's Campaign
        $campaign_id = $this->CI->input->get('campaign_id');
        
        // Check if data was submitted
        if ( $campaign_id ) {
            
            // Get selected account
            $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');

            if ( $account ) {

                $campaign = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $campaign_id . '?fields=name,objective&access_token=' . $account[0]->token), true);

                if ( $campaign ) {
                    
                    if ( ($campaign['objective'] !== 'LINK_CLICKS') && ($campaign['objective'] !== 'POST_ENGAGEMENT') ) {
                        
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('campaign_objective_not_supported')
                        );

                        echo json_encode($data);
                        exit();
                        
                    } else {
                        
                        $adsets = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $campaign_id . '/adsets?fields=name&access_token=' . $account[0]->token), true);

                        $data = array(
                            'success' => TRUE,
                            'campaign' => $campaign,
                            'ad_sets' => $adsets
                        );

                        echo json_encode($data);
                        exit();
                        
                    }

                }
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('error_occurred')
        );

        echo json_encode($data);
        
    }
    
}

/* End of file ad_campaign.php */
