<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
  Name: PayPal Helper
  Author: Scrisoft
  Created: 27/09/2016
 **/
if (!function_exists('pay_now')) {
    function pay_now($plan_id, $value, $code) {
        // Redirects users to the PayPal payment page where the user will pay 
        $paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
        $paypal_id = get_option('paypal-address');
        $main_logo = get_option('main-logo');
        if (!$main_logo):
            $main_logo = base_url() . 'assets/img/logo.png';
        endif;
        ?>
        <form action="<?php echo $paypal_url; ?>" method="post" name="paynow" id="sendform" style="display:none">
            <input type="hidden" name="business" value="<?php echo $paypal_id; ?>">
            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="item_name" value="Upgrade Payment">
            <input type="hidden" name="item_number" value="<?= $plan_id ?>">
            <input type="hidden" name="amount" value="<?= $value ?>">
            <input type="hidden" name="cpp_header_image" value="<?= $main_logo ?>">
            <input type="hidden" name="no_shipping" value="1">
            <input type="hidden" name="currency_code" value="<?= $code ?>">
            <input type="hidden" name="cancel_return" value="<?php echo site_url('user/plans') ?>">
            <input type="hidden" name="return" value="<?php echo site_url('user/success-payment') ?>">
            <input type="image" src="<?= $main_logo ?>" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        </form>
        <script language="javascript">
            document.forms["paynow"].submit();
        </script>
        <?php
    }
}
if (!function_exists('voguepay')) {
    function voguepay($plan_id, $value, $code, $user_id) {
        $merchant_id = get_option('merchant-id');
        $CI = get_instance();
        // Load Posts Model
        $CI->load->model('plans');
        if($CI->plans->book_payment($user_id, 'voguepay', $plan_id)) {
            ?>
            <style type="text/css">
            .form-style-1 {
                margin:10px auto;
                max-width: 400px;
                padding: 20px 12px 10px 20px;
                font: 13px "Lucida Sans Unicode", "Lucida Grande", sans-serif;
            }
            .form-style-1 li {
                padding: 0;
                display: block;
                list-style: none;
                margin: 10px 0 0 0;
            }
            .form-style-1 label{
                margin:0 0 3px 0;
                padding:0px;
                display:block;
                font-weight: bold;
            }
            .form-style-1 input[type=text], 
            .form-style-1 input[type=date],
            .form-style-1 input[type=datetime],
            .form-style-1 input[type=number],
            .form-style-1 input[type=search],
            .form-style-1 input[type=time],
            .form-style-1 input[type=url],
            .form-style-1 input[type=email],
            textarea, 
            select{
                box-sizing: border-box;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                border:1px solid #BEBEBE;
                padding: 7px;
                margin:0px;
                -webkit-transition: all 0.30s ease-in-out;
                -moz-transition: all 0.30s ease-in-out;
                -ms-transition: all 0.30s ease-in-out;
                -o-transition: all 0.30s ease-in-out;
                outline: none;
                margin-bottom: 15px;
                resize: none;
            }
            .form-style-1 input[type=text]:focus, 
            .form-style-1 input[type=date]:focus,
            .form-style-1 input[type=datetime]:focus,
            .form-style-1 input[type=number]:focus,
            .form-style-1 input[type=search]:focus,
            .form-style-1 input[type=time]:focus,
            .form-style-1 input[type=url]:focus,
            .form-style-1 input[type=email]:focus,
            .form-style-1 textarea:focus, 
            .form-style-1 select:focus{
                -moz-box-shadow: 0 0 8px #88D5E9;
                -webkit-box-shadow: 0 0 8px #88D5E9;
                box-shadow: 0 0 8px #88D5E9;
                border: 1px solid #88D5E9;
            }
            .form-style-1 .field-divided{
                width: 49%;
            }

            .form-style-1 .field-long{
                width: 100%;
            }
            .form-style-1 .field-select{
                width: 100%;
            }
            .form-style-1 .field-textarea{
                height: 100px;
                width: 93.4%;
            }
            .form-style-1 input[type=submit], .form-style-1 input[type=button]{
                background: #4B99AD;
                padding: 8px 15px 8px 15px;
                border: none;
                color: #fff;
                width: 93.4%;
            }
            .form-style-1 input[type=submit]:hover, .form-style-1 input[type=button]:hover{
                background: #4691A4;
                box-shadow:none;
                -moz-box-shadow:none;
                -webkit-box-shadow:none;
            }
            .form-style-1 .required{
                color:red;
            }
            </style>
            <form method="POST" action="https://voguepay.com/pay/" class="form-style-1" name="paynow">
                <input type="text" name="name" value="" placeholder="Enter your name" required />
                <input type="text" name="phone" value="" placeholder="Enter your phone" required />
                <input type="text" name="email" value="" placeholder="Enter your email" required />
                <input type="text" name="address" value="" placeholder="Enter your address" required />
                <input type="hidden" name="total" value="<?= $value ?>" />
                <input type="hidden" name="v_merchant_id" value="<?= $merchant_id ?>" />
                <textarea name="memo" class="field-textarea">Upgrade Payment</textarea>
                <input type="hidden" name="notify_url" value="<?php echo site_url('user/success-payment') ?>" />
                <input type="hidden" name="success_url" value="<?php echo site_url('user/success-payment') ?>" />
                <input type="hidden" name="fail_url" value="<?php echo site_url('user/success-payment') ?>" />
                <input type="submit" value="Submit">
                <input type="image" src="<?php $main_logo; ?>" alt="Submit" style="display: none" />
            </form>
            <?php
        } else {
            display_mess(45);
        }
    }
}
if (!function_exists('checkout2')) {
    function checkout2($plan_id, $value, $code) {
        // Redirects users to the 2Checkout payment page where the user will pay 
        ?>
        <form action='https://www.2checkout.com/checkout/purchase' method='post' name="paynow" id="sendform" style="display:none">
            <input type='hidden' name='sid' value='<?= get_option('2co-account-number') ?>' />
            <input type='hidden' name='mode' value='2CO' />
            <input type='hidden' name='li_0_type' value='product' />
            <input type='hidden' name='li_0_name' value='Upgrade Payment' />
            <input type='hidden' name='currency_code' value='<?= $code ?>'>
            <input type='hidden' name='li_0_price' value='<?= $value ?>' />
            <input type='hidden' name='plan_id' value='<?= $plan_id ?>' />
            <input type='hidden' name='x_receipt_link_url' value='<?php echo site_url('user/success-payment') ?>' />
            <input type="hidden" name="demo"value="Y">
            <input name='btnsubmit' type='submit' value='Checkout' />
        </form>
        <script language="javascript">
            setTimeout(function(){
                document.forms['paynow'].submit();
            },500);
        </script>
        <?php
    }
}
if (!function_exists('pay_stripe')) {
    function pay_stripe($plan_id,$value,$code,$number,$month,$year,$cvc) {
        require_once FCPATH.'vendor/stm/init.php';
        try{
            \Stripe\Stripe::setApiKey(get_option('stripe-secret'));
            $myCard = array('number' => $number, 'exp_month' => $month, 'exp_year' => $year, 'cvc' => $cvc);
            $charge = \Stripe\Charge::create(array('card' => $myCard, 'amount' => $value*100, 'currency' => $code));
            if($charge)
            {
                $data = json_decode(json_encode($charge));
                if(@$data->id)
                {
                    $amount = number_format(($data->amount/100),2);
                    return ['value' => $amount, 'code' => $data->currency, 'plan_id' => $plan_id, 'tx' => $data->id];
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
            exit();
        }
    }
}
if (!function_exists('check_payment')) {
    function check_payment() {
        // Check if returned transaction is valid and if the user has paid as you expected for the chosen plan
        $postdata = http_build_query(array('cmd' => '_notify-synch', 'tx' => get_instance()->input->get('tx', TRUE), 'at' => get_option('identity-token')));
        $opts = array('http' => array('method' => 'POST', 'header' => 'Content-type: application/json', 'content' => $postdata));
        $context = stream_context_create($opts);
        // Check if transaction exists
        $result = file_get_contents('https://www.paypal.com/cgi-bin/webscr', false, $context);
        if ($result) {
            $ed = explode('mc_gross', $result);
            if (trim($ed[0]) == 'SUCCESS') {
                // Now we check if the transaction already exists in our database
                if (is_numeric($_GET['item_number'])) {
                    $money = explode('mc_gross=', $result);
                    $value = explode('protection_eligibility', $money[1]);
                    $value = str_replace('=','',trim($value[0]));
                    $currency = explode('mc_currency=', $result);
                    $code = explode('item_number', $currency[1]);
                    $code = explode('mc_currency=',trim($code[0]));
                    $code = $code[0];
                    if (($value) AND ( $code) AND (get_instance()->input->get('item_number', TRUE)) AND (get_instance()->input->get('tx', TRUE))) {
                        return ['value' => $value, 'code' => $code, 'plan_id' => get_instance()->input->get('item_number', TRUE), 'tx' => get_instance()->input->get('tx', TRUE)];
                    }
                }
            }
        }
    }
}
if (!function_exists('vogue_success')) {
    function vogue_success($code,$user_id) {
        if($code) {
            $rd = get('https://voguepay.com/?v_transaction_id=' . $code['transaction_id'] . '&type=json&demo=true');
            if($rd) {
                $rd = json_decode($rd);
                if(@$rd->total_paid_by_buyer) {
                    $CI = get_instance();
                    // Load Posts Model
                    $CI->load->model('plans');
                    if($CI->plans->trans($user_id,$code['transaction_id'],$rd->total_paid_by_buyer)) {
                        return true;
                    } else {
                        return false;
                    }
                }                
            }
        }
    }
}
