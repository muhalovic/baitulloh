<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Information_page extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->under_construction();
	}
	
	function info()
	{
		$data['content'] = 'content';
		//$data['content'] = $this->load->view('information_page', '', true);
		$this->load->view('dashboard', $data);
	}//end info
	
	function under_construction()
	{
		$this->load->view('under_construction_dashboard');
	}// end under_construction
}

/* End of file information_page.php */
/* Location: ./application/controllers/information_page.php */