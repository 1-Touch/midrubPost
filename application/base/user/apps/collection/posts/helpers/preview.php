<?php
/**
 * Preview Helpers
 *
 * This file contains the class Preview
 * with methods to process the preview
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Posts\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// If session valiable doesn't exists will be called
if ( !isset($_SESSION) ) {
    session_start();
}

/*
 * Preview class provides the methods to process the preview
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
*/
class Preview {
    
    /**
     * Class variables
     *
     * @since 0.0.7.0
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.0
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
    }

    /**
     * The public method composer_generate_preview generates preview for social networks
     *
     * @since 0.0.7.8
     * 
     * @return void
     */
    public function composer_generate_preview() {

        $args = array();
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('slug', 'Slug', 'trim|required');
            $this->CI->form_validation->set_rules('title', 'Title', 'trim');
            $this->CI->form_validation->set_rules('body', 'Body', 'trim');
            $this->CI->form_validation->set_rules('medias', 'Medias', 'trim');
            $this->CI->form_validation->set_rules('url', 'Url', 'trim');
            
            // Get data
            $slug = $this->CI->input->post('slug');
            $title = $this->CI->input->post('title');
            $body = $this->CI->input->post('body');
            $medias = $this->CI->input->post('medias');
            $url = $this->CI->input->post('url');
            $parsed_url = array();

            if ( $title ) {
                $args['title'] = $title;
            }

            if ( $body ) {
                $args['body'] = $body;
            }

            if ( $medias ) {
                $args['medias'] = $medias;
            }

            if ( $url ) {
                
                // Get the Facebook App ID
                $app_id = get_option('facebook_pages_app_id');

                // Get the Facebook App Secret
                $app_secret = get_option('facebook_pages_app_secret');

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

                        if ( !isset($_SESSION[$url]) ) {

                            // Get content
                            $curl = curl_init();
                            curl_setopt($curl, CURLOPT_URL, 'https://graph.facebook.com/');
                            curl_setopt($curl, CURLOPT_POST, 1);
                            curl_setopt($curl, CURLOPT_POSTFIELDS, 'id=' . urlencode($url) . '&scrape=true&access_token=' . $get_token['access_token']);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
                            $get_content = json_decode(curl_exec($curl), true);
                            curl_close($curl);

                        } else {
                            
                            $get_content = $_SESSION[$url];

                        }

                        // Verify if url exists
                        if (isset($get_content['url'])) {
                            $parsed_url['url'] = $get_content['url'];
                        }
                        
                        // Verify if title exists
                        if ( isset($get_content['title']) ) {
                            $parsed_url['title'] = $get_content['title'];
                        }
                        
                        // Verify if image exists
                        if ( isset($get_content['image'][0]['url']) ) {
                            $parsed_url['img'] = $get_content['image'][0]['url'];
                        }
                        
                        // Verify if description exists
                        if ( isset($get_content['description']) ) {
                            $parsed_url['description'] = $get_content['description'];
                        }
                    
                    }

                }
                
                // Verify if title exists
                if ( !isset($parsed_url['title']) ) {
                    $parsed_url['title'] = $parsed_url['url'];
                }

                // Verify if description exists
                if ( !isset($parsed_url['description']) ) {
                    $parsed_url['description'] = $parsed_url['url'];
                }  

