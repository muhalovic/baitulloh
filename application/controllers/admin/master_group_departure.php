<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * Description of master_group_departure
 *
 * @author wiwit
 */
class master_group_departure extends CI_Controller{

    public function  __construct() {
        parent::__construct();
		if($this->session->userdata('id_user') == NULL)
			redirect(site_url()."/login");
    }


// ------------------------------ index ----------------------------------------

    public function index(){
        
		$this->view_list_group_departure();
    }

// ----------------- Public view -----------------------------------------------

    public function edit_group_departure($group_departure_id=null){
       
		if(is_null($group_departure_id)){
			redirect('master_group_departure/view_list_group_departure');

		}
          if($this->validate_form_group_departure()==true){
            $this->form_group_departureDB("update",$group_departure_id);
			redirect('admin/master_group_departure/edit_group_departure/'.$group_departure_id);
            }
            else{
                $this->load_form_group_departure($group_departure_id);
            }
    }

    // public function view_group_departure(){
       // Auth::cekSession('');
         
    // }

    public function add_group_departure(){
      
      
        if($this->validate_form_group_departure()==true){
                $this->form_group_departureDB("insert");
				redirect('admin/master_group_departure/add_group_departure');
            }
            else{
                $this->load_form_group_departure();
            }
    }
	
    public function delete_group_departure(){
		$this->load->model('group_departure_model');
		$this->load->model('packet_model');
		$this->load->model('room_model');
		$this->load->model('room_availability_model');
		$this->load->model('log_model');
        
		if(!isset($_POST['items'])){
			redirect('admin/master_group_departure/view_list_group_departure');

		}
		
		$error = '';
		$success = '';
		$group_departure_ids = explode(',',$this->input->post('items'));
		
		foreach($group_departure_ids as $group_departure_id){

		$value = $this->group_departure_model->get_group_berdasarkan_id($group_departure_id)->result();
        
		if(!empty($value)){
		$paket = $this->packet_model->get_packet_related_with_group($group_departure_id)->result();
		$room = $this->room_model->get_room_related_with_group($group_departure_id)->result();
		$room_availability = $this->room_availability_model->get_room_availability_related_with_group($group_departure_id)->result();

		
			if(!empty($paket)  || !empty($room)  ||  !empty($room_availability)){
				if($error != ''){
					$error .= ', ';
				}
					$error .= $value[0]->KODE_GROUP;	
			}
			else{
				$this->group_departure_model->delete_group($group_departure_id);
				if($success != ''){
				$success .= ', ';
				}
					$success .= $value[0]->KODE_GROUP;
			 
				$log = "Menghapus grup keberangkatan dengan id ".$group_departure_id.'('.$value[0]->KODE_GROUP.')';
				$this->log_model->log(null, null, $this->session->userdata('id_user'), $log);

			}
			
        }       
		}
		$messege = '';
		
		if($success != ''){
			$messege .= 'data grup keberangkatan : '.$success.' berhasil dihapus';
		}
		if($success != '' && $error != ''){
			$messege .= ' sedangkan ';
		}
		if($error != ''){
			$messege .= 'data grup keberangkatan : '.$error.' tidak dapat dihapus karena masih digunakan data yang lain';
		}
		
		$this->output->set_header($this->config->item('ajax_header'));
		$this->output->set_output($messege);
       
    }
    

