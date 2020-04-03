<?php
/**
 * Cron Controller
 *
 * This file loads the Posts's cron job commands
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.4
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Posts\Controllers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Require the functions file
require_once MIDRUB_BASE_USER_APPS_POSTS . 'inc/functions.php';

/*
 * Cron class loads the app's cron job commands
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.4
 */
class Cron {
    
    /**
     * Class variables
     *
     * @since 0.0.7.4
     */
    protected $CI, $social_accounts = array();

    /**
     * Initialise the Class
     *
     * @since 0.0.7.4
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Load URL Helper
        $this->CI->load->helper('url');
        
        // Load Networks Model
        $this->CI->load->model('networks');
        
        // Load Lists Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Lists_model', 'lists_model' );
        
        // Load language
        $this->CI->lang->load( 'posts_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_POSTS);

        // Load the app's models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Posts_model', 'posts_model' );
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Rss_model', 'rss_model' );
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Networks_model', 'networks_model' );
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Rss_accounts_model', 'rss_accounts_model' );
        
        if ( !empty($this->CI->all_user_options) ) {
            unset($this->CI->all_user_options);
        }
        
        if ( !empty($this->CI->plan_features) ) {
            unset($this->CI->plan_features);
        }
        
    }
    
    /**
     * The public method publish_scheduled publishes scheduled posts
     * 
     * @since 0.0.7.4
     * 
     * @return void
     */
    public function publish_scheduled() {
        
        $limit = 1;
        
        // Get the scheduling interval time
        $sleep = get_option( 'schedule_interval_limit' );
        
        if ( !$sleep ) {
            
            $sleep = 5;
            
        }
        
        // Get all posts which must be published
        $must_be_published = $this->CI->posts_model->get_all_scheduled_posts($limit);
        
        if ( $must_be_published ) {
            
            $num = 0;
            
            foreach ($must_be_published as $post) {
                $post_id = $post['post_id'];
                $body = $post['body'];
                $title = $post['title'];
                $url = $post['url'];
                $img = $post['img'];
                $video = $post['video'];
                $user_id = $post['user_id'];
                $category = $post['category'];
                
                // Verify if any image exists
                if ( !$img ) {
                    $img = array();
                }
                
                // Verify if any video exists
                if ( !$video ) {
                    $video = array();
                }
                
                // Get number of published posts in this month for the user
                $posts_published = get_user_option('published_posts', $user_id);
                
                // Get the user plan
                $plan_id = get_user_option('plan', $user_id);
                
                // Then verify how many posts can publish the user for the current plan
                $published_limit = plan_feature('publish_posts', $plan_id);
                
                // Set status publish
                $this->CI->posts_model->change_scheduled_to_publish($post_id);
                
                if ( $posts_published ) {
                    
                    $posts_published = unserialize($posts_published);
                    
                    if ( ($posts_published ['date'] == date('Y-m')) && ( $published_limit <= $posts_published ['posts']) ) {
                        
                        $num ++;
                        
                        continue;
                        
                    }
                    
                }
                
                // Verify if the post must be published in a group
                if ( is_numeric($category) ) {
                    
                    // get all group's social accounts
                    $networks = $this->CI->lists_model->get_lists_meta($user_id, $category);
                    
                    $pub = 0;
                    
                    if ( $networks ) {
                        
                        foreach ($networks as $network) {
                            
                            $body2 = $body;
                            
                            $title2 = $title;
                            
                            if ( get_user_option('use_spintax_posts', $user_id) ) {
                                
                                if ( in_array($network->network_name, $this->social_accounts) ) {
                                    
                                    $body2 = $this->CI->ecl('Deco')->lsd($body2, $user_id);
                                    
                                    if ( $title2 ) {
                                        
                                        $title2 = $this->CI->ecl('Deco')->lsd($title2, $user_id);
                                        
                                    }
                                    
                                } else {
                                    
                                    $this->social_accounts[] = $network->network_name;
                                    
                                }
                                
                            }
                            
                            $args = [
                                'post' => $body2,
                                'title' => $title2,
                                'network' => $network->network_name,
                                'account' => $network->network_id,
                                'url' => $url,
                                'img' => get_post_media_array($user_id, unserialize($img) ),
                                'video' => get_post_media_array($user_id, unserialize($video) ),
                                'category' => '',
                                'id' => $post_id
                            ];
                            
                            // Verify if post already has the meta
                            if ( $this->CI->networks_model->if_posts_has_the_meta($post_id, $network->network_id) ) {
                                
                                continue;
                                
                            }
                            
                            $check_pub = publish_post($args, $user_id);

                            // Publish post and check if was published succesfully
                            if ( $check_pub ) {
                                
                                if ( $check_pub === true ) {
                                    $check_pub = 0;
                                }
                                
                                // Save the post meta
                                $this->CI->posts_model->save_post_meta($post_id, $network->network_id, $network->network_name, 1, $user_id, $check_pub);
                                $pub++;
                                
                            } else {
                                
                                // If the post wasn't published successfully, will be send a notification to user
                                // First we need to check if user want to receive notification about post errors on email
                                $options = $this->CI->user_meta->get_all_user_options($user_id);
                                
                                if ( isset($options ['error_notifications']) ) {
                                    
                                    $this->CI->notifications->send_notification($user_id, 'error-sent-notification');
                                    
                                }
                                
                                $this->CI->posts->save_post_meta($post_id, $network->network_id, $network->network_name, 2, $user_id);
                                
                            }
                            
                            sleep($sleep);
                            
                        }
                        
                    }
                    
                } else {
                    
                    // Get all networks where the post must be published
                    $networks = $this->CI->posts_model->all_social_networks_by_post_id($user_id, $post_id);
                    
                    $pub = 0;
                    
                    if ( $networks ) {
                        
                        foreach ($networks as $network) {
                            
                            $body2 = $body;
                            
                            $title2 = $title;
                            
                            if ( get_user_option( 'use_spintax_posts', $user_id ) ) {
                                
                                if ( in_array($network['network_name'], $this->social_accounts) ) {
                                    
                                    $body2 = $this->CI->ecl('Deco')->lsd($body2, $user_id);
                                    
                                    if ( $title2 ) {
                                        
                                        $title2 = $this->CI->ecl('Deco')->lsd($title2, $user_id);
                                        
                                    }
                                    
                                } else {
                                    
                                    $this->social_accounts[] = $network['network_name'];
                                    
                                }
                                
                            }
                            
                            $args = [
                                'post' => $body2,
                                'title' => $title2,
                                'network' => $network['network_name'],
                                'account' => $network['network_id'],
                                'url' => $url,
                                'img' => get_post_media_array($user_id, unserialize($img) ),
                                'video' => get_post_media_array($user_id, unserialize($video) ),
                                'category' => $category,
                                'id' => $post_id
                            ];
                            
                            $this->CI->posts_model->update_post_meta($network['meta_id'], 1, $user_id);
                            
                            $check_pub = publish_post($args, $user_id);
                            
                            // Publish post and check if was published succesfully
                            if ( $check_pub ) {
                                
                                if ( $check_pub === true ) {
                                    $check_pub = 0;
                                }
                                
                                $this->CI->posts_model->update_post_meta($network['meta_id'], 1, $user_id, $check_pub);
                                $pub++;
                                
                            } else {
                                
                                // If the post wasn't published successfully, will be send a notification to user
                                // First we need to check if user want to receive notification about post errors on email
                                $options = $this->CI->user_meta->get_all_user_options($user_id);
                                
                                $this->CI->posts_model->update_post_meta($network ['meta_id'], 2, $user_id);
                                
                            }
                            
                            sleep($sleep);
                            
                        }
                        
                    }
                    
                }
                
                if ( $pub > 0 ) {
                    
                    // A new post was published successfully in this month
                    set_post_number($user_id);
                    
                }
                
                $num++;
                
            }
            
        }
        
    }
    
