<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Check_availability extends CI_Controller {

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
		$this->load->model('group_departure_model');
		$this->load->model('program_class_model');
		$this->load->model('room_type_model');
		
		$group = $this->group_departure_model->get_all_group();
		$program = $this->program_class_model->get_all_program();
		$room = $this->room_type_model->get_all_roomType();

		$group_options['0'] = '-- Pilih Grup Keberangkatan --';
		foreach($group->result() as $row){
				if(strlen($row->KODE_GROUP) < 7)
				{
					$kode = $row->KODE_GROUP." - ".$this->konversi_tanggal2($row->TANGGAL_KEBERANGKATAN_JD);
				}else{
					$kode = $row->KODE_GROUP;
				}
				
				$group_options[$row->ID_GROUP] = $kode;
		}
		
		$program_options['0'] = '-- Pilih Kelas Program --';
		foreach($program->result() as $row){
				$program_options[$row->ID_PROGRAM] = $row->NAMA_PROGRAM;
		}
		
		$room_options = '';
		foreach($room->result() as $row){
				$input_room = "<input type='text' name='jml_kamar[]' id='jml_kamar' class='input_small_table'  value='".set_value('jml_kamar[]')."' title='Harap diisi dengan Angka'><input type='hidden' name='tipe_kamar[]' value='".$row->ID_ROOM_TYPE."'>";
				$room_options .= "<tr><td><strong><img src='".base_url()."images/front/poin.png'/>&nbsp;".$row->JENIS_KAMAR."</strong> untuk</td><td> ".$input_room." &nbsp;Orang</td></tr>";
		}
			
		$data['group_options'] = $group_options;
		$data['program_options'] = $program_options;
		$data['room_options'] = $room_options;
		$data['content'] = $this->load->view('form_check_availability',$data,true);
		$this->load->view('front',$data);
	}
	
	function do_check(){
		/*$data[] = null;
		$data['content'] = $this->load->view('result_page',$data,true);
		$this->load->view('front',$data);*/
		
		
		if ($this->cek_validasi() == FALSE){
			//$this->session->set_userdata('failed_form','Kegagalan Menyimpan Data, Kesalahan Pengisian Form!');
			$this->front();
		}
		else{
			
			$this->load->model('group_departure_model');
			$this->load->model('program_class_model');
			$this->load->model('packet_model');
			$this->load->model('room_model');
			$this->load->model('room_type_model');
			
			$group = $this->input->post('group');
            $kelas_program = $this->input->post('program');
            $jml_adult = $this->input->post('jml_adult');
            $with_bed = $this->input->post('with_bed')=='' ? 0:$this->input->post('with_bed');
            $no_bed = $this->input->post('no_bed')=='' ? 0:$this->input->post('no_bed');
			$infant = $this->input->post('infant')=='' ? 0:$this->input->post('infant');			
									
			$group_info = $this->group_departure_model->get_group($group);
			$kode_group = $group_info->row()->KODE_GROUP;
			$depart_jd = $group_info->row()->TANGGAL_KEBERANGKATAN_JD;
			$depart_mk = $group_info->row()->TANGGAL_KEBERANGKATAN_MK;
			$jatu_tempo = $group_info->row()->JATUH_TEMPO_PASPOR;
			$uang_muka = $group_info->row()->JATUH_TEMPO_UANG_MUKA;
			$pelunasan = $group_info->row()->JATUH_TEMPO_PELUNASAN;
			$kirim_berkas = $group_info->row()->JATUH_TEMPO_BERKAS;
			
			$program_info = $this->program_class_model->get_program($kelas_program);
			$nama_program = $program_info->row()->NAMA_PROGRAM;
			$maskapai = $program_info->row()->MASKAPAI;
			$hotel_mk = $program_info->row()->HOTEL_MAKKAH;
			$hotel_md = $program_info->row()->HOTEL_MADINAH;
			$transportasi = $program_info->row()->TRANSPORTASI;
			
			$total_candidate = $jml_adult + $with_bed + $no_bed + $infant;	
			
			// hitung total jamaah per paket per keberangkatan
			$data_packet = $this->packet_model->sum_jumlah_orang($kelas_program)->row();
			$total_org = $data_packet->JUMLAH_ADULT + $data_packet->CHILD_WITH_BED + $data_packet->CHILD_NO_BED + $data_packet->INFANT;
			
			// cari total pagu
			$data_program = $this->program_class_model->get_program_ByGroup($kelas_program, $group);
			if($data_program->result()!= NULL)
			{
				foreach($data_program->result() as $row)
				{
					$total_pagu = $row->PAGU;
				}
			}else{
				$total_pagu = 0;
			}
			
			$hitung_pagu = $total_pagu - $total_org;
			
			if($hitung_pagu > 0){
				$sisa_pagu = " - sisa ".$hitung_pagu." Seat <font color='3B619F'>( TERSEDIA )</font>";
			}else{
				$sisa_pagu = " - sisa 0 Seat <font color='A01040'>( TIDAK TERSEDIA )</font>";
			}
			
			
			$kamar = $this->input->post('kamar');
			$jml_kamar = $this->input->post('jml_kamar');
			$id_tipe_kamar = $this->input->post('tipe_kamar');
			$total_candidate -= ($no_bed + $infant);
			$flag_room = TRUE;
			$room_capacity = 0;
			$tmp_candidate = $total_candidate;
			$available_beds = $this->room_model->count_available_beds($group)->row()->JML_BEDS;
			$data['waiting'] = FALSE;
			$konfig_kamar = '';
			$total_candidate = '';
			$value_waiting_list = 0;
			
			for($i=0; $i<count($jml_kamar); $i++)
			{
				$room_type = $this->room_type_model->get_roomType($id_tipe_kamar[$i]);
				if($jml_kamar[$i] == 0) { $jml_kamar[$i] = 0; }
				$konfig_kamar .= "<tr><td><img src='".base_url()."images/front/poin.png'/>&nbsp;<strong>".$room_type->row()->JENIS_KAMAR."</strong></td><td>:</td><td><strong>".$jml_kamar[$i]."</strong> Orang</td></tr>";
				$total_candidate += $jml_kamar[$i];
			}
			
			
			// filter waiting list
			if($total_pagu == 0)
			{
				$data['msg_box1'] = "<img src='".base_url()."images/front/warning.png'/>&nbsp;Pagu Penerbangan ".$maskapai." <font color='A01040' class='bold_red'>( TIDAK TERSEDIA )</font>.<br>";
				$value_waiting_list = 1;
			}
			
			if($total_candidate > $hitung_pagu)
			{
				$data['msg_box2'] = "<img src='".base_url()."images/front/warning.png'/>&nbsp;Jumlah Calon Jamaah <font color='A01040' class='bold_red'>Lebih Besar</font> dari Pagu Pesawat.<br>";
				$value_waiting_list = 1;
			}
			
			$sisa_kamar_ses = $this->check_sisa_kamar();
			if($sisa_kamar_ses != NULL)
			{
				$data['msg_box3'] = $this->check_sisa_kamar();
				$value_waiting_list = 1;
			}
			
			if($value_waiting_list == 0)
			{
				$data['status_waiting'] = "<img src='".base_url()."images/front/tersedia.png'/>&nbsp;<font color='3B619F' class='bold'>TERSEDIA</font> , Silahkan Melakukan Registrasi dengan mengklik tombol <font color='#A01040' class='bold_red'>Lanjut</font> dibawah ini.";
			}else{
				$data['status_waiting'] = "";
			}
			
			$data['waiting_list'] = $value_waiting_list;
			
			$data['info_berangkat'] = $this->konversi_tanggal($depart_mk);
			$data['jatu_tempo'] = $this->konversi_tanggal($jatu_tempo);
			$data['uang_muka'] = $this->konversi_tanggal($uang_muka);
			$data['pelunasan'] = $this->konversi_tanggal($pelunasan);
			$data['kirim_berkas'] = $this->konversi_tanggal($kirim_berkas);
			$data['maskapai'] = $maskapai.$sisa_pagu;
			$data['hotel_mk'] = $hotel_mk;
			$data['hotel_md'] = $hotel_md;
			$data['transportasi'] = $transportasi;
			
			$data['info_jumlah_kamar'] = $this->hitung_jumlah_kamar($group, $kelas_program);
			
			$data['konfig_kamar'] = $konfig_kamar;
			$data['jml_adult'] = $jml_adult;
			$data['with_bed'] = $with_bed;
			$data['no_bed'] = $no_bed;
			$data['infant'] = $infant;
                        $data['group'] = $group;
                        $data['program'] = $kelas_program;
			$data['kode_group'] = $kode_group;
			$data['nama_program'] = $nama_program;
			
			$data[] = null;
			$data['content'] = $this->load->view('result_page',$data,true);
			$this->load->view('front',$data);
		}
	}
	
	function cek_validasi() {
		$adult = $this->input->post('jml_adult');
		$with_bed = $this->input->post('with_bed')=='' ? 0:$this->input->post('with_bed');
		$no_bed = $this->input->post('no_bed')=='' ? 0:$this->input->post('no_bed');
		$infant = $this->input->post('infant')=='' ? 0:$this->input->post('infant');
		$total = $adult + $with_bed + $no_bed + $infant;
		
		$total_konfigurasi = 0;
		foreach($this->input->post('jml_kamar') as $key => $values)
		{
			$total_konfigurasi += $values;
		}
			
		//setting rules
		$config = array(
				array('field'=>'group','label'=>'Group', 'rules'=>'callback_cek_dropdown|callback_check_departure'),
				array('field'=>'program','label'=>'Kelas Program', 'rules'=>'callback_cek_dropdown'),
				array('field'=>'jml_adult','label'=>'Jumlah Dewasa', 'rules'=>"required|integer|callback_check_jml[$total]"),
				array('field'=>'with_bed','label'=>'Anak Dengan Ranjang', 'rules'=>"integer"),
				array('field'=>'no_bed','label'=>'Anak Tanpa Ranjang', 'rules'=>"integer"),
				array('field'=>'infant','label'=>'Bayi', 'rules'=>"integer|callback_check_jml[$adult]"),
				array('field'=>'jml_kamar[]','label'=>'Konfigurasi Kamar', 'rules'=>"callback_check_jml2[$total_konfigurasi]|callback_check_komparasi_kamar[$total]"),
				//array('field'=>'jml_kamar','label'=>'Jumlah', 'rules'=>'callback_cek_dropdown'),
				
				// validasi konfigurasi kamar : |callback_check_sisa_seat[$total_konfigurasi]|callback_check_sisa_kamar
		);

		$this->form_validation->set_rules($config);
		$this->form_validation->set_message('required', '%s wajib diisi !');
		$this->form_validation->set_message('integer', '%s harus diisi angka !');
		//$this->form_validation->set_error_delimiters('<li class="error">', '</li>');

		return $this->form_validation->run();
    }

    //cek pilihan sdh bener ap blm
    function cek_dropdown($value){
		if($value==0){
				$this->form_validation->set_message('cek_dropdown', 'Pilih salah satu %s !');
				return FALSE;
		}else
				return TRUE;
    }
	
	function check_departure($id_group){
		$this->load->model('group_departure_model');
		$val = $this->group_departure_model->get_group($id_group);
		
		if ($val->num_rows() > 0){
			$exp_date = strtotime($val->row()->JATUH_TEMPO_UANG_MUKA);
			$today = strtotime(date("Y-m-d"));
			
			if ($exp_date >= $today)
				return TRUE;
			else{
				$this->form_validation->set_message('check_departure', 'Maaf, pendaftaran untuk grup ini sudah ditutup');
				return FALSE;
			}
		}
	}
	
	//cek jumlah
    function check_jml($value, $max){
		if ($max > 20) {
			$this->form_validation->set_message('check_jml', "Jumlah maksimum calon jamaah 20 orang tiap unit");
			return FALSE;
		}else{
			if($value > $max){
				$this->form_validation->set_message('check_jml', "Kami tidak menganjurkan jumlah %s lebih besar dari orang dewasa !");
				return FALSE;
			}else
				return TRUE;
		}
    }
	
	//cek jumlah jika kosong
    function check_jml2($value, $max){
		if ($max < 0) {
			$this->form_validation->set_message('check_jml2', "%s wajib diisi !");
			return FALSE;
		}else{
			return TRUE;
		}
    }
	
	function check_komparasi_kamar($value, $total)
	{
		$total_konfigurasi = 0;
		foreach($this->input->post('jml_kamar') as $key => $values)
		{
			$total_konfigurasi += $values;
		}
		
		if($total_konfigurasi != $total)
		{
			$this->form_validation->set_message('check_komparasi_kamar', "Jumlah Orang Harus Sama dengan Jumlah Konfigurasi Kamar !");
				return FALSE;
		}else
			return TRUE;
	}
	
	// cek ketersediaan seat pesawat
	function check_sisa_seat($value, $total)
	{
		$this->load->model('packet_model');
		$this->load->model('program_class_model');
		
		$id_group = $this->input->post('group');
		$id_program	 = $this->input->post('program');
		
		// hitung total jamaah per paket per keberangkatan
		$data_packet = $this->packet_model->sum_jumlah_orang($id_program)->row();
		$total_org = $data_packet->JUMLAH_ADULT + $data_packet->CHILD_WITH_BED + $data_packet->CHILD_NO_BED + $data_packet->INFANT;
		
		// cari total pagu
		$data_program = $this->program_class_model->get_program_ByGroup($id_program, $id_group);
		if($data_program->result()!= NULL)
		{
			foreach($data_program->result() as $row)
			{
				$total_pagu = $row->PAGU;
			}
		}else{
			$total_pagu = 0;
		}
		
		$hitung_pagu = $total_pagu - $total_org;
		
		if($hitung_pagu != '0')
		{
			if($hitung_pagu > $total){
				return TRUE;
			}else{
				$this->form_validation->set_message('check_sisa_seat', "Jumlah Orang melebihi Pagu Penerbangan!");
				return FALSE;
			}
		}else{
			$this->form_validation->set_message('check_sisa_seat', "");
			return FALSE;
		}
	}
	
	// cek ketersediaan kamar
	function check_sisa_kamar()
	{
		$this->load->model('room_type_model');
		$this->load->model('room_availability_model');
		$this->load->model('room_packet_model');
		
		$jml_kamar = $this->input->post('jml_kamar');
		$id_tipe_kamar = $this->input->post('tipe_kamar');
		$id_group = $this->input->post('group');
		$id_program	 = $this->input->post('program');
		
		$konfig_kamar = '';
		$error = '';

		for($i=0; $i<count($jml_kamar); $i++)
		  {
			  // hitung jumlah konfigurasi per kamar
			  $room_type = $this->room_type_model->get_roomType($id_tipe_kamar[$i]);
			  if($jml_kamar[$i] == 0) { $jml_kamar[$i] = 0; }
			  $konfig_kamar .= "<strong>".$room_type->row()->JENIS_KAMAR."</strong> : <strong>".$jml_kamar[$i]."</strong> Orang<br>";
			  
			  // jumlah kamar tersedia
			  $kmr_tersedia = $this->room_availability_model->get_price_room($room_type->row()->ID_ROOM_TYPE, $id_program, $id_group);
			  foreach($kmr_tersedia->result() as $rows)
			  {
				  $kmr_total = $rows->JUMLAH_KAMAR * $room_type->row()->CAPACITY;
			  }
			  
			  
			  $kmr_aktif = $this->room_packet_model->periksa_kamar_aktif($room_type->row()->ID_ROOM_TYPE, $id_group, $id_program);
			  if($kmr_aktif->result() != NULL)
			  {
				  foreach($kmr_aktif->result() as $rows)
				  {
					  $kmr_terisi = $rows->JUMLAH;
				  }
				  
			  }else{
				  $kmr_terisi = '0';
			  }
			  
			  $tot = $kmr_total - $kmr_terisi;
			  
			  if($jml_kamar[$i] > $tot)
			  {
				 $error .= "<img src='".base_url()."images/front/warning.png'/>&nbsp;Jumlah Konfigurasi Kamar <strong>".$room_type->row()->JENIS_KAMAR."</strong> <font color='A01040' class='bold_red'>Lebih Besar</font> dari Sisa Kamar.<br>";
				 $valid = FALSE;
			  }else{
				 $valid = TRUE;
			  }
		  }
		  
		 if($valid)
		 {
			 return $error;
			 
		 }else{
			 
			 return NULL;
		 }
		
	}
	
	
	function getKamar(){
		$this->load->model('room_type_model');
                
		$options = '';
		$room = $this->room_type_model->get_all_roomType();
		foreach ($room->result() as $angkutan){
			$options.= '<option value="'.$angkutan->ID_ROOM_TYPE.'" class="dynamic4">'.$angkutan->JENIS_KAMAR.'</option>';
		}
		echo $options;
	}
	
	function getGroup()
	{
		$this->load->model('group_departure_model');
		
		if ($_POST['id_group']!='' && $_POST['id_group']!= 0) { 
			$parent = $_POST['id_group'];
			$parent2 = $_POST['id_program'];
			
			$info_jumlah_kamar = $this->hitung_jumlah_kamar($parent, $parent2);
			
			$data_group	= $this->group_departure_model->get_group_berdasarkan_id($parent);			
			foreach ($data_group->result() as $row){
				
				$kode = $row->KODE_GROUP;
				$ket = $row->KETERANGAN;
				$jd = $this->konversi_tanggal($row->TANGGAL_KEBERANGKATAN_JD);
				$mk = $this->konversi_tanggal($row->TANGGAL_KEBERANGKATAN_MK);
				$paspor = $this->konversi_tanggal($row->JATUH_TEMPO_PASPOR);
				$lunas = $this->konversi_tanggal($row->JATUH_TEMPO_PELUNASAN);
				$dp = $this->konversi_tanggal($row->JATUH_TEMPO_UANG_MUKA);
				$berkas = $this->konversi_tanggal($row->JATUH_TEMPO_BERKAS );
				
				$data = $jd."#".$mk."#".$paspor."#".$lunas."#".$dp."#".$berkas."#".$info_jumlah_kamar;
			}
			echo $data;
		
		} else {
			
			echo " # # # # # # ";
		}

	}
	
	function getProgram()
	{
		$this->load->model('program_class_model');
		$this->load->model('packet_model');
		$this->load->model('program_class_model');
		
		if ($_POST['id_program']!='' && $_POST['id_program']!= 0) { 
			$parent = $_POST['id_program'];
			$parent2 = $_POST['id_group'];
			
			$info_jumlah_kamar = $this->hitung_jumlah_kamar($parent2, $parent);
			
			// hitung total jamaah per paket per keberangkatan
			$data_packet = $this->packet_model->sum_jumlah_orang($parent)->row();
			$total_org = $data_packet->JUMLAH_ADULT + $data_packet->CHILD_WITH_BED + $data_packet->CHILD_NO_BED + $data_packet->INFANT;
			
			// cari total pagu
			$data_program = $this->program_class_model->get_program_ByGroup($parent, $parent2);
			if($data_program->result()!= NULL)
			{
				foreach($data_program->result() as $row)
				{
					$total_pagu = $row->PAGU;
				}
			}else{
				$total_pagu = 0;
			}
			
			$hitung_pagu = $total_pagu - $total_org;
			
			if($hitung_pagu > 0){
				$sisa_pagu = " - sisa ".$hitung_pagu." Seat <font color='3B619F'>( TERSEDIA )</font>";
			}else{
				$sisa_pagu = " - sisa 0 Seat <font color='A01040'>( TIDAK TERSEDIA )</font>";
			}
			
			
			$data_group	= $this->program_class_model->get_program($parent);			
			foreach ($data_group->result() as $row){
				
				$maskapai = $row->MASKAPAI;
				$hotel_mk = $row->HOTEL_MAKKAH;
				$hotel_jd = $row->HOTEL_MADINAH;
				$transportasi = $row->TRANSPORTASI;
				
				$data = $maskapai.$sisa_pagu."#".$hotel_mk."#".$hotel_jd."#".$transportasi."#".$info_jumlah_kamar;
			}
			echo $data;
		
		} else {
			
			echo " # # # # ";
		}

	}
	
	function hitung_jumlah_kamar($id_group, $id_program, $tipe = NULL)
	{
		$this->load->model('room_availability_model');
		$this->load->model('room_type_model');
		$this->load->model('room_packet_model');
		
		$tipe_kamar = $this->room_type_model->get_all_roomType_aktif();
		$kmr_total = '';
		$kmr_terisi = '';
		$views = '';
		$no = 1;
		foreach($tipe_kamar->result() as $row)
		{
			$nama_tipe = $row->JENIS_KAMAR;
			$kapasitas = $row->CAPACITY;
			
			$kmr_tersedia = $this->room_availability_model->get_price_room($row->ID_ROOM_TYPE, $id_program, $id_group);
			foreach($kmr_tersedia->result() as $rows)
			{
				$kmr_total = $rows->JUMLAH_KAMAR * $kapasitas;
			}
			
			
			$kmr_aktif = $this->room_packet_model->periksa_kamar_aktif($row->ID_ROOM_TYPE, $id_group, $id_program);
			if($kmr_aktif->result() != NULL)
			{
				foreach($kmr_aktif->result() as $rows)
				{
					$kmr_terisi = $rows->JUMLAH;
				}
				
			}else{
				$kmr_terisi = '0';
			}
			
			$tot = $kmr_total - $kmr_terisi;
			
			if($tot > 0)
			{
				$status_kamar = "<font color='3B619F'><strong>( TERSEDIA )</strong></font>";
			}else{
				$status_kamar = "<font color='A01040'><strong>( TIDAK TERSEDIA )</strong></font>";
				$tot = 0;
			}
			
			if($no > 1)
			{
				$spasi = "&nbsp;&nbsp;";
			}else{
				$spasi = NULL;
			}
			
			$views .= "<li><strong>".$nama_tipe."</strong> tersedia untuk ".$tot." Orang ".$status_kamar."</li>";
			$no +=1;
		}
		
		if($tipe == NULL)
		{
			return $views;
		}else{
			return $tot;
		}
	}
			
			
	
	function konversi_tanggal($tgl){
      $tanggal = substr($tgl,8,2);
      $bln    = substr($tgl,5,2);
	  $bulan = ""; $strHari = "";
      $tahun    = substr($tgl,0,4);
              $hari = date("N", mktime(0, 0, 0, $bln, $tanggal, $tahun));

              switch ($hari){
                  case 1:
                      $strHari = "Senin";
                      break;
                  case 2:
                      $strHari = "Selasa";
                      break;
                  case 3:
                      $strHari = "Rabu";
                      break;
                  case 4:
                      $strHari = "Kamis";
                      break;
                  case 5:
                      $strHari = "Jumat";
                      break;
                  case 6:
                      $strHari = "Sabtu";
                      break;
                  case 7:
                      $strHari = "Minggu";
                      break;
              }

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
	   return $strHari.", ".$tanggal.' '.$bulan.' '.$tahun;
    }
	
	function konversi_tanggal2($tgl){
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
}

/* End of file Check_availability.php */
/* Location: ./application/controllers/Check_availability.php */