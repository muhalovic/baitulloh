<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Biodata extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		if($this->session->userdata('email') == NULL)
			redirect(site_url()."/login");

                $this->cekOrder();
		
	}
	function index()
	{
		$this->list_jamaah();
	}
	
	// HALAMAN LIST CALON JAMAAH
	
	function list_jamaah(){
		
		//call library or helper
		$this->load->library('flexigrid');	
		$this->load->helper('flexigrid');
		$this->load->library('form_validation');		
		
		// call session
		$id_account = $this->session->userdata('id_account');
		$kode_reg = $this->session->userdata('kode_registrasi');
		
		//call model here	
		$this->load->model('jamaah_candidate_model');
		
		$total_data 	= $this->jamaah_candidate_model->get_total_data_sortir($id_account, $kode_reg);
		$total_data		= ''.$total_data ;
		$this->lihat_data_calon_jamaah($total_data);
	}
	
	function lihat_data_calon_jamaah($total_data){
		
		//call library or helper
		$this->load->library('flexigrid');	
		$this->load->helper('flexigrid');
		$this->load->library('form_validation');		
		
		//call model here	
		$this->load->model('jamaah_candidate_model');
		$this->load->model('packet_model');
		
		// call session
		$id_account = $this->session->userdata('id_account');
		$kode_reg = $this->session->userdata('kode_registrasi');
		
		// validasi tambah jamaah
		// - hitung jumlah jamaah di packet
		$data_packet = $this->packet_model->get_packet_byAcc($id_account, $kode_reg);
		$total_calon_di_packet = $data_packet->row()->JUMLAH_ADULT
							   + $data_packet->row()->CHILD_WITH_BED
							   + $data_packet->row()->CHILD_NO_BED
							   + $data_packet->row()->INFANT;
		
		// - hitung jumlah jamaah di jamaah_candidate
		$total_calon_di_jamaah_candidate = $this->jamaah_candidate_model->get_total_data_sortir($id_account, $kode_reg);
		
		// - cek validasi
		if($total_calon_di_jamaah_candidate > $total_calon_di_packet)
		{
			$link_tambah = "alert('Maaf, jumlah calon jamaah melebihi kuota yg ditentukan di konfigurasi packet');";
		}else{
			$link_tambah = "location.href='".site_url()."/biodata/input';";
		}
		
							   
		$colModel['no'] = array('No',40,TRUE,'center',0);
		$colModel['edit'] = array('Edit',40,FALSE,'center',0);
		$colModel['detail'] = array('Detail',40,FALSE,'center',0);
		$colModel['isi_dok'] = array('Isi Dokumen',60,FALSE,'center',0);
		$colModel['lihat_dok'] = array('Lihat Dokumen',70,FALSE,'center',0);
		$colModel['KODE_REGISTRASI'] = array('Kode Reg.',80,TRUE,'center',0);
		$colModel['NAMA_LENGKAP'] = array('Nama Lengkap',200,TRUE,'center',1);
		$colModel['NAMA_PANGGILAN'] = array('Nama Panggilan',150,TRUE,'center',1);
		$colModel['AYAH_KANDUNG'] = array('Ayah Kandung',150,TRUE,'center',1);
		$colModel['GENDER'] = array('Jenis Kelamin',80,FALSE,'center',0);
		$colModel['MAHRAM'] = array('Hubungan Mahram',100,TRUE,'center',0);
		
		$gridParams = array(
		'width' => '1190',
		'height' => 300,
		'rp' => 10,
		'rpOptions' => '[5,10,20,25,30,50,'.$total_data.']',
		'pagestat' => 'Menampilkan: {from} sampai {to} dari {total} hasil.',
		'blockOpacity' => 0.5,
		'title' => 'Biodata Calon Jamaah',
		'showTableToggleBtn' => false
		);						   
		
		$buttons[] = array('separator');
		$buttons[] = array('Tambah','add','spt_js');
		$buttons[] = array('separator');
		$buttons[] = array('Hapus','delete','spt_js');
		$buttons[] = array('separator');
		
		$grid_js = build_grid_js('flex1',site_url("/biodata/grid_calon_jamaah/"),$colModel,'no','asc',$gridParams,$buttons);
		$data['js_grid'] = $grid_js;
		
		$data['added_js'] = 
		"<script type='text/javascript'>
		function spt_js(com,grid){
			if (com=='Tambah'){
				".$link_tambah." 
			}
			if (com=='Hapus'){
			   if($('.trSelected',grid).length>0){
				   if(confirm('Anda yakin ingin menghapus ' + $('.trSelected',grid).length + ' buah data?')){
						var items = $('.trSelected',grid);
						var itemlist ='';
						for(i=0;i<items.length;i++){
							itemlist+= items[i].id.substr(3)+',';
						}
						$.ajax({
						   type: 'POST',
						   url: '".site_url('/biodata/hapus_data_calon_jamaah')."',
						   data: 'items='+itemlist,
						   success: function(data){
							$('#flex1').flexReload();
							alert(data);
						   }
						});
					}
				} else {
					return false;
				} 
			}
		} 
		</script>
		";

                
		$data['content'] = $this->load->view('biodata_list',$data,true);
		$this->load->view('front_backup',$data);		
	}
	
	
	function grid_calon_jamaah() {
		
		//call library or helper
		$this->load->library('flexigrid');	
		$this->load->helper('flexigrid');
		$this->load->library('form_validation');		
		
		//call model here	
		$this->load->model('jamaah_candidate_model');
		
		$valid_fields = array('UKURAN_BAJU','KODE_REGISTRASI','NAMA_LENGKAP','NAMA_PANGGILAN','GENDER','AYAH_KANDUNG','MOBILE','MAHRAM');
		$this->flexigrid->validate_post('ID_ACCOUNT','desc',$valid_fields);
		
		$records = $this->jamaah_candidate_model->get_grid_all_jamaah($this->session->userdata('kode_registrasi'), $this->session->userdata('id_account'));
		$this->output->set_header($this->config->item('json_header'));
		
		$no = 0;
                
		foreach ($records['records']->result() as $row)
		{
			if($row->GENDER == 1) $gender = "Laki-Laki";
			elseif($row->GENDER == 2) $gender = "Perempuan";
			
			if($row->MAHRAM == 0) $mahram = "Ada";
			elseif($row->MAHRAM == 1) $mahram = "Tidak Ada";
			
			if($row->NO_PASPOR != NULL && $row->TANGGAL_DIKELUARKAN != NULL) { $gos = 1; } 
			else{ $gos = 0; }
			
			$record_items[] = array(
			
				$row->ID_CANDIDATE,
				$no = $no+1,
				'<a href=\''.site_url().'/biodata/edit/'.$row->ID_CANDIDATE.'/'.$row->ID_ACCOUNT.'/\'><img border=\'0\' src=\''.base_url().'images/flexigrid/book.png\'></a> ',
				'<a style="cursor:pointer; text-decoration:underline; color:#000;" onClick="window.open(\''.site_url().'/biodata/profile_jamaah/'.$row->ID_CANDIDATE.'/'.$row->KODE_REGISTRASI.'\',\'profil\',\'width=500,height=500,left=400,top=100,screenX=400,screenY=100\')"><img border=\'0\' src=\''.base_url().'images/flexigrid/book.png\'></a>',
				'<a href=\''.site_url().'/paspor/edit/'.$row->ID_CANDIDATE.'/'.$row->ID_ACCOUNT.'/'.$gos.'\'><img border=\'0\' src=\''.base_url().'images/flexigrid/book.png\'></a>',
				'<a style="cursor:pointer" onClick="window.open(\''.site_url().'/biodata/paspor_view/'.$row->ID_CANDIDATE.'/'.$row->KODE_REGISTRASI.'\',\'paspor\',\'width=600,height=210,left=400,top=100,screenX=400,screenY=100\')"><img src="'.base_url().'/images/flexigrid/book.png"></a>',
				$row->KODE_REGISTRASI,	
				$row->NAMA_LENGKAP,
				$row->NAMA_PANGGILAN,
				$row->AYAH_KANDUNG,
				$gender,
				$mahram,	
			);
		}
		
		if(isset($record_items))
			$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
		else
			$this->output->set_output('{"page":"1","total":"0","rows":[]}');			
	}
	
	
	function hapus_data_calon_jamaah()
	{
		$this->load->model('jamaah_candidate_model');
		$this->load->model('log_model');

		$id_user = $this->session->userdata("id_account");
		$kode_reg = $this->session->userdata("kode_registrasi");

		$pecah_id = explode(',' , $this->input->post('items'));
		$hitung_id = (count($pecah_id)) - 1;
		$log = "menghapus ".$hitung_id." Calon Jamaah";
		$hapus_foto = '';
		$hapus_paspor = '';
		$hapus_jamaah = '';

		foreach($pecah_id as $index => $id_candidate)
		{
			if (is_numeric($id_candidate))
			{
				$hapus_foto .= $this->hapus_gambar($id_candidate, "foto");
				$hapus_paspor .= $this->hapus_gambar($id_candidate, "paspor");
				$hapus_jamaah .= $this->jamaah_candidate_model->hapus_data_calon_jamaah($id_candidate);
			}
		}


		$error = "Data Calon Jamaah ( ID : ".$this->input->post('items').") berhasil dihapus";

		$this->log_model->log($id_user, $kode_reg, NULL, $log);
		$this->output->set_header($this->config->item('ajax_header'));
		$this->output->set_output($error);
	}
	
	
	function hapus_gambar($id_candidate, $tipe_gambar)
	{
		$this->load->model('jamaah_candidate_model');
		
		$hapus_foto = $this->jamaah_candidate_model->get_data_berdasarkan_id_candidate($id_candidate);
		
		if($hapus_foto->result() != NULL)
		{
			foreach($hapus_foto->result() as $row)
			{
				if($tipe_gambar == "foto")
				{
					$file_gambar = './images/upload/foto/'.$row->FOTO;
				}elseif($tipe_gambar == "paspor")
				{
					$file_gambar = './images/upload/paspor/'.$row->SCAN_PASPOR;
				}else{
					$file_gambar = NULL;
				}
				
				if(is_file($file_gambar))
				{
					unlink($file_gambar);
					return true;
				}else{
					return false;
				}
			}
		}
	} // end function
	
	
	// HALAMAN TAMBAH CALON JAMAAH
	function input()
	{
		$this->load->library('form_validation');
		
		$this->load->model('province_model');
		$this->load->model('clothes_size_model');
		$this->load->model('relation_model');
		$this->load->model('room_packet_model');
		$this->load->model('packet_model');
		$this->load->model('jamaah_candidate_model');
		
		$id_account = $this->session->userdata('id_account');
		$kode_reg = $this->session->userdata('kode_registrasi');
		
		$province = $this->province_model->get_all_active_province();
		$relation = $this->relation_model->get_all_active_relation();
		$chlothes = $this->clothes_size_model->get_all_active_clothes();
		$packet = $this->packet_model->get_packet_byAcc($id_account, $kode_reg);
		$tipe_jamaah[''] = '-- Pilih Salah Satu Tipe --';
	
			
		foreach($packet->result() as $row)
		{
			$id_packet = $row->ID_PACKET;
			if($row->JUMLAH_ADULT > 0){
			 $tipe_jamaah['A'] = 'Dewasa';
			}
			if($row->CHILD_WITH_BED > 0){
			 $tipe_jamaah['CWB'] = 'Anak dengan Ranjang';	
			}
			if($row->CHILD_NO_BED > 0){
			 $tipe_jamaah['CNB'] = 'Anak tanpa Ranjang'; 
			}
			if($row->INFANT > 0){
			 $tipe_jamaah['I'] = 'Bayi'; 
			}
		}

		$province_options[''] = '-- Pilih Propinsi --';
		foreach($province->result() as $row){
				$province_options[$row->ID_PROPINSI] = $row->NAMA_PROPINSI;
		}
		
		$kota_options[''] = '-- Pilih Kota --';
		if(isset($_POST['province'])){
			$kota = $this->kota_model->get_kota_by_province($this->input->post('province'));
			foreach($kota->result() as $row){
				$kota_options[$row->NAMA_KOTA] = $row->NAMA_KOTA;
			}
		}
		
		$relasi_options[''] = '-- Pilih Relasi --';
		foreach($relation->result() as $row){
				$relasi_options[$row->ID_RELATION] = $row->JENIS_RELASI;
		}
		
		$chlothes_options[''] = '-- Pilih Ukuran Baju --';
		foreach($chlothes->result() as $row){
				$chlothes_options[$row->ID_SIZE] = $row->SIZE;
		}
		
		
		$kamar_options[''] = '-- Pilih Kamar --';
		$kamar = $this->room_packet_model->get_room_packet_byIDpack($id_packet);
		if($kamar->result() != NULL)
		{
			foreach($kamar->result() as $row){
				
				$total_kamar = $row->JUMLAH;
				
				$data_jamaah = $this->jamaah_candidate_model->get_jamaah_byRoomPacket($id_account, $kode_reg, $row->ID_ROOM_PACKET);
				if($data_jamaah->num_rows() < $total_kamar)
				{
					$sisa_bed = $total_kamar - $data_jamaah->num_rows();
					$kamar_options[$row->ID_ROOM_PACKET] = $row->JENIS_KAMAR." - tersedia ".$sisa_bed." bed";
				}else{
					
				}
			}
		}
		
		$data['province_options'] = $province_options;
		$data['kota_options'] = $kota_options;
		$data['relasi_options'] = $relasi_options;
		$data['chlothes_options'] = $chlothes_options;
		$data['kamar_options'] = $kamar_options;
		$data['tipe_jamaah_options'] = $tipe_jamaah;
		
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
		$data['content'] = $this->load->view('biodata_input', $data, true);
		$this->load->view('front_backup', $data);
	}
	
	function do_daftar(){
		
		$this->load->library('form_validation');
		$this->load->model('jamaah_candidate_model');
		$this->load->model('log_model');
		
		$log = "Mendaftarkan 1 Calon Jamaah";
		
		$id_user = $this->session->userdata("id_account");
		$kode_reg = $this->session->userdata("kode_registrasi");
		
		if ($this->cek_validasi() == FALSE){
			$this->input();
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
				'MAHRAM' => $mahram_s,
				'TIPE_JAMAAH' => $this->input->post('tipe_jamaah'),
				'TANGGAL_ENTRI' => date("Y-m-d H:i:s"),
				'TANGGAL_UPDATE' => date("Y-m-d H:i:s"),
				'ID_ROOM_PACKET' => $this->input->post('kamar'),
				'VIA' => 'Dashboard',
				'STATUS_KIRIM_TOOLKIT' => 0,
				'STATUS_KANDIDAT' => 1);
			
			if($valid_file)
			{
				$this->jamaah_candidate_model->insert_jamaah($data);
				$this->log_model->log($id_user, $kode_reg, NULL, $log);
			
				redirect('/biodata/list_jamaah/');
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
				array('field'=>'tipe_jamaah','label'=>'Tipe Jamaah', 'rules'=>'callback_cek_dropdown'),
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
		
		$data_jamaah = $this->jamaah_candidate_model->get_jamaah_berdasarkan_id_accaount_candidate($id_candidate, $id_account);
			
		if($data_jamaah->result() != NULL)
		{
			foreach($data_jamaah->result() as $row)
			{				
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
				$data['e_tipe_jamaah'] = $row->TIPE_JAMAAH;
				$data['e_jasa_tambahan'] = $row->JASA_TAMBAHAN;
				$data['e_kamar'] = $row->ID_ROOM_PACKET;
				$data['e_tipe_jamaah'] = $row->TIPE_JAMAAH;
				
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
				$this->load->model('kota_model');
				$this->load->model('clothes_size_model');
				$this->load->model('relation_model');
				$this->load->model('room_packet_model');
				$this->load->model('packet_model');
				$this->load->model('jamaah_candidate_model');
				
				$id_account = $this->session->userdata('id_account');
				$kode_reg = $this->session->userdata('kode_registrasi');
				
				$province = $this->province_model->get_all_province();
				$relation = $this->relation_model->get_all_relation();
				$chlothes = $this->clothes_size_model->get_all_clothes();
				$packet = $this->packet_model->get_packet_byAcc($id_account, $kode_reg);
				$tipe_jamaah[''] = '-- Pilih Salah Satu Tipe --';
				
				foreach($packet->result() as $row)
				{
					$id_packet = $row->ID_PACKET;
					if($row->JUMLAH_ADULT > 0){
					 $tipe_jamaah['A'] = 'Dewasa';
					}
					if($row->CHILD_WITH_BED > 0){
					 $tipe_jamaah['CWB'] = 'Anak dengan Ranjang';	
					}
					if($row->CHILD_NO_BED > 0){
					 $tipe_jamaah['CNB'] = 'Anak tanpa Ranjang'; 
					}
					if($row->INFANT > 0){
					 $tipe_jamaah['I'] = 'Bayi'; 
					}
				}
		
				$province_options['0'] = '-- Pilih Propinsi --';
				foreach($province->result() as $row){
						$province_options[$row->ID_PROPINSI] = $row->NAMA_PROPINSI;
				}
		
				$kota_options[''] = '-- Pilih Kota --';
				if(isset($_POST['province'])){
					$id_prop = $this->input->post('province');
				}else{
					$id_prop = $data['e_id_propinsi'];
				}
				
				$kota = $this->kota_model->get_kota_by_province($id_prop);
				foreach($kota->result() as $row){
					$kota_options[$row->NAMA_KOTA] = $row->NAMA_KOTA;
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
					foreach($kamar->result() as $row)
					{
						$total_kamar = $row->CAPACITY * $row->JUMLAH;
				
						$data_jamaah = $this->jamaah_candidate_model->get_jamaah_byRoomPacket($id_account, $kode_reg, $row->ID_ROOM_PACKET);
						if($data_jamaah->num_rows() < $total_kamar)
						{
							$sisa_bed = $total_kamar - $data_jamaah->num_rows();
							$kamar_options[$row->ID_ROOM_PACKET] = $row->JENIS_KAMAR." - sisa ".$sisa_bed." bed";
						}else{
							if($data['e_kamar'] == $row->ID_ROOM_PACKET)
							{
								$kamar_options[$row->ID_ROOM_PACKET] = $row->JENIS_KAMAR." - sisa 0 bed";
							}
						}
					}
				}
				
				$data['province_options'] = $province_options;
				$data['kota_options'] = $kota_options;
				$data['relasi_options'] = $relasi_options;
				$data['chlothes_options'] = $chlothes_options;
				$data['kamar_options'] = $kamar_options;
				$data['tipe_jamaah_options'] = $tipe_jamaah;
				
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
				
				
				
				$data['content'] = $this->load->view('biodata_edit', $data, true);
				$this->load->view('front_backup', $data);
			
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
				'TIPE_JAMAAH' => $this->input->post('tipe_jamaah'),
				'MAHRAM' => $mahram_s,
				'ID_ROOM_PACKET' => $this->input->post('kamar'),
				'VIA' => 'Dashboard',
				'TANGGAL_UPDATE' => date("Y-m-d H:i:s")
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
	
	function profile_jamaah($id_candidate, $kode_reg)
	{
		$this->load->model('jamaah_candidate_model');
		$this->load->model('packet_model');
		$this->load->model('group_departure_model');
		$this->load->model('relation_model');
		
		$data[''] = '';
		$data_jamaah = $this->jamaah_candidate_model->get_profile_view($id_candidate, $kode_reg);

		if($data_jamaah->result() != NULL)
		{
			foreach($data_jamaah->result() as $row)
			{

				$data_packet = $this->packet_model->get_packet_byAcc($row->ID_ACCOUNT, $row->KODE_REGISTRASI);
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
				$data['tgl_lahir'] = $this->konversi_tanggal($row->TANGGAL_LAHIR);
				$data['jenkel'] = $row->JENIS_KELAMIN;
				$data['ayah_kandung'] = $row->AYAH_KANDUNG;
				$data['alamat'] = $row->ALAMAT;
				$data['kota'] = $row->KOTA;
				$data['warga_negara'] = $row->WARGA_NEGARA;
				$data['tlp'] = $row->TELP;
				$data['hp'] = $row->MOBILE;
				$data['status'] = $row->STATUS_JAMAAH;
				$data['ukuran_baju'] = $row->UKURAN_BAJU;
				$data['foto'] = $row->FOTO;
				$data['id_candidate'] = $id_candidate;
				$data['kode_reg'] = $kode_reg;
				
				// pecah layanan khusus
				$pecah_khusus = explode(";", $row->LAYANAN_KHUSUS);
				$data['khusus_kursi'] = $pecah_khusus[0];
				$data['khusus_asisten'] = $pecah_khusus[1];
				
				// pecah perihal pribadi
				$pecah_pribadi = explode(";", $row->PERIHAL_PRIBADI);
				$data['perihal_darah'] = $pecah_pribadi[0];
				$data['perihal_tinggi'] = $pecah_pribadi[1];
				$data['perihal_smooking'] = $pecah_pribadi[2];
				$data['perihal_jantung'] = $pecah_pribadi[3];
				$data['perihal_asma'] = $pecah_pribadi[4];
				$data['perihal_mendengkur'] = $pecah_pribadi[5];
				
				// filter relasi
				$data_relation = $this->relation_model->get_relation($row->ID_RELATION);
				foreach($data_relation->result() as $rows)
				{
					$data['nama_relasi'] = $rows->JENIS_RELASI;
				}
			}
		}
		
		$this->load->view('profile_jamaah', $data);
	}
	
	function konversi_tanggal($tgl){
      $tanggal = substr($tgl,8,2);
      $bln    = substr($tgl,5,2);
	  $bulan = ""; $strHari = "";
      $tahun    = substr($tgl,0,4);

      switch ($bln){
        case 1:
          $bulan =  "Januari";
          break;
        case 2:
          $bulan =  "Februari";
          break;
        case 3:
          $bulan = "Maret";
          break;
        case 4:
          $bulan =  "April";
          break;
        case 5:
          $bulan =  "Mei";
          break;
        case 6:
          $bulan =  "Juni";
          break;
        case 7:
          $bulan =  "Juli";
          break;
        case 8:
          $bulan =  "Agustus";
          break;
        case 9:
          $bulan =  "September";
          break;
        case 10:
          $bulan =  "Oktober";
          break;
        case 11:
          $bulan =  "November";
          break;
        case 12:
          $bulan =  "Desember";
          break;
	   }
	   return $tanggal.' '.$bulan.' '.$tahun;
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
					

}