<?php
/**
 * Linkedin Companies
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Linkedin Companies
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
 * Linkedin_companies class - allows users to connect Linkedin Companies and publish posts.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Linkedin_companies implements MidrubBaseUserInterfaces\Networks {

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
        
        // Get Linkedin client_id
        $this->client_id = get_option('linkedin_companies_client_id');
        
        // Get Linkedin client_secret
        $this->client_secret = get_option('linkedin_companies_client_secret');
        
        // Set redirect_url
        $this->redirect_uri = site_url('user/callback/linkedin_companies');

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
        
        // Get account if exsts
        if( $this->CI->input->get('account', TRUE) ) {
            
            // Verify if account is valid
            if( is_numeric($this->CI->input->get('account', TRUE)) ) {
                
                $_SESSION['account'] = $this->CI->input->get('account', TRUE);
                
            }
            
        }
        
    }
    
    /**
     * The public method check_availability checks if the Linkedin Companies api is configured correctly.
     *
     * @return boolean true or false
     */
    public function check_availability() {
        
        if ( ( $this->client_id != '' ) AND ( $this->client_secret != '' ) ) {
            
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
            'scope' => 'w_share r_basicprofile r_liteprofile rw_organization_admin r_organization_social w_organization_social'
        );
        
        // Get redirect url
        $url = $this->endpoint . '/authorization?' . http_build_query($params);
        
        // Redirect
        header('Location:' . $url);

    }

    /**
     * The public method save will get access token.
     *
     * @param void
     */
    public function save($token = null) {

        // Verify if the code exists
        if ($this->CI->input->get('code', TRUE)) {

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

                // Get exiration time
                $expires = date('Y-m-d H:i:s', time() + $response['expires_in']);

                // Get linkedin profile
                $profile = json_decode(get('https://api.linkedin.com/v2/me?oauth2_access_token=' . $response['access_token']), true);

                if ($profile) {

                    // Get linkedin organizations
                    $organizations = json_decode(get('https://api.linkedin.com/v2/organizationalEntityAcls?q=roleAssignee&role=ADMINISTRATOR&state=APPROVED&projection=(*,elements*(*,organizationalTarget~(*)))&oauth2_access_token=' . $response['access_token']), true);

                    if ( isset($organizations['elements']) ) {

                        // Verify if must be refreshed a token
                        if ( isset( $_SESSION['account'] ) ) {

                            $acc = 0;
                            $act = $_SESSION['account'];
                            unset($_SESSION['account']);

                            if (!is_numeric($act)) {

                                exit();

                            } else {

                                // Get user social account
                                $gat = $this->CI->networks->get_account($act);

                                if ($gat) {
                                    $acc = $gat[0]->net_id;
                                }

                            }

                            // Connect pages or refresh tokens
                            $j = 0;
                            $b1 = 0;
                            foreach ($organizations['elements'] as $element) {

                                if ("urn:li:organization:" . $element['organizationalTarget~']['id'] == $acc) {
                                    $j++;
                                }

                                $acci = 0;
                                $gat = $this->CI->networks->get_account_field($this->CI->user_id, "urn:li:organization:" . $element['organizationalTarget~']['id'], 'network_id');

                                if ($gat) {

                                    $acci = $gat;
                                }

                                if ($acci) {

                                    if ($this->CI->networks->update_network($acci, $this->CI->user_id, date('Y-m-d H:i:s', strtotime('+60 days')), $response['access_token'])) {

                                        $b1++;
                                    }

                                } else {

                                    if ($this->CI->networks->add_network('linkedin_companies', "urn:li:organization:" . $element['organizationalTarget~']['id'], $response['access_token'], $this->CI->user_id, $expires, $element['organizationalTarget~']['localizedName'], '', '')) {

                                        $b1++;
                                    }

                                }

                            }

                            if ($j > 0) {

                                if ($b1 > 0) {

                                    // Display the success message
                                    echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_access_token_was_renewed') . '</p>', true);
                                    exit();

                                } else {

                                    // Display the error message
                                    echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_access_token_was_not_renewed') . '</p>', false);
                                    exit();
                                }
                            } else {

                                // Display the error message
                                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_seams_you_are_logged_different_account') . '</p>', false);
                                exit();

                            }

                        }

                        foreach ($organizations['elements'] as $element) {

                            $this->CI->networks->add_network('linkedin_companies', "urn:li:organization:" . $element['organizationalTarget~']['id'], $response['access_token'], $this->CI->user_id, $expires, $element['organizationalTarget~']['localizedName'], '', '');

                        }

                        // Display the success message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_all_linkedin_companies_connected') . '</p>', true);
                        exit();

                    }

                } else {

                    // Display the error message
                    echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_you_have_no_companies') . '</p>', false);
                    exit();

                }

            }

        }
        
        // Display the error message
        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false); 
        
    }
    
    /**
     * The public method publishes posts on Linkedin Companies.
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
            $user_details = $this->CI->networks->get_network_data(strtolower('linkedin_companies'), $user_id, $args['account']);
            
        } else {
            
            // Set user's ID
            $user_id = $this->CI->user_id;

            // Get account details
            $user_details = $this->CI->networks->get_network_data(strtolower('linkedin_companies'), $user_id, $args['account']);
            
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
                'owner' => $user_details[0]->net_id,
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
     * @return array with network details
     */
    public function get_info() {
        
        return array(
            'color' => '#eddb11',
            'icon' => '<i class="fab fa-linkedin"></i>',
            'api' => array('client_id', 'client_secret'),
            'types' => array('post', 'rss')
        );
        
    }
    
    /**
     * The public method preview generates a preview for Linkedin Companies.
     *
     * @param array $args contains the img or url.
     * 
     * @return array with html content
     */
    public function preview($args) {
    }
    
}

/* End of file linkedin_companies.php */
