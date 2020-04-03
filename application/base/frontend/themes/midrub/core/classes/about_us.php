<?php
/**
 * Midrub About Us Page Hooks
 *
 * This file loads the class About Us with hooks for the about's template
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */

// Define the page namespace
namespace MidrubBase\Frontend\Themes\Midrub\Core\Classes;

/*
 * About_us registers hooks for the about's template
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */
class About_us {

    /**
     * Class variables
     *
     * @since 0.0.7.8
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.8
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
    }
    
    /**
     * The public method load_hooks registers hooks for the about's template
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */
    public function load_hooks() {

        /**
         * The public method md_set_contents_category_meta sets about form for about page
         * 
         * @since 0.0.7.8
         */
        md_set_contents_category_meta(
            'theme_pages',
            array(
                'name' => $this->CI->lang->line('theme_about_details'),
                'slug' => 'theme_about_us',
                'template' => 'about-us',
                'fields' => array(
                    array(
                        'slug' => 'theme_about_us_image_cover',
                        'type' => 'media_input',
                        'label' => $this->CI->lang->line('theme_about_us_cover'),
                        'label_description' => $this->CI->lang->line('theme_about_us_cover_description')
                    ),
                    array(
                        'slug' => 'theme_about_us_content',
                        'type' => 'editor',
                        'label' => $this->CI->lang->line('theme_about_us_content'),
                        'label_description' => $this->CI->lang->line('theme_about_us_content_description')
                    )

                ),
                'css_urls' => array(),
                'js_urls' => array()
            )

        );

    }

}

/* End of file homepage.php */