<?php
/**
 * Facebook Ad Connector Helpers
 *
 * This file contains the class Ad_connector
 * with methods to connect and manage Ad Accounts
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
 * Ad_connector class provides the methods to manage Ad Accounts
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
*/
class Ad_connector {
    
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
        
        // Verify if Facebook Pages is enabled
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
     * The public method connect connects Facebook Ad Accounts
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function connect() {
        
        // Redirect use to the login page
        $helper = $this->fb->getRedirectLoginHelper();

        // Permissions to request
        $permissions = array(
            'ads_management',
            'manage_pages',
            'ads_read',
            'read_insights',
            'business_management'
        );

        // Get redirect url
        $loginUrl = $helper->getLoginUrl(site_url('user/app/facebook-ads?q=facebook-save-accounts'), $permissions);
        
        // Redirect
        header('Location:' . $loginUrl);
        
    }
    
    /**
     * The public method save saves the user ad accounts
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function save() {
        
        // Define the callback status
        $check = 0;

        // Obtain the user access token from redirect
        $helper = $this->fb->getRedirectLoginHelper();
        
        // Get the user access token
        $access_token = $helper->getAccessToken(site_url('user/app/facebook-ads?q=facebook-save-accounts'));
        
        // Convert it to array
        $access_token = (array) $access_token;
        
        // Get array value
        $access_token = array_values($access_token);
        
        if ( isset( $access_token[0] ) ) {
            
            try {
                
                $response = $this->fb->get(
                    '/me/adaccounts?fields=name,business',
                    $access_token[0]
                );
                
            } catch (Facebook\Exceptions\FacebookResponseException $e) {
                
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
                
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
                
            }

            $graphNode = $response->getGraphEdge();
            
            if ( $graphNode->asArray() ) {
                
                $accounts = $graphNode->asArray();
                
                foreach ( $accounts as $account ) {
                    
                    $account_id = $account['id'];
                    
                    $extra = '';
                    
                    if ( isset($account['business']) ) {
                        
                        $account_name = $account['business']['name'];
                        $extra = $account['business']['id'];
                        
                    } else {
                        
                        $account_name = $account['name'];
                        
                    }
                    
                    $this->CI->ads_networks_model->add_network('fb_ad_account', $account_id, 1, $this->CI->user_id, '', $access_token[0], '', $account_name, $extra);
                    
                }
                
                $check = 1;
                
            } else {
                
                $check = 2;
                
            }
            
        }
        
        if ( $check === 1 ) {
            
            // Display the success message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('all_ad_accounts_were_connected') . '</p>', true); 
            
        } else if ( $check === 2 ) {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('no_ad_accounts') . '</p>', false);             
            
        } else {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('error_occurred') . '</p>', false);             
            
        }
        
        exit();
        
    }
    
    /**
     * The public method select_ad_account selects ad accounts
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function select_ad_account() {
        
        // Get the account id
        $account_id = $this->CI->input->get('account_id');
        
        if ( $account_id ) {
            
            // Get account's data
            $get_account = $this->CI->ads_networks_model->get_account($account_id);
            
            // Verify if the user's is the owner of the account
            if ( @$get_account[0]->user_id === $this->CI->user_id ) {
                
                // Verify again if the ad account was selected
                $check = $this->CI->ads_account_model->save_account($this->CI->user_id, $account_id, 'facebook');
                
                if ( $check ) {
                
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('ads_account_was_connected'),
                        'account_id' => $account_id
                    );

                    echo json_encode($data);                

                } else {

                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('ads_account_was_not_connected')
                    );

                    echo json_encode($data);

                }
                
                exit();
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('error_occurred')
        );

        echo json_encode($data); 
        
    }
    
    /**
     * The public method unselect_ad_account unselects ad accounts
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function unselect_ad_account() {
        
        // Get the account id
        $account_id = $this->CI->input->get('account_id');
        
        if ( $account_id ) {
            
            // Get account's data
            $get_account = $this->CI->ads_networks_model->get_account($account_id);
            
            // Verify if the user's is the owner of the account
            if ( @$get_account[0]->user_id === $this->CI->user_id ) {
                
                // Verify again if the ad account was unselected
                $check = $this->CI->ads_account_model->delete_account($this->CI->user_id, $account_id);
                
                if ( $check ) {
                
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('ads_account_was_disconnected'),
                        'account_id' => $account_id,
                        'select_ad_account' => '<i class="icon-plus"></i> ' . $this->CI->lang->line('select_ad_account'),
                        'no_account_selected' => $this->CI->lang->line('no_account_selected'),
                        'please_select_ad_account' => $this->CI->lang->line('please_select_ad_account')
                    );

                    echo json_encode($data);                

                } else {

                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('ads_account_was_not_disconnected')
                    );

                    echo json_encode($data);

                }
                
                exit();
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('error_occurred')
        );

        echo json_encode($data); 
        
    }
    
    /**
     * The public method delete_ad_account deletes ad accounts
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function delete_ad_account() {
        
        // Get the account id
        $account_id = $this->CI->input->get('account_id');
        
        if ( $account_id ) {
            
            // Get account's data
            $get_account = $this->CI->ads_networks_model->get_account($account_id);

            // Verify if account exists
            if ($get_account) {

                // Verify if the user's is the owner of the account
                if ($get_account[0]->user_id === $this->CI->user_id) {

                    // Delete all ad account's records
                    run_hook(
                        'delete_ad_account',
                        array(
                            'account_id' => $account_id
                        )
                    );

                    // Verify again if the ad account was deleted
                    $account = $this->CI->ads_networks_model->get_account($account_id);

                    if (!$account) {

                        if (file_exists(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $get_account[0]->net_id . '-overview.json')) {
                            unlink(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $get_account[0]->net_id . '-overview.json');
                        }

                        if (file_exists(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $get_account[0]->net_id . '-campaigns.json')) {
                            unlink(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $get_account[0]->net_id . '-campaigns.json');
                        }

                        if (file_exists(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $get_account[0]->net_id . '-adsets.json')) {
                            unlink(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $get_account[0]->net_id . '-adsets.json');
                        }

                        if (file_exists(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $get_account[0]->net_id . '-ads.json')) {
                            unlink(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $get_account[0]->net_id . '-ads.json');
                        }

                        $data = array(
                            'success' => TRUE,
                            'message' => $this->CI->lang->line('ads_account_was_deleted'),
                            'account_id' => $account_id,
                            'select_ad_account' => '<i class="icon-plus"></i> ' . $this->CI->lang->line('select_ad_account'),
                            'no_account_selected' => $this->CI->lang->line('no_account_selected'),
                            'please_select_ad_account' => $this->CI->lang->line('please_select_ad_account')
                        );

                        echo json_encode($data);

                    } else {

                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('ads_account_was_not_deleted')
                        );

                        echo json_encode($data);
                    }

                    exit();

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
     * The public method display_connected_instagram_accounts displays the Instagram connected accounts
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function display_connected_instagram_accounts() {
        
        // Get the account's id
        $account_id = $this->CI->input->get('account_id');
        
        // Get the page's id
        $page_id = $this->CI->input->get('page_id');
        
        if ( is_numeric($account_id) && is_numeric($page_id) ) {
            
            // Get account's data
            $get_account = $this->CI->ads_networks_model->get_account($account_id);
            
            // Verify if the user's is the owner of the account
            if ( @$get_account[0]->user_id === $this->CI->user_id ) {
                
                $connected_accounts = array();
                
                // Get all pages
                $account_pages = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . 'me/accounts?fields=connected_instagram_account{ig_id,username},name,picture,access_token&limit=1000&access_token=' . $get_account[0]->token), true);

                if ( isset($account_pages['data'][0]) ) {
                    
                    foreach ( $account_pages['data'] as $page ) {

                        if ( $page['id'] === $page_id ) {

                            $connected_accounts = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . 'me/instagram_accounts?fields=id,username,profile_pic&access_token=' . $page['access_token']), true);
                            
                        }
                        
                    }

                }
                
                if ( $connected_accounts ) {
                
                    $data = array(
                        'success' => TRUE,
                        'accounts' => $connected_accounts
                    );

                    echo json_encode($data); 
                
                } else {
                    
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('no_accounts_found')
                    );

                    echo json_encode($data); 
                    
                }
                
                exit();
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('error_occurred')
        );

        echo json_encode($data); 
        
    }    
    
    /**
     * The public method delete_ad_campaigns deletes campaigns
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function delete_ad_campaigns() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('campaigns', 'Campaigns', 'trim');
            $this->CI->form_validation->set_rules('account', 'Account', 'trim|required');
            
            // Get data
            $campaigns = $this->CI->input->post('campaigns');
            $account = $this->CI->input->post('account');

            if ( $this->CI->form_validation->run() !== false ) {
                
                // Get selected account
                $get_account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');
                
                if ( $get_account ) {

                    $count = 0;

                    foreach ( $campaigns as $campaign ) {

                        if ( is_numeric($campaign[1]) ) {

                            $response = $this->fb->delete(
                                '/' . $campaign[1],
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
                            'message' => $this->CI->lang->line('the_selected_campaigns_were_deleted')
                        );

                        echo json_encode($data); 
                        exit();
                        
                    } else {
                        
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('the_selected_campaigns_were_not_deleted')
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
     * The public method delete_ad_sets deletes ad sets
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function delete_ad_sets() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('adsets', 'Adsets', 'trim');
            $this->CI->form_validation->set_rules('account', 'Account', 'trim|required');
            
            // Get data
            $adsets = $this->CI->input->post('adsets');
            $account = $this->CI->input->post('account');

            if ( $this->CI->form_validation->run() !== false ) {
                
                // Get selected account
                $get_account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');

                if ( $get_account ) {

                    $count = 0;

                    foreach ( $adsets as $adset ) {

                        if ( is_numeric($adset[1]) ) {

                            $response = $this->fb->delete(
                                '/' . $adset[1],
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
                            'message' => $this->CI->lang->line('the_selected_adsets_were_deleted')
                        );

                        echo json_encode($data); 
                        exit();
                        
                    } else {
                        
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('the_selected_adsets_were_not_deleted')
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

}

/* End of file ad_connector.php */