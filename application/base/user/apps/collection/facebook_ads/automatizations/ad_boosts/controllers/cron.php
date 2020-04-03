<?php
/**
 * Cron Controller
 *
 * This file loads the Facebook Ad Boosts's cron job commands
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
use MidrubApps\Collection\Facebook_ads\Helpers as MidrubAppsCollectionFacebook_adsHelpers;
use MidrubBase\User\Apps\Collection\Facebook_ads\Helpers as MidrubBaseUserAppsCollectionFacebook_adsHelpers;
use FacebookAds\Object\AdAccountActivity;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdAccountFields;
use FacebookAds\Logger\CurlLogger;

/*
 * Cron class loads the app's cron job commands
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
 */
class Cron {
    
    /**
     * Class variables
     *
     * @since 0.0.7.7
     */
    protected $CI, $fb, $app_id, $app_secret;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.7
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();

        // Load models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_BOOSTS_PATH . 'models/', 'Ads_boosts_posts_model', 'ads_boosts_posts_model' );
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_BOOSTS_PATH . 'models/', 'Ads_boosts_model', 'ads_boosts_model' );
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
     * The public method run runs the cron job methods
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function run() {

        // Get active ads
        $active_ads = $this->CI->ads_boosts_model->get_active_ads();

        if ( $active_ads ) {
            
            foreach ( $active_ads as $active_ad ) {

                // Verify if ad is not paused or archived
                $get_status = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $active_ad['ad_id'] . '?fields=status&access_token=' . $active_ad['token']), true);
                
                if ( isset($get_status["status"]) ) {

                    if ( ($get_status["status"] === 'ARCHIVED') || ($get_status["status"] === 'PAUSED') ) {

                        // Serialize the error
                        $error = "The ad has status:" . $get_status["status"];
                        $status = 3;
        
                        // Update boost stats
                        $this->CI->ads_boosts_model->update_boost_stats($active_ad['stat_id'], $status, $error);
                        continue;
                        
                    }

                } else {
    
                    // Serialize the error
                    $error = "The ad was not found.";
                    $status = 3;
    
                    // Update boost stats
                    $this->CI->ads_boosts_model->update_boost_stats($active_ad['stat_id'], $status, $error);
                    continue;

                }

                // Get ad insights
                $ad_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $active_ad['ad_id'] . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-1 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $active_ad['token']), true);

                if ( !empty($ad_insights['data']) ) {

                    // Spend variable
                    $spend = 0;
        
                    foreach ($ad_insights['data'] as $data) {
        
                        $spend = $spend + $data['spend'];
                    }
        
                    // Verify if the ad has reached the limit
                    if ($spend >= $active_ad['meta_value']) {
        
                        // Try to disable the ad
                        $response = json_decode(
                            post(
                                'https://graph.facebook.com/' . MIDRUB_ADS_FACEBOOK_GRAPH_VERSION . '/' . $active_ad['ad_id'] . '?access_token=' . $active_ad['token'],
                                array('status' => 'PAUSED')
                            ),
                            true
                        );
        
                        // Error var
                        $error = '';
        
                        // Status
                        $status = 2;
        
                        // Verify if an error has occurred
                        if (isset($response['error'])) {
        
                            // Serialize the error
                            $error = serialize($response['error']);
                            $status = 3;
                        }
        
                        // Update boost stats
                        $this->CI->ads_boosts_model->update_boost_stats($active_ad['stat_id'], $status, $error);
        
                    }
        
                }

            }

        }
        
        // Get last 24 hour posts
        $posts = $this->CI->ads_boosts_posts_model->get_posts();

        if ( $posts ) {

            foreach ( $posts as $post ) {

                if ( $this->CI->ads_boosts_model->get_boost_stats($post->post_id) ) {
                    continue;
                }

                $networks = $this->CI->ads_boosts_posts_model->all_social_networks_by_post_id($post->post_id);

                // Verify if post_id already exists in the stats
                $networks = $this->CI->ads_boosts_posts_model->all_social_networks_by_post_id($post->post_id);

                // Get post meta
                $networks = $this->CI->ads_boosts_posts_model->all_social_networks_by_post_id($post->post_id);

                if ( $networks ) {

                    if ( $post->fb_boost_id > 0 ) {

                        // Get boost option by id
                        $boost = $this->CI->ads_boosts_model->get_boost_single($post->user_id, $post->fb_boost_id);

                        if ($boost) {

                            // Get selected account
                            $get_account = $this->CI->ads_account_model->get_account($post->user_id, 'facebook');

                            if ($get_account) {

                                // Get ad set
                                $ad_set_id = $this->CI->ads_boosts_model->get_boost_meta($post->fb_boost_id, 'ad_set_id');

                                if (!$ad_set_id) {
                                    $this->CI->ads_boosts_model->save_boost_stats($post->fb_boost_id, $post->post_id, '', 3, $this->CI->lang->line('fb_boosts_ad_set_required'), $boost[0]->boost_name, 0, time(), (time() + $time));
                                    continue;
                                }

                                $platforms = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $ad_set_id . '/?fields=targeting{publisher_platforms}&access_token=' . $get_account[0]->token), true);

                                if (@$platforms['targeting']['publisher_platforms']) {

                                    $selected_placements = array();

                                    $instagram_required = 0;

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

                                            $instagram_required++;

                                            $selected_placements[] = array(
                                                '1' => 'ad-set-placement-instagram-feed'
                                            );

                                        }

                                    }

                                    // Get facebook page
                                    $facebook_page_id = $this->CI->ads_boosts_model->get_boost_meta($post->fb_boost_id, 'facebook_page_id');

                                    if (!$facebook_page_id) {
                                        $this->CI->ads_boosts_model->save_boost_stats($post->fb_boost_id, $post->post_id, '', 3, $this->CI->lang->line('fb_boosts_facebook_page_required'), $boost[0]->boost_name, 0, time(), (time() + $time));
                                        continue;
                                    }

                                    // Get instagram account
                                    $instagram_id = $this->CI->ads_boosts_model->get_boost_meta($post->fb_boost_id, 'instagram_id');

                                    if ( !$instagram_id ) {

                                        if ( $instagram_required ) {
                                            $this->CI->ads_boosts_model->save_boost_stats($post->fb_boost_id, $post->post_id, '', 3, $this->CI->lang->line('fb_boosts_instagram_account_required'), $boost[0]->boost_name, 0, time(), (time() + $time));
                                            continue;
                                        }

                                    }

                                    Api::init($this->app_id, $this->app_secret, $get_account[0]->token);

                                    $args = array(
                                        'account_id' => $get_account[0]->net_id,
                                        'objective' => 'POST_ENGAGEMENT',
                                        'ad_name' => $boost[0]->boost_name,
                                        'post_id' => $networks[0]['published_id'],
                                        'fb_page_id' => $facebook_page_id,
                                        'adset_id' => $ad_set_id,
                                        'pixel_id' => '',
                                        'pixel_conversion_id' => '',
                                        'selected_placements' => $selected_placements,
                                        'net_id' => $get_account[0]->net_id,
                                        'token' => $get_account[0]->token
                                    );

                                    if ($instagram_id) {

                                        $args['instagram_id'] = $instagram_id;
                                    }

                                    try {

                                        // Create Ad
                                        $response = (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_ads)->create_ads($args);

                                        if (@$response['ads']) {

                                            $error = '';
                                            $status = 2;

                                            if (isset($response['error'])) {

                                                $error = serialize($response['error']);
                                                $status = 3;
                                            }

                                            $time = $boost[0]->time;

                                            switch ($time) {

                                                case '1':

                                                    $time = 3600;

                                                    break;

                                                case '2':

                                                    $time = 10800;

                                                    break;

                                                case '3':

                                                    $time = 18000;

                                                    break;

                                                case '4':

                                                    $time = 36000;

                                                    break;

                                                case '5':

                                                    $time = 86400;

                                                    break;

                                                case '6':

                                                    $time = 172800;

                                                    break;

                                                default:

                                                    $time = 3600;

                                                    break;
                                            }

                                            $this->CI->ads_boosts_model->save_boost_stats($post->fb_boost_id, $post->post_id, serialize($platforms['targeting']['publisher_platforms']), 1, '', $boost[0]->boost_name, $response['ads'][0]['id'], time(), (time() + $time));
                                            break;
                                        }

                                    } catch (Exception $e) {

                                        $this->CI->ads_boosts_model->save_boost_stats($post->fb_boost_id, $post->post_id, serialize($platforms['targeting']['publisher_platforms']), 3, serialize($e->getMessage()), $boost[0]->boost_name, 0, time(), (time() + $time));

                                    }

                                }

                            }

                        }

                    }

                }

            }

        }
       
    }

}

/* End of file cron.php */