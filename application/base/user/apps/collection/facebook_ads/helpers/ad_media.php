<?php
/**
 * Facebook Ad Media Helper
 *
 * This file contains the class Ad_media
 * with methods to create and manage media on Facebook ads
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Facebook_ads\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Namespaces to use
use FacebookAds\Object\AdAccountActivity;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdAccountFields;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\AdImage;
use FacebookAds\Object\Fields\AdImageFields;
use FacebookAds\Object\AdVideo;
use FacebookAds\Object\VideoThumbnail;

/*
 * Ad_media class provides the methods to manage media on Facebook Ads
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
*/
class Ad_media {
    
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
     * The function delete_ad_media deletes media file on Facebook
     * 
     * @return void
     */
    public function delete_ad_media() {
        
        // Get type
        $type = $this->CI->input->get('type', TRUE);
        
        // Get hash
        $hash = $this->CI->input->get('hash', TRUE);
        
        // Get id
        $id = $this->CI->input->get('id', TRUE);

        if ( $type === 'image' ) {
                
            $message = array(
                'success' => TRUE,
                'message' => $this->CI->lang->line('the_image_was_deleted'),
                'type' => 'image'
            );

            echo json_encode($message); 
            exit();

        } else if ( $type === 'video' ) {
                
            $message = array(
                'success' => TRUE,
                'message' => $this->CI->lang->line('the_video_was_deleted'),
                'type' => 'video'
            );

            echo json_encode($message); 
            exit(); 

        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('error_occurred')
        );

        echo json_encode($data); 
        
    }
    
    /**
     * The function upload_media_on_facebook uploads media file on Facebook
     * 
     * @return void
     */
    public function upload_media_on_facebook() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('type', 'Type', 'trim|required');
            
            // Get data
            $type = $this->CI->input->post('type');

            if ( $this->CI->form_validation->run() !== false ) {
            
                // Get selected account
                $get_account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');

                // Supported formats
                $check_format = array('image/bmp', 'image/jpeg', 'image/gif', 'video/mp4', 'video/webm', 'video/avi');

                if ( !$_FILES['file']['type'] ) {

                    $message = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('no_supported_format')
                    );

                    echo json_encode($message);
                    exit();

                }

                // Get upload limit
                $upload_limit = get_option('upload_limit');

                if ( !$upload_limit ) {

                    $upload_limit = 6291456;

                } else {

                    $upload_limit = $upload_limit * 1048576;

                }

                if ( $_FILES['file']['size'] > $upload_limit ) {

                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('file_too_large')
                    );

