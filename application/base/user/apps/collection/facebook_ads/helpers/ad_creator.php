<?php
/**
 * Facebook Ad Creator Helper
 *
 * This file contains the class Ad_creator
 * with methods to create ads on Facebook/Instagram
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Facebook_ads\Helpers;

defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Facebook_ads\Helpers as MidrubBaseUserAppsCollectionFacebook_adsHelpers;
use FacebookAds\Object\AdAccountActivity;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdAccountFields;
use FacebookAds\Logger\CurlLogger;

/*
 * Ad_creator class provides the methods to create ads on Facebook/Instagram
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
*/
class Ad_creator {
    
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
     * The public method create_ad_campaigns creates ad campaigns
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function create_ad_campaigns() {
        
        $response = array();
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->CI->form_validation->set_rules('objective', 'Objective', 'trim|required');
            $this->CI->form_validation->set_rules('status', 'Status', 'trim|required');
            $this->CI->form_validation->set_rules('special_ad_category', 'Special Ad Category', 'trim|required');
            $this->CI->form_validation->set_rules('fb_page_id', 'Facebook Page ID', 'trim');
            $this->CI->form_validation->set_rules('instagram_id', 'Instagram ID', 'trim');
            $this->CI->form_validation->set_rules('ad_set_name', 'Ad Set Name', 'trim');
            $this->CI->form_validation->set_rules('optimization_goal', 'Optimization Goal', 'trim');
            $this->CI->form_validation->set_rules('billing_event', 'Billing Event', 'trim');
            $this->CI->form_validation->set_rules('selected_placements', 'Placements', 'trim');
            $this->CI->form_validation->set_rules('target_cost', 'Target Cost', 'trim');
            $this->CI->form_validation->set_rules('daily_budget', 'Daily Budget', 'trim');
            $this->CI->form_validation->set_rules('age_from', 'Age From', 'trim');
            $this->CI->form_validation->set_rules('age_to', 'Age To', 'trim');
            $this->CI->form_validation->set_rules('female_gender', 'Female Gender', 'trim');
            $this->CI->form_validation->set_rules('male_gender', 'Male Gender', 'trim');   
            $this->CI->form_validation->set_rules('mobile_type', 'Mobile Type', 'trim');
            $this->CI->form_validation->set_rules('desktop_type', 'Desktop Type', 'trim');
            $this->CI->form_validation->set_rules('ad_text', 'Ad Text', 'trim');
            $this->CI->form_validation->set_rules('website_url', 'Website Url', 'trim');
            $this->CI->form_validation->set_rules('headline', 'Headline', 'trim');
            $this->CI->form_validation->set_rules('description', 'Description', 'trim');
            $this->CI->form_validation->set_rules('countries', 'Countries', 'trim');
            $this->CI->form_validation->set_rules('region', 'Region', 'trim');
            $this->CI->form_validation->set_rules('city', 'City', 'trim');
            $this->CI->form_validation->set_rules('pixel_id', 'Pixel ID', 'trim');
            $this->CI->form_validation->set_rules('pixel_conversion_id', 'Pixel Conversion ID', 'trim');
            $this->CI->form_validation->set_rules('preview_image', 'Preview Image', 'trim');
            $this->CI->form_validation->set_rules('post_id', 'Post ID', 'trim');
            
            // Get data
            $name = $this->CI->input->post('name');
            $objective = $this->CI->input->post('objective');
            $status = $this->CI->input->post('status');
            $special_ad_category = $this->CI->input->post('special_ad_category');
            $fb_page_id = $this->CI->input->post('fb_page_id');
            $instagram_id = $this->CI->input->post('instagram_id');
            $ad_set_name = $this->CI->input->post('ad_set_name');
            $optimization_goal = $this->CI->input->post('optimization_goal');
            $billing_event = $this->CI->input->post('billing_event');
            $selected_placements = $this->CI->input->post('selected_placements');
            $target_cost = $this->CI->input->post('target_cost');
            $daily_budget = $this->CI->input->post('daily_budget');
            $age_from = $this->CI->input->post('age_from');
            $age_to = $this->CI->input->post('age_to');
            $female_gender = $this->CI->input->post('female_gender');
            $male_gender = $this->CI->input->post('male_gender');
            $mobile_type = $this->CI->input->post('mobile_type');
            $desktop_type = $this->CI->input->post('desktop_type');  
            $ad_text = $this->CI->input->post('ad_text');
            $website_url = $this->CI->input->post('website_url');
            $ad_name = $this->CI->input->post('ad_name');
            $headline = $this->CI->input->post('headline');
            $description = $this->CI->input->post('description');
            $adimage = $this->CI->input->post('adimage');
            $advideo = $this->CI->input->post('advideo');
            $countries = $this->CI->input->post('countries');
            $region = $this->CI->input->post('region');
            $city = $this->CI->input->post('city');
            $pixel_id = $this->CI->input->post('pixel_id');
            $pixel_conversion_id = $this->CI->input->post('pixel_conversion_id');
            $preview_image = $this->CI->input->post('preview_image');
            $post_id = $this->CI->input->post('post_id');
            
            $error = 0;
            
            // Verify if campaign's objective is valid
            if ( ( $objective !== 'LINK_CLICKS' ) && ( $objective !== 'PAGE_LIKES' ) && ( $objective !== 'POST_ENGAGEMENT' ) ) {
                
                $response[] = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('invalid_campaign_objective'),
                    'description' => $this->CI->lang->line('supported_campaign_objectives') . ': ' . $this->CI->lang->line('link_clicks') . ', ' . $this->CI->lang->line('page_likes') . ', ' . $this->CI->lang->line('post_engagement')
                );
                
                $error++;
                
            }          
            
