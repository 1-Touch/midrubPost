<?php
/**
 * Frontend Pages Functions
 *
 * PHP Version 5.6
 *
 * This files contains the frontend's pages
 * methods used in admin -> frontend
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
 * The public method md_set_frontend_page adds a frontend page in the admin panel
 * 
 * @since 0.0.7.8
 */
md_set_frontend_page(
    'themes',
    array(
        'page_name' => $CI->lang->line('frontend_themes'),
        'page_icon' => '<i class="icon-grid"></i>',
        'content' => 'md_get_frontend_page_themes',
        'css_urls' => array(
            array('stylesheet', base_url('assets/base/admin/collection/frontend/styles/css/themes.css?ver=' . MIDRUB_BASE_ADMIN_FRONTEND_VERSION), 'text/css', 'all')
        ),
        'js_urls' => array(
            array(base_url('assets/base/admin/collection/frontend/js/themes.js?ver=' . MIDRUB_BASE_ADMIN_FRONTEND_VERSION))
        )  
    )
);

if ( !function_exists('md_get_frontend_page_themes') ) {

    /**
     * The function md_get_frontend_page_themes displays the themes page
     * 
     * @return void
     */
    function md_get_frontend_page_themes() {

        // Require the frontend themes functions
        require_once APPPATH . 'base/inc/themes/frontend.php';

        // Include themes view for frontend
        md_include_component_file(MIDRUB_BASE_ADMIN_FRONTEND . 'views/themes.php');
        
    }

}

/**
 * The public method md_set_frontend_page adds a frontend page in the admin panel
 * 
 * @since 0.0.7.8
 */
md_set_frontend_page(
    'menu',
    array(
        'page_name' => $CI->lang->line('frontend_menu'),
        'page_icon' => '<i class="icon-options"></i>',
        'content' => 'md_get_frontend_page_menu',
        'css_urls' => array(
            array('stylesheet', base_url('assets/base/admin/collection/frontend/styles/css/menu.css?ver=' . MIDRUB_BASE_ADMIN_FRONTEND_VERSION), 'text/css', 'all')
        ),
        'js_urls' => array(
            array(base_url('assets/base/admin/collection/frontend/js/menu.js?ver=' . MIDRUB_BASE_ADMIN_FRONTEND_VERSION))
        )  
    )
);

if ( !function_exists('md_get_frontend_page_menu') ) {

    /**
     * The function md_get_frontend_page_menu displays the menu page
     * 
     * @return void
     */
    function md_get_frontend_page_menu() {

        // Include menu view for frontend
        md_include_component_file(MIDRUB_BASE_ADMIN_FRONTEND . 'views/menu_page.php');
        
    }

}

if ( !function_exists('the_url_by_page_role') ) {
    
    /**
     * The function the_url_by_page_role gets the page url by role
     * 
     * @param string $type contains the role
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */
    function the_url_by_page_role($type) {

        // Get codeigniter object instance
        $CI =& get_instance();

        // Get selected pages
        $selected_pages = md_the_component_variable('selected_pages_by_role');

        if ( !$selected_pages ) {

            // Get selected pages by role
            $selected_pages = $CI->base_contents->get_contents_by_meta_name('selected_page_role');

            // Set pages
            md_set_component_variable('selected_pages_by_role', $selected_pages);
        
        }

        if ( $selected_pages ) {

            foreach ( $selected_pages as $selected_page ) {

                if ( $selected_page['meta_value'] === 'settings_auth_' . $type . '_page' ) {

                    return site_url($selected_page['contents_slug']);

                }

            }

        }

        return false;
        
    }
    
}

/**
 * The public method md_set_frontend_page adds a frontend page in the admin panel
 * 
 * @since 0.0.7.8
 */
