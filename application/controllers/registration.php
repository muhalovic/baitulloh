<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Registration extends CI_Controller {
	var $data_field;
	var $tmp_pass;
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}

	function index()
	{	
		$this->front();
	}
	
	function front(){
		$waiting = $this->input->post('waiting');
		
		$this->load->model('province_model');
		$this->load->model('kota_model');
		$this->load->helper('captcha');
		
		$province = $this->province_model->get_all_province_ByStatus();
		
		$kota_options[''] = '-- Pilih Kota --';
		if(isset($_POST['province'])){
		$kota = $this->kota_model->get_kota_by_province($this->input->post('province'));
		foreach($kota->result() as $row){
			$kota_options[$row->NAMA_KOTA] = $row->NAMA_KOTA;
		}
		}
		
		$province_options[''] = '-- Pilih Propinsi --';
		foreach($province->result() as $row){
				$province_options[$row->ID_PROPINSI] = $row->NAMA_PROPINSI;
		}
		
		$vals = array(
		'img_path' => 'captcha/',
		'img_url' => base_url().'captcha/',
		'font_path' => './path/to/fonts/texb.ttf',
		'img_width' => '100',
		'img_height' => 30,
		'expiration' => 60,
		'length'=> 5,
		'numberonly'=>true
		);
		
		$this->session->unset_userdata('captcha');
		$captcha = create_captcha($vals);
		$this->session->set_userdata('captcha',$captcha['word']);
			
		$data['waiting'] = $waiting;
        $data['jml_adult'] = $this->input->post('jml_adult');
		$data['with_bed'] = $this->input->post('with_bed');
		$data['no_bed'] = $this->input->post('no_bed');
		$data['infant'] = $this->input->post('infant');
        $data['group'] = $this->input->post('group');
        $data['program'] = $this->input->post('program');

        $kamar = $this->input->post('kamar');
        $jml_kamar = $this->input->post('jml_kamar');

        for($i=0; $i<count($kamar); $i++){
			$room_choice2[$i] = array('ID_ROOM_TYPE'=>$kamar[$i], 'JUMLAH'=>$jml_kamar[$i]);
        }
        $data['room_choice2'] = $room_choice2;
				
		$data['captcha'] = $captcha['image'];
		$data['province_options'] = $province_options;
		$data['kota_options'] = $kota_options;
		$data['content'] = $this->load->view('form_registration',$data,true);
		$this->load->view('front',$data);
	}
	
	//insert data inputan ke database
    function do_register() {
		if ($this->check_validasi() == FALSE){
			// //$this->session->set_userdata('failed_form','Kegagalan Menyimpan Data, Kesalahan Pengisian Form!');
			$this->front();
		}
		else{
			$this->load_data_form();
			
			if (empty($this->data_field))
				$this->front();
			else{
				$this->load->model('accounts_model');
				$this->load->model('log_model');
                $this->load->model('packet_model');
				
				$this->accounts_model->insert_new_account($this->data_field);						
				$this->log_model->log(null, $this->data_field['KODE_REGISTRASI'], null, 'REGISTER new account, EMAIL = '.$this->data_field['EMAIL'].', KODE_REGISTRASI = '.$this->data_field['KODE_REGISTRASI']);

                $waiting = 0;
				$id_acc = $this->accounts_model->get_account_byKode($this->data_field['KODE_REGISTRASI'])->row()->ID_ACCOUNT;
				
				// data packet
                $group = $this->input->post('group');
                $kelas_program = $this->input->post('program');
                $jml_adult = $this->input->post('jml_adult');
                $with_bed = $this->input->post('with_bed')=='' ? 0:$this->input->post('with_bed');
                $no_bed = $this->input->post('no_bed')=='' ? 0:$this->input->post('no_bed');
                $infant = $this->input->post('infant')=='' ? 0:$this->input->post('infant');
										
				// if waiting list
				if ($this->input->post('waiting') == 1){
					$this->load->model('waiting_list_model');
                    $waiting = 1;
					$data_waiting = array('KODE_REGISTRASI'=>$this->data_field['KODE_REGISTRASI'], 'ID_ACCOUNT'=>$id_acc);
					
					$this->waiting_list_model->insert_waiting_list($data_waiting);
					$this->log_model->log($id_acc, $this->data_field['KODE_REGISTRASI'], null, 'INSERT data WAITING_LIST dengan KODE_REGISTRASI = '.$this->data_field['KODE_REGISTRASI']);
                    
					// insert into packet
                    $data = array(
                        'ID_GROUP'=>$group, 'ID_ACCOUNT'=>$id_acc, 'KODE_REGISTRASI' =>$this->data_field['KODE_REGISTRASI'], 'ID_PROGRAM'=>$kelas_program,
                        'JUMLAH_ADULT'=>$jml_adult, 'CHILD_WITH_BED'=>$with_bed, 'CHILD_NO_BED'=>$no_bed, 'INFANT'=>$infant,
                        'TANGGAL_PESAN'=>date("Y-m-d h:i:s"), 'STATUS_PESANAN'=>2
                    );
                    $this->packet_model->insert_packet($data);
                    $this->log_model->log($id_acc, $this->data_field['KODE_REGISTRASI'], null, 'INSERT data PACKET untuk akun dengan KODE_REGISTRASI = '.$this->data_field['KODE_REGISTRASI']);
                    
					// insert into room packet
					$id_pack = $this->packet_model->get_packet_byAcc_waiting($id_acc, $this->data_field['KODE_REGISTRASI']);
					if ($id_pack->num_rows() > 0){
						$this->load->model('room_packet_model');
						$kamar = $this->input->post('kamar');
						$jml_kamar = $this->input->post('jml_kamar');

						for($i=0; $i<count($kamar); $i++){
							if($jml_kamar[$i]!="0"){
								$this->room_packet_model->insert_room_packet(array('ID_ROOM_TYPE'=>$kamar[$i],
									'ID_PACKET'=>$id_pack->row()->ID_PACKET, 'JUMLAH'=>$jml_kamar[$i]));
							}	
						}

						$this->log_model->log($id_acc, $this->data_field['KODE_REGISTRASI'], null, 'INSERT data ROOM_PACKET untuk packet dengan ID_PACKET = '.$id_pack->row()->ID_PACKET);
					}                    
				}else{
			
					// insert into packet
                    $data = array(
                        'ID_GROUP'=>$group, 'ID_ACCOUNT'=>$id_acc, 'KODE_REGISTRASI' =>$this->data_field['KODE_REGISTRASI'], 'ID_PROGRAM'=>$kelas_program,
						'JUMLAH_ADULT'=>$jml_adult, 'CHILD_WITH_BED'=>$with_bed, 'CHILD_NO_BED'=>$no_bed, 'INFANT'=>$infant,
                        'TANGGAL_PESAN'=>date("Y-m-d h:i:s"), 'STATUS_PESANAN'=>1
                    );
                    $this->packet_model->insert_packet($data);
                    $this->log_model->log($id_acc, $this->data_field['KODE_REGISTRASI'], null, 'INSERT data PACKET untuk akun dengan KODE_REGISTRASI = '.$this->data_field['KODE_REGISTRASI']);
					
					// insert into room packet
					$id_pack = $this->packet_model->get_packet_byAcc($id_acc, $this->data_field['KODE_REGISTRASI']);
					if ($id_pack->num_rows() > 0){
						$this->load->model('room_packet_model');
						$kamar = $this->input->post('kamar');
						$jml_kamar = $this->input->post('jml_kamar');

						for($i=0; $i<count($kamar); $i++){
							if($jml_kamar[$i]!="0"){
								$this->room_packet_model->insert_room_packet(array('ID_ROOM_TYPE'=>$kamar[$i],
									'ID_PACKET'=>$id_pack->row()->ID_PACKET, 'JUMLAH'=>$jml_kamar[$i]));
							}	
						}

						$this->log_model->log($id_acc, $this->data_field['KODE_REGISTRASI'], null, 'INSERT data ROOM_PACKET untuk packet dengan ID_PACKET = '.$id_pack->row()->ID_PACKET);
					}
				}	
				
				$keycode = $this->secure($this->data_field['KODE_REGISTRASI']);
				$this->send_email($keycode, $waiting); 
				
				redirect('pasca_daftar/'.$this->data_field['KODE_REGISTRASI']);
			}
		}
    }
	
	function secure($data){
		$textlen = strlen($data);
		$key = rand(2,9);
		$split = round($textlen/$key);
		
		if ($split > $key) $count = $split-$key; else $count = 1;
		$newstr = "";
		
		for($i = 0; $i < strlen($data); $i ++){
			$part = substr($data, $i, $split);
			$newstr .= $part.substr(md5(rand(1*$key, $textlen*$key)), 1, $count);
			$i += $split-1;
		}
		return $newstr.$key.$split;
	}
	
	function load_data_form() {
		$nama = $this->input->post('nama');
		$email = $this->input->post('email');
		$telp = $this->input->post('telp');
		$mobile = $this->input->post('mobile');
		$province = $this->input->post('province');
		$kota = $this->input->post('kota');
		$alamat = $this->input->post('alamat');
		$id_card = $this->input->post('id_card');
		$kode = $this->input->post('recaptcha_challenge_field');
		
		$kode_reg = substr(md5('koderegistrasi-'.$nama.'-'.$email.'-'.date("Y m d H:i:s")), 0, 4);
		$pwd = $this->input->post('password');
		
		$this->data_field = array('KODE_REGISTRASI' => $kode_reg, 'ID_PROPINSI' => $province, 'NAMA_USER' => $nama, 
								'EMAIL' => $email, 'PASSWORD' =>$pwd, 'NO_ID_CARD' => $id_card, 'TELP' => $telp, 
								'MOBILE' => $mobile, 'KOTA' => $kota, 'ALAMAT' => $alamat, 'TANGGAL_REGISTRASI' =>date("Y-m-d h:i:s"), 'STATUS' => 0);
		
		//return $data_field;
    }
	
	function check_validasi() {
		//setting rules
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('email', 'E-mail', 'required|valid_email|callback_email_is_exist');
		$this->form_validation->set_rules('telp', 'Telp', '');
		$this->form_validation->set_rules('mobile', 'Mobile', '');
		$this->form_validation->set_rules('province', 'Propinsi', 'required');
		$this->form_validation->set_rules('kota', 'Kota', 'required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'required|max_length[255]');
		$this->form_validation->set_rules('id_card', 'No ID Card', 'required|min_length[10]');
		$this->form_validation->set_rules('captcha', 'Captcha', 'required|callback_check_captcha');
		$this->form_validation->set_rules('password', 'Password', 'required|md5');
		$this->form_validation->set_rules('password_verification', 'Verifikasi Password', 'required|matches[password]');
		
		//$this->form_validation->set_error_delimiters('<li class="error">', '</li>');
		$this->form_validation->set_message('required', '%s wajib diisi !');
		$this->form_validation->set_message('valid_email', '%s wajib berisi alamat email yang benar !');
		$this->form_validation->set_message('min_length', '%s minimum berisi %s karakter !');
		$this->form_validation->set_message('max_length', '%s maksimal berisi %s karakter !');
		$this->form_validation->set_message('email_is_exist', '%s sudah digunakan!');
		$this->form_validation->set_message('check_captcha', '%s tidak benar');
		$this->form_validation->set_message('matches', '%s harus sama dengan %s');
		
		return $this->form_validation->run();
    }
	
	function check_captcha($response){
		if($this->session->userdata('captcha')!= $response){
			$this->session->unset_userdata('captcha');
			return false;
		}
		else{
			$this->session->unset_userdata('captcha');
			return true;
		}
		
	}

    //cek pilihan sdh bener ap blm
    function check_dropdown($value){
		if($value==0){
			$this->form_validation->set_message('check_dropdown', 'Harap memilih salah satu %s !');
				return FALSE;
		}else
				return TRUE;
    }
	
	function email_is_exist($value){
		$this->load->model('accounts_model');
		return $this->accounts_model->is_email_enable($value);
    }
	
	function send_email($key, $waiting){
		$this->load->library('email');
		$this->load->library('parser');
		
		$config['protocol'] = 'mail';
		$config['mailtype'] = 'html';

		$this->email->initialize($config);
		
		$data['key'] = $key;
		$data['subject'] = 'Aktivasi Akun';
        $data['waiting'] = $waiting;
		$data['NAMA_USER'] = $this->data_field['NAMA_USER'];
		
		$data['KODE_REGISTRASI'] = $this->data_field['KODE_REGISTRASI'];
		
		// $this->load->view('email_activation',$data);
		$htmlMessage =  $this->parser->parse('email_activation', $data, true);
		
		$this->email->from('noreply@umrahkamilah.com', 'Kamilah Wisata Muslim');
		$this->email->to($this->data_field['EMAIL']);

		$this->email->subject('Aktivasi Akun');
		$this->email->message($htmlMessage);

		// $this->email->send();
                //echo $this->tmp_pass;

		// echo $this->email->print_debugger();
	}
}

/* End of file registration.php */
/* Location: ./application/controllers/registration.php */