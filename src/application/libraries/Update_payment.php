<?php
if (! defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Message Library
 *
 * @package Message
 * @subpackage Library
 */
class Update_payment
{
    
    public function __construct()
	{
		$this->_ci = & get_instance();
		$this->_ci->load->helper('message');
		$this->_ci->load->library('session');
	}
        
	public function a($a)
	{
            return $a;
	}
}
