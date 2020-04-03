<?php
/**
 * Midrub Ad Boosts Automatization
 *
 * This file loads the Ad_boosts app
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Facebook_ads\Automatizations\Ad_boosts;

// Define Constants
defined('BASEPATH') OR exit('No direct script access allowed');
defined('MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_BOOSTS_PATH') OR define('MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_BOOSTS_PATH', MIDRUB_BASE_USER . 'apps/collection/facebook_ads/automatizations/ad_boosts/');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Facebook_ads\Interfaces as MidrubBaseUserAppsCollectionFacebook_adsInterfaces;
use MidrubBase\User\Apps\Collection\Facebook_ads\Automatizations\Ad_boosts\Controllers as MidrubBaseUserAppsCollectionFacebook_adsAutomatizationsAd_boostsControllers;

/*
 * Main class loads the Ad_boosts automatization loader
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
 */
class Main implements MidrubBaseUserAppsCollectionFacebook_adsInterfaces\Automatizations {
    
    /**
     * Class variables
     *
     * @since 0.0.7.7
     */
    protected
            $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.7
     */
    public function __construct() {
        
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();
        
    }
    
    /**
     * The public method user loads the automatization's main page in the user panel
     * 
     * @since 0.0.7.7
     * 
     * @return string with html
     */
    public function user() {
        
        // Load user's view
        (new MidrubBaseUserAppsCollectionFacebook_adsAutomatizationsAd_boostsControllers\User)->view();
        
    }
    
    /**
     * The public method modals loads the automatization's modals in the user panel
     * 
     * @since 0.0.7.7
     * 
     * @return string with html
     */
    public function modals() {
        
        // Load user's modals
        (new MidrubBaseUserAppsCollectionFacebook_adsAutomatizationsAd_boostsControllers\User)->modals();
        
    }    
    
    /**
     * The public method ajax processes the ajax's requests
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function ajax() {
        
        // Get action's get input
        $action = $this->CI->input->get('action');

        if ( !$action ) {
            $action = $this->CI->input->post('action');
        }
        
        try {

            // Call method if exists
            (new MidrubBaseUserAppsCollectionFacebook_adsAutomatizationsAd_boostsControllers\Ajax)->$action();

        } catch (Exception $ex) {

            $data = array(
                'success' => FALSE,
                'message' => $ex->getMessage()
            );

            echo json_encode($data);

        }
        
    }
    
    /**
     * The public method cron_jobs loads the cron jobs commands
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function cron_jobs() {

        // Call method if exists
        (new MidrubBaseUserAppsCollectionFacebook_adsAutomatizationsAd_boostsControllers\Cron)->run();

    }
    
    /**
     * The public method delete_account is called when user's account is deleted
     * 
     * @param integer $user_id contains the user's ID
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function delete_account( $user_id ) {

        // Load models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_BOOSTS_PATH . 'models/', 'Ads_boosts_model', 'ads_boosts_model' );
        
        // Delete boost records by user_id
        $this->CI->ads_boosts_model->delete_boost_records_by_user( $user_id );

    }
    
    /**
     * The public method load_hooks contains the automatization's hooks
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function load_hooks() {
        
        // Load models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_BOOSTS_PATH . 'models/', 'Ads_boosts_model', 'ads_boosts_model' );

        // Add hook in the queue
        add_hook(
            'delete_fb_ads_boost',

            function ($args) {

                // Delete ad boost's records
                $this->CI->ads_boosts_model->delete_boost_records( $args['boost_id'] );

            }

        );

        // Add hook in the queue
        add_hook(
            'delete_ad_account',

            function ($args) {

                // Delete ad boost's records by account
                $this->CI->ads_boosts_model->delete_boost_records_by_account( $args['account_id'] );

            }

        );
        
    }
    
    /**
     * The public method automatization_info contains the automatization's info
     * 
     * @since 0.0.7.7
     * 
     * @return array with automatization's information
     */
    public function automatization_info() {

        // Load the automatization's language files
        $this->CI->lang->load( 'facebook_ads_boosts_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_BOOSTS_PATH );
        
        // Return automatization information
        return array(
            'automatization_name' => $this->CI->lang->line('fb_boosts_ad_boosts'),
            'display_automatization_name' => $this->CI->lang->line('fb_boosts_ad_boosts'),
            'automatization_slug' => 'automatization-ad-boosts',
            'automatization_icon' => '<i class="fas fa-project-diagram"></i>',
            'version' => '0.0.1',
            'required_version' => '0.0.7.7'
        );
        
    }

}

/* End of file main.php */