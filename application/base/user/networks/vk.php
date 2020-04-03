<?php
/**
 * Vk
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Vk
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
 * Vk class - allows users to connect to their Vk Account and publish posts.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Vk implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    protected $CI, $check, $params, $version='5.92', $redirect_uri, $client_id, $client_secret;
    
    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Get VK's client_id
        $this->client_id = get_option('vk_client_id');
        
        // Get VK's client_secret
        $this->client_secret = get_option('vk_client_secret');

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
        
        // Get the redirect url
        $this->redirect_uri = 'http://oauth.vk.com/authorize?client_id=' . $this->client_id . '&scope=wall,offline,photos,video,friends&redirect_uri=http://oauth.vk.com/blank.html&display=page&v=' . $this->version . '&response_type=token';
        
        // Params for request
        $this->params = array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_uri,
            'response_type' => 'code'
        );
        
    }
    /**
     * The public method check_availability checks if the VK api is configured correctly.
     *
     * @return boolean true or false
     */
    public function check_availability() {

        if ( ($this->client_id != '') AND ( $this->client_secret != '') ) {

            return true;

        } else {

            return false;

        }

    }
    
    /**
     * The public method connect will redirect user to VK login page.
     * 
     * @return void
     */
    public function connect() {
        
        if ( $this->params ) {
            
            // Get redirect url
            $loginUrl = 'http://oauth.vk.com/authorize?' . urldecode(http_build_query($this->params));
            
            // Redirect
            header('Location:' . $loginUrl);
            
        }
        
    }
    
    /**
     * The public method save will get access token.
     *
     * @param string $token contains the token for some social networks
     * 
     * @return boolean true or false
     */
    public function save($token = null) {
        
        // Get the url
        $token = explode('#code=', $token);
        
        // Verify if token exists
        if ( !isset($token[1]) ) {
            return false;
        }
        
        // Will check if the token is valid
        $params = array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'code' => $token[1],
            'redirect_uri' => $this->redirect_uri
        );
        
        // Get cURL resource
        $curl = curl_init();
        
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => 'https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params)),CURLOPT_HEADER => false));
        
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        
        // Close request to clear up some resources
        curl_close($curl);
        
        // Decode response
        $token = (array)json_decode($resp);
        
        // If token is valid
        if ( isset($token['access_token']) ) {
            
            // Set token
            $token = $token['access_token'];
            
            // Permissions
            $params = array(
                'fields' => 'uid,screen_name,photo_big,wall,offline',
                'access_token' => $token,
                'v' => $this->version
            );
            
            // Get cURL resource
            $curl = curl_init();
            
            // Set some options - we are passing in a useragent too here
            curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => 'https://api.vk.com/method/users.get'.'?'.urldecode(http_build_query($params)),CURLOPT_HEADER => false));
            
            // Send the request & save response to $resp
            $userInfo = curl_exec($curl);
            
            // Close request to clear up some resources
            curl_close($curl);

            // Get user data
            $userInfo = json_decode($userInfo, true);
            
            // Verify if user data is correct
            if ( isset($userInfo['response'][0]['id']) ) {
                
                // Verify if account was already added
                if ( $this->CI->networks->check_account_was_added('vk', $userInfo['response'][0]['id'], $this->CI->user_id) ) {
                    
                    return false;
                    
                } else {
                    
                    $aid = 0;
                    
                    $params = array(
                        'owner_id' => $userInfo['response'][0]['id'],
                        'count_id' => 1,
                        'access_token' => $token,
                        'v' => $this->version
                    );

                    // Get cURL resource
                    $curl = curl_init();
                    
                    // Set some options - we are passing in a useragent too here
                    curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => 'https://api.vk.com/method/photos.getAlbums'.'?'.urldecode(http_build_query($params)),CURLOPT_HEADER => false));
                    
                    // Send the request & save response to $resp
                    $res = curl_exec($curl);
                    
                    // Close request to clear up some resources
                    curl_close($curl);
                    
                    // Decode the response
                    $resp = json_decode($res, true);

                    // Verify if album was created
                    if ( isset($resp['response']['items'][0]['id']) ) {
                        $aid = $resp['response']['items'][0]['id'];
                    }

                    // Verify if the user's data is correct
                    if ( isset($userInfo['response'][0]['id']) && isset($userInfo['response'][0]['first_name']) && isset($userInfo['response'][0]['last_name']) && isset($userInfo['response'][0]['photo_big']) ) {

                        // Try to save account
                        if ( $this->CI->networks->add_network('vk', $userInfo['response'][0]['id'], $token, $this->CI->user_id, '', $userInfo['response'][0]['first_name'] . ' ' . $userInfo['response'][0]['last_name'], $userInfo['response'][0]['photo_big'], $aid) ) {
                            return true;
                        }

                    }
                    
                }
                
            }
            
        }
        
        return false;
        
    }
    
    /**
     * The public method post publishes posts on Vk.
     *
     * @param array $args contains the post data.
     * @param integer $user_id is the ID of the current user
     * 
     * @return boolean true if post was published
     */
    public function post($args, $user_id = null) {

        // Verify if user_id exists
        if ( $user_id ) {
            
            // Get account details
            $user_details = $this->CI->networks->get_network_data('vk', $user_id, $args['account']);
            
        } else {
            
            // Set user's ID
            $user_id = $this->CI->user_id;

            // Get account details
            $user_details = $this->CI->networks->get_network_data('vk', $user_id, $args['account']);
            
        }
        
        // Get post's data
        $post = $args['post'];
        
        // Verify if url exists
        if ( $args['url'] ) {
            $post = str_replace($args['url'], ' ', $post);
        }
        
        // Verify if title is not empty
        if( $args['title'] ) {
            
            $post = $args['title']. ' '. $post;
            
        }
        
        // Set the params for the new post
        $post_params = array(
            'owner_id' => $user_details[0]->net_id,
            'message' => $post,
            'access_token' => $user_details[0]->token,
            'v' => $this->version
        );
        
        // Verify if the post has an image
        if ($args['img']) {
            
            // Verify if user has a photo album
            if ( !trim($user_details[0]->secret) ) {
                
                // If album missing, will publish the image as link
                $post_params['attachments'] = $args['img'][0]['body'];
                
            } else {
                
                // Set params to get the media server
                $params = array(
                    'album_id' => $user_details[0]->secret,
                    'save_big' => 1,
                    'access_token' => $user_details[0]->token,
                    'v' => $this->version
                );
                
                // Get cURL resource
                $curl = curl_init();
                
                // Set some options to in a useragent
                curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => 'https://api.vk.com/method/photos.getUploadServer'.'?'.urldecode(http_build_query($params)),CURLOPT_HEADER => false));
                
                // Send the request
                $resp = curl_exec($curl);
                
                // Close request to clear up some resources
                curl_close($curl);
                
                // Get response
                $resp = json_decode($resp, true);
                
                // Verify if the server was found
                if ( isset($resp['response']['upload_url']) ) {
                    
                    // Define the number of files
                    $e = 1;
                    
                    // Create attachement array for all photos
                    $attachements = array();

                    // List all images to upload
                    foreach ( $args['img'] as $img ) {
                        
                        // Create a multipart content body for images
                        $image = str_replace(base_url(), FCPATH, $img['body']);
                        $file = new \CurlFile($image);
                        
                        // Add image to attachement
                        $attachements['file' . $e] = $file;
                        
                        // Increase the number of the file
                        $e++;

                    }

                    // Upload the images
                    $curl = curl_init( $resp['response']['upload_url'] );
                    
                    // No header
                    curl_setopt ( $curl, CURLOPT_HEADER, false );
                    
                    // We expect response
                    curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true );
                    
                    // No SSL verify
                    curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, false );
                    
                    // Send POST request
                    curl_setopt ( $curl, CURLOPT_POST, true );
                    
                    // Define the POST's fields
                    curl_setopt ( $curl, CURLOPT_POSTFIELDS, $attachements );
                    
                    // Get response
                    $data = curl_exec($curl);
                    
                    curl_close($curl);
                    
                    // Get data
                    $data = json_decode( $data );
                    
                    // Verifies if the images were uploaded
                    if ( @$data->server ) {
                        
                        // Set the params to save images in the selected album
                        $params = array(
                            'album_id' => $user_details[0]->secret,
                            'server' => $data->server,
                            'photos_list' => $data->photos_list,
                            'hash' => $data->hash,
                            'caption' => $post,
                            'access_token' => $user_details[0]->token,
                            'v' => $this->version
                        );

                        // Get cURL resource
                        $curl = curl_init();

                        // Set some options to in a useragent
                        curl_setopt_array($curl, array(
                            CURLOPT_RETURNTRANSFER => 1,
                            CURLOPT_URL => 'https://api.vk.com/method/photos.save',
                            CURLOPT_HEADER => false,
                            CURLOPT_POST => 1,
                            CURLOPT_POSTFIELDS => $params
                        ));

                        // Send the request
                        $resp = curl_exec($curl);

                        // Close request to clear up some resources
                        curl_close($curl);

                        $resp = json_decode($resp, true);

                        $ids = '';

                        // Verify if the images were saved and prepare to attach them in the post
                        if ( isset($resp['response'][0]['id']) ) {

                            // List all uploaded images
                            for ( $u = 0; $u < count($resp['response']); $u++ ) {

                                if ( $u < 1 ) {

                                    $ids .= 'photo' . $user_details[0]->net_id . '_' . $resp['response'][$u]['id'];

                                } else {

                                    $ids .= ',photo' . $user_details[0]->net_id . '_' . $resp['response'][$u]['id'];

                                } 

                            }

                            $post_params['attachments'] = $ids;

                        }

                    }
                    
                    
                } else {
                    
                    // If the images can't be uploaded, publish the first as a link
                    $post_params['attachments'] = $args['img'][0]['body'];
                    
                }
                
            }
            
            // Verify if url exists and add as a link if exists
            if ( $args['url'] ) {
            
                $post_params['attachments'] = $post_params['attachments'] . ',' . short_url($args['url']);

            }
            
        } else if ( $args['video'] ) {
            
            // Prepare the params to get the server address
            $params = array (
                'access_token' => $user_details[0]->token,
                'v' => $this->version,
                'name' => $args['title'],
                'description' => $post,
                'no_comments' => 0
            );

            // Get cURL resource
            $curl = curl_init();

            // Set some options to in a useragent
            curl_setopt($curl, CURLOPT_URL, 'https://api.vk.com/method/video.save' . '?' . http_build_query($params));
            
            // Send GET request
            curl_setopt($curl, CURLOPT_HTTPGET, TRUE);
            
            // Return data
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

            // Send the request
            $resp = json_decode(curl_exec($curl), true);

            // Close request
            curl_close($curl);

            // If server address exists upload the video
            if ( isset($resp['response']['upload_url']) ) {

                // Create a multipart content body for videos
                $video = str_replace(base_url(), FCPATH, $args['video'][0]['body']);
                $file = new \CurlFile($video);

                $params = array(
                    'video_file' => $file
                );
                
                // Get cURL resource
                $curl = curl_init();
                
                // Set some options to in a useragent
                curl_setopt($curl, CURLOPT_URL, $resp['response']['upload_url']);
                
                // Set POST type
                curl_setopt($curl, CURLOPT_POST, TRUE);
                
                // Set POST's fields
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                
                // We expect response
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                
                // Set safe upload
                curl_setopt($curl, CURLOPT_SAFE_UPLOAD, TRUE);

                // Decode the response
                $resp = json_decode(curl_exec($curl), true);

                // Close request
                curl_close($curl);

                // Verify if video was uploaded correctly
                if ( isset($resp['video_id']) ) {

                    $post_params['attachments'] = 'video' . $user_details[0]->net_id . '_' . $resp['video_id'];

                }


            }
            
            if ( $args['url'] ) {
            
                $post_params['attachments'] = $post_params['attachments'] . ',' . short_url($args['url']);

            }
            
        } else if ( $args['url'] ) {
            
            $post_params['attachments'] = short_url($args['url']);
            
        }
        
        // Get cURL resource
        $curl = curl_init();
        
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.vk.com/method/wall.post',
            CURLOPT_HEADER => false,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $post_params
        ));
        
        // Send the request & save response to $resp
        $publish = curl_exec($curl);
        
        // Close request to clear up some resources
        curl_close($curl);
        
        // Decode response
        $publish = json_decode($publish);

        if ( !empty($publish->error) ) {
            
            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($publish->error) );
            
        } else {
            
            return true;
            
        }
        
    }
    
    /**
     * The public method get_info displays information about this class.
     * 
     * @return array with network's data
     */
    public function get_info() {
        
        return array(
            'color' => '#6383a8',
            'icon' => '<i class="fab fa-vk"></i>',
            'popup' => 'class="openpopup btn btn-default"',
            'hidden' => '<div>
                            <div class="col-md-12 clean">
                                <div class="input-group search">
                                    <input type="text" placeholder="' . $this->CI->lang->line('networks_vk_copy_paste_url') . '" class="form-control search_accounts token">
                                    <span class="input-group-btn search-m">
                                        <button class="btn save-token" type="button"><i class="far fa-save"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>',
            'api' => array('client_id', 'client_secret'),
            'types' => array('post', 'rss', 'insights')
        );
        
    }
    
    /**
     * The public method preview generates a preview for Vk.
     *
     * @param $args contains the img or url.
     * 
     * @return array with html content
     */
    public function preview($args) {
        
    }
    
}

/* End of file vk.php */
