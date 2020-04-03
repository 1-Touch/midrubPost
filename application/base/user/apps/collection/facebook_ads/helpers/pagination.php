<?php
/**
 * Pagination Helper
 *
 * This file contains the class Pagination
 * with methods to load contents by pagination
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
 * Pagination class provides the methods to load contents by pagination
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
*/
class Pagination {
    
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
     * The public method load_campaigns_by_pagination loads campaigns by pagination
     *
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_campaigns_by_pagination() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('url', 'Url', 'trim');
            
            // Get data
            $url = $this->CI->input->post('url');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                if ( !$url ) {
                    
                    // Get selected account
                    $get_account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');
                    
                    if ( $get_account ) {
                        
                        $response = $this->fb->get(
                            '/' . $get_account[0]->net_id . '/campaigns?fields=name,status,insights,objective&limit=10',
                            $get_account[0]->token
                        );
                        
                        $data = $response->getDecodedBody();
                        
                        if ( $data['data'] ) {
                            
                            $this->create_cache(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $get_account[0]->net_id . '-campaigns.json', json_encode($data, JSON_PRETTY_PRINT));
                            
                            $previous = '';

                            if ( isset($data['paging']['previous']) ) {
                                $previous = $data['paging']['previous'];
                            }                    

                            $next = '';

                            if ( isset($data['paging']['next']) ) {
                                $next = $data['paging']['next'];
                            }

                            $array = array(
                                'success' => TRUE,
                                'campaigns' => $data['data'],
                                'previous' => $previous,
                                'next' => $next
                            );

                            echo json_encode($array);
                            exit();
                            
                        }
                        
                    }
                    
                } else {

                    $campaigns = json_decode(get($url), true);

                    if ( $campaigns['data'] ) {

                        $previous = '';

                        if ( isset($campaigns['paging']['previous']) ) {
                            $previous = $campaigns['paging']['previous'];
                        }                    

                        $next = '';

                        if ( isset($campaigns['paging']['next']) ) {
                            $next = $campaigns['paging']['next'];
                        }

                        $data = array(
                            'success' => TRUE,
                            'campaigns' => $campaigns['data'],
                            'previous' => $previous,
                            'next' => $next
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
     * The public method load_ad_sets_by_pagination loads ad sets by pagination
     *
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_ad_sets_by_pagination() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('url', 'Url', 'trim');
            
            // Get data
            $url = $this->CI->input->post('url');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                if ( !$url ) {
                    
                    // Get selected account
                    $get_account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');
                    
                    if ( $get_account ) {
                        
                        $response = $this->fb->get(
                            '/' . $get_account[0]->net_id . '/adsets?fields=status,insights,name,campaign{name}&limit=10',
                            $get_account[0]->token
                        );
                        
                        $data = $response->getDecodedBody();
                        
                        if ( $data ) {
                            
                            $this->create_cache(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $get_account[0]->net_id . '-adsets.json', json_encode($data, JSON_PRETTY_PRINT));
                            
                            $previous = '';

                            if ( isset($data['paging']['previous']) ) {
                                $previous = $data['paging']['previous'];
                            }                    

                            $next = '';

                            if ( isset($data['paging']['next']) ) {
                                $next = $data['paging']['next'];
                            }

                            $array = array(
                                'success' => TRUE,
                                'adsets' => $data['data'],
                                'previous' => $previous,
                                'next' => $next
                            );

                            echo json_encode($array);
                            exit();
                            
                        }
                        
                    }
                    
                } else {

                    $adsets = json_decode(get($url), true);

                    if ( $adsets ) {

                        $previous = '';

                        if ( isset($adsets['paging']['previous']) ) {
                            $previous = $adsets['paging']['previous'];
                        }                    

                        $next = '';

                        if ( isset($adsets['paging']['next']) ) {
                            $next = $adsets['paging']['next'];
                        }

                        $data = array(
                            'success' => TRUE,
                            'adsets' => $adsets['data'],
                            'previous' => $previous,
                            'next' => $next
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
     * The public method load_ads_by_pagination loads ads by pagination
     *
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_ads_by_pagination() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('url', 'Url', 'trim');
            
            // Get data
            $url = $this->CI->input->post('url');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                if ( !$url ) {
                    
                    // Get selected account
                    $get_account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');
                    
                    if ( $get_account ) {
                        
                        $response = $this->fb->get(
                            '/' . $get_account[0]->net_id . '/ads?fields=insights,status,name,adset{name}&limit=10',
                            $get_account[0]->token
                        );
                        
                        $data = $response->getDecodedBody();
                        
                        if ( $data ) {
                            
                            $this->create_cache(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $get_account[0]->net_id . '-ads.json', json_encode($data, JSON_PRETTY_PRINT));
                            
                            $previous = '';

                            if ( isset($data['paging']['previous']) ) {
                                $previous = $data['paging']['previous'];
                            }                    

                            $next = '';

                            if ( isset($data['paging']['next']) ) {
                                $next = $data['paging']['next'];
                            }

                            $array = array(
                                'success' => TRUE,
                                'ads' => $data['data'],
                                'previous' => $previous,
                                'next' => $next
                            );

                            echo json_encode($array);
                            exit();
                            
                        }
                        
                    }
                    
                } else {

                    $ads = json_decode(get($url), true);

                    if ( $ads ) {

                        $previous = '';

                        if ( isset($ads['paging']['previous']) ) {
                            $previous = $ads['paging']['previous'];
                        }                    

                        $next = '';

                        if ( isset($ads['paging']['next']) ) {
                            $next = $ads['paging']['next'];
                        }

                        $data = array(
                            'success' => TRUE,
                            'ads' => $ads['data'],
                            'previous' => $previous,
                            'next' => $next
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
     * The public method load_pixel_conversions_by_pagination loads pixel's conversions by pagination
     *
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_pixel_conversions_by_pagination() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('url', 'Url', 'trim');
            
            // Get data
            $url = $this->CI->input->post('url');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                if ( !$url ) {
                    
                    // Get selected account
                    $get_account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');
                    
                    if ( $get_account ) {
                    
                        $response = $this->fb->get(
                            '/' . $get_account[0]->net_id . '/customconversions?fields=id,name,data_sources,aggregation_rule,custom_event_type,rule,pixel{name}&limit=10',
                            $get_account[0]->token
                        );

                        $conversions = $response->getDecodedBody();

                        if ( $conversions ) {

                            $previous = '';

                            if ( isset($conversions['paging']['previous']) ) {
                                $previous = $conversions['paging']['previous'];
                            }                    

                            $next = '';

                            if ( isset($conversions['paging']['next']) ) {
                                $next = $conversions['paging']['next'];
                            }

                            $data = array(
                                'success' => TRUE,
                                'conversions' => $conversions['data'],
                                'previous' => $previous,
                                'next' => $next
                            );

                            echo json_encode($data);
                            exit();

                        }
                        
                    }
                    
                } else {

                    $conversions = json_decode(get($url), true);

                    if ( $conversions ) {

                        $previous = '';

                        if ( isset($conversions['paging']['previous']) ) {
                            $previous = $conversions['paging']['previous'];
                        }                    

                        $next = '';

                        if ( isset($conversions['paging']['next']) ) {
                            $next = $conversions['paging']['next'];
                        }

                        $data = array(
                            'success' => TRUE,
                            'conversions' => $conversions['data'],
                            'previous' => $previous,
                            'next' => $next
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
     * The public method create_cache creates cache
     * 
     * @param string $file_name contains the file's name
     * @param string $content contains the file's content
     *
     * @since 0.0.7.6
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

/* End of file pagination.php */
