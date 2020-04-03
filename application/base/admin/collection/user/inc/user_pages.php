<?php
/**
 * User Pages Functions
 *
 * PHP Version 5.6
 *
 * This files contains the user's pages
 * methods used in admin -> user
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
 * The public method md_set_user_page adds a user's page in the admin panel
 * 
 * @since 0.0.7.9
 */
md_set_user_page(
    'components',
    array(
        'page_name' => $CI->lang->line('user_components'),
        'page_icon' => '<i class="fas fa-swatchbook"></i>',
        'content' => 'md_get_user_page_components',
        'css_urls' => array(
            array('stylesheet', base_url('assets/base/admin/collection/user/styles/css/components.css?ver=' . MIDRUB_BASE_ADMIN_USER_VERSION), 'text/css', 'all')
        ),
        'js_urls' => array(
            array(base_url('assets/base/admin/collection/user/js/components.js?ver=' . MIDRUB_BASE_ADMIN_USER_VERSION))
        )  
    )
);

if ( !function_exists('md_get_user_page_components') ) {

    /**
     * The function md_get_user_page_components gets user's page components content
     * 
     * @return void
     */
    function md_get_user_page_components() {

        // Get codeigniter object instance
        $CI = &get_instance();

        // Require the Components Inc
        require_once MIDRUB_BASE_ADMIN_USER . 'inc/components.php';

        // Verify if component exists
        if ( $CI->input->get('component', true) ) {

            // Include component view for user
            md_include_component_file(MIDRUB_BASE_ADMIN_USER . 'views/component.php');

        } else {

            // Include components view for user
            md_include_component_file(MIDRUB_BASE_ADMIN_USER . 'views/components.php');

        }
        
    }

}

/**
 * The public method md_set_user_page adds a user's page in the admin panel
 * 
 * @since 0.0.7.9
 */
md_set_user_page(
    'apps',
    array(
        'page_name' => $CI->lang->line('user_apps'),
        'page_icon' => '<i class="icon-layers"></i>',
        'content' => 'md_get_user_page_apps',
        'css_urls' => array(
            array('stylesheet', base_url('assets/base/admin/collection/user/styles/css/apps.css?ver=' . MIDRUB_BASE_ADMIN_USER_VERSION), 'text/css', 'all')
        ),
        'js_urls' => array(
            array(base_url('assets/base/admin/collection/user/js/apps.js?ver=' . MIDRUB_BASE_ADMIN_USER_VERSION))
        )  
    )
);

if ( !function_exists('md_get_user_page_apps') ) {

    /**
     * The function md_get_user_page_apps gets user's page apps content
     * 
     * @return void
     */
    function md_get_user_page_apps() {

        // Get codeigniter object instance
        $CI = &get_instance();

        // Require the Admin Apps Options Inc
        require_once MIDRUB_BASE_PATH . 'inc/apps/admin_options.php';

        // Require the Apps Inc
        require_once MIDRUB_BASE_ADMIN_USER . 'inc/apps.php';

        // Verify if app exists
        if ( $CI->input->get('app', true) ) {

            // Include app view for user
            md_include_component_file(MIDRUB_BASE_ADMIN_USER . 'views/app.php');

        } else {

            // Include apps view for user
            md_include_component_file(MIDRUB_BASE_ADMIN_USER . 'views/apps.php');

        }
        
    }

}

/**
 * The public method md_set_user_page adds a user page in the admin panel
 * 
 * @since 0.0.7.9
 */
md_set_user_page(
    'themes',
    array(
        'page_name' => $CI->lang->line('user_themes'),
        'page_icon' => '<i class="icon-grid"></i>',
        'content' => 'md_get_user_page_themes',
        'css_urls' => array(
            array('stylesheet', base_url('assets/base/admin/collection/user/styles/css/themes.css?ver=' . MIDRUB_BASE_ADMIN_USER_VERSION), 'text/css', 'all')
        ),
        'js_urls' => array(
            array(base_url('assets/base/admin/collection/user/js/themes.js?ver=' . MIDRUB_BASE_ADMIN_USER_VERSION))
        )  
    )
);

if ( !function_exists('md_get_user_page_themes') ) {

    /**
     * The function md_get_user_page_themes displays the themes page
     * 
     * @return void
     */
    function md_get_user_page_themes() {

        // Require the user themes functions
        require_once APPPATH . 'base/inc/themes/user.php';

        // Include themes view for user
        md_include_component_file(MIDRUB_BASE_ADMIN_USER . 'views/themes.php');
        
    }

}

/**
 * The public method md_set_user_page adds a user page in the admin panel
 * 
 * @since 0.0.7.9
 */
md_set_user_page(
    'menu',
    array(
        'page_name' => $CI->lang->line('user_menu'),
        'page_icon' => '<i class="icon-options"></i>',
        'content' => 'md_get_user_page_menu',
        'css_urls' => array(
            array('stylesheet', base_url('assets/base/admin/collection/user/styles/css/menu.css?ver=' . MIDRUB_BASE_ADMIN_USER_VERSION), 'text/css', 'all')
        ),
        'js_urls' => array(
            array(base_url('assets/base/admin/collection/user/js/menu.js?ver=' . MIDRUB_BASE_ADMIN_USER_VERSION))
        )  
    )
);

