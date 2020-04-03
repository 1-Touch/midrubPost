<?php
/**
 * Functions Inc
 *
 * PHP Version 7.2
 *
 * This files contains the functions used
 * in the view's files
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */

 // Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

if ( !function_exists('display_plan_usage') ) {
    
    /**
     * The function display_plan_usage displays plan's usage
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    function display_plan_usage() {

        // Get plan's usage
        $plan_usages = the_plans_usage();

        // Verify if plan's usages exists
        if ( $plan_usages ) {

            foreach ( $plan_usages as $plan_usage ) {

                // Get usage's left
                $usage_left = number_format((100 - (($plan_usage['limit'] - $plan_usage['value']) / $plan_usage['limit']) * 100));
        
                // Get processbar color
                if ( $usage_left < 90 ) {
                    $color = ' bg-success';
                } else {
                    $color = ' bg-danger';
                }

                echo '<li>'
                    . '<div class="row">'
                        . '<div class="col-xl-9 col-sm-8 col-6">'
                            . $plan_usage['name']
                        . '</div>'
                        . '<div class="col-xl-3 col-sm-4 col-6 text-right">'
                            . $plan_usage['left']
                        . '</div>'
                    . '</div>'
                    . '<div class="progress">'
                        . '<div class="progress-bar' . $color . '" role="progressbar" style="width: ' . $usage_left . '%" aria-valuenow="' . $usage_left . '" aria-valuemin="0" aria-valuemax="100"></div>'
                    . '</div>'
                . '</li>';

            }

        }
        
    }
    
}