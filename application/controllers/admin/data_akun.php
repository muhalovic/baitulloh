<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * Description of accounts
 *
 * @author wiwit
 */
class data_akun extends CI_Controller{

    public function  __construct() {
        parent::__construct();
		if($this->session->userdata('id_user') == NULL)
			redirect(site_url()."/login");
    }


// ------------------------------ index ----------------------------------------

    public function index(){
        
		$this->view_list_accounts();
    }

// ----------------- Public view -----------------------------------------------

    public function edit_accounts($accounts_id=null,$kode_account=null){
       
		if(is_null($accounts_id) && is_null($kode_account)){
			redirect('admin/data_akun/view_list_accounts');

		}
          if($this->validate_form_accounts()==true){
            $this->form_accountsDB("update",$accounts_id,$kode_account);
			redirect('admin/data_akun/edit_accounts/'.$accounts_id.'/'.$kode_account);
            }
            else{
                $this->load_form_accounts($accounts_id);
            }
    }

    // public function view_accounts(){
       // Auth::cekSession('');
         
    // }

    public function add_accounts(){
      
      
        if($this->validate_form_accounts()==true){
                $this->form_accountsDB("insert");
				redirect('admin/data_akun/add_accounts');
            }
            else{
                $this->load_form_accounts();
            }
    }

    public function delete_accounts($accounts_id,$kode_registrasi){
		$this->load->model('accounts_model');
        
		if(is_null($accounts_id)){
			redirect('admin/data_akun/view_list_accounts');

		}
		
		$value = $this->accounts_model->get_account($accounts_id,$kode_registrasi)->result();
        
		if(!empty($value)){
            
			 $this->form_accountsDB("delete",$accounts_id,$kode_registrasi);
        }       
		redirect('admin/data_akun/view_list_accounts');
       
    }
	
	public function view_package($id_account,$kode_registrasi){
		if(is_null($id_account) || is_null($kode_registrasi)){
		 		
		}
		else{
		 $this->load_view_package($id_account,$kode_registrasi);	
		}
	}
    

    public function view_list_accounts(){
         $this->load->helper('flexigrid');

		
		$colModel['no'] = array('No',30,TRUE,'center',0);
		$colModel['edit'] = array('Edit',50,FALSE,'center',0);
		$colModel['delete'] = array('Delete',50,FALSE,'center',0);
		$colModel['paket'] = array('Paket',50,FALSE,'center',0);
		$colModel['paket'] = array('Paket',50,FALSE,'center',0);
		$colModel['jamaah'] = array('Tambah Jamaah',100,FALSE,'center',0);
		
		$colModel['NAMA_USER'] = array('Nama User',100,TRUE,'center',2);
		$colModel['EMAIL'] = array('Email',150,TRUE,'center',2);
		$colModel['TELP'] = array('Telepon',100,TRUE,'center',2);
		$colModel['MOBILE'] = array('Mobile',100,TRUE,'center',2);
		$colModel['ALAMAT'] = array('Alamat',300,TRUE,'center',2);
		$colModel['KOTA'] = array('Kota',100,TRUE,'center',2);
		




		$gridParams = array(
			'width' => '1190',
			'height' => 285,
			'rp' => 15,
			'rpOptions' => '[5,10,15,20,25,40]',
			'pagestat' => 'Menampilkan: {from} hingga {to} dari {total} hasil.',
			'blockOpacity' => 0.5,
			'title' => 'Daftar _accounts Applikasi',
			'showTableToggleBtn' => false
		);

		$buttons[] = array('separator');
		$buttons[] = array('Tambah','add','add');
                $buttons[] = array('separator');

		$content['js_grid'] = build_grid_js('flex1',base_url()."index.php/admin/data_akun/json_list_accounts",$colModel,'no','asc',$gridParams,$buttons);
		$content['added_js'] =
			"<script type='text/javascript'>
			function add()
			{
					location.href='".site_url('/admin/data_akun/add_accounts')."';
			}


			function edit(hash,hash1){
				if(confirm('Anda yakin ingin merubah data ini?')){
					location.href='".site_url()."/admin/data_akun/edit_accounts/'+hash+'/'+hash1;
				}
			}			
			
			function add_jamaah(hash,hash1){
					location.href='".site_url()."/admin/data_jamaah/do_daftar/'+hash+'/'+hash1;
			}
			
			function paket(hash,hash1){
				window.open('".site_url()."/admin/data_akun/view_package/'+hash+'/'+hash1,'Paket','width=550,height=250,left=400,top=100,screenX=400,screenY=100');
			}

			function hapus(hash,hash1){
				if(confirm('Anda yakin ingin menghapus data ini?')){
					location.href='".site_url()."/admin/data_akun/delete_accounts/'+hash+'/'+hash1;
				}
			}
             
			</script>
			";

			
			$content['notifikasi'] = null;
				if($this->session->userdata('sukses') == 'true'){
					$content['notifikasi'] = '<div id="message-green">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="green-left">Data Akun Calon Jamaah '.$this->session->userdata('delete_akun').' Berhasil dihapus.</td>
								<td class="green-right"><a class="close-green"><img src="'.base_url().'images/table/icon_close_green.gif"   alt=""  /></a></td>
							</tr>
						</table><br>
					</div>';
					$this->session->unset_userdata('sukses');
					$this->session->unset_userdata('delete_akun');
				}
                $contents['content'] = $this->load->view('admin/grid',$content,true);
                
                $this->load->view('admin/front',$contents);
    }

