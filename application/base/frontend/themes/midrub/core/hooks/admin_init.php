<?php
/**
 * Contents Categories Functions
 *
 * PHP Version 5.6
 *
 * This files contains the component's contents
 * categories used in admin -> frontend
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
 * The public method md_set_contents_category sets the Default contents category
 * 
 * @since 0.0.7.8
 */
md_set_contents_category(
    'theme_pages',
    array(
        'category_name' => $CI->lang->line('theme_category_name'),
        'category_icon' => '<i class="far fa-clone"></i>',
        'editor' => false,
        'slug_in_url' => false,
        'templates_path' => the_theme_path() . 'contents/templates/',
        'words_list' => array(
            'new_content' => $CI->lang->line('theme_new_page'),
            'search_content' => $CI->lang->line('theme_search_pages'),
            'enter_content_title' => $CI->lang->line('theme_enter_page_title')
        )
    )
);

/**
 * The public method md_set_contents_category_meta sets meta for the Default contents category
 * 
 * @since 0.0.7.8
 */
md_set_contents_category_meta(
    'theme_pages',
    array(
        'name' => $CI->lang->line('theme_quick_seo'),
        'slug' => 'quick_seo',
        'fields' => array(
            array(
                'slug' => 'quick_seo_page_title',
                'type' => 'text_input',
                'label' => $CI->lang->line('theme_page_title'),
                'label_description' => $CI->lang->line('theme_page_description')
            ), array(
                'slug' => 'quick_seo_meta_description',
                'type' => 'text_input',
                'label' => $CI->lang->line('theme_meta_description'),
                'label_description' => $CI->lang->line('theme_meta_description_info')
            ), array(
                'slug' => 'quick_seo_meta_keywords',
                'type' => 'text_input',
                'label' => $CI->lang->line('theme_meta_keywords'),
                'label_description' => $CI->lang->line('theme_meta_keywords_info')                
            )            
        ),
        'css_urls' => array(
        ),
        'js_urls' => array(
        )        
    )    
);

/**
 * The public method md_set_contents_category_option sets option for the Themes contents category
 * 
 * @since 0.0.7.8
 */
md_set_contents_category_option(
    'theme_pages',
    array(
        'name' => $CI->lang->line('frontend_set_theme_template'),
        'slug' => 'theme_templates',
        'fields' => array(
            array(
                'slug' => 'theme_templates',
                'type' => 'theme_templates',
                'label' => $CI->lang->line('frontend_selected_theme_template'),
                'label_description' => ''
            )            
        ),
        'css_urls' => array(
        ),
        'js_urls' => array(
        )        
    )    
);

/**
 * The public method md_set_frontend_menu registers a new menu
 * 
 * @since 0.0.7.8
 */
md_set_frontend_menu(
    'main_menu',
    array(
        'name' => $CI->lang->line('theme_top_menu')      
    )    
);

/**
 * The public method md_set_frontend_menu registers a new menu
 * 
 * @since 0.0.7.8
 */
md_set_frontend_menu(
    'access_menu',
    array(
        'name' => $CI->lang->line('theme_access_menu')      
    )    
);

/**
 * The public method md_set_frontend_menu registers a new menu
 * 
 * @since 0.0.7.8
 */
md_set_frontend_menu(
    'footer_menu',
    array(
        'name' => $CI->lang->line('theme_footer_menu')      
    )    
);

/**
 * The public method md_set_frontend_menu registers a new menu
 * 
 * @since 0.0.7.8
 */
md_set_frontend_menu(
    'social_menu',
    array(
        'name' => $CI->lang->line('theme_social_menu')      
    )    
);

// Load classes based on templates
if ( ($CI->input->get('p', TRUE) === 'editor') && ($CI->input->get('category', TRUE) === 'theme_pages') && $CI->input->get('template', TRUE) ) {

    $template = str_replace('-', '_', $CI->input->get('template', TRUE));

    // Verify if class exists
    if ( file_exists(the_theme_path() . 'core/classes/' . $template . '.php' ) ) {

        // Create an array
        $array = array(
            'MidrubBase',
            'Frontend',
            'Themes',
            'Midrub',
            'Core',
            'Classes',
            ucfirst($template)
        );

        // Implode the array above
        $cl = implode('\\', $array);

        // Register hooks
        (new $cl())->load_hooks($category);

    }

}