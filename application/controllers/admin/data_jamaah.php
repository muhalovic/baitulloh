<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_jamaah extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		if($this->session->userdata('id_user') == NULL)
			redirect(site_url()."/login");
	}

	function index()
	{
		$this->lihat();
	}
	
	function lihat()
	{
		//call library or helper
		$this->load->library('flexigrid');	
		$this->load->helper('flexigrid');
		$this->load->library('form_validation');		
		
		//call model here	
		$this->load->model('jamaah_candidate_model');
		$this->load->model('group_departure_model');
		
		$group 			= $this->group_departure_model->get_all_group();
		$total_data 	= $this->jamaah_candidate_model->get_total_data_aktif();
		$total_data		= ''.$total_data ;
		
		$colModel['no'] 					= array('No',40,TRUE,'center',0);
		$colModel['KODE_REGISTRASI'] 		= array('Kode Registrasi',100,TRUE,'center',0);
		$colModel['NAMA_USER'] 				= array('Nama Registrasi',100,TRUE,'center',1);
		$colModel['GROUP'] 					= array('Group',60,FALSE,'center',0);
		$colModel['NAMA_LENGKAP'] 			= array('Nama Calon',100,TRUE,'center',1);
		$colModel['JENIS_KELAMIN'] 			= array('Gender',80,TRUE,'center',1);
		$colModel['KOTA'] 					= array('Kota',80,TRUE,'center',1);
		$colModel['ALAMAT'] 				= array('Alamat',110,TRUE,'center',1);
		$colModel['TELP'] 					= array('Telepon',90,TRUE,'center',1);
		$colModel['MOBILE'] 				= array('Handphone',90,TRUE,'center',1);
		$colModel['TANGGAL_ENTRI']			= array('Tanggal Entri',75,TRUE,'center',0);
		$colModel['STATUS_JAMAAH']			= array('Status',75,TRUE,'center',1);
		$colModel['DATA_PASPOR']			= array('Paspor',40,FALSE,'center',0);
		
		$gridParams = array(
		'width' => '1190',
		'height' => 300,
		'rp' => 10,
		'rpOptions' => '[5,10,15,20,25,30,'.$total_data.']',
		'pagestat' => 'Menampilkan: {from} sampai {to} dari {total} hasil.',
		'blockOpacity' => 0.5,
		'title' => 'Data calon jamaah yang tercatat dalam sistem',
		'showTableToggleBtn' => false
		);
		
		$buttons[] = array('separator');
		$buttons[] = array('Tambah','add','js');
        $buttons[] = array('separator');
				
		$grid_js = build_grid_js('flex1',site_url("/admin/data_jamaah/grid_calon_jamaah/"),$colModel,'no','asc',$gridParams,$buttons);
		$data['js_grid'] = $grid_js;
		
		$data['added_js'] = "<script type='text/javascript'>
			function js(com,grid)
			{
				if(com=='Tambah')
				{
					location.href='".site_url('/admin/master_group_departure/add_group_departure')."';
				}

    			

             
			}


			function edit(hash){
				if(confirm('Anda yakin ingin merubah data ini?')){
					location.href='".site_url()."/admin/master_group_departure/edit_group_departure/'+hash;
				}
			}

			function hapus(hash){
				if(confirm('Anda yakin ingin menghapus data ini?')){
					location.href='".site_url()."/admin/master_group_departure/delete_group_departure/'+hash;
				}
			}
             
			</script>
			";
		
		$group_options['0'] = '-- Pilih Group --';
		foreach($group->result() as $row){
			$group_options[$row->ID_GROUP] = $row->KODE_GROUP;
		}

		$data['group_options'] = $group_options;
		$data['content'] = $this->load->view('admin/data_jamaah',$data,true);
		$this->load->view('admin/front',$data);		
	}

	function grid_calon_jamaah() 
	{
		
		//call library or helper
		$this->load->library('flexigrid');	
		$this->load->helper('flexigrid');
		$this->load->library('form_validation');		
		
		//call model here	
		$this->load->model('jamaah_candidate_model');
		$this->load->model('packet_model');
		$this->load->model('group_departure_model');
		
		$valid_fields = array('KODE_REGISTRASI','NAMA_USER','NAMA_LENGKAP','JENIS_KELAMIN','KOTA','ALAMAT','TELP','MOBILE','TANGGAL_ENTRI','STATUS_JAMAAH');
		$this->flexigrid->validate_post('ID_ACCOUNT','desc',$valid_fields);
		
		$records = $this->jamaah_candidate_model->get_grid_allover_jamaah2();
		$this->output->set_header($this->config->item('json_header'));
		
		$no = 0;
                
		foreach ($records['records']->result() as $row)
		{
			$data_packet = $this->packet_model->get_packet_byAccAll($row->ID_ACCOUNT, $row->KODE_REGISTRASI);
			if($data_packet->result() != NULL)
			{
				foreach($data_packet->result() as $rows)
				{
					$id_group = $rows->ID_GROUP;
				}
			}else{
				$id_group = NULL;
			}
			
			$data_group = $this->group_departure_model->get_group_berdasarkan_id($id_group);
			if($data_packet->result() != NULL)
			{
				foreach($data_group->result() as $rows)
				{
					$kode_group = $rows->KODE_GROUP;
				}
			}else{
				$kode_group = "";
			}		
			
			$url_paspor = '<a style="cursor:pointer" onClick="window.open(\''.site_url().'/admin/data_jamaah/paspor_view/'.$row->ID_CANDIDATE.'/'.$row->KODE_REGISTRASI.'\',\'paspor\',\'width=600,height=210,left=400,top=100,screenX=400,screenY=100\')"><img src="'.base_url().'/images/flexigrid/book.png"></a>';
			
			$record_items[] = array(
				$row->ID_CANDIDATE,
				$no = $no+1,
				$row->KODE_REGISTRASI,
				$row->NAMA_USER,	
				$kode_group,
				$row->NAMA_LENGKAP,
				$row->JENIS_KELAMIN,
				$row->KOTA,
				$row->ALAMAT,
				$row->TELP,
				$row->MOBILE,
				date("d M Y", strtotime($row->TANGGAL_ENTRI)),
				$row->STATUS_JAMAAH,
				$url_paspor
			);
		}
		
		if(isset($record_items))
			$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
		else
			$this->output->set_output('{"page":"1","total":"0","rows":[]}');			
	}
	
	function paspor_view($id_candidate, $kode_reg)
	{
		$this->load->model('jamaah_candidate_model');
		$this->load->model('packet_model');
		$this->load->model('group_departure_model');
		
		$data_jamaah = $this->jamaah_candidate_model->get_profile_view($id_candidate, $kode_reg);
		if($data_jamaah->result() != NULL)
		{
			foreach($data_jamaah->result() as $row)
			{
				$data_packet = $this->packet_model->get_packet_byAccAll($row->ID_ACCOUNT, $row->KODE_REGISTRASI);
				if($data_packet->result() != NULL)
				{
					foreach($data_packet->result() as $rows)
					{
						$id_group = $rows->ID_GROUP;
					}
				}else{
					$id_group = NULL;
				}
				
				$data_group = $this->group_departure_model->get_group_berdasarkan_id($id_group);
				if($data_packet->result() != NULL)
				{
					foreach($data_group->result() as $rows)
					{
						$data['kode_group'] = $rows->KODE_GROUP;
					}
				}else{
					$data['kode_group'] = NULL;
				}	
				
				$data['nama_jamaah'] = $row->NAMA_LENGKAP;
				$data['tempat_lahir'] = $row->TEMPAT_LAHIR;
				$data['tgl_lahir'] = $row->TANGGAL_LAHIR;
				$data['jenkel'] = $row->JENIS_KELAMIN;
				$data['no_paspor'] = $row->NO_PASPOR;
				$data['tgl_dikeluarkan'] = $row->TANGGAL_DIKELUARKAN;
				$data['tgl_habis'] = $row->TANGGAL_HABIS;
				$data['kantor'] = $row->KANTOR_PEMBUATAN;
				$data['scan_paspor'] = $row->SCAN_PASPOR;
			}
		}
		
		$this->load->view('window_paspor', $data, '');
	}
	
	
	function input($id_user,$kode_registrasi)
	{
		$this->load->library('form_validation');
		
		$this->load->model('province_model');
		$this->load->model('clothes_size_model');
		$this->load->model('relation_model');
		$this->load->model('room_packet_model');
		$this->load->model('packet_model');
		$this->load->model('accounts_model');
		
		$id_account = $id_user;
		$kode_reg = $kode_registrasi;
		
		$province = $this->province_model->get_all_province();
		$relation = $this->relation_model->get_all_relation();
		$chlothes = $this->clothes_size_model->get_all_clothes();
		$packet = $this->packet_model->get_packet_byAccAll($id_account, $kode_reg);
		$account = $this->accounts_model->get_data_account($id_account)->row(); 
		
		foreach($packet->result() as $row)
		{
			$id_packet = $row->ID_PACKET;
		}

		$province_options['0'] = '-- Pilih Propinsi --';
		foreach($province->result() as $row){
				$province_options[$row->ID_PROPINSI] = $row->NAMA_PROPINSI;
		}
		
		$relasi_options['0'] = '-- Pilih Relasi --';
		foreach($relation->result() as $row){
				$relasi_options[$row->ID_RELATION] = $row->JENIS_RELASI;
		}
		
		$chlothes_options['0'] = '-- Pilih Ukuran Baju --';
		foreach($chlothes->result() as $row){
				$chlothes_options[$row->ID_SIZE] = $row->SIZE;
		}
		
		
		$kamar_options['0'] = '-- Pilih Kamar --';
		$kamar = $this->room_packet_model->get_room_packet_byIDpack($id_packet);
		if($kamar->result() != NULL)
		{
			foreach($kamar->result() as $row){
				$kamar_options[$row->ID_ROOM_PACKET] = $row->JENIS_KAMAR;
			}
		}
		
		$data['province_options'] = $province_options;
		$data['relasi_options'] = $relasi_options;
		$data['chlothes_options'] = $chlothes_options;
		$data['kamar_options'] = $kamar_options;
		$data['nama_user'] = $account->NAMA_USER;
		$data['email_user'] = $account->EMAIL;
		$data['telp_user'] = $account->TELP;
		$data['mobile_user'] = $account->MOBILE;
		$data['alamat_user'] = $account->ALAMAT;
		
		$data['error_file'] = '';
		if($this->session->userdata('upload_file') != '')
		{
			$data['error_file'] = '<div id="message-blue">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="blue-left">'.$this->session->userdata('upload_file').'</td>
								<td class="blue-right"><a class="close-blue"><img src="'.base_url().'images/table/icon_close_blue.gif"   alt="" /></a></td>
							</tr>
						</table><br>
					</div>';
			
		}
		$this->session->unset_userdata('upload_file');
		$data['content'] = $this->load->view('admin/biodata_input', $data, true);
		$this->load->view('front', $data);
	}
	
	function do_daftar($id_user=null,$kode_registrasi=null){
		
		$this->load->library('form_validation');
		$this->load->model('jamaah_candidate_model');
		$this->load->model('log_model');
		
		$log = "Mendaftarkan 1 Calon Jamaah";
		
		if(is_null($id_user) || is_null($kode_registrasi)){
			redirect('/admin/data_jamaah/');
		}
		
		if ($this->cek_validasi() == FALSE){
			$this->input($id_user,$kode_registrasi);
		}
		else{
			// tanggal lahir
			$tgl = $this->input->post('tgl_lahir');
			$bln = $this->input->post('bln_lahir');
			$thn = $this->input->post('thn_lahir');
			$dates = $thn."-".$bln."-".$tgl;
			
			// pelayanan khusus
			$kursi = $this->input->post('kursi_roda');
			$asisten = $this->input->post('asisten');
			if(empty($kursi)) $kursi = 0;
			if(empty($asisten)) $asisten = 0;
			$pelayanan_khusus = $kursi.";".$asisten;
			
			// perihal pribadi
			$darah_tinggi = $this->input->post('darah_tinggi');
			$takut_ketinggian = $this->input->post('takut_ketinggian');
			$smooking_room = $this->input->post('smooking_room');
			$jantung = $this->input->post('jantung');
			$asma = $this->input->post('asma');
			$mendengkur = $this->input->post('mendengkur');
			if(empty($darah_tinggi)) $darah_tinggi = 0;
			if(empty($takut_ketinggian)) $takut_ketinggian = 0;
			if(empty($smooking_room)) $smooking_room = 0;
			if(empty($jantung)) $jantung = 0;
			if(empty($asma)) $asma = 0;
			if(empty($mendengkur)) $mendengkur = 0;
			$perihal_pribadi = $darah_tinggi.";".$takut_ketinggian.";".$smooking_room.";".$jantung.";".$asma.";".$mendengkur;
			
			$cek_foto = $_FILES['foto']['name'];
			if($cek_foto != "")
			{
				
				if(!is_dir('./images/upload/foto'))
				{
					mkdir('./images/upload/foto',0777);
				}
				
				// Upload Foto
				$config['upload_path'] = './images/upload/foto/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
				$config['max_size']	= '5000';
				$config['encrypt_name']	= TRUE;
				
				$this->load->library('upload', $config);
				
				if(!$this->upload->do_upload('foto'))
				{
					$this->session->set_userdata('upload_file', $this->upload->display_errors("<p>Error Foto : ", "</p>"));
					$data_file = NULL;
					$valid_file = FALSE;
				
				}else{
					
					$data_file = $this->upload->data();
					$valid_file = TRUE;
				}
				
				$file_foto = $data_file['file_name'];
			}else{
				$file_foto = NULL;
				$valid_file = TRUE;
			}
			
			
			if($this->input->post('gender') == 2 && $this->input->post('relasi') == '6')
			{
				$mahram_s = $this->input->post('mahram');
			}else{
				$mahram_s = 0;
			}
			
			
			// insert ke database
			$data = array(
				'ID_RELATION' => $this->input->post('relasi'),
				'ID_SIZE' => $this->input->post('baju'),
				'ID_ACCOUNT' => $id_user,
				'KODE_REGISTRASI' => $kode_reg,
				'ID_PROPINSI' => $this->input->post('province'),
				'NAMA_LENGKAP' => $this->input->post('nama_lengkap'),
				'NAMA_PANGGILAN' => $this->input->post('panggilan'),
				'GENDER' => $this->input->post('gender'),
				'WARGA_NEGARA' => $this->input->post('warga_negara'),
				'TEMPAT_LAHIR' => $this->input->post('tempat_lahir'),
				'TANGGAL_LAHIR' => $dates,
				'AYAH_KANDUNG' => $this->input->post('ayah_kandung'),
				'NO_PASPOR' => NULL,
				'TANGGAL_DIKELUARKAN' => NULL,
				'TANGGAL_HABIS' => NULL,
				'KANTOR_PEMBUATAN' => NULL,
				'SCAN_PASPOR' => NULL,
				'KOTA' => $this->input->post('kota'),
				'ALAMAT' => $this->input->post('alamat'),
				'TELP' => $this->input->post('telp'),
				'MOBILE' => $this->input->post('hp'),
				'LAYANAN_KHUSUS' => $pelayanan_khusus,
				'PERIHAL_PRIBADI' => $perihal_pribadi,
				'FOTO' => $file_foto,
				'JASA_TAMBAHAN' => $this->input->post('jasa_maningtis'),
				'REQUESTED_NAMA' => $this->input->post('jasa_paspor_nama'),
				'MAHRAM' => $mahram_s,
				'TANGGAL_ENTRI' => date("Y-m-d H:i:s"),
				'TANGGAL_UPDATE' => date("Y-m-d H:i:s"),
				'ID_ROOM_PACKET' => $this->input->post('kamar'),
				'STATUS_KANDIDAT' => 1,
				'VIA'=>'admin');
			
			if($valid_file)
			{
				$this->jamaah_candidate_model->insert_jamaah($data);
				$this->log_model->log($id_user, $kode_reg, NULL, $log);
			
				redirect('/admin/data_jamaah/');
			}
			else{
				$this->input();
			}
		}
	}
	
	
	function cek_validasi() {
		$this->load->library('form_validation');
		//setting rules
		$config = array(
				array('field'=>'nama_lengkap','label'=>'Nama Lengkap', 'rules'=>'required'),
				array('field'=>'gender','label'=>'Jenis Kelamin', 'rules'=>'callback_cek_dropdown'),
				array('field'=>'ayah_kandung','label'=>'Ayah', 'rules'=>'required'),
				array('field'=>'warga_negara','label'=>'Warga Negara', 'rules'=>'required'),
				array('field'=>'tempat_lahir','label'=>'Tempat Lahir', 'rules'=>'required'),
				array('field'=>'tgl_lahir','label'=>'Tgl. Lahir', 'rules'=>'callback_cek_dropdown'),
				array('field'=>'bln_lahir','label'=>'Tgl. Lahir', 'rules'=>'callback_cek_dropdown'),
				array('field'=>'thn_lahir','label'=>'Tgl. Lahir', 'rules'=>'callback_cek_dropdown'),
				array('field'=>'province','label'=>'Provisi', 'rules'=>'callback_cek_dropdown'),
				array('field'=>'kota','label'=>'Kota', 'rules'=>'required'),
				array('field'=>'alamat','label'=>'Alamat', 'rules'=>'required'),
				array('field'=>'email','label'=>'Email', 'rules'=>'valid_email'),
				array('field'=>'telp','label'=>'Telephone', 'rules'=>'required|numeric'),
				array('field'=>'hp','label'=>'Handphone', 'rules'=>'numeric'),
				array('field'=>'relasi','label'=>'Hubungan', 'rules'=>'callback_cek_dropdown'),
				array('field'=>'baju','label'=>'Baju', 'rules'=>'callback_cek_dropdown'),
				array('field'=>'kamar','label'=>'Kamar', 'rules'=>'callback_cek_dropdown'),
			//	array('field'=>'foto','label'=>'Foto', 'rules'=>'required'),
			);
		
		
		$this->form_validation->set_rules($config);
		$this->form_validation->set_message('required', 'Kolom <strong>%s</strong> harus diisi !');
		$this->form_validation->set_message('valid_email', 'Penulisan kolom <strong>%s</strong> tidak benar!');
		$this->form_validation->set_message('numeric', '<strong>Kolom %s</strong> harus berupa angka !');
		//$this->form_validation->set_error_delimiters('<li class="error">', '</li>');

		return $this->form_validation->run();
    }

    //cek pilihan sdh bener ap blm
    function cek_dropdown($value){
		$this->load->library('form_validation');
		if($value==0){
				$this->form_validation->set_message('cek_dropdown', 'Pilih salah satu <strong>%s</strong> !');
				return FALSE;
		}else
				return TRUE;
    }
	
	// HALAMAN EDIT CALON JAMAAH
	function edit($id_candidate = NULL, $id_account = NULL)
	{
		
		$this->load->library('form_validation');
		$this->load->model('jamaah_candidate_model');
		$this->load->model('accounts_model');
		
		if(is_null($candidate) || is_null($id_account)){
			redirect('/admin/data_jamaah/');
		}
		
		$data_jamaah = $this->jamaah_candidate_model->get_jamaah_berdasarkan_id_accaount_candidate($id_candidate, $id_account);
		$account = $this->accounts_model->get_data_account($id_account)->row(); 
		
		if($data_jamaah->result() != NULL)
		{
			foreach($data_jamaah->result() as $row)
			{
				if($row->REQUESTED_NAMA != "0") { $req_nama = $row->REQUESTED_NAMA; }
				  else{ $req_nama = NULL; }
				
				$data['e_id_candidate'] = $row->ID_CANDIDATE;
				$data['e_id_account'] = $row->ID_ACCOUNT;
				$data['e_nama_lengkap'] = $row->NAMA_LENGKAP;
				$data['e_nama_panggilan'] = $row->NAMA_PANGGILAN;
				$data['e_gender'] = $row->GENDER;
				$data['e_ayah_kandung'] = $row->AYAH_KANDUNG;
				$data['e_warga_negara'] = $row->WARGA_NEGARA;
				$data['e_tempat_lahir'] = $row->TEMPAT_LAHIR;
				$data['e_tgl_lahir'] = $row->TANGGAL_LAHIR;
				$data['e_id_propinsi'] = $row->ID_PROPINSI;
				$data['e_kota'] = $row->KOTA;
				$data['e_alamat'] = $row->ALAMAT;
				$data['e_mahram'] = $row->MAHRAM;
				$data['e_telp'] = $row->TELP;
				$data['e_hp'] = $row->MOBILE;
				$data['e_id_relation'] = $row->ID_RELATION;
				$data['e_id_size'] = $row->ID_SIZE;
				$data['e_layanan_khusus'] = $row->LAYANAN_KHUSUS;
				$data['e_perihal_pribadi'] = $row->PERIHAL_PRIBADI;
				$data['e_pas_foto'] = $row->FOTO;
				$data['e_jasa_tambahan'] = $row->JASA_TAMBAHAN;
				$data['e_kamar'] = $row->ID_ROOM_PACKET;
				$data['e_request_nama'] = $req_nama;
				$data['nama_user'] = $account->NAMA_USER;
				$data['email_user'] = $account->EMAIL;
				$data['telp_user'] = $account->TELP;
				$data['mobile_user'] = $account->MOBILE;
				$data['alamat_user'] = $account->ALAMAT;
				
				// PECAH TANGGAL LAHIR
				$pecah_tgl = explode("-", $data['e_tgl_lahir']);
				$data['e_thn_lahir'] = $pecah_tgl[0];
				$data['e_bln_lahir'] = $pecah_tgl[1];
				$data['e_tgl_lahir'] = $pecah_tgl[2];
				
				// PECAH PELAYANAN KHUSUS
				$pecah_khusus = explode(";", $data['e_layanan_khusus']);
				$data['e_khusus_kursi'] = $pecah_khusus[0];
				$data['e_khusus_asisten'] = $pecah_khusus[1];
				
				// PECAH PERIHAL PRIBADI
				$pecah_pribadi = explode(";", $data['e_perihal_pribadi']);
				$data['e_perihal_darah'] = $pecah_pribadi[0];
				$data['e_perihal_tinggi'] = $pecah_pribadi[1];
				$data['e_perihal_smooking'] = $pecah_pribadi[2];
				$data['e_perihal_jantung'] = $pecah_pribadi[3];
				$data['e_perihal_asma'] = $pecah_pribadi[4];
				$data['e_perihal_mendengkur'] = $pecah_pribadi[5];
				
				
				// LOAD DATA DROPDOWN
				$this->load->model('province_model');
				$this->load->model('clothes_size_model');
				$this->load->model('relation_model');
				$this->load->model('room_packet_model');
				$this->load->model('packet_model');
				
				$id_account = $this->session->userdata('id_account');
				$kode_reg = $this->session->userdata('kode_registrasi');
				
				$province = $this->province_model->get_all_province();
				$relation = $this->relation_model->get_all_relation();
				$chlothes = $this->clothes_size_model->get_all_clothes();
				$packet = $this->packet_model->get_packet_byAccAll($id_account, $kode_reg);
				
				foreach($packet->result() as $row)
				{
					$id_packet = $row->ID_PACKET;
				}
		
				$province_options['0'] = '-- Pilih Propinsi --';
				foreach($province->result() as $row){
						$province_options[$row->ID_PROPINSI] = $row->NAMA_PROPINSI;
				}
				
				$relasi_options['0'] = '-- Pilih Relasi --';
				foreach($relation->result() as $row){
						$relasi_options[$row->ID_RELATION] = $row->JENIS_RELASI;
				}
				
				$chlothes_options['0'] = '-- Pilih Ukuran Baju --';
				foreach($chlothes->result() as $row){
						$chlothes_options[$row->ID_SIZE] = $row->SIZE;
				}
				
				$kamar_options['0'] = '-- Pilih Kamar --';
				$kamar = $this->room_packet_model->get_room_packet_byIDpack($id_packet);
				if($kamar->result() != NULL)
				{
					foreach($kamar->result() as $row){
						$kamar_options[$row->ID_ROOM_PACKET] = $row->JENIS_KAMAR;
					}
				}
				
				$data['province_options'] = $province_options;
				$data['relasi_options'] = $relasi_options;
				$data['chlothes_options'] = $chlothes_options;
				$data['kamar_options'] = $kamar_options;
				
				$data['notifikasi'] = null;
				if($this->session->userdata('sukses') == 'true'){
					$data['notifikasi'] = '<div id="message-green">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="green-left">Data Profil Calon Jamaah Berhasil diubah.</td>
								<td class="green-right"><a class="close-green"><img src="'.base_url().'images/table/icon_close_green.gif"   alt="" /></a></td>
							</tr>
						</table><br>
					</div>';
					$this->session->unset_userdata('sukses');
				}
				
				
				$data['error_file'] = '';
				if($this->session->userdata('upload_file') != '')
				{
					$data['error_file'] = '<div id="message-blue">
								<table border="0" width="100%" cellpadding="0" cellspacing="0">
									<tr>
										<td class="blue-left">'.$this->session->userdata('upload_file').'</td>
										<td class="blue-right"><a class="close-blue"><img src="'.base_url().'images/table/icon_close_blue.gif"   alt="" /></a></td>
									</tr>
								</table><br>
							</div>';
					$this->session->unset_userdata('upload_file');
				}
				
				
				
				$data['content'] = $this->load->view('admin/biodata_edit', $data, true);
				$this->load->view('front', $data);
			
			}
		
		} else {
			
			redirect(site_url()."/biodata/list_jamaah");
		}
		
	}
	
	function do_edit(){
		
		$this->load->library('form_validation');
		$this->load->model('jamaah_candidate_model');
		$this->load->model('log_model');

		$log = "Mengubah data profile Calon Jamaah";
		
		$id_user = $this->session->userdata("id_account");
		$kode_reg = $this->session->userdata("kode_registrasi");
		
		if ($this->cek_validasi() == FALSE){
			// ID CANDIDATE 
			$id_candidate = $this->input->post('id_candidate');
			$id_account = $this->input->post('id_account');
			$this->edit($id_candidate, $id_account);
		}
		else{
			
			// ID CANDIDATE 
			$id_candidate = $this->input->post('id_candidate');
			$id_account = $this->input->post('id_account');
			
			// tanggal lahir
			$tgl = $this->input->post('tgl_lahir');
			$bln = $this->input->post('bln_lahir');
			$thn = $this->input->post('thn_lahir');
			$dates = $thn."-".$bln."-".$tgl;
			
			// pelayanan khusus
			$kursi = $this->input->post('kursi_roda');
			$asisten = $this->input->post('asisten');
			if(empty($kursi)) $kursi = 0;
			if(empty($asisten)) $asisten = 0;
			$pelayanan_khusus = $kursi.";".$asisten;
			
			// perihal pribadi
			$darah_tinggi = $this->input->post('darah_tinggi');
			$takut_ketinggian = $this->input->post('takut_ketinggian');
			$smooking_room = $this->input->post('smooking_room');
			$jantung = $this->input->post('jantung');
			$asma = $this->input->post('asma');
			$mendengkur = $this->input->post('mendengkur');
			if(empty($darah_tinggi)) $darah_tinggi = 0;
			if(empty($takut_ketinggian)) $takut_ketinggian = 0;
			if(empty($smooking_room)) $smooking_room = 0;
			if(empty($jantung)) $jantung = 0;
			if(empty($asma)) $asma = 0;
			if(empty($mendengkur)) $mendengkur = 0;
			$perihal_pribadi = $darah_tinggi.";".$takut_ketinggian.";".$smooking_room.";".$jantung.";".$asma.";".$mendengkur;
			
			
			// cek requuest jasa nama paspor
			if($this->input->post('jasa_paspor_nama_edit') != NULL)
			{
				if($this->input->post('jasa_paspor_nama') != NULL)
				{
					$request_nama = $this->input->post('jasa_paspor_nama');
				
				} else {
					
					$request_nama = $this->input->post('jasa_paspor_nama_edit');
				}
			
			} else {
				
				$request_nama = $this->input->post('jasa_paspor_nama');
			}
			
			if(!is_dir('./images/upload/foto'))
			{
				mkdir('./images/upload/foto',0777);
			}
			
			// cek foto
			$cek_foto = $_FILES['foto']['name'];
			if($cek_foto != "")
			{
				// Upload Foto
				$config['upload_path'] = './images/upload/foto/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
				$config['max_size']	= '5000';
				$config['encrypt_name']	= TRUE;
				
				$this->load->library('upload', $config);
				
				if(!$this->upload->do_upload('foto'))
				{
					$this->session->set_userdata('upload_file', $this->upload->display_errors("<p>Error Foto : ", "</p>"));
					$data_file = NULL;
					$valid_file = FALSE;
				
				}else{
					$data_file = $this->upload->data();
					$valid_file = TRUE;
				}
				
				$valid = TRUE;
			
			} else {
				$valid = FALSE;
				$valid_file = TRUE;
			}
			
			if($this->input->post('gender') == 2 && $this->input->post('relasi') == '6')
			{
				$mahram_s = $this->input->post('mahram');
			}else{
				$mahram_s = 0;
			}
			
			// update table
			$data = array(
				'ID_RELATION' => $this->input->post('relasi'),
				'ID_SIZE' => $this->input->post('baju'),
				'ID_PROPINSI' => $this->input->post('province'),
				'NAMA_LENGKAP' => $this->input->post('nama_lengkap'),
				'NAMA_PANGGILAN' => $this->input->post('panggilan'),
				'GENDER' => $this->input->post('gender'),
				'WARGA_NEGARA' => $this->input->post('warga_negara'),
				'TEMPAT_LAHIR' => $this->input->post('tempat_lahir'),
				'TANGGAL_LAHIR' => $dates,
				'AYAH_KANDUNG' => $this->input->post('ayah_kandung'),
				'KOTA' => $this->input->post('kota'),
				'ALAMAT' => $this->input->post('alamat'),
				'TELP' => $this->input->post('telp'),
				'MOBILE' => $this->input->post('hp'),
				'LAYANAN_KHUSUS' => $pelayanan_khusus,
				'PERIHAL_PRIBADI' => $perihal_pribadi,
				'JASA_TAMBAHAN' => $this->input->post('jasa_maningtis'),
				'REQUESTED_NAMA' => $request_nama,
				'MAHRAM' => $mahram_s,
				'ID_ROOM_PACKET' => $this->input->post('kamar'),
				'TANGGAL_UPDATE' => date("Y-m-d H:i:s"),
				'VIA'=>'admin'
				);
			
			if($valid_file)
			{
				if($valid)
				{
					$foto = array('FOTO' => $data_file['file_name']);
					$this->jamaah_candidate_model->update_jamaah($foto, $id_candidate);
					
					$file_gambar = $data_file['file_path'].$this->input->post('foto_edit');
					if(is_file($file_gambar))
					{
						unlink($file_gambar);
					}
				}
				
				//buat session sukses
				$this->session->set_userdata('sukses','true');
				$this->log_model->log($id_user, $kode_reg, NULL, $log);
				$update = $this->jamaah_candidate_model->update_jamaah($data, $id_candidate);
				redirect('/biodata/edit/'.$id_candidate.'/'.$id_account);
			}else{
				$this->edit($id_candidate, $id_account);
			}
		}
	}

        // cek order packet
        function cekOrder(){
            $this->load->model('packet_model');
            $id_user = $this->session->userdata("id_account");
            $kode_reg = $this->session->userdata("kode_registrasi");

            $packet = $this->packet_model->get_packet_byAcc($id_user, $kode_reg);
            if ($packet->num_rows() < 1)
                    redirect('beranda');
        }
	
}//end class

/* End of file /admin/data_jamaah.php */
/* Location: ./application/controllers/admin/data_jamaah.php */