<?php
/**
 * Instagram Api
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Instagram
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

// If session valiable doesn't exists will be created
if (!isset($_SESSION)) {
    session_start();
}

/**
 * Instagram class - allows users to connect to their Instagram account and publish posts by using Buffer api.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Instagram_api implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    protected $CI, $check, $role, $redirect_uri, $client_id, $client_secret;
    
    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Get Buffer client_id
        $this->client_id = get_option('instagram_api_client_id');
        
        // Get Buffer client_secret
        $this->client_secret = get_option('instagram_api_client_secret');
        
        // Get Buffer redirect
        $this->redirect_uri = site_url('admin/user?p=networks&network=instagram_api');

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
        
        // Verify if the social network is enabled
        if ( get_option( 'instagram_api' ) ) {
            
            // Load user_networks SQL Queries
            $table = $this->CI->db->table_exists('users_networks');

            // Verify if table exists
            if ( !$table ) {
                
                // Create user_networks table
                $this->CI->db->query( 'CREATE TABLE `users_networks` (`net_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,`user_id` bigint(20) NOT NULL,`type` varchar(100) NOT NULL,`email` varchar(255) NOT NULL,`password` varchar(255) NOT NULL,`network_id` bigint(20) NOT NULL,`status` tinyint(1) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;' );
                
            }
            
        }
    }
    
    /**
     * The public method check_availability checks if the Buffer api is configured correctly.
     *
     * @return boolean true or false
     */
    public function check_availability() {
        
        // Verify if client_id and client_secret is not empty
        if ( ($this->client_id != '') AND ( $this->client_secret != '') ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method connect will redirect user to Buffer login page.
     *
     * @return void
     */
    public function connect() {
        
        // Get user role
        $this->role = $this->CI->user->check_role_by_username($this->CI->session->userdata['username']);
        
        // Verify if user is admin
        if ( !$this->role ) {
            
            // Check if data was submitted
            if ( $this->CI->input->post() ) {
                
                // Add form validation
                $this->CI->form_validation->set_rules('username', 'Username', 'trim|required');
                $this->CI->form_validation->set_rules('password', 'Password', 'trim|required');
                
                // Get post data
                $username = $this->CI->input->post('username');
                $password = $this->CI->input->post('password');
                
                // Verify if post data is correct
                if ( $this->CI->form_validation->run() == false ) {
                    
                    // Return error message
                    echo $this->CI->lang->line('networks_please_enter_username_password');
                    
                } else {
                    
                    $this->CI->db->select( '*' );
                    $this->CI->db->from( 'users_networks' );
                    $this->CI->db->where(['type' => 'instagram_api', 'email' => $username]);
                    $query = $this->CI->db->get();

                    if ( $query->num_rows() > 0 ) {
                        
                        // Display the error message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_go_back') . '</p>', false);
                        exit();
                        
                    } else {
                        
                        $secret_key = 'trydtufyi';
                        $secret_iv = 'rytuyiguoihdfg';
                        
                        $output = false;
                        $encrypt_method = "AES-256-CBC";
                        $key = hash( 'sha256', $secret_key );
                        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
                        
                        $output = base64_encode( openssl_encrypt( $password, $encrypt_method, $key, 0, $iv ) );
                        
                        // Set data
                        $data = ['user_id' => $this->CI->user_id, 'type' => 'instagram_api', 'email' => $username, 'password' => $output];
                        
                        // Insert
                        $this->CI->db->insert( 'users_networks', $data );
                        
                        if ( $this->CI->db->affected_rows() ) {
                            
                            // Display the success message
                            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' .  $this->CI->lang->line('networks_your_account_was_submitted') . '</p>', true);
                            exit();
                            
                        } else {
                            
                            // Display the error message
                            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_your_account_was_not_submitted') . '</p>', false);
                            exit();
                            
                        }
                        
                    }
                    
                }
                
            }
            
            // If doesn't exists will be displayed the form
            echo $this->CI->ecl('Social_login')->content('Username', 'Password', 'Connect', $this->get_info(), 'instagram_api', $this->CI->lang->line('networks_please_enter_username_password'));
            
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
     * The public method post publishes posts on Instagram
     *
     * @param array $args contains the post data.
     * @param integer $user_id is the ID of the current user
     *
     * @return boolean true or false
     */
    public function post($args, $user_id = null) {
        
        // Verify if user_id exists
        if ( $user_id ) {
            
            // Get account details
            $con = $this->CI->networks->get_network_data('instagram_api', $user_id, $args ['account']);
            
        } else {
            
            // Set user's ID
            $user_id = $this->CI->user_id;

            // Get account details
            $con = $this->CI->networks->get_network_data('instagram_api', $user_id, $args ['account']);
            
        }
        
        // Verify if image exists
        if ( !$args['img'] ) {
            
            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($this->CI->lang->line('networks_a_photo_is_required_to_publish_here')));
            
            // Then return false
            return false;
            
        }
        
        // Verify if account exists
        if ( $con ) {
            
            // Verify if token exists
            if ( $con [0]->token ) {
                
                try {
                    
                    // Get post value
                    $post = $args['post'];
                    
                    // Verify if title is not empty
                    if ( $args['title'] ) {
                        
                        $post = $args['title'] . ' ' . $post;
                        
                    }
                    
                    // Publish
                    $params = array(
                        'text' => $post,
                        'profile_ids[]' => $con[0]->net_id,
                        'pretty' => true,
                        'now' => true,
                        'access_token' => $con[0]->token
                    );
                    
                    // Verify if url exists
                    if ( $args['url'] ) {
                        
                        $params['media'] = array(
                            'link' => short_url($args['url'])
                        );
                        
                    }
                    
                    // Verify if image exists
                    if ( isset($args['img'][0]['body']) ) {
                        
                        $params['media'] = array(
                            'photo' => $args['img'][0]['body']
                        );
                        
                    }
                    
                    $curl = curl_init('https://api.bufferapp.com/1/updates/create.json');
                    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
                    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)');
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
                    $status = curl_exec($curl);
                    curl_close($curl);
                    
                    $check = json_decode($status);
                    
                    // Verify if the post was published
                    if ( $check->success ) {
                        
                        // The post was published
                        return true;
                        
                    } else {
                        
                        // Save the error
                        $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', $status );
                        
                    }
                    
                } catch (Exception $e) {
                    
                    // Save the error
                    $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', $e->getMessage());
                    
                    // Then return false
                    return false;
                    
                }
                
            }
            
        }
        
    }
    
    /**
     * The public method get_info displays information about this class
     *
     * @return array with network's data
     */
    public function get_info() {
        
        if ( isset($this->CI->session->userdata['username']) ) {
            
            // Get user role
            $this->role = $this->CI->user->check_role_by_username($this->CI->session->userdata['username']);
            
        } else {
            
            $this->role = 0;
            
        }

        if ( $this->CI->input->get( 'net_id', TRUE ) && ( $this->CI->input->get( 'network', TRUE ) === 'instagram_api' ) ) {

            // Set session net_id
            $_SESSION['net_id'] = $this->CI->input->get( 'net_id', TRUE );
            
            // Set params values
            $this->params = array(
                'client_id' => $this->client_id,
                'response_type' => 'code',
                'redirect_uri' => urlencode(site_url('admin/user?p=networks&network=instagram_api')),
            );
            
            // Generate redirect
            $loginUrl = 'https://bufferapp.com/oauth2/authorize?' . urldecode(http_build_query($this->params));
            
            echo '<script language="javascript">document.location.href="' . $loginUrl . '";</script>';

            exit();

        }

        // Verify if the code exists
        if ( $this->CI->input->get('code', TRUE) ) {
            
            $params = array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'code' => $this->CI->input->get('code', TRUE),
            'redirect_uri' => $this->redirect_uri,
            'grant_type' => 'authorization_code'
            );
            $curl = curl_init( 'https://api.bufferapp.com/1/oauth2/token.json' );
            curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, 30 );
            curl_setopt( $curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)' );
            curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, 1 );
            curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query($params) );
            $resp = curl_exec( $curl );
            curl_close( $curl );
            $token = (array)json_decode($resp);
            
            // Verify if the token is valid
            if ( isset($token['access_token']) ) {
                
                $params = array(
                    'access_token' => $token['access_token']
                );
                
                // Get cURL resource
                $curl = curl_init();
                
                // Set some options - we are passing in a useragent too here
                curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => 'https://api.bufferapp.com/1/profiles.json'.'?'.urldecode(http_build_query($params)),CURLOPT_HEADER => false));
                
                // Send the request & save response to $resp
                $profiles = curl_exec($curl);
                
                // Close request to clear up some resources
                curl_close($curl);
                
                
                if ( $profiles && isset($_SESSION['net_id']) ) {

                    // Load Networks Model
                    $this->CI->load->model('networks');
                    
                    $user_id = 0;
                    
                    // Get Instagram's user
                    $this->CI->db->select( '*' );
                    $this->CI->db->from( 'users_networks' );
                    $this->CI->db->where( [ 'net_id' => $_SESSION['net_id'] ] );
                    $query = $this->CI->db->get();
                    if ( $query->num_rows() > 0 ) {
                        
                        $result = $query->result();
                        $user_id = $result[0]->user_id;
                        
                    }
                    
                    // Decode Response
                    $profiles = (array)json_decode($profiles);
                    
                    foreach ( $profiles as $profile ) {
                        
                        if ( $profile->formatted_service == 'Instagram Business' && $user_id > 0 ) {
                            
                            
                            // Verify if account was already saved
                            if ( !$this->CI->networks->check_account_was_added( 'instagram_api', $profile->_id, $user_id ) ) {
                                
                                $network_id = $this->CI->networks->add_network( 'instagram_api', $profile->_id, $token['access_token'], $user_id, '', $profile->formatted_username, $profile->avatar );
                                
                                if ( $network_id ) {
                                    
                                    $this->CI->db->set([ 'network_id' => $network_id, 'status' => '1' ]);
                                    $this->CI->db->where( [ 'net_id' => $_SESSION['net_id'] ] );
                                    $this->CI->db->update( 'users_networks' );
                                    
                                }
                                
                            }
                            
                        }
                        
                    }

                    unset($_SESSION['net_id']);
                    
                }
                
            }
            
        }
        
        if ( $this->role > 0 && $this->CI->input->get('delete', TRUE) ) {
            
            $delete = $this->CI->input->get('delete', TRUE);
            
            // Get Instagram's user
            $this->CI->db->select( '*' );
            $this->CI->db->from( 'users_networks' );
            $this->CI->db->where( [ 'net_id' => $delete ] );
            $query = $this->CI->db->get();
            if ( $query->num_rows() > 0 ) {
                
                $result = $query->result();
                $network_id = $result[0]->network_id;
                $this->CI->db->delete( 'networks', [ 'network_id' => $network_id ] );
                
            }
            
            // Delete account
            $this->CI->db->delete( 'users_networks', [ 'net_id' => $delete ] );
            
        }
        
        $users = '<tr><td colspan="5">No users found.</td></tr>';
        
        $pages = '';
        
        if ( ($this->role > 0) && ($this->CI->db->table_exists('users_networks') == true ) ) {
            
            if ( $this->CI->input->get('page', TRUE) ) {
                
                $page = $this->CI->input->get('page', TRUE);
                $page--;
                $page = $page * 10;
                
            } else {
                
                $page = 0;
                
            }
            
            // Get Instagram's users
            $this->CI->db->select( '*' );
            $this->CI->db->from( 'users_networks' );
            $this->CI->db->where( ['type' => 'instagram_api'] );
            $this->CI->db->order_by( 'net_id', 'desc' );
            $this->CI->db->limit(10, $page);
            $query = $this->CI->db->get();
            if ( $query->num_rows() > 0 ) {
                $results = $query->result();
                $page = $page / 10 + 1;
                $users = '';
                foreach ( $results as $result ) {
                    
                    $secret_key = 'trydtufyi';
                    $secret_iv = 'rytuyiguoihdfg';
                    
                    $output = false;
                    $encrypt_method = "AES-256-CBC";
                    $key = hash( 'sha256', $secret_key );
                    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
                    $output = openssl_decrypt( base64_decode( $result->password ), $encrypt_method, $key, 0, $iv );
                    $connect = '';
                    if ( !$result->status ) {
                        
                        $connect = '<a class="btn btn-success btn-xs"  href="' . site_url( 'admin/user?p=networks&network=instagram_api&net_id=' . $result->net_id ) . '" style="margin-top: -2px; color: #ffffff; margin-left: 0;"><i class="fa fa-plug" aria-hidden="true"></i></span></a>';
                        
                    }
                    $users .= '<tr>
                    <td><a href="' . site_url( 'admin/users#' . $result->user_id ) . '">' . $result->user_id . '</a></td>
                    <td>' . $result->email . '</td>
                    <td>' . $output . '</td>
                    <td class="text-center">' . $connect . '</td>
                    <td class="text-center"><a class="btn btn-danger btn-xs" href="' . site_url( 'admin/user?p=networks&network=instagram_api&page=' . $page . '&delete=' . $result->net_id ) . '" style="margin-top: -2px;"><i class="far fa-trash-alt"></i></a></td>
                    </tr>';
                }
            }
            
            $this->CI->db->select( '*' );
            $this->CI->db->from( 'users_networks' );
            $this->CI->db->where( ['type' => 'instagram_api'] );
            $query2 = $this->CI->db->get();
            
            // Get pagination
            if ( !$this->CI->input->get('page', TRUE) ) {
                
                $current = 1;
                
            } else {
                
                $current = $this->CI->input->get('page', TRUE);
                
            }
            
            // Set limit
            $limit = 10;
            
            if ( $current > 1 ) {
                
                $bac = $current - 1;
                $pages = '<li><a href="' . site_url( 'admin/user?p=networks&network=instagram_api&page=' . $bac ) . '">' . $this->CI->lang->line('networks_next') . '</a></li>';
                
            } else {
                
                $pages = '<li class="pagehide"><a href="#">' . $this->CI->lang->line('networks_next') . '</a></li>';
                
            }
            
            $tot = (int) $query2->num_rows() / (int) $limit;
            
            $tot = ceil($tot) + 1;
            
            $from = ($current > 2) ? $current - 2 : 1;
            
            for ( $p = $from; $p < $tot; $p++ ) {
                
                if ( $p == $current ) {
                    
                    $pages .= '<li class="active"><a>' . $p . '</a></li>';
                    
                } else if ( ($p < $current + 3) && ($p > $current - 3) ) {
                    
                    $pages .= '<li><a href="' . site_url( 'admin/user?p=networks&network=instagram_api&page=' . $p ) . '">' . $p . '</a></li>';
                    
                } else if ( ($p < 6) && ($tot > 5) && (($current == 1) || ($current == 2)) ) {
                    
                    $pages .= '<li><a href="' . site_url( 'admin/user?p=networks&network=instagram_api&page=' . $p ) . '">' . $p . '</a></li>';
                    
                } else {
                    
                    break;
                    
                }
                
            }
            
            if ( $p == 1 ) {
                
                $pages .= '<li class="active"><a href="' . site_url( 'admin/user?p=networks&network=instagram_api&page=' . $p ) . '">' . $p . '</a></li>';
                
            }
            
            $next = $current;
            
            $next++;
            
            if ( $next < $tot ) {
                
                $pages = $pages . '<li><a href="' . site_url( 'admin/user?p=networks&network=instagram_api&page=' . $next ) . '">' . $this->CI->lang->line('networks_prev') . '</a></li>';
                
            } else {
                
                $pages = $pages . '<li class="pagehide"><a href="#">' . $this->CI->lang->line('networks_prev') . '</a></li>';
                
            }
            
        }

        return array(
            'color' => '#c9349a',
            'icon' => '<i class="icon-social-instagram"></i>',
            'api' => array('client_id', 'client_secret'),
            'types' => array('post', 'rss'),
            'extra_content' => '<hr><div class="row">'
                . '<div class="col-md-12">'
                    . '<h5>Network\'s Users</h5>'
                    . '<div class="table-responsive">'
                        . '<table id="mytable" class="table table-bordred table-striped">'
                            . '<thead style="font-size: 13px;">'
                                . '<th>ID</th>'
                                . '<th>Email Login</th>'
                                . '<th>Password Login</th>'
                                . '<th class="text-center">Connect</th>'
                                . ' <th class="text-center">Delete</th>'
                            . '</thead>'
                        . '<tbody>'
                        . $users
                    . '</tbody>'
                . '</table>'
                . '<div class="clearfix"></div>'
                    . '<ul class="pagination pull-right">'
                        . $pages
                    . '</ul>'
                . '</div>'
            . '</div>'
            . '</div>'
        );
    }
    
    /**
     * This function generates a preview for Instagram
     *
     * @param array $args contains the img or url.
     *
     * @return array with html
     */
    public function preview($args) {
    }
    
}

/* End of file instagram_api.php */
