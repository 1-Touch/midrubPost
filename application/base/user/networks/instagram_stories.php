<?php
/**
 * Instagram Stories
 *
 * PHP Version 7.2
 *
 * Connect and Publish Instagram Stories
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace MidrubBase\User\Networks;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');
    
// Limits the maximum execution time to unlimited
set_time_limit(0);

// Sets the default timezone used by all date/time functions
date_default_timezone_set('UTC');

// Define the namespaces to use
use MidrubBase\User\Interfaces as MidrubBaseUserInterfaces;

/**
 * Instagram_stories class - allows users to connect to their Instagram Account and publish stories
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Instagram_stories implements MidrubBaseUserInterfaces\Networks {
    
    /**
     * Class variables
     */
    protected $CI, $instagram;
    
    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
        
        // Require the vendor autoload
        include_once FCPATH . 'vendor/autoload.php';
        
    }
    
    /**
     * The public method check_availability doesn't check if Instagram api was configured correctly.
     *
     * @return will be true
     */
    public function check_availability() {
        
        return true;
        
    }
    
    /**
     * The public method connect will show a form where the user can add the Instagram's username and password.
     *
     * @return void
     */
    public function connect() {
        
        if ( $this->CI->input->post() ) {
            
            $this->CI->form_validation->set_rules('username', 'Username', 'trim|required');
            $this->CI->form_validation->set_rules('password', 'Password', 'trim|required');
            $this->CI->form_validation->set_rules('proxy', 'Proxy', 'trim');
            
            // Get data
            $username = $this->CI->input->post('username');
            $password = $this->CI->input->post('password');
            $proxy = $this->CI->input->post('proxy');
            
            if ( $this->CI->form_validation->run() == false ) {
                
                // Display the error message
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false);
                
            } else {

                $check = new \InstagramAPI\Instagram(false, false);

                if ($proxy) {

                    $check->setProxy($proxy);

                } else {

                    $user_proxy = $this->CI->user->get_user_option($this->CI->user_id, 'proxy');

                    if ($user_proxy) {

                        $check->setProxy($user_proxy);

                    } else {

                        $proxies = @trim(get_option('instagram_proxy'));

                        if ($proxies) {

                            $proxies = explode('<br>', nl2br($proxies, false));

                            $rand = rand(0, count($proxies));

                            if (@$proxies[$rand]) {

                                $check->setProxy($proxies[$rand]);

                            }

                        }

                    }

                }

                try {

                    $check->login($username, $password);

                    // Verify if account was already saved
                    if (!$this->CI->networks->get_network_data('instagram_stories', $this->CI->user_id, $username)) {

                        $this->CI->networks->add_network('instagram_stories', $username, $password, $this->CI->user_id, '', $username, '', $proxy);

                        // Display the success message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_your_account_was_connected') . '</p>', true);
                    
                    } else {

                        // Display the error message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_your_account_was_not_connected') . '</p>', false);
                    
                    }

                } catch (Exception $e) {

                    $check = $e->getMessage();

                    if (preg_match('/required/i', $check)) {

                        // Display the error message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_please_go_to_instagram') . '</p>', false);
                    
                    } else if (preg_match('/password/i', $check)) {

                        // Display the error message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_username_or_password_incorrect') . '</p>', false);
                    
                    } else {

                        // Display the error message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $check . '</p>', false);
                    
                    }
                    
                }
                
            }
            
        } else {
            
            // Display the login form
            echo get_instance()->ecl('Social_login')->content('Username', 'Password', 'Connect', $this->get_info(), 'instagram_stories', '');
            
        }
        
    }
    
    /**
     * The public method save was added only to follow the interface.
     *
     * @param $token contains the token for some social networks
     *
     * @return void
     */
    public function save($token = null) {
        
    }
    
    /**
     * The public method post publishes posts on Instagram.
     *
     * @param array $args contains the post data.
     * @param integer $user_id is the ID of the current user
     *
     * @return boolean true if post was published
     */
    public function post($args, $user_id = null) {
        
        // Verify if user_id exists
        if ( $user_id ) {
            
            // Get account details
            $user_details = $this->CI->networks->get_network_data('instagram_stories', $user_id, $args['account']);
            
        } else {
            
            // Set user's ID
            $user_id = $this->CI->user_id;

            // Get account details
            $user_details = $this->CI->networks->get_network_data('instagram_stories', $user_id, $args['account']);
            
        }
        
        // Verify if image exists
        if ( !$args['img'] ) {
            
            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($this->CI->lang->line('networks_a_photo_is_required_to_publish_here')));
            
            // Then return false
            return false;
            
        }
        
        // Verify if the image is loaded on server
        $im = explode(base_url(), $args['img'][0]['body']);
        
        // If image is on server
        if ( @$im[1] ) {
            
            // Get the path
            $filename = str_replace(base_url(), FCPATH, $args['img'][0]['body']);
            
            // Verify format
            if ( exif_imagetype($filename) != IMAGETYPE_JPEG ) {
                
                $in = get($args['img'][0]['body']);
                
                if ($in) {
                    
                    $filename = FCPATH . 'assets/share/' . uniqid() . time() . '.jpg';
                    
                    file_put_contents($filename, $in);
                    
                    if ( file_exists($filename) ) {
                        
                        $file = $filename;
                        
                    } else {
                        
                        return false;
                        
                    }
                    
                } else {
                    
                    return false;
                    
                }
                
            }
            
            $file = $filename;
            
        } else {
            
            $in = get($args['img'][0]['body']);
            
            if ( $in ) {
                
                $filename = FCPATH . 'assets/share/' . uniqid() . time() . '.jpg';
                
                // Save image on server
                file_put_contents($filename, $in);
                
                // Verify if image was saved
                if ( file_exists($filename) ) {
                    
                    $file = $filename;
                    
                } else {
                    
                    return false;
                    
                }
                
            } else {
                
                return false;
                
            }
            
        }
        
        // Get the post content
        $post = $args['post'];
        
        // If title is not empty
        if ( $args['title'] ) {
            
            $post = $args['title'] . ' ' . $post;
            
        }
        
        // Verify if url exists
        if ( $args['url'] ) {
            $post = str_replace($args['url'], ' ', $post) . ' ' . short_url($args['url']);
        }
        
        // Set the photo
        $photo = $file;
        
        // Call the Instagram class
        $check = new \InstagramAPI\Instagram(false, false);
        
        // Verify if for this account was added a proxy
        if ( trim($user_details[0]->secret) ) {
            
            $check->setProxy($user_details[0]->secret);
            
        } else {
            
            // Get proxy if exists
            $user_proxy = $this->CI->user->get_user_option($user_id, 'proxy');
            
            // Veirify if proxy exists
            if ( $user_proxy ) {
                
                $check->setProxy($user_proxy);
                
            } else {
                
                // Get global proxy
                $proxies = @trim(get_option('instagram_proxy'));
                
                // Verify if proxy exists
                if ($proxies) {
                    
                    $proxies = explode('<br>', nl2br($proxies, false));
                    
                    $rand = rand(0, count($proxies));
                    
                    if ( @$proxies[$rand] ) {
                        
                        $check->setProxy($proxies[$rand]);
                        
                    }
                    
                }
                
            }
            
        }
        
        // Login
        $check->login($user_details[0]->net_id, $user_details[0]->token);
        
        try {
            $location = $check->location->search('34.037238', '-118.265678')->getVenues()[0];
        } catch (\Exception $e) {
            echo 'Something went wrong: ' . $e->getMessage() . "\n";
        }
        
        $caption = $post;
        
        $ver = explode('#', $caption);
        
        $hashtag = 'social';
        
        if ( isset($ver[1]) ) {
            
            $all = explode(' ', $caption);
            
            foreach ($all as $a) {
                
                if (preg_match('/#/i', $a)) {
                    
                    
                    
                    $hashtag = str_replace('#', '', $a);
                }
            }
        } else {
            
            $caption = $caption . ' #' . $hashtag;
            
        }
        
        // Now create the metadata array:
        $metadata = [
        // (optional) Captions can always be used, like this:
        'caption'  => $caption,
        // (optional) To add a hashtag, do this:
        'hashtags' => [
        // Note that you can add more than one hashtag in this array.
        [
        'tag_name'         => $hashtag, // Hashtag WITHOUT the '#'! NOTE: This hashtag MUST appear in the caption.
        'x'                => 0.5, // Range: 0.0 - 1.0. Note that x = 0.5 and y = 0.5 is center of screen.
        'y'                => 0.5, // Also note that X/Y is setting the position of the CENTER of the clickable area.
        'width'            => 0.24305555, // Clickable area size, as percentage of image size: 0.0 - 1.0
        'height'           => 0.07347973, // ...
        'rotation'         => 0.0,
        'is_sticker'       => false, // Don't change this value.
        'use_custom_title' => false, // Don't change this value.
        ],
        // ...
        ],
        // (optional) To add a location, do BOTH of these:
        'location_sticker' => [
        'width'         => 0.89333333333333331,
        'height'        => 0.071281859070464776,
        'x'             => 0.5,
        'y'             => 0.2,
        'rotation'      => 0.0,
        'is_sticker'    => true,
        'location_id'   => $location->getExternalId(),
        ],
        'location' => $location,
        // (optional) You can use story links ONLY if you have a business account with >= 10k followers.
        // 'link' => 'https://github.com/mgp25/Instagram-API',
        ];
        
        $resizer = new \InstagramAPI\Media\Photo\InstagramPhoto($photo, ['targetFeed' => \InstagramAPI\Constants::FEED_STORY]);;
        
        // Upload the photo
        try {
            
            $myphoto = $check->story->uploadPhoto($resizer->getFile(), $metadata);
            
            if ( $myphoto ) {
                
                $moph = json_encode((array) $myphoto);
                
                $str = explode('media_id":"', $moph);
                
                if ( @$str[1] ) {
                    
                    $rd = explode('"', $str[1]);
                    
                    sami($rd[0], $args['id'], $args['account'], 'instagram_stories', $user_id);
                    
                    
                }
                
                return true;
                
            } else {
                
                return false;
                
            }
            
        } catch (Exception $e) {
            
            try {
                
                $myphoto = $check->story->uploadPhoto($resizer->getFile(), $metadata);
                
                if ($myphoto) {
                    
                    $moph = json_encode((array) $myphoto);
                    
                    $str = explode('media_id":"', $moph);
                    
                    if (@$str[1]) {
                        
                        $rd = explode('"', $str[1]);
                        
                        sami($rd[0], $args['id'], $args['account'], 'instagram_stories', $user_id);
                        
                    }
                    
                    return true;
                    
                } else {
                    
                    return false;
                    
                }
                
            } catch (Exception $e) {
                
                try {
                    
                    sleep(1);
                    
                    $myphoto = $check->story->uploadPhoto($resizer->getFile(), $metadata);
                    
                    if ( $myphoto ) {
                        
                        $moph = json_encode((array) $myphoto);
                        
                        $str = explode('media_id":"', $moph);
                        
                        if ( @$str[1] ) {
                            
                            $rd = explode('"', $str[1]);
                            
                            sami($rd[0], $args['id'], $args['account'], 'instagram_stories', $user_id);
                            
                        }
                        
                        return true;
                        
                    } else {
                        
                        return false;
                        
                    }
                    
                } catch (Exception $e) {
                    
                    try {
                        
                        sleep(1);
                        
                        $myphoto = $check->story->uploadPhoto($resizer->getFile(), $metadata);
                        
                        if ( $myphoto ) {
                            
                            $moph = json_encode((array) $myphoto);
                            
                            $str = explode('media_id":"', $moph);
                            
                            if ( @$str[1] ) {
                                
                                $rd = explode('"', $str[1]);
                                
                                sami($rd[0], $args['id'], $args['account'], 'instagram_stories', $user_id);
                                
                            }
                            
                            return true;
                            
                        } else {
                            
                            return false;
                            
                        }
                        
                    } catch (Exception $e) {
                        
                        // Save the error
                        $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', $e->getMessage());
                        
                        // Then return false
                        return false;
                        
                    }
                    
                }
                
            }
            
        }
        
    }
    
    /**
     * The public method get_info displays information about this class.
     *
     * @return array with network's data
     */
    public function get_info() {

        $value = '';

        if ( get_option('instagram_stories_proxy') ) {
            $value = get_option('instagram_stories_proxy');
        }

        return array(
            'color' => '#c9349a',
            'icon' => '<i class="icon-social-instagram"></i>',
            'api' => array('proxy'),
            'types' => array('post', 'rss'),
            'extra_content' => '<div class="form-group">'
                . '<div class="row">'
                    . '<div class="col-lg-10 col-xs-6">'
                        . '<label for="menu-item-text-input">'
                            . 'Proxy'
                        . '</label>'
                    . '</div>'
                    . '<div class="col-lg-2 col-xs-6">'
                        . '<div class="checkbox-option pull-right">'
                            . '<textarea class="optionvalue form-control social-text-input" id="instagram_stories_proxy" placeholder="Enter one IP per line(example: http://00.00.00.00:(port) or with ssl)">'
                                . $value
                            . '</textarea>'
                        . '</div>'
                    . '</div>'
                . '</div>'
            . '</div>'

        );
        
    }
    
    /**
     * The public method preview generates a preview for Instagram.
     *
     * @param $args contains the img or url.
     *
     * @return array with html
     */
    public function preview($args) {
    }
    
}

/* End of file Instagram_stories.php */