    public function json_list_accounts(){
                
		$this->load->library('flexigrid');
        $this->load->model('accounts_model');


		$valid_fields = array('ID_ACCOUNT');
		$this->flexigrid->validate_post('ID_ACCOUNT','asc',$valid_fields);
		
	
		$records = $this->accounts_model->get_grid_account();
		$this->output->set_header($this->config->item('json_header'));
		
		$no = 0;
		foreach ($records['records']->result() as $row)
		{
                    
			
			$edit = '<img alt="Edit"  style="cursor:pointer" src="'.base_url().'images/flexigrid/edit.jpg" onclick="edit(\''.$row->ID_ACCOUNT.'\',\''.$row->KODE_REGISTRASI.'\')">';
			$delete = '<img alt="Delete"  style="cursor:pointer" src="'.base_url().'images/flexigrid/delete.jpg" onclick="hapus(\''.$row->ID_ACCOUNT.'\',\''.$row->KODE_REGISTRASI.'\')">';
			$add_jamaah = '<img alt="Tambah Jamaah"  style="cursor:pointer" src="'.base_url().'images/flexigrid/add.png" onclick="add_jamaah(\''.$row->ID_ACCOUNT.'\',\''.$row->KODE_REGISTRASI.'\')">';
			$paket = '<img alt="Paket"  style="cursor:pointer" src="'.base_url().'images/flexigrid/book.png" onclick="paket(\''.$row->ID_ACCOUNT.'\',\''.$row->KODE_REGISTRASI.'\')">';
                 
			
			$record_items[] = array(
									$row->ID_ACCOUNT,
									$no = $no+1,
									$edit,
									$delete,
									$paket,
									$add_jamaah,
									$row->NAMA_USER,
									$row->EMAIL,
									$row->TELP,
									$row->MOBILE,
									$row->ALAMAT,
									$row->KOTA
									
			);
		}

		if(isset($record_items))
			$this->output->set_output($this->flexigrid->json_build($records['record_count'],
				$record_items));
		else
			$this->output->set_output('{"page":"1","total":"0","rows":[]}');

    }
    
// ---------------- Validation function ----------------------------------------

