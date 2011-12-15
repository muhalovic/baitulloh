<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_propinsi extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		if($this->session->userdata('id_user') == NULL)
			redirect(site_url()."/admin/login");
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
		$this->load->model('province_model');
		
		$propinsi		= $this->province_model->get_all_province();
		$total_data 	= $this->province_model->get_total_propinsi();
		$total_data		= ''.$total_data ;
		
		$colModel['no'] 				= array('No',40,TRUE,'center',0);
		$colModel['EDIT']		 		= array('Edit',50,FALSE,'center',0);
		$colModel['NAMA_PROPINSI'] 		= array('Nama Propinsi',150,TRUE,'center',1);
		$colModel['STATUS'] 	= array('Status',150,TRUE,'center',1);
		
		$gridParams = array(
		'width' => '1190',
		'height' => 300,
		'rp' => 10,
		'rpOptions' => '[5,10,15,20,25,30,'.$total_data.']',
		'pagestat' => 'Menampilkan: {from} sampai {to} dari {total} hasil.',
		'blockOpacity' => 0.5,
		'title' => 'Data Master Propinsi',
		'showTableToggleBtn' => false
		);
		
		$buttons[] = array('separator');
		$buttons[] = array('Tambah','add','js');
        $buttons[] = array('separator');
		$buttons[] = array('Hapus','delete','js');
        $buttons[] = array('separator');
				
		$grid_js = build_grid_js('flex1',site_url("/admin/master_propinsi/grid/"),$colModel,'no','asc',$gridParams,$buttons);
		$data['js_grid'] = $grid_js;
		
		$data['added_js'] = "<script type='text/javascript'>
			function js(com,grid)
			{
				if(com=='Tambah')
				{
					location.href='".site_url('/admin/master_propinsi/input')."';
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
							   url: '".site_url('/admin/master_propinsi/hapus')."',
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

	function grid() 
	{
		
		//call library or helper
		$this->load->library('flexigrid');	
		$this->load->helper('flexigrid');
		$this->load->library('form_validation');		
		
		//call model here	
		$this->load->model('province_model');
		
		$valid_fields = array('NAMA_PROPINSI','STATUS');
		$this->flexigrid->validate_post('ID_PROPINSI','asc',$valid_fields);
		
		$records = $this->province_model->get_grid_allover();
		$this->output->set_header($this->config->item('json_header'));
		
		$no = 0;
                
		foreach ($records['records']->result() as $row)
		{
			$url_edit = '<a style="cursor:pointer" href="'.site_url().'/admin/master_propinsi/edit/'.$row->ID_PROPINSI.'"><img src="'.base_url().'/images/flexigrid/edit.jpg"></a>';
			
			if($row->STATUS =='1') { $status = "Aktif"; }
			  else { $status = "Tidak Aktif"; }
			
			$record_items[] = array(

				$row->ID_PROPINSI,
				$no = $no+1,
				$url_edit,
				$row->NAMA_PROPINSI,
				$status,
			);
		}
		
		if(isset($record_items))
			$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
		else
			$this->output->set_output('{"page":"1","total":"0","rows":[]}');			
	}
	
	function hapus()
	{
		$this->load->model('province_model');
		$this->load->model('log_model');

		$pecah_id = explode(',' , $this->input->post('items'));
		$hitung_id = (count($pecah_id)) - 1;
		$log = "menghapus ".$hitung_id." item Propinsi";
		$hapus_data = '';

		foreach($pecah_id as $index => $id_propinsi)
		{
			if (is_numeric($id_propinsi))
			{
				$hapus_data .= $this->province_model->hapus_propinsi($id_propinsi);
			}
		}

		$error = "Data Propinsi ( ID : ".$this->input->post('items').") berhasil dihapus";

		$this->log_model->log(NULL, NULL, $this->session->userdata('id_user'), $log);
		$this->output->set_header($this->config->item('ajax_header'));
		$this->output->set_output($error);
	}
	
	function input()
	{
		$this->load->library('form_validation');
		$this->load->model('log_model');
		
		$data['e_id_propinsi'] = NULL;
		$data['e_nama'] = NULL;
		$data['e_status'] = NULL;	
		$data['notifikasi'] = NULL;
		
		$data['content'] = $this->load->view('admin/form_propinsi_input', $data, true);
		$this->load->view('admin/front', $data);
	}
	
	function edit($id_propinsi = NULL)
	{
		
		$this->load->library('form_validation');
		$this->load->model('province_model');
		$this->load->model('log_model');
		
		if(is_null($id_propinsi)){
			redirect('/admin/master_propinsi/');
		}
		
		$data_propinsi = $this->province_model->get_province($id_propinsi);

		if($data_propinsi->result() != NULL)
		{
			foreach($data_propinsi->result() as $row)
			{
				$data['e_id_propinsi'] = $row->ID_PROPINSI;
				$data['e_nama'] = $row->NAMA_PROPINSI;
				$data['e_status'] = $row->STATUS;				
				$data['notifikasi'] = NULL;
				
				if($this->session->userdata('sukses') == 'true'){
					$data['notifikasi'] = '<div id="message-green">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="green-left">Ubah Data Propinsi Berhasil. Klik <a href="'.site_url().'/admin/master_propinsi/"><strong>Disini</strong></a> untuk kembali ke daftar Master Propinsi</td>
								<td class="green-right"><a class="close-green"><img src="'.base_url().'images/table/icon_close_green.gif"   alt="" /></a></td>
							</tr>
						</table><br>
					</div>';
					$this->session->unset_userdata('sukses');
				}
				
				
				$data['content'] = $this->load->view('admin/form_propinsi_input', $data, true);
				$this->load->view('admin/front', $data);
			
			}
		
		} else {
			
			redirect(site_url()."/admin/master_propinsi");
		}
		
	}
	
	
	function do_send($id_propinsi = NULL){
		
		$this->load->library('form_validation');
		$this->load->model('province_model');
		$this->load->model('log_model');
						
		if ($this->cek_validasi() == FALSE){
			$this->input();
		}
		else{

			// insert ke database
			$data = array(
				'NAMA_PROPINSI' => $this->input->post('nama_propinsi'),
				'STATUS' => $this->input->post('status'),
				);
			
			if($id_propinsi == NULL)
			{
				$log = "Menambah 1 item Propinsi";
				
				$this->province_model->insert_propinsi($data);
				$this->log_model->log(NULL, NULL, $this->session->userdata('id_user'), $log);
				
				redirect('/admin/master_propinsi/');
			
			}else{
				
				$cek_data = $this->province_model->get_province($id_propinsi);
				if($cek_data->num_rows() > 0)
				{
					$log = "Ubah 1 item Propinsi ID:".$id_propinsi;
				
					$this->province_model->update_propinsi($data, $id_propinsi);
					$this->log_model->log(NULL, NULL, $this->session->userdata('id_user'), $log);
					$this->session->set_userdata('sukses','true');
					
					redirect('/admin/master_propinsi/edit/'.$id_propinsi);
				}else{
					redirect('/admin/master_propinsi/');
				}
			}
		}
	}
	
	
	function cek_validasi() {
		$this->load->library('form_validation');
		
		//setting rules
		$config = array(
				array('field'=>'nama_propinsi','label'=>'Nama Propinsi', 'rules'=>'required'),
			//	array('field'=>'status','label'=>'Group', 'Status'=>'required'),
			);
		
		$this->form_validation->set_rules($config);
		$this->form_validation->set_message('required', 'Kolom <strong>%s</strong> harus diisi !');

		return $this->form_validation->run();
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

}//end class

/* End of file /admin/master_room.php */
/* Location: ./application/controllers/admin/master_propinsi.php */