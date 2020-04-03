<?php
/**
 * Facebook Pages
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Facebook Pages
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
 * Facebook_pages class - allows users to connect to their Facebook Pages and publish posts.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Facebook_pages implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    public $CI, $fb, $app_id, $app_secret;

    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Get the Facebook App ID
        $this->app_id = get_option('facebook_pages_app_id');
        
        // Get the Facebook App Secret
        $this->app_secret = get_option('facebook_pages_app_secret');

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
        
        // Verify if app_id and app_secret exists
        if ( ($this->app_id != '') AND ( $this->app_secret != '') ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }

    /**
     * The public method connect will redirect user to facebook login page.
     * 
     * @return void
     */
    public function connect() {
        
        // Redirect use to the login page
        $helper = $this->fb->getRedirectLoginHelper();
                    
        // Permissions to request
        $permissions = array(
            'manage_pages',
            'publish_pages'
        );
        
        if ( get_option( 'app_posts_enable_insights' ) ) {
            $permissions[] = 'read_insights';
        };
        
        if ( get_option( 'app_inbox_enable' ) ) {
            $permissions[] = 'pages_messaging';
        };        
        
        // Get redirect url
        $loginUrl = $helper->getLoginUrl(site_url('user/callback/facebook_pages'), $permissions);
        
        // Redirect
        header('Location:' . $loginUrl);

    }

    /**
     * The public method save will get access token.
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
        $access_token = $helper->getAccessToken(site_url('user/callback/facebook_pages'));
        
        // Convert it to array
        $access_token = (array) $access_token;
        
        // Get array value
        $access_token = array_values($access_token);

        // Verify if access token exists
        if ( isset($access_token[0]) ) {

            // Get user data
            $response = json_decode(get('https://graph.facebook.com/me/accounts?limit=500&access_token=' . $access_token[0]), true);
            
            // Verify if user has pages
            if ( isset($response['data'][0]['id']) ) {
                
                // Calculate expire token period
                $expires = '';
                
                // Save page
                for ( $y = 0; $y < count($response['data']); $y++ ) {
                    
                    // Save page
                    if ( $this->CI->networks->add_network('facebook_pages', $response['data'][$y]['id'], $access_token[0], $this->CI->user_id, $expires, $response['data'][$y]['name'], '', $response['data'][$y]['access_token']) ) {
                        $check++;
                    }
                    
                }
                
            } else {

                // Display the error message
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_your_account_don_has_pages') . '</p>', false);
                exit();
                
            }

        }
        
        if ( $check > 0 ) {
            
            // Display the success message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_all_facebook_pages_added') . '</p>', true); 
            
        } else {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false);             
            
        }
        
    }

    /**
     * The public method post publishes posts on Facebook Pages
     *
     * @param array $args contains the post data
     * @param integer $user_id is the ID of the current user
     * 
     * @return boolean true if post was published
     */
    public function post($args, $user_id = null) {
        // die('pages');
        // Get user details
        if ($user_id) {
            
            // Get account details
            $user_details = $this->CI->networks->get_network_data('facebook_pages', $user_id, $args['account']);
            
        } else {
            
            // Set user's ID
            $user_id = $this->CI->user_id;

            // Get account details
            $user_details = $this->CI->networks->get_network_data('facebook_pages', $user_id, $args['account']);
            
        }
        
        try {
                
            $token = $user_details[0]->secret;

            // Get post content 
            $post = $args['post'];

            // Verify if the title is not empty
            if ( $args['title'] ) {

                $post = $args['title'] . ' ' . $post;

            }

            // Verify if token exists
            if ( $token ) {

                // Set access token
                $this->fb->setDefaultAccessToken($token);

                // Verify if image exists
                if ( $args['img'] ) {

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
                            $status = $this->fb->post('/' . $user_details[0]->net_id . '/photos', array('url' => $img['body'], 'published' => FALSE), $token);
                            
                            if ( @$status->getDecodedBody() ) {
                                
                                $stat = $status->getDecodedBody();
                                
                                $photos['attached_media[' . $e . ']'] = '{"media_fbid":"' . $stat['id'] . '"}';
                                $e++;
                                
                            }
                            
                        }
                        
    
    
                        // Decode the response
                        if ( $photos ) {
    
                            $post = $this->fb->post('/' . $user_details[0]->net_id . '/feed', $photos, $token);
    
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
                    $post = $this->fb->post('/' . $user_details[0]->net_id . '/feed', $linkData, $token);

                } else {

                    // Create post content
                    $linkData = array('message' => $post);

                    // Create post content
                    $post = $this->fb->post('/' . $user_details[0]->net_id . '/feed', $linkData, $token);

                }

                // Decode the post response
                if ($post->getDecodedBody()) {

                    $mo = $post->getDecodedBody();

                    return $mo['id'];

                } else {
                    
                    // Save the error
                    $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', json_encode($mo) );

                    return false;

                }

            } else {
                
                // Save the error
                $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', 'No valid token.' );

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
        
        return array(
            'color' => '#4065b3',
            'icon' => '<i class="fab fa-facebook"></i>',
            'preview_icon' => '<i class="icon-screen-desktop"></i>',
            'api' => array('app_id', 'app_secret'),
            'types' => array('post', 'insights', 'rss', 'inbox', 'preview'),
        );
        
    }

    /**
     * The public method preview generates a preview for facebook pages
     *
     * @param $args contains the post's data
     * 
     * @return array with html content
     */
    public function preview($args) {

        // Default body value
        $body = '';

        // Default media value
        $media = '';

        // Default url value
        $url = '';

        if ( $args ) {

            if ( isset($args['body']) ) {

                $body = '<tr>'
                            . '<td colspan="2" class="post-preview-body" style="padding: 0 15px 15px; font-style: normal; font-weight: normal; line-height: normal; font-size: 13px; color: rgb(20, 24, 35);">'
                                . $args['body']
                            . '</td>'  
                        . '</tr>';                

            }

            if ( isset($args['medias']) ) {

                foreach ( $args['medias'] as $medi ) {
                                
                    if ( $medi['type'] === 'image' ) {

                        $media .= '<tr>'                                         
                                    . '<td colspan="2">'
                                        . '<div data-id="' . $medi['id'] . '" data-type="' . $medi['type'] . '">'
                                            . '<img src="' . $medi['url'] . '" style="width: 100%; height: 269px;">'
                                            . '<a href="#" class="btn-delete-post-media" style="position: absolute; right: 20px; margin-top: 5px; font-size: 20px; border: 0 !important;">'
                                                . '<i class="icon-close" style="background-color: #343a40; color: #FFFFFF; border-radius: 50%;"></i>'
                                            . '</a>'
                                        . '</div>'
                                    . '</td>'
                                . '</tr>';

                    } else {

                        $media .= '<tr>'                                         
                                    . '<td colspan="2">'
                                        . '<div data-id="' . $medi['id'] . '" data-type="' . $medi['type'] . '">'
                                            . '<video style="width: 100%; height: 269px;" controls="">'
                                                . '<source src="' . $medi['url'] . '" type="video/mp4">'
                                            . '</video>'
                                            . '<a href="#" class="btn-delete-post-media" style="position: absolute; right: 20px; margin-top: 5px; font-size: 20px; border: 0 !important;">'
                                                . '<i class="icon-close" style="background-color: #343a40; color: #FFFFFF; border-radius: 50%;"></i>'
                                            . '</a>'
                                        . '</div>'
                                    . '</td>'
                                . '</tr>';

                    }

                }               

            }

            if ( isset($args['link']) ) {

                if ( isset($args['link']['img']) ) {

                    $url .= '<tr>'
                                . '<td colspan="2">'
                                    . '<img src="' . $args['link']['img'] . '" style="width: 100%;">'                                
                                    . '<a href="#" class="btn-delete-post-url" class="btn-delete-post-media" style="position: absolute; right: 35px; margin-top: 15px; font-size: 20px; border: 0 !important;">'
                                        . '<i class="icon-close" style="background-color: #343a40; color: #FFFFFF; border-radius: 50%;"></i>'
                                    . '</a>'
                                . '</td>'
                            . '</tr>';

                    $url .= '<tr style="background-color: #f2f3f5;">'
                            . '<td style="padding: 0; position: relative;">'
                                . '<p style="font-size: 12px; line-height: 16px; padding: 15px 15px 0; height: 30px; color: #979ba7; text-transform: uppercase; max-width: 90%; width: 300px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">'
                                    . $args['link']['url']
                                . '</p>'
                                . '<h3 style="margin-bottom: 0; font-weight: 700; font-size: 15px; padding: 10px 15px 15px; color: #000000;">'
                                    . $args['link']['title']
                                . '</h3>'
                            . '</td>'
                            . '<td style="padding: 0; position: relative;">'
                                . '<button type="button" class="btn btn-primary" style="background-color: #eff1f3; border-radius: 0; border-color: #bec3c9; color: #333333; margin-right: 15px; padding: 5px 15px; font-weight: 600; font-size: 12px;">'
                                    . $this->CI->lang->line('networks_learn_more')
                                . '</button>'
                            . '</td>'
                        . '</tr>';

                } else {

                    $url .= '<tr style="background-color: #f2f3f5;">'
                        . '<td style="padding: 0; position: relative;">'
                            . '<p style="font-size: 12px; line-height: 16px; padding: 15px 15px 0; height: 30px; color: #979ba7; text-transform: uppercase; max-width: 90%; width: 300px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">'
                                . $args['link']['url']
                            . '</p>'
                            . '<h3 style="margin-bottom: 0; font-weight: 700; font-size: 15px; padding: 10px 15px 15px; color: #000000;">'
                                . $args['link']['title']
                            . '</h3>'
                        . '</td>'
                        . '<td style="padding: 0; position: relative;">'
                            . '<button type="button" class="btn btn-primary" style="background-color: #eff1f3; border-radius: 0; border-color: #bec3c9; color: #333333; margin-right: 15px; padding: 5px 15px; font-weight: 600; font-size: 12px;">'
                                . $this->CI->lang->line('networks_learn_more')
                            . '</button>'
                            . '<a href="#" class="btn-delete-post-url" style="position: absolute; top: 5px; right: 5px; font-size: 20px; border: 0 !important;">'
                                . '<i class="icon-close" style="background-color: #343a40; color: #FFFFFF; border-radius: 50%;"></i>'
                            . '</a>'
                        . '</td>'
                    . '</tr>';

                }

            }

        }

        return array(
            'body' => '<table style="width: calc(100% + 30px); box-shadow: rgba(0, 0, 0, 0.06) 0px 7px 8px; margin-left: -15px; margin-bottom: 30px;">'
                        . '<thead>'
                            . '<tr>'
                                . '<th colspan="3" style="padding: 0 15px 9px;">'
                                    . '<img src="' . base_url('assets/img/avatar-placeholder.png') . '" style="width: 40px; border-radius: 50%; float: left; margin-top: 15px; margin-right: 15px;">'
                                    . '<h3 style="font-size: 16px;padding-top: 14px;float: left;">'
                                        . '<a href="#" style="display: block; font-style: normal; pointer-events: none; font-weight: bold; line-height: normal; font-size: 14px; color: rgb(20, 24, 35);">'
                                            . $this->CI->lang->line('networks_your_page_name')
                                        . '</a>'
                                        . '<span style="font-size: 12px; color: rgb(97, 103, 112);">'
                                            . $this->CI->lang->line('networks_now') . '&nbsp;&nbsp;-&nbsp;&nbsp;<i class="fas fa-globe-americas"></i>'
                                        . '</span>'
                                    . '</h3>'
                                . '</th>'
                            . '</tr>'
                        . '</thead>'
                        . '<tbody>'
                            . '<tr>'
                                . '<td colspan="3" class="clean">'
                                    . '<table class="full" style="width: 100%;">'
                                        . '<tbody>'
                                            . $body
                                            . $media
                                            . $url
                                        . '</tbody>'
                                    . '</table>'
                                . '</td>'
                            . '</tr>'
                        . '</tbody>'
                        . '<tfoot style="border-top: 1px solid #e5e5e5;">'
                            . '<tr>'
                                . '<td style="text-align: center; color: #606770; flex: 1 0; height: 40px; line-height: 40px; padding: 0 2px; text-decoration: none;">'
                                    . '<i class="far fa-thumbs-up"></i> ' . $this->CI->lang->line('networks_like')
                                . '</td>'
                                . '<td style="text-align: center; color: #606770; flex: 1 0; height: 40px; line-height: 40px; padding: 0 2px; text-decoration: none;">'
                                    . '<i class="far fa-comment-alt"></i> ' . $this->CI->lang->line('networks_comment')
                                . '</td>'
                                . '<td style="text-align: center; color: #606770; flex: 1 0; height: 40px; line-height: 40px; padding: 0 2px; text-decoration: none;">'
                                    . '<i class="fas fa-share"></i> ' . $this->CI->lang->line('networks_share')
                                . '</td>'
                            . '</tr>'
                        . '</tfoot>'
                    . '</table>'

        );

    }

}

/* End of file facebook_pages.php */
