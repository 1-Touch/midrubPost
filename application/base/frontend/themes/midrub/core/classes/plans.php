<?php
/**
 * Midrub Plans Page Hooks
 *
 * This file loads the class Plans with hooks for the plans's template
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */

// Define the page namespace
namespace MidrubBase\Frontend\Themes\Midrub\Core\Classes;

/*
 * Plans registers hooks for the plans's template
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */
class Plans {

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
     * The public method load_hooks registers hooks for the contact's template
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */
    public function load_hooks() {

        /**
         * The public method md_set_contents_category_meta sets contact form for contact page
         * 
         * @since 0.0.7.8
         */
        md_set_contents_category_meta(
            'theme_pages',
            array(
                'name' => $this->CI->lang->line('theme_plans_text'),
                'slug' => 'theme_plans',
                'template' => 'plans',
                'fields' => array(
                    array(
                        'slug' => 'theme_plans_title',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_plans_title'),
                        'label_description' => $this->CI->lang->line('theme_plans_title_description'),
                        'value' => 'Plans and Features'
                    ),  
                    array(
                        'slug' => 'theme_plans_text_below_title',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_presentation_text_below_title'),
                        'label_description' => $this->CI->lang->line('theme_presentation_text_below_title_description'),
                        'value' => 'We offer plans for businesses of all sizes and personal use.'
                    )

                ),
                'css_urls' => array(),
                'js_urls' => array()
            )

        );

        /**
         * The public method theme_contact_section_enable sets contact section for home page
         * 
         * @since 0.0.7.8
         */
        md_set_contents_category_meta(
            'theme_pages',
            array(
                'name' => $this->CI->lang->line('theme_contact_section'),
                'slug' => 'theme_contact_section',
                'template' => 'plans',
                'fields' => array(
                    array(
                        'slug' => 'theme_contact_section_title',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_contact_section_title'),
                        'label_description' => $this->CI->lang->line('theme_presentation_section_title_description'),
                        'value' => 'Still have questions?'
                    ),  
                    array(
                        'slug' => 'theme_contact_text_below_title',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_presentation_text_below_title'),
                        'label_description' => $this->CI->lang->line('theme_presentation_text_below_title_description'),
                        'value' => 'We\'d love to hear your thoughts and answer any questions you may have.'
                    ),
                    array(
                        'slug' => 'theme_contact_section_button_text',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_top_section_button_text'),
                        'label_description' => $this->CI->lang->line('theme_top_section_button_text_description'),
                        'value' => 'Contact Us'
                    ),
                    array(
                        'slug' => 'theme_contact_section_button_link',
                        'type' => 'select_page',
                        'label' => $this->CI->lang->line('theme_top_section_button_link'),
                        'label_description' => $this->CI->lang->line('theme_top_section_button_link_description')
                    ),                                                     
                    array(
                        'slug' => 'theme_contact_section_enable',
                        'type' => 'checkbox_input',
                        'label' => $this->CI->lang->line('theme_top_section_enable'),
                        'label_description' => $this->CI->lang->line('theme_top_section_enable_description')
                    )

                ),
                'css_urls' => array(),
                'js_urls' => array()
            )

        );  

    }

}

/* End of file homepage.php */