<?php
/**
 * Csv Helper
 *
 * This file contains the class Csv
 * with all methods for CSV importation
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Chatbot\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Csv imports posts from csv
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
*/
class Csv {
    
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
     * The public method upload_csv imports replies from csv
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function upload_csv() {
        
        // Verify if post data was sent
        if ( $this->CI->input->post() ) {
            
            $type = $this->CI->security->xss_clean($_FILES['file']['type']);

            if ( $type === 'application/octet-stream' || $type === 'text/csv' || $type === 'application/vnd.ms-excel' || $type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'  ) {
                
                // Get upload limit
                $upload_limit = get_option('upload_limit');
                
                if ( !$upload_limit ) {

                    $upload_limit = 6291456;

                } else {

                    $upload_limit = $upload_limit * 1048576;

                }
                
                if ( $_FILES['file']['size'] > $upload_limit ) {
                    
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('chatbot_file_too_large')
                    );

                    echo json_encode($data);
                    die();
                    
                }
                
                // Generate a new file name
                $csv_file = uniqid() . '-' . time();

                $config['upload_path'] = 'assets/share';
                $config['file_name'] = $csv_file;
                $config['file_ext_tolower'] = TRUE;
                $this->CI->load->library('upload', $config);
                $this->CI->upload->initialize($config);
                $this->CI->upload->set_allowed_types('*');

                // Upload file 
                if ( $this->CI->upload->do_upload('file') ) {
                    
                    // Verify if the CSV file was upoaded
                    if ( file_exists($config['upload_path'] . '/' . $csv_file . '.csv') ) {
                        
                        // Decode the csv file
                        $handle = fopen($config['upload_path'] . '/' . $csv_file . '.csv', 'r');
                        
                        $row = 1;
                        
                        $count = 0;

                        while ( ($data = fgetcsv($handle, 1000, ",") ) !== FALSE) {

                            $num = count($data);
                            
                            $row++;
                            
                            $args = array(
                                'keywords' => '',
                                'response' => '',
                                'accuracy' => ''
                            );
                            
                            for ($c=0; $c < $num; $c++) {

                                switch ( $c ) {
                                
                                    case '0':
                                        $args['keywords'] = $this->CI->security->xss_clean($data[$c]);
                                        break;
                                    
                                    case '1':
                                        $args['response'] = $this->CI->security->xss_clean($data[$c]);
                                        break;
                                    
                                    case '2':
                                        $args['accuracy'] = $this->CI->security->xss_clean($data[$c]);
                                        break;
                                
                                }
                                
                            }
                            
                            // Verify if keywords is not empty
                            if ( empty($args['keywords']) ) {

                                continue;

                            } else {

                                // Try to create the reply's parameters
                                $reply = array(
                                    'user_id' => $this->CI->user_id,
                                    'body' => $args['keywords'],
                                    'created' => time()
                                );

                                // Verify if accuracy exists
                                if ( is_numeric($args['accuracy']) && ( $args['accuracy'] > 0 ) && ( $args['accuracy'] < 101 ) ) {

                                    // Set accuracy
                                    $reply['accuracy'] = $args['accuracy'];

                                } else {

                                    // Set accuracy
                                    $reply['accuracy'] = 100;

                                }

                                // Save Reply's parameters by using the Base's Model
                                $reply_id = $this->CI->base_model->insert('chatbot_replies', $reply);

                                // Verify if the reply was saved
                                if ($reply_id) {

                                    // Count the reply
                                    $count++;

                                    // Verify if the reply has a response
                                    if ( $args['response'] ) {

                                        // Try to create the reply's response
                                        $response = array(
                                            'reply_id' => $reply_id,
                                            'body' => $args['response'],
                                            'type' => 1
                                        );

                                        // Save Reply's response by using the Base's Model
                                        $this->CI->base_model->insert('chatbot_replies_response', $response);

                                    }

                                }

                            }
                            
                        }
                        
                        // Close the file
                        fclose($handle);
                        
                        // Delete the file
                        unlink($config['upload_path'] . '/' . $csv_file . '.csv');
                        
                        // Prepare the response
                        $data = array(
                            'success' => TRUE,
                            'message' => $count . $this->CI->lang->line('chatbot_replies_imported')
                        );

                        // Display the response
                        echo json_encode($data);
                        exit();
                        
                    }

                }
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('chatbot_error_occurred')
        );

        echo json_encode($data);
        
    }

    /**
     * The public method export_csv downloads replies in a CSV file
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function export_csv() {

        // Use the base model for a simply sql query
        $get_replies = $this->CI->base_model->get_data_where(
            'chatbot_replies',
            'chatbot_replies.reply_id AS reply_id, chatbot_replies.body AS keywords, chatbot_replies.accuracy AS accuracy, chatbot_replies_response.body AS response',
            array(
                'chatbot_replies.user_id' => $this->CI->user_id
            ),
            array(),
            array(),
            array(array(
                'table' => 'chatbot_replies_response',
                'condition' => 'chatbot_replies.reply_id=chatbot_replies_response.reply_id',
                'join_from' => 'LEFT'
            ))
        );

        // Verify if replies exists
        if ($get_replies) {

            // Prepare the header
            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=replies.csv");
            $csv = fopen('php://output', 'w');

            // List all replies
            foreach ( $get_replies as $reply ) {
                
                // Add reply to CSV
                fputcsv($csv, array(
                    $reply['keywords'],
                    $reply['response'],
                    $reply['accuracy']
                ));

            }
            
            // Close CSV
            fclose($csv);

        }
        
    }

    /**
     * The public method export_phone_csv downloads phone numbers in a CSV file
     * 
     * @since 0.0.8.1
     * 
     * @return void
     */ 
    public function export_phone_csv() {

        // Use the base model for a simply sql query
        $get_phone_numbers = $this->CI->base_model->get_data_where(
            'chatbot_phone_numbers',
            'body',
            array(
                'user_id' => $this->CI->user_id
            )
        );

        // Verify if phone numbers exists
        if ( $get_phone_numbers ) {

            // Prepare the header
            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=phones.csv");
            $csv = fopen('php://output', 'w');

            // List all phone numbers
            foreach ( $get_phone_numbers as $get_phone ) {
                
                // Add phone to CSV
                fputcsv($csv, array(
                    $get_phone['body']
                ));

            }
            
            // Close CSV
            fclose($csv);

        }
        
    }

    /**
     * The public method export_email_csv exports email addresses in a CSV file
     * 
     * @since 0.0.8.1
     * 
     * @return void
     */ 
    public function export_email_csv() {

        // Use the base model for a simply sql query
        $get_email_addresses = $this->CI->base_model->get_data_where(
            'chatbot_email_addresses',
            'body',
            array(
                'user_id' => $this->CI->user_id
            )
        );

        // Verify if email addresses exists
        if ( $get_email_addresses ) {

            // Prepare the header
            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=emails.csv");
            $csv = fopen('php://output', 'w');

            // List all email addresses
            foreach ( $get_email_addresses as $get_email ) {
                
                // Add email addresse to CSV
                fputcsv($csv, array(
                    $get_email['body']
                ));

            }
            
            // Close CSV
            fclose($csv);

        }
        
    }

}

/* End of file csv.php */