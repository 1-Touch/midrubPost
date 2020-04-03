<?php
/**
 * Facebook Ad Ads Helper
 *
 * This file contains the class Ad_ads
 * with methods to manage Ad's ads
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
use FacebookAds\Object\Ad;
use FacebookAds\Object\Fields\AdFields;

/*
 * Ad_ads class provides the methods to manage Ad's ads
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
*/
class Ad_ads {
    
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
     * The public method create_ads creates ads
     * 
     * @param array $args contains the arguments to create an ad's caßmpaign
     * 
     * @since 0.0.7.6
     * 
     * @return integer with adsets id or false
     */ 
    public function create_ads($args) {
        
        if ( $args ) {

            $response = array();

            $responses[] = (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_creatives)->create_creative($args, $args['selected_placements']);
            
            return array(
                'ads' => $responses
            );            
            
        } else {
         
            return array(
                'success' => FALSE,
                'message' => 'Invalid parameters.'
            );
            
        }        
        
    }
    
    /**
     * The public method delete_ads deletes ads
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function delete_ads() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('ads', 'Ads', 'trim');
            $this->CI->form_validation->set_rules('account', 'Account', 'trim|required');
            
            // Get data
            $ads = $this->CI->input->post('ads');
            $account = $this->CI->input->post('account');

            if ( $this->CI->form_validation->run() !== false ) {
                
                // Get selected account
                $get_account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');

                if ( $get_account ) {

                    $count = 0;

                    foreach ( $ads as $ad ) {

                        if ( is_numeric($ad[1]) ) {

                            $response = $this->fb->delete(
                                '/' . $ad[1],
                                array(),
                                $get_account[0]->token
                            );
                            
                            if ( $response ) {
                                
                                $count++;
                            
                            }

                        }

                    }
                    
                    if ( $count ) {
                        
                        $data = array(
                            'success' => TRUE,
                            'message' => $this->CI->lang->line('the_selected_ads_were_deleted')
                        );

                        echo json_encode($data); 
                        exit();
                        
                    } else {
                        
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('the_selected_ads_were_not_deleted')
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
    
    /**
     * The public method load_select_ads loads ads list for select dropdown
     *
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function load_select_ads() {
        
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
                        
                        $ads = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/ads?fields=name&limit=1000&access_token=' . $account[0]->token), true);

                        if ( $ads ) {
                            
                            $all_ads = array(
                                'data'
                            );

                            $i = 0;

                            foreach ( $ads['data'] as $ad ) {

                                if ( preg_match("/{$key}/i", $ad['name'] ) ) {
                                    $all_ads['data'][] = $ad;
                                    $i++;
                                }

                                if ( $i > 9 ) {
                                    break;
                                }

                            }
                            
                            if ( isset($all_ads['data']) ) {

                                $data = array(
                                    'success' => TRUE,
                                    'ad_ads' => $all_ads
                                );

                                echo json_encode($data);
                                exit();
                                
                            }

                        }                        
                        
                    } else {

                        $ads = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/ads?fields=name&limit=10&access_token=' . $account[0]->token), true);

                        if ( $ads ) {

                            $data = array(
                                'success' => TRUE,
                                'ad_ads' => $ads
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
            'message' => $this->CI->lang->line('no_ads_found')
        );

        echo json_encode($data);
        
    }
    
}

/* End of file ad_ads.php */
