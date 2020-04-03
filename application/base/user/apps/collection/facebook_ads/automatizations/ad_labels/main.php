<?php
/**
 * Midrub Ad Labels Automatization
 *
 * This file loads the Ad_labels app
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
 */

namespace MidrubBase\User\Apps\Collection\Facebook_ads\Automatizations\Ad_labels;

// Define Constants
defined('BASEPATH') OR exit('No direct script access allowed');
defined('MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_LABELS_PATH') OR define('MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_LABELS_PATH', MIDRUB_BASE_USER . 'apps/collection/facebook_ads/automatizations/ad_labels/');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Facebook_ads\Interfaces as MidrubBaseUserAppsCollectionFacebook_adsInterfaces;
use MidrubBase\User\Apps\Collection\Facebook_ads\Automatizations\Ad_labels\Controllers as MidrubBaseUserAppsCollectionFacebook_adsAutomatizationsAd_labelsControllers;

/*
 * Main class loads the Ad_labels automatization loader
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
        
        // Load models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_LABELS_PATH . 'models/', 'Ads_labels_model', 'ads_labels_model' );
        
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
        (new MidrubBaseUserAppsCollectionFacebook_adsAutomatizationsAd_labelsControllers\User)->view();
        
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
        (new MidrubBaseUserAppsCollectionFacebook_adsAutomatizationsAd_labelsControllers\User)->modals();
        
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
            (new MidrubBaseUserAppsCollectionFacebook_adsAutomatizationsAd_labelsControllers\Ajax)->$action();

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

        // Load models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_LABELS_PATH . 'models/', 'Ads_labels_model', 'ads_labels_model' );
        
        // Get active ads
        $active_ads = $this->CI->ads_labels_model->get_active_ads();
        
        if ( $active_ads ) {
            
            foreach ( $active_ads as $active_ad ) {

                // Verify if ad is not paused or archived
                $get_status = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $active_ad['ad_id'] . '?fields=status&access_token=' . $active_ad['token']), true);
                
                if ( isset($get_status["status"]) ) {

                    if ( ($get_status["status"] === 'ARCHIVED') || ($get_status["status"] === 'PAUSED') ) {

                        // Serialize the error
                        $error = "The ad has status:" . $get_status["status"];
                        $status = 3;
        
                        // Update label stats
                        $this->CI->ads_labels_model->update_label_stats($active_ad['stat_id'], $status, $error);
                        continue;
                        
                    }

                } else {
    
                    // Serialize the error
                    $error = "The ad was not found.";
                    $status = 3;
    
                    // Update label stats
                    $this->CI->ads_labels_model->update_label_stats($active_ad['stat_id'], $status, $error);
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
        
                        // Update label stats
                        $this->CI->ads_labels_model->update_label_stats($active_ad['stat_id'], $status, $error);
        
                    }
        
                }

            }

        }
        
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
    public function delete_account($user_id) {

        // Load models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_LABELS_PATH . 'models/', 'Ads_labels_model', 'ads_labels_model' );

        // Delete label records by user_id
        $this->CI->ads_labels_model->delete_label_records_by_user( $user_id );
        
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
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_LABELS_PATH . 'models/', 'Ads_labels_model', 'ads_labels_model' );

        // Add hook in the queue
        add_hook(
            'delete_fb_ads_label',

            function ($args) {

                // Delete ad labels records
                $this->CI->ads_labels_model->delete_label_records( $args['label_id'] );

            }

        );

        // Add hook in the queue
        add_hook(
            'delete_ad_account',

            function ($args) {

                // Delete ad labels records by account
                $this->CI->ads_labels_model->delete_label_records_by_account( $args['account_id'] );

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
        $this->CI->lang->load( 'facebook_ads_labels_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_LABELS_PATH );
        
        // Return automatization information
        return array(
            'automatization_name' => $this->CI->lang->line('fb_labels_ad_labels'),
            'display_automatization_name' => $this->CI->lang->line('fb_labels_ad_labels'),
            'automatization_slug' => 'automatization-ad-labels',
            'automatization_icon' => '<i class="icon-rocket"></i>',
            'version' => '0.0.1',
            'required_version' => '0.0.7.7'
        );
        
    }

}

/* End of file main.php */