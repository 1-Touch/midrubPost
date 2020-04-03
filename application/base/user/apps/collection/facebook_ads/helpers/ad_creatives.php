<?php
/**
 * Facebook Ad Creatives Helper
 *
 * This file contains the class Ad_creatives
 * with methods to manage Ad's creatives
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Facebook_ads\Helpers;

// Constants
defined('BASEPATH') or exit('No direct script access allowed');

// Namespaces to use
use FacebookAds\Object\AdAccountActivity;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdAccountFields;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\AdCreative;
use FacebookAds\Object\AdCreativeLinkData;
use FacebookAds\Object\Fields\AdCreativeLinkDataFields;
use FacebookAds\Object\AdCreativeObjectStorySpec;
use FacebookAds\Object\Fields\AdCreativeObjectStorySpecFields;
use FacebookAds\Object\Fields\AdCreativeFields;
use FacebookAds\Object\Ad;
use FacebookAds\Object\Fields\AdFields;
use FacebookAds\Object\Values\AdCreativeCallToActionTypeValues;
use FacebookAds\Object\Fields\AdCreativeLinkDataCallToActionValueFields;
use FacebookAds\Object\Fields\PageCallToActionFields;
use FacebookAds\Object\Values\PageCallToActionAndroidDestinationTypeValues;
use FacebookAds\Object\Values\PageCallToActionIphoneDestinationTypeValues;
use FacebookAds\Object\Values\PageCallToActionTypeValues;
use FacebookAds\Object\Values\PageCallToActionWebDestinationTypeValues;
use FacebookAds\Object\AdCreativeVideoData;
use FacebookAds\Object\Fields\AdCreativeVideoDataFields;

/*
 * Ad_creatives class provides the methods to manage Ad's creatives
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
*/

class Ad_creatives
{

    /**
     * Class variables
     *
     * @since 0.0.7.6
     */
    protected $CI, $fb, $app_id, $app_secret;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.6
     */
    public function __construct()
    {

        // Get the CodeIgniter super object
        $this->CI = &get_instance();

        // Load models
        $this->CI->load->ext_model(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'models/', 'Ads_networks_model', 'ads_networks_model');

        // Get the Facebook App ID
        $this->app_id = get_option('facebook_pages_app_id');

        // Get the Facebook App Secret
        $this->app_secret = get_option('facebook_pages_app_secret');

        // Load the Vendor dependencies
        require_once FCPATH . 'vendor/autoload.php';

        // Verify if Facebook Pages was configured
        if ( ($this->app_id != '') and ($this->app_secret != '') ) {

            $this->fb = new \Facebook\Facebook([
                'app_id' => $this->app_id,
                'app_secret' => $this->app_secret,
                'default_graph_version' => MIDRUB_ADS_FACEBOOK_GRAPH_VERSION,
                'default_access_token' => '{access-token}',
            ]);

        }

    }

    /**
     * The public method create_creative creates an ad's creative
     * 
     * @param array $args contains the arguments to create an ad's campaign
     * @param string $placements contains the ad's placements
     * 
     * @since 0.0.7.6
     * 
     * @return array with response
     */
    public function create_creative($args, $placements) {

        if ($args) {

            $destinations = array();

            if ($placements) {

                foreach ($placements as $key => $value) {

                    if ( ($value[1] === 'ad-set-placement-facebook-feeds') || ($value[1] === 'ad-set-placement-facebook-feeds2') ) {

                        $destinations[] = 'facebook';

                    } elseif ( ($value[1] === 'ad-set-placement-instagram-feed') || ($value[1] === 'ad-set-placement-instagram-feed2') ) {

                        $destinations[] = 'instagram';

                    } elseif ( ($value[1] === 'ad-set-placement-messenger-inbox') || ($value[1] === 'ad-set-placement-messenger-inbox2') ) {

                        $destinations[] = 'messenger';

                    }

                }

            }       
            
            if ( !isset($args['instagram_id']) && in_array('instagram', $destinations) ) {

                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('ad_not_created'),
                    'description' => $this->CI->lang->line('instagram_account_required_to_be_as_identity')
                );

            }

            if ( !isset($args['fb_page_id']) ) {

                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('ad_not_created'),
                    'description' => $this->CI->lang->line('facebook_page_required_to_be_as_identity')
                );

            }

            switch ($args['objective']) {

                case 'LINK_CLICKS':

                    if ( !$destinations ) { 

                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('ad_not_created'),
                            'description' => $this->CI->lang->line('your_ad_set_should_have_at_least_one_placement')
                        );

                    }

