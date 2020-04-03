<?php
/**
 * Midrub Home Page Hooks
 *
 * This file loads the class Homepage with hooks for the homepage's template
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */

// Define the page namespace
namespace MidrubBase\Frontend\Themes\Midrub\Core\Classes;

/*
 * Homepage registers hooks for the homepage's template
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */
class Homepage {

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
     * The public method load_hooks registers hooks for the homepage's template
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */
    public function load_hooks() {

        /**
         * The public method md_set_contents_category_meta sets top section for home page
         * 
         * @since 0.0.7.8
         */
        md_set_contents_category_meta(
            'theme_pages',
            array(
                'name' => $this->CI->lang->line('theme_top_section'),
                'slug' => 'theme_top_section',
                'template' => 'homepage',
                'fields' => array(
                    array(
                        'slug' => 'theme_top_section_slogan',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_top_section_slogan'),
                        'label_description' => $this->CI->lang->line('theme_top_section_slogan_description'),
                        'value' => 'The easiest way to plan your social life'
                    ),  
                    array(
                        'slug' => 'theme_top_section_text_below_slogan',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_top_section_text_below_slogan'),
                        'label_description' => $this->CI->lang->line('theme_top_section_text_below_slogan_description'),
                        'value' => 'Schedule and manage posts from the most popular social networks in one single place.'
                    ),
                    array(
                        'slug' => 'theme_top_section_button_text',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_top_section_button_text'),
                        'label_description' => $this->CI->lang->line('theme_top_section_button_text_description'),
                        'value' => 'Try it free'
                    ),
                    array(
                        'slug' => 'theme_top_section_button_link',
                        'type' => 'select_page',
                        'label' => $this->CI->lang->line('theme_top_section_button_link'),
                        'label_description' => $this->CI->lang->line('theme_top_section_button_link_description')
                    ),    
                    array(
                        'slug' => 'theme_top_section_text_below_button',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_top_section_text_below_button'),
                        'label_description' => $this->CI->lang->line('theme_top_section_text_below_button_description'),
                        'value' => '100% Money Back Guarantee'
                    ),                                                   
                    array(
                        'slug' => 'theme_top_section_large_image',
                        'type' => 'media_input',
                        'label' => $this->CI->lang->line('theme_top_section_large_image'),
                        'label_description' => $this->CI->lang->line('theme_top_section_large_image_description')
                    ),                                                
                    array(
                        'slug' => 'theme_top_section_enable',
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
         * The public method md_set_contents_category_meta sets presentation section for home page
         * 
         * @since 0.0.7.8
         */
        md_set_contents_category_meta(
            'theme_pages',
            array(
                'name' => $this->CI->lang->line('theme_presentation_section'),
                'slug' => 'theme_presentation_section',
                'template' => 'homepage',
                'fields' => array(
                    array(
                        'slug' => 'theme_top_section_stats',
                        'type' => 'list_items',
                        'label' => $this->CI->lang->line('theme_top_section_stats'),
                        'words' => array(
                            'new_item_text' => '<i class="icon-chart"></i> ' . $this->CI->lang->line('theme_new_stats')
                        ),
                        'fields' => array(
                            array(
                                'slug' => 'theme_top_section_stats_title',
                                'type' => 'text_input',
                                'label' => $this->CI->lang->line('theme_top_section_stats_title'),
                                'label_description' => $this->CI->lang->line('theme_top_section_stats_description')
                            ),
                            array(
                                'slug' => 'theme_top_section_stats_value',
                                'type' => 'text_input',
                                'label' => $this->CI->lang->line('theme_top_section_stats_value'),
                                'label_description' => $this->CI->lang->line('theme_top_section_stats_value_description')
                            ), 
                        ),
                    ), 
                    array(
                        'slug' => 'theme_presentation_section_title',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_presentation_section_title'),
                        'label_description' => $this->CI->lang->line('theme_presentation_section_title_description'),
                        'value' => 'See how Midrub can help your business to grow'
                    ),  
                    array(
                        'slug' => 'theme_presentation_text_below_title',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_presentation_text_below_title'),
                        'label_description' => $this->CI->lang->line('theme_presentation_text_below_title_description'),
                        'value' => 'By sign up for free you can test all those features'
                    ), 
                    array(
                        'slug' => 'theme_presentation_videos',
                        'type' => 'list_items',
                        'label' => $this->CI->lang->line('theme_videos'),
                        'words' => array(
                            'new_item_text' => '<i class="fab fa-youtube"></i> ' . $this->CI->lang->line('theme_new_video')
                        ),
                        'fields' => array(
                            array(
                                'slug' => 'theme_presentation_section_tab_title',
                                'type' => 'text_input',
                                'label' => $this->CI->lang->line('theme_presentation_section_tab_title'),
                                'label_description' => $this->CI->lang->line('theme_presentation_section_tab_title_description')
                            ),
                            array(
                                'slug' => 'theme_presentation_section_video_title',
                                'type' => 'text_input',
                                'label' => $this->CI->lang->line('theme_presentation_section_video_title'),
                                'label_description' => $this->CI->lang->line('theme_presentation_section_video_title_description')
                            ), 
                            array(
                                'slug' => 'theme_presentation_section_video_cover',
                                'type' => 'media_input',
                                'label' => $this->CI->lang->line('theme_presentation_section_video_cover'),
                                'label_description' => $this->CI->lang->line('theme_presentation_section_video_cover_description')
                            ), 
                            array(
                                'slug' => 'theme_presentation_section_video_url',
                                'type' => 'text_input',
                                'label' => $this->CI->lang->line('theme_presentation_section_video_url'),
                                'label_description' => $this->CI->lang->line('theme_presentation_section_video_url_description')
                            ),
                            array(
                                'slug' => 'theme_presentation_section_video_title2',
                                'type' => 'text_input',
                                'label' => $this->CI->lang->line('theme_presentation_section_video_title'),
                                'label_description' => $this->CI->lang->line('theme_presentation_section_video_title_description')
                            ), 
                            array(
                                'slug' => 'theme_presentation_section_video_cover2',
                                'type' => 'media_input',
                                'label' => $this->CI->lang->line('theme_presentation_section_video_cover'),
                                'label_description' => $this->CI->lang->line('theme_presentation_section_video_cover_description')
                            ), 
                            array(
                                'slug' => 'theme_presentation_section_video_url2',
                                'type' => 'text_input',
                                'label' => $this->CI->lang->line('theme_presentation_section_video_url'),
                                'label_description' => $this->CI->lang->line('theme_presentation_section_video_url_description')
                            ),     
                            array(
                                'slug' => 'theme_presentation_section_video_title3',
                                'type' => 'text_input',
                                'label' => $this->CI->lang->line('theme_presentation_section_video_title'),
                                'label_description' => $this->CI->lang->line('theme_presentation_section_video_title_description')
                            ), 
                            array(
                                'slug' => 'theme_presentation_section_video_cover3',
                                'type' => 'media_input',
                                'label' => $this->CI->lang->line('theme_presentation_section_video_cover'),
                                'label_description' => $this->CI->lang->line('theme_presentation_section_video_cover_description')
                            ), 
                            array(
                                'slug' => 'theme_presentation_section_video_url3',
                                'type' => 'text_input',
                                'label' => $this->CI->lang->line('theme_presentation_section_video_url'),
                                'label_description' => $this->CI->lang->line('theme_presentation_section_video_url_description')
                            ),                       
                        )
                    ),                                                               
                    array(
                        'slug' => 'theme_presentation_section_enable',
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
         * The public method theme_questions_section_enable sets questions section for home page
         * 
         * @since 0.0.7.8
         */
        md_set_contents_category_meta(
            'theme_pages',
            array(
                'name' => $this->CI->lang->line('theme_questions_section'),
                'slug' => 'theme_questions_section',
                'template' => 'homepage',
                'fields' => array(
                    array(
                        'slug' => 'theme_questions_section_title',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_presentation_section_title'),
                        'label_description' => $this->CI->lang->line('theme_presentation_section_title_description'),
                        'value' => 'Get Started'
                    ),  
                    array(
                        'slug' => 'theme_questions_text_below_title',
                        'type' => 'text_input',
                        'label' => $this->CI->lang->line('theme_presentation_text_below_title'),
                        'label_description' => $this->CI->lang->line('theme_presentation_text_below_title_description'),
                        'value' => '5 steps to starting growing your business'
                    ),    
                    array(
                        'slug' => 'theme_questions_section_list',
                        'type' => 'list_items',
                        'label' => $this->CI->lang->line('theme_questions_and_answers'),
                        'words' => array(
                            'new_item_text' => '<i class="far fa-question-circle"></i> ' . $this->CI->lang->line('theme_new_question')
                        ),
                        'fields' => array(
                            array(
                                'slug' => 'theme_questions_section_list_question',
                                'type' => 'text_input',
                                'label' => $this->CI->lang->line('theme_question'),
                                'label_description' => $this->CI->lang->line('theme_question_description')
                            ),  
                            array(
                                'slug' => 'theme_questions_section_list_answer',
                                'type' => 'editor',
                                'label' => $this->CI->lang->line('theme_answer'),
                                'label_description' => $this->CI->lang->line('theme_answer_description')
                            )
                        )
                    ),                                               
                    array(
                        'slug' => 'theme_questions_section_enable',
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
         * The public method theme_contact_section_enable sets contact section for home page
         * 
         * @since 0.0.7.8
         */
        md_set_contents_category_meta(
            'theme_pages',
            array(
                'name' => $this->CI->lang->line('theme_contact_section'),
                'slug' => 'theme_contact_section',
                'template' => 'homepage',
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