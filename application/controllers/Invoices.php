<?php
/**
 * Invoices Controller
 *
 * PHP Version 5.6
 *
 * Invoices file contains the Invoice Controler
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
if ( !defined('BASEPATH') ) {

    exit('No direct script access allowed');
    
}

/**
 * Invoice class - contains all methods for invoices
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Invoices extends MY_Controller {
    
    /**
     * Class variables
     */   
    private $user_id, $user_role;
    
    /**
     * Initialise the Statistics controller
     */
    public function __construct() {
        parent::__construct();
        
        // Load form helper library
        $this->load->helper('form');
        
        // Load form validation library
        $this->load->library('form_validation');
        
        // Load User Model
        $this->load->model('user');
        
        // Load User Meta Model
        $this->load->model('user_meta');
        
        // Load Tickets Model
        $this->load->model('tickets');
        
        // Load Invoices Model
        $this->load->model('invoice');
        
        // Load Templates Model
        $this->load->model('templates');
        
        // Load Main Helper
        $this->load->helper('main_helper');
        
        // Load Admin Helper
        $this->load->helper('admin_helper');
        
        // Load Fourth Helper
        $this->load->helper('fourth_helper');
        
        // Load session library
        $this->load->library('session');
        
        // Load URL Helper
        $this->load->helper('url');
        
        // Load SMTP
        $config = smtp();
        
        // Load Sending Email Class
        $this->load->library('email', $config);
        
        if ( isset($this->session->userdata['username']) ) {
            
            // Set user_id
            $this->user_id = $this->user->get_user_id_by_username($this->session->userdata['username']);
            
            // Set user_role
            $this->user_role = $this->user->check_role_by_username($this->session->userdata['username']);
            
            // Set user_status
            $this->user_status = $this->user->check_status_by_username($this->session->userdata['username']);
            
        }
        
        // Verify if exist a customized language file
        if ( file_exists( APPPATH . 'language/' . $this->config->item('language') . '/default_alerts_lang.php') ) {
            
            // load the alerts language file
            $this->lang->load( 'default_alerts', $this->config->item('language') );
            
        }
        
        // Verify if exist a customized language file
        if ( file_exists( APPPATH . 'language/' . $this->config->item('language') . '/default_admin_lang.php') ) {
            
            // load the admin language file
            $this->lang->load( 'default_admin', $this->config->item('language') );
            
        }
        
    }
    
    /**
     * The public method invoices contains the invoice's lists
     * 
     * @param integer $period contains the period of time
     * 
     * @return void
     */
    public function invoices( $period ) {
        
        // Check if the session exists and if the login user is admin
        $this->check_session($this->user_role, 1);
        
        // Get statistics template
        $this->body = 'admin/invoices';
        
        // Load the admin layout
        $this->admin_layout();
        
    }
    
    /**
     * The public method get_invoices gets invoices by page
     * 
     * @param integer $page contains the page's number
     * 
     * @return void
     */
    public function get_invoices( $page ) {
        
        // Check if the session exists and if the login user is admin
        $this->check_session($this->user_role, 1);

        // Check if data was submitted
        if ($this->input->post()) {
            
            // Add form validation
            $this->form_validation->set_rules('user', 'User', 'trim');
            $this->form_validation->set_rules('from_date', 'From Date', 'trim');
            $this->form_validation->set_rules('to_date', 'To Date', 'trim');
            
            // Get data
            $user = $this->input->post('user');
            $from_date = $this->input->post('from_date');
            $to_date = $this->input->post('to_date');
            
            // Check form validation
            if ( $this->form_validation->run() != false ) {
                
                $page --;
                $limit = 10;
                
                // Set default user_id
                $user_id = 0;
                
                // Verify if user exists
                if ( $user ) {
                    
                    // Get user by search
                    $response = $this->invoice->get_user_by_username_or_email( $user );
                    
                    if ( $response ) {
                        
                        $user_id = $response[0]->user_id;
                        
                    } else {
                        
                        // Stop running the script
                        exit();
                        
                    }
                    
                }
                
                // Set date from
                $date_from = 0;
                
                // Verify if $from_date is not empty
                if ( $from_date ) {
                    
                    $date_from = $from_date;
                    
                }
                
                // Set date to
                $date_to = 0;
                
                // Verify if $to_date is not empty
                if ( $to_date ) {
                    
                    $date_to = $to_date;
                    
                }         
                
                // Now get total number of invoices
                $total = $this->invoice->get_invoices( $page * $limit, $limit, $user_id, $date_from, $date_to, false );
                
                // Now get all invoices
                $invoices = $this->invoice->get_invoices( $page * $limit, $limit, $user_id, $date_from, $date_to, true );
                
                if ( $invoices ) {
                    
                    echo json_encode(['invoices' => $invoices, 'total' => $total]);
                    
                }
                
            }
            
        }
        
    }
    
    /**
     * The public method get_invoice gets invoice by id
     * 
     * @param integer $invoice_id contains the invoice_id
     * 
     * @return void
     */
    public function get_invoice( $invoice_id ) {
        
        // Check if the session exists and if the login user is admin
        $this->check_session($this->user_role, 1);
        
        // Get invoice details
        $invoice = $this->invoice->get_invoice( $invoice_id );    
        
        // Verify if invoice exists
        if ( $invoice ) {
            
            echo json_encode(['transaction_id' => $invoice[0]->transaction_id,
                'invoice_date' => $this->get_invoice_time($invoice[0]->invoice_date),
                'from_period' => $this->get_invoice_time($invoice[0]->from_period),
                'to_period' => $this->get_invoice_time($invoice[0]->to_period),
                'invoice_title' => $this->get_invoice_placeholders($invoice[0]->invoice_title, ['username' => $invoice[0]->username]),
                'invoice_text' => $this->get_invoice_placeholders($invoice[0]->invoice_text, ['username' => $invoice[0]->username]),
                'invoice_amount' => $invoice[0]->amount . ' ' . $invoice[0]->currency,
                'invoice_taxes' => $invoice[0]->taxes . ' %',
                'invoice_total' => $invoice[0]->total . ' ' . $invoice[0]->currency,
                'plan_name' => $invoice[0]->plan_name]);
            
        }
        
    }
    
    /**
     * The public method print_invoice displays the invoice data and user can print
     * 
     * @param integer $invoice_id contains the invoice_id
     * 
     * @return void
     */
    public function print_invoice( $invoice_id ) {
        
        // Check if the current user is admin and if session exists
        $this->check_session($this->user_role, 0);
        
        // Get invoice details
        $invoice = $this->invoice->get_invoice( $invoice_id );    
        
        // Verify if invoice exists
        if ( $invoice ) {
            
            if ( $invoice[0]->user_id === $this->user_id ) {
            
                // Get the invoice logo
                $invoice_logo = get_option( 'main-logo' );

                if ( get_option( 'invoice-logo' ) ) {

                    $invoice_logo = get_option( 'invoice-logo' );

                }

                // Get the invoice billing period text
                $billing_period = 'Billing Period';

                if ( get_option( 'invoice-billing-period' ) ) {

                    $billing_period = get_option( 'invoice-billing-period' );

                }   

                // Get the invoice transaction id
                $transaction_id = 'Transaction ID';

                if ( get_option( 'invoice-transaction-id' ) ) {

                    $transaction_id = get_option( 'invoice-transaction-id' );

                }         

                // Get the invoice date format
                $date_format = 'dd-mm-yyyy';

                if ( get_option( 'invoice-date-format' ) ) {

                    $date_format = get_option( 'invoice-date-format' );

                }

                // Get the invoice hello text
                $hello_text = 'Hello [username]';

                if ( get_option( 'invoice-hello-text' ) ) {

                    $hello_text = get_option( 'invoice-hello-text' );

                }

                // Get the invoice message
                $message = 'Thanks for using using our services.';

                if ( get_option( 'invoice-message' ) ) {

                    $message = get_option( 'invoice-message' );

                }

                // Get the invoice date word
                $date = 'Date';

                if ( get_option( 'invoice-date' ) ) {

                    $date = get_option( 'invoice-date' );

                }

                // Get the invoice description word
                $description = 'Description';

                if ( get_option( 'invoice-description' ) ) {

                    $description = get_option( 'invoice-description' );

                }  

                // Get the invoice description text
                $description_text = 'Upgrade Payment';

                if ( get_option( 'invoice-description-text' ) ) {

                    $description_text = get_option( 'invoice-description-text' );

                }  

                // Get the invoice amount word
                $amount = 'Amount';

                if ( get_option( 'invoice-amount' ) ) {

                    $amount = get_option( 'invoice-amount' );

                }  

                // Get the invoice amount word
                $taxes = 'Taxes';

                if ( get_option( 'invoice-taxes' ) ) {

                    $taxes = get_option( 'invoice-taxes' );

                }  

                // Get the invoice taxes value
                $taxes_value = '';

                if ( get_option( 'invoice-taxes-value' ) ) {

                    $taxes_value = get_option( 'invoice-taxes-value' );

                }       

                // Get the invoice total word
                $total = 'Total';

                if ( get_option( 'invoice-total' ) ) {

                    $total = get_option( 'invoice-total' );

                }

                // Get the no reply message
                $no_reply = 'Please do not reply to this email. This mailbox is not monitored and you will not receive a response. For assistance, please contact us to info@ouremail.com.';

                if ( get_option( 'invoice-no-reply' ) ) {

                    $no_reply = get_option( 'invoice-no-reply' );

                }  
                
                echo '<table cellpadding="0" cellspacing="0" border="0" width="1000px">
                            <tbody><tr><td align="center" width="600" valign="top">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tbody>
                                                <tr>
                                                    <td align="center" valign="top" bgcolor="#ffffff">
                                                        <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:10px;" width="100%">
                                                            <tbody><tr valign="bottom">    
                                                                    <td width="20" align="center" valign="top">&nbsp;</td>
                                                                    <td align="left" height="64">
                                                                        <img alt="logo" class="invoice-logo" src="' . $invoice_logo . '">
                                                                    </td>   
                                                                    <td width="40" align="center" valign="top">&nbsp;</td>
                                                                    <td align="right">
                                                                        <span style="padding-top:15px;padding-bottom:10px;font:italic 12px;color:#757575;line-height:15px;">
                                                                            <span style="display:inline;">
                                                                                <span class="invoice-billing-period">' . $billing_period . '</span> <strong><span class="invoice-date-format billing-period-from">' . $this->get_invoice_time($invoice[0]->from_period) . '</span> to <span class="invoice-date-format billing-period-to">' . $this->get_invoice_time($invoice[0]->to_period) . '</span></strong>
                                                                            </span>
                                                                            <span style="display:inline;">
                                                                                <br>
                                                                                <span class="invoice-transaction-id">' . $transaction_id . '</span>: <strong class="transaction-id">' . $invoice[0]->transaction_id . '</strong>
                                                                            </span>
                                                                        </span>
                                                                    </td>
                                                                    <td width="20" align="center" valign="top">&nbsp;</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <table border="0" cellpadding="0" cellspacing="0" style="padding-bottom:10px;padding-top:10px;margin-bottom:20px;" width="100%">
                                                            <tbody><tr valign="bottom">    
                                                                    <td width="20" align="center" valign="top">&nbsp;</td>
                                                                    <td valign="top" style="font-family:Calibri, Trebuchet, Arial, sans serif;font-size:15px;line-height:22px;color:#333333;" class="yiv1811948700ppsans">
                                                                        <p>
                                                                        </p><div style="margin-top:30px;color:#333;font-family:arial, helvetica, sans-serif;font-size:12px;"><span style="color:#333333;font-weight:bold;font-family:arial, helvetica, sans-serif; margin-left: 2px;" class="invoice-hello-text">' . $this->get_invoice_placeholders($hello_text, array('username' => $invoice[0]->username) ) . ' </span><table><tbody><tr><td valign="top" class="invoice-message">' . $this->get_invoice_placeholders($message, array('username' => $invoice[0]->username) ) . '</td><td></td></tr></tbody></table><br><div style="margin-top:5px;">
                                                                                <br><div class="yiv1811948700mpi_image" style="margin:auto;clear:both;">
                                                                                </div>
                                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" style="clear:both;color:#333;font-family:arial, helvetica, sans-serif;font-size:12px;margin-top:20px;" width="100%">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td style="text-align:left;border:1px solid #ccc;border-right:none;border-left:none;padding:5px 10px 5px 10px !important;color:#333333;" class="invoice-date" width="10%">' . $date . '</td>
                                                                                            <td style="border:1px solid #ccc;border-right:none;border-left:none;padding:5px 10px 5px 10px !important;color:#333333;" width="80%" class="invoice-description">' . $description . '</td>
                                                                                            <td style="text-align:right;border:1px solid #ccc;border-right:none;border-left:none;padding:5px 10px 5px 10px !important;color:#333333;" width="10%" class="amount">' . $amount . '</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td style="padding:10px;" width="80%">
                                                                                                <span class="invoice-description-text">' . $description_text . '</span>
                                                                                                <br>

                                                                                                <span style="display:inline;font-style: italic;color: #888888;" class="invoice-plan-name">' . $invoice[0]->plan_name . '</span>
                                                                                            </td>
                                                                                            <td style="text-align:right;padding:10px;" width="10%"></td>
                                                                                            <td style="text-align:right;padding:10px;" width="10%" class="invoice-amount">' . $invoice[0]->amount . ' ' . $invoice[0]->currency . '</td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" style="border-top:1px solid #ccc;border-bottom:1px solid #ccc;color:#333;font-family:arial, helvetica, sans-serif;font-size:12px;margin-bottom:10px;" width="100%">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <table border="0" cellpadding="0" cellspacing="0" style="width:100%;color:#333;font-family:arial, helvetica, sans-serif;font-size:12px;margin-top:10px;">
                                                                                                    <tbody>
                                                                                                        <tr class="taxes-area" style="display: none;">
                                                                                                            <td style="width:80%;text-align:right;padding:0 10px 10px 0;" class="invoice-taxes">' . $taxes . '</td>
                                                                                                            <td style="width:20%;text-align:right;padding:0 10px 10px 0;">
                                                                                                                <span style="display:inline;" class="invoice-taxes-value">' . $taxes_value . ' %</span>

                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td style="width:80%;text-align:right;padding:0 10px 10px 0;">
                                                                                                                <span style="color:#333333;font-weight:bold;" class="invoice-total">' . $total . '</span>
                                                                                                            </td>
                                                                                                            <td style="width:20%;text-align:right;padding:0 10px 10px 0;" class="invoice-total-value">' . $invoice[0]->total . ' ' . $invoice[0]->currency . '</td>
                                                                                                        </tr>
                                                                                                    </tbody></table>
                                                                                            </td>
                                                                                        </tr>

                                                                                    </tbody></table>
                                                                                <span style="font-size:11px;color:#333;" class="invoice-no-reply">' . $no_reply . '</span></div>
                                                                            <span style="font-weight:bold;color:#444;">
                                                                            </span>
                                                                            <span>
                                                                            </span>
                                                                        </div></td>
                                                                        <td width="20" align="center" valign="top">&nbsp;</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                                                    </td>
                                                </tr>
                                        </tbody>
                                    </table>            
                                    </td>
                                </tr>
                            </tbody>
                        </table>';
                
                exit();
                
            }
            
        }
        
        show_404();
        
    }
    
    /**
     * The public method delete_invoice deletes invoice by id
     * 
     * @param integer $invoice_id contains the invoice_id
     * 
     * @return void
     */
    public function delete_invoice( $invoice_id ) {
        
        // Check if the session exists and if the login user is admin
        $this->check_session($this->user_role, 1);
        
        // Get invoice details
        $invoice = $this->invoice->delete_invoice( $invoice_id );        
        
        // Verify if invoice exists
        if ( $invoice ) {
            
            echo json_encode(1);
            
        }
        
    }
    
    /**
     * The public method send_invoice sends invoice to user
     * 
     * @param integer $invoice_id contains the invoice_id
     * 
     * @return void
     */
    public function send_invoice( $invoice_id ) {
        
        // Check if the session exists and if the login user is admin
        $this->check_session($this->user_role, 1);
        
        // Load the function send_invoice
        if ( sends_invoice($invoice_id) ) {
            
            echo json_encode(1);
            
        }
        
    }    
    
    /**
     * The public method invoice_settings provides the invoice settings
     * 
     * @return void
     */
    public function invoice_settings() {
        
        // Check if the session exists and if the login user is admin
        $this->check_session($this->user_role, 1);
        
        // Get statistics template
        $this->body = 'admin/invoice-settings';
        
        // Load the admin layout
        $this->admin_layout();
        
    }   
    
    /**
     * The public method get_invoice_settings gets the invoice's settings
     * 
     * @return void
     */
    public function get_invoice_settings() {
        
        // Check if the session exists and if the login user is admin
        $this->check_session($this->user_role, 1);
        
        // Get the invoice logo
        $invoice_logo = get_option( 'main-logo' );
        
        if ( get_option( 'invoice-logo' ) ) {
            
            $invoice_logo = get_option( 'invoice-logo' );
            
        }
        
        // Get the invoice billing period text
        $billing_period = 'Billing Period';
        
        if ( get_option( 'invoice-billing-period' ) ) {
            
            $billing_period = get_option( 'invoice-billing-period' );
            
        }   
        
        // Get the invoice transaction id
        $transaction_id = 'Transaction ID';
        
        if ( get_option( 'invoice-transaction-id' ) ) {
            
            $transaction_id = get_option( 'invoice-transaction-id' );
            
        }         
        
        // Get the invoice date format
        $date_format = 'dd-mm-yyyy';
        
        if ( get_option( 'invoice-date-format' ) ) {
            
            $date_format = get_option( 'invoice-date-format' );
            
        }
        
        // Get the invoice hello text
        $hello_text = 'Hello [username]';
        
        if ( get_option( 'invoice-hello-text' ) ) {
            
            $hello_text = get_option( 'invoice-hello-text' );
            
        }
        
        // Get the invoice message
        $message = 'Thanks for using using our services.';
        
        if ( get_option( 'invoice-message' ) ) {
            
            $message = get_option( 'invoice-message' );
            
        }
        
        // Get the invoice date word
        $date = 'Date';
        
        if ( get_option( 'invoice-date' ) ) {
            
            $date = get_option( 'invoice-date' );
            
        }

        // Get the invoice description word
        $description = 'Description';
        
        if ( get_option( 'invoice-description' ) ) {
            
            $description = get_option( 'invoice-description' );
            
        }  
        
        // Get the invoice description text
        $description_text = 'Upgrade Payment';
        
        if ( get_option( 'invoice-description-text' ) ) {
            
            $description_text = get_option( 'invoice-description-text' );
            
        }  
        
        // Get the invoice amount word
        $amount = 'Amount';
        
        if ( get_option( 'invoice-amount' ) ) {
            
            $amount = get_option( 'invoice-amount' );
            
        }  
        
        // Get the invoice amount word
        $taxes = 'Taxes';
        
        if ( get_option( 'invoice-taxes' ) ) {
            
            $taxes = get_option( 'invoice-taxes' );
            
        }  

        // Get the invoice taxes value
        $taxes_value = '';
        
        if ( get_option( 'invoice-taxes-value' ) ) {
            
            $taxes_value = get_option( 'invoice-taxes-value' );
            
        }       
        
        // Get the invoice total word
        $total = 'Total';
        
        if ( get_option( 'invoice-total' ) ) {
            
            $total = get_option( 'invoice-total' );
            
        }
        
        // Get the no reply message
        $no_reply = 'Please do not reply to this email. This mailbox is not monitored and you will not receive a response. For assistance, please contact us to info@ouremail.com.';
        
        if ( get_option( 'invoice-no-reply' ) ) {
            
            $no_reply = get_option( 'invoice-no-reply' );
            
        }        
        
        echo json_encode(['billing_period' => $billing_period, 'transaction_id' => $transaction_id, 'invoice_logo' => $invoice_logo, 'invoice_date' => $date_format, 'hello_text' => $hello_text, 'invoice_message' => $message, 'date' => $date, 'description' => $description, 'description_text' => $description_text, 'amount' => $amount, 'taxes' => $taxes, 'taxes_value' => $taxes_value, 'no_reply' => $no_reply, 'total' => $total, 'no_reply' => $no_reply]);
        
    } 
    
    /**
     * The private method get_invoice_time gets the invoice's formatted time
     * 
     * @param string $date contains the date
     * 
     * @return string with correct date
     */
    private function get_invoice_time( $date ) {
        
        // Get the invoice date format
        $date_format = 'dd-mm-yyyy';
        
        if ( get_option( 'invoice-date-format' ) ) {
            
            $date_format = get_option( 'invoice-date-format' );
            
        }
        
        return str_replace(['dd', 'mm', 'yyyy'], [date('d', strtotime($date)), date('m', strtotime($date)), date('Y', strtotime($date))], $date_format);
        
    }
    
    /**
     * The private method get_invoice_placeholders processes and replaces placeholders 
     * 
     * @param string $data contains the string
     * @param array $args contains the replaced content
     * 
     * @return string with new content
     */
    private function get_invoice_placeholders( $data, $args ) {
        
        $data = str_replace('[username]', $args['username'], $data);
        
        return $data;
        
    }    
    
}

/* End of file Invoices.php */
