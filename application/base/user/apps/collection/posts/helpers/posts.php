<?php
/**
 * Posts Helpers
 *
 * This file contains the class Posts
 * with methods to process the posts data
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Posts\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Posts\Helpers as MidrubBaseUserAppsCollectionHelpers;

/*
 * Posts class provides the methods to process the posts data
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
*/
class Posts {
    
    /**
     * Class variables
     *
     * @since 0.0.7.0
     */
    protected $CI, $socials = array();

    /**
     * Initialise the Class
     *
     * @since 0.0.7.0
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();

        // Load Networks Model
        $this->CI->load->model('networks');
        
    }
    
    /**
     * The public method composer_publish_post publishes a post
     * 
     * @since 0.0.7.4
     * 
     * @return true or false
     */ 
    public function composer_publish_post() {
        
        // Load Main Helper
        $this->CI->load->helper('short_url_helper');
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('post', 'Post', 'trim|required');
            $this->CI->form_validation->set_rules('networks', 'Networks', 'trim');
            $this->CI->form_validation->set_rules('group_id', 'Group ID', 'trim');
            $this->CI->form_validation->set_rules('url', 'Url', 'trim');
            $this->CI->form_validation->set_rules('medias', 'Medias', 'trim');
            $this->CI->form_validation->set_rules('category', 'Category', 'trim');
            $this->CI->form_validation->set_rules('date', 'Date', 'trim');
            $this->CI->form_validation->set_rules('current_date', 'Current Date', 'trim');
            $this->CI->form_validation->set_rules('post_title', 'Post Title', 'trim');
            $this->CI->form_validation->set_rules('publish', 'Publish', 'trim|integer');
            $this->CI->form_validation->set_rules('fb_boost_id', 'Facebook Boost ID', 'trim');
            
            // Get data
            $post = str_replace('-', '/', $this->CI->input->post('post'));
            $post = $this->CI->security->xss_clean(base64_decode($post));
            $networks = $this->CI->input->post('networks');
            $group_id = $this->CI->input->post('group_id');
            $url = $this->CI->input->post('url');
            $medias = $this->CI->input->post('medias');
            $category = $this->CI->input->post('category');
            $date = $this->CI->input->post('date');
            $current_date = $this->CI->input->post('current_date');
            $publish = $this->CI->input->post('publish');
            $post_title = $this->CI->input->post('post_title');
            $fb_boost_id = $this->CI->input->post('fb_boost_id');
            $img = array();
            $video = array();

            // Verify if medias is not empty
            if ( $medias ) {
                
                foreach ( $medias as $media ) {
                    
                    if ( $media['type'] === 'image' ) {
                        
                        $img[] = $media['id'];
                        
                    } else {
                        
                        $video[] = $media['id'];                        
                        
                    }
                    
                }
                
            }
            
            // Serialize media
            $img = serialize($img);
            $video = serialize($video);
            
            // Get number of published posts in this month
            $posts_published = get_user_option('published_posts');
            
            if ( $posts_published ) {
                
                $posts_published = unserialize($posts_published);
                
                $published_limit = plan_feature('publish_posts');
                
                if ( ($posts_published['date'] === date('Y-m')) AND ( $published_limit <= $posts_published['posts']) ) {
                    
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('reached_maximum_number_posts')
                    );

                    echo json_encode($data);

                    exit();
                    
                }
                
            }
            
            if ( $this->CI->form_validation->run() === false ) {
                
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('your_post_too_short')
                );

