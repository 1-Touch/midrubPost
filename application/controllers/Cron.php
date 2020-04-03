<?php
/**
 * Cron Controller
 *
 * PHP Version 7.2
 *
 * Cron contains the Cron Controller
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */

 // Constants
defined('BASEPATH') OR exit('No direct script access allowed');
defined('MIDRUB_BASE_USER') OR define('MIDRUB_BASE_USER', APPPATH . 'base/user/');

/**
 * Cron class - runs all Midrub's cron jobs commands
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Cron extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Load Main Helper
        $this->load->helper('main_helper');
        
        // Load User Model
        $this->load->model('user');
        
        // Load User Meta Model
        $this->load->model('user_meta');
        
        // Load Plans Model
        $this->load->model('plans');
        
        // Load SMTP
        $config = smtp();
        
        // Load Sending Email Class
        $this->load->library('email', $config);
        
    }
    
    /**
     * The public method run is called to run all Midrub's cron jobs
     * 
     * @return void
     */
    public function run() {
        
        // Require the base class
        $this->load->file(APPPATH . '/base/main.php');

        // List all user's apps
        foreach (glob(APPPATH . 'base/user/apps/collection/*', GLOB_ONLYDIR) as $directory) {

            // Get the directory's name
            $app = trim(basename($directory) . PHP_EOL);

            // Verify if the app is enabled
            if (!get_option('app_' .  $app . '_enable')) {
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

            // Run cron job commands
            (new $cl())->cron_jobs();
        }
        
        // Prepare allowed files
        $allowed_files = array(
            'cron.php',
            'index.php',
            'ipn-listener.php',
            'update.php'
        );
        
        // List all files
        foreach (glob(FCPATH . '/*.php') as $filename) {
            
            $name = str_replace(FCPATH . '/', '', $filename);

            if ( !in_array($name, $allowed_files) ) {
                
                $msg = 'Was detected and deleted the file ' . $filename . '. Please contact support if you don\'t know this file.';
                
                $this->send_warning_message($msg);
                unlink($filename);
                
            } else {
                
                if ( $this->check_for_malware($filename) ) {
                    
                    $msg = 'Was detected malware in the file ' . $filename . ' and was deleted.';

                    $this->send_warning_message($msg);
                    unlink($filename);
                    
                }
                
            }
            
        }
        
        $extensions = array(
            '.php3',
            '.php4',
            '.php5',
            '.php7',
            '.phtml',
            '.pht'
        );

        foreach ( $extensions as $ext ) {
            $this->delete_files_by_extension(FCPATH, $ext);   
        }
        
        $extensions[] = '.php';
        
        foreach ( $extensions as $ext ) {
            $this->delete_files_by_extension(FCPATH . 'assets', $ext);   
        }
        
        $this->check_for_non_midrub_files(FCPATH . 'assets');
        
    }
    
    /**
     * The protected method check_for_non_midrub_files verifies for non midrub's files
     * 
     * @param string $path contains the path to index
     * 
     * @return void
     */
    protected function check_for_non_midrub_files($path) {
        
        $dirs = scandir($path);

        unset($dirs[array_search('.', $dirs, true)]);
        unset($dirs[array_search('..', $dirs, true)]);

        if (count($dirs) < 1) {
            return;
        }


        foreach($dirs as $dir){
            
            if ( is_dir( $path . '/' . $dir ) ) {
                
                $extensions = array(
                    '.php',
                    '.php3',
                    '.php4',
                    '.php5',
                    '.php7',
                    '.phtml',
                    '.pht'
                );

                foreach ( $extensions as $ext ) {
                    $this->delete_files_by_extension($path . '/' . $dir, $ext);   
                }
                
                $this->check_for_non_midrub_files( $path . '/' . $dir );
                
            }
            
        }
        
    }
    
    /**
     * The protected delete_files_by_extension deletes all files by extension
     * 
     * @param string $path contains the dir path
     * @param string $ext the files extension
     * 
     * @return void
     */
    protected function delete_files_by_extension($path, $ext) {
        
        if ( glob($path . '/*' . $ext) ) {
        
            foreach (glob($path . '/*' . $ext) as $filename) {
                
                $msg = 'Was detected and deleted the file ' . $filename . '. Please contact support if you don\'t know this file.';
                
                $this->send_warning_message($msg);
                
                unlink($filename);
            }
        
        }
        
    } 
    
    /**
     * The protected check_for_malware verifies if a file has malware
     * 
     * @param string $file contains the file's path
     * 
     * @return boolean true or false
     */
    protected function check_for_malware($file) {
        
        header('Content-Type: text/plain');
        
        $contents = file_get_contents($file);
        
        $searchfor = 'base64';
        
        $pattern = preg_quote($searchfor, '/');
        
        $pattern = "/^.*$pattern.*\$/m";

        if (preg_match_all($pattern, $contents, $matches)) {
            return true;
        }
        
        $searchfor = 'eval';
        
        $pattern = preg_quote($searchfor, '/');
        
        $pattern = "/^.*$pattern.*\$/m";        
        
        if (preg_match_all($pattern, $contents, $matches)) {
            return true;
        }
        
        $searchfor = 'rawurldecode';
        
        $pattern = preg_quote($searchfor, '/');
        
        $pattern = "/^.*$pattern.*\$/m";        
        
        if (preg_match_all($pattern, $contents, $matches)) {
            return true;
        }        
        
        return false;
        
    } 
    
    /**
     * The protected send_warning_message sends alert by email
     * 
     * @param string $msg contains message to send
     * 
     * @return void
     */
    protected function send_warning_message($msg) {
        
        $this->email->from($this->config->item('contact_mail'), $this->config->item('site_name'));
        $this->email->to($this->config->item('notification_mail'));
        $this->email->subject('Unexpected files on your server');
        $this->email->message($msg);
        $this->email->send();
        
    }     
    
}

/* End of file Cron.php */
