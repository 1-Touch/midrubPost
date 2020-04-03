<?php
/**
 * General Inc
 *
 * PHP Version 7.3
 *
 * This files contains the functions used
 * in the Upgrade views
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */

 // Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

if ( !function_exists('generate_incomplete_transaction') ) {
    
    /**
     * The function generate_incomplete_transaction generates url for payments
     * 
     * @param array $args contains the parameters
     * 
     * @return string with url
     */
    function generate_incomplete_transaction($args) {

        // Get codeigniter object instance
        $CI = &get_instance();

        // Set transaction data
        $CI->session->set_flashdata('incomplete_transaction', $args);
        
    }   
    
}


if ( !function_exists('the_complete_transaction') ) {
    
    /**
     * The function the_complete_transaction provides the transaction's data
     * 
     * @since 0.0.8.0
     * 
     * @return array with the complete transaction or boolean false
     */
    function the_complete_transaction() {

        // Get codeigniter object instance
        $CI = &get_instance();

        // Verify if flash data exists
        if ( $CI->session->flashdata('complete_transaction') ) {

            // Get complete transaction
            $transaction = $CI->session->flashdata('complete_transaction');

            // Verify if transaction's ID exists
            if ( isset($transaction['transaction_id']) ) {

                // Verify if transaction's ID is numeric
                if ( is_numeric($transaction['transaction_id']) ) {

                    // Try to find the transaction
                    $get_transaction = $CI->base_model->get_data_where('transactions', 'transactions.*, users.username', array('transactions.transaction_id' => $transaction['transaction_id'], 'transactions.user_id' => $CI->user_id),
                        array(),
                        array(),
                        array(array(
                            'table' => 'users',
                            'condition' => 'transactions.user_id=users.user_id',
                            'join_from' => 'LEFT'
                        ))
                    );

                    // Verify if the transaction exists
                    if ( $get_transaction ) {

                        // Transaction array
                        $transaction = array(
                            'transaction_id' => $get_transaction[0]['transaction_id'],
                            'user_id' => $get_transaction[0]['user_id'],
                            'net_id' => $get_transaction[0]['net_id'],
                            'amount' => $get_transaction[0]['amount'],
                            'currency' => $get_transaction[0]['currency'],
                            'gateway' => $get_transaction[0]['gateway'],
                            'status' => $get_transaction[0]['status'],
                            'created' => $get_transaction[0]['created'],
                            'username' => $get_transaction[0]['username'],
                        );

                        // Try to find the transaction's fields
                        $fields = $CI->base_model->get_data_where('transactions_fields', '*', array('transaction_id' => $transaction['transaction_id']));
                        
                        // Verify if the transaction has fields
                        if ( $fields ) {

                            // Set fields key
                            $transaction['fields'] = array();

                            // List all fields
                            foreach ( $fields as $field ) {

                                // Set field
                                $transaction['fields'][] = array(
                                    'field_name' => $field['field_name'],
                                    'field_value' => $field['field_value']
                                );

                            }

                        }

                        // Try to find the transaction's options
                        $options = $CI->base_model->get_data_where('transactions_options', '*', array('transaction_id' => $transaction['transaction_id']));
                        
                        // Verify if the transaction has options
                        if ( $options ) {

                            // Set options key
                            $transaction['options'] = array();

                            // List all options
                            foreach ( $options as $option ) {

                                // Set option
                                $transaction['options'][] = array(
                                    'option_name' => $option['option_name'],
                                    'option_value' => $option['option_value']
                                );

                            }

                        }

                        return $transaction;

                    }

                }

            }

        }

        return false;
        
    }
    
}