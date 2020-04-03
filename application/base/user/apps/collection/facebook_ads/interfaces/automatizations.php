<?php
/**
 * Automatizations
 *
 * PHP Version 7.2
 *
 * Automatizations Interface for Midrub Facebook Ads's Automatizations
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Facebook_ads\Interfaces;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Automatizations interface - allows to create automatizations for Midrub Facebook Ads's Automatizations
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
interface Automatizations {
    
    /**
     * The public method user loads the automatization's main page in the user panel
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function user();
    
    /**
     * The public method modals loads the automatization's modals in the user panel
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function modals();
    
    /**
     * The public method ajax processes the ajax's requests
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function ajax();
    
    /**
     * The public method cron_jobs loads the cron jobs commands
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function cron_jobs();
    
    /**
     * The public method delete_account is called when user's account is deleted
     * 
     * @param integer $user_id contains the user's ID
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function delete_account($user_id);
    
    /**
     * The public method load_hooks contains the automatization's hooks
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function load_hooks();
    
    /**
     * The public method automatization_info contains the automatization's info
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function automatization_info();
    
}

/* End of file automatizations.php */