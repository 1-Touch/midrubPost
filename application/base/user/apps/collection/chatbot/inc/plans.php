<?php
/**
 * Plans Inc
 *
 * PHP Version 7.3
 *
 * This files contains the hooks for
 * the plans in the admin's panel
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
 * The public set_plans_options registers the chatbot plans options
 * 
 * @since 0.0.7.9
 */
set_plans_options(

    array(
        'name' => $this->lang->line('chatbot'),
        'icon' => '<i class="icon-bubbles"></i>',
        'slug' => 'chatbot',
        'fields' => array(

            array (
                'type' => 'checkbox_input',
                'slug' => 'app_chatbot',
                'label' => $this->lang->line('enable_app'),
                'label_description' => $this->lang->line('if_is_enabled_chatbot_plan')
            ),
            array (
                'type' => 'text_input',
                'slug' => 'app_facebook_chatbot_replies',
                'label' => $this->lang->line('number_allowed_replies'),
                'label_description' => $this->lang->line('number_allowed_replies_description'),
                'input_type' => 'number'
            )

        )

    )

);

/* End of file plans.php */