    public function view_list_group_departure(){
         $this->load->helper('flexigrid');

		
		$colModel['no'] = array('No',30,TRUE,'center',0);
		$colModel['edit'] = array('Edit',50,FALSE,'center',0);
		// $colModel['delete'] = array('Delete',50,FALSE,'center',0);
		
		$colModel['KODE_GROUP'] = array('Kode Group',100,TRUE,'center',2);
		$colModel['KETERANGAN'] = array('Keterangan',100,TRUE,'center',2);
		$colModel['TANGGAL_KEBERANGKATAN_JD'] = array('Tanggal Keberangkatan Jeddah',150,TRUE,'center',2);
		$colModel['TANGGAL_KEBERANGKATAN_MK'] = array('Tanggal Keberangkatan Makkah',150,TRUE,'center',2);
		$colModel['JATUH_TEMPO_PASPOR'] = array('Jatuh Tempo Paspor',150,TRUE,'center',2);
		$colModel['JATUH_TEMPO_UANG_MUKA'] = array('Jatuh Tempo Uang Muka',150,TRUE,'center',2);
		$colModel['JATUH_TEMPO_PELUNASAN'] = array('Jatuh Tempo Pelunasan',150,TRUE,'center',2);
		$colModel['JATUH_TEMPO_BERKAS'] = array('Jatuh Tempo Berkas',150,TRUE,'center',2);
		$colModel['BATAS_WAITING_LIST'] = array('Batas Waiting List',150,TRUE,'center',2);
		$colModel['HARI'] = array('Jumlah Hari',100,TRUE,'center',2);
		$colModel['STATUS'] = array('Status',100,TRUE,'center',2);
		
        




		$gridParams = array(
			'width' => '1190',
			'height' => 285,
			'rp' => 15,
			'rpOptions' => '[5,10,15,20,25,40]',
			'pagestat' => 'Menampilkan: {from} hingga {to} dari {total} hasil.',
			'blockOpacity' => 0.5,
			'title' => 'Daftar _group_departure Applikasi',
			'showTableToggleBtn' => false
		);

		$buttons[] = array('separator');
		$buttons[] = array('Tambah','add','js');
		$buttons[] = array('separator');
		$buttons[] = array('Hapus','delete','js');

		$content['js_grid'] = build_grid_js('flex1',base_url()."index.php/admin/master_group_departure/json_list_group_departure",$colModel,'no','asc',$gridParams,$buttons);
		$content['added_js'] =
			"<script type='text/javascript'>
			function js(com,grid)
			{
				if(com=='Tambah')
				{
					location.href='".site_url('/admin/master_group_departure/add_group_departure')."';
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
							   url: '".site_url('/admin/master_group_departure/delete_group_departure')."',
							   data: 'items='+itemlist,
							   success: function(data){
								$('#flex1').flexReload();
								alert(data);
							   }
							});
						}
					}
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
			
			if($this->session->userdata('notification')==true){
		   $content['notifikasi'] = '<div id="message-green">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="green-left">'.$this->session->userdata('notification_messege').'</td>
								<td class="green-right"><a class="close-green"><img src="'.base_url().'images/table/icon_close_green.gif"   alt=""  /></a></td>
							</tr>
						</table><br>
					</div>';
		   $this->session->unset_userdata('notification');
		   $this->session->unset_userdata('notification_messege');
		}
		
		if($this->session->userdata('error')==true){
		   $content['error'] ='<div id="message-blue">
								<table border="0" width="100%" cellpadding="0" cellspacing="0">
									<tr>
										<td class="blue-left">'.$this->session->userdata('error_messege').'</td>
										<td class="blue-right"><a class="close-blue"><img src="'.base_url().'images/table/icon_close_blue.gif"   alt="" /></a></td>
									</tr>
								</table><br>
							</div>';

		   $this->session->unset_userdata('error');
		   $this->session->unset_userdata('error_messege');
	   }
			
                $contents['content'] = $this->load->view('admin/grid',$content,true);
                
                $this->load->view('admin/front',$contents);
    }

    public function json_list_group_departure(){
                
		$this->load->library('flexigrid');
        $this->load->model('group_departure_model');


		$valid_fields = array('KODE_GROUP');
		$this->flexigrid->validate_post('KODE_GROUP','asc',$valid_fields);
		
	
		$records = $this->group_departure_model->get_grid_group();
		$this->output->set_header($this->config->item('json_header'));
		
		$no = 0;
		foreach ($records['records']->result() as $row)
		{
                    
			
			$edit = '<img alt="Edit"  style="cursor:pointer" src="'.base_url().'images/flexigrid/edit.jpg" onclick="edit(\''.$row->ID_GROUP.'\')">';
			$delete = '<img alt="Delete"  style="cursor:pointer" src="'.base_url().'images/flexigrid/delete.jpg" onclick="hapus(\''.$row->ID_GROUP.'\')">';
                 
			if($row->STATUS == 0){
			$status = 'tidak aktif';
			}
			else{
			$status = 'aktif';
			}
				 
			
			$record_items[] = array(
									$row->ID_GROUP,
									$no = $no+1,
									$edit,
								//	$delete,
									$row->KODE_GROUP,
									$row->KETERANGAN,
									date('d-m-Y',strtotime($row->TANGGAL_KEBERANGKATAN_JD)),
									date('d-m-Y',strtotime($row->TANGGAL_KEBERANGKATAN_MK)),
									date('d-m-Y',strtotime($row->JATUH_TEMPO_PASPOR)),
									date('d-m-Y',strtotime($row->JATUH_TEMPO_UANG_MUKA)),
									date('d-m-Y',strtotime($row->JATUH_TEMPO_PELUNASAN)),
									date('d-m-Y',strtotime($row->JATUH_TEMPO_BERKAS)),
									date('d-m-Y',strtotime($row->BATAS_WAITING_LIST)),
									$row->HARI,
									$status
			);
		}

		if(isset($record_items))
			$this->output->set_output($this->flexigrid->json_build($records['record_count'],
				$record_items));
		else
			$this->output->set_output('{"page":"1","total":"0","rows":[]}');

    }
    
