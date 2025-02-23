<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->cek_session();
	}

	function index()
	{
		$this->form();
	}
	
	function email_is_exist($param){
		return !$this->accounts_model->is_email_enable($param);
	}
	
	function email_is_active($param){
		return $this->accounts_model->is_email_active($param);
	}
	
	
	function cek_login()
	{
		
		$this->load->model('accounts_model');
		$this->load->model('log_model');
        $this->load->model('packet_model');
		
		$this->session->sess_destroy(); // menghapus semua session yang ada dalam aplikasi		
		$valid 		= false; // kondisi awal parameter login
		$data_user	= $this->accounts_model->get_all_login(); // menampilkan semua data di table accounts
		
		// load library validasi
		$this->load->library('form_validation');
		
		//cek validasi input
		$this->form_validation->set_rules('email', 'Email ', 'required|valid_email|callback_email_is_exist|callback_email_is_active');
		$this->form_validation->set_rules('password', 'Password ', 'required|md5');
		
		$this->form_validation->set_message('required', '<strong>%s</strong> tidak boleh kosong!');
		$this->form_validation->set_message('valid_email', 'penulisan <strong>%s</strong> tidak benar!');
		$this->form_validation->set_message('email_is_exist', '<strong>%s</strong> tidak ada!');
		$this->form_validation->set_message('email_is_active', 'Akun belum diaktifkan!');
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		if($this->form_validation->run() == TRUE)
		{
			$email 	= $this->input->post('email');
			$password 	= $this->input->post('password');
			$row = $this->accounts_model->cek_login($email,$password);
			if(!empty($row)){
					
					$valid = true;
                    $packet = $this->packet_model->get_packet_byAcc($row->ID_ACCOUNT, $row->KODE_REGISTRASI);
					
					//setting session terhadap data user
					$newdata = array(
						'id_account'		=> $row->ID_ACCOUNT,
						'email' 			=> $row->EMAIL,
						'nama'				=> $row->NAMA_USER,
						'kode_registrasi' 	=> $row->KODE_REGISTRASI,
                        'order_packet'      => $packet->num_rows() > 0 ? 1:0
					);
					
					$this->session->set_userdata($newdata);
				}			
		
			//apabila login telah sesuai dengan email dan password maka user akan masuk halaman utama
			if($valid){ 
				$log = "LOGIN kedalam sistem";
				$id_user = $this->session->userdata("id_account");
				$kode_reg = $this->session->userdata("kode_registrasi");
				
				$this->log_model->log($id_user, $kode_reg, NULL, $log);
				redirect('dashboard');
			}
			//apabila login tidak sesuai dengan email dan password maka user akan masuk halaman login
			else{
				$data['msg'] = '<div class="error_validation">Email atau Password anda salah !!!</div>';
				$data['cek_error'] = "-error";
				$data['content'] = $this->load->view('form_login', $data, true);
				$this->load->view('front', $data);
			}
		
		} 
		else 
		{
		
			$this->form(1);
		
		} // end foreach		
		
	}//end cek_login	
	
	
	
	function form($cek_form = NULL)
	{
		
		$this->load->model('accounts_model');
		$this->load->model('log_model');
		
		if($cek_form == NULL) 
		{
			$data['cek_error'] = NULL;
			
		}else{
			
			$data['cek_error'] = "-error";
		}
		
		$data['content'] = $this->load->view('form_login',$data, true);
		$this->load->view('front', $data);
	}
	
	
	function cek_session()
	{
		if($this->session->userdata('email') != NULL)
			redirect('beranda');
  	}
	
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */