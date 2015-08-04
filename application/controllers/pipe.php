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


class Pipe extends MX_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{

		$data['json'] = $this->applib->get_emails();
                $this->load->view('json',isset($data) ? $data : NULL);

	}
}
/* End of file sys_language.php */
/* Location: ./application/controllers/pipe.php */