// ---------------- Validation function ----------------------------------------

    private function validate_form_group_departure(){
        $this->load->library('form_validation');
		
	
		
		$this->form_validation->set_rules('kode_group','Kode Grup','required|xss_clean|prep_for_form');
		$this->form_validation->set_rules('keterangan','Keterangan','xss_clean|prep_for_form');
	    $this->form_validation->set_rules('hari','Jumlah Hari','required|xss_clean|prep_for_form');
        $this->form_validation->set_rules('tgl_keberangkatan_jd','Tanggal Keberangkatan Jeddah','required|xss_clean|prep_for_form|callback_is_date['.$this->input->post('thn_keberangkatan_jd').'-'.$this->input->post('bln_keberangkatan_jd').'-'.$this->input->post('tgl_keberangkatan_jd').']');
        $this->form_validation->set_rules('tgl_keberangkatan_mk','Tanggal Keberangkatan Mekkah','required|xss_clean|prep_for_form|callback_is_date['.$this->input->post('thn_keberangkatan_mk').'-'.$this->input->post('bln_keberangkatan_mk').'-'.$this->input->post('tgl_keberangkatan_mk').']');
        $this->form_validation->set_rules('tgl_jatuh_tempo_paspor','Jatuh Tempo Paspor','required|xss_clean|prep_for_form|callback_is_date['.$this->input->post('thn_jatuh_tempo_paspor').'-'.$this->input->post('bln_jatuh_tempo_paspor').'-'.$this->input->post('tgl_jatuh_tempo_paspor').']');
        $this->form_validation->set_rules('tgl_jatuh_tempo_uang_muka','Jatuh Tempo Uang muka','required|xss_clean|prep_for_form|callback_is_date['.$this->input->post('thn_jatuh_tempo_uang_muka').'-'.$this->input->post('bln_jatuh_tempo_uang_muka').'-'.$this->input->post('tgl_jatuh_tempo_uang_muka').']');
        $this->form_validation->set_rules('tgl_jatuh_tempo_pelunasan','Jatuh Tempo Pelunasan','required|xss_clean|prep_for_form|callback_is_date['.$this->input->post('thn_jatuh_tempo_pelunasan').'-'.$this->input->post('bln_jatuh_tempo_pelunasan').'-'.$this->input->post('tgl_jatuh_tempo_pelunasan').']');
        $this->form_validation->set_rules('tgl_jatuh_tempo_berkas','Jatuh Tempo Berkas','required|xss_clean|prep_for_form|callback_is_date['.$this->input->post('thn_jatuh_tempo_berkas').'-'.$this->input->post('bln_jatuh_tempo_berkas').'-'.$this->input->post('tgl_jatuh_tempo_berkas').']');
        $this->form_validation->set_rules('tgl_batas_waiting_list','Batas Waiting List','required|xss_clean|prep_for_form|callback_is_date['.$this->input->post('thn_batas_waiting_list').'-'.$this->input->post('bln_batas_waiting_list').'-'.$this->input->post('tgl_batas_waiting_list').']');
        $this->form_validation->set_rules('status','Status','required|xss_clean|prep_for_form');
		
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
		$this->load->model('group_departure');       
        if(is_null($this->group_departure->get_group_departure($this->input->post('group_departure_id'))) ){
			
			
			return true;
		}
		else{
			if(strpos(current_url(),'edit') == true){
			$url = explode('/',current_url());
			if(strcmp($url[count($url)-1],$this->input->post('group_departure_id'))==0){
			return true;
			}
			else{
			$this->form_validation->set_message('is_exist', 'group_departure_id sudah ada');
			return false;
			}
			
			}
			else{
			$this->form_validation->set_message('is_exist', 'group_departure_id sudah ada');
			return false;
			}
			
		}
	}
	
	public function is_date($value,$date){
	
		$array = explode('-',$date);
	
		if(count($array)==3){
		
		$array[0] = intval($array[0]);
		$array[1] = intval($array[1]);
		$array[2] = intval($array[2]);

			// if(is_long($array[1]) && is_long($array[2]) && is_long($array[0])){
				
				if(checkdate($array[1],$array[2],$array[0])){
				
					return true;
				}
				else{
					$this->form_validation->set_message('is_date', '%s tidak sesuai');
					return false;
				}
			// }
		}
		$this->form_validation->set_message('is_date', '%s tidak sesuai');
		return false;
		
	}
	
