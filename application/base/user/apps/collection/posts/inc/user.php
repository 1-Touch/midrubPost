<?php
/**
 * User Inc
 *
 * PHP Version 7.2
 *
 * This files contains the hooks for
 * the User's Settings from the User Panel
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */

 // Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

if ( !function_exists('posts_posting_limit') ) {

    /**
     * The function posts_posting_limit provides the posts posting limit
     * 
     * @return integer with usage's limit
     */
    function posts_posting_limit() {
        
        // Get published posts
        $published_posts = get_user_option( 'published_posts' );

        // Create $published variable
        $published = 0;

        if ( $published_posts ) {

            $posts_data = unserialize($published_posts);

            if ( ($posts_data['date'] === date('Y-m')) ) {

                $published = $posts_data['posts'];

            }

        }

        // Save value to not calculate again
        md_set_component_variable('published_posts', $published);
        
        return $published;
        
    }

}

if ( !function_exists('posts_rss_limit') ) {

    /**
     * The function posts_rss_limit provides the rss feeds usage limit
     * 
     * @return integer with usage's limit
     */
    function posts_rss_limit() {

        // Get codeigniter object instance
        $CI =& get_instance();

        // Load the app's models
        $CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Rss_model', 'rss_model' );
        
        // Get total number of rss feeds
        $rss_feeds_total = $CI->rss_model->get_rss_feeds( $CI->user_id, 0, 0, '' );
        
        if ( !$rss_feeds_total ) {
            $rss_feeds_total = 0;
        }

        // Save value to not call again the database
        md_set_component_variable('rss_feeds', $rss_feeds_total);
        
        return $rss_feeds_total;   
        
    }

}

if ( !function_exists('posts_plan_limit') ) {

    /**
     * The function posts_plan_limit provides the limit for the user's plan
     * 
     * @param string $meta_name contains the plan's meta_name
     * 
     * @return integer with plan's limit
     */
    function posts_plan_limit($meta_name) {

        return plan_feature($meta_name)?plan_feature($meta_name):'0';
        
    }

}

if ( !function_exists('posts_plan_usage_left') ) {

    /**
     * The function posts_plan_usage_left provides the plan's usage left
     * 
     * @param string $meta_name contains the plan's meta_name
     * 
     * @return integer with plan's usage left
     */
    function posts_plan_usage_left($meta_name) {

        // If should be showed number of posts left
        if ( $meta_name === 'publish_posts' ) {

            return ( posts_plan_limit('publish_posts') - md_the_component_variable('published_posts') );

        }

        // If should be showed number of rss feeds left
        if ( $meta_name === 'rss_feeds' ) {

            return ( posts_plan_limit('rss_feeds') - md_the_component_variable('rss_feeds') );

        }
        
    }

}

/**
 * Registers options for User's Settings
 * 
 * @since 0.0.7.9
 */
set_user_settings_options(

    array (
        'section_name' => $this->lang->line('posts'),
        'section_slug' => 'posts',
        'component' => false,
        'section_fields' => array (

            array (
                'type' => 'checkbox_input',
                'slug' => 'settings_character_count',
                'name' => $this->lang->line('character_count'),
                'description' => $this->lang->line('character_count_description')
            ), array (
                'type' => 'checkbox_input',
                'slug' => 'error_notifications',
                'name' => $this->lang->line('email_errors'),
                'description' => $this->lang->line('email_errors_if_enabled')
            ), array (
                'type' => 'checkbox_input',
                'slug' => 'settings_display_groups',
                'name' => $this->lang->line('display_groups'),
                'description' => $this->lang->line('display_groups_description')
            ), array (
                'type' => 'checkbox_input',
                'slug' => 'settings_posts_url_import',
                'name' => $this->lang->line('posts_enable_url_field'),
                'description' => $this->lang->line('posts_enable_url_field_description')
            ), array (
                'type' => 'checkbox_input',
                'slug' => 'settings_hashtags_suggestion',
                'name' => $this->lang->line('posts_hashtags_option'),
                'description' => $this->lang->line('posts_hashtags_option_description')
            ), array (
                'type' => 'checkbox_input',
                'slug' => 'settings_posts_parse_rss_images',
                'name' => $this->lang->line('posts_parse_rss_images'),
                'description' => $this->lang->line('posts_parse_rss_images_description')
            )

        )
        
    )

);

/**
 * The public method set_plans_usage adds plan usage to the list
 * 
 * @since 0.0.8.0
 */
set_plans_usage(

    array (

        array(
            'name' => $this->lang->line('posts'),
            'value' => posts_posting_limit(),
            'limit' => posts_plan_limit('publish_posts'),
            'left' => posts_plan_usage_left('publish_posts')
        ), array(
            'name' => $this->lang->line('posts_rss_feeds'),
            'value' => posts_rss_limit(),
            'limit' => posts_plan_limit('rss_feeds'),
            'left' => posts_plan_usage_left('rss_feeds')
        )
        
    )

);