md_set_frontend_page(
    'social_access',
    array(
        'page_name' => $CI->lang->line('frontend_social_access'),
        'page_icon' => '<i class="fas fa-share-alt"></i>',
        'content' => 'md_get_frontend_page_social_access',
        'css_urls' => array(
            array('stylesheet', base_url('assets/base/admin/collection/frontend/styles/css/social.css?ver=' . MIDRUB_BASE_ADMIN_FRONTEND_VERSION), 'text/css', 'all')
        ),
        'js_urls' => array(
            array(base_url('assets/base/admin/collection/frontend/js/social.js?ver=' . MIDRUB_BASE_ADMIN_FRONTEND_VERSION))
        )  
    )
);

if ( !function_exists('md_get_frontend_page_social_access') ) {

    /**
     * The function md_get_frontend_page_social_access displays the social access page
     * 
     * @return void
     */
    function md_get_frontend_page_social_access() {

        // Include auth social view for frontend
        md_include_component_file(MIDRUB_BASE_ADMIN_FRONTEND . 'views/auth_social.php'); 

    }

}

/**
 * The public method md_set_frontend_page adds a frontend page in the admin panel
 * 
 * @since 0.0.7.8
 */
md_set_frontend_page(
    'settings',
    array(
        'page_name' => $CI->lang->line('frontend_settings'),
        'page_icon' => '<i class="icon-settings"></i>',
        'content' => 'md_get_frontend_page_settings',
        'css_urls' => array(
            array('stylesheet', base_url('assets/base/admin/collection/frontend/styles/css/settings.css?ver=' . MIDRUB_BASE_ADMIN_FRONTEND_VERSION), 'text/css', 'all')
        ),
        'js_urls' => array(
            array(base_url('assets/base/admin/collection/frontend/js/settings.js?ver=' . MIDRUB_BASE_ADMIN_FRONTEND_VERSION))
        )  
    )
);

if ( !function_exists('md_get_frontend_page_settings') ) {

    /**
     * The function md_get_frontend_page_settings gets frontend's page settings content
     * 
     * @return void
     */
    function md_get_frontend_page_settings() {

        // Get codeigniter object instance
        $CI = &get_instance();

        // Require the dropdown functions
        require_once APPPATH . 'base/inc/options/dropdown.php';

        // Verify which tab should be displayed
        if ( $CI->input->get('section', true) === 'header' ) {

            // Include header settings view for frontend
            md_include_component_file(MIDRUB_BASE_ADMIN_FRONTEND . 'views/settings/header.php');

        } else if ( $CI->input->get('section', true) === 'footer' ) {

            // Include footer settings view for frontend
            md_include_component_file(MIDRUB_BASE_ADMIN_FRONTEND . 'views/settings/footer.php');

        } else {

            // Include general settings view for frontend
            md_include_component_file(MIDRUB_BASE_ADMIN_FRONTEND . 'views/settings/general.php');

        }
        
    }

}

/**
 * The public method md_add_hook registers a hook
 * 
 * @since 0.0.7.8
 */
md_add_hook(
    'update_content',
    function ($args) {

        // Get codeigniter object instance
        $CI =& get_instance();

        // Verify if content_id and contents_slug exists
        if ( isset($args['content_id']) && isset($args['contents_slug']) ) {

            // Update the classification
            $CI->base_model->update_ceil('classifications_meta', array (
                'meta_slug' => 'selected_page',
                'meta_extra' => $args['content_id']
            ), array (
                'meta_value' => $args['contents_slug']
            )); 

        }

    }

);

/**
 * The public method md_add_hook registers a hook
 * 
 * @since 0.0.7.8
 */
md_add_hook(
    'delete_content',
    function ($args) {

        // Get codeigniter object instance
        $CI =& get_instance();

        // Verify if content_id exists
        if ( isset($args['content_id']) ) {

            // Delete the content's records
            $CI->base_model->delete('classifications_meta', array (
                'meta_slug' => 'selected_page',
                'meta_extra' => $args['content_id']
            )); 

        }

    }

);