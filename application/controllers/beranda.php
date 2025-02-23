<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Beranda extends CI_Controller {

	function __construct()
	{
		parent::__construct();
        $this->cekSession();        
	}

	function index()
	{
		$this->front();
	}
	
	function front()
	{
		$this->load->model('packet_model');

		$id_user = $this->session->userdata('id_account');
		$kode_reg = $this->session->userdata('kode_registrasi');

		$packet = $this->packet_model->get_packet_byAcc($id_user, $kode_reg);

		if ($packet->row()->STATUS_PESANAN == 3){
			$this->load->model('room_packet_model');
			
			foreach ($packet->result() as $row){
				$id_group = $row->ID_GROUP;
				$data['group'] = $row->KODE_GROUP;
				$data['keterangan_group'] = $row->KETERANGAN;
				$data['program'] = $row->NAMA_PROGRAM;
				$data['adult'] = $row->JUMLAH_ADULT;
				$data['with_bed'] = $row->CHILD_WITH_BED;
				$data['no_bed'] = $row->CHILD_NO_BED;
				$data['infant'] = $row->INFANT;
				$data['tgl_pesan'] = $row->TANGGAL_PESAN;
				$id_packet = $row->ID_PACKET;
			}

			// get data room
			$room = $this->room_packet_model->get_room_packet_byIDpack($id_packet);
			$data['room'] = $room->result();
			$data['is_order'] = TRUE;

			//get group info
			$this->load->model('group_departure_model');
			$data_group	= $this->group_departure_model->get_group_berdasarkan_id($id_group);
			foreach ($data_group->result() as $row){
				$data['jd'] = $this->konversi_tanggal($row->TANGGAL_KEBERANGKATAN_JD);
				$data['mk'] = $this->konversi_tanggal($row->TANGGAL_KEBERANGKATAN_MK);
				$data['paspor'] = $this->konversi_tanggal($row->JATUH_TEMPO_PASPOR);
				$data['lunas'] = $this->konversi_tanggal($row->JATUH_TEMPO_PELUNASAN);
				$data['dp'] = $this->konversi_tanggal($row->JATUH_TEMPO_UANG_MUKA);
				$data['berkas']  = $this->konversi_tanggal($row->JATUH_TEMPO_BERKAS );
			}

			$data['content'] = $this->load->view('home_ordered',$data,true);
		
		} elseif ($packet->row()->STATUS_PESANAN == 1) {
					
			$this->load->model('group_departure_model');
			$this->load->model('program_class_model');
			$this->load->model('room_type_model');
			$this->load->model('room_packet_model');
			
			$this->load->library('form_validation');
			
			// LOAD VALUE
			$data['e_packet'] = $packet->row()->ID_PACKET;
			$data['e_id_group'] = $packet->row()->ID_GROUP;
			$data['e_id_program'] = $packet->row()->ID_PROGRAM;
			$data['e_adult'] = $packet->row()->JUMLAH_ADULT;
			$data['e_cwb'] = $packet->row()->CHILD_WITH_BED == 0 ? '' : $packet->row()->CHILD_WITH_BED;
			$data['e_cnb'] = $packet->row()->CHILD_NO_BED == 0 ? '' : $packet->row()->CHILD_NO_BED;
			$data['e_infant'] = $packet->row()->INFANT == 0 ? '' : $packet->row()->INFANT;
			
			
			
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
			foreach($room->result() as $row)
			{
				$room_packet = $this->room_packet_model->get_data_byRoomTypePacket($row->ID_ROOM_TYPE, $packet->row()->ID_PACKET);
				if($room_packet->result() != NULL)
				{
					$value_jumlah_candidate = $room_packet->row()->JUMLAH;
				}else{
					$value_jumlah_candidate = NULL;
				}
				
				$input_room = "<input type='text' name='jml_kamar[]' id='jml_kamar' class='input_small_table'  value='".$value_jumlah_candidate."' title='Harap diisi dengan Angka'><input type='hidden' name='tipe_kamar[]' value='".$row->ID_ROOM_TYPE."'>";
				
				$room_options .= "<tr><td><strong><img src='".base_url()."images/front/poin.png'/>&nbsp;".$row->JENIS_KAMAR."</strong> untuk</td><td> ".$input_room." &nbsp;Orang</td></tr>";
			}
				
			$data['group_options'] = $group_options;
			$data['program_options'] = $program_options;
			$data['room_options'] = $room_options;
			
			$data['content'] = $this->load->view('home',$data,true);
		}
		
		$this->load->view('front_backup',$data);
	}
	
	function do_check(){
		$this->load->library('form_validation');
		
		if ($this->cek_validasi() == FALSE){
			
			$this->front();
		}
		else{
			
			$this->load->model('group_departure_model');
			$this->load->model('program_class_model');
			$this->load->model('packet_model');
			$this->load->model('room_model');
			$this->load->model('room_type_model');
			$this->load->model('log_model');
			
            $id_packet = $this->input->post('packet');
			$group = $this->input->post('group');
            $kelas_program = $this->input->post('program');
            $jml_adult = $this->input->post('jml_adult');
            $with_bed = $this->input->post('with_bed')=='' ? 0:$this->input->post('with_bed');
            $no_bed = $this->input->post('no_bed')=='' ? 0:$this->input->post('no_bed');
			$infant = $this->input->post('infant')=='' ? 0:$this->input->post('infant');			
									
			$group_info = $this->group_departure_model->get_group($group);
			$kode_group = $group_info->row()->KODE_GROUP;
			
			$program_info = $this->program_class_model->get_program($kelas_program);
			$nama_program = $program_info->row()->NAMA_PROGRAM;
			$maskapai = $program_info->row()->MASKAPAI;
						
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
			
			$kamar = $this->input->post('kamar');
			$jml_kamar = $this->input->post('jml_kamar');
			$id_tipe_kamar = $this->input->post('tipe_kamar');

			$input_kamar = '';
			$total_candidate = '';
			$value_waiting_list = 0;
			
			for($i=0; $i<count($jml_kamar); $i++)
			{
				$room_type = $this->room_type_model->get_roomType($id_tipe_kamar[$i]);
				if($jml_kamar[$i] == 0) { $jml_kamar[$i] = 0; }
				$input_kamar .= "
				<input type='text' name='jml_kamar[]' id='jml_kamar' class='input_small_table'  value='".$jml_kamar[$i]."'>
				<input type='hidden' name='tipe_kamar[]' value='".$room_type->row()->ID_ROOM_TYPE."'>";
				$total_candidate += $jml_kamar[$i];
			}
			
			
			// filter waiting list
			if($total_pagu == 0)
			{
				$data['msg_box1'] = '<li>Pagu Penerbangan '.$maskapai.' <font color="A01040">( TIDAK TERSEDIA )</font>.</li><br>';
				$value_waiting_list = 1;
			}
			
			if($total_candidate > $hitung_pagu)
			{
				$data['msg_box2'] = '<li>Jumlah Calon Jamaah <font color="A01040">Lebih Besar</font> dari Pagu Pesawat.</li><br>';
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
				$data['status_waiting'] = "<li><font color='3B619F'><strong>TERSEDIA</strong></font> , Silahkan klik tombol <strong>Proses</strong> di bawah ini untuk melanjutkan proses edit packet.</li>";
			}else{
				$data['status_waiting'] = "<br>Klik <a href='".site_url()."/beranda'><strong><font color='A01040' > Disini</font></strong></a> untuk mengkonfigurasi kembali paket.";
			}
			
			$data['waiting_list'] = $value_waiting_list;
			$data['input_kamar'] = $input_kamar;
			$data['jml_adult'] = $jml_adult;
			$data['with_bed'] = $with_bed;
			$data['no_bed'] = $no_bed;
			$data['infant'] = $infant;
			$data['group'] = $group;
			$data['program'] = $kelas_program;
			$data['packet'] = $id_packet;
			
			$data[] = null;
			$data['content'] = $this->load->view('home_result',$data,true);
			$this->load->view('front_backup',$data);
		}

        
        }        
        
	function check_availability(){
                $this->load->model('group_departure_model');
                $this->load->model('program_class_model');

                $group = $this->input->post('group');
                $kelas_program = $this->input->post('program');
                $jml_adult = $this->input->post('jml_adult');
                $with_bed = $this->input->post('with_bed')=='' ? 0:$this->input->post('with_bed');
                $no_bed = $this->input->post('no_bed')=='' ? 0:$this->input->post('no_bed');
                $infant = $this->input->post('infant')=='' ? 0:$this->input->post('infant');

                $group_info = $this->group_departure_model->get_group($group);
                $kode_group = $group_info->row()->KODE_GROUP;
                // $pagu_sv = $group_info->row()->PAGU_SV;
                // $pagu_ga = $group_info->row()->PAGU_GA;
                $depart_jd = $group_info->row()->TANGGAL_KEBERANGKATAN_JD;
                $depart_mk = $group_info->row()->TANGGAL_KEBERANGKATAN_MK;

                $program_info = $this->program_class_model->get_program($kelas_program);
                $nama_program = $program_info->row()->NAMA_PROGRAM;

                $total_candidate = $jml_adult + $with_bed + $no_bed + $infant;
                $message = "";

                // check pagu pesawat
                $plane_flag = FALSE;
                if ($pagu_sv > $total_candidate){
                        $plane_flag = TRUE;
                }
                if ($pagu_ga > $total_candidate){
                        $plane_flag = TRUE;
                }
                else if (($pagu_sv+$pagu_ga) > $total_candidate){
                        $plane_flag = TRUE;
                }else
                       $this->message = "Paket yang anda inginkan saat ini sedang tak tersedia.";

                // check room avilability
                $this->load->model('room_model');
                $this->load->model('room_type_model');

                $kamar = $this->input->post('kamar');
                $jml_kamar = $this->input->post('jml_kamar');
                $total_candidate -= ($no_bed + $infant);
                $flag_room = TRUE;
                $room_capacity = 0;
                $tmp_candidate = $total_candidate;
                $available_beds = $this->room_model->count_available_beds($group)->row()->JML_BEDS;

                for($i=0; $i<count($kamar); $i++){
                        if($kamar[$i]!='0'  && $kamar[$i] != ''){
                                $room_type = $this->room_type_model->get_roomType($kamar[$i]);
                                $this->room_choice[$i] = "<pre>".$room_type->row()->JENIS_KAMAR." jumlah ".$jml_kamar[$i]." Kamar</pre>";
                                $tmp_capacity = $room_type->row()->CAPACITY * $jml_kamar[$i];
                                $room_capacity += $tmp_capacity;

                                $tmp_candidate -= $tmp_capacity;
                                if ($tmp_candidate >= 0){
                                        $counter = $this->room_model->check_available_room($group, $kelas_program, $kamar[$i], $room_type->row()->CAPACITY)->num_rows();
                                }else {
                                        $counter = $this->room_model->check_available_room($group, $kelas_program, $kamar[$i], 0)->num_rows();
                                }

                                if ($counter < $jml_kamar[$i]){
                                        $flag_room = FALSE;
                                }
                        }
                }

                //reverse order
                if (! $flag_room){
                    $tmp_candidate = $total_candidate;
                    $room_capacity = 0;
                    $flag_room = TRUE;
                    for($i=count($kamar)-1; $i >= 0; $i--){
                            if($kamar[$i]!='0'  && $kamar[$i] != ''){
                                    $room_type = $this->room_type_model->get_roomType($kamar[$i]);
                                    $tmp_capacity = $room_type->row()->CAPACITY * $jml_kamar[$i];
                                    $room_capacity += $tmp_capacity;

                                    $tmp_candidate -= $tmp_capacity;
                                    if ($tmp_candidate >= 0){
                                            $counter = $this->room_model->check_available_room($group, $kelas_program, $kamar[$i], $room_type->row()->CAPACITY)->num_rows();
                                    }else {
                                            $counter = $this->room_model->check_available_room($group, $kelas_program, $kamar[$i], 0)->num_rows();
                                    }

                                    if ($counter < $jml_kamar[$i]){
                                            $flag_room = FALSE;
                                    }
                            }
                    }
                }

                $this->load->library('form_validation');
                if ($room_capacity >= $total_candidate && $available_beds >= $total_candidate){
                        if ($flag_room && $plane_flag){
                                return TRUE;
                        }else if ($flag_room==FALSE){
                                $this->message .= "Maaf, Jumlah kamar tidak tersedia untuk pilihan paket anda !!! Silahkan memilih konfigurasi yang lain.";
                                $this->available_room = $this->room_model->count_available_room($group);
                                return FALSE;
                        }else if ($plane_flag==FALSE){
                                return FALSE;
                        }
                } else {
                        $this->message = "Maaf, Jumlah pilihan kamar tidak mencukupi pilihan paket anda !!! Silahkan memilih konfigurasi yang lain.";
                        $this->available_room = $this->room_model->count_available_room($group);
                        return FALSE;
                }
                

	}
	
	function choose_packet()
	{
		$this->load->model('room_packet_model');
		$this->load->model('packet_model');
		$this->load->model('room_type_model');
		$this->load->model('log_model');
		
		$id_user = $this->session->userdata("id_account");
		$kode_reg = $this->session->userdata("kode_registrasi");
		
		$id_packet = $this->input->post('packet');
		$group = $this->input->post('group');
		$kelas_program = $this->input->post('program');
		$jml_adult = $this->input->post('jml_adult');
		$with_bed = $this->input->post('with_bed');
		$no_bed = $this->input->post('no_bed');
		$infant = $this->input->post('infant');
		$jml_kamar = $this->input->post('jml_kamar');
		$id_tipe_kamar = $this->input->post('tipe_kamar');

		// update into packet
	   	$data = array(
			'ID_GROUP'=>$group, 'ID_PROGRAM'=>$kelas_program,
			'JUMLAH_ADULT'=>$jml_adult, 'CHILD_WITH_BED'=>$with_bed, 'CHILD_NO_BED'=>$no_bed, 'INFANT'=>$infant
		 );
		
		$this->packet_model->update_packet($data, $id_packet);
		$this->log_model->log($id_user, $kode_reg, null, 'UPDATE data PACKET untuk akun dengan KODE_REGISTRASI = '.$kode_reg);
		
		// update into room packet		
		$id_pack = $this->packet_model->get_packet_byAcc($id_user, $kode_reg);
		for($i=0; $i<count($jml_kamar); $i++)
		{
			$room_type = $this->room_type_model->get_roomType($id_tipe_kamar[$i]);
			if($jml_kamar[$i] != 0) 
			{ 
				$this->room_packet_model->update_room_packet(array('JUMLAH'=>$jml_kamar[$i]), $id_tipe_kamar[$i],
                               			$id_pack->row()->ID_PACKET);
			}
		}
		
		// update log dan set session order_packet
		$log = 'UPDATE data ROOM_PACKET untuk packet dengan ID_PACKET = '.$id_pack->row()->ID_PACKET.'';
		$this->log_model->log($id_user, $kode_reg, null, $log);
		$this->session->set_userdata('order_packet', 1);
		
        redirect('beranda');
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
				$this->form_validation->set_message('check_jml', "Jumlah maksimum untuk %s adalah $max !");
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
				 $error .= "- Jumlah Konfigurasi Kamar <strong>".$room_type->row()->JENIS_KAMAR."</strong> <font color='A01040' >Lebih Besar</font> dari Sisa Kamar.<br>";
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

				// $pagu_ga = $row->PAGU_GA;
                                // $pagu_sv = $row->PAGU_SV;


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
			
			$views .= "<strong>".$spasi.$nama_tipe."</strong> tersedia untuk ".$tot." Orang ".$status_kamar."<br>";
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
	
	//cek apakah user sudah login kedalam sistem
    function cekSession(){
            if($this->session->userdata('id_account') == NULL || $this->session->userdata('id_account') == '')
                    redirect('login');
    }
}

/* End of file Check_availability.php */
/* Location: ./application/controllers/Check_availability.php */