if ( !function_exists('md_get_user_page_menu') ) {

    /**
     * The function md_get_user_page_menu displays the menu page
     * 
     * @return void
     */
    function md_get_user_page_menu() {

        // Include menu view for user
        md_include_component_file(MIDRUB_BASE_ADMIN_USER . '/views/menu_page.php');
        
    }

}

/**
 * The public method md_set_user_page adds a user's page in the admin panel
 * 
 * @since 0.0.7.9
 */
md_set_user_page(
    'plans',
    array(
        'page_name' => $CI->lang->line('user_plans'),
        'page_icon' => '<i class="fas fa-hand-holding-usd"></i>',
        'content' => 'md_get_user_page_plans',
        'css_urls' => array(
            array('stylesheet', base_url('assets/base/admin/collection/user/styles/css/plans.css?ver=' . MIDRUB_BASE_ADMIN_USER_VERSION), 'text/css', 'all')
        ),
        'js_urls' => array(
            array(base_url('assets/base/admin/collection/user/js/plans.js?ver=' . MIDRUB_BASE_ADMIN_USER_VERSION))
        )  
    )
);

if ( !function_exists('md_get_user_page_plans') ) {

    /**
     * The function md_get_user_page_plans gets user's page plans content
     * 
     * @return void
     */
    function md_get_user_page_plans() {

        // Get codeigniter object instance
        $CI = &get_instance();

        // Verify if plan_id exists
        if ( $CI->input->get('plan_id', true) ) {

            // Include plans options
            require_once MIDRUB_BASE_PATH . 'inc/plans/options.php';

            // Require the General Plans Options Inc
            require_once MIDRUB_BASE_ADMIN_USER . 'inc/general_plans_options.php';

            // List all user's apps
            foreach (glob(APPPATH . 'base/user/apps/collection/*', GLOB_ONLYDIR) as $directory) {

                // Get the directory's name
                $app = trim(basename($directory) . PHP_EOL);

                // Create an array
                $array = array(
                    'MidrubBase',
                    'User',
                    'Apps',
                    'Collection',
                    ucfirst($app),
                    'Main'
                );

                // Implode the array above
                $cl = implode('\\', $array);

                // Load the hooks
                (new $cl())->load_hooks('admin_init');

            }

            // Include plan view for user
            md_include_component_file(MIDRUB_BASE_ADMIN_USER . 'views/plan.php');

        } else {

            // Include plans view for user
            md_include_component_file(MIDRUB_BASE_ADMIN_USER . 'views/plans.php');

        }
        
    }

}

/**
 * The public method md_set_user_page adds a user's page in the admin panel
 * 
 * @since 0.0.7.9
 */
md_set_user_page(
    'networks',
    array(
        'page_name' => $CI->lang->line('user_networks'),
        'page_icon' => '<i class="far fa-share-square"></i>',
        'content' => 'md_get_user_page_networks',
        'css_urls' => array(
            array('stylesheet', base_url('assets/base/admin/collection/user/styles/css/networks.css?ver=' . MIDRUB_BASE_ADMIN_USER_VERSION), 'text/css', 'all')
        ),
        'js_urls' => array(
            array(base_url('assets/base/admin/collection/user/js/networks.js?ver=' . MIDRUB_BASE_ADMIN_USER_VERSION))
        )  
    )
);

if ( !function_exists('md_get_user_page_networks') ) {

    /**
     * The function md_get_user_page_networks gets user's page networks content
     * 
     * @return void
     */
    function md_get_user_page_networks() {

        // Get codeigniter object instance
        $CI = &get_instance();

        // Require the Networks Inc
        require_once MIDRUB_BASE_ADMIN_USER . 'inc/networks.php';

        // Verify if network exists
        if ( $CI->input->get('network', true) ) {

            // Include network view for user
            md_include_component_file(MIDRUB_BASE_ADMIN_USER . 'views/network.php');

        } else {

            // Include networks view for user
            md_include_component_file(MIDRUB_BASE_ADMIN_USER . 'views/networks.php');

        }
        
    }

}

/**
 * The public method md_set_user_page adds a user's page in the admin panel
 * 
 * @since 0.0.7.9
 */
md_set_user_page(
    'settings',
    array(
        'page_name' => $CI->lang->line('user_settings'),
        'page_icon' => '<i class="icon-settings"></i>',
        'content' => 'md_get_user_page_settings',
        'css_urls' => array(
            array('stylesheet', base_url('assets/base/admin/collection/user/styles/css/settings.css?ver=' . MIDRUB_BASE_ADMIN_USER_VERSION), 'text/css', 'all')
        ),
        'js_urls' => array(
            array(base_url('assets/base/admin/collection/user/js/settings.js?ver=' . MIDRUB_BASE_ADMIN_USER_VERSION))
        )  
    )
);

if ( !function_exists('md_get_user_page_settings') ) {

    /**
     * The function md_get_user_page_settings gets user's page settings content
     * 
     * @return void
     */
    function md_get_user_page_settings() {

        // Get codeigniter object instance
        $CI = &get_instance();

        // Verify which tab should be displayed
        if ( $CI->input->get('section', true) === 'footer' ) {

            // Include footer settings view for user
            md_include_component_file(MIDRUB_BASE_ADMIN_USER . '/views/settings/footer.php');

        } else {

            // Include header settings view for user
            md_include_component_file(MIDRUB_BASE_ADMIN_USER . '/views/settings/header.php');

        }
        
    }

}