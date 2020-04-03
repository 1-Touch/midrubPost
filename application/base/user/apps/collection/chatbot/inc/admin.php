<?php
/**
 * Admin Inc
 *
 * PHP Version 7.3
 *
 * This files contains the hooks for
 * the app displayed in the admin Panel
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
 * @since 0.0.8.0
 */
set_admin_app_options(

    array (

        array (
            'type' => 'checkbox_input',
            'slug' => 'app_chatbot_enable',
            'label' => $this->lang->line('enable_app'),
            'label_description' => $this->lang->line('if_is_enabled_chatbot')
        ), array (
            'type' => 'checkbox_input',
            'slug' => 'app_chatbot_enable_activity',
            'label' => $this->lang->line('activity_widget'),
            'label_description' => $this->lang->line('activity_widget_description')
        ), array (
            'type' => 'checkbox_input',
            'slug' => 'app_chatbot_enable_history',
            'label' => $this->lang->line('history_widget'),
            'label_description' => $this->lang->line('history_widget_description')
        ), array (
            'type' => 'text_input',
            'slug' => 'app_facebook_chatbot_verify_token',
            'label' => $this->lang->line('app_token'),
            'label_description' => $this->lang->line('app_token_description')
        )
        
    )

);

/* End of file admin.php */