<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paspor extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		if($this->session->userdata('email') == NULL)
			redirect(site_url()."/login");

                $this->cekOrder();
	}
	function index()
	{
		$this->front();
	}
	
	function front()	
	{
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
		
		$colModel['no'] = array('No',40,TRUE,'center',0);
		$colModel['edit'] = array('Edit',40,FALSE,'center',0);
		$colModel['NAMA_LENGKAP'] = array('Nama Lengkap',150,TRUE,'center',1);
		$colModel['REQUESTED_NAMA'] = array('Nama 3 Suku Kata',150,TRUE,'center',1);
		$colModel['GENDER'] = array('Jenis Kelamin',100,FALSE,'center',0);
		$colModel['WARGA_NEGARA'] = array('Warga Negara',130,TRUE,'center',1);
		$colModel['NO_PASPOR'] = array('No Paspor',120,FALSE,'center',1);
		$colModel['TANGGAL_DIKELUARKAN'] = array('Tgl. Dikerluarkan',100,TRUE,'center',0);
		$colModel['TANGGAL_HABIS'] = array('Tgl. Habis Berlaku',100,TRUE,'center',0);
		$colModel['KANTOR_PEMBUATAN'] = array('Kantor',100,TRUE,'center',1);
		
		$gridParams = array(
		'width' => 'auto',
		'height' => 300,
		'rp' => 10,
		'rpOptions' => '[5,10,20,25,30,50,'.$total_data.']',
		'pagestat' => 'Menampilkan: {from} sampai {to} dari {total} hasil.',
		'blockOpacity' => 0.5,
		'title' => 'Dokumen Calon Jamaah',
		'showTableToggleBtn' => false
		);
		
		$buttons[] = array('separator');
		
		$grid_js = build_grid_js('flex1',site_url("/paspor/grid_calon_jamaah/"),$colModel,'no','asc',$gridParams);
		$data['js_grid'] = $grid_js;
		
		$data['added_js'] = 
		"<script type='text/javascript'>
		function spt_js(com,grid){
			
		} 
		</script>
		";

                
		$data['content'] = $this->load->view('paspor_list',$data,true);
		$this->load->view('front_backup',$data);		
	}
	
	
	function grid_calon_jamaah() {
		
		//call library or helper
		$this->load->library('flexigrid');	
		$this->load->helper('flexigrid');
		$this->load->library('form_validation');		
		
		//call model here	
		$this->load->model('jamaah_candidate_model');
		
		$valid_fields = array('NAMA_LENGKAP','REQUESTED_NAMA','WARGA_NEGARA','NO_PASPOR','KANTOR_PEMBUATAN');
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
			
			if($row->TANGGAL_DIKELUARKAN != NULL) { $tgl_keluar = date("d M Y", strtotime($row->TANGGAL_DIKELUARKAN)); }
			  else{ $tgl_keluar = ""; }
			 
			if($row->TANGGAL_HABIS != NULL) { $tgl_berakhir = date("d M Y", strtotime($row->TANGGAL_HABIS)); }
			  else{ $tgl_berakhir = ""; }
			
			if($row->REQUESTED_NAMA == '0') { $req_nama = ""; }
			  else { $req_nama = $row->REQUESTED_NAMA; }
			
			$record_items[] = array(
			
				$row->ID_CANDIDATE,
				$no = $no+1,
				'<a href=\''.site_url().'/paspor/edit/'.$row->ID_CANDIDATE.'/'.$row->ID_ACCOUNT.'/'.$gos.'\'><img border=\'0\' src=\''.base_url().'images/flexigrid/book.png\'></a> ',
				$row->NAMA_LENGKAP,	
				$req_nama,
				$gender,
				$row->WARGA_NEGARA,
				$row->NO_PASPOR,
				$tgl_keluar,
				$tgl_berakhir,
				$row->KANTOR_PEMBUATAN
			);
		}
		
		if(isset($record_items))
			$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
		else
			$this->output->set_output('{"page":"1","total":"0","rows":[]}');			
	}
	
	
	// HALAMAN EDIT DOKUMEN CALON JAMAAH
	function edit($id_candidate = NULL, $id_account = NULL, $tipe)
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
				$data['e_tgl_lahir'] = $row->TANGGAL_LAHIR;
				
				// DATA PASPOR
				$data['e_no_paspor'] = $row->NO_PASPOR;
				$data['e_nama_paspor'] = $row->NAMA_PASPOR;
				$data['e_tgl_keluar'] = $row->TANGGAL_DIKELUARKAN;
				$data['e_tgl_habis'] = $row->TANGGAL_HABIS;
				$data['e_kantor'] = $row->KANTOR_PEMBUATAN;
				$data['e_scan_paspor'] = $row->SCAN_PASPOR;
				
				// FILTER JENIS KELAMIN
				if($row->GENDER == 1) $data['e_gender'] = "Laki-Laki";
				elseif($row->GENDER == 2) $data['e_gender'] = "Perempuan";
				
				// UBAH TANGGAL LAHIR
				$data['tgl_lahir'] = $this->konversi_tanggal($data['e_tgl_lahir']);
				
				// PECAH TANGGAL PASPOR
				if($data['e_tgl_keluar'] != NULL)
				{
					$k_pecah_tgl = explode("-", $data['e_tgl_keluar']);
					$data['e_k_thn'] = $k_pecah_tgl[0];
					$data['e_k_bln'] = $k_pecah_tgl[1];
					$data['e_k_tgl'] = $k_pecah_tgl[2];
				}
				
				if($data['e_tgl_habis'] != NULL)
				{
					$b_pecah_tgl = explode("-", $data['e_tgl_habis']);
					$data['e_b_thn'] = $b_pecah_tgl[0];
					$data['e_b_bln'] = $b_pecah_tgl[1];
					$data['e_b_tgl'] = $b_pecah_tgl[2];
				}
						
				$data['tipe'] = $tipe;
				
				$data['notifikasi'] = null;
				if($this->session->userdata('sukses') == 'true'){
					$data['notifikasi'] = '<div id="message-green">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="green-left">Dokumen Paspor Berhasil diubah.</td>
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
				
				$data['content'] = $this->load->view('paspor_edit', $data, true);
				$this->load->view('front_backup', $data);
			
			}
		
		} else {
			
			redirect(site_url()."/paspor/");
		}
		
	}
	
	function cek_validasi() {
		$this->load->library('form_validation');
		
		$tgl_b = $this->input->post('b_tgl_lahir');
		$bln_b = $this->input->post('b_bln_lahir');
		$thn_b = $this->input->post('b_thn_lahir');
		$tgl_berakhir = $thn_b."-".$bln_b."-".$tgl_b;
		
		//setting rules
		$config = array(
				array('field'=>'nama_paspor','label'=>'Nama Paspor', 'rules'=>'required'),
				array('field'=>'no_paspor','label'=>'Nomor Paspor', 'rules'=>'required'),
				array('field'=>'k_tgl_lahir','label'=>'Tgl. Dikeluarkan', 'rules'=>'callback_cek_dropdown'),
				array('field'=>'k_bln_lahir','label'=>'Tgl. Dikeluarkan', 'rules'=>'callback_cek_dropdown'),
				array('field'=>'k_thn_lahir','label'=>'Tgl. Dikeluarkan', 'rules'=>'callback_cek_dropdown'),
				array('field'=>'b_tgl_lahir','label'=>'Tgl. Berakhir', 'rules'=>'callback_cek_dropdown'),
				array('field'=>'b_bln_lahir','label'=>'Tgl. Berakhir', 'rules'=>'callback_cek_dropdown'),
				array('field'=>'b_thn_lahir','label'=>'Tgl. Berakhir', 'rules'=>'callback_cek_dropdown|callback_cek_masa_berlaku['.$tgl_berakhir.']'),
				array('field'=>'kantor','label'=>'Kantor', 'rules'=>'required'),
		//		array('field'=>'foto','label'=>'Scan Paspor', 'rules'=>'required'),
			);
		
		
		$this->form_validation->set_rules($config);
		$this->form_validation->set_message('required', 'Kolom <strong>%s</strong> harus diisi !');
		$this->form_validation->set_message('valid_email', 'Penulisan kolom <strong>%s</strong> tidak benar!');
		$this->form_validation->set_message('numeric', '<strong>Kolom %s</strong> harus berupa angka !');
		//$this->form_validation->set_error_delimiters('<li class="error">', '</li>');

		return $this->form_validation->run();
    }
	
	function cek_dropdown($value){
		$this->load->library('form_validation');
		if($value==0){
				$this->form_validation->set_message('cek_dropdown', 'Pilih salah satu <strong>%s</strong> !');
				return FALSE;
		}else
				return TRUE;
    }
	
	function cek_masa_berlaku($value, $tgl_berakhir)
	{
		$this->load->model('packet_model');
		
		$id_user = $this->session->userdata("id_account");
		$kode_reg = $this->session->userdata("kode_registrasi");
		
		$data_packet = $this->packet_model->get_packet_byAcc($id_user, $kode_reg);
		$tgl_berangkat = $data_packet->row()->TANGGAL_KEBERANGKATAN_MK;
		$pecah_tgl = explode("-", $tgl_berangkat);
		
		$pengurangan_bulan = date("Y-m-d", mktime(0,0,0,$pecah_tgl[1]-6,$pecah_tgl[2],$pecah_tgl[0]));
		
		$total_tgl_berangkat = strtotime($pengurangan_bulan);
		$total_tgl_berakhir_paspor = strtotime($tgl_berakhir);
		
		if($total_tgl_berakhir_paspor < $total_tgl_berangkat)
		{
			$this->form_validation->set_message('cek_masa_berlaku', 'Masa berlaku paspor kurang dari 6 bulan !');
				return FALSE;
		}else{
			return TRUE;
		}
		
	}
	
	function do_edit(){
		
		$this->load->library('form_validation');
		$this->load->model('jamaah_candidate_model');
		$this->load->model('log_model');

		$log = "Mengubah dokumen paspor Calon Jamaah";
		
		$id_user = $this->session->userdata("id_account");
		$kode_reg = $this->session->userdata("kode_registrasi");
		
		// ID CANDIDATE DAN ID ACCOUNT
		$id_candidate = $this->input->post('id_candidate');
		$id_account = $this->input->post('id_account');
		$tipe = $this->input->post('id_tipe');
		
		if ($this->cek_validasi() == FALSE){
			$this->edit($id_candidate, $id_account, $tipe);
		}
		else{

			// tanggal dikerluarkan 
			$k_tgl = $this->input->post('k_tgl_lahir');
			$k_bln = $this->input->post('k_bln_lahir');
			$k_thn = $this->input->post('k_thn_lahir');
			$k_dates = $k_thn."-".$k_bln."-".$k_tgl;
			
			// tanggal berakhir
			$b_tgl = $this->input->post('b_tgl_lahir');
			$b_bln = $this->input->post('b_bln_lahir');
			$b_thn = $this->input->post('b_thn_lahir');
			$b_dates = $b_thn."-".$b_bln."-".$b_tgl;
			
			// cek foto
			$cek_foto = $_FILES['foto']['name'];
			if($cek_foto != "")
			{
				// Upload Foto
				$config['upload_path'] = './images/upload/paspor/';
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
			
			
			// update table
			$data = array(
				'NAMA_PASPOR' => $this->input->post('nama_paspor'),
				'NO_PASPOR' => $this->input->post('no_paspor'),
				'TANGGAL_DIKELUARKAN' => $k_dates,
				'TANGGAL_HABIS' => $b_dates,
				'KANTOR_PEMBUATAN' => $this->input->post('kantor'),
				'TANGGAL_UPDATE' => date("Y-m-d H:i:s")
				);
			
			if($valid_file)
			{
				if($valid)
				{
					$foto = array('SCAN_PASPOR' => $data_file['file_name']);
					$this->jamaah_candidate_model->update_jamaah($foto, $id_candidate);
					
					$file_gambar = $data_file['file_path'].$this->input->post('paspor_edit');
					if(is_file($file_gambar))
					{
						unlink($file_gambar);
					}
				}
			
				$tipe = 1;
				$this->session->set_userdata('sukses','true');
				$this->log_model->log($id_user, $kode_reg, NULL, $log);
				$update = $this->jamaah_candidate_model->update_jamaah($data, $id_candidate);
				
				redirect('/paspor/edit/'.$id_candidate.'/'.$id_account.'/'.$tipe.'/');
			}else{
				$this->edit($id_candidate, $id_account, $tipe);
			}
		}
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

?>