    /**
     * The public method publish_rss_posts publishes RSS's posts
     * 
     * @since 0.0.7.4
     * 
     * @return void
     */
    public function publish_rss_posts() {
        
        // Load RSS Helper
        $this->CI->load->helper('fifth_helper');
        
        // Set publish limit
        $limit = 1;
        
        // Get the scheduling interval time
        $sleep = get_option( 'schedule_interval_limit' );
        
        if ( !$sleep ) {
            
            $sleep = 5;
            
        }
            
        // Get number of RSS to process
        $rss_process_limit = get_option( 'rss_process_limit' );
    
        if ( !is_numeric($rss_process_limit) ) {
            
            $rss_process_limit = 1;
            
        }
        
        for ( $pr = 0; $pr < $rss_process_limit; $pr++ ) {
            
            // Check if the session exists and if the login user is admin
            $random = $this->CI->rss_model->get_random_rss();
            
            // Check if RSS Feeds support is enabled
            if ( !get_option('app_posts_rss_feeds') ) {
                return false;
            }
            
            // Verify if any RSS's url was found
            if ( !$random ) {
                $this->CI->rss_model->reset_rss();
                return false;
            }
            
            // Verify if the RSS's posts could be published automatically
            if ( (int)$random[0]->pub > 0 ) {
                continue;
            }

            $posts_published = get_user_option('published_posts', $random[0]->user_id);
            
            // Get the user plan
            $plan_id = get_user_option('plan', $random[0]->user_id);

            // Then verify how many posts can publish the user for the current plan
            $published_limit = plan_feature('publish_posts', $plan_id);

            if ( $posts_published ) {
                
                $posts_published = unserialize($posts_published);
                
                if ( ( $posts_published ['date'] === date('Y-m')) && ( $published_limit <= $posts_published ['posts']) ) {
                    
                    $this->CI->rss_model->set_completed($random[0]->rss_id, 1);
                    continue;
                    
                }
                
            }

            if ( $this->calculate_publish_interval($random) ) {
                
                $this->CI->rss_model->set_completed($random[0]->rss_id, 1);
                continue;
                
            }
          
            if ( @$random[0]->rss_url ) {
                
                if ( $random[0]->keep_html ) {
                    
                    $parsed = parse_rss_feed($random[0]->rss_url, 1);
                    
                } else {
                    
                    $parsed = parse_rss_feed($random[0]->rss_url);
                
                }
                
                if ( @$parsed ) {
                    
                    $f = 0;
                    
                    for ( $n = 0; $n < count($parsed['title']); $n++ ) {
                        
                        if ( $limit <= $f) {
                            break;
                        }
                        
                        $description = '';
                        
                        $title = trim($parsed['title'][$n]);

                        if ( $random[0]->publish_description === '1' ) {
                            
                            $description = $parsed['description'][$n];
                            
                        }
                        
                        $url = $parsed['url'][$n];
                        
                        if ( $url ) {
                            
                            if ( preg_match('/amazon./i', $url) ) {
                                
                                $url = explode('ref=', $url);
                                $url = $url [0];
                                
                            }
                            
                            if ( preg_match('/news.google/i', $url) ) {
                                
                                $url = explode('&url=', $url);
                                $url = (@$url[1]) ? $url[1] : $url[0];
                                
                            }
                            
                            $url2 = $url;
                            
                            // Verify if the Feed RSS has a refferal
                            if ( $random [0]->refferal ) {

                                $refferal = str_replace(array('&', '?', '*'), array('', '', '&'), $random[0]->refferal);

                                if ( preg_match('/\?/i', $url2) ) {

                                    $url2 = $url2 . '&' . $refferal;

                                } else {

                                    $url2 = $url2 . '?' . $refferal;

                                }

                            }

                            // Verify if we have to exclude some posts
                            if ( @trim($random[0]->include) ) {

                                $include = $random[0]->include;
                                $g = 0;

                                $exn = explode(',', $include);
                                foreach ( $exn as $ex ) {

                                    if ( preg_match('/' . $ex . '/i', $title) ) {

                                        $g++;
                                    }

                                    if ( preg_match('/' . $ex . '/i', $description) ) {

                                        $g++;
                                    }

                                }

                                // If $g is 0 means no required words found
                                if ( $g < 1 ) {

                                    continue;
                                }

                            }

                            if ( @trim($random[0]->exclude) ) {

                                $exclude = $random[0]->exclude;
                                $w = 0;

                                $exc = explode(',', $exclude);

                                foreach ( $exc as $ex ) {

                                    if ( preg_match('/' . $ex . '/i', $title) ) {

                                        $w++;
                                    }

                                    if ( preg_match('/' . $ex . '/i', $description) ) {

                                        $w++;
                                    }

                                }

                                // If $g is 0 means no required words found
                                if ( $w > 0 ) {

                                    continue;

                                }

                            }
                            
                            $image = '';
                            $img = '';

                            if ( $random[0]->type ) {

                                if ( get_user_option('settings_posts_parse_rss_images', $random[0]->user_id ) ) {

                                    // Get the Facebook App ID
                                    $app_id = get_option('facebook_app_id');

                                    // Get the Facebook App Secret
                                    $app_secret = get_option('facebook_app_secret');

                                    // Verify if user uses default Facebook's classes
                                    if (!$app_id && !$app_secret) {

                                        // Get the Facebook App ID
                                        $app_id = get_option('facebook_profiles_app_key');

                                        // Get the Facebook App Secret
                                        $app_secret = get_option('facebook_profiles_app_secret');
                                    }

                                    if ($app_id && $app_secret) {

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

                                        if (isset($get_token['access_token'])) {

                                            // Get content
                                            $curl = curl_init();
                                            curl_setopt($curl, CURLOPT_URL, 'https://graph.facebook.com/');
                                            curl_setopt($curl, CURLOPT_POST, 1);
                                            curl_setopt($curl, CURLOPT_POSTFIELDS, 'id=' . urlencode($url2) . '&scrape=true&access_token=' . $get_token['access_token']);
                                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
                                            $get_content = json_decode(curl_exec($curl), true);
                                            curl_close($curl);

                                            // Verify if image exists
                                            if (isset($get_content['image'][0]['url'])) {
                                                $image = $get_content['image'][0]['url'];
                                            }

                                        }

                                    }

                                }

                                if ( !$image ) {

                                    if (@$parsed['show'][$n]) {

                                        $image = $parsed['show'][$n];

                                    }

                                }

                            }

                            // Create teporary variables for title and description
                            $title2 = $title;

                            $description2 = $description;
                            
                            // Current time
                            $time = time();

                            // Try to save the post
                            $post_save = $this->CI->rss_model->save_rss_post( $random[0]->user_id, $random[0]->rss_id, $url2, $time, $title2, $description2, $image, 1);
                            
                            // Verify if the post was saved
                            if ($post_save) {

                                $net = array();

                                if ( get_user_option('settings_display_groups', $random[0]->user_id ) ) {

                                    // Load Lists Model
                                    $this->CI->load->model('lists');

                                    if ( $random[$n]->group_id < 0 ) {

                                        // Try to disable the RSS's Feed if no group selected
                                        $this->CI->rss_model->update_rss_meta($random[0]->rss_id, 'enabled', 0);
                                        continue;

                                    }

                                    $rss_accounts = $this->CI->lists_model->get_lists_meta($random[0]->user_id, $random[0]->group_id);
                                    
                                    // Verify if user want to make original the content
                                    if ( get_user_option( 'use_spintax_rss', $random[0]->user_id ) ) {

                                        if ( in_array($random[0]->network_name, $this->social_accounts) ) {

                                            $description2 = $this->CI->ecl('Deco')->lsd($description2, $random[0]->user_id);

                                            if ( $title2 ) {

                                                $title2 = $this->CI->ecl('Deco')->lsd($title2, $random[0]->user_id);

                                            }

                                        } else {

                                            $this->social_accounts[] = $random[0]->network_name;

                                        }

                                    }

                                    if ( $rss_accounts ) {

                                        foreach ( $rss_accounts as $rss_account ) {

                                            $args = array(
                                                'post' => html_entity_decode(stripslashes($description2)),
                                                'title' => html_entity_decode(stripslashes($title2)),
                                                'network' => $rss_account->network_name,
                                                'account' => $rss_account->network_id,
                                                'url' => $url2,
                                                'img' => $img,
                                                'video' => '',
                                                'category' => ''
                                            );
                                            
                                            if ( $random[0]->type ) {

                                                if ( @$parsed['show'][$n] ) {

                                                    $args['img'] = array(
                                                        
                                                        array(
                                                            'body' => $parsed['show'][$n]
                                                        )
                                                        
                                                    );

                                                }

                                            }
                                            
                                            if ( $random[0]->remove_url ) {
                                                $args['url'] = '';
                                            }

                                            $check_pub = publish_post($args, $random[0]->user_id);

                                            if ( $check_pub ) {

                                                if ( $check_pub === true ) {
                                                    $check_pub = 0;
                                                }

                                                $this->CI->rss_model->save_post_meta($post_save, $rss_account->network_id, $rss_account->network_name, 1, $random[0]->user_id, $check_pub);

                                                // A new post was published successfully in this month
                                                set_post_number($random[0]->user_id);
                                                
                                                $f++;
                                                
                                                sleep($sleep);

                                            } else {

                                                $this->CI->rss_model->save_post_meta($post_save, $rss_account->network_id, $rss_account->network_name, 2, $random[0]->user_id);

                                            }

                                        }

                                    } else {

                                        // Try to disable the RSS's Feed if group doesn't have accounts
                                        $this->CI->rss_model->update_rss_meta($random[0]->rss_id, 'enabled', 0);

                                    }

                                } else {

                                    // Get RSS's accounts by RSS's ID
                                    $rss_accounts = $this->CI->rss_accounts_model->get_rss_accounts($random[0]->rss_id);

                                    if ( $rss_accounts ) {

                                        foreach ( $rss_accounts as $rss_account ) {

                                            // Verify if user want to make original the content
                                            if ( get_user_option( 'use_spintax_rss', $rss_account->user_id ) ) {

                                                if ( in_array($rss_account->network_name, $this->social_accounts) ) {

                                                    $description2 = $this->CI->ecl('Deco')->lsd($description2, $rss_account->user_id);

                                                    if ( $title2 ) {

                                                        $title2 = $this->CI->ecl('Deco')->lsd($title2, $rss_account->user_id);

                                                    }

                                                } else {

                                                    $this->social_accounts[] = $rss_account->network_name;

                                                }

                                            }

                                            $args = array(
                                                'post' => html_entity_decode(stripslashes($description2)),
                                                'title' => html_entity_decode(stripslashes($title2)),
                                                'network' => $rss_account->network_name,
                                                'account' => $rss_account->network_id,
                                                'url' => $url2,
                                                'img' => $img,
                                                'video' => '',
                                                'category' => ''
                                            );
                                            
                                            if ( $random[0]->type ) {

                                                if ( @$parsed['show'][$n] ) {

                                                    $args['img'] = array(
                                                        
                                                        array(
                                                            'body' => $parsed['show'][$n]
                                                        )
                                                        
                                                    );
                                                    
                                                }

                                            }
                                            
                                            if ( $random[0]->remove_url ) {
                                                $args['url'] = '';
                                            }

                                            $check_pub = publish_post($args, $random[0]->user_id);

                                            if ( $check_pub ) {

                                                if ( $check_pub === true ) {
                                                    $check_pub = 0;
                                                }

                                                // Save post
                                                $this->CI->rss_model->save_post_meta($post_save, $rss_account->network_id, $rss_account->network_name, 1, $random[0]->user_id, $check_pub);

                                                // A new post was published successfully in this month
                                                set_post_number($random[0]->user_id);
                                                
                                                $f++;
                                                
                                                sleep($sleep);
                                    
                                            } else {

                                                // Save post
                                                $this->CI->rss_model->save_post_meta($post_save, $rss_account->network_id, $rss_account->network_name, 2, $random[0]->user_id);

                                            }

                                        }

                                    } else {

                                        // Try to disable the RSS's Feed if no accounts selected
                                        $this->CI->rss_model->update_rss_meta($random[0]->rss_id, 'enabled', 0);

                                    }

                                }
                                
                            }
                            
                        }
                        
                    }

                    if ( $limit > $f ) {

                        $this->CI->rss_model->set_completed($random[0]->rss_id, 1);
                    }
                    
                }
                
            }
            
        }
        
    }
    
