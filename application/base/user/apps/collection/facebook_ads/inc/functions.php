<?php
/**
 * Posts Functions Inc
 *
 * PHP Version 7.2
 *
 * I've created this file to store several generic 
 * functions called in the view's files
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

if ( !function_exists( 'facebook_ads_automatizations' ) ) {
    
    /**
     * The function facebook_ads_automatizations displays all available automatizations
     * 
     * @return void
     */
    function facebook_ads_automatizations() {
        
        // Verify if the automatizations directory is not empty
        if ( !empty(glob(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'automatizations/*', GLOB_ONLYDIR)) ) {

            // List all automatizations
            foreach ( glob(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'automatizations/*', GLOB_ONLYDIR) as $automatization_dir ) {

                // Get automatization's directory name
                $automatization = trim(basename($automatization_dir) . PHP_EOL);
    
                // Create an array
                $array = array(
                    'MidrubBase',
                    'User',
                    'Apps',
                    'Collection',
                    'Facebook_ads',
                    'Automatizations',
                    ucfirst($automatization),
                    'Main'
                );
    
                // Implode the array above
                $cl = implode('\\', $array);
    
                // Get automatization info
                $automatization_info = (new $cl())->automatization_info();
    
                echo '<li class="nav-item">'
                    . '<a class="nav-link" data-toggle="tab" href="#' . $automatization_info['automatization_slug'] . '" role="tab" aria-controls="boost-posts" aria-selected="false">'
                        . $automatization_info['automatization_icon']
                        . $automatization_info['display_automatization_name']
                    . '</a>'
                . '</li>';
                    
            }

        } else {

            // Get codeigniter object instance
            $CI = &get_instance();

            // Display no automatizations found message
            echo '<li class="nav-item">'
                . $CI->lang->line('no_automatizations_found')
            . '</li>';

        }
        
    }
    
}

if ( !function_exists( 'facebook_ads_automatizations_modals' ) ) {
    
    /**
     * The function facebook_ads_automatizations_modals displays all available automatizations modals
     * 
     * @return void
     */
    function facebook_ads_automatizations_modals() {
        
        // Verify if the automatizations directory is not empty
        if ( !empty(glob(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'automatizations/*', GLOB_ONLYDIR)) ) {

            // List all automatizations
            foreach ( glob(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'automatizations/*', GLOB_ONLYDIR) as $automatization_dir ) {

                // Get automatization's directory name
                $automatization = trim(basename($automatization_dir) . PHP_EOL);
                
                // Create an array
                $array = array(
                    'MidrubBase',
                    'User',
                    'Apps',
                    'Collection',
                    'Facebook_ads',
                    'Automatizations',
                    ucfirst($automatization),
                    'Main'
                );
                
                // Implode the array above
                $cl = implode('\\', $array);
                
                // Get modals
                echo (new $cl())->modals();
            }

        }
        
    }
    
}

/* End of file functions.php */