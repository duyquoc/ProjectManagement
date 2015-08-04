<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Users
 *
 *
 * @package		Freelancer Office
 * @subpackage	Users Module
 * @category	Controller
 * @author		William Mandai
*/



class Set_language extends MX_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->session->set_userdata('lang', $this->input->get('lang'));

		redirect($_SERVER["HTTP_REFERER"]);
	}
}
/* End of file sys_language.php */
/* Location: ./application/controllers/sys_language.php */