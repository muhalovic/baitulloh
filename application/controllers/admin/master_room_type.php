<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * Description of master_room_type
 *
 * @author wiwit
 */
class master_room_type extends CI_Controller{

    public function  __construct() {
        parent::__construct();
		if($this->session->userdata('id_user') == NULL)
			redirect(site_url()."/admin/login");
    }


// ------------------------------ index ----------------------------------------

    public function index(){
        
		$this->view_list_room_type();
    }
	
	

// ----------------- Public view -----------------------------------------------

    public function edit_room_type($room_type_id=null){
       
		if(is_null($room_type_id)){
			redirect('master_room_type/view_list_room_type');

		}
          if($this->validate_form_room_type()==true){
            $this->form_room_typeDB("update",$room_type_id);
			redirect('admin/master_room_type/edit_room_type/'.$room_type_id);
            }
            else{
                $this->load_form_room_type($room_type_id);
            }
    }

    // public function view_room_type(){
       // Auth::cekSession('');
         
    // }

    public function add_room_type(){
      
      
        if($this->validate_form_room_type()==true){
                $this->form_room_typeDB("insert");
				
				redirect('admin/master_room_type/add_room_type');
            }
            else{

                $this->load_form_room_type();
            }
    }

    public function delete_room_type(){
		$this->load->model('room_type_model');
		$this->load->model('room_packet_model');
		$this->load->model('room_model');
		$this->load->model('room_availability_model');
		$this->load->model('log_model');
        
		if(!isset($_POST['items'])){
			redirect('admin/master_room_type/view_list_room_type');
		}
		
		$error = '';
		$success = '';
		$room_type_ids = explode(',',$this->input->post('items'));
		
		foreach($room_type_ids as $room_type_id){
		$value = $this->room_type_model->get_roomType($room_type_id)->result();
        
		
		if(!empty($value)){
		$room_paket = $this->room_packet_model->get_room_packet_related_with_room_type($room_type_id)->result();
		$room = $this->room_model->get_room_related_with_room_type($room_type_id)->result();
		$room_availability = $this->room_availability_model->get_room_availability_related_with_room_type($room_type_id)->result();

		
			if(!empty($room_paket)  || !empty($room)  ||  !empty($room_availability)){
			
			if($error != ''){
				$error .= ', ';
			}
				$error .= $value[0]->JENIS_KAMAR;	
			}
			else{
			 $log = "Menghapus tipe kamar dengan id ".$room_type_id.' ('.$value[0]->JENIS_KAMAR.')';
			 $this->room_type_model->delete_room_type($room_type_id);
			 if($success != ''){
				$success .= ', ';
			 }
			 $success .= $value[0]->JENIS_KAMAR;
			 $this->log_model->log(null, null, $this->session->userdata('id_user'), $log);
			}
        }
		
		}
		
		
		$messege = '';
		
		if($success != ''){
			$messege .= 'data tipe kamar : '.$success.' berhasil dihapus';
		}
		if($success != '' && $error != ''){
			$messege .= ' sedangkan ';
		}
		if($error != ''){
			$messege .= 'data tipe kamar : '.$error.' tidak dapat dihapus karena masih digunakan data yang lain';
		}
		
		$this->output->set_header($this->config->item('ajax_header'));
		$this->output->set_output($messege);
       
    }
    

