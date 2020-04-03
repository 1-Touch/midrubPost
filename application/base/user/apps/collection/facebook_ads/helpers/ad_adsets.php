<?php
/**
 * Facebook Ad Adsets Helper
 *
 * This file contains the class Ad_adsets
 * with methods to manage Ad's adsets
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
use FacebookAds\Object\AdAccountActivity;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdAccountFields;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Values\OptimizationGoals;
use FacebookAds\Object\Values\BillingEvents;
use FacebookAds\Object\Values\AdSetBillingEventValues;
use FacebookAds\Object\Values\AdSetOptimizationGoalValues;
use FacebookAds\Object\Fields\TargetingFields;
use FacebookAds\Object\Targeting;

/*
 * Ad_adsets class provides the methods to manage Ad's adsets
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
*/
class Ad_adsets {
    
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
     * The public method create_adsets creates ad's sets
     * 
     * @param array $args contains the arguments to create an ad's campaign
     * 
     * @since 0.0.7.6
     * 
     * @return integer with adsets id or false
     */ 
    public function create_adsets($args) {
        
        if ( $args ) {
            
            // Get user Currency
            $get_user_currency = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $args['account_id'] . '?fields=currency,min_daily_budget&access_token=' . $args['token']), true);
            
            // Verify if user has currency
            if ( !isset($get_user_currency['currency']) ) {
                
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('please_add_currency_to_account')
                );
                
            }

            // Get currency limits
            $get_currencies = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $args['account_id'] . '/minimum_budgets?access_token=' . $args['token']), true);
            
            foreach ( $get_currencies['data'] as $currency ) {
                
                if ( $get_user_currency['currency'] === $currency['currency'] ) {
                    
                    if ( !$args['target_cost'] || !is_numeric($args['target_cost']) || ( @$args['target_cost'] < $currency['min_daily_budget_high_freq'] ) ) {
                        
                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('please_add_correct_target_cost')
                        );                        
                        
                    }
                    
                }
                
            }

            if ( !$args['daily_budget'] || !is_numeric($args['daily_budget']) || ( @$args['daily_budget'] < $get_user_currency['min_daily_budget'] ) ) {

                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('please_add_the_minimum_daily_budget')
                );                        

            }
            
            // Allowed countries
            $allowed_countries = array(
                "US",
                "CA",
                "GB",
                "AR",
                "AU",
                "AT",
                "BE",
                "BR",
                "CL",
                "CN",
                "CO",
                "HR",
                "DK",
                "DO",
                "EG",
                "FI",
                "FR",
                "DE",
                "GR",
                "HK",
                "IN",
                "ID",
                "IE",
                "IL",
                "IT",
                "JP",
                "JO",
                "KW",
                "LB",
                "MY",
                "MX",
                "NL",
                "NZ",
                "NG",
                "NO",
                "PK",
                "PA",
                "PE",
                "PH",
                "PL",
                "RU",
                "SA",
                "RS",
                "SG",
                "ZA",
                "KR",
                "ES",
                "SE",
                "CH",
                "TW",
                "TH",
                "TR",
                "AE",
                "VE",
                "PT",
                "LU",
                "BG",
                "CZ",
                "SI",
                "IS",
                "SK",
                "LT",
                "TT",
                "BD",
                "LK",
                "KE",
                "HU",
                "MA",
                "CY",
                "JM",
                "EC",
                "RO",
                "BO",
                "GT",
                "CR",
                "QA",
                "SV",
                "HN",
                "NI",
                "PY",
                "UY",
                "PR",
                "BA",
                "PS",
                "TN",
                "BH",
                "VN",
                "GH",
                "MU",
                "UA",
                "MT",
                "BS",
                "MV",
                "OM",
                "MK",
                "LV",
                "EE",
                "IQ",
                "DZ",
                "AL",
                "NP",
                "MO",
                "ME",
                "SN",
                "GE",
                "BN",
                "UG",
                "GP",
                "BB",
                "AZ",
                "TZ",
                "LY",
                "MQ",
                "CM",
                "BW",
                "ET",
                "KZ",
                "NA",
                "MG",
                "NC",
                "MD",
                "FJ",
                "BY",
                "JE",
                "GU",
                "YE",
                "ZM",
                "IM",
                "HT",
                "KH",
                "AW",
                "PF",
                "AF",
                "BM",
                "GY",
                "AM",
                "MW",
                "AG",
                "RW",
                "GG",
                "GM",
                "FO",
                "LC",
                "KY",
                "BJ",
                "AD",
                "GD",
                "VI",
                "BZ",
                "VC",
                "MN",
                "MZ",
                "ML",
                "AO",
                "GF",
                "UZ",
                "DJ",
                "BF",
                "MC",
                "TG",
                "GL",
                "GA",
                "GI",
                "CD",
                "KG",
                "PG",
                "BT",
                "KN",
                "SZ",
                "LS",
                "LA",
                "LI",
                "MP",
                "SR",
                "SC",
                "VG",
                "TC",
                "DM",
                "MR",
                "AX",
                "SM",
                "SL",
                "NE",
                "CG",
                "AI",
                "YT",
                "CV",
                "GN",
                "TM",
                "BI",
                "TJ",
                "VU",
                "SB",
                "ER",
                "WS",
                "AS",
                "FK",
                "GQ",
                "TO",
                "KM",
                "PW",
                "FM",
                "CF",
                "SO",
                "MH",
                "VA",
                "TD",
                "KI",
                "ST",
                "TV",
                "NR",
                "RE"
            );
            
            $all_countries = array();
            
            if ( $args['countries'] ) {
                
                foreach ( $args['countries'] as $country ) {

                    if ( in_array($country[1], $allowed_countries) ) {
                        
                        $all_countries[] = $country[1];
                        
                    }
                    
                }
                
            }
            
            if ( !$all_countries ) {

                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('please_select_a_country')
                );                        

            } 
            
            $platforms = array();

            $os = array();
            
            if ( $args['mobile_type'] && !$args['desktop_type'] ) {
                $device_platforms = array('mobile');
                $os = array('android', 'ios');
            } else if ( !$args['mobile_type'] && $args['desktop_type'] ) {
                $device_platforms = array('desktop');
                $os = array('windows');
            } else {
                $device_platforms = array('desktop', 'mobile');
                $os = array('android', 'ios', 'windows');
            }
            
            if ( $args['selected_placements'] ) {
                
                foreach ( $args['selected_placements'] as $key => $value ) {

                    // Verify if Facebook is supported
                    if ( ($value[1] === 'ad-set-placement-facebook-feeds') || ($value[1] === 'ad-set-placement-facebook-feeds2') ) {
                        $platforms[] = 'facebook';
                        $platforms[] = 'audience_network';
                    }
                    
                    // Verify if Instagram is supported
                    if ( ($value[1] === 'ad-set-placement-instagram-feed') || ($value[1] === 'ad-set-placement-instagram-feed2') ) {
                        
                        $platforms[] = 'instagram';
                        
                    }
                    
                    // Verify if Messenger is supported
                    if ( ($value[1] === 'ad-set-placement-messenger-inbox') || ($value[1] === 'ad-set-placement-messenger-inbox2') ) {
                        
                        if ( !isset($platforms['facebook']) ) {
                            $platforms[] = 'facebook';
                        }
                        
                        $platforms[] = 'messenger';
                        
                    }                    

                }
            
            }

            if ( isset($args['region']) ) {

                // Set region
                $locations = array(
                    
                    'regions' => array(
                        array(
                            'key' => $args['region']
                        )
                    )
                    
                );

                if ( isset($args['city']) ) {

                    $locations = array(

                        'cities' => array(
                            array(
                                'key' => $args['city'],
                                'radius' => 10,
                                'distance_unit' => 'mile'
                            )
                        )

                    );

                }

            } else {

                // Set country
                $locations = array(
                    'countries' => $all_countries,
                );

            }
            
            $targeting = array(
                TargetingFields::GEO_LOCATIONS => $locations,
                TargetingFields::USER_OS => $os,
                TargetingFields::PUBLISHER_PLATFORMS => $platforms,
                TargetingFields::DEVICE_PLATFORMS => $device_platforms
            );
            
            if ( $args['age_from'] ) {
                
                if ( ( (int)$args['age_from'] > 17 ) && ( (int)$args['age_from'] < 66 ) ) {
                    
                    $targeting['age_min'] = $args['age_from'];
                    
                }
                
            }
            
            if ( $args['age_to'] ) {
                
                if ( ( (int)$args['age_to'] > 19 ) && ( (int)$args['age_to'] < 71 ) ) {
                    
                    $targeting['age_max'] = (int)$args['age_to'];
                    
                }
                
            }
            
            if ( ($args['female_gender'] === '1') && ( $args['male_gender'] !== '1' ) ) {
                
                $targeting['genders'] = array(2);
                
            }
            
            if ( ($args['female_gender'] !== '1') && ( $args['male_gender'] === '1' ) ) {
                
                $targeting['genders'] = array(1);
                
            }

            try {

                $account = new AdAccount($args['account_id']);

                $fields = array();
                $params = array(
                    AdSetFields::NAME => $args['name'],
                    AdSetFields::OPTIMIZATION_GOAL => $args['optimization_goal'],
                    AdSetFields::BILLING_EVENT => $args['billing_event'],
                    AdSetFields::BID_AMOUNT => $args['target_cost'],
                    AdSetFields::DAILY_BUDGET => $args['daily_budget'],
                    AdSetFields::CAMPAIGN_ID => $args['campaign_id'],
                    AdSetFields::TARGETING => (new Targeting())->setData($targeting),
                    'status' => 'ACTIVE'
                );

                $adset = $account->createAdSet($fields, $params);

            } catch (\Throwable $e) {

                // Get error
                $error = json_decode($e->getResponse()->getBody(), true);

                // Verify if error was decoded
                if ( isset($error['error']['error_user_msg']) ) {

                    return array(
                        'success' => FALSE,
                        'message' => $error['error']['error_user_msg']
                    );
                    
                } else {

                    return array(
                        'success' => FALSE,
                        'message' => 'Invalid parametrs.'
                    );
                    
                }

            }

            if ( $adset->id ) {

                return array(
                    'success' => TRUE,
                    'adset_id' => $adset->id
                );
                
            } else {

                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('ad_set_not_created_successfully')
                );
                
            }
            
        } else {
         
            return array(
                'success' => FALSE,
                'message' => 'Invalid parametrs.'
            );
            
        }        
        
    }
    
    /**
     * The public method load_select_ad_sets loads ad sets for select dropdown
     *
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function load_select_ad_sets() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('campaign_id', 'Campaign ID', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('key', 'Key', 'trim|required');
            
            // Get data
            $campaign_id = $this->CI->input->post('campaign_id');
            $key = $this->CI->input->post('key');

            if ( $this->CI->form_validation->run() !== false ) {
            
                // Get selected account
                $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');

                if ( $account ) {
                    
                    $adsets = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $campaign_id . '/adsets?fields=name,objective&limit=1000&access_token=' . $account[0]->token), true);

                    if ( $adsets ) {

                        $all_adsets = array(
                            'data'
                        );

                        $i = 0;

                        foreach ( $adsets['data'] as $adset ) {

                            if ( preg_match("/{$key}/i", $adset['name'] ) ) {
                                $all_adsets['data'][] = $adset;
                                $i++;
                            }

                            if ( $i > 9 ) {
                                break;
                            }

                        }

                        if ( isset($all_adsets['data']) ) {

                            $data = array(
                                'success' => TRUE,
                                'ad_sets' => $all_adsets
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
            'message' => $this->CI->lang->line('no_adsets_found')
        );

        echo json_encode($data);
        
    }
    
    /**
     * The public method load_select_all_ad_sets loads ad sets for select dropdown
     *
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function load_select_all_ad_sets() {
        
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
                        
                        $ad_sets = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/adsets?fields=name&limit=1000&access_token=' . $account[0]->token), true);

                        if ( $ad_sets ) {
                            
                            $all_sets = array(
                                'data'
                            );

                            $i = 0;

                            foreach ( $ad_sets['data'] as $ad_set ) {

                                if ( preg_match("/{$key}/i", $ad_set['name'] ) ) {
                                    $all_sets['data'][] = $ad_set;
                                    $i++;
                                }

                                if ( $i > 9 ) {
                                    break;
                                }

                            }
                            
                            if ( isset($all_sets['data']) ) {

                                $data = array(
                                    'success' => TRUE,
                                    'ad_sets' => $all_sets
                                );

                                echo json_encode($data);
                                exit();
                                
                            }

                        }                        
                        
                    } else {

                        $ad_sets = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/adsets?fields=name&limit=10&access_token=' . $account[0]->token), true);

                        if ( $ad_sets ) {

                            $data = array(
                                'success' => TRUE,
                                'ad_sets' => $ad_sets
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
            'message' => $this->CI->lang->line('no_adsets_found')
        );

        echo json_encode($data);
        
    }
    
}

/* End of file ad_adsets.php */
