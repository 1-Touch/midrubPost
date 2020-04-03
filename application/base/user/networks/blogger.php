<?php
/**
 * Blogger
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Blogger
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
 * Blogger class - allows users to connect to their Blogger blogs and publish posts.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Blogger implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    protected $connect, $client, $CI, $clientId, $clientSecret, $apiKey, $appName, $scriptUri;

    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Get the Blogger's client_id
        $this->clientId = get_option('blogger_client_id');
        
        // Get the Blogger's client_secret
        $this->clientSecret = get_option('blogger_client_secret');
        
        // Get the Blogger's api key
        $this->apiKey = get_option('blogger_api_key');
        
        // Get the Blogger's application name
        $this->appName = get_option('blogger_google_application_name');

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
        
        // Verify if the class Google_Client was already called
        if ( !class_exists('Google_Client', false ) ) {
            require_once FCPATH . 'vendor/google/src/Google_Client.php';
        }
        
        // Require Blog Services
        require_once FCPATH . 'vendor/google/src/contrib/Google_BloggerService.php';
        
        // Blogger Callback
        $this->scriptUri = site_url('user/callback/blogger');
        
    }

    /**
     * The public method check_availability checks if the Blogger api is configured correctly.
     *
     * @return boolean true or false
     */
    public function check_availability() {
        
        // Verify if clientId, clientSecret and apiKey exists
        if ( ($this->clientId != '') AND ( $this->clientSecret != '') AND ( $this->apiKey != '') ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }

    /**
     * The public method connect will redirect user to Google login page.
     * 
     * @return void
     */
    public function connect() {
        
        // Call the class Google_Client
        $this->client = new \Google_Client();
        
        // Offline because we need to get refresh token
        $this->client->setAccessType('offline');
        
        // Name of the google application
        $this->client->setApplicationName($this->appName);
        
        // Set the client_id
        $this->client->setClientId($this->clientId);
        
        // Set the client_secret
        $this->client->setClientSecret($this->clientSecret);
        
        // Redirects to same url
        $this->client->setRedirectUri($this->scriptUri);
        
        // Set the api key
        $this->client->setDeveloperKey($this->apiKey);
        
        // Load required scopes
        $this->client->setScopes(array('https://www.googleapis.com/auth/blogger https://www.googleapis.com/auth/userinfo.profile'));
        
        // Get the redirect url
        $authUrl = $this->client->createAuthUrl();
        
        // Redirect
        header('Location:' . $authUrl);
        
    }

    /**
     * The public method save will get access token.
     *
     * @param string $token contains the token for some social networks
     * 
     * @return boolean true or false
     */
    public function save($token = null) {
        
        // Define the callback status
        $check = 0;
        
        // Verify if code exists
        if ( $this->CI->input->get('code', TRUE) ) {
            
            // Call the class Google_Client
            $this->client = new \Google_Client();

            // Offline because we need to get refresh token
            $this->client->setAccessType('offline');

            // Name of the google application
            $this->client->setApplicationName($this->appName);

            // Set the client_id
            $this->client->setClientId($this->clientId);

            // Set the client_secret
            $this->client->setClientSecret($this->clientSecret);

            // Redirects to same url
            $this->client->setRedirectUri($this->scriptUri);

            // Set the api key
            $this->client->setDeveloperKey($this->apiKey);

            // Load required scopes
            $this->client->setScopes(array('https://www.googleapis.com/auth/blogger https://www.googleapis.com/auth/userinfo.profile'));            
            
            // Send the received code
            $this->client->authenticate( $this->CI->input->get('code', TRUE) );
            
            // Get access token
            $token = $this->client->getAccessToken();
            
            // Set access token
            $this->client->setAccessToken($token);
            
            // Decode response
            $token = json_decode($token);
            
            // Verify if token exists
            if ( !empty($token->access_token) ) {
                
                // Get refresh token
                $refresh = $token->refresh_token;
                
                // Get expiration time
                $expires_in = '';
                
                // Get access token
                $token = $token->access_token;
                
                // we will use the token to get user data
                $curl = curl_init();
                
                // Set some options - we are passing in a useragent too here
                curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => 'https://www.googleapis.com/oauth2/v3/userinfo?access_token=' . $token, CURLOPT_HEADER => false));
                
                // Send the request & save response to $resp
                $udata = curl_exec($curl);
                
                // Close request to clear up some resources
                curl_close($curl);
                
                // Veify if response is valid
                if ($udata) {
                    
                    // Decode response
                    $udecode = json_decode($udata);
                    if ( !empty($udecode->sub) ) {
                        
                        // Get blogs
                        $curl = curl_init();
                        
                        // Set some options - we are passing in a useragent too here
                        curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => 'https://www.googleapis.com/blogger/v3/users/self/blogs?fetchUserInfo=true&role=ADMIN&view=ADMIN&fields=blogUserInfos%2Citems&access_token=' . $token, CURLOPT_HEADER => false));
                        
                        // Send the request & save response to $resp
                        $blogs = curl_exec($curl);
                        
                        // Close request to clear up some resources
                        curl_close($curl);
                        
                        // Verify if user has blogs
                        if ( $blogs ) {
                            
                            // Decode response
                            $getBlogs = json_decode($blogs);
                            
                            // check and get first blog
                            if ( $refresh ) {
                                $expires_in = ' ';
                            }
                            
                            // If user has blogs will save them
                            if ( !empty($getBlogs->blogUserInfos[0]->blog->id) ) {
                                
                                // Get blog name
                                $name = $udecode->name;
                                
                                // Get blog's picture
                                $picture = $udecode->picture;
                                for ( $y = 0; $y < count($getBlogs->blogUserInfos); $y++ ) {
                                    
                                    // Verify if the blog already was saved
                                    if ( !$this->CI->networks->get_network_data('blogger', $this->CI->user_id, $getBlogs->blogUserInfos[$y]->blog->id) ) {
                                        
                                        // Save
                                        $this->CI->networks->add_network('blogger', $getBlogs->blogUserInfos[$y]->blog->id, $token, $this->CI->user_id, $expires_in, $getBlogs->blogUserInfos[$y]->blog->name, $picture, $refresh);
                                        
                                    }
                                }
                                
                                $check++;
                                
                            }
                            
                        } else {

                            // Display the error message
                            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_you_don_t_have_blogs') . '</p>', false);
                            exit();

                        }
                        
                    }
                    
                }
                
            }
            
        }
        
        if ( $check > 0 ) {
            
            // Display the success message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_all_blogger_blogs_were_added') . '</p>', true); 
            
        } else {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false);             
            
        }
        
    }

    /**
     * The public method post publishes posts on Blogger.
     *
     * @param array $args contains the post data.
     * @param integer $user_id is the ID of the current user
     * 
     * @return boolean true if post was published
     */
    public function post( $args, $user_id = null ) {
        
        // Get user details
        if ( $user_id ) {
            
            // Get network's data
            $con = $this->CI->networks->get_network_data('blogger', $user_id, $args['account']);
            
        } else {
            
            // Set user's id
            $user_id = $this->CI->user_id;

            // Get account details
            $con = $this->CI->networks->get_network_data('blogger', $user_id, $args['account']);
            
        }
        
        // Verify if user has the account
        if ( $con ) {
            
            if ( $con[0]->secret ) {
                
                // will be refreshed the token
                try {
                    
                    // Call the class Google_Client
                    $this->client = new \Google_Client();

                    // Offline because we need to get refresh token
                    $this->client->setAccessType('offline');

                    // Name of the google application
                    $this->client->setApplicationName($this->appName);

                    // Set the client_id
                    $this->client->setClientId($this->clientId);

                    // Set the client_secret
                    $this->client->setClientSecret($this->clientSecret);

                    // Redirects to same url
                    $this->client->setRedirectUri($this->scriptUri);

                    // Set the api key
                    $this->client->setDeveloperKey($this->apiKey);

                    // Load required scopes
                    $this->client->setScopes(array('https://www.googleapis.com/auth/blogger https://www.googleapis.com/auth/userinfo.profile'));

                    // Call the Blogger Service
                    $this->connect = new \Google_BloggerService($this->client);
                    
                    // Get refresh token
                    $this->client->refreshToken($con[0]->secret);
                    
                    // Get access token
                    $newtoken = $this->client->getAccessToken();
                    
                    // Set access token
                    $this->client->setAccessToken($newtoken);
                    
                    // Call the class Google_Post
                    $post = new \Google_Post();
                    
                    // Set content to publish
                    $content = $args['post'];
                    
                    // Verify if title exists
                    if ( $args ['title'] ) {
                        
                        // Set title
                        $post->setTitle( $args ['title'] );
                        
                        // Verify if url is empty
                        if ( $args['url'] ) {
                            $url = short_url($args['url']);
                            $content = str_replace($args['url'], ' ', $content);
                            $content = $content . '<br><p><a href="' . $url . '" target="_blank">' . $url . '</a></p>';
                        }
                        
                        // Set body
                        $post->setContent(nl2br($content) );
                        
                    } else {
                        
                        // Verify if url is empty
                        if ( $args['url'] ) {
                            $url = short_url($args['url']);
                            $content = str_replace($args['url'], ' ', $content);
                            
                            // Set body
                            $post->setContent(  '<br><p><a href="' . $url . '" target="_blank">' . $url . '</a></p>' );
                            
                        }
                        
                        // Set title
                        $post->setTitle( $content );
                        
                    }
                    
                    // Verify if category was selected
                    if ( $args['category'] ) {
                        
                        $category = json_decode($args['category'], true);

                        if ( !empty($category[$args['account']]) ) {

                            $post->setLabels([$category[$args['account']]]);
                            
                        }
                        
                    }
                    
                    // Publish
                    $data = $this->connect->posts->insert($con[0]->net_id, $post);
                    
                    if ( $data ) {
                        
                        return true;
                        
                    } else {
                        
                        return false;
                        
                    }
                    
                } catch (Exception $e) {
            
                    // Save the error
                    $this->CI->user_meta->update_user_option( $user_id, 'last-social-error', $e->getMessage() );

                    // Then return false
                    return false;
            
                }
                
            }
            
        }
        
    }

    /**
     * The public method get_info displays information about this class.
     * 
     * @return array with network data
     */
    public function get_info() {

        return array(
            'color' => '#fb8f3d',
            'icon' => '<i class="fa fa-bold" style="color:#ffffff"></i>',
            'api' => array('client_id', 'client_secret', 'api_key', 'google_application_name'),
            'types' => array('post', 'rss', 'categories')
        );
        
    }

    /**
     * The public method preview generates a preview for Telegram Groups.
     *
     * @param $args contains the img or url.
     * 
     * @return array with html content
     */
    public function preview($args) {}

}

/* End of file blogger.php */
