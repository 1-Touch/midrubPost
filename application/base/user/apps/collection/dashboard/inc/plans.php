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
        'name' => $this->lang->line('dashboard'),
        'icon' => '<i class="icon-speedometer"></i>',
        'slug' => 'dashboard',
        'fields' => array(
            array (
                'type' => 'checkbox_input',
                'slug' => 'app_dashboard',
                'label' => $this->lang->line('enable_app'),
                'label_description' => $this->lang->line('if_is_enabled_plan')
            )
        )
    )
    
);
