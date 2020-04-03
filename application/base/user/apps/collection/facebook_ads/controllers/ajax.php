<?php
/**
 * Ajax Controller
 *
 * This file processes the app's ajax calls
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Facebook_ads\Controllers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Facebook_ads\Helpers as MidrubBaseUserAppsCollectionFacebook_adsHelpers;

/*
 * Ajaz class processes the app's ajax calls
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */
class Ajax {
    
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
        
        // Load models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'models/', 'Ads_networks_model', 'ads_networks_model' );
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'models/', 'Ads_account_model', 'ads_account_model' );
        
    }
    
    /**
     * The public method load_ad_accounts load ad accounts by page
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_ad_accounts() {
        
        // Load Ad Accounts
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Start)->load_ad_accounts();
        
    }
    
    /**
     * The public method quick_ad_accounts loads the last 20 ad accounts
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function quick_ad_accounts() {
        
        // Load Ad Accounts
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Start)->quick_ad_accounts();
        
    }
    
    /**
     * The public method load_ad_account_overview loads Ad Account's overview
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_ad_account_overview() {
        
        // Load Ad Account overview
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Start)->load_ad_account_overview();
        
    }
    
    /**
     * The public method load_ad_account_campaigns loads Ad Account's campaigns
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_ad_account_campaigns() {
        
        // Load Ad Account campaigns
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Start)->load_ad_account_campaigns();
        
    }
    
    /**
     * The public method load_ad_account_adsets loads Ad Account's adsets
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_ad_account_adsets() {
        
        // Load Ad Account adsets
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Start)->load_ad_account_adsets();
        
    }
    
    /**
     * The public method load_ad_account_ads loads Ad Account's ads
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_ad_account_ads() {
        
        // Load Ad Account ads
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Start)->load_ad_account_ads();
        
    }
    
    /**
     * The public method load_ad_account_pixel_conversions loads Pixel's conversions
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_ad_account_pixel_conversions() {
        
        // Load Pixel's conversions
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Start)->load_ad_account_pixel_conversions();
        
    }
    
    /**
     * The public method delete_ad_account deletes ad accounts
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function delete_ad_account() {
        
        // Delete Ad Accounts
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_connector)->delete_ad_account();
        
    }
    
    /**
     * The public method select_ad_account selects account
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function select_ad_account() {
        
        // Select Ad Accounts
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_connector)->select_ad_account();
        
    }    
    
    /**
     * The public method unselect_ad_account unselects account
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function unselect_ad_account() {
        
        // Unselect Ad Accounts
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_connector)->unselect_ad_account();
        
    }
    
    /**
     * The public method load_campaigns_by_pagination loads campaigns by page
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_campaigns_by_pagination() {
        
        // Load Campaigns
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Pagination)->load_campaigns_by_pagination();
        
    }
    
    /**
     * The public method load_ad_sets_by_pagination loads sets by pagination
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_ad_sets_by_pagination() {
        
        // Load Ad Sets by pagination
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Pagination)->load_ad_sets_by_pagination();
        
    }
    
    /**
     * The public method load_ads_by_pagination loads ads by pagination
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_ads_by_pagination() {
        
        // Load Ads by pagination
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Pagination)->load_ads_by_pagination();
        
    }
    
    /**
     * The public method load_pixel_conversions_by_pagination loads pixel's conversions by pagination
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_pixel_conversions_by_pagination() {
        
        // Load Ad Conversions by pagination
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Pagination)->load_pixel_conversions_by_pagination();
        
    }
    
    /**
     * The public method delete_ad_campaigns deletes campaigns
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function delete_ad_campaigns() {
        
        // Delete campaigns
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_connector)->delete_ad_campaigns();
        
    }
    
    /**
     * The public method delete_ad_sets deletes ad sets
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function delete_ad_sets() {
        
        // Delete ad sets
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_connector)->delete_ad_sets();
        
    }
    
    /**
     * The public method delete_ads deletes ads
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function delete_ads() {
        
        // Delete ads
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_ads)->delete_ads();
        
    }
    
    /**
     * The public method create_ad_campaigns creates campaigns
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function create_ad_campaigns() {
        
        // Create campaigns
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_creator)->create_ad_campaigns();
        
    }
    
    /**
     * The public method create_ad_set creates ad sets
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function create_ad_set() {
        
        // Create ad sets
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_creator)->create_ad_set();
        
    }
    
    /**
     * The public method create_ad creates ad
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function create_ad() {
        
        // Create ad
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_creator)->create_ad();
        
    }
    
    /**
     * The public method display_connected_instagram_accounts display connected Instagram accounts
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function display_connected_instagram_accounts() {
        
        // Create ad sets
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_connector)->display_connected_instagram_accounts();
        
    }
    
    /**
     * The public method upload_media_on_facebook uploads media files on Facebook
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function upload_media_on_facebook() {
        
        // Uppload media
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_media)->upload_media_on_facebook();
        
    }
    
    /**
     * The public method delete_ad_media deletes ad's media
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function delete_ad_media() {
        
        // Delete Ad's media
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_media)->delete_ad_media();
        
    }
    
    /**
     * The public method create_pixel_conversion create Pixel's conversion
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function create_pixel_conversion() {
        
        // Create Pixel's conversion
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Pixel)->create_pixel_conversion();
        
    }
    
    /**
     * The public method load_all_pixel_coversions loads all Pixel's conversions
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_all_pixel_coversions() {
        
        // Load all Pixel's conversions
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Pixel)->load_all_pixel_coversions();
        
    } 
    
    /**
     * The public method filter_pixel_coversions search for Pixel's conversions
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function filter_pixel_coversions() {
        
        // Search Pixel's conversions
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Pixel)->filter_pixel_coversions();
        
    }
    
    /**
     * The public method load_account_overview loads account stats
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_account_overview() {
        
        // Load account's stats
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Load_account)->load_account_overview();
        
    }
    
    /**
     * The public method load_ad_identity load Ad's identity
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_ad_identity() {
        
        // Load Ad's identity
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Load_account)->load_account_pages();
        
    } 
    
    /**
     * The public method load_ad_account_details load Ad's account
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_ad_account_details() {
        
        // Load Ad's account
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Load)->account_details();
        
    } 
    
    /**
     * The public method load_select_ad_campaigns gets Ad's Campaigns
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_select_ad_campaigns() {
        
        // Load Ad's Campaigns
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_campaign)->load_select_campaigns();
        
    }
    
    /**
     * The public method load_select_ad_sets gets Ad's Sets
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function load_select_ad_sets() {
        
        // Load Ad's Sets
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_adsets)->load_select_ad_sets();
        
    }
    
    /**
     * The public method load_select_all_ad_sets gets Ad's Sets
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function load_select_all_ad_sets() {
        
        // Load Ad's Sets
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_adsets)->load_select_all_ad_sets();
        
    }
    
    /**
     * The public method load_select_ads gets Ads list
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function load_select_ads() {
        
        // Load Ads list
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_ads)->load_select_ads();
        
    }
    
    /**
     * The public method select_facebook_campaign selects Ad's Campaign
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function select_facebook_campaign() {
        
        // Select Ad's Campaign
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_campaign)->select_facebook_campaign();
        
    }
    
    /**
     * The public method load_account_insights gets Ad Account's Insights
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function load_account_insights() {
        
        // Get Ad Account's Insights
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_insights)->load_account_insights();
        
    }
    
    /**
     * The public method ad_campaigns_insights_by_time gets Ad Campaign's Insights
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function ad_campaigns_insights_by_time() {
        
        // Get Ad Campaign's Insights
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_insights)->ad_campaigns_insights_by_time();
        
    }
    
    /**
     * The public method ad_sets_insights_by_time gets Ad Set's Insights
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function ad_sets_insights_by_time() {
        
        // Get Ad Set's Insights
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_insights)->ad_sets_insights_by_time();
        
    }
    
    /**
     * The public method ad_insights_by_time gets Ad's Insights
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function ad_insights_by_time() {
        
        // Get Ad's Insights
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_insights)->ad_insights_by_time();
        
    }
    
    /**
     * The public method insights_download_for_account downloads Ad Account's Insights
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function insights_download_for_account() {
        
        // Downloads Ad Account's Insights
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_insights)->insights_download_for_account();
        
    }
    
    /**
     * The public method insights_download_for_campaigns downloads Ad Campaigns's Insights
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function insights_download_for_campaigns() {
        
        // Downloads Ad Account's Insights
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_insights)->insights_download_for_campaigns();
        
    }
    
    /**
     * The public method insights_download_for_ad_sets downloads Ad Set's Insights
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function insights_download_for_ad_sets() {
        
        // Downloads Ad Set's Insights
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_insights)->insights_download_for_ad_sets();
        
    }
    
    /**
     * The public method insights_download_for_ad downloads Ad's Insights
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function insights_download_for_ad() {
        
        // Downloads Ad's Insights
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_insights)->insights_download_for_ad();
        
    }
    
    /**
     * The public method load_posts_for_boosting gets posts for boosting
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function load_posts_for_boosting() {
        
        // Get posts
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Boost)->load_posts_for_boosting();
        
    }    
    
    /**
     * The public method get_post_data_for_boost gets post for boosting
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function get_post_data_for_boost() {
        
        // Get post
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Boost)->get_post_data_for_boost();
        
    }
    
    /**
     * The public method load_countries loads regions
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */
    public function load_regions() {
        
        // Get regions
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Targeting)->load_regions();
        
    }

    /**
     * The public method load_cities loads cities
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function load_cities() {
        
        // Get cities
        (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Targeting)->load_cities();
        
    }
    
}

/* End of file ajax.php */