<?php
/**
 * Widgets
 *
 * PHP Version 7.3
 *
 * Widgets Interface for Dashboard's Widgets
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Chatbot\Interfaces;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Widgets interface - allows to create default widgets for dashboard
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
interface Widgets {

    /**
     * The public method display_widget will return the widget html
     * 
     * @since 0.0.8.0
     * 
     * @param integer $user_id contains the user's id
     * @param string $plan_end contains the plan's end period time
     * @param object $plan_data contains the user's plan's data
     * 
     * @return array with widget html
     */ 
    public function display_widget( $user_id, $plan_end, $plan_data );
    
    /**
     * The public method widget_helper processes the widget content
     * 
     * @since 0.0.8.0
     * 
     * @param integer $user_id contains the user's id
     * @param string $plan_end contains the plan's end period time
     * @param object $plan_data contains the user's plan's data
     * 
     * @return array with widget's content
     */ 
    public function widget_helper( $user_id, $plan_end, $plan_data );
    
    /**
     * The public method widget_info contains the widget options
     * 
     * @since 0.0.8.0
     * 
     * @return array with widget information
     */ 
    public function widget_info();

}
