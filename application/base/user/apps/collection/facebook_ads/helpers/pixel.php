<?php
/**
 * Pixel Helper
 *
 * This file contains the class Pixel Helper
 * with methods to manage Facebook Pixel
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
 * Pixel class provides the methods to manage Facebook Pixel
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
*/
class Pixel {
    
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
     * The public method create_pixel_conversion creates Pixel's conversions
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function create_pixel_conversion() {
        
        $response = array();
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->CI->form_validation->set_rules('conversion_url', 'Conversion Url', 'trim|required');
            $this->CI->form_validation->set_rules('conversion_type', 'Conversion Type', 'trim|required');
            
            // Get data
            $name = $this->CI->input->post('name');
            $conversion_url = $this->CI->input->post('conversion_url');
            $conversion_type = $this->CI->input->post('conversion_type');
            
            $data = array(
                'success' => TRUE,
                'response' => array($name, $conversion_url, $conversion_type)
            );
            
            // Get selected account
            $get_account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');

            if ( $get_account ) {
                
                // Get account pixel
                $pixel = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $get_account[0]->net_id . '/adspixels?access_token=' . $get_account[0]->token), true);
                
                // Verify if account has pixel
                if ( !isset($pixel['data'][0]['id']) ) {
                    
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('please_create_ads_pixels')
                    );

                    echo json_encode($data);
                    exit();
                    
                }
                
                $args = array(
                    'name' => $name,
                    'pixel_id' => $pixel['data'][0]['id'],
                    'rule' => array(
                        'url' => array(
                            'i_contains' => $conversion_url
                        )
                    ),
                    'event_source_id' => $pixel['data'][0]['id'],
                    'custom_event_type' => $conversion_type,
                );
                
                $create_conversion = json_decode(post(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $get_account[0]->net_id . '/customconversions?access_token=' . $get_account[0]->token, $args), true);

                if ( isset($create_conversion['id']) ) {
                    
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('conversion_tracking_was_created')
                    );

                    echo json_encode($data);  
                    
                } else if ( isset($create_conversion['error']['error_user_title']) ) {
                    
                    $data = array(
                        'success' => FALSE,
                        'message' => $create_conversion['error']['error_user_title']
                    );

                    echo json_encode($data);                    
                    
                } else {
                    
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('conversion_tracking_was_not_created')
                    );

                    echo json_encode($data);
                    
                }
                
                exit();
                
            }
            
        }
        
        $data = array(
            'success' => TRUE,
            'response' => $response
        );

        echo json_encode($data); 
        
    }
    
    /**
     * The public method load_all_pixel_coversions loads all Pixel's conversions
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function load_all_pixel_coversions() {
        
        // Get selected account
        $get_account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');

        // Verify if user has selected an Ad Account
        if ( $get_account ) {

            $response = $this->fb->get(
                '/' . $get_account[0]->net_id . '/customconversions?fields=id,name,data_sources,aggregation_rule,custom_event_type,rule,pixel{name}&limit=10',
                $get_account[0]->token
            );

            $conversions = $response->getDecodedBody();

            if ( isset($conversions['data'][0]) ) {

                $data = array(
                    'success' => TRUE,
                    'conversions' => $conversions['data']
                );

                echo json_encode($data);
                exit();

            }

        }
        
        $data = array(
            'success' => FALSE,
            'response' => $this->CI->lang->line('no_pixel_conversions')
        );

        echo json_encode($data); 
        
    }
    
    /**
     * The public method filter_pixel_coversions search for Pixel's conversions
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function filter_pixel_coversions() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim|required');
            
            // Get data
            $key = $this->CI->input->post('key');
            
            // Get selected account
            $get_account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');

            if ( $get_account ) {
                
                $response = $this->fb->get(
                    '/' . $get_account[0]->net_id . '/customconversions?fields=id,name,data_sources,aggregation_rule,custom_event_type,rule,pixel{name}&limit=1000',
                    $get_account[0]->token
                );

                $conversions = $response->getDecodedBody();

                if ( $conversions['data'] ) {
                    
                    $all_conversions = array();
                    
                    $i = 0;
                    
                    foreach ( $conversions['data'] as $conversion ) {
                        
                        if ( preg_match("/{$key}/i", $conversion['name'] ) ) {
                            $all_conversions[] = $conversion;
                            $i++;
                        }
                        
                        if ( $i > 9 ) {
                            break;
                        }
                        
                    }

                    if ( $all_conversions ) {

                        $data = array(
                            'success' => TRUE,
                            'conversions' => $all_conversions
                        );

                        echo json_encode($data);
                        exit();

                    }
                    
                }
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('no_pixel_conversions')
        );

        echo json_encode($data); 
        
    }
    
}

/* End of file pixel.php */
