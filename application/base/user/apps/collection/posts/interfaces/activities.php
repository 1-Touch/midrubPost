<?php
/**
 * Activities
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
 * Activities interface - allows to create App's ativities templates
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
interface Activities {

    /**
     * The public method template returns the Ativities template
     * 
     * @since 0.0.7.0
     * 
     * @param integer $user_id contains the user's ID
     * @param integer $member_id contains the member's ID
     * @param integer $id contains the identificator for the requested template
     * 
     * @return array with template's data
     */ 
    public function template( $user_id, $member_id, $id );
    
    /**
     * The public method adapter adapts database content for template
     * 
     * @since 0.0.7.0
     * 
     * @param integer $user_id contains the user's ID
     * @param integer $member_id contains the member's ID
     * @param integer $id contains the identificator for the requested template
     * 
     * @return array with db's data
     */ 
    public function adapter( $user_id, $member_id, $id );

}