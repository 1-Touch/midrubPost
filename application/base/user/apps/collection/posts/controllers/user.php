<?php
/**
 * User Controller
 *
 * This file loads the Posts app in the user panel
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Posts\Controllers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Posts\Helpers as MidrubBaseUserAppsCollectionPostsHelpers;

// Require the functions file
require_once MIDRUB_BASE_USER_APPS_POSTS . 'inc/functions.php';

/*
 * User class loads the Posts app loader
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
 */
class User {
    
    /**
     * Class variables
     *
     * @since 0.0.7.0
     */
    protected $CI, $total;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.0
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();

        // Load language
        $this->CI->lang->load( 'posts_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_POSTS);

        // Load the app's models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Posts_model', 'posts_model' );
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Rss_model', 'rss_model' );
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Networks_model', 'networks_model' );
        
    }
    
    /**
     * The public method view loads the app's template
     * 
     * @since 0.0.7.0
     * 
     * @return void
     */
    public function view() {

        // Set the page's title
        set_the_title($this->CI->lang->line('posts'));
        
        // Making temlate and send data to view.
        if ( $this->CI->input->get('q', TRUE) ) {
            
            switch ( $this->CI->input->get('q', TRUE) ) {
                
                case 'pixabay':

                    // Set Pixabay's styles
                    set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/posts/styles/css/pixabay.css?ver=' . MIDRUB_BASE_USER_APPS_POSTS_VERSION), 'text/css', 'all'));

                    // Set Pixabay's Js
                    set_js_urls(array(base_url('assets/base/user/apps/collection/posts/js/pixabay.js?ver=' . MIDRUB_BASE_USER_APPS_POSTS_VERSION)));

                    // Set views params
                    set_user_view(
                        $this->CI->load->ext_view(
                            MIDRUB_BASE_USER_APPS_POSTS . 'views',
                            'pixabay',
                            array(),
                            true
                        )
                    );        
                    
                    break;
                
                case 'post':
                    
                    // Get post id
                    $post_id = $this->CI->input->get('post_id', TRUE);
                    
                    // Get post data by user id and post id
                    $get_post = $this->CI->posts_model->get_post($this->CI->user_id, $post_id);
                    
                    if ( $get_post ) {
                        
                        // Get image
                        $img = unserialize($get_post['img']);

                        // Get video
                        $video = unserialize($get_post['video']);

                        // Verify if image exists
                        if ( $img ) {
                            $images = get_post_media_array($this->CI->user_id, $img );
                            if ($images) {
                                $img = $images;
                            }
                        }

                        // Verify if video exists
                        if ( $video ) {
                            $videos = get_post_media_array($this->CI->user_id, $video );
                            if ($videos) {
                                $video = $videos;
                            }
                        }

                        // Set Emojion Area Styles
                        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/posts/js/emojionearea-master/dist/emojionearea.min.css'), 'text/css', 'all'));

                        // Set Posts styles
                        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/posts/styles/css/posts.css?ver=' . MIDRUB_BASE_USER_APPS_POSTS_VERSION), 'text/css', 'all'));

                        // Set Emojion Area Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/posts/js/emojionearea-master/dist/emojionearea.min.js?ver=' . MIDRUB_BASE_USER_APPS_POSTS_VERSION)));

                        // Set views params
                        set_user_view(
                            $this->CI->load->ext_view(
                                MIDRUB_BASE_USER_APPS_POSTS . 'views',
                                'post',
                                array(
                                    'post' => $get_post,
                                    'images' => $img,
                                    'videos' => $video
                                ),
                                true
                            )
                        );
                        
                    } else {
                        
                        show_404();
                        
                    }
                    
                    break;
                    
                case 'rss':
                    
                    // Load language
                    $this->CI->lang->load( 'rss_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_POSTS );
                    
                    // Load RSS Helper
                    $this->CI->load->helper('fifth_helper');
                    
                    // Get rss id
                    $rss_id = $this->CI->input->get('rss_id', TRUE);
                    
                    // Get rss_data by rss_id
                    $rss_data = $this->CI->rss_model->get_rss($rss_id, $this->CI->user_id);

                    if ( $rss_data ) {
                        
                        // Try to get RSS content
                        $get_content = parse_rss_feed($rss_data[0]->rss_url);
                        
                        // Get selected accounts
                        $selected_accounts = plan_feature('publish_accounts');
                        
                        // Get publish limit
                        $published_limit = plan_feature('publish_posts');
                        
                        $body = array(
                            'rss_content' => $get_content,
                            'selected_accounts' => $selected_accounts,
                            'refferal' => trim($rss_data[0]->refferal),
                            'period' => trim($rss_data[0]->period),
                            'include' => trim($rss_data[0]->include),
                            'exclude' => trim($rss_data[0]->exclude),
                            'enabled' => $rss_data[0]->enabled,
                            'publish_description' => $rss_data[0]->publish_description,
                            'publish_url' => $rss_data[0]->publish_url,
                            'remove_url' => $rss_data[0]->remove_url,
                            'keep_html' => $rss_data[0]->keep_html,
                            'rss_id' => $rss_data[0]->rss_id,
                            'published' => 0,
                            'limit' => $published_limit,
                            'publish_way' => $rss_data[0]->pub,
                            'type' => $rss_data[0]->type
                        );

                        // Verify if user wants groups instead accounts
                        if ( get_user_option('settings_display_groups') ) {

                            // Load Lists Model
                            $this->CI->load->model('lists');
                            
                            // Get the user lists
                            $groups_list = $this->CI->lists->get_lists( $this->CI->user_id, 0, 'social', 10 );

                            // Get total number of accounts
                            $body['total'] = $this->CI->lists->get_lists( $this->CI->user_id, 0, 'social');
                            
                            $body['groups_list'] = $groups_list;

                        } else {
                            
                            // Get accounts list
                            $accounts_list = (new MidrubBaseUserAppsCollectionPostsHelpers\Accounts)->list_accounts_for_composer($this->CI->networks_model->get_accounts( $this->CI->user_id, 0, 10 ));

                            // Get total number of accounts
                            $body['total'] = $this->CI->networks_model->get_accounts( $this->CI->user_id, 0, 0);
                            
                            $body['accounts_list'] = $accounts_list;

                        }
                        
                        $body['title'] = $get_content['rss_title'];

                        // Set Posts styles
                        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/posts/styles/css/rss.css?ver=' . MIDRUB_BASE_USER_APPS_POSTS_VERSION), 'text/css', 'all'));

                        // Set Emojion Area Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/posts/js/rss.js?ver=' . MIDRUB_BASE_USER_APPS_POSTS_VERSION)));

                        // Set views params
                        set_user_view(
                            $this->CI->load->ext_view(
                                MIDRUB_BASE_USER_APPS_POSTS . 'views',
                                'rss',
                                $body,
                                true
                            )
                        );
                        
                    } else {
                        
                        show_404();
                        
                    }
                    
                    break;
                
                default:
                    
                    show_404();
                    
                    break;
                    
            }
            
        } else {

            // Set FullCalendar's styles
            set_css_urls(array('stylesheet', '//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css?ver=' . MD_VER, 'text/css', 'all'));

            // Set Emojion Area Styles
            set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/posts/js/emojionearea-master/dist/emojionearea.min.css'), 'text/css', 'all'));
        
            // Set Posts styles
            set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/posts/styles/css/posts.css?ver=' . MIDRUB_BASE_USER_APPS_POSTS_VERSION), 'text/css', 'all'));

            // Set FullCalendar's Js
            set_js_urls(array('//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js')); 

            if ( $this->CI->lang->line('calendar_language') ) {

                // Set FullCalendar's Js
                set_js_urls(array($this->CI->lang->line('calendar_language')));

            }  
            
            // Set Chart Js
            set_js_urls(array('//www.chartjs.org/dist/2.7.2/Chart.js'));
            
            // Set Utils Js
            set_js_urls(array('//www.chartjs.org/samples/latest/utils.js'));            

            // Set Emojion Area Js
            set_js_urls(array(base_url('assets/base/user/apps/collection/posts/js/emojionearea-master/dist/emojionearea.min.js?ver=' . MIDRUB_BASE_USER_APPS_POSTS_VERSION)));

            // Set Posts Js
            set_js_urls(array(base_url('assets/base/user/apps/collection/posts/js/posts.js?ver=' . MIDRUB_BASE_USER_APPS_POSTS_VERSION)));  
            
            // Set Media Js
            set_js_urls(array(base_url('assets/user/js/media.js?ver=' . MIDRUB_BASE_USER_APPS_POSTS_VERSION)));  
            
            // Define the accounts list valiable
            $accounts_list = '';
            
            // Define the groups list variable
            $groups_list = '';
            
            // Define the total accounts variable
            $total = 0;
            
            // Verify if user wants groups instead accounts
            if ( get_user_option('settings_display_groups') ) {

                // Load Lists Model
                $this->CI->load->model('lists');
                
                // Get the user lists
                $groups_list = $this->CI->lists->get_lists( $this->CI->user_id, 0, 'social', 10 );
                
                // Get total number of accounts
                $total = $this->CI->lists->get_lists( $this->CI->user_id, 0, 'social');
                
            } else {
                
                // Get accounts list
                $accounts_list = (new MidrubBaseUserAppsCollectionPostsHelpers\Accounts)->list_accounts_for_composer($this->CI->networks_model->get_accounts( $this->CI->user_id, 0, 10 ));
                
                // Get total number of accounts
                $total = $this->CI->networks_model->get_accounts( $this->CI->user_id, 0, 0);              
                
            }
            
            // Set total
            $this->total = $total;

            // Social Networks with preview
            $preview_socials = array();

            // List all available networks
            foreach (glob(MIDRUB_BASE_USER . 'networks/*.php') as $filename) {

                // Get the class's name
                $className = str_replace(array(MIDRUB_BASE_USER . 'networks/', '.php'), '', $filename);

                // Check if the administrator has disabled the $className social network
                if ( !get_option($className) || !plan_feature($className) ) {
                    continue;
                }

                // Create an array
                $array = array(
                    'MidrubBase',
                    'User',
                    'Networks',
                    ucfirst($className)
                );

                // Implode the array above
                $cl = implode('\\', $array);

                // Get method
                $get = (new $cl());

                // Verify if the social networks is available
                if ($get->check_availability()) {

                    // Get social info
                    $info = $get->get_info();

                    // Verify if social network has preview
                    if ( !in_array('preview', $info['types']) ) {
                        continue;
                    }

                    // Add to list
                    $preview_socials[] = array(
                        'name' => ucwords(str_replace('_', ' ', $className)),
                        'slug' => strtolower($className),
                        'preview_icon' => $info['preview_icon']
                    );

                }

            }
            
            if ( get_option('app_posts_enable_dropbox') ) {

                // Register a hook
                add_hook(
                    'the_header',
                    function () {
                        echo '<script type="text/javascript" src="//www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="' . get_option('app_posts_dropbox_app_key') . '"></script>';
                    }
                
                );

            }          

            // Set views params
            set_user_view(
                $this->CI->load->ext_view(
                    MIDRUB_BASE_USER_APPS_POSTS . 'views',
                    'main',
                    array(
                        'accounts_list' => $accounts_list,
                        'groups_list' => $groups_list,
                        'total' => $total,
                        'preview_socials' => $preview_socials
                    ),
                    true
                )
                
            );
            
        }
        
    }

}
