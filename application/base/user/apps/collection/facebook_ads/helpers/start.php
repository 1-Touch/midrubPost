<?php
/**
 * Start Helper
 *
 * This file contains the class Start
 * with methods to process when the Facebook Ads's page loads
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
 * Start class provides the methods to process the methods when the Facebook Ads's page loads
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
*/
class Start {
    
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
        
    }

    /**
     * The public method load_ad_accounts load ad accounts by page
     *
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_ad_accounts() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim|required');
            
            // Get data
            $search = $this->CI->input->post('key'); 
            $page = $this->CI->input->post('page');      
        
            $limit = 10;

            $page--;

            // Get total ad accounts
            $total = $this->CI->ads_networks_model->get_networks($this->CI->user_id, 1, '', '', $search);

            // Get ad accounts by page
            $get_accounts = $this->CI->ads_networks_model->get_networks($this->CI->user_id, 1, ($page * $limit), $limit, $search);

            // Verify if accounts exists
            if ( $get_accounts ) {

                $data = array(
                    'success' => TRUE,
                    'accounts' => $get_accounts,
                    'page' => ($page + 1),
                    'total' => $total,
                    'delete' => $this->CI->lang->line('delete')
                );            

                echo json_encode($data);
                exit();

            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('no_accounts_found')
        );

        echo json_encode($data);   
        
    }
    
    /**
     * The public method quick_ad_accounts loads the last 20 ad accounts
     *
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function quick_ad_accounts() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            
            // Get data
            $search = $this->CI->input->post('key');  

            // Get ad accounts by page
            $get_accounts = $this->CI->ads_networks_model->get_networks($this->CI->user_id, 1, 0, 20, $search);

            // Verify if accounts exists
            if ( $get_accounts ) {

                $data = array(
                    'success' => TRUE,
                    'accounts' => $get_accounts
                );            

                echo json_encode($data);
                exit();

            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('no_accounts_found'),
            'select_ad_account' => '<i class="icon-plus"></i> ' . $this->CI->lang->line('select_ad_account')
        );

        echo json_encode($data);  
        
    }
    
    /**
     * The public method load_ad_account_overview loads Ad Account's overview
     *
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_ad_account_overview() {
        
        // Get selected account
        $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');
        
        // Verify if user has selected an Ad Account
        if ( $account ) {
        
            // Get account's insights
            $account_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/insights?fields=cpc,ctr,cpp,cpm,impressions,clicks,reach,spend,social_spend,account_currency,frequency&time_range[since]=' . date('Y-m-d', strtotime('-1 day')) . '&time_range[until]=' . date('Y-m-d', strtotime('now')) . '&access_token=' . $account[0]->token), true);

            // Verify if insights exists
            if ( isset($account_insights['data'][0]) ) {
                
                // Verify if cache exists
                if ( file_exists(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-overview.json') ) {
                    unlink(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-overview.json');
                }
                
                // Update the cache
                $this->create_cache(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-overview.json', json_encode($account_insights, JSON_PRETTY_PRINT));

                $data = array(
                    'success' => TRUE,
                    'account_insights' => $account_insights['data'][0],
                    'words' => array(
                        'today' => $this->CI->lang->line('today'),
                        'week' => $this->CI->lang->line('week'),
                        'month' => $this->CI->lang->line('month'),
                        'new_ad' => $this->CI->lang->line('new_ad'),
                        'total_spent' => $this->CI->lang->line('total_spent'),
                        'social_spent' => $this->CI->lang->line('social_spent'),
                        'impressions' => $this->CI->lang->line('impressions'),
                        'clicks' => $this->CI->lang->line('clicks'),
                        'reach' => $this->CI->lang->line('reach'),
                        'frequency' => $this->CI->lang->line('frequency'),
                        'cpm' => $this->CI->lang->line('cpm'),
                        'cpp' => $this->CI->lang->line('cpp'),
                        'ctr' => $this->CI->lang->line('ctr')
                    )
                );

                echo json_encode($data);
                exit();

            } else {
                
                // Get user Currency
                $get_user_currency = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '?fields=currency,min_daily_budget&access_token=' . $account[0]->token), true);
                
                $data = array(
                    'success' => TRUE,
                    'account_insights' => array(
                        'ctr' => '0',
                        'cpp' => '0',
                        'cpm' => '0',
                        'impressions' => '0',
                        'clicks' => '0',
                        'reach' => '0',
                        'spend' => '0',
                        'social_spend' => '0',
                        'account_currency' => @$get_user_currency['currency'],
                        'frequency' => '0'
                    ),
                    'words' => array(
                        'today' => $this->CI->lang->line('today'),
                        'week' => $this->CI->lang->line('week'),
                        'month' => $this->CI->lang->line('month'),
                        'new_ad' => $this->CI->lang->line('new_ad'),
                        'total_spent' => $this->CI->lang->line('total_spent'),
                        'social_spent' => $this->CI->lang->line('social_spent'),
                        'impressions' => $this->CI->lang->line('impressions'),
                        'clicks' => $this->CI->lang->line('clicks'),
                        'reach' => $this->CI->lang->line('reach'),
                        'frequency' => $this->CI->lang->line('frequency'),
                        'cpm' => $this->CI->lang->line('cpm'),
                        'cpp' => $this->CI->lang->line('cpp'),
                        'ctr' => $this->CI->lang->line('ctr')
                    )
                );

                echo json_encode($data);
                exit();
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'no_account_selected' => $this->CI->lang->line('no_account_selected'),
            'please_select_ad_account' => $this->CI->lang->line('please_select_ad_account')
        );

        echo json_encode($data);  
        
    }
    
    /**
     * The public method load_ad_account_campaigns loads Ad Account's campaigns
     *
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_ad_account_campaigns() {
        
        // Get selected account
        $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');
        
        if ( $account ) {
            
            // Get account's campaigns
            $account_campaigns = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/campaigns?fields=name,status,insights,objective&limit=10&access_token=' . $account[0]->token), true);

            if ( isset($account_campaigns['data'][0]) ) {
                
                if ( file_exists(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-campaigns.json') ) {
                    unlink(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-campaigns.json');
                }
                
                $this->create_cache(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-campaigns.json', json_encode($account_campaigns, JSON_PRETTY_PRINT));
                
                $data = array(
                    'success' => TRUE,
                    'campaigns' => $account_campaigns['data'],
                    'previous' => '',
                    'next' => '',
                    'words' => array(
                        'new_campaign' => $this->CI->lang->line('new_campaign'),
                        'delete' => $this->CI->lang->line('delete'),
                        'reports' => $this->CI->lang->line('reports'),
                        'name' => $this->CI->lang->line('name'),
                        'status' => $this->CI->lang->line('status'),
                        'remaining_budget' => $this->CI->lang->line('remaining_budget'),
                        'start' => $this->CI->lang->line('start'),
                        'end' => $this->CI->lang->line('end'),
                        'previous' => $this->CI->lang->line('previous'),
                        'next' => $this->CI->lang->line('next'),
                    )
                );
                
                if ( isset($account_campaigns['paging']['previous']) ) {
                    $data['previous'] = $account_campaigns['paging']['previous'];
                }
                
                if ( isset($account_campaigns['paging']['next']) ) {
                    $data['next'] = $account_campaigns['paging']['next'];
                }

                echo json_encode($data);
                exit();
                
            }
            
        }
 
        $data = array(
            'success' => FALSE,
            'words' => array(
                'new_campaign' => $this->CI->lang->line('new_campaign'),
                'delete' => $this->CI->lang->line('delete'),
                'reports' => $this->CI->lang->line('reports'),
                'name' => $this->CI->lang->line('name'),
                'status' => $this->CI->lang->line('status'),
                'previous' => $this->CI->lang->line('previous'),
                'next' => $this->CI->lang->line('next'),
            )
        );

        echo json_encode($data);
        
    }
    
    /**
     * The public method load_ad_account_adsets loads Ad Account's adsets
     *
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_ad_account_adsets() {
        
        // Get selected account
        $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');
        
        if ( $account ) {
            
            // Get account's adsets
            $account_adsets = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/adsets?fields=status,insights,name,campaign{name}&limit=10&access_token=' . $account[0]->token), true);

            if ( isset($account_adsets['data'][0]) ) {
                
                if ( file_exists(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-adsets.json') ) {
                    unlink(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-adsets.json');
                }
                
                $this->create_cache(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-adsets.json', json_encode($account_adsets, JSON_PRETTY_PRINT));
                
                $data = array(
                    'success' => TRUE,
                    'adsets' => $account_adsets['data'],
                    'previous' => '',
                    'next' => '',
                    'words' => array(
                        'new_ad_set' => $this->CI->lang->line('new_ad_set'),
                        'delete' => $this->CI->lang->line('delete'),
                        'reports' => $this->CI->lang->line('reports'),
                        'name' => $this->CI->lang->line('name'),
                        'status' => $this->CI->lang->line('status'),
                        'previous' => $this->CI->lang->line('previous'),
                        'next' => $this->CI->lang->line('next'),
                    )
                );
                
                if ( isset($account_adsets['paging']['previous']) ) {
                    $data['previous'] = $account_adsets['paging']['previous'];
                }
                
                if ( isset($account_adsets['paging']['next']) ) {
                    $data['next'] = $account_adsets['paging']['next'];
                }

                echo json_encode($data);
                exit();
                
            }
            
        }
 
        $data = array(
            'success' => FALSE,
            'words' => array(
                'new_ad_set' => $this->CI->lang->line('new_ad_set'),
                'delete' => $this->CI->lang->line('delete'),
                'reports' => $this->CI->lang->line('reports'),
                'name' => $this->CI->lang->line('name'),
                'status' => $this->CI->lang->line('status'),
                'remaining_budget' => $this->CI->lang->line('remaining_budget'),
                'start' => $this->CI->lang->line('start'),
                'end' => $this->CI->lang->line('end'),
                'previous' => $this->CI->lang->line('previous'),
                'next' => $this->CI->lang->line('next'),
            )
        );

        echo json_encode($data);
        
    }
    
    /**
     * The public method load_ad_account_ads loads Ad Account's ads
     *
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_ad_account_ads() {
        
        // Get selected account
        $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');

        // Get status from the get parameter
        $get_parameter = $this->CI->input->get('status');

        // Default status
        $status = '';        
        
        // Verify if account exists
        if ( $account ) {

            // Verify if status is numeric
            if ( is_numeric($get_parameter) ) {

                // Verify if status is valid
                if ( ($get_parameter > 0) && ($get_parameter < 5) ) {

                    switch ( $get_parameter ) {

                        case '1':

                            $status = '&filtering=[{"field":"effective_status","operator":"IN","value":["ACTIVE"]}]';

                            break;

                        case '2':

                            $status = '&filtering=[{"field":"effective_status","operator":"IN","value":["PAUSED"]}]';

                            break;     
                            
                        case '3':

                            $status = '&filtering=[{"field":"effective_status","operator":"IN","value":["DELETED"]}]';

                            break;
                            
                        case '4':

                            $status = '&filtering=[{"field":"effective_status","operator":"IN","value":["ARCHIVED"]}]';

                            break;                            

                    }

                }

            }

            // Get account's ads
            $account_ads = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/ads?fields=insights,status,name,adset{name}' . $status . '&limit=10&access_token=' . $account[0]->token), true);

            // Verify if ads exists
            if ( isset($account_ads['data'][0]) ) {
                
                if ( file_exists(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-ads.json') ) {
                    unlink(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-ads.json');
                }
                
                $this->create_cache(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-ads.json', json_encode($account_ads, JSON_PRETTY_PRINT));
                
                $data = array(
                    'success' => TRUE,
                    'ads' => $account_ads['data'],
                    'previous' => '',
                    'next' => '',
                    'words' => array(
                        'new_ad' => $this->CI->lang->line('new_ad'),
                        'delete' => $this->CI->lang->line('delete'),
                        'reports' => $this->CI->lang->line('reports'),
                        'name' => $this->CI->lang->line('name'),
                        'status' => $this->CI->lang->line('status'),
                        'previous' => $this->CI->lang->line('previous'),
                        'next' => $this->CI->lang->line('next'),
                    )
                );
                
                if ( isset($account_ads['paging']['previous']) ) {
                    $data['previous'] = $account_ads['paging']['previous'];
                }
                
                if ( isset($account_ads['paging']['next']) ) {
                    $data['next'] = $account_ads['paging']['next'];
                }

                if ( $status ) {
                    $data['status'] = $get_parameter;
                }

                echo json_encode($data);
                exit();
                
            }
            
        }

        $data = array(
            'success' => FALSE,
            'words' => array(
                'new_ad' => $this->CI->lang->line('new_ad'),
                'delete' => $this->CI->lang->line('delete'),
                'reports' => $this->CI->lang->line('reports'),
                'name' => $this->CI->lang->line('name'),
                'status' => $this->CI->lang->line('status'),
                'created' => $this->CI->lang->line('created'),
                'previous' => $this->CI->lang->line('previous'),
                'next' => $this->CI->lang->line('next'),
            )
        );

        if ( $status ) {
            $data['status'] = $get_parameter;
        }        

        echo json_encode($data);
        
    }
    
    /**
     * The public method load_ad_account_pixel_conversions loads Pixel's conversions
     *
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_ad_account_pixel_conversions() {
        
        // Get selected account
        $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');
        
        if ( $account ) {
            
            // Get account's conversions
            $account_conversions = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/customconversions?fields=id,name,data_sources,aggregation_rule,custom_event_type,rule,pixel{name}&limit=10&access_token=' . $account[0]->token), true);

            if ( isset($account_conversions['data'][0]) ) {
                
                if ( file_exists(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-conversions.json') ) {
                    unlink(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-conversions.json');
                }
                
                $this->create_cache(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'cache/' . $account[0]->net_id . '-conversions.json', json_encode($account_conversions, JSON_PRETTY_PRINT));
                
                $data = array(
                    'success' => TRUE,
                    'conversions' => $account_conversions['data'],
                    'previous' => '',
                    'next' => '',
                    'words' => array(
                        'new_conversion' => $this->CI->lang->line('new_conversion'),
                        'name' => $this->CI->lang->line('name'),
                        'type' => $this->CI->lang->line('type'),
                        'url' => $this->CI->lang->line('url'),
                        'previous' => $this->CI->lang->line('previous'),
                        'next' => $this->CI->lang->line('next'),
                    )
                );
                
                if ( isset($account_conversions['paging']['previous']) ) {
                    $data['previous'] = $account_conversions['paging']['previous'];
                }
                
                if ( isset($account_conversions['paging']['next']) ) {
                    $data['next'] = $account_conversions['paging']['next'];
                }

                echo json_encode($data);
                exit();
                
            }
            
        }
 
        $data = array(
            'success' => FALSE,
            'words' => array(
                'new_conversion' => $this->CI->lang->line('new_conversion'),
                'name' => $this->CI->lang->line('name'),
                'type' => $this->CI->lang->line('type'),
                'url' => $this->CI->lang->line('url'),
                'previous' => $this->CI->lang->line('previous'),
                'next' => $this->CI->lang->line('next'),
            )
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

/* End of file start.php */