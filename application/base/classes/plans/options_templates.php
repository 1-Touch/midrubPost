<?php
/**
 * Options_templates Class
 *
 * This file loads the Options_templates Class with methods to generates options templates for plans
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */

// Define the page namespace
namespace MidrubBase\Classes\Plans;

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Options_templates class loads the methods to generates options templates for plans
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */
class Options_templates {

    /**
     * The public method checkbox_input processes the plans option's type checkbox
     * 
     * @param array $option contains the option's information
     * @param integer $plan_id contains the plan's id
     * 
     * @since 0.0.7.9
     * 
     * @return string with processed data
     */ 
    public function checkbox_input( $option, $plan_id ) {
        
        // Verify if option has correct format
        if ( !isset($option['slug']) || !isset($option['label']) ) {
            return '';
        }

        // Verify if the option is enabled
        $checked = '';
        
        if ( plan_feature( $option['slug'], $plan_id ) === '1' ) {
            $checked = ' checked';
        }

        $label_description = '';

        if ( !empty($option['label_description']) ) {
            $label_description = '<p>' . $option['label_description'] . '</p>';
        }
        
        // Return html
        return '<li>'
                    . '<div class="row">'
                        . '<div class="col-lg-10">'
                            . '<label>' . $option['label'] . '</label>'
                            . $label_description
                        . '</div>'
                        . '<div class="col-lg-2">'
                            . '<div class="checkbox-option pull-right">'
                                . '<input id="' . $option['slug'] . '" name="' . $option['slug'] . '" class="plan-option-checkbox" type="checkbox" ' . $checked . '>'
                                . '<label for="' . $option['slug'] . '"></label>'
                            . '</div>'
                        . '</div>'
                    . '</div>'
                . '</li>';
        
    }
    
    /**
     * The public method text_input processes the option's type text
     * 
     * @param array $option contains the option's information
     * @param integer $plan_id contains the plan's id
     * 
     * @since 0.0.7.9
     * 
     * @return string with processed data
     */ 
    public function text_input( $option, $plan_id ) {
        
        // Verify if option has correct format
        if ( !isset($option['slug']) || !isset($option['label']) ) {
            return '';
        }
        
        // Verify if the option is enabled
        $value = '';
        
        if ( plan_feature( $option['slug'], $plan_id ) ) {
            $value = plan_feature( $option['slug'], $plan_id );
        }
        
        $maxlength = '';
        
        if ( isset($option['maxlength']) ) {
            $maxlength = ' maxlength="' . $option['maxlength'] . '"';
        }  
        
        $type = 'text';
        
        if ( isset($option['input_type']) ) {
            $type = $option['input_type'];
        }  
        
        $label_description = '';

        if ( !empty($option['label_description']) ) {
            $label_description = '<p>' . $option['label_description'] . '</p>';
        }
        
        // Return html
        return '<li>'
                    . '<div class="row">'
                        . '<div class="col-lg-6">'
                            . '<label>' . $option['label'] . '</label>'
                            . $label_description
                        . '</div>'
                        . '<div class="col-lg-6">'
                            . '<div class="plans-option">'
                                . '<input id="' . $option['slug'] . '" name="' . $option['slug'] . '" value="' . $value . '" class="plan-input" type="' . $type . '"' . $maxlength . '>'
                            . '</div>'
                        . '</div>'
                    . '</div>'
                . '</li>';
        
    }
    
    /**
     * The public method textarea processes the option's type textarea
     * 
     * @param array $option contains the option's information
     * @param integer $plan_id contains the plan's id
     * 
     * @since 0.0.7.9
     * 
     * @return string with processed data
     */ 
    public function textarea( $option, $plan_id ) {
        
        // Verify if option has correct format
        if ( !isset($option['slug']) || !isset($option['label']) ) {
            return '';
        }
        
        // Verify if the option is enabled
        $value = '';
        
        if ( plan_feature( $option['slug'], $plan_id ) ) {
            $value = plan_feature( $option['slug'], $plan_id );
        }

        $label_description = '';

        if ( !empty($option['label_description']) ) {
            $label_description = '<p>' . $option['label_description'] . '</p>';
        }
        
        // Return html
        return '<li>'
                    . '<div class="row">'
                        . '<div class="col-lg-6">'
                            . '<label>' . $option['label'] . '</label>'
                            . $label_description
                        . '</div>'
                        . '<div class="col-lg-6">'
                            . '<div class="plans-option">'
                                . '<textarea id="' . $option['slug'] . '" name="' . $option['slug'] . '" class="plan-input">' . $value . '</textarea>'
                            . '</div>'
                        . '</div>'
                    . '</div>'
                . '</li>';
        
    }

}

/* End of file options_templates.php */
