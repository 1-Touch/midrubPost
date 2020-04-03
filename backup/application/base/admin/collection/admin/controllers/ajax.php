<?php
/**
 * Ajax Controller
 *
 * This file processes the Admin's ajax calls
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */

// Define the page namespace
namespace MidrubBase\Admin\Collection\Admin\Controllers;

defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\Admin\Collection\Admin\Helpers as MidrubBaseAdminCollectionAdminHelpers;

/*
 * Ajax class processes the admin component's ajax calls
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */
class Ajax {
    
    /**
     * Class variables
     *
     * @since 0.0.8.0
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.0
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();

    }
    
    /**
     * The public method load_payments_transactions loads payments transactions
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function load_payments_transactions() {
        
        // Get transactions
        (new MidrubBaseAdminCollectionAdminHelpers\Transactions)->load_payments_transactions();
        
    }

    /**
     * The public method delete_transaction deletes transaction by transaction's id
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */
    public function delete_transaction() {
        
        // Delete transaction
        (new MidrubBaseAdminCollectionAdminHelpers\Transactions)->delete_transaction();
        
    } 
    
    /**
     * The public method delete_transactions deletes transactions by transactions ids
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */
    public function delete_transactions() {
        
        // Delete transactions
        (new MidrubBaseAdminCollectionAdminHelpers\Transactions)->delete_transactions();
        
    } 

}

/* End of file ajax.php */