                    echo json_encode($data);
                    die();

                }

                if ( in_array( $_FILES['file']['type'], $check_format ) ) {

                    // Generate a new file name
                    $file_name = uniqid() . '-' . time();

                    if ( !is_dir( FCPATH . 'assets/temp' ) ) {
                        mkdir(FCPATH . 'assets/temp');
                    }

                    $config['upload_path'] = 'assets/temp';
                    $config['file_name'] = $file_name;
                    $this->CI->load->library('upload', $config);
                    $this->CI->upload->initialize($config);
                    $this->CI->upload->set_allowed_types('*');
                    $data['upload_data'] = '';

                    // Upload file 
                    if ( $this->CI->upload->do_upload('file') ) {

                        // Get uploaded file
                        $data['upload_data'] = $this->CI->upload->data();

                        // Get file
                        $file = FCPATH . 'assets/temp/' . $data['upload_data']['file_name'];

                        $api = Api::init($this->app_id, $this->app_secret, $get_account[0]->token);

                        try {

                            $account_data = $this->fb->get(
                                '/' . $get_account[0]->net_id . '?fields=funding_source,name,account_status',
                                $get_account[0]->token
                            );
    
                        } catch (Facebook\Exceptions\FacebookResponseException $e) {

                            $message = array(
                                'success' => FALSE,
                                'message' => $e->getMessage()
                            );

                            echo json_encode($message);
                            exit();
    
                        } catch (Facebook\Exceptions\FacebookSDKException $e) {
    
                            $message = array(
                                'success' => FALSE,
                                'message' => $e->getMessage()
                            );

                            echo json_encode($message);
                            exit();
    
                        }

                        $ad_account = $account_data->getDecodedBody();

                        // Check the account is active
                        if ($ad_account['account_status'] !== 1) {

                            $message = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('the_ad_account_is_not_active')
                            );

                            echo json_encode($message);
                            exit();

                        }
                        
                        if ( $type === 'image' ) {

                            $image = (new AdAccount($get_account[0]->net_id))->createAdImage(
                                array(),
                                array(
                                    AdImageFields::FILENAME => $file
                                )
                            );

                            unlink($file);

                            if ( isset($image->images[$data['upload_data']['file_name']]) ) {

                                $response = json_decode(
                                        get('https://graph.facebook.com/' . MIDRUB_ADS_FACEBOOK_GRAPH_VERSION . '/' . $get_account[0]->net_id . '/adimages?fields=url,url_128,hash,name,width,height&limit=1&access_token=' . $get_account[0]->token
                                    ),
                                    true
                                );

                                if ( ($response['data'][0]['width'] < 1000) || ($response['data'][0]['height'] < 800) ) {

                                    $message = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('image_is_too_small')
                                    );
        
                                    echo json_encode($message); 
                                    exit();

                                }

                                if ( isset($response['data'][0]['url_128']) ) {

                                    $message = array(
                                        'success' => TRUE,
                                        'url' => $response['data'][0]['url'],
                                        'url_128' => $response['data'][0]['url_128'],
                                        'hash' => $image->images[$data['upload_data']['file_name']]['hash'],
                                        'id' => $response['data'][0]['id'],
                                        'name' => $response['data'][0]['name'],
                                        'width' => $response['data'][0]['width'],
                                        'height' => $response['data'][0]['height'],
                                        'type' => $type,
                                        'words' => array(
                                            'change' => $this->CI->lang->line('change'),
                                            'delete' => $this->CI->lang->line('delete')
                                        ),
                                        'message' => $this->CI->lang->line('the_image_was_uploaded')

                                    );

                                    echo json_encode($message);
                                    exit();                            

                                }

                            }

                            $message = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('file_not_uploaded')
                            );

                            echo json_encode($message);  
                            
                        } else if ( $type === 'video' ) {
                            
                            if ( $_FILES['file']['size'] > 2097152 ) {
                                
                                $message = array(
                                    'success' => FALSE,
                                    'message' => $this->CI->lang->line('video_file_is_too_big')
                                );

                                echo json_encode($message);
                                
                            } else {
                            
                                try {
                                  // Returns a `Facebook\FacebookResponse` object
                                  $response = $this->fb->post(
                                    '/' . $get_account[0]->net_id . '/advideos',
                                    array (
                                      'source' => $this->fb->videoToUpload($file),
                                    ),
                                    $get_account[0]->token
                                  );
                                } catch(Facebook\Exceptions\FacebookResponseException $e) {

                                    $message = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('file_not_uploaded')
                                    );

                                    echo json_encode($message);                                  
                                    exit();

                                } catch(Facebook\Exceptions\FacebookSDKException $e) {

                                    $message = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('file_not_uploaded')
                                    );

                                    echo json_encode($message);                                  
                                    exit();

                                }

                                unlink($file);

                                $data = $response->getDecodedBody();

                                if ( isset($data['id']) ) {

                                    sleep(30);

                                    $response = json_decode(
                                            get('https://graph.facebook.com/' . MIDRUB_ADS_FACEBOOK_GRAPH_VERSION . '/' . $data['id'] . '?fields=picture,source&access_token=' . $get_account[0]->token
                                        ),
                                        true
                                    );

                                    if ( isset($response['picture']) ) {

                                        $message = array(
                                            'success' => TRUE,
                                            'url' => $response['picture'],
                                            'source' => $response['source'],
                                            'id' => $data['id'],
                                            'name' => $data['id'],
                                            'type' => $type,
                                            'words' => array(
                                                'change' => $this->CI->lang->line('change'),
                                                'delete' => $this->CI->lang->line('delete')
                                            ),
                                            'message' => $this->CI->lang->line('the_video_was_uploaded')

                                        );

                                        echo json_encode($message);
                                        exit();   

                                    }

                                }

                                $message = array(
                                    'success' => FALSE,
                                    'message' => $this->CI->lang->line('file_not_uploaded')
                                );

                                echo json_encode($message);
                                
                            }
                            
                        }

                    } else {

                        $message = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('file_not_uploaded')
                        );

                        echo json_encode($message);  

                    }

                } else {

                    $message = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('unsupported_format')
                    );

                    echo json_encode($message);

                }
                
            }
                
        }
        
    }

}

/* End of file ad_media.php */