    /**
     * The public method publish_scheduled_rss_posts gets all scheduled posts and publish
     * 
     * @since 0.0.7.4
     * 
     * @return void
     */
    public function publish_scheduled_rss_posts() {
        
        // Set publish limit
        $limit = 1;
        
        // Get the scheduling interval time
        $sleep = get_option( 'schedule_interval_limit' );
        
        if ( !$sleep ) {
            
            $sleep = 5;
            
        }

        $random = $this->CI->rss_model->get_random_rss_m($limit);

        if ( $random ) {
            
            // List all found scheduled posts
            for( $n = 0; $n < count($random); $n++ ) {

                // Verify if the RSS's posts could be published manually
                if ( (int)$random[$n]['pub'] < 1 ) {
                    continue;
                }

                // Mark post as published
                $this->CI->rss_model->update_rss_post_field($random[$n]['post_id'], 'status', 1);

                // Save published time
                $this->CI->rss_model->update_rss_post_field($random[$n]['post_id'], 'published', time());

                // Get published posts
                $posts_published = get_user_option('published_posts', $random[$n]['user_id']);

                // Get the user plan
                $plan_id = get_user_option('plan', $random[$n]['user_id']);

                // Then verify how many posts can publish the user for the current plan
                $published_limit = plan_feature('publish_posts', $plan_id);

                // Verify if the post was published
                if ( $posts_published ) {

                    // Get published posts
                    $posts_published = unserialize($posts_published);

                    if ( ($posts_published['date'] == date('Y-m')) && ( (int)$published_limit <= (int)$posts_published['posts']) ) {

                        return false;

                    }

                }

                // Define the variable which will count the number of published posts
                $publish_status = 0;

                $description = '';

                $uri = $random[$n]['url'];

                if ( preg_match('/amazon./i', $uri) ) {

                    $uri = explode('ref=', $uri);
                    $uri = $uri[0];

                }

                if ( preg_match('/news.google/i', $uri) ) {

                    $uri = explode('&url=', $uri);
                    $uri = $uri[1];

                }

                $description = '';

                $title = $random[$n]['title'];

                $description = $random[$n]['content'];

                if ( !empty($random[$n]['refferal']) ) {

                    $refferal = str_replace(array('&', '?', '*'), array('', '', '*'), $random[$n]['refferal']);
                    if ( preg_match('/\?/i', $uri) ) {

                        $uri = $uri . '&' . $refferal;

                    } else {

                        $uri = $uri . '?' . $refferal;

                    }

                }
                
                $image = !empty($random[$n]['image'])?$random[$n]['image']:'';
                
                $img = '';

                if ( $image ) {

                    // Verify if user wants to publish photos instead urls
                    if ( $random[$n]['type'] ) {
                     
                        $img = array(
                            array(
                                'body' => $image
                            )
                        );
                        
                    }

                }                

                $title2 = $title;

                $description2 = $description;

                if ( get_user_option('settings_display_groups', $random[$n]['user_id']) ) {
                    
                    $rss_accounts = $this->CI->lists_model->get_lists_meta($random[$n]['user_id'], $random[$n]['group_id']);

                    if ( $rss_accounts ) {

                        foreach ( $rss_accounts as $rss_account ) {

                            if ( get_user_option( 'use_spintax_rss', $random[$n]['user_id'] ) ) {

                                if ( in_array($rss_account->network_name, $this->social_accounts) ) {

                                    $description2 = $this->CI->ecl('Deco')->lsd($description2, $random[$n]['user_id']);

                                    if ( $title2 ) {

                                        $title2 = $this->CI->ecl('Deco')->lsd($title2, $random[$n]['user_id']);

                                    }

                                } else {

                                    $this->social_accounts[] = $rss_account->network_name;

                                }

                            }

                            $args = array(
                                'post' => html_entity_decode(stripslashes($description2)),
                                'title' => html_entity_decode(stripslashes($title2)),
                                'network' => $rss_account->network_name,
                                'account' => $rss_account->network_id,
                                'url' => $uri,
                                'img' => $img,
                                'video' => '',
                                'category' => ''
                            );

                            $check_pub = publish_post($args, $random[$n]['user_id']);

                            if ( $check_pub ) {

                                if ( $check_pub === true ) {
                                    $check_pub = 0;
                                }

                                $this->CI->rss_model->save_post_meta($random[$n]['post_id'], $rss_account->network_id, $rss_account->network_name, 1, $random[$n]['user_id'], $check_pub);

                                $publish_status++;
                                
                                sleep($sleep);

                            } else {

                                $this->CI->rss_model->save_post_meta($random[$n]['post_id'], $rss_account->network_id, $rss_account->network_name, 2, $random[$n]['user_id']);

                            }

                        }

                    }

                } else {

                    // Get RSS's accounts by RSS's ID
                    $rss_accounts = $this->CI->rss_accounts_model->get_rss_accounts($random[$n]['rss_id']);

                    if ( $rss_accounts ) {

                        foreach ( $rss_accounts as $rss_account ) {

                            if ( get_user_option( 'use_spintax_rss', $random[$n]['user_id'] ) ) {

                                if ( in_array($rss_account->network_name, $this->social_accounts) ) {

                                    $description2 = $this->CI->ecl('Deco')->lsd($description2, $random[$n]['user_id']);

                                    if ( $title2 ) {

                                        $title2 = $this->CI->ecl('Deco')->lsd($title2, $random[$n]['user_id']);

                                    }

                                } else {

                                    $this->social_accounts[] = $rss_account->network_name;

                                }

                            }

                            $args = array(
                                'post' => html_entity_decode(stripslashes($description2)),
                                'title' => html_entity_decode(stripslashes($title2)),
                                'network' => $rss_account->network_name,
                                'account' => $rss_account->network_id,
                                'url' => $uri,
                                'img' => $img,
                                'video' => '',
                                'category' => ''
                            );

                            $check_pub = publish_post($args, $random[$n]['user_id']);

                            if ( $check_pub ) {

                                if ( $check_pub === true ) {
                                    $check_pub = 0;
                                }

                                $this->CI->rss_model->save_post_meta($random[$n]['post_id'], $rss_account->network_id, $rss_account->network_name, 1, $random[$n]['user_id'], $check_pub);

                                $publish_status++;
                                                               
                                sleep($sleep);

                            } else {

                                $this->CI->rss_model->save_post_meta($random[$n]['post_id'], $rss_account->network_id, $rss_account->network_name, 2, $random[$n]['user_id']);

                            }

                        }

                    }

                }
                
                if ( $publish_status ) {
                    
                    // A new post was published successfully in this month
                    set_post_number($random[$n]['user_id']);
                    
                }
                
            }
            
        }
        
    }
    
    /**
     * The public method publish_rss_posts publishes RSS's posts
     * 
     * @since 0.0.7.4
     * 
     * @return void
     */
    private function calculate_publish_interval($rss) {
        
        // Calculate new time
        if ( is_numeric($rss[0]->period) ) {
            
            $time = time() + ($rss[0]->period * 60);
            
        } else {
            
            $time = time();
            
        }
        
        // Verify if is time to publish a new post
        if ( $rss[0]->updated) {
            
            if ( $rss[0]->updated < time() ) {
                
                $this->CI->rss_model->update_rss_meta($rss[0]->rss_id, 'updated', $time);
                return false;
                
            } else {
                
                return true;
                
            }
            
        } else {
            
            $this->CI->rss_model->update_rss_meta($rss[0]->rss_id, 'updated', $time);
            return false;
            
        }
        
    }

}