                if ( $parsed_url ) {
                    $args['link'] = $parsed_url;
                    $args['link']['url'] = $url;
                    $body = str_replace($url, '', $body);
                }

            }

            if ( $this->CI->form_validation->run() !== false ) {

                // Check if the $network exists
                if (file_exists(MIDRUB_BASE_USER . 'networks/' . $slug . '.php')) {

                    // Now we need to get the key
                    require_once MIDRUB_BASE_USER . 'networks/' . $slug . '.php';

                    // Create an array
                    $array = array(
                        'MidrubBase',
                        'User',
                        'Networks',
                        ucfirst($slug)
                    );

                    // Implode the array above
                    $cl = implode('\\', $array);

                    // Get method
                    $get = (new $cl());

                    // Get social preview
                    $preview = $get->preview($args);

                    // Verify if preview exists
                    if ( $preview ) {

                        $data = array(
                            'success' => TRUE,
                            'preview' => $preview['body']
                        );
                
                        echo json_encode($data);
                        exit();

                    }

                }

            }

        }

        $data = '';

        if ( isset($args['title']) ) {

            $data .= '<div class="row">'
                    . '<div class="col-xl-12 post-preview-title">'
                        . '<div class="row">'
                            . $args['title']
                        . '</div>'
                    . '</div>'
                . '</div>';

        } else {

            $data .= '<div class="row">'
                    . '<div class="col-xl-12 post-preview-title">'
                        . '<div class="row">'
                            . '<div class="col-xl-8"></div>'
                        . '</div>'
                    . '</div>'
                . '</div>';            

        }

        if ( isset($args['body']) ) {

            $link = '';

            if ( isset($args['link']) ) {

                $parse = parse_url($url);

                if ( isset($args['link']['img']) ) {

                    $link = '<table class="full">'
                        . '<tbody>'
                            . '<tr>'
                                . '<td>'
                                    . '<a href="#" class="btn-delete-post-url"><i class="icon-close"></i></a>'
                                    . '<img src="' . $args['link']['img'] . '">'
                                . '</td>'
                            . '</tr>'
                            . '<tr>'
                                . '<td>'
                                    . '<h3>'
                                        . $parsed_url['title']
                                    . '</h3>'
                                    . '<a href="' . $url . '" target="_blank">'
                                        . $parse['host']
                                    . '</a>'
                                    . '<p>'
                                        . $args['link']['description']
                                    . '</p>'
                                . '</td>'
                            . '</tr>'
                        . '</tbody>'
                    . '</table>';
                    
                } else {

                    $link = '<table class="full">'
                        . '<tbody>'
                            . '<tr>'
                                . '<td>'
                                    . '<h3>'
                                        . $parsed_url['title']
                                    . '</h3>'
                                    . '<a href="' . $url . '" target="_blank">'
                                        . $parse['host']
                                    . '</a>'
                                    . '<p>'
                                        . $args['link']['description']
                                    . '</p>'
                                . '</td>'
                            . '</tr>'
                        . '</tbody>'
                    . '</table>';
                    
                }

            }

            $data .= '<div class="row">'
                        . '<div class="col-xl-12 post-preview-body">'
                            . '<div class="row">'
                                . $args['body']
                                . $link
                            . '</div>'
                        . '</div>'
                    . '</div>';

        } else if (isset($args['link'])) {

            $link = '';

            if ( isset($args['link']) ) {

                $parse = parse_url($url);

                if ( isset($args['link']['img']) ) {

                    $link = '<table class="full">'
                        . '<tbody>'
                            . '<tr>'
                                . '<td>'
                                    . '<a href="#" class="btn-delete-post-url"><i class="icon-close"></i></a>'
                                    . '<img src="' . $args['link']['img'] . '">'
                                . '</td>'
                            . '</tr>'
                            . '<tr>'
                                . '<td>'
                                    . '<h3>'
                                        . $parsed_url['title']
                                    . '</h3>'
                                    . '<a href="' . $url . '" target="_blank">'
                                        . $parse['host']
                                    . '</a>'
                                    . '<p>'
                                        . $args['link']['description']
                                    . '</p>'
                                . '</td>'
                            . '</tr>'
                        . '</tbody>'
                    . '</table>';
                    
                } else {

                    $link = '<table class="full">'
                        . '<tbody>'
                            . '<tr>'
                                . '<td>'
                                    . '<h3>'
                                        . $parsed_url['title']
                                    . '</h3>'
                                    . '<a href="' . $url . '" target="_blank">'
                                        . $parse['host']
                                    . '</a>'
                                    . '<p>'
                                        . $args['link']['description']
                                    . '</p>'
                                . '</td>'
                            . '</tr>'
                        . '</tbody>'
                    . '</table>';
                    
                }

            }

            $data .= '<div class="row">'
                        . '<div class="col-xl-12 post-preview-body">'
                            . '<div class="row">'
                                . $link
                            . '</div>'
                        . '</div>'
                    . '</div>';

        } else {

            $data .= '<div class="row">'
                        . '<div class="col-xl-12 post-preview-body">'
                            . '<div class="row">'
                                . '<div class="col-xl-11"></div>'
                            . '</div>'
                            . '<div class="row">'
                                . '<div class="col-xl-11"></div>'
                            . '</div>'
                            . '<div class="row">'
                                . '<div class="col-xl-11"></div>'
                            . '</div>'
                            . '<div class="row">'
                                . '<div class="col-xl-7"></div>'
                            . '</div>'
                        . '</div>'
                    . '</div>';            

        }

        if ( isset($args['medias']) ) {

            $data .= '<div class="row">'
                        . '<div class="col-xl-12 post-preview-medias" style="background-color: rgb(255, 255, 255);">';

            foreach ( $args['medias'] as $media ) {

                if ( $media['type'] === 'image' ) {

                    $data .= '<div data-id="' . $media['id'] . '" data-type="' . $media['type'] . '">'
                            . '<img src="' . $media['url'] . '">'
                            . '<a href="#" class="btn-delete-post-media">'
                                . '<i class="icon-close"></i>'
                            . '</a>'
                            . '<div>'
                            . '</div>'
                        . '</div>';

                } else if ( $media['type'] === 'video' ) {

                    $data .= '<div data-id="' . $media['id'] . '" data-type="' . $media['type'] . '">'
                        . '<video controls="">'
                            . '<source src="' . $media['url'] . '" type="video/mp4">'
                        . '</video>'
                        . '<a href="#" class="btn-delete-post-media">'
                            . '<i class="icon-close"></i>'
                        . '</a>'
                        . '<div>'
                        . '</div>'
                    . '</div>';

                }

            }

            $data .= '</div>'
                . '</div>';

        } else {

            $data .= '<div class="row">'
                    . '<div class="col-xl-12 post-preview-medias">'
                    . '</div>'
                . '</div>';

        }

        $data = array(
            'success' => TRUE,
            'preview' => $data
        );

        echo json_encode($data);
        
    }

}

/* End of file preview.php */