<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_room extends CI_Controller {

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
		
		//call model here	
		$this->load->model('jamaah_candidate_model');
		$this->load->model('group_departure_model');
		$this->load->model('program_class_model');
		$this->load->model('room_availability_model');
		
		$group 			= $this->group_departure_model->get_all_group();
		$program 		= $this->program_class_model->get_all_program();
		$total_data 	= $this->room_availability_model->get_total_room();
		$total_data		= ''.$total_data ;
		
		$colModel['no'] 				= array('No',40,TRUE,'center',0);
		$colModel['JENIS_KAMAR'] 		= array('Tipe Kamar',150,TRUE,'center',1);
		$colModel['KODE_GROUP'] 		= array('Nama Group',150,TRUE,'center',1);
		$colModel['NAMA_PROGRAM'] 		= array('Program',150,TRUE,'center',1);
		$colModel['HARGA_KAMAR']		= array('Harga ($)',150,TRUE,'center',1);
		$colModel['JUMLAH_KAMAR'] 		= array('Jumlah Kamar',100,FALSE,'center',1);
		$colModel['EDIT']		 		= array('Edit',80,FALSE,'center',0);
		
		$gridParams = array(
		'width' => '1190',
		'height' => 300,
		'rp' => 10,
		'rpOptions' => '[5,10,15,20,25,30,'.$total_data.']',
		'pagestat' => 'Menampilkan: {from} sampai {to} dari {total} hasil.',
		'blockOpacity' => 0.5,
		'title' => 'Data Master Kamar',
		'showTableToggleBtn' => false
		);
		
		$buttons[] = array('separator');
		$buttons[] = array('Tambah','add','js');
        $buttons[] = array('separator');
		$buttons[] = array('Hapus','delete','js');
        $buttons[] = array('separator');
				
		$grid_js = build_grid_js('flex1',site_url("/admin/master_room/grid_kamar/"),$colModel,'no','asc',$gridParams,$buttons);
		$data['js_grid'] = $grid_js;
		
		$data['added_js'] = "<script type='text/javascript'>
			function js(com,grid)
			{
				if(com=='Tambah')
				{
					location.href='".site_url('/admin/master_room/input')."';
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
							   url: '".site_url('/admin/master_room/hapus_kamar')."',
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
		
		$data['content'] = $this->load->view('admin/grid',$data,true);
		$this->load->view('admin/front',$data);		
	}

	function grid_kamar() 
	{
		
		//call library or helper
		$this->load->library('flexigrid');	
		$this->load->helper('flexigrid');
		$this->load->library('form_validation');		
		
		//call model here	
		$this->load->model('jamaah_candidate_model');
		$this->load->model('packet_model');
		$this->load->model('group_departure_model');
		$this->load->model('room_availability_model');
		
		$valid_fields = array('JENIS_KAMAR','KODE_GROUP','NAMA_PROGRAM','HARGA_KAMAR','JUMLAH_KAMAR', 'EDIT');
		$this->flexigrid->validate_post('ID_AVAILABILITY','desc',$valid_fields);
		
		$records = $this->room_availability_model->get_grid_allover_room();
		$this->output->set_header($this->config->item('json_header'));
		
		$no = 0;
                
		foreach ($records['records']->result() as $row)
		{
			$url_edit = '<a style="cursor:pointer" href="'.site_url().'/admin/master_room/edit/'.$row->ID_AVAILABILITY.'"><img src="'.base_url().'/images/flexigrid/book.png"></a>';
			
			$record_items[] = array(
				$row->ID_AVAILABILITY,
				$no = $no+1,
				$row->JENIS_KAMAR,
				$row->KODE_GROUP,	
				$row->NAMA_PROGRAM,
				$this->cek_ribuan($row->HARGA_KAMAR),
				$row->JUMLAH_KAMAR,
				$url_edit,
			);
		}
		
		if(isset($record_items))
			$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
		else
			$this->output->set_output('{"page":"1","total":"0","rows":[]}');			
	}
	
	function hapus_kamar()
	{
		$this->load->model('room_availability_model');
		$this->load->model('log_model');

		$pecah_id = explode(',' , $this->input->post('items'));
		$hitung_id = (count($pecah_id)) - 1;
		$log = "menghapus ".$hitung_id." Kamar Yg Tersedia";
		$hapus_data = '';

		foreach($pecah_id as $index => $id_room_ava)
		{
			if (is_numeric($id_room_ava))
			{
				$hapus_data .= $this->room_availability_model->hapus_room($id_room_ava);
			}
		}

		$error = "Data Kamar Tersedia ( ID : ".$this->input->post('items').") berhasil dihapus";

		$this->log_model->log(NULL, NULL, $this->session->userdata('id_user'), $log);
		$this->output->set_header($this->config->item('ajax_header'));
		$this->output->set_output($error);
	}
	
	function input()
	{
		$this->load->library('form_validation');
		
		$this->load->model('group_departure_model');
		$this->load->model('room_type_model');
		$this->load->model('program_class_model');
		
		$group = $this->group_departure_model->get_all_group();
		$room_type = $this->room_type_model->get_all_roomType();
		$program = $this->program_class_model->get_all_program();
		
		

		$group_options['0'] = '-- Pilih Group --';
		foreach($group->result() as $row){
				$group_options[$row->ID_GROUP] = $row->KODE_GROUP;
		}
		
		$room_type_options['0'] = '-- Pilih Tipe Kamar --';
		foreach($room_type->result() as $row){
				$room_type_options[$row->ID_ROOM_TYPE] = $row->JENIS_KAMAR;
		}
		
		$program_options[''] = '-- Pilih Program --';
		foreach($program->result() as $row){
				$program_options[$row->ID_PROGRAM] = $row->NAMA_PROGRAM;
		}
		
		$data['group_options'] = $group_options;
		$data['room_type_options'] = $room_type_options;
		$data['program_options'] = $program_options;
		$data['e_tipe_kamar'] = NULL;
		$data['e_group'] = NULL;
		$data['e_program'] = NULL;
		$data['e_harga'] = NULL;
		$data['e_jumlah'] = NULL;
		
		$data['notifikasi'] = null;
		if($this->session->userdata('sukses') == 'true'){
			$data['notifikasi'] = '<div id="message-green">
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="green-left">Ubah Data Kamar Tersedia Berhasil.</td>
						<td class="green-right"><a class="close-green"><img src="'.base_url().'images/table/icon_close_green.gif"   alt="" /></a></td>
					</tr>
				</table><br>
			</div>';
			$this->session->unset_userdata('sukses');
		}
		
		$data['content'] = $this->load->view('admin/room_input', $data, true);
		$this->load->view('admin/front', $data);
	}
	
	function edit($id_room = NULL)
	{
		
		$this->load->library('form_validation');
		$this->load->model('group_departure_model');
		$this->load->model('room_type_model');
		$this->load->model('program_class_model');
		$this->load->model('room_availability_model');
		
		if(is_null($id_room)){
			redirect('/admin/master_room/');
		}
		
		$data_kamar = $this->room_availability_model->get_room_byId($id_room);

		if($data_kamar->result() != NULL)
		{
			foreach($data_kamar->result() as $row)
			{

				$data['e_id_room_ava'] = $row->ID_AVAILABILITY;
				$data['e_tipe_kamar'] = $row->ID_ROOM_TYPE;
				$data['e_group'] = $row->ID_GROUP;
				$data['e_program'] = $row->ID_PROGRAM;
				$data['e_harga'] = $row->HARGA_KAMAR;
				$data['e_jumlah'] = $row->JUMLAH_KAMAR;
				
				
				// LOAD DATA DROPDOWN
				$this->load->model('group_departure_model');
				$this->load->model('room_type_model');
				$this->load->model('program_class_model');
				
				$group = $this->group_departure_model->get_all_group();
				$room_type = $this->room_type_model->get_all_roomType();
				$program = $this->program_class_model->get_all_program();
				
				
		
				$group_options['0'] = '-- Pilih Group --';
				foreach($group->result() as $row){
						$group_options[$row->ID_GROUP] = $row->KODE_GROUP;
				}
				
				$room_type_options['0'] = '-- Pilih Tipe Kamar --';
				foreach($room_type->result() as $row){
						$room_type_options[$row->ID_ROOM_TYPE] = $row->JENIS_KAMAR;
				}
				
				$program_options[''] = '-- Pilih Program --';
				foreach($program->result() as $row){
						$program_options[$row->ID_PROGRAM] = $row->NAMA_PROGRAM;
				}
				
				$data['group_options'] = $group_options;
				$data['room_type_options'] = $room_type_options;
				$data['program_options'] = $program_options;
				
				
				$data['notifikasi'] = null;
				if($this->session->userdata('sukses') == 'true'){
					$data['notifikasi'] = '<div id="message-green">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="green-left">Ubah Data Kamar Tersedia Berhasil.</td>
								<td class="green-right"><a class="close-green"><img src="'.base_url().'images/table/icon_close_green.gif"   alt="" /></a></td>
							</tr>
						</table><br>
					</div>';
					$this->session->unset_userdata('sukses');
				}
				
				
				$data['content'] = $this->load->view('admin/room_input', $data, true);
				$this->load->view('admin/front', $data);
			
			}
		
		} else {
			
			redirect(site_url()."/admin/master_room");
		}
		
	}
	
	
	function do_send($id_availability = NULL){
		
		$this->load->library('form_validation');
		$this->load->model('room_availability_model');
		$this->load->model('log_model');
						
		if ($this->cek_validasi() == FALSE){
			$this->input();
		}
		else{

			// insert ke database
			$data = array(
				'ID_ROOM_TYPE' => $this->input->post('tipe_kamar'),
				'ID_PROGRAM' => $this->input->post('program'),
				'ID_GROUP' => $this->input->post('group'),
				'HARGA_KAMAR' => $this->input->post('harga'),
				'JUMLAH_KAMAR' => $this->input->post('jumlah')
				);
			
			if($id_availability == NULL)
			{
				$log = "Menambah 1 Record Daftar Kamar";
				
				$this->room_availability_model->insert_room($data);
				$this->log_model->log(NULL, NULL, $this->session->userdata('id_user'), $log);
				
				redirect('/admin/master_room/');
			
			}else{
				
				$cek_data = $this->room_availability_model->get_room_byId($id_availability);
				if($cek_data->num_rows() > 0)
				{
					$log = "Ubah 1 Record Room Availability ID:".$id_availability;
				
					$this->room_availability_model->update_room($data, $id_availability);
					$this->log_model->log(NULL, NULL, $this->session->userdata('id_user'), $log);
					$this->session->set_userdata('sukses','true');
					
					redirect('/admin/master_room/edit/'.$id_availability);
				}else{
					redirect('/admin/master_room/');
				}
			}
		}
	}
	
	
	function cek_validasi() {
		$this->load->library('form_validation');
		
		//setting rules
		$config = array(
				array('field'=>'tipe_kamar','label'=>'Tipe Kamar', 'rules'=>'required|callback_cek_dropdown'),
				array('field'=>'group','label'=>'Group', 'rules'=>'required|callback_cek_dropdown'),
				array('field'=>'program','label'=>'Program', 'rules'=>'required|callback_cek_dropdown'),
				array('field'=>'harga','label'=>'Harga', 'rules'=>'required|numeric'),
				array('field'=>'jumlah','label'=>'Jumlah', 'rules'=>'required|numeric'),
			);
		
		$this->form_validation->set_rules($config);
		$this->form_validation->set_message('required', 'Kolom <strong>%s</strong> harus diisi !');
		$this->form_validation->set_message('numeric', '<strong>Kolom %s</strong> harus berupa angka !');

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

        // cek order packet
        function cekOrder(){
            $this->load->model('packet_model');
            $id_user = $this->session->userdata("id_account");
            $kode_reg = $this->session->userdata("kode_registrasi");

            $packet = $this->packet_model->get_packet_byAcc($id_user, $kode_reg);
            if ($packet->num_rows() < 1)
                    redirect('beranda');
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
	
	function cek_ribuan($txt)
	{
		$pecah = number_format($txt);
		$ubah = str_replace(",", ".", $pecah);
		
		return $ubah;
	}

}//end class

/* End of file /admin/master_room.php */
/* Location: ./application/controllers/admin/master_room.php */