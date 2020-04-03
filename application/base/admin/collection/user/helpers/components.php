<?php
/**
 * Components Helper
 *
 * This file contains the class Components
 * with methods to manage the components
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */

// Define the page namespace
namespace MidrubBase\Admin\Collection\User\Helpers;

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Components class provides the methods to manage the components
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
*/
class Components
{

    /**
     * Class variables
     *
     * @since 0.0.7.9
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.9
     */
    public function __construct()
    {

        // Get codeigniter object instance
        $this->CI = &get_instance();

    }

    /**
     * The public method settings_components_and_apps_list list all components and apps
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */ 
    public function settings_components_and_apps_list() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('drop_class', 'Dropdown Class', 'trim');
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');

            // Get received data
            $drop_class = $this->CI->input->post('drop_class');
            $key = $this->CI->input->post('key');

            // Check form validation
            if ($this->CI->form_validation->run() !== false) {

                // Apps array
                $apps = array();

                // List all user's apps
                foreach (glob(APPPATH . 'base/user/apps/collection/*', GLOB_ONLYDIR) as $directory) {

                    // Get the directory's name
                    $app = trim(basename($directory) . PHP_EOL);

                    // Verify if the app is enabled
                    if ( !get_option('app_' .  $app. '_enable') ) {
                        continue;
                    }

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

                    // Get app's info
                    $info = (new $cl())->app_info();

                    if ( preg_match("/{$key}/i", $info['app_name']) ) {

                        // Add info to app
                        $apps[] = array(
                            'name' => $info['app_name'],
                            'slug' => $info['app_slug']
                        );

                    }

                    // Max number 10
                    if ( count($apps) > 9 ) {
                        break;
                    }

                }

                // List all user's components
                foreach (glob(APPPATH . 'base/user/components/collection/*', GLOB_ONLYDIR) as $directory) {

                    // Get the directory's name
                    $component = trim(basename($directory) . PHP_EOL);

                    // Verify if the component is enabled
                    if ( !get_option('component_' . $component . '_enable') ) {
                        continue;
                    }

                    // Create an array
                    $array = array(
                        'MidrubBase',
                        'User',
                        'Components',
                        'Collection',
                        ucfirst($component),
                        'Main'
                    );

                    // Implode the array above
                    $cl = implode('\\', $array);

                    // Get component's info
                    $info = (new $cl())->component_info();

                    if ( preg_match("/{$key}/i", $info['component_name']) ) {

                        // Add info to component
                        $apps[] = array(
                            'name' => $info['component_name'],
                            'slug' => $info['component_slug']
                        );

                    }

                    // Max number 10
                    if ( count($apps) > 9 ) {
                        break;
                    }

                }

                // Verify if apps exists
                if ( $apps ) {

                    // Display success message
                    $data = array(
                        'success' => TRUE,
                        'drop_class' => $drop_class,
                        'apps' => $apps
                    );

                    echo json_encode($data);
                    exit();

                }

            }

            // Display error message
            $data = array(
                'success' => FALSE,
                'drop_class' => $drop_class,
                'message' => $this->CI->lang->line('user_no_data_found_to_show')
            );

            echo json_encode($data); 

        }

    }

    /**
     * The public method load_selected_components loads the list with selected components and apps
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */ 
    public function load_selected_components() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('component_slugs', 'Component Slugs', 'trim');

            // Get received data
            $component_slugs = $this->CI->input->post('component_slugs');

            // Check form validation
            if ($this->CI->form_validation->run() !== false) {

                // Verify if component slugs exists
                if ( $component_slugs ) {

                    // All components
                    $components = array();

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

                        // Get app's info
                        $info = (new $cl())->app_info();

                        // Verify if app's slug is in the list
                        if ( in_array($info['app_slug'], $component_slugs) ) {

                            // Add info to list
                            $components[] = array(
                                'name' => $info['app_name'],
                                'slug' => $info['app_slug']
                            );

                        }

                    }

                    if ( !$components ) {

                        // List all user's components
                        foreach (glob(APPPATH . 'base/user/components/collection/*', GLOB_ONLYDIR) as $directory) {

                            // Get the directory's name
                            $component = trim(basename($directory) . PHP_EOL);

                            // Create an array
                            $array = array(
                                'MidrubBase',
                                'User',
                                'Components',
                                'Collection',
                                ucfirst($component),
                                'Main'
                            );

                            // Implode the array above
                            $cl = implode('\\', $array);

                            // Get component's info
                            $info = (new $cl())->component_info();

                            // Verify if component's slug is in the list
                            if (in_array($info['component_slug'], $component_slugs)) {

                                // Add info to list
                                $components[] = array(
                                    'name' => $info['component_name'],
                                    'slug' => $info['component_slug']
                                );

                            }

                        }

                    }

                    if ( $components ) {

                        $data = array(
                            'success' => TRUE,
                            'components' => $components
                        );

                        echo json_encode($data);
                    }
                    
                }

            }

        }

    }

}

/* End of file components.php */
