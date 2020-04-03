<?php
/**
 * Linkedin
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Linkedin
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

// If session valiable doesn't exists will be created
if (!isset($_SESSION)) {
    session_start();
}

// Define the namespaces to use
use MidrubBase\User\Interfaces as MidrubBaseUserInterfaces;

/**
 * Linkedin class - allows users to connect to their Linkedin Account and publish posts.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Linkedin implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    protected $CI, $redirect_uri, $client_id, $client_secret, $endpoint = 'https://www.linkedin.com/oauth/v2';

    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Get the Linkedin's client_id
        $this->client_id = get_option('linkedin_client_id');
        
        // Get the Linkedin's client_secret
        $this->client_secret = get_option('linkedin_client_secret');
        
        // Set redirect_url
        $this->redirect_uri = site_url('user/callback/linkedin');

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
        
        // Get account if exsts
        if ( $this->CI->input->get('account', TRUE) ) {
            
            // Verify if account is valid
            if ( is_numeric($this->CI->input->get('account', TRUE) ) ) {
                
                // Create the session account
                $_SESSION['account'] = $this->CI->input->get('account', TRUE);
                
            }
            
        }
        
    }

    /**
     * The public method check_availability checks if the Linkedin api is configured correctly.
     *
     * @return boolean true or false
     */
    public function check_availability() {
        
        // Verify if client_id or client_secret is empty
        if ( ($this->client_id != '') AND ( $this->client_secret != '') ) {
            
            return true;
            
        } else {
            
            return false;
            
        }

    }

    /**
     * The public method connect will redirect user to Linkedin login page.
     * 
     * @return void
     */
    public function connect() {
        
        // Set params
        $params = array(
            'response_type' => 'code',
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect_uri,
            'state' => time(),
            'scope' => 'w_share r_basicprofile r_liteprofile w_member_social'
        );
        
        // Get redirect url
        $url = $this->endpoint . '/authorization?' . http_build_query($params);
        
        // Redirect
        header('Location:' . $url);
            
    }

    /**
     * The public method save will get access token.
     *
     * @param string $token contains the token for some social networks
     * 
     * @return void
     */
    public function save( $token = null ) {
        
        // Verify if the code exists
        if ( $this->CI->input->get('code', TRUE) ) {
            
            // Set params
            $params = array(
                'grant_type' => 'authorization_code',
                'code' => $this->CI->input->get('code', TRUE),
                'redirect_uri' => $this->redirect_uri,
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret
            );
            
            // Get access token
            $response = json_decode(post($this->endpoint . '/accessToken', $params), true);

            // Verify if token exists
            if ( isset($response['access_token']) ) {
                
                // Get linkedin profile
                $profile = json_decode(get('https://api.linkedin.com/v2/me?oauth2_access_token=' . $response['access_token']), true);
                
                // Verify if data exists
                if ( isset($profile['firstName']['preferredLocale']['country']) ) {
                    
                    // Get first and last name
                    $name = $profile['firstName']['localized'][$profile['firstName']['preferredLocale']['language'] . '_' . $profile['firstName']['preferredLocale']['country']] . ' ' . $profile['lastName']['localized'][$profile['lastName']['preferredLocale']['language'] . '_' . $profile['lastName']['preferredLocale']['country']];
                    
                    // Get profile id
                    $id = $profile['id'];
                    
                    // Get exiration time
                    $expires = date('Y-m-d H:i:s', time() + $response['expires_in']);
                    
                    // Verify if we have to renew account
                    if ( isset($_SESSION['account']) ) {
                        
                        $acc = 0;
                        $act = $_SESSION['account'];
                        unset($_SESSION['account']);
                        
                        // Verify if account is valid
                        if ( !is_numeric($act) ) {
                            
                            exit();
                            
                        } else {
                            
                            // Get account's data
                            $gat = $this->CI->networks->get_account($act);
                            
                            if ($gat) {
                                
                                $acc = $gat[0]->net_id;
                                
                            }
                            
                        }
                        
                        // Verify if user is logged in correct account
                        if ( $id == $acc ) {
                            
                            // Refresh the token
                            if ($this->CI->networks->update_network($act, $this->CI->user_id, date('Y-m-d H:i:s', strtotime('+60 days')), $response['access_token'])) {
                                
                                // Display the success message
                                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_access_token_was_renewed') . '</p>', true);
                                
                            } else {
                                
                                // Display the error message
                                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_access_token_was_not_renewed') . '</p>', false); 
                                
                            }
                        } else {

                            // Display the error message
                            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_seams_you_are_logged_different_account') . '</p>', false);
                                
                        }
                        
                        exit();
                        
                    }
                    
                    // Verify if account was already saved
                    if ( !$this->CI->networks->get_network_data('linkedin', $this->CI->user_id, $id) ) {
                        
                        // Try to save the account
                        if ( $this->CI->networks->add_network('linkedin', $id, $response['access_token'], $this->CI->user_id, $expires, $name, '', '') ) {

                            // Display the success message
                            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_your_account_was_connected') . '</p>', true);

                        }
                        
                    } else {
                        
                        // Display the error message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_your_account_already_added') . ' Linkedin ' . $this->CI->lang->line('networks_change_your_account') . '</p>', false); 
                        
                    }
                    
                    exit();
                    
                }
                
            }
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_your_account_already_added') . ' Linkedin ' . $this->CI->lang->line('networks_change_your_account') . '</p>', false); 
            
        }
        
    }

    /**
     * The public method post publishes posts on Linkedin.
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
            $user_details = $this->CI->networks->get_network_data('linkedin', $user_id, $args['account']);
            
        } else {
            
            // Set user's ID
            $user_id = $this->CI->user_id;

            // Get account details
            $user_details = $this->CI->networks->get_network_data('linkedin', $user_id, $args['account']);
            
        }
        
        // Get the post
        $post = $args['post'];
        
        // Verify if url exists
        if ( $args['url'] ) {
            $post = str_replace($args['url'], ' ', $post);
        }       
        
        $new_post = mb_substr($post, 0, 699);
        
        try {
            
            // Set params
            $params = array(
                'distribution' => array(
                    'linkedInDistributionTarget' => array(
                        'visibleToGuest' => true
                    ),
                ),
                'owner' => 'urn:li:person:' . $user_details[0]->net_id,
                'text' =>
                array(
                    'text' => $post,
                ),
            );
            
            if ( $args['url'] ) {
                
                $params['content'] = array( 
                    
                    'contentEntities' => array(
                        
                        array(
                            'entityLocation' => $args['url']
                        )
                        
                    )
                    
                );
                
                if ( isset($args['img'][0]['body']) ) {
                    $params['content']['contentEntities'][0]['thumbnails'][0]['resolvedUrl'] = $args['img'][0]['body'];
                }
                
                if ( $args['title'] ) {
                    $params['content']['title'] = $args['title'];
                }
                
            } else if ( isset($args['img'][0]['body']) ) {
                
                $params['content'] = array( 
                    'contentEntities' => array(
                        array(
                            'entityLocation' => $args['img'][0]['body'],
                            'thumbnails' => array(
                                array(
                                    'resolvedUrl' => $args['img'][0]['body'],
                                )
                            )
                        )
                    )
                );
                
                if ( $args['title'] ) {
                    $params['content']['title'] = $args['title'];
                }                
                
            } else if ( $args['title'] ) {
                $params['subject'] = $args['title'];
            }

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HEADER, 1);
            curl_setopt($curl, CURLOPT_URL, 'https://api.linkedin.com/v2/shares');
            
            $headers = array(
                'Authorization: Bearer ' . $user_details[0]->token,
                'Cache-Control: no-cache',
                'X-RestLi-Protocol-Version: 2.0.0',
                'x-li-format: json',
                'Content-Type: application/json'
            );
            
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            
            $response = curl_exec($curl);
            
            curl_close($curl);

            if ( preg_match('/x-restli-id/i', $response) ) {

                return true;

            } else {
                
                // Save the error
                $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($response));

                // Then return falsed
                return false;
                
            }
            
        } catch (Exception $e) {

            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', $e->getMessage());

            // Then return falsed
            return false;
            
        }
    }

    /**
     * The public method get_info displays information about this class.
     * 
     * @return array with network's data
     */
    public function get_info() {
        
        return array(
            'color' => '#eddb11',
            'icon' => '<i class="fab fa-linkedin"></i>',
            'preview_icon' => '<i class="icon-screen-desktop"></i>',
            'api' => array('client_id', 'client_secret'),
            'types' => array('post', 'rss', 'preview')
        );
        
    }

    /**
     * This function generates a preview for Linkedin.
     *
     * @param array $args contains the img or url.
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
                                    . $this->CI->lang->line('networks_posts_learn_more')
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
                                . $this->CI->lang->line('networks_posts_learn_more')
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
                                            . $this->CI->lang->line('networks_now')
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

/* End of file linkedin.php */
