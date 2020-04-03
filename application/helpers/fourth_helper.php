<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Name: Fourth Helper
 * Author: Scrisoft
 * Created: 15/07/2017
 * Here you will find the following functions:
 * user_name_by - get username by id
 * delete_media - deletes media by url
 * */
if (!function_exists('user_name_by')) {
    function user_name_by($user_id) {
        $CI = get_instance();
        // Load Posts Model
        $CI->load->model('user');
        $get_username = $CI->user->get_username_by_id($user_id);
        if($get_username) {
            return $get_username;
        }
    }
}
if (!function_exists('delete_media')) {
    function delete_media($url) {
        $filename = str_replace(base_url(), FCPATH, $url);
        @unlink($filename);
    }
}