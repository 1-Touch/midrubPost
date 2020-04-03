<?php
/**
 * Midrub Contact Us Page Hooks
 *
 * This file loads the class Contact Us with hooks for the contact's template
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */

// Define the page namespace
namespace MidrubBase\Frontend\Themes\Midrub\Core\Classes;

/*
 * Contact_us registers hooks for the contact's template
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */
class Contact_us {

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
                'name' => $this->CI->lang->line('theme_contact_form'),
                'slug' => 'theme_contact_form',
                'template' => 'contact-us',
                'fields' => array(
                    array(
                        'slug' => 'theme_contact_form_title',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_contact_form_title'),
                        'label_description' => $this->CI->lang->line('theme_contact_form_title_description'),
                        'value' => 'Contact Our Team'
                    ),  
                    array(
                        'slug' => 'theme_contact_text_below_title',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_presentation_text_below_title'),
                        'label_description' => $this->CI->lang->line('theme_presentation_text_below_title_description'),
                        'value' => 'We would love to hear from you.'
                    ),                                                   
                    array(
                        'slug' => 'theme_contact_form_enable',
                        'type' => 'checkbox_input',
                        'label' => $this->CI->lang->line('theme_top_section_enable'),
                        'label_description' => $this->CI->lang->line('theme_contact_form_description')
                    )

                ),
                'css_urls' => array(),
                'js_urls' => array()
            )

        );

        /**
         * The public method md_set_contents_category_meta sets map for contact page
         * 
         * @since 0.0.7.8
         */
        md_set_contents_category_meta(
            'theme_pages',
            array(
                'name' => $this->CI->lang->line('theme_contact_map_configuration'),
                'slug' => 'theme_contact_map',
                'template' => 'contact-us',
                'fields' => array(
                    array(
                        'slug' => 'theme_contact_map_latitude',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_contact_map_latitude'),
                        'label_description' => $this->CI->lang->line('theme_contact_map_latitude_description')
                    ),  
                    array(
                        'slug' => 'theme_contact_map_longitude',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_contact_map_longitude'),
                        'label_description' => $this->CI->lang->line('theme_contact_map_longitude_description')
                    ),
                    array(
                        'slug' => 'theme_contact_map_api_key',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_contact_map_api_key'),
                        'label_description' => $this->CI->lang->line('theme_contact_map_api_key_description')
                    ),                                                   
                    array(
                        'slug' => 'theme_contact_map_enable',
                        'type' => 'checkbox_input',
                        'label' => $this->CI->lang->line('theme_top_section_enable'),
                        'label_description' => $this->CI->lang->line('theme_contact_map_enable_description')
                    )

                ),
                'css_urls' => array(),
                'js_urls' => array()
            )

        );

        /**
         * The public method md_set_contents_category_meta sets map for contact page
         * 
         * @since 0.0.7.8
         */
        md_set_contents_category_meta(
            'theme_pages',
            array(
                'name' => $this->CI->lang->line('theme_contact_details'),
                'slug' => 'theme_contact_details',
                'template' => 'contact-us',
                'fields' => array(
                    array(
                        'slug' => 'theme_contact_details_location',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_location'),
                        'label_description' => $this->CI->lang->line('theme_contact_details_location_description')
                    ),
                    array(
                        'slug' => 'theme_contact_details_phone',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_phone'),
                        'label_description' => $this->CI->lang->line('theme_contact_details_phone_description')
                    ), 
                    array(
                        'slug' => 'theme_contact_details_hours',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_hours'),
                        'label_description' => $this->CI->lang->line('theme_contact_details_hours_description')
                    ),                                                          
                    array(
                        'slug' => 'theme_contact_details_enable',
                        'type' => 'checkbox_input',
                        'label' => $this->CI->lang->line('theme_top_section_enable'),
                        'label_description' => $this->CI->lang->line('theme_top_section_enable_description')
                    )

                ),
                'css_urls' => array(),
                'js_urls' => array()
            )

        );
        
        /**
         * The public method md_set_contents_category_meta sets map for contact page
         * 
         * @since 0.0.7.8
         */
        md_set_contents_category_meta(
            'theme_pages',
            array(
                'name' => $this->CI->lang->line('theme_contact_recaptcha'),
                'slug' => 'theme_contact_recaptcha',
                'template' => 'contact-us',
                'fields' => array(
                    array(
                        'slug' => 'theme_contact_recaptcha_site_key',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_contact_recaptcha_site_key'),
                        'label_description' => $this->CI->lang->line('theme_contact_recaptcha_site_key_description')
                    ),
                    array(
                        'slug' => 'theme_contact_recaptcha_site_secret',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_contact_recaptcha_site_secret'),
                        'label_description' => $this->CI->lang->line('theme_contact_recaptcha_site_secret_description')
                    )

                ),
                'css_urls' => array(),
                'js_urls' => array()
            )

        );        

    }

}

/* End of file homepage.php */