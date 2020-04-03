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
            'slug' => 'app_storage_enable',
            'label' => $this->lang->line('enable_app'),
            'label_description' => $this->lang->line('if_is_enabled_storage')
        ), array (
            'type' => 'checkbox_input',
            'slug' => 'app_storage_enable_url_download',
            'label' => $this->lang->line('enable_url_download'),
            'label_description' => $this->lang->line('enable_url_download_description')
        )
        
    )

);