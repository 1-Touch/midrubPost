<?php
/**
 * Facebook Groups
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Facebook Groups
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

// Define the namespaces to use
use MidrubBase\User\Interfaces as MidrubBaseUserInterfaces;

/**
 * Facebook_groups class - allows users to connect to their Facebook Groups and publish posts.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Facebook_groups implements MidrubBaseUserInterfaces\Networks {
    
    /**
     * Class variables
     */
    public $CI, $fb, $app_id, $app_secret, $fb_version = 'v5.0';
    
    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Get the Facebook App ID
        $this->app_id = get_option('facebook_groups_app_id');
        
        // Get the Facebook App Secret
        $this->app_secret = get_option('facebook_groups_app_secret');

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
        
        // Load the Vendor dependencies
        require_once FCPATH . 'vendor/autoload.php';
        
        // Set required args
        $args = array(
          'app_id' => $this->app_id,
          'app_secret' => $this->app_secret,
          'default_graph_version' => $this->fb_version,
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
        
        // Verify if app_id and app_secret exists
        if ( ($this->app_id != '') AND ( $this->app_secret != '') ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method connects will redirect user to facebook login page
     *
     * @return void
     */
    public function connect() {
        
        // Redirect use to the login page
        $helper = $this->fb->getRedirectLoginHelper();
        
        // Permissions to request
        $permissions = array('publish_to_groups', 'groups_access_member_info');
        
        // Get redirect url
        $loginUrl = $helper->getLoginUrl(site_url('user/callback/facebook_groups'), $permissions);
        
        // Redirect
        header('Location:' . $loginUrl);
    }
    
    /**
     * The public method saves will get access token.
     *
     * @param string $token contains the token for some social networks
     *
     * @return void
     */
    public function save($token = null) {
        
        // Define the callback status
        $check = 0;
        
        // Obtain the user access token from redirect
        $helper = $this->fb->getRedirectLoginHelper();
        
        // Get the user access token
        $access_token = $helper->getAccessToken(site_url('user/callback/facebook_groups'));
        
        // Convert it to array
        $access_token = (array) $access_token;
        
        // Get array value
        $access_token = array_values($access_token);
        
        // Verify if access token exists
        if ( isset($access_token[0]) ) {

            // Get user data
            $response = json_decode(get('https://graph.facebook.com/me/groups?limit=200&fields=administrator,name&access_token=' . $access_token[0]));

            // Verify if user has groups
            if ( !empty($response->data) ) {

                // Verify 
                if ($response->data[0]->id) {

                    // Calculate expire token period
                    $expires = '';

                    // Save groups
                    for ($y = 0; $y < count($response->data); $y++) {

                        if ( get_option('facebook_group_only_administrator') ) {

                            if ( !$response->data[$y]->administrator ) {
                                continue;
                            }

                        }

                        $this->CI->networks->add_network('facebook_groups', $response->data[$y]->id, $access_token[0], $this->CI->user_id, $expires, $response->data[$y]->name, '');

                    }

                    $check++;

                }

            } else {

                // Display the error message
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_your_account_don_has_groups') . '</p>', false);
                exit();
                
            }

        }
        
        if ( $check > 0 ) {
            
            // Display the success message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_all_facebook_groups_added') . '</p>', true);
            
        } else {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false);
            
        }
        
    }
    
    /**
     * The public method post publishes posts on Facebook Groups
     *
     * @param array $args contains the post data.
     * @param integer $user_id is the ID of the current user
     *
     * @return boolean true if post was published
     */
    public function post($args, $user_id = null) {
        // Get user details
        if ( $user_id ) {
            
            // Get network data
            $user_details = $this->CI->networks->get_network_data('facebook_groups', $user_id, $args['account']);
            
        } else {

            // Set current user_id
            $user_id = $this->CI->user_id;
        
            // Get network data
            $user_details = $this->CI->networks->get_network_data('facebook_groups', $user_id, $args['account']);
            
        }
        
        // Verify if user can use his App ID and App secret
        if ( get_option('facebook_pages_user_api_key') ) {
            
            // If api_key is empty missing no app_id and app_secret
            if( !$user_details[0]->api_key ) {
                
                return false;
                
            } else {
                
                // Verify if the Facebook SDK exists
                if (file_exists(FCPATH . 'vendor/facebook/php-sdk-v4/src/Facebook/autoload.php')) {
                    
                    try {
                        
                        // Call the Facebook Class
                        include FCPATH . 'vendor/facebook/php-sdk-v4/src/Facebook/autoload.php';
                        $this->fb = new Facebook\Facebook(['app_id' => $user_details[0]->api_key,'app_secret' => $user_details[0]->api_secret,'default_graph_version' => $this->fb_version, 'default_access_token' => '{access-token}']);
                        
                    } catch (Facebook\Exceptions\FacebookResponseException $e) {
                        
                        // When Graph returns an error
                        get_instance()->session->set_flashdata('error', 'Graph returned an error: ' . $e->getMessage());
                        
                    } catch (Facebook\Exceptions\FacebookSDKException $e) {
                        
                        // When validation fails or other local issues
                        get_instance()->session->set_flashdata('error', 'Facebook SDK returned an error: ' . $e->getMessage());
                        
                    }
                    
                }
                
            }
            
        }
        
        
        try {
            
            // Set access token
            $this->fb->setDefaultAccessToken($user_details[0]->token);
            
            // Get post content
            $post = $args['post'];
            
            // Verify if the title is not empty
            if ( $args['title'] ) {
                
                $post = $args['title'] . ' ' . $post;
                
            }
            
            // Verify if image exists
            if ($args['img']) {

                if ( strpos($args['img'][0]['body'], '.gif') !== false ) {

                    // Publish the post
                    $post = $this->fb->post('/' . $user_details[0]->net_id . '/videos',  array(
                        'description' => $post,
                        'source' => $args['img'][0]['body'],
                        'file_url' => $args['img'][0]['body'],
                        'caption' => ''
                    ));

                } else {
                
                    $photos = array();
                    
                    // Verify if url exists
                    if ( $args['url'] ) {
                        
                        $post = str_replace($args['url'], ' ', $post) . ' ' . short_url($args['url']);
                        
                    }
                    
                    $photos['message'] = $post;
                    
                    $e = 0;
                    
                    foreach ( $args['img'] as $img ) {
                        
                        // Try to upload the image
                        $status = $this->fb->post('/' . $user_details[0]->net_id . '/photos', array('url' => $img['body'], 'published' => FALSE), $user_details[0]->token);
                        
                        if ( $status->getDecodedBody() ) {
                            
                            $stat = $status->getDecodedBody();
                            
                            $photos['attached_media[' . $e . ']'] = '{"media_fbid":"' . $stat['id'] . '"}';
                            $e++;
                            
                        }
                        
                    }
                    
                    
                    
                    // Decode the response
                    if ( $photos ) {
                        
                        $post = $this->fb->post('/' . $user_details[0]->net_id . '/feed', $photos, $user_details[0]->token);
                        
                    }

                }
                
            } elseif ( $args['video'] ) {
                
                // Verify if url exists
                if ( $args['url'] ) {
                    
                    $post = str_replace($args['url'], ' ', $post) . ' ' . short_url($args['url']);
                    
                }
                
                // Publish the post
                $post = $this->fb->post('/' . $user_details[0]->net_id . '/videos',  array( 'description' => $post, 'source' => $this->fb->videoToUpload(str_replace(base_url(), FCPATH, $args['video'][0]['body']))));
                
            } elseif ( $args['url'] ) {
                
                // Create post content
                $linkData = array(
                                  'link' => short_url($args['url']),
                                  'message' => str_replace($args['url'], ' ', $post)
                                  );
                
                // Publish the post
                $post = $this->fb->post('/' . $user_details[0]->net_id . '/feed', $linkData, $user_details[0]->token);
                
            } else {
                
                // Create post content
                $linkData = array(
                                  'message' => $post
                                  );
                
                // Publish the post
                $post = $this->fb->post('/' . $user_details[0]->net_id . '/feed', $linkData, $user_details[0]->token);
                
            }
            
            // Decode the post response
            if ( $post->getDecodedBody() ) {
                
                $mo = $post->getDecodedBody();
                
                if ( @$mo['id'] && @$args['id'] ) {
                    
                    sami($mo['id'], $args['id'], $args['account'], 'facebook_groups', $user_id);
                    
                }
                
                return $mo['id'];
                
            } else {
                
                // Save the error
                $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', json_encode($mo) );
                
                return false;
                
            }
            
        } catch (Exception $e) {
            
            // Save the error
            $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', json_encode($e->getMessage()) );
            
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

        $checked = '';

        if ( get_option('facebook_group_only_administrator') ) {
            $checked = ' checked';
        }
        
        return array(
          'color' => '#4065b3',
          'icon' => '<i class="fab fa-facebook"></i>',
          'api' => array('app_id', 'app_secret'),
          'types' => array('post', 'insights', 'rss'),
          'extra_content' => '<div class="form-group">'
                                . '<div class="row">'
                                    . '<div class="col-lg-10 col-xs-6">'
                                        . '<label for="menu-item-text-input">'
                                            . 'Users will get only the Groups where them are administrator'
                                        . '</label>'
                                    . '</div>'
                                . '<div class="col-lg-2 col-xs-6">'
                                    . '<div class="checkbox-option pull-right">'
                                        . '<input id="facebook_group_only_administrator" name="facebook_group_only_administrator" class="social-option-checkbox" type="checkbox" ' . $checked . '>'
                                        . '<label for="facebook_group_only_administrator"></label>'
                                    . '</div>'
                                . '</div>'
                            . '</div>'
                        . '</div>'
        );
        
    }
    
    /**
     * The public method preview generates a preview for facebook groups.
     *
     * @param $args contains the img or url
     *
     * @return array with html content
     */
    public function preview($args) {
    }

}

/* End of file facebook_groups.php */
