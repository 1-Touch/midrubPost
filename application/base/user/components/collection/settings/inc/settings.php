<?php
/**
 * Settings Inc
 *
 * PHP Version 7.2
 *
 * This files contains the hooks for
 * the Settings component from the user Panel
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */

 // Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

if ( !function_exists('settings_language_list') ) {

    /**
     * The function settings_language_list provides the language list
     * 
     * @return array with languages
     */
    function settings_language_list() {

        // Get codeigniter object instance
        $CI = get_instance();

        // Language list
        $language_list = array();

        // List all languages
        $languages = glob(APPPATH . 'language' . '/*', GLOB_ONLYDIR);

        if ( $languages ) {

            foreach ( $languages as $language ) {

                $only_dir = str_replace(APPPATH . 'language' . '/', '', $language);

                $selected = false;

                if ( ( $CI->config->item('language') === $only_dir ) && !get_user_option('user_language') ) {
                    $selected = true;
                }

                if ( get_user_option('user_language') === $only_dir ) {
                    $selected = true;
                }

                $language_list[] = array(
                    'value' => $only_dir,
                    'text' => ucfirst($only_dir),
                    'selected' => $selected
                );

            }

        }

        return $language_list;

    }

}

if ( !function_exists('settings_set_advanced_settings') ) {

    /**
     * The function settings_set_advanced_settings sets the advanced settings
     * 
     * @return void
     */
    function settings_set_advanced_settings() {

        // Get codeigniter object instance
        $CI = get_instance();

        // Set advanced settings
        $advanced_settings = array(
            'section_name' => $CI->lang->line('advanced'),
            'section_slug' => 'advanced',
            'component' => true,
            'section_fields' => array(

                array(
                    'type' => 'text_input',
                    'slug' => 'first_name',
                    'name' => $CI->lang->line('first_name'),
                    'description' => $CI->lang->line('first_name_description'),
                    'edit' => true
                ), array(
                    'type' => 'text_input',
                    'slug' => 'last_name',
                    'name' => $CI->lang->line('last_name'),
                    'description' => $CI->lang->line('last_name_description'),
                    'edit' => true
                ), array(
                    'type' => 'text_input',
                    'slug' => 'username',
                    'name' => $CI->lang->line('username'),
                    'description' => $CI->lang->line('username_description'),
                    'edit' => false
                ), array(
                    'type' => 'text_input',
                    'slug' => 'email',
                    'name' => $CI->lang->line('email'),
                    'description' => $CI->lang->line('email_description'),
                    'edit' => true
                ), array(
                    'type' => 'modal_link',
                    'slug' => 'change-password',
                    'name' => $CI->lang->line('password'),
                    'description' => $CI->lang->line('password_description'),
                    'modal_link' => $CI->lang->line('change_password'),
                    'edit' => false
                ), array(
                    'type' => 'text_input',
                    'slug' => 'country',
                    'name' => $CI->lang->line('country'),
                    'description' => $CI->lang->line('country_description'),
                    'edit' => true
                ), array(
                    'type' => 'text_input',
                    'slug' => 'city',
                    'name' => $CI->lang->line('city'),
                    'description' => $CI->lang->line('city_description'),
                    'edit' => true
                ), array(
                    'type' => 'text_input',
                    'slug' => 'address',
                    'name' => $CI->lang->line('address'),
                    'description' => $CI->lang->line('address_description'),
                    'edit' => true
                )

            )

        );

        // Verify if multilanguage is enabled
        if ( get_option('enable_multilanguage') ) {

            $advanced_settings['section_fields'][] = array(
                'type' => 'select_input',
                'slug' => 'user_language',
                'name' => $CI->lang->line('language'),
                'description' => $CI->lang->line('select_language_description'),
                'options' => settings_language_list()
            );

        }

        // Set the delete account option
        $advanced_settings['section_fields'][] = array(
            'type' => 'modal_link',
            'slug' => 'delete-account',
            'name' => $CI->lang->line('delete_account'),
            'description' => $CI->lang->line('delete_account_description'),
            'modal_link' => $CI->lang->line('delete_my_account'),
            'edit' => false
        );

        /**
         * Registers options for User's Settings
         * 
         * @since 0.0.7.9
         */
        set_user_settings_options($advanced_settings);

    }

}

