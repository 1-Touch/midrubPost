<?php
/**
 * Admin Init Hooks
 *
 * PHP Version 5.6
 *
 * This files contains the hooks loaded
 * in the Midrub's admin panel
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */

 // Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Get codeigniter object instance
$CI = &get_instance();

/**
 * The public method md_set_user_menu registers a new menu
 * 
 * @since 0.0.7.9
 */
md_set_user_menu(
    'user_left_menu',
    array(
        'name' => $CI->lang->line('theme_user_left_menu')   
    )    
);

/**
 * The public method md_set_user_menu registers a new menu
 * 
 * @since 0.0.7.9
 */
md_set_user_menu(
    'user_top_menu',
    array(
        'name' => $CI->lang->line('theme_user_top_menu')   
    )    
);