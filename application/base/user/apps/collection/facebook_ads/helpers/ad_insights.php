<?php
/**
 * Facebook Ad_insights Helper
 *
 * This file contains the class Ad_insights
 * with methods to get insights
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Facebook_ads\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Ad_insights class provides methods to get insights
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
*/
class Ad_insights {
    
    /**
     * Class variables
     *
     * @since 0.0.7.7
     */
    protected $CI, $fb, $app_id, $app_secret;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.7
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Load models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'models/', 'Ads_networks_model', 'ads_networks_model' );
        
    }
    
    /**
     * The function load_account_insights gets Ad Accounts insights
     * 
     * @return void
     */
    public function load_account_insights() {
        
        // Get type
        $type = $this->CI->input->get('type', TRUE);
        
        // Get selected account
        $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');        
        
        if ( is_numeric($type) && $account ) {

            switch($type) {
                
                case '1':
            
                    // Get account insights
                    $account_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-1 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);
                    
                    if ( @$account_insights['data'][0] ) {
                    
                        $data = array(
                            'success' => TRUE,
                            'account_insights' => $account_insights['data']
                        );

                        echo json_encode($data);
                        exit();
                        
                    }
                    
                    break;
                
                case '2':
            
                    // Get account insights
                    $account_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-7 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);
                    
                    if ( @$account_insights['data'][0] ) {
                    
                        $data = array(
                            'success' => TRUE,
                            'account_insights' => $account_insights['data']
                        );

                        echo json_encode($data);
                        exit();
                        
                    }
                    
                    break;
                
                case '3':

                    // Get account insights
                    $account_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-30 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);
                    
                    if ( @$account_insights['data'][0] ) {
                    
                        $data = array(
                            'success' => TRUE,
                            'account_insights' => $account_insights['data']
                        );

                        echo json_encode($data);
                        exit();
                        
                    }
                    
                    break;
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('no_insights_found')
        );

        echo json_encode($data); 
        
    }
    
    /**
     * The function ad_campaigns_insights_by_time gets Ad Campaign insights
     * 
     * @return void
     */
    public function ad_campaigns_insights_by_time() {
        
        // Get order
        $order = $this->CI->input->get('order', TRUE);
        
        // Get Campaign ID
        $campaign_id = $this->CI->input->get('campaign_id', TRUE);
        
        if ( !$campaign_id ) {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('please_select_ad_campaign')
            );

            echo json_encode($data);
            exit();            
            
        }

        // Get selected account
        $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');        
        
        if ( is_numeric($order) && $account ) {

            switch($order) {
                
                case '1':
            
                    // Get campaign insights
                    $campaign_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $campaign_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-1 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);
                    
                    if ( @$campaign_insights['data'][0] ) {
                    
                        $data = array(
                            'success' => TRUE,
                            'campaign_insights' => $campaign_insights['data']
                        );

                        echo json_encode($data);
                        exit();
                        
                    }
                    
                    break;
                
                case '2':
            
                    // Get campaign insights
                    $campaign_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $campaign_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-7 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);
                    
                    if ( @$campaign_insights['data'][0] ) {
                    
                        $data = array(
                            'success' => TRUE,
                            'campaign_insights' => $campaign_insights['data']
                        );

                        echo json_encode($data);
                        exit();
                        
                    }
                    
                    break;
                
                case '3':

                    // Get campaign insights
                    $campaign_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $campaign_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-30 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);
                    
                    if ( @$campaign_insights['data'][0] ) {
                    
                        $data = array(
                            'success' => TRUE,
                            'campaign_insights' => $campaign_insights['data']
                        );

                        echo json_encode($data);
                        exit();
                        
                    }
                    
                    break;
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('no_insights_found')
        );

        echo json_encode($data); 
        
    }
    
    /**
     * The function ad_sets_insights_by_time gets Ad Set insights
     * 
     * @return void
     */
    public function ad_sets_insights_by_time() {
        
        // Get order
        $order = $this->CI->input->get('order', TRUE);
        
        // Get Ad Set ID
        $ad_set_id = $this->CI->input->get('ad_set_id', TRUE);
        
        if ( !$ad_set_id ) {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('please_select_ad_set')
            );

            echo json_encode($data);
            exit();            
            
        }

        // Get selected account
        $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');        
        
        if ( is_numeric($order) && $account ) {

            switch($order) {
                
                case '1':
            
                    // Get ad set insights
                    $ad_set_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $ad_set_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-1 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);
                    
                    if ( @$ad_set_insights['data'][0] ) {
                    
                        $data = array(
                            'success' => TRUE,
                            'ad_set_insights' => $ad_set_insights['data']
                        );

                        echo json_encode($data);
                        exit();
                        
                    }
                    
                    break;
                
                case '2':
            
                    // Get ad set insights
                    $ad_set_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $ad_set_id. '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-7 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);
                    
                    if ( @$ad_set_insights['data'][0] ) {
                    
                        $data = array(
                            'success' => TRUE,
                            'ad_set_insights' => $ad_set_insights['data']
                        );

                        echo json_encode($data);
                        exit();
                        
                    }
                    
                    break;
                
                case '3':

                    // Get ad set insights
                    $ad_set_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $ad_set_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-30 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);
                    
                    if ( @$ad_set_insights['data'][0] ) {
                    
                        $data = array(
                            'success' => TRUE,
                            'ad_set_insights' => $ad_set_insights['data']
                        );

                        echo json_encode($data);
                        exit();
                        
                    }
                    
                    break;
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('no_insights_found')
        );

        echo json_encode($data); 
        
    }
    
    /**
     * The function ad_insights_by_time gets Ad insights
     * 
     * @return void
     */
    public function ad_insights_by_time() {
        
        // Get order
        $order = $this->CI->input->get('order', TRUE);
        
        // Get Ad ID
        $ad_id = $this->CI->input->get('ad_id', TRUE);
        
        if ( !$ad_id ) {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('please_select_ad')
            );

            echo json_encode($data);
            exit();            
            
        }

        // Get selected account
        $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');        
        
        if ( is_numeric($order) && $account ) {

            switch($order) {
                
                case '1':
            
                    // Get ad insights
                    $ad_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $ad_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-1 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);
                    
                    if ( @$ad_insights['data'][0] ) {
                    
                        $data = array(
                            'success' => TRUE,
                            'ad_insights' => $ad_insights['data']
                        );

                        echo json_encode($data);
                        exit();
                        
                    }
                    
                    break;
                
                case '2':
            
                    // Get ad insights
                    $ad_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $ad_id. '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-7 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);
                    
                    if ( @$ad_insights['data'][0] ) {
                    
                        $data = array(
                            'success' => TRUE,
                            'ad_insights' => $ad_insights['data']
                        );

                        echo json_encode($data);
                        exit();
                        
                    }
                    
                    break;
                
                case '3':

                    // Get ad insights
                    $ad_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $ad_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-30 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);
                    
                    if ( @$ad_insights['data'][0] ) {
                    
                        $data = array(
                            'success' => TRUE,
                            'ad_insights' => $ad_insights['data']
                        );

                        echo json_encode($data);
                        exit();
                        
                    }
                    
                    break;
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('no_insights_found')
        );

        echo json_encode($data); 
        
    }
    
    /**
     * The function insights_download_for_account downloads Ad Accounts insights
     * 
     * @return void
     */
    public function insights_download_for_account() {
        
        // Get type
        $type = $this->CI->input->get('type', TRUE);
        
        // Get selected account
        $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');        
        
        if ( is_numeric($type) && $account ) {

            switch($type) {
                
                case '1':
            
                    // Get account insights
                    $account_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-1 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);

                    if ( @$account_insights['data'][0] ) {
                        
                        if ( !$this->CI->input->get('download', TRUE) ) {

                            $data = array(
                                'success' => TRUE,
                                'account_insights' => $account_insights['data']
                            );

                            echo json_encode($data);
                            exit();

                        }
                        
                        header("Content-type: application/csv");
                        header("Content-Disposition: attachment; filename=insights.csv");
                        $csv = fopen('php://output', 'w');
                        
                        fputcsv($csv, array(
                            $this->CI->lang->line('date'),
                            $this->CI->lang->line('impressions'),
                            $this->CI->lang->line('reach'),
                            $this->CI->lang->line('clicks'),
                            $this->CI->lang->line('cpm'),
                            $this->CI->lang->line('cpc'),
                            $this->CI->lang->line('ctr'),
                            $this->CI->lang->line('spent')
                        ));

                        foreach ( $account_insights['data'] as $data ) {
                            
                            $cpc = 0;
                            
                            if ( isset($data['cpc']) ) {
                                $cpc = $data['cpc'];
                            }
                            
                            fputcsv($csv, array(
                                $data['date_start'],
                                $data['impressions'],
                                $data['reach'],
                                $data['unique_clicks'],
                                $data['cpm'],
                                $cpc,
                                $data['ctr'],
                                $data['account_currency'] . ' ' . $data['spend']
                            ));
                      
                            

                        }
                        
                        fclose($csv);
                        
                        exit();
                        
                    }
                    
                    break;
                
                case '2':
            
                    // Get account insights
                    $account_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-7 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);
                    
                    if ( @$account_insights['data'][0] ) {
                        
                        if ( !$this->CI->input->get('download', TRUE) ) {

                            $data = array(
                                'success' => TRUE,
                                'account_insights' => $account_insights['data']
                            );

                            echo json_encode($data);
                            exit();

                        }
                    
                        header("Content-type: application/csv");
                        header("Content-Disposition: attachment; filename=insights.csv");
                        $csv = fopen('php://output', 'w');
                        
                        fputcsv($csv, array(
                            $this->CI->lang->line('date'),
                            $this->CI->lang->line('impressions'),
                            $this->CI->lang->line('reach'),
                            $this->CI->lang->line('clicks'),
                            $this->CI->lang->line('cpm'),
                            $this->CI->lang->line('cpc'),
                            $this->CI->lang->line('ctr'),
                            $this->CI->lang->line('spent')
                        ));

                        foreach ( $account_insights['data'] as $data ) {
                            
                            $cpc = 0;
                            
                            if ( isset($data['cpc']) ) {
                                $cpc = $data['cpc'];
                            }
                            
                            fputcsv($csv, array(
                                $data['date_start'],
                                $data['impressions'],
                                $data['reach'],
                                $data['unique_clicks'],
                                $data['cpm'],
                                $cpc,
                                $data['ctr'],
                                $data['account_currency'] . ' ' . $data['spend']
                            ));
                      
                            

                        }
                        
                        fclose($csv);
                        
                        exit();
                        
                    }
                    
                    break;
                
                case '3':

                    // Get account insights
                    $account_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $account[0]->net_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-30 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);
                    
                    if ( @$account_insights['data'][0] ) {
                        
                        if ( !$this->CI->input->get('download', TRUE) ) {

                            $data = array(
                                'success' => TRUE,
                                'account_insights' => $account_insights['data']
                            );

                            echo json_encode($data);
                            exit();

                        }
                        
                        header("Content-type: application/csv");
                        header("Content-Disposition: attachment; filename=insights.csv");
                        $csv = fopen('php://output', 'w');
                        
                        fputcsv($csv, array(
                            $this->CI->lang->line('date'),
                            $this->CI->lang->line('impressions'),
                            $this->CI->lang->line('reach'),
                            $this->CI->lang->line('clicks'),
                            $this->CI->lang->line('cpm'),
                            $this->CI->lang->line('cpc'),
                            $this->CI->lang->line('ctr'),
                            $this->CI->lang->line('spent')
                        ));

                        foreach ( $account_insights['data'] as $data ) {
                            
                            $cpc = 0;
                            
                            if ( isset($data['cpc']) ) {
                                $cpc = $data['cpc'];
                            }
                            
                            fputcsv($csv, array(
                                $data['date_start'],
                                $data['impressions'],
                                $data['reach'],
                                $data['unique_clicks'],
                                $data['cpm'],
                                $cpc,
                                $data['ctr'],
                                $data['account_currency'] . ' ' . $data['spend']
                            ));
                      
                            

                        }
                        
                        fclose($csv);
                        
                        exit();
                        
                    }
                    
                    break;
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('no_insights_found')
        );

        echo json_encode($data); 
        
    }
    
    /**
     * The function insights_download_for_campaigns downloads Ad Campaigns insights
     * 
     * @return void
     */
    public function insights_download_for_campaigns() {
        
        // Get order
        $order = $this->CI->input->get('order', TRUE);
        
        // Get Campaign ID
        $campaign_id = $this->CI->input->get('campaign_id', TRUE);
        
        if ( !$campaign_id ) {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('please_select_ad_campaign')
            );

            echo json_encode($data);
            exit();            
            
        }
        
        // Get selected account
        $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');        
        
        if ( is_numeric($order) && $account ) {

            switch($order) {
                
                case '1':
            
                    // Get campaign insights
                    $campaign_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $campaign_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-1 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);

                    if ( @$campaign_insights['data'][0] ) {
                        
                        if ( !$this->CI->input->get('download', TRUE) ) {

                            $data = array(
                                'success' => TRUE,
                                'campaign_insights' => $campaign_insights['data']
                            );

                            echo json_encode($data);
                            exit();

                        }
                        
                        header("Content-type: application/csv");
                        header("Content-Disposition: attachment; filename=insights.csv");
                        $csv = fopen('php://output', 'w');
                        
                        fputcsv($csv, array(
                            $this->CI->lang->line('date'),
                            $this->CI->lang->line('impressions'),
                            $this->CI->lang->line('reach'),
                            $this->CI->lang->line('clicks'),
                            $this->CI->lang->line('cpm'),
                            $this->CI->lang->line('cpc'),
                            $this->CI->lang->line('ctr'),
                            $this->CI->lang->line('spent')
                        ));

                        foreach ( $campaign_insights['data'] as $data ) {
                            
                            $cpc = 0;
                            
                            if ( isset($data['cpc']) ) {
                                $cpc = $data['cpc'];
                            }
                            
                            fputcsv($csv, array(
                                $data['date_start'],
                                $data['impressions'],
                                $data['reach'],
                                $data['unique_clicks'],
                                $data['cpm'],
                                $cpc,
                                $data['ctr'],
                                $data['account_currency'] . ' ' . $data['spend']
                            ));
                      
                            

                        }
                        
                        fclose($csv);
                        
                        exit();
                        
                    }
                    
                    break;
                
                case '2':
            
                    // Get campaign insights
                    $campaign_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $campaign_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-7 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);
                    
                    if ( @$campaign_insights['data'][0] ) {
                        
                        if ( !$this->CI->input->get('download', TRUE) ) {

                            $data = array(
                                'success' => TRUE,
                                'campaign_insights' => $campaign_insights['data']
                            );

                            echo json_encode($data);
                            exit();

                        }
                    
                        header("Content-type: application/csv");
                        header("Content-Disposition: attachment; filename=insights.csv");
                        $csv = fopen('php://output', 'w');
                        
                        fputcsv($csv, array(
                            $this->CI->lang->line('date'),
                            $this->CI->lang->line('impressions'),
                            $this->CI->lang->line('reach'),
                            $this->CI->lang->line('clicks'),
                            $this->CI->lang->line('cpm'),
                            $this->CI->lang->line('cpc'),
                            $this->CI->lang->line('ctr'),
                            $this->CI->lang->line('spent')
                        ));

                        foreach ( $campaign_insights['data'] as $data ) {
                            
                            $cpc = 0;
                            
                            if ( isset($data['cpc']) ) {
                                $cpc = $data['cpc'];
                            }
                            
                            fputcsv($csv, array(
                                $data['date_start'],
                                $data['impressions'],
                                $data['reach'],
                                $data['unique_clicks'],
                                $data['cpm'],
                                $cpc,
                                $data['ctr'],
                                $data['account_currency'] . ' ' . $data['spend']
                            ));
                      
                            

                        }
                        
                        fclose($csv);
                        
                        exit();
                        
                    }
                    
                    break;
                
                case '3':

                    // Get campaign insights
                    $campaign_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $campaign_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-30 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);
                    
                    if ( @$campaign_insights['data'][0] ) {
                        
                        if ( !$this->CI->input->get('download', TRUE) ) {

                            $data = array(
                                'success' => TRUE,
                                'campaign_insights' => $campaign_insights['data']
                            );

                            echo json_encode($data);
                            exit();

                        }
                        
                        header("Content-type: application/csv");
                        header("Content-Disposition: attachment; filename=insights.csv");
                        $csv = fopen('php://output', 'w');
                        
                        fputcsv($csv, array(
                            $this->CI->lang->line('date'),
                            $this->CI->lang->line('impressions'),
                            $this->CI->lang->line('reach'),
                            $this->CI->lang->line('clicks'),
                            $this->CI->lang->line('cpm'),
                            $this->CI->lang->line('cpc'),
                            $this->CI->lang->line('ctr'),
                            $this->CI->lang->line('spent')
                        ));

                        foreach ( $campaign_insights['data'] as $data ) {
                            
                            $cpc = 0;
                            
                            if ( isset($data['cpc']) ) {
                                $cpc = $data['cpc'];
                            }
                            
                            fputcsv($csv, array(
                                $data['date_start'],
                                $data['impressions'],
                                $data['reach'],
                                $data['unique_clicks'],
                                $data['cpm'],
                                $cpc,
                                $data['ctr'],
                                $data['account_currency'] . ' ' . $data['spend']
                            ));
                      
                            

                        }
                        
                        fclose($csv);
                        
                        exit();
                        
                    }
                    
                    break;
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('no_insights_found')
        );

        echo json_encode($data); 
        
    }
    
    /**
     * The function insights_download_for_ad_sets downloads Ad Sets insights
     * 
     * @return void
     */
    public function insights_download_for_ad_sets() {
        
        // Get order
        $order = $this->CI->input->get('order', TRUE);
        
        // Get Ad Set ID
        $ad_set_id = $this->CI->input->get('ad_set_id', TRUE);
        
        if ( !$ad_set_id ) {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('please_select_ad_set')
            );

            echo json_encode($data);
            exit();            
            
        }
        
        // Get selected account
        $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');        
        
        if ( is_numeric($order) && $account ) {

            switch($order) {
                
                case '1':
            
                    // Get ad set insights
                    $ad_set_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $ad_set_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-1 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);

                    if ( @$ad_set_insights['data'][0] ) {
                        
                        if ( !$this->CI->input->get('download', TRUE) ) {

                            $data = array(
                                'success' => TRUE,
                                'ad_sets_insights' => $ad_set_insights['data']
                            );

                            echo json_encode($data);
                            exit();

                        }
                        
                        header("Content-type: application/csv");
                        header("Content-Disposition: attachment; filename=insights.csv");
                        $csv = fopen('php://output', 'w');
                        
                        fputcsv($csv, array(
                            $this->CI->lang->line('date'),
                            $this->CI->lang->line('impressions'),
                            $this->CI->lang->line('reach'),
                            $this->CI->lang->line('clicks'),
                            $this->CI->lang->line('cpm'),
                            $this->CI->lang->line('cpc'),
                            $this->CI->lang->line('ctr'),
                            $this->CI->lang->line('spent')
                        ));

                        foreach ( $ad_set_insights['data'] as $data ) {
                            
                            $cpc = 0;
                            
                            if ( isset($data['cpc']) ) {
                                $cpc = $data['cpc'];
                            }
                            
                            fputcsv($csv, array(
                                $data['date_start'],
                                $data['impressions'],
                                $data['reach'],
                                $data['unique_clicks'],
                                $data['cpm'],
                                $cpc,
                                $data['ctr'],
                                $data['account_currency'] . ' ' . $data['spend']
                            ));
                      
                            

                        }
                        
                        fclose($csv);
                        
                        exit();
                        
                    }
                    
                    break;
                
                case '2':
            
                    // Get ad set insights
                    $ad_set_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $ad_set_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-7 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);
                    
                    if ( @$ad_set_insights['data'][0] ) {
                        
                        if ( !$this->CI->input->get('download', TRUE) ) {

                            $data = array(
                                'success' => TRUE,
                                'ad_sets_insights' => $ad_set_insights['data']
                            );

                            echo json_encode($data);
                            exit();

                        }
                    
                        header("Content-type: application/csv");
                        header("Content-Disposition: attachment; filename=insights.csv");
                        $csv = fopen('php://output', 'w');
                        
                        fputcsv($csv, array(
                            $this->CI->lang->line('date'),
                            $this->CI->lang->line('impressions'),
                            $this->CI->lang->line('reach'),
                            $this->CI->lang->line('clicks'),
                            $this->CI->lang->line('cpm'),
                            $this->CI->lang->line('cpc'),
                            $this->CI->lang->line('ctr'),
                            $this->CI->lang->line('spent')
                        ));

                        foreach ( $ad_set_insights['data'] as $data ) {
                            
                            $cpc = 0;
                            
                            if ( isset($data['cpc']) ) {
                                $cpc = $data['cpc'];
                            }
                            
                            fputcsv($csv, array(
                                $data['date_start'],
                                $data['impressions'],
                                $data['reach'],
                                $data['unique_clicks'],
                                $data['cpm'],
                                $cpc,
                                $data['ctr'],
                                $data['account_currency'] . ' ' . $data['spend']
                            ));
                      
                            

                        }
                        
                        fclose($csv);
                        
                        exit();
                        
                    }
                    
                    break;
                
                case '3':

                    // Get ad set insights
                    $ad_set_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $ad_set_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-30 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);
                    
                    if ( @$ad_set_insights['data'][0] ) {
                        
                        if ( !$this->CI->input->get('download', TRUE) ) {

                            $data = array(
                                'success' => TRUE,
                                'ad_sets_insights' => $ad_set_insights['data']
                            );

                            echo json_encode($data);
                            exit();

                        }
                        
                        header("Content-type: application/csv");
                        header("Content-Disposition: attachment; filename=insights.csv");
                        $csv = fopen('php://output', 'w');
                        
                        fputcsv($csv, array(
                            $this->CI->lang->line('date'),
                            $this->CI->lang->line('impressions'),
                            $this->CI->lang->line('reach'),
                            $this->CI->lang->line('clicks'),
                            $this->CI->lang->line('cpm'),
                            $this->CI->lang->line('cpc'),
                            $this->CI->lang->line('ctr'),
                            $this->CI->lang->line('spent')
                        ));

                        foreach ( $ad_set_insights['data'] as $data ) {
                            
                            $cpc = 0;
                            
                            if ( isset($data['cpc']) ) {
                                $cpc = $data['cpc'];
                            }
                            
                            fputcsv($csv, array(
                                $data['date_start'],
                                $data['impressions'],
                                $data['reach'],
                                $data['unique_clicks'],
                                $data['cpm'],
                                $cpc,
                                $data['ctr'],
                                $data['account_currency'] . ' ' . $data['spend']
                            ));
                      
                            

                        }
                        
                        fclose($csv);
                        
                        exit();
                        
                    }
                    
                    break;
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('no_insights_found')
        );

        echo json_encode($data); 
        
    }
    
    /**
     * The function insights_download_for_ad downloads Ad's insights
     * 
     * @return void
     */
    public function insights_download_for_ad() {
        
        // Get order
        $order = $this->CI->input->get('order', TRUE);
        
        // Get Ad ID
        $ad_id = $this->CI->input->get('ad_id', TRUE);
        
        if ( !$ad_id ) {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('please_select_ad')
            );

            echo json_encode($data);
            exit();            
            
        }
        
        // Get selected account
        $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');        
        
        if ( is_numeric($order) && $account ) {

            switch($order) {
                
                case '1':
            
                    // Get ad insights
                    $ad_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $ad_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-1 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);

                    if ( @$ad_insights['data'][0] ) {
                        
                        if ( !$this->CI->input->get('download', TRUE) ) {

                            $data = array(
                                'success' => TRUE,
                                'ad_insights' => $ad_insights['data']
                            );

                            echo json_encode($data);
                            exit();

                        }
                        
                        header("Content-type: application/csv");
                        header("Content-Disposition: attachment; filename=insights.csv");
                        $csv = fopen('php://output', 'w');
                        
                        fputcsv($csv, array(
                            $this->CI->lang->line('date'),
                            $this->CI->lang->line('impressions'),
                            $this->CI->lang->line('reach'),
                            $this->CI->lang->line('clicks'),
                            $this->CI->lang->line('cpm'),
                            $this->CI->lang->line('cpc'),
                            $this->CI->lang->line('ctr'),
                            $this->CI->lang->line('spent')
                        ));

                        foreach ( $ad_insights['data'] as $data ) {
                            
                            $cpc = 0;
                            
                            if ( isset($data['cpc']) ) {
                                $cpc = $data['cpc'];
                            }
                            
                            fputcsv($csv, array(
                                $data['date_start'],
                                $data['impressions'],
                                $data['reach'],
                                $data['unique_clicks'],
                                $data['cpm'],
                                $cpc,
                                $data['ctr'],
                                $data['account_currency'] . ' ' . $data['spend']
                            ));
                      
                            

                        }
                        
                        fclose($csv);
                        
                        exit();
                        
                    }
                    
                    break;
                
                case '2':
            
                    // Get ad insights
                    $ad_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $ad_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-7 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);
                    
                    if ( @$ad_insights['data'][0] ) {
                        
                        if ( !$this->CI->input->get('download', TRUE) ) {

                            $data = array(
                                'success' => TRUE,
                                'ad_insights' => $ad_insights['data']
                            );

                            echo json_encode($data);
                            exit();

                        }
                    
                        header("Content-type: application/csv");
                        header("Content-Disposition: attachment; filename=insights.csv");
                        $csv = fopen('php://output', 'w');
                        
                        fputcsv($csv, array(
                            $this->CI->lang->line('date'),
                            $this->CI->lang->line('impressions'),
                            $this->CI->lang->line('reach'),
                            $this->CI->lang->line('clicks'),
                            $this->CI->lang->line('cpm'),
                            $this->CI->lang->line('cpc'),
                            $this->CI->lang->line('ctr'),
                            $this->CI->lang->line('spent')
                        ));

                        foreach ( $ad_insights['data'] as $data ) {
                            
                            $cpc = 0;
                            
                            if ( isset($data['cpc']) ) {
                                $cpc = $data['cpc'];
                            }
                            
                            fputcsv($csv, array(
                                $data['date_start'],
                                $data['impressions'],
                                $data['reach'],
                                $data['unique_clicks'],
                                $data['cpm'],
                                $cpc,
                                $data['ctr'],
                                $data['account_currency'] . ' ' . $data['spend']
                            ));
                      
                            

                        }
                        
                        fclose($csv);
                        
                        exit();
                        
                    }
                    
                    break;
                
                case '3':

                    // Get ad insights
                    $ad_insights = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $ad_id . '/insights?fields=account_currency,spend,impressions,reach,clicks,unique_clicks,cpm,cpc,ctr&time_range={"since":"' . date('Y-m-d', strtotime('-30 days')) . '","until":"' . date('Y-m-d') . '"}&time_increment=1&access_token=' . $account[0]->token), true);
                    
                    if ( @$ad_insights['data'][0] ) {
                        
                        if ( !$this->CI->input->get('download', TRUE) ) {

                            $data = array(
                                'success' => TRUE,
                                'ad_insights' => $ad_insights['data']
                            );

                            echo json_encode($data);
                            exit();

                        }
                        
                        header("Content-type: application/csv");
                        header("Content-Disposition: attachment; filename=insights.csv");
                        $csv = fopen('php://output', 'w');
                        
                        fputcsv($csv, array(
                            $this->CI->lang->line('date'),
                            $this->CI->lang->line('impressions'),
                            $this->CI->lang->line('reach'),
                            $this->CI->lang->line('clicks'),
                            $this->CI->lang->line('cpm'),
                            $this->CI->lang->line('cpc'),
                            $this->CI->lang->line('ctr'),
                            $this->CI->lang->line('spent')
                        ));

                        foreach ( $ad_insights['data'] as $data ) {
                            
                            $cpc = 0;
                            
                            if ( isset($data['cpc']) ) {
                                $cpc = $data['cpc'];
                            }
                            
                            fputcsv($csv, array(
                                $data['date_start'],
                                $data['impressions'],
                                $data['reach'],
                                $data['unique_clicks'],
                                $data['cpm'],
                                $cpc,
                                $data['ctr'],
                                $data['account_currency'] . ' ' . $data['spend']
                            ));
                      
                            

                        }
                        
                        fclose($csv);
                        
                        exit();
                        
                    }
                    
                    break;
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('no_insights_found')
        );

        echo json_encode($data); 
        
    }
    
}

/* End of file ad_insights.php */