    public function view_list_room_type(){
         $this->load->helper('flexigrid');

		
		$colModel['no'] = array('No',30,TRUE,'center',0);
		$colModel['edit'] = array('Edit',50,FALSE,'center',0);
		
		
		$colModel['JENIS_KAMAR'] = array('Jenis Kamar',100,TRUE,'center',2);
		$colModel['CAPACITY'] = array('Kapasitas',100,TRUE,'center',2);
		$colModel['STATUS'] = array('Status',100,TRUE,'center',2);
		



		$gridParams = array(
			'width' => '1190',
			'height' => 285,
			'rp' => 15,
			'rpOptions' => '[5,10,15,20,25,40]',
			'pagestat' => 'Menampilkan: {from} hingga {to} dari {total} hasil.',
			'blockOpacity' => 0.5,
			'title' => 'Daftar Tipe Kamar',
			'showTableToggleBtn' => false
		);

		$buttons[] = array('separator');
		$buttons[] = array('Tambah','add','js');
        $buttons[] = array('separator');
		$buttons[] = array('Hapus','delete','js');

		$content['js_grid'] = build_grid_js('flex1',base_url()."index.php/admin/master_room_type/json_list_room_type",$colModel,'no','asc',$gridParams,$buttons);
		$content['added_js'] =
			"<script type='text/javascript'>
			function js(com,grid)
			{
				if(com=='Tambah')
				{
					location.href='".site_url('/admin/master_room_type/add_room_type')."';
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
							   url: '".site_url('/admin/master_room_type/delete_room_type')."',
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
					location.href='".site_url()."/admin/master_room_type/edit_room_type/'+hash;
				}
			}

			function hapus(hash){
				if(confirm('Anda yakin ingin menghapus data ini?')){
					location.href='".site_url()."/admin/master_room_type/delete_room_type/'+hash;
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

    public function json_list_room_type(){
                
		$this->load->library('flexigrid');
        $this->load->model('room_type_model');


		$valid_fields = array('ID_ROOM_TYPE');
		$this->flexigrid->validate_post('ID_ROOM_TYPE','asc',$valid_fields);
		
	
		$records = $this->room_type_model->get_grid_room_type();
		$this->output->set_header($this->config->item('json_header'));
		
		$no = 0;
		foreach ($records['records']->result() as $row)
		{
                    
			
			$edit = '<img alt="Edit"  style="cursor:pointer" src="'.base_url().'images/flexigrid/edit.jpg" onclick="edit(\''.$row->ID_ROOM_TYPE.'\')">';
			// $delete = '<img alt="Delete"  style="cursor:pointer" src="'.base_url().'images/flexigrid/delete.jpg" onclick="hapus(\''.$row->ID_PROGRAM.'\')">';
         
		if($row->STATUS == 0){
			$status = 'tidak aktif';
			}
			else{
			$status = 'aktif';
			}
					 
			
			$record_items[] = array(
									$row->ID_ROOM_TYPE,
									$no = $no+1,
									$edit,
									//$delete,
									$row->JENIS_KAMAR,
									$row->CAPACITY,
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

    private function validate_form_room_type(){
        $this->load->library('form_validation');
		
	
		
		$this->form_validation->set_rules('jenis_kamar','Jenis Kamar','required|xss_clean|prep_for_form');
		$this->form_validation->set_rules('capacity','Kapasitas','required|numeric|xss_clean|prep_for_form');
        $this->form_validation->set_rules('status','Status','required|xss_clean|prep_for_form');
		
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');

		$this->form_validation->set_message('required', '<strong>%s</strong> wajib diisi!');
		$this->form_validation->set_message('numeric', '%s hanya boleh angka');
		$this->form_validation->set_message('xss_clean', 'No javascript allowed');
		$this->form_validation->set_message('prep_for_form', '%s tidak sesuai format');

         return $this->form_validation->run();
    }
 

// ---------------- Validation callback function -------------------------------


	
	
	
// ---------------- loader view function ---------------------------------------

    private function load_form_room_type($room_type_id=""){
        
        $this->load->model('room_type_model');

        
        $values = (array)$this->room_type_model->get_roomType($room_type_id)->row();
			
        if(empty($values)){
            
            $content['JENIS_KAMAR'] = $this->input->post('jenis_kamar');
            $content['CAPACITY'] = $this->input->post('capacity');
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

      
     $contents['content'] = $this->load->view('admin/form_master_room_type',$content,true);
     
       $contents['added_js']="";
      $this->load->view('admin/front',$contents);
    }
    
    


// ---------------- Database handler function ----------------------------------
    private function form_room_typeDB($action,$currentroom_type_id=""){
        $this->load->model('room_type_model');
        $this->load->model('log_model');
        $room_type = $this->room_type_model->get_roomType($this->input->post('room_type_id'))->row(); 
	
        if(is_null($room_type)){
        $room_type = array();
        }
            $room_type['JENIS_KAMAR'] = $this->input->post('jenis_kamar');
            $room_type['CAPACITY'] = $this->input->post('capacity');
			$room_type['STATUS'] = $this->input->post('status');
		   
        if($action =="insert"){
            $this->room_type_model->add_room_type($room_type);
			$this->session->set_userdata('notification',true);
			
			$log = "Menambah tipe kamar baru : ".$room_type['JENIS_KAMAR'];
			$this->log_model->log(null, null, $this->session->userdata('id_user'), $log);
        }
        elseif($action =="update"){
            $this->room_type_model->update_room_type($currentroom_type_id,$room_type);
			$this->session->set_userdata('notification',true);
			$log = "Mengubah tipe kamar dengan id ".$currentroom_type_id.' ('.$room_type['JENIS_KAMAR'].')';
			$this->log_model->log(null, null, $this->session->userdata('id_user'), $log);
			
        }
    }

// ---------------- Private function -------------------------------------------
	
}
?>
