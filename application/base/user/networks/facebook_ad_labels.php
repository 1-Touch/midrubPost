<?php
    
/**
 * Facebook Ad Pages
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Facebook Ad Labels
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace MidrubBase\User\Networks;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');
defined('MIDRUB_FACEBOOK_ADS_AD_LABELS_PATH') OR define('MIDRUB_FACEBOOK_ADS_AD_LABELS_PATH', APPPATH . 'application/base/user/apps/collection/facebook_ads/automatizations/ad_labels');

// Define the namespaces to use
use MidrubBase\User\Interfaces as MidrubBaseUserInterfaces;
use MidrubBase\User\Apps\Collection\Facebook_ads\Helpers as MidrubBaseUserAppsCollectionFacebook_adsHelpers;
use FacebookAds\Object\AdAccountActivity;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdAccountFields;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\AdImage;
use FacebookAds\Object\Fields\AdImageFields;

/**
 * Facebook_ad_labels class - allows users to connect to their Facebook Ads Labels and create ads.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Facebook_ad_labels implements MidrubBaseUserInterfaces\Networks {
    
    /**
     * Class variables
     */
    public $CI;
    
    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Get the Facebook App ID
        $this->app_id = get_option('facebook_app_id');
        
        // Get the Facebook App Secret
        $this->app_secret = get_option('facebook_app_secret');

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
        
        // Load the Vendor dependencies
        require_once FCPATH . 'vendor/autoload.php';
        
        // Set required args
        $args = array(
          'app_id' => $this->app_id,
          'app_secret' => $this->app_secret,
          'default_graph_version' => 'v3.3',
          'default_access_token' => '{access-token}',
        );
        
        
        if ( ($this->app_id != '') && ( $this->app_secret != '') ) {
            
            // Load the Facebook Class
            $this->fb = new \Facebook\Facebook($args);
            
        }
        
    }
    
    /**
     * The public method check_availability checks if the Facebook api is configured correctly.
     *
     * @return boolean true or false
     */
    public function check_availability() {
        
        return true;
        
    }
    
    /**
     * The public method connect will open the Ad Labels popup
     *
     * @return void
     */
    public function connect() {
        
        $check = 0;
        
        // Get All user's Ad Labels
        $this->CI->db->select('ads_labels.label_id, ads_labels.label_name, ads_labels.time');
        $this->CI->db->from('ads_labels');
        $this->CI->db->join('ads_labels_meta', 'ads_labels.label_id=ads_labels_meta.label_id', 'LEFT');
        $this->CI->db->where(array(
                                   'ads_labels.user_id' => $this->CI->user_id
                                   )
                             );
        
        $this->CI->db->order_by('ads_labels.label_id', 'desc');
        $this->CI->db->group_by('ads_labels.label_id');
        $query = $this->CI->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $results = $query->result();
            
            foreach ( $results as $label ) {
                
                if ( $this->CI->networks->add_network('facebook_ad_labels', $label->label_id, '', $this->CI->user_id, '', $label->label_name, '', '') ) {
                    
                    $check++;
                    
                }
                
            }
            
        }
        
        if ( $check > 0 ) {
            
            // Display the success message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_all_ad_labels_were_connected') . '</p>', true);
            
        } else {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_no_ad_labels_were_connected') . '</p>', false);
            
        }
        
    }
    
    /**
     * The public method save will get access token.
     *
     * @param string $token contains the token for some social networks
     *
     * @return void
     */
    public function save($token = null) {
        
    }
    
    /**
     * The public method post publishes posts on Facebook Groups.
     *
     * @param array $args contains the post data
     * @param integer $user_id is the ID of the current user
     *
     * @return boolean true if post was published
     */
    public function post($args, $user_id = null) {
        
        // Get user details
        if ($user_id) {
            
            // if the $user_id variable is not null, will be published a postponed post
            $user_details = $this->CI->networks->get_network_data('facebook_ad_labels', $user_id, $args['account']);
            
        } else {
            
            $user_id = $this->CI->user_id;
            $user_details = $this->CI->networks->get_network_data('facebook_ad_labels', $user_id, $args['account']);
            
        }
        
        // Verify if Ad Labels Automatization exists
        if ( !is_dir(APPPATH . 'base/user/apps/collection/facebook_ads/automatizations/ad_labels') ) {

            // Save the error
            $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', $this->CI->lang->line('networks_facebook_ad_manager_missing') );
            
            // Then return false
            return false;
            
        }
        
        // Verify if title exists
        if ( !$args['title'] ) {
            
            // Save the error
            $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', $this->CI->lang->line('networks_title_required_for_ad') );
            
            // Then return false
            return false;
            
        }
        
        // Verify if link exists
        if ( !$args ['url'] ) {
            
            // Save the error
            $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', $this->CI->lang->line('networks_link_required_for_ad') );
            
            // Then return false
            return false;
            
        }
        
        // Load models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_AD_LABELS_PATH . 'models/', 'Ads_labels_model', 'ads_labels_model' );
        
        // Get All user's Ad Labels
        $this->CI->db->select('*');
        $this->CI->db->from('ads_labels_meta');
        $this->CI->db->where(
            array(
                'label_id' => $user_details[0]->net_id
            )
        );

        $query = $this->CI->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $label_info = $this->CI->ads_labels_model->get_label( $user_id, $user_details[0]->net_id );
            
            if ( $label_info[0]->time > 0 ) {
                
                $time = $label_info[0]->time;
                
                switch ( $time ) {
                        
                    case '1':
                        
                        $time = 3600;
                        
                        break;
                        
                    case '2':
                        
                        $time = 10800;
                        
                        break;
                        
                    case '3':
                        
                        $time = 18000;
                        
                        break;
                        
                    case '4':
                        
                        $time = 36000;
                        
                        break;
                        
                    case '5':
                        
                        $time = 86400;
                        
                        break;
                        
                    case '6':
                        
                        $time = 172800;
                        
                        break;
                        
                    default:
                        
                        $time = 3600;
                        
                        break;
                        
                }
                
            } else {
                
                $time = 3600;
                
            }
            
            $metas = array();
            
            $results = $query->result();
            
            foreach ( $results as $result ) {
                
                $metas[$result->meta_name] = $result->meta_value;
                
            }
            
            // Create an array
            $array = array(
                'MidrubBase',
                'User',
                'Apps',
                'Collection',
                'Facebook_ads',
                'Main'
            );
            
            // Implode the array above
            $cl = implode('\\',$array);
            
            // Load models
            $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'models/', 'Ads_networks_model', 'ads_networks_model' );

            // Load language
            $this->CI->lang->load('facebook_ads_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_FACEBOOK_ADS);
            
            if ( isset( $metas['ad_account'] ) ) {
                
                if ( !isset( $metas['ad_set_id'] ) ) {
                    
                    // Save the error
                    $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', $this->CI->lang->line('networks_ad_set_not_selected') );
                    
                    // Then return false
                    return false;
                    
                }
                
                if ( !isset( $metas['facebook_page_id'] ) ) {
                    
                    // Save the error
                    $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', $this->CI->lang->line('networks_ad_facebook_page_not_selected') );
                    
                    // Then return false
                    return false;
                    
                }
                
                // Get account's data
                $get_account = $this->CI->ads_networks_model->get_account($metas['ad_account']);
                
                if ( $get_account ) {
                    
                    Api::init($this->app_id, $this->app_secret, $get_account[0]->token);

                    try {

                        $account_data = $this->fb->get(
                            '/' . $get_account[0]->net_id . '?fields=funding_source,name,account_status',
                            $get_account[0]->token
                        );

                    } catch (Facebook\Exceptions\FacebookResponseException $e) {

                        // Save the error
                        $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', $e->getMessage() );

                    } catch (Facebook\Exceptions\FacebookSDKException $e) {

                        // Save the error
                        $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', $e->getMessage() );

                    }

                    $ad_account = $account_data->getDecodedBody();

                    if ( $ad_account['account_status'] !== 1 ) {

                        // Save the error
                        $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', $this->CI->lang->line('networks_the_ad_account_is_not_active') );
                        
                        // Then return false
                        return false;

                    }
                    
                    $add_image = '';
                    
                    if ( $args['img'] ) {

                        try {

                            $image = (new AdAccount($get_account[0]->net_id))->createAdImage(
                                array(),
                                array(
                                    AdImageFields::FILENAME => str_replace(base_url(), FCPATH, $args['img'][0]['body'])
                                )
                            );

                        } catch (Facebook\Exceptions\FacebookResponseException $e) {

                            // Save the error
                            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', $e->getMessage());

                        } catch (Facebook\Exceptions\FacebookSDKException $e) {

                            // Save the error
                            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', $e->getMessage());
                            
                        }

                        if ( !empty($image->images[str_replace(base_url() . 'assets/share/', '', $args['img'][0]['body'])]['hash']) ) {
                            $add_image = $image->images[str_replace(base_url() . 'assets/share/', '', $args['img'][0]['body'])]['hash'];
                        }
                        
                    }
                    
                    $platforms = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $metas['ad_set_id'] . '/?fields=targeting{publisher_platforms}&access_token=' . $get_account[0]->token), true);
                    
                    if ( !empty($platforms['targeting']['publisher_platforms']) ) {
                        
                        $selected_placements = array();
                        
                        foreach ( $platforms['targeting']['publisher_platforms'] as $platform ) {
                            
                            if ( $platform === 'facebook' ) {
                                
                                $selected_placements[] = array(
                                    '1' => 'ad-set-placement-facebook-feeds'
                                );
                                
                            } else if ( $platform === 'messenger' ) {
                                
                                $selected_placements[] = array(
                                    '1' => 'ad-set-placement-messenger-inbox'
                                );
                                
                            } else if ( $platform === 'instagram' && $args['img'] ) {
                                
                                $selected_placements[] = array(
                                    '1' => 'ad-set-placement-instagram-feed'
                                );
                                
                            }
                            
                        }

                        $ad_args = array(
                            'account_id' => $get_account[0]->net_id,
                            'objective' => 'LINK_CLICKS',
                            'ad_name' => $args['title'],
                            'ad_text' => $args['post'],
                            'website_url' => $args['url'],
                            'adimage' => $add_image,
                            'preview_image' => '',
                            'video_id' => '',
                            'fb_page_id' => $metas['facebook_page_id'],
                            'instagram_id' => '',
                            'headline' => $args['title'],
                            'adset_id' => $metas['ad_set_id'],
                            'pixel_id' => '',
                            'pixel_conversion_id' => '',
                            'selected_placements' => $selected_placements,
                            'net_id' => $get_account[0]->net_id,
                            'token' => $get_account[0]->token
                        );
                        
                        if ( isset($metas['instagram_id']) ) {
                            
                            $ad_args['instagram_id'] = $metas['instagram_id'];
                            
                            // Verify if image exists
                            if ( $args['img'] ) {
                                $ad_args['preview_image'] = $args['img'][0]['body'];
                            }
                            
                        }
                        
                        // Create Ad
                        $response = (new MidrubBaseUserAppsCollectionFacebook_adsHelpers\Ad_ads)->create_ads($ad_args);

                        if ( !empty($response['ads']) ) {
                            
                            foreach ( $response['ads'] as $ad ) {
                                
                                $status = 2;
                                $error = $ad['description'];
                                $ad_id = '';
                                
                                if ( $ad['success'] ) {
                                    $error = '';
                                    $status = 1;
                                    $ad_id = $ad['id'];
                                }
                                
                                $data = array(
                                    'label_id' => $user_details[0]->net_id,
                                    'publisher_platforms' => serialize($selected_placements),
                                    'status' => $status,
                                    'platform_status' => $error,
                                    'ad_name' => $args['title'],
                                    'ad_id' => $ad_id,
                                    'created' => time(),
                                    'end_time' => (time() + $time)
                                );
                                
                                $this->CI->db->insert('ads_labels_stats', $data);
                                
                            }
                            
                            return true;
                            
                        } else {
                            
                            // Save the error
                            $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', $this->CI->lang->line('networks_ad_not_created') );
                            
                            // Then return false
                            return false;
                            
                        }
                        
                    } else {
                        
                        // Save the error
                        $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', $this->CI->lang->line('no_platforms_selected') . ' ' . $this->CI->lang->line('your_ad_set_should_have_platforms') );
                        
                        // Then return false
                        return false;
                        
                    }
                    
                } else {
                    
                    // Save the error
                    $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', $this->CI->lang->line('networks_facebook_ad_account_not_found') );
                    
                    // Then return false
                    return false;
                    
                }
                
            } else {
                
                // Save the error
                $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', $this->CI->lang->line('networks_facebook_ad_account_not_found') );
                
                // Then return false
                return false;
                
            }
            
        } else {
            
            // Save the error
            $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', $this->CI->lang->line('networks_facebook_ad_label_not_found') );
            
            // Then return false
            return false;
            
        }
        
    }
    
    /**
     * The public method get_info displays information about this class
     *
     * @return array with network data
     */
    public function get_info() {
        
        return array(
            'color' => '#4065b3',
            'icon' => '<i class="fab fa-facebook"></i>',
            'api' => array(),
            'types' => array('post', 'rss')
        );
        
    }
    
    /**
     * The public method preview generates a preview for Facebook Ad Labels
     *
     * @param $args contains the img or url
     *
     * @return array with html content
     */
    public function preview($args) {}
    
}

/* End of file facebook_ad_labels.php */
