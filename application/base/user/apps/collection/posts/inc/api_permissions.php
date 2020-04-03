<?php
/**
 * Api Permissions Inc
 *
 * PHP Version 7.2
 *
 * This files contains the hooks for
 * the Settings component from the admin Panel
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
set_admin_api_permissions(

    array (
         
        array(
            'name' => $this->lang->line('user_posts'),
            'slug' => 'user_posts',
            'description' => $this->lang->line('user_posts_description'),
            'user_allow' => $this->lang->line('user_posts_allow')
        ), array(
            'name' => $this->lang->line('user_social_accounts'),
            'slug' => 'user_social_accounts',
            'description' => $this->lang->line('user_social_accounts_description'),
            'user_allow' => $this->lang->line('user_social_accounts_allow')
        )
        
    )

);