                    if ( !$args['preview_image'] && in_array('instagram', $destinations) ) {

                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('ad_not_created'),
                            'description' => $this->CI->lang->line('an_image_is_required_to_create_ad')
                        );

                    }

                    if ( !$args['adimage'] && in_array('instagram', $destinations) ) {

                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('ad_not_created'),
                            'description' => $this->CI->lang->line('an_image_is_required_to_create_ad')
                        );

                    }

                    // Verify if ad's text exists
                    if ( !@$args['ad_text'] ) {

                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('ad_not_created'),
                            'description' => $this->CI->lang->line('ad_text_is_required')
                        );

                    }

                    // Verify if ad's url exists
                    if ( !@$args['website_url'] ) {

                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('ad_not_created'),
                            'description' => $this->CI->lang->line('ad_url_is_required')
                        );

                    }

                    if (in_array('instagram', $destinations)) {

                        // Link Fields
                        $link_fields = array(
                            AdCreativeLinkDataFields::MESSAGE => $args['ad_text'],
                            AdCreativeLinkDataFields::LINK => $args['website_url'],
                            AdCreativeLinkDataFields::IMAGE_HASH => $args['adimage']
                        );

                        // Verify if headline exists
                        if ( !empty($args['headline']) ) {
                            $link_fields['name'] = $args['headline'];
                        }

                        // Verify if description exists
                        if ( !empty($args['description']) ) {
                            $link_fields['description'] = $args['description'];
                        } 

                        $link_data = new AdCreativeLinkData();
                        $link_data->setData($link_fields);

                        // Set ad's call to action
                        $call_to_action = array(
                            'type' => 'LEARN_MORE',
                            'value' => array(
                                'link' => $args['website_url']
                            )
                        );                       

                        $link_data->setData(array(
                            AdCreativeLinkDataFields::MESSAGE => $args['ad_text'],
                            AdCreativeLinkDataFields::LINK => $args['website_url'],
                            AdCreativeLinkDataFields::IMAGE_HASH => $args['adimage'],
                            AdCreativeLinkDataFields::CAPTION => $args['website_url'],
                            AdCreativeLinkDataFields::CALL_TO_ACTION => $call_to_action
                        ));

                        $specs = array(
                            AdCreativeObjectStorySpecFields::PAGE_ID => $args['fb_page_id'],
                            AdCreativeObjectStorySpecFields::INSTAGRAM_ACTOR_ID => $args['instagram_id'],
                            AdCreativeObjectStorySpecFields::LINK_DATA => $link_data
                        );

                        $object_story_spec = new AdCreativeObjectStorySpec();
                        $object_story_spec->setData($specs);

                    } else {

                        if ($args['adimage']) {

                            // Link Fields
                            $link_fields = array(
                                AdCreativeLinkDataFields::MESSAGE => $args['ad_text'],
                                AdCreativeLinkDataFields::LINK => $args['website_url'],
                                AdCreativeLinkDataFields::IMAGE_HASH => $args['adimage']
                            );

                            // Verify if headline exists
                            if ( !empty($args['headline']) ) {
                                $link_fields['name'] = $args['headline'];
                            }

                            // Verify if description exists
                            if ( !empty($args['description']) ) {
                                $link_fields['description'] = $args['description'];
                            }                            

                            $link_data = new AdCreativeLinkData();
                            $link_data->setData($link_fields);

                            $specs = array(
                                AdCreativeObjectStorySpecFields::PAGE_ID => $args['fb_page_id'],
                                AdCreativeObjectStorySpecFields::LINK_DATA => $link_data
                            );
                            $object_story_spec = new AdCreativeObjectStorySpec();
                            $object_story_spec->setData($specs);

                        } else {

                            // Link Fields
                            $link_fields = array(
                                AdCreativeLinkDataFields::MESSAGE => $args['ad_text'],
                                AdCreativeLinkDataFields::LINK => $args['website_url']
                            );

                            // Verify if headline exists
                            if ( !empty($args['headline']) ) {
                                $link_fields['name'] = $args['headline'];
                            }

                            // Verify if description exists
                            if ( !empty($args['description']) ) {
                                $link_fields['description'] = $args['description'];
                            }

                            $link_data = new AdCreativeLinkData();
                            $link_data->setData($link_fields);

                            $specs = array(
                                AdCreativeObjectStorySpecFields::PAGE_ID => $args['fb_page_id'],
                                AdCreativeObjectStorySpecFields::LINK_DATA => $link_data
                            );
                            $object_story_spec = new AdCreativeObjectStorySpec();
                            $object_story_spec->setData($specs);

                        }

                    }

                    $creativeFields = array(
                        AdCreativeFields::NAME => $args['ad_name'],
                        AdCreativeFields::OBJECT_STORY_SPEC => $object_story_spec
                    );

                    if (isset($args['instagram_id'])) {

                        $creativeFields['platform_customizations'] = array(

                            'instagram' => array(
                                'image_url' => $args['preview_image'],
                                'image_crops' => array(
                                    '100x100' => array(array(200, 90), array(900, 790))
                                ),
                            )

                        );

                    }

                    $creative = (new AdAccount($args['account_id']))->createAdCreative(
                        array(),
                        $creativeFields
                    );

                    if (@$creative->id) {

                        $pixel = array();

                        if ($args['pixel_conversion_id']) {

                            $response = $this->fb->get(
                                '/' . $args['net_id'] . '/customconversions?fields=id,name,data_sources,aggregation_rule,custom_event_type,rule,pixel{name}&limit=1000',
                                $args['token']
                            );

                            $conversions = $response->getDecodedBody();

                            if ($conversions['data']) {

                                foreach ($conversions['data'] as $conversion) {

                                    if ($args['pixel_conversion_id'] === $conversion['id']) {

                                        $pixel = array(array(
                                            'action.type' => $conversion['custom_event_type'],
                                            'fb_pixel' => $args['pixel_id']
                                        ));
                                    }
                                }
                            }
                        }

                        $params = array(
                            AdFields::CREATIVE => array(
                                'creative_id' => $creative->id
                            ),
                            AdFields::NAME => $args['ad_name'],
                            AdFields::ADSET_ID => $args['adset_id'],
                            'status' => 'ACTIVE',
                            $pixel
                        );

                        $ad = (new AdAccount($args['account_id']))->createAd(
                            array(),
                            $params
                        );

                        if (@$ad->id) {

                            return array(
                                'success' => TRUE,
                                'message' => $this->CI->lang->line('ad_created'),
                                'description' => $this->CI->lang->line('ad_id') . ': ' . $ad->id,
                                'id' => $ad->id
                            );
                        } else {

                            return array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('ad_not_created'),
                                'description' => $this->CI->lang->line('error_occurred')
                            );
                        }
                    } else {

                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('ad_not_created'),
                            'description' => $this->CI->lang->line('creative_not_created_successfully')
                        );
                    }

                    break;

                case 'POST_ENGAGEMENT':

                    if (!isset($args['post_id'])) { 

                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('ad_not_created'),
                            'description' => $this->CI->lang->line('no_post_speficied_for_boosting')
                        );
                        
                    }

                    $creativeFields = array(
                        AdCreativeFields::NAME => $args['ad_name'],
                        AdCreativeFields::OBJECT_STORY_ID => $args['post_id'],
                        AdCreativeFields::INSTAGRAM_ACTOR_ID => $args['instagram_id']
                    );

                    $creative = (new AdAccount($args['account_id']))->createAdCreative(
                        array(),
                        $creativeFields
                    );

                    if (@$creative->id) {

                        $pixel = array();

                        if ($args['pixel_conversion_id']) {

                            $response = $this->fb->get(
                                '/' . $args['net_id'] . '/customconversions?fields=id,name,data_sources,aggregation_rule,custom_event_type,rule,pixel{name}&limit=1000',
                                $args['token']
                            );

                            $conversions = $response->getDecodedBody();

                            if ($conversions['data']) {

                                foreach ($conversions['data'] as $conversion) {

                                    if ($args['pixel_conversion_id'] === $conversion['id']) {

                                        $pixel = array(array(
                                            'action.type' => $conversion['custom_event_type'],
                                            'fb_pixel' => $args['pixel_id']
                                        ));
                                    }
                                }
                            }
                        }

                        $params = array(
                            AdFields::TRACKING_SPECS => array(
                                'action.type' => 'post_engagement',
                                'post' => str_replace($args['fb_page_id'] . '_', '', $args['post_id']),
                                'page' => $args['fb_page_id']
                            ),
                            'creative' => array(
                                'creative_id' => $creative->id
                            ),
                            AdFields::NAME => $args['ad_name'],
                            AdFields::ADSET_ID => $args['adset_id'],
                            'status' => 'ACTIVE',
                            $pixel
                        );

                        $ad = (new AdAccount($args['account_id']))->createAd(
                            array(),
                            $params
                        );

                        if (@$ad->id) {

                            return array(
                                'success' => TRUE,
                                'message' => $this->CI->lang->line('ad_created'),
                                'description' => $this->CI->lang->line('ad_id') . ': ' . $ad->id,
                                'id' => $ad->id
                            );

                        } else {

                            return array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('ad_not_created'),
                                'description' => $this->CI->lang->line('error_occurred')
                            );
                        }

                    } else {

                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('ad_not_created'),
                            'description' => $this->CI->lang->line('error_occurred')
                        );
                    }

                    break;
            }

        } else {

            return array(
                'success' => FALSE,
                'message' => 'Invalid parametrs.'
            );
        }
    }
}

/* End of file ad_creatives.php */
