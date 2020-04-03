<?php
/**
 * Targeting Helper
 *
 * This file contains the class Targeting Helper
 * with methods to process the Targeting Specs
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Facebook_ads\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Namespaces to use
use FacebookAds\Api;
use FacebookAds\Object\TargetingSearch;
use FacebookAds\Object\Search\TargetingSearchTypes;

/*
 * Targeting class provides the methods to process the Targeting Specs
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
*/
class Targeting {
    
    /**
     * Class variables
     *
     * @since 0.0.7.8
     */
    protected $CI, $fb, $app_id, $app_secret;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.8
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
            
        // Get the Facebook App ID
        $this->app_id = get_option('facebook_pages_app_id');

        // Get the Facebook App Secret
        $this->app_secret = get_option('facebook_pages_app_secret');
        
        // Load the Vendor dependencies
        require_once FCPATH . 'vendor/autoload.php';
        
        // Verify if user has configured Facebook Pages
        if (($this->app_id != '') AND ( $this->app_secret != '')) {
            
            $this->fb = new \Facebook\Facebook([
                'app_id' => $this->app_id,
                'app_secret' => $this->app_secret,
                'default_graph_version' => MIDRUB_ADS_FACEBOOK_GRAPH_VERSION,
                'default_access_token' => '{access-token}',
            ]);
            
        }
        
    }

    /**
     * The public method load_regions loads regions based on country code
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */ 
    public function load_regions() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('code', 'Code', 'trim|required');
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            
            // Get data
            $code = $this->CI->input->post('code');
            $key = $this->CI->input->post('key'); 

            if ( $this->CI->form_validation->run() !== false ) {

                // Get selected account
                $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');

                // Get regions
                $all_regions = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . "search/?type=adgeolocation&location_types=['region']&limit=1000&country_code=" . $code . "&access_token=" . $account[0]->token), true);

                if ( $all_regions['data'] ) {

                    $regions = array();

                    foreach ( $all_regions['data'] as $region ) {

                        if ( $key ) {

                            if ( !preg_match("/{$key}/i", $region['name'] ) ) {
                                continue;
                            }

                        }

                        if ( $region['type'] === 'region' ) {

                            if ( $region['supports_region'] ) {

                                $regions[] = array(
                                    'key' => $region['key'],
                                    'name' => $region['name']
                                );

                            }

                        }

                        if ( count($regions) > 9 ) {
                            break;
                        }

                    }

                    if ( $regions ) {

                        $data = array(
                            'success' => TRUE,
                            'regions' => $regions,
                            'words' => array(
                                'select_region' => $this->CI->lang->line('select_region'),
                                'search_for_regions' => $this->CI->lang->line('search_for_regions'),
                            )
                        );

                        echo json_encode($data);
                        exit();                  

                    }

                }

            }


        }

        $data = array(
            'success' => FALSE,
            'words' => array(
                'no_regions_found' => $this->CI->lang->line('no_regions_found'),
                'select_region' => $this->CI->lang->line('select_region'),
                'search_for_regions' => $this->CI->lang->line('search_for_regions')
            )
        );

        echo json_encode($data);
        
    }

    /**
     * The public method load_cities loads cities based on region's name
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */ 
    public function load_cities() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            $this->CI->form_validation->set_rules('region', 'region', 'trim|required');
            
            // Get data
            $key = $this->CI->input->post('key');
            $region = $this->CI->input->post('region');

            if ( $this->CI->form_validation->run() !== false ) {

                // Get selected account
                $get_account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');

                // Verify if account exists
                if ( $get_account ) {

                    // Init Api
                    Api::init($this->app_id, $this->app_secret, $get_account[0]->token);

                    // Search for cities
                    $response = TargetingSearch::search(
                        TargetingSearchTypes::GEOLOCATION,
                        null,
                        $region,
                        array(
                            'location_types' => array('city'),
                            'limit' => 1000
                        ));


                    // Verify if city exists
                    if ( $response ) {

                        $cities = array();

                        // List all locations
                        foreach ( $response as $obj ) {

                            $res = $obj->getData();

                            if ( $key ) {

                                if ( !preg_match("/{$key}/i", $res['name'] ) ) {
                                    continue;
                                }
    
                            }
    
                            if ( $res['type'] === 'city' ) {
    
                                if ( $res['supports_city'] ) {
    
                                    $cities[] = array(
                                        'key' => $res['key'],
                                        'name' => $res['name']
                                    );
    
                                }
    
                            }
    
                            if ( count($cities) > 9 ) {
                                break;
                            }

                        }

                        if ( $cities ) {

                            $data = array(
                                'success' => TRUE,
                                'cities' => $cities,
                                'words' => array(
                                    'select_city' => $this->CI->lang->line('select_city'),
                                    'search_for_cities' => $this->CI->lang->line('search_for_cities'),
                                )
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
            'words' => array(
                'no_cities_found' => $this->CI->lang->line('no_cities_found'),
                'select_city' => $this->CI->lang->line('select_city'),
                'search_for_cities' => $this->CI->lang->line('search_for_cities')
            )
        );

        echo json_encode($data);
        
    }
    
}

/* End of file targeting.php */