if ( !function_exists('settings_set_default_plan_usage') ) {

    /**
     * The function settings_set_default_plan_usage sets the default plan's usage
     * 
     * @return void
     */
    function settings_set_default_plan_usage() {

        // Get codeigniter object instance
        $CI = get_instance();

        // Default plan's usage
        $default = array();

        // Get the user's plan
        $user_plan = get_user_option('plan', $CI->user_id);

        // Get plan end
        $plan_end = get_user_option('plan_end', $CI->user_id);

        // Get plan data
        $plan_data = $CI->plans->get_plan($user_plan);

        // Calculate remaining time
        $time = strip_tags(calculate_time(strtotime($plan_end), time()));

        // Set period
        $period = (strtotime($plan_end) - (strtotime($plan_end) - ($plan_data[0]['period'] * 86400)));

        // Set taken time
        $time_taken = ($period - (strtotime($plan_end) - time()));

        // Set usage
        $default[] = array(
            'name' => $plan_data[0]['plan_name'],
            'value' => $time_taken,
            'limit' => $period,
            'left' => $time
        );

        // Get user storage
        $user_storage = get_user_option('user_storage', $CI->user_id);

        if (!$user_storage) {
            $user_storage = 0;
        }

        $plan_storage = 0;

        // Gets plan's storage
        $plan_st = plan_feature('storage');

        if ($plan_st) {

            $plan_storage = $plan_st;
        }

        // Set usage
        $default[] = array(
            'name' => $CI->lang->line('storage'),
            'value' => $user_storage,
            'limit' => $plan_storage,
            'left' => calculate_size($user_storage) . '/' . calculate_size($plan_storage)
        );

        $team_limits = plan_feature('teams');

        // Verify if teams is available
        if ($team_limits > 0) {

            // Load Team Model
            $CI->load->model('team');

            // Get team members
            $members = $CI->team->get_members($CI->user_id);

            if (!$members) {
                $members = 0;
            }

            // Set usage
            $default[] = array(
                'name' => $CI->lang->line('teams'),
                'value' => $members,
                'limit' => $team_limits,
                'left' => ($team_limits - $members)
            );

        }

        // Adds plan usage to the list
        set_plans_usage($default);

    }

}

if ( !function_exists('settings_set_optional_features') ) {

    /**
     * The function settings_set_optional_features sets the optional's features
     * 
     * @return void
     */
    function settings_set_optional_features() {

        // Get codeigniter object instance
        $CI = get_instance();

        // Verify if referrals are enabled
        if ( get_option('enable_referral') ) {

            /**
             * Registers options for User's Settings
             * 
             * @since 0.0.7.9
             */
            set_user_settings_options(

                array(
                    'section_name' => $CI->lang->line('referrals'),
                    'section_slug' => 'referrals',
                    'component' => true,
                    'section_fields' => array()

                )

            );

        }

        // Verify if Invoices are enabled
        if ( !get_option('hide_invoices') ) {

            /**
             * Registers options for User's Settings
             * 
             * @since 0.0.7.9
             */
            set_user_settings_options(

                array(
                    'section_name' => $CI->lang->line('invoices'),
                    'section_slug' => 'invoices',
                    'component' => true,
                    'section_fields' => array()

                )

            );

        }

    }

}

/**
 * Registers options for User's Settings
 * 
 * @since 0.0.7.9
 */
set_user_settings_options(

    array (
        'section_name' => $this->lang->line('general'),
        'section_slug' => 'general',
        'component' => true,
        'section_fields' => array (

            array (
                'type' => 'checkbox_input',
                'slug' => 'email_notifications',
                'name' => $this->lang->line('email_notifications'),
                'description' => $this->lang->line('email_notifications_if_enabled'),
            ), array (
                'type' => 'checkbox_input',
                'slug' => 'notification_tickets',
                'name' => $this->lang->line('tickets_email_notification'),
                'description' => $this->lang->line('notifications_about_tickets_replies')
            ), array (
                'type' => 'checkbox_input',
                'slug' => 'display_activities',
                'name' => $this->lang->line('display_activities'),
                'description' => $this->lang->line('display_activities_description')
            ), array (
                'type' => 'checkbox_input',
                'slug' => 'settings_delete_activities',
                'name' => $this->lang->line('settings_delete_activities'),
                'description' => $this->lang->line('settings_delete_activities_description')
            ), array (
                'type' => 'checkbox_input',
                'slug' => '24_hour_format',
                'name' => $this->lang->line('24_hour_format'),
                'description' => $this->lang->line('24_hour_format_description')
            ), array (
                'type' => 'checkbox_input',
                'slug' => 'invoices_by_email',
                'name' => $this->lang->line('invoices_by_email'),
                'description' => $this->lang->line('invoices_by_email_description')
            )

        )
        
    )

);

/**
 * Sets advanced's settings
 * 
 * @since 0.0.8.0
 */
settings_set_advanced_settings();

/**
 * Registers options for User's Settings
 * 
 * @since 0.0.7.9
 */
set_user_settings_options(

    array (
        'section_name' => $this->lang->line('plan_usage'),
        'section_slug' => 'plan_usage',
        'component' => true,
        'section_fields' => array (
        )
        
    )

);

/**
 * Sets default plan's usage
 * 
 * @since 0.0.8.0
 */
settings_set_default_plan_usage();

/**
 * Sets optional settings features like referrals or invoices
 * 
 * @since 0.0.8.0
 */
settings_set_optional_features();