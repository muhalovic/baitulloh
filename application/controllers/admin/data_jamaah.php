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
		$this->load->model('program_class_model');
		
		$group 			= $this->group_departure_model->get_all_group();
		$program 		= $this->program_class_model->get_all_program();
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
		
		$grid_js = build_grid_js('flex1',site_url("/admin/data_jamaah/grid_calon_jamaah/"),$colModel,'no','asc',$gridParams,null);
		$data['js_grid'] = $grid_js;
		
		$data['added_js'] = "";
		
		$group_options['0'] = '-- Pilih Group --';
		foreach($group->result() as $row){
			$group_options[$row->ID_GROUP] = $row->KODE_GROUP;
		}

		$program_options['0'] = '-- Pilih Program --';
		foreach($program->result() as $row){
						$program_options[$row->ID_PROGRAM] = $row->NAMA_PROGRAM;
		}

		$data['group_options'] = $group_options;
		$data['program_options'] = $program_options;
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
			
			$url_nama_calon = '<a style="cursor:pointer; text-decoration:underline; color:#000;" onClick="window.open(\''.site_url().'/admin/data_jamaah/profile_jamaah/'.$row->ID_CANDIDATE.'/'.$row->KODE_REGISTRASI.'\',\'profil\',\'width=500,height=500,left=400,top=100,screenX=400,screenY=100\')">'.$row->NAMA_LENGKAP.'</a>';
			
			$record_items[] = array(
				$row->ID_CANDIDATE,
				$no = $no+1,
				$row->KODE_REGISTRASI,
				$row->NAMA_USER,	
				$kode_group,
				$url_nama_calon,
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
		
		$this->load->view('admin/profile_jamaah', $data);
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
}//end class

/* End of file /admin/data_jamaah.php */
/* Location: ./application/controllers/admin/data_jamaah.php */