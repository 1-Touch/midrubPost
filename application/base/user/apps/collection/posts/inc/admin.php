<?php
/**
 * Admin Inc
 *
 * PHP Version 7.2
 *
 * This files contains the hooks for
 * the User component from the admin Panel
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */

 // Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * The public method set_admin_app_options registers options for Admin's
 * 
 * @since 0.0.7.9
 */
set_admin_app_options(

    array (
         
        array (
            'type' => 'checkbox_input',
            'slug' => 'app_posts_enable',
            'label' => $this->lang->line('enable_app'),
            'label_description' => $this->lang->line('if_is_enabled')
        ), array (
            'type' => 'checkbox_input',
            'slug' => 'app_posts_enable_composer',
            'label' => $this->lang->line('enable_app_composer'),
            'label_description' => $this->lang->line('if_is_enabled_composer')
        ), array (
            'type' => 'checkbox_input',
            'slug' => 'app_posts_enable_scheduled',
            'label' => $this->lang->line('enable_app_scheduled'),
            'label_description' => $this->lang->line('if_is_enabled_scheduled')
        ), array (
            'type' => 'checkbox_input',
            'slug' => 'app_posts_enable_insights',
            'label' => $this->lang->line('enable_app_insights'),
            'label_description' => $this->lang->line('if_is_enabled_insights')
        ), array (
            'type' => 'checkbox_input',
            'slug' => 'app_posts_enable_history',
            'label' => $this->lang->line('enable_app_history'),
            'label_description' => $this->lang->line('if_is_enabled_history')
        ), array (
            'type' => 'checkbox_input',
            'slug' => 'app_posts_rss_feeds',
            'label' => $this->lang->line('enable_rss_feeds'),
            'label_description' => $this->lang->line('if_is_enabled_rss_feeds')
        ), array (
            'type' => 'checkbox_input',
            'slug' => 'app_posts_enable_faq',
            'label' => $this->lang->line('enable_rss_faq'),
            'label_description' => $this->lang->line('if_is_enable_rss_faq')
        ), array (
            'type' => 'checkbox_input',
            'slug' => 'app_posts_enable_dropbox',
            'label' => $this->lang->line('enable_dropbox_picker'),
            'label_description' => $this->lang->line('if_is_enabled_dropbox')
        ), array (
            'type' => 'text_input',
            'slug' => 'app_posts_dropbox_app_key',
            'label' => $this->lang->line('dropbox_appkey'),
            'label_description' => $this->lang->line('if_is_enabled_dropbox_appkey')
        ), array (
            'type' => 'checkbox_input',
            'slug' => 'app_posts_enable_pixabay',
            'label' => $this->lang->line('enable_pixabay'),
            'label_description' => $this->lang->line('if_is_enable_pixabay')
        ), array (
            'type' => 'text_input',
            'slug' => 'app_post_pixabay_api_key',
            'label' => $this->lang->line('pixabay_api_key'),
            'label_description' => $this->lang->line('if_is_enabled_pixabay_api_key')
        ), array (
            'type' => 'checkbox_input',
            'slug' => 'app_posts_enable_url_download',
            'label' => $this->lang->line('enable_url_download'),
            'label_description' => $this->lang->line('enable_url_download_description')
        ), array (
            'type' => 'text_input',
            'slug' => 'app_post_designbold_api_id',
            'label' => $this->lang->line('designbold_api_id'),
            'label_description' => $this->lang->line('designbold_api_id_description')
        ), array (
            'type' => 'checkbox_input',
            'slug' => 'app_posts_enable_designbold_button',
            'label' => $this->lang->line('enable_designbold_button'),
            'label_description' => $this->lang->line('enable_designbold_button_description')
        )
        
    )

);