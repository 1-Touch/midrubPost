<?php
/**
 * Plans Inc
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
 * The public set_plans_options registers the dashboard plans options
 * 
 * @since 0.0.7.9
 */
set_plans_options(

    array(
        'name' => $this->lang->line('posts'),
        'icon' => '<i class="icon-layers"></i>',
        'slug' => 'posts',
        'fields' => array(

            array (
                'type' => 'checkbox_input',
                'slug' => 'app_posts',
                'label' => $this->lang->line('enable_app'),
                'label_description' => $this->lang->line('if_is_enabled_plan')
            ), array (
                'type' => 'text_input',
                'slug' => 'publish_posts',
                'label' => $this->lang->line('number_published_posts_month'),
                'label_description' => $this->lang->line('number_published_posts_month_description'),
                'input_type' => 'number'
            ), array (
                'type' => 'text_input',
                'slug' => 'rss_feeds',
                'label' => $this->lang->line('number_allowed_rss'),
                'label_description' => $this->lang->line('number_allowed_rss_description'),
                'input_type' => 'number'
            )

        )

    )

);
