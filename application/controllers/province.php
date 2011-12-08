<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Province extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function get_ajax_kota(){
		$this->load->model('kota_model');
		
		$kota = $this->kota_model->get_kota_by_province($this->input->post('id_province'));
		$options = '<option value="" >-- Pilih Kota --</option>';
			
		
		
			foreach($kota->result() as $row){
				$options .= '<option value="'.$row->NAMA_KOTA.'" >'.$row->NAMA_KOTA.'</option>';
			}
		
		echo $options;
	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */