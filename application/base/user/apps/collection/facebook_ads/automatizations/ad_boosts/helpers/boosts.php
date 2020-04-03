<?php
/**
 * Boosts Helper
 *
 * This file contains the class Boosts
 * with methods to process the Ad Boosts
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Facebook_ads\Automatizations\Ad_boosts\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Boosts class provides the methods to process the Ad Boosts
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
*/
class Boosts {
    
    /**
     * Class variables
     *
     * @since 0.0.7.7
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.7
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Load models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'models/', 'Ads_account_model', 'ads_account_model' );
        
    }

    /**
     * The public method create_new_boost creates a new Ad Boost
     *
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function create_new_boost() {
        
        $response = array();
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('campaign_id', 'Campaign ID', 'trim');
            $this->CI->form_validation->set_rules('ad_set_id', 'Ad Set ID', 'trim');
            $this->CI->form_validation->set_rules('boost_name', 'boost Name', 'trim');
            $this->CI->form_validation->set_rules('spending_limit', 'Spending Limit', 'trim');
            $this->CI->form_validation->set_rules('fb_page_id', 'Facebook Page ID', 'trim');
            $this->CI->form_validation->set_rules('instagram_id', 'Instagram ID', 'trim');
            
            // Get data
            $campaign_id = $this->CI->input->post('campaign_id'); 
            $ad_set_id = $this->CI->input->post('ad_set_id');
            $boost_name = $this->CI->input->post('boost_name');
            $spending_limit = $this->CI->input->post('spending_limit');
            $fb_page_id = $this->CI->input->post('fb_page_id');
            $instagram_id = $this->CI->input->post('instagram_id');
            
            // Get the user selected account
            $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');
            
            if ( !$account ) {
                
                $response[] = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('fb_boosts_ad_account_not_valid'),
                    'description' => $this->CI->lang->line('fb_boosts_ad_account_is_requied')
                );                
                
            }
        
            if ( !is_numeric($campaign_id) && $campaign_id < 1 ) {
                
                $response[] = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('fb_boosts_campaign_invalid'),
                    'description' => $this->CI->lang->line('fb_boosts_please_select_ad_campaign')
                );
                
            }
            
            if ( !is_numeric($ad_set_id) && $ad_set_id < 1 ) {
                
                $response[] = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('fb_boosts_ad_set_invalid'),
                    'description' => $this->CI->lang->line('fb_boosts_please_select_ad_set')
                );
                
            }
            
            if ( !$boost_name && strlen($boost_name) < 6 ) {
                
                $response[] = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('fb_boosts_boost_name_too_short'),
                    'description' => $this->CI->lang->line('fb_boosts_please_enter_at_least_6_characters')
                );
                
            }
            
            if ( !is_numeric($fb_page_id) ) {
                
                $response[] = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('fb_boosts_page_required'),
                    'description' => $this->CI->lang->line('fb_boosts_please_select_facebook_page')
                );
                
            }            
            
            if ( !$response ) {
                
                // Create new Ad's boost
                $boost = $this->CI->ads_boosts_model->save_boost($this->CI->user_id, $boost_name);
                
                if ( $boost ) {
                    
                    $c = 0;
                    
                    // Save Ad's boost meta
                    if ( $this->CI->ads_boosts_model->save_boost_meta($boost, 'ad_campaign_id', $campaign_id) ) {
                        $c++;
                    }
                    
                    // Save Ad's boost meta
                    if ( $this->CI->ads_boosts_model->save_boost_meta($boost, 'ad_set_id', $ad_set_id) ) {
                        $c++;
                    } 
                    
                    // Save Ad's boost meta
                    if ( $this->CI->ads_boosts_model->save_boost_meta($boost, 'ad_account', $account[0]->network_id) ) {
                        $c++;
                    } 
                    
                    // Save Ad's boost meta
                    if ( $this->CI->ads_boosts_model->save_boost_meta($boost, 'facebook_page_id', $fb_page_id) ) {
                        $c++;
                    }

                    // Verify if spending limit is numeric
                    if ( is_numeric($spending_limit) ) {

                        // Verify if spending limit is greater than 0
                        if ( $spending_limit > 0 ) {
        
                            if ( $this->CI->ads_boosts_model->save_boost_meta($boost, 'spending_limit', $spending_limit) ) {
                                $c++;
                            }                            
        
                        }
                        
                    }
                    
                    // Verify if user has added Instagram Account
                    if ( $instagram_id ) {
                        
                        // Save Ad's boost meta
                        if ( $this->CI->ads_boosts_model->save_boost_meta($boost, 'instagram_id', $instagram_id) ) {
                            $c++;
                        }                        
                        
                    }
                    
                    if ( $c ) {
                        
                        $response[] = array(
                            'success' => TRUE,
                            'message' => $this->CI->lang->line('fb_boosts_boost_was_saved'),
                            'description' => $this->CI->lang->line('fb_boosts_boost_was_saved_successfully')
                        );                         
                        
                    } else {
                        
                        $response[] = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('fb_boosts_boost_was_not_saved'),
                            'description' => $this->CI->lang->line('fb_boosts_an_error_occurred')
                        );                        
                        
                    }
                    
                } else {
                
                    $response[] = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('fb_boosts_boost_was_not_saved'),
                        'description' => $this->CI->lang->line('fb_boosts_an_error_occurred')
                    );
                    
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
     * The public method fb_boosts_load_all loads all Ad's boosts
     *
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function fb_boosts_load_all() {
        
        // Get the user selected account
        $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');
        
        $account_id = 0;
        
        if ( $account ) {
            
            $account_id = $account[0]->network_id;
            
        }
        
        // Get the page's input
        $page = $this->CI->input->get('page');
        
        $page--;
        
        $start = ($page * 10);
        
        // Get boosts
        $boosts = $this->CI->ads_boosts_model->get_boosts($this->CI->user_id, $account_id, $start, 10);
        
        // Get Total Ad's boosts
        $total_boosts = $this->CI->ads_boosts_model->get_boosts($this->CI->user_id, $account_id);
        
        if ( $boosts ) {

            $data = array(
                'success' => TRUE,
                'boosts' => $boosts,
                'total_boosts' => $total_boosts,
                'page' => ($page + 1)
            );

            echo json_encode($data); 
            
        } else {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('fb_boosts_no_ad_boosts_found')
            );

            echo json_encode($data);             
            
        }
        
    }

    /**
     * The public method fb_boosts_load_single loads single Ad's boost
     *
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function fb_boosts_load_single() {
        
        // Get the user selected account
        $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');
        
        $account_id = 0;
        
        if ( $account ) {
            
            $account_id = $account[0]->network_id;
            
        }
        
        // Get the boost_id's input
        $boost_id = $this->CI->input->get('boost_id');
        
        // Get boost option by id
        $boost = $this->CI->ads_boosts_model->get_boost_single($this->CI->user_id, $boost_id);
        
        if ( $boost ) {

            $data = array(
                'success' => TRUE,
                'boost' => $boost
            );

            echo json_encode($data); 
            
        } else {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('fb_boosts_no_ad_boost_found')
            );

            echo json_encode($data);                
            
        }
        
    }    
    
    /**
     * The public method delete_ad_boosts deletes all Ad's boosts
     *
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function delete_ad_boosts() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('boosts', 'Boosts', 'trim');
            
            // Get data
            $boosts = $this->CI->input->post('boosts');

            if ( $this->CI->form_validation->run() !== false ) {
                
                if ( $boosts ) {

                    $count = 0;

                    foreach ( $boosts as $boost ) {

                        if ( is_numeric($boost[1]) ) {

                            if ( $this->CI->ads_boosts_model->delete_boost($boost[1], $this->CI->user_id) ) {

                                $count++;

                            }

                        }

                    }

                    if ( $count ) {

                        $data = array(
                            'success' => TRUE,
                            'message' => $this->CI->lang->line('fb_boosts_were_deleted')
                        );

                        echo json_encode($data); 
                        exit();

                    } else {

                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('fb_boosts_were_not_deleted')
                        );

                        echo json_encode($data); 
                        exit();

                    }
                
                }
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('fb_boosts_please_select_ad_boost')
        );

        echo json_encode($data); 
        
    }
    
    /**
     * The public method order_reports_by_time generates reports
     *
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function order_reports_by_time() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('order', 'Order', 'trim|numeric|required');
            
            // Get data
            $order = $this->CI->input->post('order');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                $time = strtotime('-30 days');
                
                switch ( $order ) {
                    
                    case '1':
                        
                        $time = strtotime('-1 days');
                        
                        break;
                    
                    case '2':
                        
                        $time = strtotime('-7 days');
                        
                        break;
                    
                    case '4':
                        
                        $time = strtotime('-90 days');
                        
                        break;
                    
                }
                
                // Gets reports by time
                $get_reports = $this->CI->ads_boosts_model->get_reports_by_time($this->CI->user_id, $time);    
                
                if ( $get_reports ) {
                    
                    $data = array(
                        'success' => TRUE,
                        'reports' => $get_reports
                    );

                    echo json_encode($data);
                    exit();
                    
                }
                
            }
            
        }
            
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('fb_boosts_no_reports_found')
        );

        echo json_encode($data);
        
    }
    
}

/* End of file boosts.php */