// ---------------- loader view function ---------------------------------------

    private function load_form_group_departure($group_departure_id=""){
        
        $this->load->model('group_departure_model');

        
        $values = (array)$this->group_departure_model->get_group($group_departure_id)->row();
			
        if(empty($values)){
            
            $content['KODE_GROUP'] = $this->input->post('kode_group');
            $content['KETERANGAN'] = $this->input->post('keterangan');
            $content['TANGGAL_KEBERANGKATAN_MK'] = $this->input->post('tgl_keberangkatan_mk');
            $content['TANGGAL_KEBERANGKATAN_JD'] = $this->input->post('tgl_keberangkatan_jd');
            $content['JATUH_TEMPO_PASPOR'] = $this->input->post('jatuh_tempo_paspor');
            $content['JATUH_TEMPO_UANG_MUKA'] = $this->input->post('jatuh_tempo_uang_muka');
            $content['JATUH_TEMPO_PELUNASAN'] = $this->input->post('jatuh_tempo_pelunasan');
            $content['JATUH_TEMPO_BERKAS'] = $this->input->post('jatuh_tempo_berkas');
            $content['BATAS_WAITING_LIST'] = $this->input->post('batas_waiting_list');
            $content['HARI'] = $this->input->post('hari');
            $content['TANGGAL_KEBERANGKATAN_JD'] = $this->input->post('thn_keberangkatan_jd').'-'.$this->input->post('bln_keberangkatan_jd').'-'.$this->input->post('tgl_keberangkatan_jd');
            $content['TANGGAL_KEBERANGKATAN_MK'] = $this->input->post('thn_keberangkatan_mk').'-'.$this->input->post('bln_keberangkatan_mk').'-'.$this->input->post('tgl_keberangkatan_mk');
            $content['JATUH_TEMPO_PASPOR'] = $this->input->post('thn_jatuh_tempo_paspor').'-'.$this->input->post('bln_jatuh_tempo_paspor').'-'.$this->input->post('tgl_jatuh_tempo_paspor');
            $content['JATUH_TEMPO_UANG_MUKA'] = $this->input->post('thn_jatuh_tempo_uang_muka').'-'.$this->input->post('bln_jatuh_tempo_uang_muka').'-'.$this->input->post('tgl_jatuh_tempo_uang_muka');
            $content['JATUH_TEMPO_PELUNASAN'] = $this->input->post('thn_jatuh_tempo_pelunasan').'-'.$this->input->post('bln_jatuh_tempo_pelunasan').'-'.$this->input->post('tgl_jatuh_tempo_pelunasan');
            $content['JATUH_TEMPO_BERKAS'] = $this->input->post('thn_jatuh_tempo_berkas').'-'.$this->input->post('bln_jatuh_tempo_berkas').'-'.$this->input->post('tgl_jatuh_tempo_berkas');
            $content['BATAS_WAITING_LIST'] = $this->input->post('thn_batas_waiting_list').'-'.$this->input->post('bln_batas_waiting_list').'-'.$this->input->post('tgl_batas_waiting_list');
            $content['STATUS'] = 1;
			$content['type'] = "Ditambahkan";
       }
       else{
           
		   $content = $values;
		   $content['type'] = "Diubah";
                     
       }
	   
	     if($this->session->userdata('notification')==true){
		   $content['notification'] = "true";
		   $this->session->unset_userdata('notification');
	   }

      
     $contents['content'] = $this->load->view('admin/form_master_group',$content,true);
     
       $contents['added_js']="";
      $this->load->view('admin/front',$contents);
    }
    
    


