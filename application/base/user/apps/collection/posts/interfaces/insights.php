<?php
/**
 * Insights
 *
 * PHP Version 5.6
 *
 * Insights Interface for Posts Insights
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Posts\Interfaces;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Insights interface - allows to create insights classes for the Posts app
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
interface Insights {
    
    /**
     * Contains the Class's configurations
     *
     * @since 0.0.7.0
     * 
     * return array with class's configuration
     */
    public function configuration();
    
    /**
     * The public method get_account gets all accounts posts
     * 
     * @since 0.0.7.0
     * 
     * @param object $network contains the network details
     * 
     * @return array with posts or string with non found message
     */
    public function get_account($network);
    
    /**
     * The public method get_reactions gets the post's reactions
     * 
     * @since 0.0.7.0
     * 
     * @param object $network contains the network details
     * 
     * @return array with reactions or empty array
     */
    public function get_reactions($network);
    
    /**
     * The public method get_comments gets the post's comments
     * 
     * @since 0.0.7.0
     * 
     * @param object $network contains the network details
     * 
     * @return array with comments or string
     */
    public function get_comments($network);
    
    /**
     * The public method get_likes gets the post's likes
     * 
     * @since 0.0.7.0
     * 
     * @param object $network contains the network details
     * 
     * @return array with likes or string
     */
    public function get_likes($network);
    
    /**
     * The public method get_insights gets the post's insights
     * 
     * @since 0.0.7.0
     * 
     * @param object $network contains the network details
     * @param object $type contains the insights type
     * 
     * @return array with insights or string
     */
    public function get_insights($network, $type);
    
    /**
     * The public method post send submit data to social network
     * 
     * @since 0.0.7.0
     * 
     * @param object $network contains the network details
     * @param string type contains the data type
     * @param string $msg contains the data to send
     * @param string $parent contains the parent
     * 
     * @return array with status or string
     */
    public function post($network, $type, $msg, $parent = NULL);
    
}
