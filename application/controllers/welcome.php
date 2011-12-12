<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$data['content'] = $this->load->view('information_page', '', true);
		$this->load->view('front', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */