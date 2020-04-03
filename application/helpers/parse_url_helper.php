<?php
/**
 * Parse URL helper
 *
 * This file contains the method to parse the url
 * i've added it here to call the helper only where is required to parse a url
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
 */

defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('parse_url_content')) {
    
    /**
     * The function parse_url_content parses url's content
     * 
     * @return void
     */
    function parse_url_content() {
        
        // Get codeigniter object instance
        $CI = get_instance();
        
        // Default preview data
        $data = array(
            'title' => '',
            'img' => base_url() . 'assets/img/no-image.png',
            'description' => '',
            'url' => ''
        );
        
        // Check if data was submitted
        if ( $CI->input->post() ) {

            // Add form validation
            $CI->form_validation->set_rules('url', 'Url', 'trim|required');
            
            $url = str_replace('url: ', '', $CI->input->post('url'));

            // Check form validation
            if ($CI->form_validation->run() !== false ) {
                
                // Set url
                $data['url'] = str_replace(array('http://', 'https://', '/'), array('', '', ''), $url);
        
                // Get the Facebook App ID
                $app_id = get_option('facebook_pages_app_id');

                // Get the Facebook App Secret
                $app_secret = get_option('facebook_pages_app_secret');

                // Verify if user uses default Facebook's classes
                if ( !$app_id && !$app_secret ) {

                    // Get the Facebook App ID
                    $app_id = get_option('facebook_profiles_app_key');

                    // Get the Facebook App Secret
                    $app_secret = get_option('facebook_profiles_app_secret');

                }

                if ( $app_id && $app_secret ) {

                    // Create array
                    $params = array(
                        'client_id' => $app_id,
                        'client_secret' => $app_secret,
                        'grant_type' => 'client_credentials'
                    );

                    // Get app's token
                    $get_token = json_decode(
                        get(
                            'https://graph.facebook.com/oauth/access_token?' . urldecode(http_build_query($params))
                        ),
                        true
                    );
                    
                    if ( isset($get_token['access_token']) ) {

                        // Get content
                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, 'https://graph.facebook.com/');
                        curl_setopt($curl, CURLOPT_POST, 1);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, 'id=' . urlencode($url) . '&scrape=true&access_token=' . $get_token['access_token']);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
                        $get_content = json_decode(curl_exec($curl), true);
                        curl_close($curl);
                        
                        // Verify if title exists
                        if ( isset($get_content['title']) ) {
                            $data['title'] = $get_content['title'];
                        }
                        
                        // Verify if image exists
                        if ( isset($get_content['image'][0]['url']) ) {
                            $data['img'] = $get_content['image'][0]['url'];
                        }
                        
                        // Verify if description exists
                        if ( isset($get_content['description']) ) {
                            $data['description'] = $get_content['description'];
                        }
                    
                    }

                }
                
                // Verify if title exists
                if ( !$data['title'] ) {
                    $data['title'] = $data['url'];
                }

                // Verify if description exists
                if ( !$data['description'] ) {
                    $data['description'] = $data['url'];
                }                
                
            }
            
        }
        
        echo json_encode(array(
            'success' => TRUE,
            'response' => $data
        )); 
        
    }
    
}