// ---------------- Database handler function ----------------------------------
    private function form_group_departureDB($action,$currentgroup_departure_id=""){
        $this->load->model('group_departure_model');
        $this->load->model('log_model');
        $group_departure = $this->group_departure_model->get_group_berdasarkan_id($this->input->post('group_departure_id'))->row(); 
	
        if(is_null($group_departure)){
        $group_departure = array();
        }
            $group_departure['KODE_GROUP'] = $this->input->post('kode_group');
            $group_departure['KETERANGAN'] = $this->input->post('keterangan');
            $group_departure['TANGGAL_KEBERANGKATAN_MK'] = $this->input->post('tgl_keberangkatan_mk');
            $group_departure['TANGGAL_KEBERANGKATAN_JD'] = $this->input->post('tgl_keberangkatan_jd');
            $group_departure['JATUH_TEMPO_PASPOR'] = $this->input->post('jatuh_tempo_paspor');
            $group_departure['JATUH_TEMPO_UANG_MUKA'] = $this->input->post('jatuh_tempo_uang_muka');
            $group_departure['JATUH_TEMPO_PELUNASAN'] = $this->input->post('jatuh_tempo_pelunasan');
            $group_departure['JATUH_TEMPO_BERKAS'] = $this->input->post('jatuh_tempo_berkas');
            $group_departure['BATAS_WAITING_LIST'] = $this->input->post('batas_waiting_list');
            $group_departure['HARI'] = $this->input->post('hari');
            $group_departure['TANGGAL_KEBERANGKATAN_JD'] = $this->input->post('thn_keberangkatan_jd').'-'.$this->input->post('bln_keberangkatan_jd').'-'.$this->input->post('tgl_keberangkatan_jd');
            $group_departure['TANGGAL_KEBERANGKATAN_MK'] = $this->input->post('thn_keberangkatan_mk').'-'.$this->input->post('bln_keberangkatan_mk').'-'.$this->input->post('tgl_keberangkatan_mk');
            $group_departure['JATUH_TEMPO_PASPOR'] = $this->input->post('thn_jatuh_tempo_paspor').'-'.$this->input->post('bln_jatuh_tempo_paspor').'-'.$this->input->post('tgl_jatuh_tempo_paspor');
            $group_departure['JATUH_TEMPO_UANG_MUKA'] = $this->input->post('thn_jatuh_tempo_uang_muka').'-'.$this->input->post('bln_jatuh_tempo_uang_muka').'-'.$this->input->post('tgl_jatuh_tempo_uang_muka');
            $group_departure['JATUH_TEMPO_PELUNASAN'] = $this->input->post('thn_jatuh_tempo_pelunasan').'-'.$this->input->post('bln_jatuh_tempo_pelunasan').'-'.$this->input->post('tgl_jatuh_tempo_pelunasan');
            $group_departure['JATUH_TEMPO_BERKAS'] = $this->input->post('thn_jatuh_tempo_berkas').'-'.$this->input->post('bln_jatuh_tempo_berkas').'-'.$this->input->post('tgl_jatuh_tempo_berkas');
            $group_departure['BATAS_WAITING_LIST'] = $this->input->post('thn_batas_waiting_list').'-'.$this->input->post('bln_batas_waiting_list').'-'.$this->input->post('tgl_batas_waiting_list');
			$group_departure['STATUS'] = $this->input->post('status');
			
        if($action =="insert"){
            $this->group_departure_model->add_group($group_departure);
			$this->session->set_userdata('notification',true);
			$log = "Menambah grup keberangkatan baru :".$group_departure['KODE_GROUP'];
			$this->log_model->log(null, null, $this->session->userdata('id_user'), $log);
			
        }
        elseif($action =="update"){
            $this->group_departure_model->update_group($currentgroup_departure_id,$group_departure);
			$this->session->set_userdata('notification',true);
			$log = "Mengubah grup keberangkatan dengan id ".$currentgroup_departure_id.' ('.$group_departure['KODE_GROUP'].')';
			$this->log_model->log(null, null, $this->session->userdata('id_user'), $log);
			
        }
    }


}
?>