                echo json_encode($data);
                exit();
                
            } else {
                
                $date = (is_numeric(strtotime($date))) ? strtotime($date) : time();
                
                $current_date = (is_numeric(strtotime($current_date))) ? strtotime($current_date) : time();
                
                // If date is null or has invalid format will be converted to current time or null with strtotime
                if ( $date > $current_date ) {
                    
                    if ( get_user_option('settings_display_groups') ) {
                        
                        if ( !is_numeric($group_id) ) {

                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('no_group_selected')
                            );

                            echo json_encode($data);

                            exit();

                        }
                        
                    } else {
                    
                        if ( !$networks ) {

                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('no_accounts_selected')
                            );

                            echo json_encode($data);

                            exit();

                        }
                        
                    }
                    
                    // The post will be scheduled
                    $publish = 2;
                    
                    $d = $date - $current_date;
                    
                    $date = time() + $d;
                    
                } else {
                    
                    $date = time();
                    
                }
                
                if ( is_numeric($group_id) ) {
                    
                    $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Lists_model', 'lists_model' );
                    
                    $metas = $this->CI->lists_model->get_lists_meta($this->CI->user_id, $group_id);
                    
                    if ( $metas ) {
                        
                        $category = $group_id;
                        $networks = $metas;
                        
                    } else {
                        
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('posts_the_selected_group_is_empty')
                        );

                        echo json_encode($data); 
                        exit();
                        
                    }
                    
                }
                
                if ( !is_numeric($category) ) {
                    $category = json_encode($category);
                }
                
                $lastId = $this->CI->posts_model->save_post($this->CI->user_id, $post, $url, $img, $video, $date, $publish, $category, $post_title, $fb_boost_id);

                if ( $networks ) {
                    
                    if ( $lastId ) {
                        
                        $net = '';
                        
                        if ( !is_numeric($category) ) {
                            
                            foreach ($networks as $network => $account) {
                                
                                $post2 = $post;
                                
                                $post_title2 = $post_title;
                                
                                // Check if network exists
                                if (file_exists(MIDRUB_BASE_USER . 'networks/' . $network . '.php')) {
                                    
                                    $accounts = json_decode($account);
                                    
                                    if ( $accounts ) {
                                        
                                        foreach ($accounts as $ac_id) {
                                            
                                            if ( (int)$publish === 1 ) {
                                                
                                                if ( get_user_option('use_spintax_posts') ) {
                                                    
                                                    if ( in_array($network, $this->socials) ) {
                                                        
                                                        $post2 = $this->CI->ecl('Deco')->lsd($post2, $this->CI->user_id);
                                                        
                                                        if ( $post_title2 ) {
                                                            
                                                            $post_title2 = $this->CI->ecl('Deco')->lsd($post_title2, $this->CI->user_id);
                                                            
                                                        }
                                                        
                                                    } else {
                                                        
                                                        $this->socials[] = $network;
                                                        
                                                    }
                                                    
                                                }
                                                
                                                $args = array(
                                                    'post' => $post2,
                                                    'title' => $post_title2,
                                                    'network' => $network,
                                                    'account' => $ac_id,
                                                    'url' => $url,
                                                    'img' => get_post_media_array($this->CI->user_id, unserialize($img) ),
                                                    'video' => get_post_media_array($this->CI->user_id, unserialize($video) ),
                                                    'category' => $category,
                                                    'id' => $lastId
                                                );
                                                
                                                $check_pub = publish_post($args);
                                                
                                                if ( $check_pub ) {
                                                    
                                                    if ( $net ) {
                                                        
                                                        if ( !preg_match('/' . $network . '/i', $net) ) {
                                                            
                                                            $net .= ', ' . ucfirst($network);
                                                            
                                                        }
                                                        
                                                    } else {
                                                        
                                                        if ( !preg_match('/' . $network . '/i', $net) ) {
                                                            
                                                            $net .= ucfirst($network);
                                                            
                                                        }
                                                        
                                                    }
                                                    
                                                    if ( $check_pub === true ) {
                                                        $check_pub = 0;
                                                    }
                                                    
                                                    $this->CI->posts_model->save_post_meta($lastId, $ac_id, $network, 1, $this->CI->user_id, $check_pub);
                                                    
                                                } else {
                                                    
                                                    $this->CI->posts_model->save_post_meta($lastId, $ac_id, $network, 2, $this->CI->user_id);
                                                    
                                                }
                                                
                                            } else {
                                                
                                                $net .= ucfirst(str_replace('_', ' ', $network));
                                                $this->CI->posts_model->save_post_meta($lastId, $ac_id, $network, 0, $this->CI->user_id);
                                                
                                            }
                                            
                                        }
                                        
                                    }
                                    
                                }
                                
                            }
                            
                        } else {
                            
                            if ( $networks ) {
                                
                                foreach ($networks as $meta) {
                                    
                                    $post2 = $post;
                                    
                                    $post_title2 = $post_title;
                                    
                                    // Check if network exists
                                    if ( file_exists(MIDRUB_BASE_USER . 'networks/' . $meta->network_name . '.php') ) {
                                        
                                        if ( $this->CI->user->get_user_option($this->CI->user_id, 'use_spintax_posts') === 1 ) {
                                            
                                            if ( in_array($meta->network_name, $this->socials) ) {
                                                
                                                $post2 = $this->CI->ecl('Deco')->lsd($post2, $this->CI->user_id);
                                                
                                                if( $post_title2 ) {
                                                    
                                                    $post_title2 = $this->CI->ecl('Deco')->lsd($post_title2, $this->CI->user_id);
                                                    
                                                }
                                                
                                            } else {
                                                
                                                $this->socials[] = $meta->network_name;
                                                
                                            }
                                            
                                        }
                                        
                                        if ( $meta->network_id ) {
                                            
                                            if ( (int)$publish === 1 ) {
                                                
                                                $args = array(
                                                    'post' => $post2,
                                                    'title' => $post_title2,
                                                    'network' => $meta->network_name,
                                                    'account' => $meta->network_id,
                                                    'url' => $url,
                                                    'img' => get_post_media_array($this->CI->user_id, unserialize($img)),
                                                    'video' => get_post_media_array($this->CI->user_id, unserialize($video)),
                                                    'category' => json_encode($category),
                                                    'id' => $lastId
                                                );
                                                
                                                $check_pub = publish_post($args);
                                                
                                                if ( $check_pub ) {
                                                    
                                                    $net = $this->CI->lang->line('accounts_from_the_selected_group');
                                                    
                                                    if ( $check_pub === true ) {
                                                        $check_pub = 0;
                                                    }
                                                    
                                                    $this->CI->posts_model->save_post_meta($lastId, $meta->network_id, $meta->network_name, 1, $this->CI->user_id, $check_pub);
                                                    
                                                }
                                                
                                            }
                                            
                                        }
                                        
                                    }
                                    
                                    sleep(1);
                                    
                                }
                                
                            }
                            
                            if ( (int)$publish === 2 ) {
                                
                                $net = $this->CI->lang->line('accounts_from_the_selected_group');
                                
                            }
                            
                        }
                        
                        if ( $net ) {
                            
                            if ( (int)$publish === 1 ) {
                                
                                // A new post was published successfully in this month
                                set_post_number($this->CI->user_id);
                                
                                $data = array(
                                    'success' => TRUE,
                                    'message' => $this->CI->lang->line('your_post_was_published') . str_replace('_', ' ', $net)
                                );

                                echo json_encode($data);
                                
                            } elseif ( (int)$publish === 2 ) {
                                
                                $data = array(
                                    'success' => TRUE,
                                    'message' => $this->CI->lang->line('your_post_was_scheduled')
                                );

                                echo json_encode($data);
                                
                            } else {
                                
                                $data = array(
                                    'success' => TRUE,
                                    'message' => $this->CI->lang->line('post_saved_as_draft')
                                );

                                echo json_encode($data); 
                                
                            }
                            
                        } else {
                            
                            if ( (int)$publish === 2 && is_numeric($category) ) {
                                
                                $data = array(
                                    'success' => TRUE,
                                    'message' => $this->CI->lang->line('your_post_was_scheduled')
                                );

                                echo json_encode($data);
                                
                            } else {
                            
                                $data = array(
                                    'success' => FALSE,
                                    'message' => $this->CI->lang->line('error_occurred')
                                );

                                echo json_encode($data);
                            
                            }
                            
                        }
                        
                    } else {
                        
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('your_post_was_not_published')
                        );

                        echo json_encode($data);                         
                        
                    }
                    
                } else {
                    
                    if ( (int)$publish === 0 ) {
                        
                        $data = array(
                            'success' => TRUE,
                            'message' => $this->CI->lang->line('post_saved_as_draft')
                        );

                        echo json_encode($data); 
                        
                    } else {
                        
                        if ( get_user_option('settings_display_groups') ) {

                            if ( !is_numeric($group_id) ) {

                                $data = array(
                                    'success' => FALSE,
                                    'message' => $this->CI->lang->line('no_group_selected')
                                );

                                echo json_encode($data);

                                exit();

                            }

                        } else {

                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('no_accounts_selected')
                            );

                            echo json_encode($data);
                            
                        }
                        
                    }
                    
                }
                
            }
            
        }
        
    }

    /**
     * The public method history_edit_post edits a post
     * 
     * @since 0.0.8.0
     * 
     * @return true or false
     */ 
    public function history_edit_post() { 
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('post_id', 'Post ID', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('post', 'Post', 'trim|required');
            $this->CI->form_validation->set_rules('url', 'Url', 'trim');
            $this->CI->form_validation->set_rules('medias', 'Medias', 'trim');
            $this->CI->form_validation->set_rules('post_title', 'Post Title', 'trim');
            $this->CI->form_validation->set_rules('date', 'Date', 'trim');
            $this->CI->form_validation->set_rules('current_date', 'Current Date', 'trim');
            
            // Get data
            $post_id = $this->CI->input->post('post_id');
            $post = str_replace('-', '/', $this->CI->input->post('post'));
            $post = $this->CI->security->xss_clean(base64_decode($post));
            $url = $this->CI->input->post('url');
            $medias = $this->CI->input->post('medias');
            $post_title = $this->CI->input->post('post_title');
            $date = $this->CI->input->post('date');
            $current_date = $this->CI->input->post('current_date');
            $img = array();
            $video = array();

            // Verify if medias is not empty
            if ( $medias ) {
                
                foreach ( $medias as $media ) {
                    
                    if ( $media['type'] === 'image' ) {
                        
                        $img[] = $media['id'];
                        
                    } else {
                        
                        $video[] = $media['id'];                        
                        
                    }
                    
                }
                
            }
            
            // Serialize media
            $img = serialize($img);
            $video = serialize($video);
            
            if ( $this->CI->form_validation->run() === false ) {
                
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('your_post_too_short_or_post_id_missing')
                );

                echo json_encode($data);
                exit();
                
            } else {

                // Get post data by user id and post id
                $get_post = $this->CI->posts_model->get_post($this->CI->user_id, $post_id);

                // Publish time
                $publish_time = 0;

                // Verify if user is the post's owner
                if ( $get_post ) {

                    // Verify if the post is scheduled
                    if ( $get_post['status'] > 1 ) {

                        $date = (is_numeric(strtotime($date))) ? strtotime($date) : time();
                
                        $current_date = (is_numeric(strtotime($current_date))) ? strtotime($current_date) : time();
                        
                        // If date is null or has invalid format will be converted to current time or null with strtotime
                        if ( $date > $current_date ) {
                            
                            $d = $date - $current_date;
                            
                            $date = time() + $d;
                            
                        } else {
                            
                            $date = time();
                            
                        }

                        $publish_time = $date;

                    }

                    // Try to update the post
                    if ( $this->CI->posts_model->update_post($post_id, $this->CI->user_id, $post, $url, $img, $video, $post_title, $publish_time) ) {

                        $data = array(
                            'success' => TRUE,
                            'message' => $this->CI->lang->line('posts_post_was_updated'),
                            'post_id' => $post_id
                        );
        
                        echo json_encode($data);

                    } else {

                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('posts_post_not_updated')
                        );
        
                        echo json_encode($data);

                    }

                } else {

                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('posts_the_post_is_not_yours')
                    );

                    echo json_encode($data);

                }
                
            }
            
        }
        
    }
    
    /**
     * The public method composer_display_all_posts will display posts with pagination
     *
     * @since 0.0.7.4
     * 
     * @return void
     */
    public function composer_display_all_posts() {
        
        // Get page's input
        $page = $this->CI->input->get('page', TRUE);
        
        // Get search's key input
        $search = $this->CI->input->get('key', TRUE);        
        
        $limit = 10;
        
        $page--;
        
        // Get total posts
        $total = $this->CI->posts_model->get_posts($this->CI->user_id, '', '', $search);
        
        // Get posts by page
        $get_posts = $this->CI->posts_model->get_posts($this->CI->user_id, ($page * $limit), $limit, $search);
        
        // Verify if posts exists
        if ( $get_posts ) {
            
            $data = array(
                'success' => TRUE,
                'total' => $total,
                'date' => time(),
                'page' => ($page + 1),
                'posts' => $get_posts,
                'details' => $this->CI->lang->line('details')
            );

            echo json_encode($data);
            
        } else {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('no_posts_found')
            );

            echo json_encode($data);            
            
        }
        
    }
    
    /**
     * The public method get_user_post gets post's details
     * 
     * @since 0.0.7.0
     * 
     * @return void
     */
    public function get_user_post() {
        
        // Get post's id
        $post_id = $this->CI->input->get('post_id', TRUE);        
        
        // Get post data by user id and post id
        $get_post = $this->CI->posts_model->get_post($this->CI->user_id, $post_id);
        
        if ( $get_post ) {
            
            if ( get_user_option('settings_display_groups') && $get_post['status'] != '1' ) {
                
                if ( is_numeric( $get_post['category'] ) ) {
                    
                    // Load the lists model
                    $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Lists_model', 'lists_model' );
                
                    // Get social networks
                    $group_metas = $this->CI->lists_model->get_lists_meta( $this->CI->user_id, $get_post['category'] );
                    
                    $networks = array();
                    
                    if ( $group_metas ) {
                        
                        foreach ( $group_metas as $meta ) {
                            
                            $array_meta = (array)$meta;
                            $array_meta['status'] = '0';
                            $array_meta['network_status'] = '';
                            $networks[] = $array_meta;
                            
                        }
                        
                    }
                    
                } else {
                    
                    $networks = array();
                    
                }
                
            } else {

                // Get social networks
                $networks = $this->CI->posts_model->all_social_networks_by_post_id( $this->CI->user_id, $post_id );
                
            }

            $profiles = array();
            
            $networks_icon = array();

            if ( $networks ) {

                foreach ( $networks as $network ) {

                    if ( in_array( $network['network_name'], $networks_icon ) ) {

                        $profiles[] = array(
                            'network_id' => $network['network_id'],
                            'user_name' => $network['user_name'],
                            'status' => $network['status'],
                            'network_status' => $network['network_status'],
                            'icon' => $networks_icon[$network['network_name']],
                            'network_name' => ucwords( str_replace('_', ' ', $network['network_name']) )
                        );

                    } else {

                        $network_icon = (new MidrubBaseUserAppsCollectionHelpers\Accounts)->get_network_icon($network['network_name']);

                        if ( $network_icon ) {
                            
                            $profiles[] = array(
                                'network_id' => $network['network_id'],
                                'user_name' => $network['user_name'],
                                'status' => $network['status'],
                                'network_status' => $network['network_status'],
                                'icon' => $network_icon,
                                'network_name' => ucwords( str_replace('_', ' ', $network['network_name']) )
                            );
                            
                            $networks_icon[$network['network_name']] = $network_icon;
                            
                        }

                    }

                }

            }

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
            
            $time = $get_post['time'];
            
            if ( $get_post['status'] < 1 ) {
                $time = $this->CI->lang->line('draft_post');
            }

            // Define boost variable
            $boost = array();

            // Verify if Ads Manager is enabled
            if (get_option('app_facebook_ads_enable')) {

                if ( $get_post['fb_boost_id'] ) {

                    // Get boost option by id
                    $get_boost = $this->CI->posts_model->get_boost_single($this->CI->user_id, $get_post['fb_boost_id']);

                    if ($get_boost) {

                        $boost = $get_boost;
                    }

                }

            }

            // Get post content
            $post = array(
                'post_id' => $post_id,
                'title' => $get_post['title'],
                'body' => $get_post['body'],
                'datetime' => $time,
                'time' => time(),
                'img' => $img,
                'video' => $video,
                'profiles' => $profiles,
                'boost' => $boost,
                'delete_post' => $this->CI->lang->line('delete_post')
            );
            
            $data = array(
                'success' => TRUE,
                'content' => $post
            );

            echo json_encode($data);
            
        } else {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('no_post_found')
            );

            echo json_encode($data);             
            
        }
        
    }
    
    /**
     * The public method insights_display_post_details displays the post's insights
     *
     * @since 0.0.7.4
     * 
     * @return void
     */
    public function insights_display_post_details() {
        
        // Get meta_id's input
        $meta_id = $this->CI->input->get('meta_id', TRUE);
        
        // Gets meta's data by meta_id
        $get_meta = $this->CI->posts_model->get_post_meta($meta_id);
        
        // Verify if post's meta exists
        if ( $get_meta ) {
            
            // Get post's ID
            $post_id = $get_meta[0]['post_id'];
        
            // Get post data by user id and post id
            $get_post = $this->CI->posts_model->get_post($this->CI->user_id, $post_id);

            // Verify if post exists
            if ( $get_post ) {
                
                $network_data = $this->CI->networks_model->get_account( $get_meta[0]['network_id'] );
                
                if ( $network_data ) {
                    
                    // Set default avatar
                    $user_picture = base_url('assets/img/avatar-placeholder.png');
                            
                    if ( $network_data[0]->user_avatar ) {
                        $user_picture = $network_data[0]->user_avatar;
                    }
                        
                    // Create an array
                    $array = array(
                        'MidrubBase',
                        'User',
                        'Apps',
                        'Collection',
                        'Posts',
                        'Insights',
                        ucfirst($network_data[0]->network_name)
                    );       

                    // Implode the array above
                    $cl = implode('\\',$array);

                    // Set post id
                    $network_data[0]->post_id = $get_meta[0]['published_id'];

                    // Get reactions
                    $reactions = (new $cl())->get_reactions($network_data);

                    // Get insights
                    $insights = (new $cl())->get_insights($network_data, 'post');

                    // Get configuration
                    $configuration = (new $cl())->configuration();

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

                    $time = $get_post['time'];

                    if ( $get_post['status'] < 1 ) {
                        $time = $this->CI->lang->line('draft_post');
                    } 

                    // Get post content
                    $post = array(
                        'post_id' => $post_id,
                        'title' => $get_post['title'],
                        'body' => $get_post['body'],
                        'datetime' => $time,
                        'time' => time(),
                        'img' => $img,
                        'video' => $video,
                        'user_name' => $network_data[0]->user_name,
                        'user_picture' => $user_picture,
                        'reactions' => $reactions,
                        'insights' => $insights,
                        'configuration' => $configuration
                    );

                    $data = array(
                        'success' => TRUE,
                        'content' => $post,
                        'meta_id' => $meta_id
                    );

                    echo json_encode($data);
                
                } else {
                    
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('no_post_found')
                    );

                    echo json_encode($data); 
                    
                }

            } else {

                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('no_post_found')
                );

                echo json_encode($data);             

            }
            
        } else {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('no_post_found')
            );

            echo json_encode($data);             
            
        }
        
    }
    
    /**
     * The public method order_reports_by_time generates reports
     *
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function order_reports_by_time() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('order', 'Order', 'trim|numeric|required');
            
            // Get data
            $order = $this->CI->input->post('order');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                $time = strtotime('-30 days');
                
                switch ( $order ) {
                    
                    case '1':
                        
                        $time = strtotime('-1 days');
                        
                        break;
                    
                    case '2':
                        
                        $time = strtotime('-7 days');
                        
                        break;
                    
                    case '4':
                        
                        $time = strtotime('-90 days');
                        
                        break;
                    
                }
                
                // Gets posts by time
                $get_reports = $this->CI->posts_model->get_posts_by_time($this->CI->user_id, $time);    
                
                if ( $get_reports ) {
                    
                    $data = array(
                        'success' => TRUE,
                        'reports' => $get_reports
                    );

                    echo json_encode($data);
                    exit();
                    
                }
                
            }
            
        }
            
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('no_posts_found')
        );

        echo json_encode($data);
        
    }

}

/* End of file posts.php */