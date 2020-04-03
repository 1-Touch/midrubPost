<?php
/**
 * Categories helper
 *
 * This file contains the methods
 * for categories where are published the posts
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
 */

defined('BASEPATH') OR exit('No direct script access allowed');

if ( !function_exists( 'composer_get_categories' ) ) {

    /**
     * The function gets categories
     * 
     * @return string with categories
     */
    function composer_get_categories() {
        
        // Get codeigniter object instance
        $CI = get_instance();
        
        // Make uppercase first letter
        $network = $CI->input->get('network');
        
        // Get account's id
        $account_id = $CI->input->get('account_id');

        // Gets all categories from a blog.
        if ( $network === 'blogger' ) {

            // Check if the $network exists in autopost
            if ( file_exists(APPPATH . 'base/user/networks/' . $network . '.php') ) {
                
                // Get Blogger's api
                $key = get_option('blogger_api_key');

                // Initialize a cURL session
                $curl = curl_init();
                curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => 'https://www.googleapis.com/blogger/v2/blogs/' . $account_id . '/posts?fields=items&labels&key=' . $key, CURLOPT_HEADER => 'User-Agent: Chrome\r\n', CURLOPT_TIMEOUT => '3L'));
                $getCategories = curl_exec($curl);
                curl_close($curl);
                
                // Decode categories
                $getCategories = json_decode($getCategories);

                // Verify if categories exists
                if ( !empty($getCategories->items) ) {
                    
                    $cats = array();
                    
                    $categories = array();

                    foreach ( $getCategories->items as $category ) {
                        
                        // Verify if category was already added
                        if ( !in_array(@$category->labels[0], $cats) && @$category->labels[0] ) {
                            
                            // Add categories
                            $categories[] = '<option value="' . $category->labels[0] . '">' . $category->labels[0] . '</option>';
                            $cats[] = $category->labels[0];
                            
                        }
                        
                    }
                    
                    // Display success message
                    $data = array(
                        'success' => TRUE,
                        'categories' => $categories
                    );

                    echo json_encode($data);
                    
                }
                
            }
            
        } elseif ( $network === 'wordpress' ) {
            
            // Check if the $network exists in autopost
            if ( file_exists(APPPATH . 'base/user/networks/' . $network . '.php' ) ) {
                
                // Get categories
                $cats = get( 'https://public-api.wordpress.com/rest/v1.1/sites/' . $account_id . '/categories' );
                
                // Decode response
                $get = json_decode( $cats );
                
                $categories = array();
                
                // Verify if categories exists
                if ( !empty($get->categories) ) {
                    
                    // Lists all categories
                    foreach ( $get->categories as $category ) {
                        
                        $categories[] = '<option value="' . $category->slug . '">' . $category->name . '</option>';
                        
                    }
                    
                    // Display success message
                    $data = array(
                        'success' => TRUE,
                        'categories' => $categories
                    );

                    echo json_encode($data);
                    
                }
                
            }
            
        } elseif ($network === 'wordpress_platform') {
            
            // Check if the $network exists in autopost
            if ( file_exists(APPPATH . 'base/user/networks/' . $network . '.php') ) {
                
                // Get codeigniter object instance
                $CI = get_instance();
                
                // Load the User's model
                $CI->load->model('user');
                
                // Load the Networks's model
                $CI->load->model('networks');
                
                // Get user's ID
                $user_id = $CI->user->get_user_id_by_username($CI->session->userdata['username']);
                
                // Get site url
                $site_url = $CI->networks->get_account_field($user_id, $account_id, 'user_avatar');
                
                // Get categories
                $get = get($site_url . '?key=' . $account_id . '&catget=1');
                
                // Decode response
                $get = json_decode($get);
                
                // Define the array categories
                $categories = array();
                
                // Verify if categories exists
                if ( $get ) {
                    
                    // Lists all categories
                    foreach ( $get as $category ) {
                        
                        // Add category to the array $categories
                        $categories[] = '<option value="' . $category->term_id . '">' . $category->name . '</option>';
                        
                    }
                    
                    // Display success message
                    $data = array(
                        'success' => TRUE,
                        'categories' => $categories
                    );

                    echo json_encode($data);
                    
                }
                
            }
            
        } elseif ($network === 'reddit') {
            
            $categories = array();
            $categories[] = '<option value="funny">Funny</option>';
            $categories[] = '<option value="gifs">Gifs</option>';
            $categories[] = '<option value="gaming">Gaming</option>';
            $categories[] = '<option value="jokes">Jokes</option>';
            $categories[] = '<option value="personalfinance">Personal Finance</option>';
            $categories[] = '<option value="pics">Pics</option>';
            $categories[] = '<option value="movies">Movies</option>';
            $categories[] = '<option value="music">Music</option>';
            $categories[] = '<option value="worldnews">News</option>';
            $categories[] = '<option value="videos">Videos</option>';
            
            // Display success message
            $data = array(
                'success' => TRUE,
                'categories' => $categories
            );

            echo json_encode($data);
            
        } elseif ($network === 'youtube') {
            
            $categories = array();
            $categories[] = '<option value="1">Film & Animation</option>';
            $categories[] = '<option value="2">Autos & Vehicles</option>';
            $categories[] = '<option value="10">Music</option>';
            $categories[] = '<option value="15">Pets & Animals</option>';
            $categories[] = '<option value="17">Sports</option>';
            $categories[] = '<option value="18">Short Movies</option>';
            $categories[] = '<option value="20">Gaming</option>';
            $categories[] = '<option value="21">Videoblogging</option>';
            $categories[] = '<option value="22">People & Blogs</option>';
            $categories[] = '<option value="25">News & Politics</option>';
            $categories[] = '<option value="27">Education</option>';
            
            // Display success message
            $data = array(
                'success' => TRUE,
                'categories' => $categories
            );

            echo json_encode($data);
            
        } elseif ($network === 'imgur') {
            
            // Get codeigniter object instance
            $CI = get_instance();

            // Load the Networks's model
            $CI->load->model('networks');
            
            // Get the Imgur's client_id
            $clientId = get_option('imgur_client_id');

            // Get the Imgur's client_secret
            $clientSecret = get_option('imgur_client_secret');
            
            // Get secret
            $secret = $CI->networks->get_account_field($CI->user_id, $account_id, 'secret');
            
            // Get user name
            $user_name = $CI->networks->get_account_field($CI->user_id, $account_id, 'user_name');
            
            // Refresh the token 
            $curl = curl_init('https://api.imgur.com/oauth2/token');
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt(
                $curl, CURLOPT_POSTFIELDS, array(
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'refresh_token' => $secret,
                    'grant_type' => 'refresh_token'
                )
            );
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($curl);
            curl_close($curl);

            // Decode response
            $data = json_decode($data);
            
            if ( @$data->access_token ) {
                
                // Get user's information
                $curl = curl_init('https://api.imgur.com/3/account/' . $user_name . '/albums');
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $data->access_token));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $albums = json_decode(curl_exec($curl));
                curl_close($curl);

                $categories = array();
                
                if ( $albums->data ) {
                    
                    foreach ( $albums->data as $album ) {
                        
                        $categories[] = '<option value="' . $album->id . '">' . $album->title . '</option>';
                        
                    }
                    
                }

                // Display success message
                $data = array(
                    'success' => TRUE,
                    'categories' => $categories
                );

                echo json_encode($data);   
                
            } else {
                
                // Display success message
                $data = array(
                    'success' => TRUE,
                    'categories' => array()
                );                
                
                echo json_encode($data); 
                
            }
            
        } else {
            
            // Display error message
            $data = array(
                'success' => FALSE,
                'message' => $CI->lang->line('no_categories_found')
            );

            echo json_encode($data);            
            
        }
        
    }

}