    private function validate_form_accounts(){
        $this->load->library('form_validation');
		
	
		
		$this->form_validation->set_rules('nama','Nama','required|xss_clean|prep_for_form');
		$this->form_validation->set_rules('email','Email','required|xss_clean|prep_for_form');
		$this->form_validation->set_rules('telp','Telepon','required|xss_clean|prep_for_form');
		$this->form_validation->set_rules('mobile','Handphone','required|xss_clean|prep_for_form');
        $this->form_validation->set_rules('province','Provinsi','required|xss_clean|prep_for_form');
        $this->form_validation->set_rules('kota','Kota','required|xss_clean|prep_for_form');
        $this->form_validation->set_rules('alamat','Alamat','required|xss_clean|prep_for_form');
        $this->form_validation->set_rules('id_card','No Identitas','required|xss_clean|prep_for_form');
		
		if($this->uri->segment(3)=== 'add_accounts'){
		$this->form_validation->set_rules('group','Group','required|xss_clean|prep_for_form');
        $this->form_validation->set_rules('program','Program','required|xss_clean|prep_for_form');
        $this->form_validation->set_rules('jml_adult','Jumlah Dewasa','required|xss_clean|prep_for_form');
        $this->form_validation->set_rules('kamar[]','Kamar','required|xss_clean|prep_for_form');
        $this->form_validation->set_rules('jml_kamar[]','Jumlah Kamar','required|xss_clean|prep_for_form');
		}
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');

		$this->form_validation->set_message('required', '<strong>%s</strong> wajib diisi!');
		$this->form_validation->set_message('numeric', '%s hanya boleh angka');
		$this->form_validation->set_message('xss_clean', 'No javascript allowed');
		$this->form_validation->set_message('prep_for_form', '%s tidak sesuai format');
		$this->form_validation->set_message('matches', '%s tidak sama dengan verifikasi');

        return $this->form_validation->run();
    }
 

// ---------------- Validation callback function -------------------------------

	public function is_exist(){
		$this->load->model('accounts');       
        if(is_null($this->accounts->get_accounts($this->input->post('accounts_id'))) ){
			
			
			return true;
		}
		else{
			if(strpos(current_url(),'edit') == true){
			$url = explode('/',current_url());
			if(strcmp($url[count($url)-1],$this->input->post('accounts_id'))==0){
			return true;
			}
			else{
			$this->form_validation->set_message('is_exist', 'accounts_id sudah ada');
			return false;
			}
			
			}
			else{
			$this->form_validation->set_message('is_exist', 'accounts_id sudah ada');
			return false;
			}
			
		}
	}
	
// ---------------- loader view function ---------------------------------------
	
