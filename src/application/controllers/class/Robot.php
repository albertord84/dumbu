<?php
require_once 'Client.php';
require_once 'Reference_profile.php';


/**
 * class Robot
 * 
 */
class Robot
{

  /** Aggregations: */

  /** Compositions: */

   /*** Attributes: ***/

  /**
   * 
   * @access protected
   */
  protected $id;

  /**
   * 
   * @access protected
   */
  protected $IP;

  /**
   * 
   * @access protected
   */
  protected $dir;


  /**
   * 
   *
   * @param Client Client 

   * @return bool
   * @access public
   */
  public function login_client( $Client) {
  } // end of member function login_client

  /**
   * 
   *
   * @param Client Client 

   * @param Reference_profile Ref_profile 

   * @return void
   * @access public
   */
  public function do_follow_unfollow_work( $Client,  $Ref_profile) {
  } // end of member function do_follow_unfollow_work





} // end of Robot
?>