            // Verify if campaign's status is valid
            if ( ( $status !== 'ACTIVE' ) && ( $status !== 'PAUSED' )  ) {
                
                $response[] = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('invalid_campaign_status'),
                    'description' => $this->CI->lang->line('allowed_campaign_statuses') . ': ' . $this->CI->lang->line('active') . ', ' . $this->CI->lang->line('paused')
                );
                
                $error++;
                
            }             

            if ( ( $this->CI->form_validation->run() !== false ) && ( $error < 1 ) ) {
                
                // Get selected account
                $get_account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');
                
                if ( $get_account ) {
                    
                    Api::init($this->app_id, $this->app_secret, $get_account[0]->token);

                    try {

                        $account_data = $this->fb->get(
                            '/' . $get_account[0]->net_id . '?fields=funding_source,name,account_status',
                            $get_account[0]->token
                        );

                    } catch (Facebook\Exceptions\FacebookResponseException $e) {

                        $response[] = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('campaign_not_created_successfully'),
                            'description' => $e->getMessage()
                        );

                    } catch (Facebook\Exceptions\FacebookSDKException $e) {

                        $response[] = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('campaign_not_created_successfully'),
                            'description' => $e->getMessage()
                        );

                    }
                    
                    $ad_account = $account_data->getDecodedBody();
                            
                    $args = array(
                        'account_id' => $get_account[0]->net_id,
                        'name' => $name,
                        'objective' => $objective,
                        'status' => $status,
                        'special_ad_category' => $special_ad_category
                    );

                    // Create Ad's campaign
                    $campaign_response = (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_campaign)->create_ad_campaigns($args);
                    
                    if ( $campaign_response['success'] ) {
                        
                        $response[] = array(
                            'success' => TRUE,
                            'message' => $this->CI->lang->line('campaign_created_successfully'),
                            'description' => $this->CI->lang->line('campaign_id') . ': ' . $campaign_response['campaign_id']
                        );
                        
                    } else {
                        
                        $response[] = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('campaign_not_created_successfully'),
                            'description' => $campaign_response['message']
                        );
                        
                    }

                    if ( isset($campaign_response['campaign_id']) ) {

                        if ( $ad_set_name ) {
                            
                            // Verify if ad sets placements are selected
                            if ( !$selected_placements ) {

                                $response[] = array(
                                    'success' => FALSE,
                                    'message' => $this->CI->lang->line('no_ad_set_placement_selected'),
                                    'description' => $this->CI->lang->line('select_at_least_one_placement')
                                );

                                $error++;

                            }
                            
                            $args = array(
                                'account_id' => $get_account[0]->net_id,
                                'name' => $ad_set_name,
                                'optimization_goal' => $optimization_goal,
                                'billing_event' => $billing_event,
                                'target_cost' => $target_cost,
                                'daily_budget' => $daily_budget,
                                'countries' => $countries,
                                'campaign_id' => $campaign_response['campaign_id'],
                                'selected_placements' => $selected_placements,
                                'age_from' => $age_from,
                                'age_to' => $age_to,
                                'female_gender' => $female_gender,
                                'male_gender' => $male_gender,
                                'mobile_type' => $mobile_type,
                                'desktop_type' => $desktop_type,
                                'net_id' => $get_account[0]->net_id,
                                'token' => $get_account[0]->token
                            );

                            if ( $region ) {
                                $args['region'] = $region;
                            }

                            if ( $city ) {
                                $args['city'] = $city;
                            }                            

                            // Create Ad's sets
                            $adsets_response = (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_adsets)->create_adsets($args);

                            if ( $adsets_response['success'] ) {

                                $response[] = array(
                                    'success' => TRUE,
                                    'message' => $this->CI->lang->line('ad_set_created_successfully'),
                                    'description' => $this->CI->lang->line('ad_set_id') . ': ' . $adsets_response['adset_id']
                                );

                            } else {

                                $response[] = array(
                                    'success' => FALSE,
                                    'message' => $this->CI->lang->line('ad_set_not_created_successfully'),
                                    'description' => $adsets_response['message']
                                );

                            }

                            if ( isset($ad_account['funding_source']) ) {

                                if ((isset($adsets_response['adset_id']) && filter_var($website_url, FILTER_VALIDATE_URL) && $ad_name) || ($objective === 'POST_ENGAGEMENT' && $post_id && $ad_name)) {

                                    // Verify if facebook page id exists
                                    if (!is_numeric($fb_page_id)) {

                                        $response[] = array(
                                            'success' => FALSE,
                                            'message' => $this->CI->lang->line('no_selected_facebook_page'),
                                            'description' => $this->CI->lang->line('facebook_page_should_be_selected')
                                        );

                                    } else {

                                        switch ($objective) {

                                            case 'LINK_CLICKS':

                                                $args = array(
                                                    'account_id' => $get_account[0]->net_id,
                                                    'objective' => $objective,
                                                    'ad_name' => $ad_name,
                                                    'ad_text' => $ad_text,
                                                    'website_url' => $website_url,
                                                    'adimage' => $adimage,
                                                    'preview_image' => $preview_image,
                                                    'video_id' => $advideo,
                                                    'fb_page_id' => $fb_page_id,
                                                    'instagram_id' => $instagram_id,
                                                    'headline' => $headline,
                                                    'description' => $description,
                                                    'adset_id' => $adsets_response['adset_id'],
                                                    'pixel_id' => $pixel_id,
                                                    'pixel_conversion_id' => $pixel_conversion_id,
                                                    'selected_placements' => $selected_placements,
                                                    'net_id' => $get_account[0]->net_id,
                                                    'token' => $get_account[0]->token
                                                );

                                                break;

                                            case 'POST_ENGAGEMENT':

                                                $args = array(
                                                    'account_id' => $get_account[0]->net_id,
                                                    'objective' => $objective,
                                                    'ad_name' => $ad_name,
                                                    'post_id' => $post_id,
                                                    'fb_page_id' => $fb_page_id,
                                                    'instagram_id' => $instagram_id,
                                                    'adset_id' => $adsets_response['adset_id'],
                                                    'pixel_id' => $pixel_id,
                                                    'pixel_conversion_id' => $pixel_conversion_id,
                                                    'selected_placements' => $selected_placements,
                                                    'net_id' => $get_account[0]->net_id,
                                                    'token' => $get_account[0]->token
                                                );

                                                break;
                                        }



                                        // Create Ad
                                        $response[] = (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_ads)->create_ads($args);

                                    }

                                } else {

                                    $response[] = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('ad_not_created'),
                                        'description' => $this->CI->lang->line('fill_in_all_required_fields')
                                    );

                                }

                            } else {

                                $response[] = array(
                                    'success' => FALSE,
                                    'message' => $this->CI->lang->line('ad_not_created'),
                                    'description' => $this->CI->lang->line('add_a_valid_payment_method_your_ad_account')
                                );

                            }

                        }

                    }
                    
                }
                
            }
            
        }
        
        $data = array(
            'success' => TRUE,
            'response' => $response
        );

        echo json_encode($data); 
        
    }
    
    /**
     * The public method create_ad_set creates ad set
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function create_ad_set() {
        
        $response = array();
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('campaign_id', 'Campaign ID', 'trim|required');
            $this->CI->form_validation->set_rules('objective', 'Objective', 'trim|required');
            $this->CI->form_validation->set_rules('fb_page_id', 'Facebook Page ID', 'trim');
            $this->CI->form_validation->set_rules('instagram_id', 'Instagram ID', 'trim');
            $this->CI->form_validation->set_rules('ad_set_name', 'Ad Set Name', 'trim');
            $this->CI->form_validation->set_rules('optimization_goal', 'Optimization Goal', 'trim');
            $this->CI->form_validation->set_rules('billing_event', 'Billing Event', 'trim');
            $this->CI->form_validation->set_rules('selected_placements', 'Placements', 'trim');
            $this->CI->form_validation->set_rules('target_cost', 'Target Cost', 'trim');
            $this->CI->form_validation->set_rules('daily_budget', 'Daily Budget', 'trim');
            $this->CI->form_validation->set_rules('age_from', 'Age From', 'trim');
            $this->CI->form_validation->set_rules('age_to', 'Age To', 'trim');
            $this->CI->form_validation->set_rules('female_gender', 'Female Gender', 'trim');
            $this->CI->form_validation->set_rules('male_gender', 'Male Gender', 'trim');   
            $this->CI->form_validation->set_rules('mobile_type', 'Mobile Type', 'trim');
            $this->CI->form_validation->set_rules('desktop_type', 'Desktop Type', 'trim');
            $this->CI->form_validation->set_rules('ad_text', 'Ad Text', 'trim');
            $this->CI->form_validation->set_rules('website_url', 'Website Url', 'trim');
            $this->CI->form_validation->set_rules('headline', 'Headline', 'trim');
            $this->CI->form_validation->set_rules('description', 'Description', 'trim');
            $this->CI->form_validation->set_rules('countries', 'Countries', 'trim');
            $this->CI->form_validation->set_rules('region', 'Region', 'trim');
            $this->CI->form_validation->set_rules('pixel_id', 'Pixel ID', 'trim');
            $this->CI->form_validation->set_rules('pixel_conversion_id', 'Pixel Conversion ID', 'trim');
            $this->CI->form_validation->set_rules('preview_image', 'Preview Image', 'trim');
            $this->CI->form_validation->set_rules('post_id', 'Post ID', 'trim');

            // Get data
            $campaign_id = $this->CI->input->post('campaign_id');
            $objective = $this->CI->input->post('objective');
            $fb_page_id = $this->CI->input->post('fb_page_id');
            $instagram_id = $this->CI->input->post('instagram_id');
            $ad_set_name = $this->CI->input->post('ad_set_name');
            $optimization_goal = $this->CI->input->post('optimization_goal');
            $billing_event = $this->CI->input->post('billing_event');
            $selected_placements = $this->CI->input->post('selected_placements');
            $target_cost = $this->CI->input->post('target_cost');
            $daily_budget = $this->CI->input->post('daily_budget');
            $age_from = $this->CI->input->post('age_from');
            $age_to = $this->CI->input->post('age_to');
            $female_gender = $this->CI->input->post('female_gender');
            $male_gender = $this->CI->input->post('male_gender');  
            $mobile_type = $this->CI->input->post('mobile_type');
            $desktop_type = $this->CI->input->post('desktop_type'); 
            $ad_text = $this->CI->input->post('ad_text');
            $website_url = $this->CI->input->post('website_url');
            $ad_name = $this->CI->input->post('ad_name');
            $headline = $this->CI->input->post('headline');
            $description = $this->CI->input->post('description');
            $adimage = $this->CI->input->post('adimage');
            $advideo = $this->CI->input->post('advideo');
            $countries = $this->CI->input->post('countries');
            $region = $this->CI->input->post('region');
            $city = $this->CI->input->post('city');
            $pixel_id = $this->CI->input->post('pixel_id');
            $pixel_conversion_id = $this->CI->input->post('pixel_conversion_id');
            $preview_image = $this->CI->input->post('preview_image');
            $post_id = $this->CI->input->post('post_id');
            
            $error = 0; 
            
            // Verify if ad sets placements are selected
            if ( !$selected_placements ) {
                
                $response[] = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('no_ad_set_placement_selected'),
                    'description' => $this->CI->lang->line('select_at_least_one_placement')
                );
                
                $error++;
                
            }             

            if ( ( $this->CI->form_validation->run() !== false ) && ( $error < 1 ) ) {
                
                // Get selected account
                $get_account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');
                
                if ( $get_account ) {
                    
                    $api = Api::init($this->app_id, $this->app_secret, $get_account[0]->token);

                    try {

                        $account_data = $this->fb->get(
                            '/' . $get_account[0]->net_id . '?fields=funding_source,name,account_status',
                            $get_account[0]->token
                        );

                    } catch (Facebook\Exceptions\FacebookResponseException $e) {

                        $response[] = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('campaign_not_created_successfully'),
                            'description' => $e->getMessage()
                        );

                    } catch (Facebook\Exceptions\FacebookSDKException $e) {

                        $response[] = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('campaign_not_created_successfully'),
                            'description' => $e->getMessage()
                        );

                    }

                    $ad_account = $account_data->getDecodedBody();

                    if ( $ad_set_name ) {

                        $args = array(
                            'account_id' => $get_account[0]->net_id,
                            'name' => $ad_set_name,
                            'optimization_goal' => $optimization_goal,
                            'billing_event' => $billing_event,
                            'target_cost' => $target_cost,
                            'daily_budget' => $daily_budget,
                            'countries' => $countries,
                            'campaign_id' => $campaign_id,
                            'selected_placements' => $selected_placements,
                            'age_from' => $age_from,
                            'age_to' => $age_to,
                            'female_gender' => $female_gender,
                            'male_gender' => $male_gender,
                            'mobile_type' => $mobile_type,
                            'desktop_type' => $desktop_type,
                            'net_id' => $get_account[0]->net_id,
                            'token' => $get_account[0]->token
                        );

                        if ( $region ) {
                            $args['region'] = $region;
                        }

                        if ( $city ) {
                            $args['city'] = $city;
                        }

                        // Create Ad's sets
                        $adsets_response = (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_adsets)->create_adsets($args);

                        if ( $adsets_response['success'] ) {

                            $response[] = array(
                                'success' => TRUE,
                                'message' => $this->CI->lang->line('ad_set_created_successfully'),
                                'description' => $this->CI->lang->line('ad_set_id') . ': ' . $adsets_response['adset_id']
                            );

                        } else {

                            $response[] = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('ad_set_not_created_successfully'),
                                'description' => $adsets_response['message']
                            );

                        }

                        if ( isset($ad_account['funding_source']) ) {

                            if ((isset($adsets_response['adset_id']) && filter_var($website_url, FILTER_VALIDATE_URL) && $ad_name) || ($objective === 'POST_ENGAGEMENT' && $post_id && $ad_name)) {

                                // Verify if facebook page id exists
                                if (!is_numeric($fb_page_id)) {

                                    $response[] = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('no_selected_facebook_page'),
                                        'description' => $this->CI->lang->line('facebook_page_should_be_selected')
                                    );

                                } else {

                                    switch ($objective) {

                                        case 'LINK_CLICKS':

                                            $args = array(
                                                'account_id' => $get_account[0]->net_id,
                                                'objective' => $objective,
                                                'ad_name' => $ad_name,
                                                'ad_text' => $ad_text,
                                                'website_url' => $website_url,
                                                'adimage' => $adimage,
                                                'preview_image' => $preview_image,
                                                'video_id' => $advideo,
                                                'fb_page_id' => $fb_page_id,
                                                'instagram_id' => $instagram_id,
                                                'headline' => $headline,
                                                'description' => $description,
                                                'adset_id' => $adsets_response['adset_id'],
                                                'pixel_id' => $pixel_id,
                                                'pixel_conversion_id' => $pixel_conversion_id,
                                                'selected_placements' => $selected_placements,
                                                'net_id' => $get_account[0]->net_id,
                                                'token' => $get_account[0]->token
                                            );

                                            break;

                                        case 'POST_ENGAGEMENT':

                                            $args = array(
                                                'account_id' => $get_account[0]->net_id,
                                                'objective' => $objective,
                                                'ad_name' => $ad_name,
                                                'post_id' => $post_id,
                                                'fb_page_id' => $fb_page_id,
                                                'instagram_id' => $instagram_id,
                                                'adset_id' => $adsets_response['adset_id'],
                                                'pixel_id' => $pixel_id,
                                                'pixel_conversion_id' => $pixel_conversion_id,
                                                'selected_placements' => $selected_placements,
                                                'net_id' => $get_account[0]->net_id,
                                                'token' => $get_account[0]->token
                                            );

                                            break;
                                    }



                                    // Create Ad
                                    $response[] = (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_ads)->create_ads($args);

                                }

                            } else {

                                $response[] = array(
                                    'success' => FALSE,
                                    'message' => $this->CI->lang->line('ad_not_created'),
                                    'description' => $this->CI->lang->line('fill_in_all_required_fields')
                                );

                            }

                        } else {

                            $response[] = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('ad_not_created'),
                                'description' => $this->CI->lang->line('add_a_valid_payment_method_your_ad_account')
                            );

                        }

                    }
                    
                }
                
            } else {
                
                $response[] = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('no_selected_ad_campaign'),
                    'description' => $this->CI->lang->line('please_select_ad_campaign')
                );  
                
            }
            
        }
        
        $data = array(
            'success' => TRUE,
            'response' => $response
        );

        echo json_encode($data); 
        
    }
    
    /**
     * The public method create_ad creates new ad
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */ 
    public function create_ad() {
        
        $response = array();
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('campaign_id', 'Campaign ID', 'trim|required');
            $this->CI->form_validation->set_rules('objective', 'Objective', 'trim|required');
            $this->CI->form_validation->set_rules('fb_page_id', 'Facebook Page ID', 'trim');
            $this->CI->form_validation->set_rules('instagram_id', 'Instagram ID', 'trim');
            $this->CI->form_validation->set_rules('ad_set_id', 'Ad Set ID', 'trim|required');
            $this->CI->form_validation->set_rules('ad_text', 'Ad Text', 'trim');
            $this->CI->form_validation->set_rules('website_url', 'Website Url', 'trim');
            $this->CI->form_validation->set_rules('headline', 'Headline', 'trim');
            $this->CI->form_validation->set_rules('description', 'Description', 'trim');
            $this->CI->form_validation->set_rules('countries', 'Countries', 'trim');
            $this->CI->form_validation->set_rules('pixel_id', 'Pixel ID', 'trim');
            $this->CI->form_validation->set_rules('pixel_conversion_id', 'Pixel Conversion ID', 'trim');
            $this->CI->form_validation->set_rules('preview_image', 'Preview Image', 'trim');
            $this->CI->form_validation->set_rules('post_id', 'Post ID', 'trim');

            // Get data
            $campaign_id = $this->CI->input->post('campaign_id');
            $objective = $this->CI->input->post('objective');
            $fb_page_id = $this->CI->input->post('fb_page_id');
            $instagram_id = $this->CI->input->post('instagram_id');
            $ad_set_id = $this->CI->input->post('ad_set_id');
            $ad_text = $this->CI->input->post('ad_text');
            $website_url = $this->CI->input->post('website_url');
            $ad_name = $this->CI->input->post('ad_name');
            $headline = $this->CI->input->post('headline');
            $description = $this->CI->input->post('description');
            $adimage = $this->CI->input->post('adimage');
            $advideo = $this->CI->input->post('advideo');
            $countries = $this->CI->input->post('countries');
            $pixel_id = $this->CI->input->post('pixel_id');
            $pixel_conversion_id = $this->CI->input->post('pixel_conversion_id');
            $preview_image = $this->CI->input->post('preview_image');
            $post_id = $this->CI->input->post('post_id');
            $error = 0;             

            if ( ( $this->CI->form_validation->run() !== false ) && ( $error < 1 ) ) {
                
                // Get selected account
                $get_account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');
                
                if ( $get_account ) {
                    
                   $api = Api::init($this->app_id, $this->app_secret, $get_account[0]->token);

                    try {

                        $account_data = $this->fb->get(
                            '/' . $get_account[0]->net_id . '?fields=funding_source,name,account_status',
                            $get_account[0]->token
                        );

                    } catch (Facebook\Exceptions\FacebookResponseException $e) {

                        $response[] = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('campaign_not_created_successfully'),
                            'description' => $e->getMessage()
                        );

                    } catch (Facebook\Exceptions\FacebookSDKException $e) {

                        $response[] = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('campaign_not_created_successfully'),
                            'description' => $e->getMessage()
                        );
                        
                    }

                    $ad_account = $account_data->getDecodedBody();

                    if ( isset($ad_account['funding_source']) ) {

                        if ((isset($ad_set_id) && filter_var($website_url, FILTER_VALIDATE_URL) && $ad_name) || ($objective === 'POST_ENGAGEMENT' && $post_id && $ad_name)) {

                            // Verify if facebook page id exists
                            if (!is_numeric($fb_page_id)) {

                                $response[] = array(
                                    'success' => FALSE,
                                    'message' => $this->CI->lang->line('no_selected_facebook_page'),
                                    'description' => $this->CI->lang->line('facebook_page_should_be_selected')
                                );

                            } else {

                                $platforms = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $ad_set_id . '/?fields=targeting{publisher_platforms}&access_token=' . $get_account[0]->token), true);

                                if (@$platforms['targeting']['publisher_platforms']) {

                                    $selected_placements = array();

                                    foreach ($platforms['targeting']['publisher_platforms'] as $platform) {

                                        if ($platform === 'facebook') {

                                            $selected_placements[] = array(
                                                '1' => 'ad-set-placement-facebook-feeds'
                                            );

                                        } else if ($platform === 'messenger') {

                                            $selected_placements[] = array(
                                                '1' => 'ad-set-placement-messenger-inbox'
                                            );

                                        } else if ($platform === 'instagram') {

                                            $selected_placements[] = array(
                                                '1' => 'ad-set-placement-instagram-feed'
                                            );

                                        }

                                    }

                                    switch ($objective) {

                                        case 'LINK_CLICKS':

                                            $args = array(
                                                'account_id' => $get_account[0]->net_id,
                                                'objective' => $objective,
                                                'ad_name' => $ad_name,
                                                'ad_text' => $ad_text,
                                                'website_url' => $website_url,
                                                'adimage' => $adimage,
                                                'preview_image' => $preview_image,
                                                'video_id' => $advideo,
                                                'fb_page_id' => $fb_page_id,
                                                'instagram_id' => $instagram_id,
                                                'headline' => $headline,
                                                'description' => $description,
                                                'adset_id' => $ad_set_id,
                                                'pixel_id' => $pixel_id,
                                                'pixel_conversion_id' => $pixel_conversion_id,
                                                'selected_placements' => $selected_placements,
                                                'net_id' => $get_account[0]->net_id,
                                                'token' => $get_account[0]->token
                                            );

                                            break;

                                        case 'POST_ENGAGEMENT':

                                            $args = array(
                                                'account_id' => $get_account[0]->net_id,
                                                'objective' => $objective,
                                                'ad_name' => $ad_name,
                                                'post_id' => $post_id,
                                                'fb_page_id' => $fb_page_id,
                                                'instagram_id' => $instagram_id,
                                                'adset_id' => $ad_set_id,
                                                'pixel_id' => $pixel_id,
                                                'pixel_conversion_id' => $pixel_conversion_id,
                                                'selected_placements' => $selected_placements,
                                                'net_id' => $get_account[0]->net_id,
                                                'token' => $get_account[0]->token
                                            );

                                            break;
                                    }

                                    // Create Ad
                                    $response[] = (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_ads)->create_ads($args);

                                } else {

                                    $response[] = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('no_platforms_selected'),
                                        'description' => $this->CI->lang->line('your_ad_set_should_have_platforms')
                                    );

                                }

                            }

                        } else if ($website_url) {

                            $response[] = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('ad_set_is_required'),
                                'description' => $this->CI->lang->line('please_select_ad_set')
                            );

                        } else {

                            $response[] = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('ad_not_created'),
                                'description' => $this->CI->lang->line('fill_in_all_required_fields')
                            );

                        }

                    } else {

                        $response[] = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('ad_not_created'),
                            'description' => $this->CI->lang->line('add_a_valid_payment_method_your_ad_account')
                        );
                        
                    }
                    
                }
                
            } else {
                
                $response[] = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('no_selected_ad_campaign_or_ad_set'),
                    'description' => $this->CI->lang->line('please_select_ad_campaign_or_ad_set')
                );  
                
            }
            
        }
        
        $data = array(
            'success' => TRUE,
            'response' => $response
        );

        echo json_encode($data); 
        
    }
    
}

/* End of file ad_creator.php */
