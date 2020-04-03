<?php
/**
 * Accounts Helpers
 *
 * This file contains the class Accounts
 * with methods to process the accounts data
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Posts\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Accounts class provides the methods to process the accounts data
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
*/
class Accounts {
    
    /**
     * Class variables
     *
     * @since 0.0.7.0
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.0
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
    }
    
    /**
     * The public method search_accounts searches accounts in the database
     * 
     * @since 0.0.7.4
     * 
     * @return void
     */ 
    public function search_accounts() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            $this->CI->form_validation->set_rules('network', 'Network', 'trim|required');
            $this->CI->form_validation->set_rules('type', 'Type', 'trim|required');
            
            // Get data
            $key = $this->CI->input->post('key');
            $network = $this->CI->input->post('network');
            $type = $this->CI->input->post('type');
            
            if ( $this->CI->form_validation->run() == false ) {
                
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('no_accounts_found'),
                    'type' => $type
                );

                echo json_encode($data);   
                
            } else {
                
                if ( $type === 'accounts_manager' ) {
                
                    // Search for active accounts
                    $search_accounts = $this->get_active_accounts($this->CI->networks_model->get_accounts_by_network( $this->CI->user_id, $network, 1, $key ), 1);
                    
                } else {
                
                    // Search for active accounts
                    $search_accounts = $this->get_active_accounts($this->CI->networks_model->get_accounts_by_network( $this->CI->user_id, $network, 1, $key ), 2);
                    
                }
                
                if ( $search_accounts ) {
                
                    $data = array(
                        'success' => TRUE,
                        'social_data' => $search_accounts,
                        'type' => $type
                    );

                    echo json_encode($data);
                
                } else {
                    
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('no_accounts_found'),
                        'type' => $type
                    );

                    echo json_encode($data); 
                    
                }
                
            }
            
        }
        
    }  
    
    /**
     * The public method delete_accounts deletes social accounts
     * 
     * @since 0.0.7.4
     * 
     * @return void
     */ 
    public function delete_accounts() {
        
        // Get the account id
        $account_id = $this->CI->input->get('account_id', TRUE);
        
        if ( $account_id ) {
            
            $response = $this->CI->networks_model->delete_network( $account_id, $this->CI->user_id );
            
            if ( $response ) {
                
                $data = array(
                    'success' => TRUE,
                    'message' => $this->CI->lang->line('account_was_deleted'),
                    'account_id' => $account_id
                );

                echo json_encode($data);                
                
            } else {
                
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('error_occurred')
                );

                echo json_encode($data);                 
                
            }
            
        } else {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('error_occurred')
            );

            echo json_encode($data); 
            
        }
        
    }

    /**
     * The public method list_accounts_for_composer prepares the list with accounts for posts composer
     * 
     * @since 0.0.7.0
     * 
     * @return array with accounts
     */ 
    public function list_accounts_for_composer($accounts) {
        
        if ( $accounts ) {
            
            // Create array for all accounts networks
            $networks = array();
            
            // Create the accounts_list array
            $accounts_list = array();
            
            // List all accounts
            foreach ( $accounts as $account ) {
                
                // Get network's name
                $network = $account->network_name;

                // Check if the $network exists
                if ( file_exists(MIDRUB_BASE_USER . 'networks/' . $network . '.php') ) {
                    
                    // Verify if same networks was called before
                    if ( isset( $networks[$account->network_name] ) ) {
                        
                        $avatar = $account->user_avatar;
                        
                        if ( $account->network_name === 'facebook_pages' ) {
                            $avatar = 'https://graph.facebook.com/' . $account->net_id . '/picture?type=square&access_token=' . $account->secret;
                        } else if ( $account->network_name === 'facebook_groups' ) {
                            $avatar = 'https://graph.facebook.com/' . $account->net_id . '/picture?type=square&access_token=' . $account->token;
                        } else if ( $account->network_name === 'instagram_insights' ) {
                            $img_av = json_decode(get('https://graph.facebook.com/' . $account->net_id . '?fields=profile_picture_url&access_token=' . $account->token), true);
                            if ( isset($img_av['profile_picture_url']) ) {
                                $avatar = $img_av['profile_picture_url'];
                            }
                        }
                        
                        $accounts_list[] = array(
                            'network_info' => $networks[$account->network_name],
                            'network_id' => $account->network_id,
                            'net_id' => $account->net_id,
                            'user_name' => $account->user_name,
                            'network_name' => $account->network_name,
                            'display_network_name' => ucwords( str_replace('_', ' ', $account->network_name) ),
                            'user_avatar' => $avatar,
                            'network' => $network
                        );
                        
                        continue;
                        
                    }

                    // Create an array
                    $array = array(
                        'MidrubBase',
                        'User',
                        'Networks',
                        ucfirst($network)
                    );

                    // Implode the array above
                    $cl = implode('\\', $array);

                    // Get method
                    $get = (new $cl());
                    
                    // Add network info in the array
                    $networks[$account->network_name] = $get->get_info();
                    
                    $avatar = $account->user_avatar;

                    if ( $account->network_name === 'facebook_pages' ) {
                        $avatar = 'https://graph.facebook.com/' . $account->net_id . '/picture?type=square&access_token=' . $account->secret;
                    } else if ( $account->network_name === 'facebook_groups' ) {
                        $avatar = 'https://graph.facebook.com/' . $account->net_id . '/picture?type=square&access_token=' . $account->token;
                    } else if ( $account->network_name === 'instagram_insights' ) {
                        $img_av = json_decode(get('https://graph.facebook.com/' . $account->net_id . '?fields=profile_picture_url&access_token=' . $account->token), true);
                        if ( isset($img_av['profile_picture_url']) ) {
                            $avatar = $img_av['profile_picture_url'];
                        }
                    }

                    // Return array with network info and accounts
                    $accounts_list[] = array(
                        'network_info' => $get->get_info(),
                        'network_id' => $account->network_id,
                        'net_id' => $account->net_id,
                        'user_name' => $account->user_name,
                        'user_avatar' => $avatar,
                        'network_name' => $account->network_name,
                        'display_network_name' => ucwords( str_replace('_', ' ', $account->network_name) ),
                        'network' => $network
                    );

                }
            
            }
            
            return $accounts_list;
            
        } else {
            
            return array();
            
        }
        
    }
    
    /**
     * The public method get_network_icon by network's name
     * 
     * @since 0.0.7.0
     * 
     * @param string $network_name contains network's name
     * 
     * @return array with accounts
     */ 
    public function get_network_icon( $network_name ) {

        // Check if the $network exists
        if ( file_exists(MIDRUB_BASE_USER . 'networks/' . strtolower($network_name) . '.php') ) {

            // Create an array
            $array = array(
                'MidrubBase',
                'User',
                'Networks',
                ucfirst($network_name)
            );

            // Implode the array above
            $cl = implode('\\', $array);

            // Get method
            $get = (new $cl());

            // Add network info
            $info = $get->get_info();

            return str_replace(' class', ' style="color: ' . $info['color'] . ';" class', $info['icon']);

        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method get_accounts_list gets accounts list
     * 
     * @since 0.0.7.0
     * 
     * @param array $accounts_list contains an array with accounts
     * 
     * @return array with accounts
     */ 
    public function get_accounts_list( $accounts_list ) {
        
        if ( $accounts_list ) {
            
            $accounts = '';

            foreach ( $accounts_list as $account ) {
                
                $accounts .= '<li>'
                                . '<a href="#" data-id="' . $account['network_id'] . '" data-network="' . $account['network_name'] . '">'
                                    . str_replace(' class', ' style="color: ' . $account['network_info']['color'] . '" class', $account['network_info']['icon'] )
                                    . $account['user_name']
                                . '<span>'
                                    . '<i class="icon-user"></i> ' . ucwords(str_replace('_', ' ', $account['network_name']))
                                . '</span>'
                                . '<i class="icon-check"></i>'
                            . '</a>'
                        . '</li>';
                
            }
            
            return $accounts;

        } else {

            return '<li class="no-accounts-found">' . $this->CI->lang->line('no_accounts_found') . '</li>';

        }
        
    }   
    
    /**
     * The public method get_groups_list gets groups list
     * 
     * @since 0.0.7.0
     * 
     * @param array $groups_list contains an array with groups
     * 
     * @return array with groups
     */ 
    public function get_groups_list( $groups_list ) {
        
        if ( $groups_list ) {
            
            $groups = '';

            foreach ( $groups_list as $group ) {

                $groups .= '<li>'
                                . '<a href="#" data-id="' . $group->list_id . '">'
                                . '<i class="icon-folder-alt"></i>'
                                . $group->name
                                . '<i class="icon-check"></i>'
                            . '</a>'
                        . '</li>';
                
            }
            
            return $groups;

        } else {

            return '<li class="no-groups-found">' . $this->CI->lang->line('no_groups_found') . '</li>';

        }
        
    } 
    
    /**
     * The public method get_active_accounts gets active network's accounts
     * 
     * @since 0.0.7.0
     * 
     * @param object $accounts_list contains all active network's accounts
     * @param integer $active contains the account's status
     * 
     * @return string with accounts
     */ 
    public function get_active_accounts( $accounts_list, $active ) {
        
        if ( $active === 1 ) {
        
            if ( $accounts_list ) {

                $accounts = '<ul class="accounts-manager-active-accounts-list">';

                foreach ( $accounts_list as $account ) {

                    $accounts .= '<li>'
                                    . '<a href="#" data-id="' . $account->network_id . '">'
                                        . $account->user_name . ' <i class="icon-trash"></i>'
                                    . '</a>'
                                . '</li>';

                }

                return $accounts . '</ul>';

            } else {

                return '<ul class="accounts-manager-active-accounts-list"><li class="no-accounts-found">' . $this->CI->lang->line('no_accounts_found') . '</li></ul>';

            }
        } else if ( $active === 2 ) {
        
            if ( $accounts_list ) {

                $accounts = '<ul class="accounts-manager-groups-active-accounts">';

                foreach ( $accounts_list as $account ) {

                    $accounts .= '<li>'
                                    . '<a href="#" data-id="' . $account->network_id . '">'
                                        . $account->user_name . ' <i class="icon-plus"></i>'
                                    . '</a>'
                                . '</li>';

                }

                return $accounts . '</ul>';

            } else {

                return '<ul class="accounts-manager-groups-active-accounts"><li class="no-accounts-found">' . $this->CI->lang->line('no_accounts_found') . '</li></ul>';

            }
        
        } else {
            
            if ( $accounts_list ) {

                $accounts = '<ul class="accounts-manager-expired-accounts-list">';

                foreach ( $accounts_list as $account ) {

                    $accounts .= '<li>'
                                    . '<a href="#" data-id="' . $account->network_id . '">'
                                        . $account->user_name . ' <i class="icon-refresh"></i>'
                                    . '</a>'
                                . '</li>';

                }

                return $accounts . '</ul>';

            } else {

                return '';

            }            
            
        }
        
    } 
    
    /**
     * The public method get_network_instructions gets network's instructions
     * 
     * @since 0.0.7.0
     * 
     * @param string $network_name contains the network's name
     * 
     * @return string with network's insructions
     */ 
    public function get_network_instructions( $network_name ) {

        // Load the networks language file
        $this->CI->lang->load('default_networks', $this->CI->config->item('language'));

        // Returns instructions
        return $this->CI->lang->line( 'instructions_' . strtolower($network_name));
        
    } 

    /**
     * The public method get_network_info gets the network's settings
     * 
     * @since 0.0.7.0
     * 
     * @param string $network_name contains network's name
     * 
     * @return array with network's settings or false
     */ 
    public function get_network_info( $network_name ) {
        
        // Check if the $network exists
        if ( file_exists(MIDRUB_BASE_USER . 'networks/' . strtolower($network_name) . '.php') ) {

            // Create an array
            $array = array(
                'MidrubBase',
                'User',
                'Networks',
                ucfirst($network_name)
            );

            // Implode the array above
            $cl = implode('\\', $array);

            // Get method
            $get = (new $cl());

            // Add network info
            $info = $get->get_info();
            
            // Set network's settings
            $settings = array(
                'icon' => str_replace(' class', ' style="color: ' . $info['color'] . ';" class', $info['icon']),
                'hidden' => ''
                
            );
            
            if ( !empty($info['hidden']) ) {
                $settings['hidden'] = $info['hidden'];
            }
            
            if ( !empty($info['custom_connect']) ) {
                $settings['custom_connect'] = $info['custom_connect'];
            }

            return $settings;

        } else {
            
            return false;
            
        }
        
    } 
    
    /**
     * The public method composer_search_accounts gets accounts to composer by search
     * 
     * @since 0.0.7.4
     * 
     * @return void
     */
    public function composer_search_accounts() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');
            
            // Get data
            $search_key = $this->CI->input->post('key');
            $page = $this->CI->input->post('page');

            if ( $this->CI->form_validation->run() == false ) {
                
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('no_accounts_found')
                );

                echo json_encode($data);  
                
            } else {
                
                $limit = 10;

                $page--;
        
                // Set default key
                $key = '';

                // Verify if key exists
                if ( $search_key ) {
                    $key = $search_key;
                }

                // Get accounts list
                $accounts_list = $this->list_accounts_for_composer($this->CI->networks_model->get_accounts( $this->CI->user_id, ($page * $limit), $limit, $key ));
                
                // Get total number of accounts
                $total = $this->CI->networks_model->get_accounts( $this->CI->user_id, 0, 0, $key );

                // Verify accounts were found
                if ( $accounts_list ) {

                    $data = array(
                        'success' => TRUE,
                        'accounts_list' => $accounts_list,
                        'total' => $total,
                        'page' => ($page + 1)
                    );

                    echo json_encode($data);

                } else {

                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('no_accounts_found')
                    );

                    echo json_encode($data);            

                }
                
            }
            
        }
        
    }
    
    /**
     * The public method composer_search_groups gets groups to composer by search
     * 
     * @since 0.0.7.0
     * 
     * @return void
     */
    public function composer_search_groups() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');
            
            // Get data
            $search_key = $this->CI->input->post('key');
            $page = $this->CI->input->post('page');

            if ( $this->CI->form_validation->run() == false ) {
                
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('no_groups_found')
                );

                echo json_encode($data);  
                
            } else {
        
                // Load the lists model
                $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Lists_model', 'lists_model' );

                $limit = 10;

                $page--;

                // Set default key
                $key = '';

                // Verify if key exists
                if ( $search_key ) {
                    $key = $search_key;
                }

                // Get groups list
                $groups_list = $this->CI->lists_model->get_groups( $this->CI->user_id, ($page * $limit), $limit, $key );

                // Get total groups list
                $total = $this->CI->lists_model->get_groups( $this->CI->user_id, 0, 0, $key );

                // Verify groups were found
                if ( $groups_list ) {

                    $data = array(
                        'success' => TRUE,
                        'total' => $total,
                        'page' => ($page + 1),
                        'groups_list' => $groups_list
                    );

                    echo json_encode($data);

                } else {

                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('no_groups_found')
                    );

                    echo json_encode($data);            

                }
                
            }
            
        }
        
    }

}

/* End of file accounts.php */