	function load_view_package($id_account,$kode_registrasi){		
                $this->load->model('packet_model');
				$this->load->model('accounts_model');
				
                $id_user = $id_account;
                $kode_reg = $kode_registrasi;
				$account = (array)$this->accounts_model->get_data_account($id_user)->row();
				
                $packet = $this->packet_model->get_packet_byAcc($id_user, $kode_reg);
                if ($packet->num_rows() < 1){
                    $packet = $this->packet_model->get_packet_byAcc_waiting($id_user, $kode_reg);
                    $data['waiting'] = TRUE;
                }
				

                if ($packet->num_rows() > 0){
                    $this->load->model('room_packet_model');
                    $room = array();
                    $data['paket'] = $packet->result(); 
					
					foreach ($data['paket'] as $row){
                    $room[] = $this->room_packet_model->get_room_packet_byIDpack($row->ID_PACKET)->result();   
				
				   }

                    // get data room
                
                    $data['room'] = $room;
                    $data['is_order'] = TRUE;
					$data = array_merge($data,$account);

                    $data['content'] = $this->load->view('admin/package_user',$data);
                }
		

	}
	
	
    private function load_form_accounts($accounts_id=""){
        
        $this->load->model('accounts_model');
		$this->load->model('province_model');
		$this->load->model('group_departure_model');
		$this->load->model('program_class_model');
		$this->load->model('room_type_model');
		  
        $account = (array)$this->accounts_model->get_data_account($accounts_id)->row();
		
		$province = $this->province_model->get_all_province();
		
		$province_options[''] = '-- Pilih Propinsi --';
		foreach($province->result() as $row){
				$province_options[$row->ID_PROPINSI] = $row->NAMA_PROPINSI;
		}
			
		$group = $this->group_departure_model->get_all_group();
		$program = $this->program_class_model->get_all_program();
		$room = $this->room_type_model->get_all_roomType();

		$group_options[''] = '-- Pilih Group --';
		foreach($group->result() as $row){
				$group_options[$row->ID_GROUP] = $row->KODE_GROUP;
		}
		
		$program_options[''] = '-- Pilih Program --';
		foreach($program->result() as $row){
				$program_options[$row->ID_PROGRAM] = $row->NAMA_PROGRAM;
		}
		
		$room_options = '';
		foreach($room->result() as $row){
				$room_options[$row->ID_ROOM_TYPE] = $row->JENIS_KAMAR;
		}
			
			$content['group_options'] = $group_options;
			$content['program_options'] = $program_options;
			$content['room_options'] = $room_options;
			$content['province_options']= $province_options;
		
		
        if(empty($account)){
        
            $content['NAMA_USER'] = $this->input->post('nama_user');
            $content['EMAIL'] = $this->input->post('email');
            $content['TELP'] = $this->input->post('telp');
            $content['MOBILE'] = $this->input->post('mobile');
            $content['ID_PROPINSI'] = $this->input->post('province');
            $content['KOTA'] = $this->input->post('kota');
            $content['ALAMAT'] = $this->input->post('alamat');
            $content['NO_ID_CARD'] = $this->input->post('id_card');
            $content['ID_GROUP'] = $this->input->post('batas_waiting_list');
            $content['ID_PROGRAM'] = $this->input->post('pagu_sv');
            $content['JUMLAH_ADULT'] = $this->input->post('pagu_ga');
            $content['CHILD_WITH_BED'] = $this->input->post('pagu_ga');
            $content['CHILD_NO_BED'] = $this->input->post('pagu_ga');
            $content['INFANT'] = $this->input->post('pagu_ga');
            $content['HARI'] = $this->input->post('hari');
			$content['notifikasi'] = null;
				if($this->session->userdata('sukses') == 'true'){
					$content['notifikasi'] = '<div id="message-green">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="green-left">Data Akun Calon Jamaah Berhasil ditambah.</td>
								<td class="green-right"><a class="close-green"><img src="'.base_url().'images/table/icon_close_green.gif"   alt="" onclick = "window.location  = \''.base_url().'index.php/admin/data_akun'.'\'" /></a></td>
							</tr>
						</table><br>
					</div>';
					$this->session->unset_userdata('sukses');
				}
			
			$contents['content'] = $this->load->view('admin/form_registration',$content,true);
			
				
				
			
       }
       else{
           $content['notifikasi'] = null;
				if($this->session->userdata('sukses') == 'true'){
					$content['notifikasi'] = '<div id="message-green">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="green-left">Data Akun Calon Jamaah Berhasil diubah.</td>
								<td class="green-right"><a class="close-green"><img src="'.base_url().'images/table/icon_close_green.gif"   alt="" onclick = "window.location  = \''.base_url().'index.php/admin/data_akun'.'\'" /></a></td>
							</tr>
						</table><br>
					</div>';
					$this->session->unset_userdata('sukses');
				}
				
				
		   $content = array_merge($account,$content);
		   $contents['content'] = $this->load->view('admin/form_edit_akun',$content,true);
			
       }

     
     
     
       $contents['added_js']="";
      $this->load->view('admin/front',$contents);
    }
    
    


// ---------------- Database handler function ----------------------------------
    private function form_accountsDB($action,$currentaccounts_id="",$kode_account=""){
        $this->load->model('accounts_model');
        $this->load->model('log_model');
        $this->load->model('packet_model');
        $this->load->model('room_packet_model');
		
        $accounts = $this->accounts_model->get_account($currentaccounts_id,$kode_account); 
		
        if(is_null($accounts)){
        $accounts = array();
        }
		$nama = $this->input->post('nama');
		$email = $this->input->post('email');
		$telp = $this->input->post('telp');
		$mobile = $this->input->post('mobile');
		$province = $this->input->post('province');
		$kota = $this->input->post('kota');
		$alamat = $this->input->post('alamat');
		$id_card = $this->input->post('id_card');
		$kode_reg = substr(md5('koderegistrasi-'.$nama.'-'.$email.'-'.date("Y m d H:i:s")), 0, 15);
		$pwd = substr(md5('password-'.$nama.'-'.$email.'-'.date("Y m d")), 0, 5);

        $accounts = array('KODE_REGISTRASI' => $kode_reg, 'ID_PROPINSI' => $province, 'NAMA_USER' => $nama, 
								'EMAIL' => $email, 'PASSWORD' =>md5($pwd), 'NO_ID_CARD' => $id_card, 'TELP' => $telp, 
								'MOBILE' => $mobile, 'KOTA' => $kota, 'ALAMAT' => $alamat, 'TANGGAL_REGISTRASI' =>date("Y-m-d h:i:s"), 'STATUS' => 1);
								
		
        if($action =="insert"){
            $this->accounts_model->insert_new_account($accounts);
			$this->log_model->log(null, null, $this->session->userdata('id_user'), 'REGISTER new account, EMAIL = '.$accounts['EMAIL'].', KODE_REGISTRASI = '.$accounts['KODE_REGISTRASI']);
			 // data packet
			  $account= $this->accounts_model->get_account_byKode($accounts['KODE_REGISTRASI'])->row();
			  
                                        $group = $this->input->post('group');
                                        $kelas_program = $this->input->post('program');
                                        $jml_adult = $this->input->post('jml_adult');
                                        $with_bed = $this->input->post('with_bed')=='' ? 0:$this->input->post('with_bed');
                                        $no_bed = $this->input->post('no_bed')=='' ? 0:$this->input->post('no_bed');
                                        $infant = $this->input->post('infant')=='' ? 0:$this->input->post('infant');

                                        $data = array(
                                            'ID_GROUP'=>$group, 'ID_ACCOUNT'=>$account->ID_ACCOUNT, 'KODE_REGISTRASI' =>$accounts['KODE_REGISTRASI'], 'ID_PROGRAM'=>$kelas_program,
                                            'JUMLAH_ADULT'=>$jml_adult, 'CHILD_WITH_BED'=>$with_bed, 'CHILD_NO_BED'=>$no_bed, 'INFANT'=>$infant,
                                            'TANGGAL_PESAN'=>date("Y-m-d h:i:s"), 'STATUS_PESANAN'=>1
                                        );

                                        // insert into packet
                                        $this->packet_model->insert_packet($data);
                                        $this->log_model->log(null, null, $this->session->userdata('id_user'), 'INSERT data PACKET untuk akun dengan KODE_REGISTRASI = '.$accounts['KODE_REGISTRASI']);

                                        // insert into room packet
                                        $id_pack = $this->packet_model->get_packet_byAcc_waiting($account->ID_ACCOUNT, $accounts['KODE_REGISTRASI']);
                                        if ($id_pack->num_rows() > 0){
                                            $this->load->model('room_packet_model');
                                            $kamar = $this->input->post('kamar');
                                            $jml_kamar = $this->input->post('jml_kamar');

                                            for($i=0; $i<count($kamar); $i++){
                                                $this->room_packet_model->insert_room_packet(array('ID_ROOM_TYPE'=>$kamar[$i],
                                                    'ID_PACKET'=>$id_pack->row()->ID_PACKET, 'JUMLAH'=>$jml_kamar[$i]));
                                            }

                                            $this->log_model->log(null, null, $this->session->userdata('id_user'), 'INSERT data ROOM_PACKET untuk packet dengan ID_PACKET = '.$id_pack->row()->ID_PACKET);
                                        }
				$this->session->set_userdata('sukses','true');
			
        }
        elseif($action =="update"){
			  $accounts = array( 'ID_PROPINSI' => $province, 'NAMA_USER' => $nama, 
								'EMAIL' => $email, 'PASSWORD' =>md5($pwd), 'NO_ID_CARD' => $id_card, 'TELP' => $telp, 
								'MOBILE' => $mobile, 'KOTA' => $kota, 'ALAMAT' => $alamat, 'TANGGAL_UPDATE' =>date("Y-m-d h:i:s"), 'STATUS' => 1);
			                                            $this->log_model->log(null, null, $this->session->userdata('id_user'), 'UPDATE data Account dengan ID_ACCOUNT = '.$currentaccounts_id);
            $this->accounts_model->update_with_id_account($accounts,$currentaccounts_id);
				$this->session->set_userdata('sukses','true');
	   }
		
		elseif($action =="delete"){
			$account = $this->accounts_model->get_data_account($currentaccounts_id)->row();
			$accounts = array(  'TANGGAL_UPDATE' =>date("Y-m-d h:i:s"), 'STATUS' => 0);
			$this->log_model->log(null, null, $this->session->userdata('id_user'), 'DELETE/DEAKTIF data Account dengan ID_ACCOUNT = '.$currentaccounts_id);
            $this->accounts_model->update_with_id_account($accounts,$currentaccounts_id);
        	$this->session->set_userdata('sukses','true');
        	$this->session->set_userdata('delete_akun',$account->NAMA_USER);
			

		}
    }

// ---------------- Private function -------------------------------------------
	
	
	private function konversi_tanggal($tgl){
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
?>
