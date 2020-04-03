<?php
/**
 * General Inc
 *
 * This file contains the general functions
 * used in the User's panel
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| DEFAULTS FUNCTIONS WHICH RETURNS DATA
|--------------------------------------------------------------------------
*/

if ( !function_exists('team_role_permission') ) {
    
    /**
     * The function team_role_permission verifies if member has permission
     * 
     * @param string $permission contains the requested permission
     * 
     * @since 0.0.7.9
     * 
     * @return boolean true or false
     */
    function team_role_permission($permission) {

        // Get codeigniter object instance
        $CI =& get_instance();

        // Verify if user is team's member
        if ( isset( $CI->session->userdata['member'] ) ) {

            // Require the Team Inc file
            require_once MIDRUB_BASE_USER . 'inc/team.php';

            // Verify if user has or no this permission
            return verify_team_role_permission($permission);

        } else {
            return true;
        }

    }

}

/*
|--------------------------------------------------------------------------
| DEFAULTS FUNCTIONS WHICH DISPLAYS DATA
|--------------------------------------------------------------------------
*/

if ( !function_exists('get_menu') ) {
    
    /**
     * The function get_menu generates a menu
     * 
     * @param string $menu_slug contains the menu's slug
     * @param array $args contains the menu's arguments
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function get_menu($menu_slug, $args) {

        // Require the Read Menu Inc file
        require_once APPPATH . 'base/inc/menu/read_menu.php';

        // Prepare menu's args
        $menu_args = array(
            'slug' => $menu_slug,
            'fields' => array(
                'selected_component',
                'permalink',
                'class'
            ),
            'language' => TRUE
        );

        // Get menu's items
        $menu_items = md_the_theme_menu($menu_args);

        // Verify if menu items exists
        if ( $menu_items ) {

            if ( isset($args['before_menu']) ) {
                echo $args['before_menu'];
            }

            // List all menu items
            foreach ( $menu_items as $menu_item ) {

                $permalink = '';

                if ( is_dir(MIDRUB_BASE_USER . 'components/collection/' . $menu_item['selected_component']. '/') && !$menu_item['permalink'] ) {
                    $permalink = site_url('user/' . $menu_item['selected_component']);
                    $cl = implode('\\', array('MidrubBase', 'User', 'Components', 'Collection', ucfirst($menu_item['selected_component']), 'Main'));
                    if (!(new $cl())->check_availability()) {
                        continue;
                    }
                } else if ( is_dir(MIDRUB_BASE_USER . 'apps/collection/' . $menu_item['selected_component']. '/') && !$menu_item['permalink'] ) {
                    $permalink = site_url('user/app/' . $menu_item['selected_component']);
                    $cl = implode('\\', array('MidrubBase', 'User', 'Apps', 'Collection', ucfirst($menu_item['selected_component']), 'Main'));
                    if (!(new $cl())->check_availability()) {
                        continue;
                    }
                }

                if ( $menu_item['permalink'] ) {
                    $permalink = $menu_item['permalink'];
                }

                $class = '';

                if ( $menu_item['class'] ) {
                    $class = ' ' . $menu_item['class'];
                }

                $active = '';

                if ( $menu_item['selected_component'] === md_the_component_variable('url_slug') ) {
                    $active = ' class="active"';
                }

                if ( isset($args['before_single_item']) ) {
                    echo str_replace(array('[class]', '[url]', '[active]'), array($class, $permalink, $active), $args['before_single_item']);
                }

                echo $menu_item['meta_value'];

                if ( isset($args['after_single_item']) ) {
                    echo $args['after_single_item'];
                }

            }

            if ( isset($args['after_menu']) ) {
                echo $args['after_menu'];
            }

        }
        
    }
    
}

if ( !function_exists('get_the_js_urls') ) {
    
    /**
     * The function get_the_js_urls gets the js links
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function get_the_js_urls() {

        md_get_the_js_urls();
        
    }
    
}

if ( !function_exists('get_the_title') ) {
    
    /**
     * The functionget_the_title gets the page's title
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function get_the_title() {

        md_get_the_title();
        
    }
    
}

if ( !function_exists('get_the_header') ) {
    
    /**
     * The function get_the_header loads the header
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function get_the_header() {

        // Call the properties class
        $hooks = (new MidrubBase\Classes\Hooks);

        // Runs a hook based on hook's name
        $hooks->run_hook('the_header', array());
        
    }
    
}

if ( !function_exists('get_the_footer') ) {
    
    /**
     * The function get_the_footer loads the header
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function get_the_footer() {

        // Call the properties class
        $hooks = (new MidrubBase\Classes\Hooks);

        // Runs a hook based on hook's name
        $hooks->run_hook('the_footer', array());
        
    }
    
}

if ( !function_exists('set_user_view') ) {
    
    /**
     * The function set_user_view sets the user's view
     * 
     * @param array $view contains the view parameters
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function set_user_view($view) {

        // Set content view
        md_set_component_variable('user_content_view', $view);
        
    }
    
}

if ( !function_exists('get_user_view') ) {
    
    /**
     * The function get_user_view gets the user view
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function get_user_view() {

        // Verify if view exists
        if ( md_the_component_variable('user_content_view') ) {

            // Display view
            echo md_the_component_variable('user_content_view');

        }
        
    }
    
}

if ( !function_exists('get_the_css_urls') ) {
    
    /**
     * The function get_the_css_urls gets the css links
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function get_the_css_urls() {

        md_get_the_css_urls();
        
    }
    
}

if ( !function_exists('get_the_file') ) {
    
    /**
     * The function get_the_file gets a file
     * 
     * @param string $file_path contains the file's path
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function get_the_file($file_path) {

        md_include_component_file($file_path);

    }
    
}

if ( !function_exists('add_hook') ) {
    
    /**
     * The function add_hook registers a hook
     * 
     * @param string $hook_name contains the hook's name
     * @param function $function contains the function to call
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function add_hook($hook, $function) {

        md_add_hook($hook, $function);

    }
    
}

if ( !function_exists('run_hook') ) {
    
    /**
     * The function run_hook runs a hook based on hook name
     * 
     * @param string $hook_name contains the hook's name
     * @param array $args contains the function's args
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function run_hook($hook_name, $args) {

        // Run a hook
        md_run_hook($hook_name, $args);
        
    }
    
}

/*
|--------------------------------------------------------------------------
| DEFAULT FUNCTIONS TO SAVE DATA
|--------------------------------------------------------------------------
*/

if ( !function_exists('set_css_urls') ) {
    
    /**
     * The function set_css_urls sets the css links
     * 
     * @param array $css_url contains the css link parameters
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function set_css_urls($css_url) {

        md_set_css_urls($css_url);
        
    }
    
}

if ( !function_exists('set_js_urls') ) {
    
    /**
     * The function set_js_urls sets the js links
     * 
     * @param array $js_url contains the js link parameters
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function set_js_urls($js_url) {

        md_set_js_urls($js_url);
        
    }
    
}

if ( !function_exists('set_the_title') ) {
    
    /**
     * The function set_the_title sets the page's title
     * 
     * @param string $title contains the title
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function set_the_title($title) {

        md_set_the_title($title);
        
    }
    
}

/*
|--------------------------------------------------------------------------
| REGISTER DEFAULT HOOKS
|--------------------------------------------------------------------------
*/

/**
 * The public method md_add_hook registers a hook
 * 
 * @since 0.0.7.9
 */
md_add_hook(
    'the_header',
    function () {

        // Get header code
        $footer = get_option('user_header_code');

        // Verify if header code exists
        if ( $footer ) {

            // Show code
            echo $footer;

        }

    }

);

/**
 * The public method md_add_hook registers a hook
 * 
 * @since 0.0.7.9
 */
md_add_hook(
    'the_footer',
    function () {

        // Get footer code
        $footer = get_option('user_footer_code');

        // Verify if footer code exists
        if ( $footer ) {

            // Show code
            echo $footer;

        }

        echo "<script src=\"" . base_url('assets/js/jquery.min.js') . "\"></script>\n";
        echo "<script src=\"" . base_url("assets/js/main.js?ver=" . MD_VER) . "\"></script>\n";

    }

);