<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * Description of master_program_class
 *
 * @author wiwit
 */
class master_program_class extends CI_Controller{

    public function  __construct() {
        parent::__construct();
		if($this->session->userdata('id_user') == NULL)
			redirect(site_url()."/login");
    }


// ------------------------------ index ----------------------------------------

    public function index(){
        
		$this->view_list_program_class();
    }

// ----------------- Public view -----------------------------------------------

    public function edit_program_class($program_class_id=null){
       
		if(is_null($program_class_id)){
			redirect('master_program_class/view_list_program_class');

		}
          if($this->validate_form_program_class()==true){
            $this->form_program_classDB("update",$program_class_id);
			redirect('admin/master_program_class/edit_program_class/'.$program_class_id);
            }
            else{
                $this->load_form_program_class($program_class_id);
            }
    }

    // public function view_program_class(){
       // Auth::cekSession('');
         
    // }

    public function add_program_class(){
      
      
        if($this->validate_form_program_class()==true){
                $this->form_program_classDB("insert");
				
				redirect('admin/master_program_class/add_program_class');
            }
            else{

                $this->load_form_program_class();
            }
    }

    public function delete_program_class(){
		$this->load->model('program_class_model');
		$this->load->model('packet_model');
		$this->load->model('room_model');
		$this->load->model('room_availability_model');
		$this->load->model('log_model');
        
		if(!isset($_POST['items'])){
			redirect('admin/master_program_class/view_list_program_class');

		}
		
		$error = '';
		$success = '';
		$program_class_ids = explode(',',$this->input->post('items'));
		
		foreach($program_class_ids as $program_class_id){

		$value = $this->program_class_model->get_program($program_class_id)->result();
        
		if(!empty($value)){
		$paket = $this->packet_model->get_packet_related_with_program($program_class_id)->result();
		$room = $this->room_model->get_room_related_with_program($program_class_id)->result();
		$room_availability = $this->room_availability_model->get_room_availability_related_with_program($program_class_id)->result();

		
			if(!empty($paket)  || !empty($room)  ||  !empty($room_availability)){
				if($error != ''){
				$error .= ', ';
				}
				$error .= $value[0]->NAMA_PROGRAM;	
		
			}
			else{
			 $log = "Menghapus program dengan id ".$program_class_id;
			 $this->program_class_model->delete_program($program_class_id);
			 if($success != ''){
				$success .= ', ';
			 }
			 $success .= $value[0]->NAMA_PROGRAM;
			 
			 $this->log_model->log(null, null, $this->session->userdata('id_user'), $log);
			}
        }       
		}
		$messege = '';
		
		if($success != ''){
			$messege .= 'data program : '.$success.' berhasil dihapus';
		}
		if($success != '' && $error != ''){
			$messege .= ' sedangkan ';
		}
		if($error != ''){
			$messege .= 'data program : '.$error.' tidak dapat dihapus karena masih digunakan data yang lain';
		}
		
		$this->output->set_header($this->config->item('ajax_header'));
		$this->output->set_output($messege);
       
       
    }
    

    public function view_list_program_class(){
         $this->load->helper('flexigrid');

		
		$colModel['no'] = array('No',30,TRUE,'center',0);
		$colModel['edit'] = array('Edit',50,FALSE,'center',0);
		// $colModel['delete'] = array('Delete',50,FALSE,'center',0);
		
		$colModel['NAMA_PROGRAM'] = array('Nama Program',100,TRUE,'center',2);
		$colModel['KETERANGAN'] = array('Keterangan',100,TRUE,'center',2);
		$colModel['HOTEL_MAKKAH'] = array('Hotel Makkah',175,TRUE,'center',2);
		$colModel['HOTEL_MADINAH'] = array('Hotel Madinah',175,TRUE,'center',2);
		$colModel['MASKAPAI'] = array('Maskapai',150,TRUE,'center',2);
		$colModel['TRANSPORTASI'] = array('Transportasi',150,TRUE,'center',2);
		



		$gridParams = array(
			'width' => '1190',
			'height' => 285,
			'rp' => 15,
			'rpOptions' => '[5,10,15,20,25,40]',
			'pagestat' => 'Menampilkan: {from} hingga {to} dari {total} hasil.',
			'blockOpacity' => 0.5,
			'title' => 'Daftar Kelas Program',
			'showTableToggleBtn' => false
		);

		$buttons[] = array('separator');
		$buttons[] = array('Tambah','add','js');

        $buttons[] = array('separator');		
		$buttons[] = array('Hapus','delete','js');

		$content['js_grid'] = build_grid_js('flex1',base_url()."index.php/admin/master_program_class/json_list_program_class",$colModel,'no','asc',$gridParams,$buttons);
		$content['added_js'] =
			"<script type='text/javascript'>
			function js(com,grid)
			{
				if(com=='Tambah')
				{
					location.href='".site_url('/admin/master_program_class/add_program_class')."';
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
							   url: '".site_url('/admin/master_program_class/delete_program_class')."',
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
					location.href='".site_url()."/admin/master_program_class/edit_program_class/'+hash;
				}
			}

			function hapus(hash){
				if(confirm('Anda yakin ingin menghapus data ini?')){
					location.href='".site_url()."/admin/master_program_class/delete_program_class/'+hash;
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

    public function json_list_program_class(){
                
		$this->load->library('flexigrid');
        $this->load->model('program_class_model');


		$valid_fields = array('ID_PROGRAM');
		$this->flexigrid->validate_post('ID_PROGRAM','asc',$valid_fields);
		
	
		$records = $this->program_class_model->get_grid_program();
		$this->output->set_header($this->config->item('json_header'));
		
		$no = 0;
		foreach ($records['records']->result() as $row)
		{
                    
			
			$edit = '<img alt="Edit"  style="cursor:pointer" src="'.base_url().'images/flexigrid/edit.jpg" onclick="edit(\''.$row->ID_PROGRAM.'\')">';
			$delete = '<img alt="Delete"  style="cursor:pointer" src="'.base_url().'images/flexigrid/delete.jpg" onclick="hapus(\''.$row->ID_PROGRAM.'\')">';
                 
			
			$record_items[] = array(
									$row->ID_PROGRAM,
									$no = $no+1,
									$edit,
									//$delete,
									$row->NAMA_PROGRAM,
									$row->KETERANGAN,
									$row->HOTEL_MAKKAH,
									$row->HOTEL_MADINAH,
									$row->MASKAPAI,
									$row->TRANSPORTASI
			);
		}

		if(isset($record_items))
			$this->output->set_output($this->flexigrid->json_build($records['record_count'],
				$record_items));
		else
			$this->output->set_output('{"page":"1","total":"0","rows":[]}');

    }
    
// ---------------- Validation function ----------------------------------------

    private function validate_form_program_class(){
        $this->load->library('form_validation');
		
	
		
		$this->form_validation->set_rules('nama_program','Nama Program','required|xss_clean|prep_for_form');
		$this->form_validation->set_rules('keterangan','Keterangan','xss_clean|prep_for_form');
		$this->form_validation->set_rules('hotel_makkah','Hotel Makkah','required|xss_clean|prep_for_form');
		$this->form_validation->set_rules('hotel_madinah','Hotel Madinah','required|xss_clean|prep_for_form');
        $this->form_validation->set_rules('maskapai','Maskapai','required|xss_clean|prep_for_form');
        $this->form_validation->set_rules('transportasi','Transportasi','required|xss_clean|prep_for_form');
        
		
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');

		$this->form_validation->set_message('required', '<strong>%s</strong> wajib diisi!');
		$this->form_validation->set_message('numeric', '%s hanya boleh angka');
		$this->form_validation->set_message('xss_clean', 'No javascript allowed');
		$this->form_validation->set_message('prep_for_form', '%s tidak sesuai format');
		$this->form_validation->set_message('matches', '%s tidak sama dengan verifikasi');

         return $this->form_validation->run();
    }
 

// ---------------- Validation callback function -------------------------------


	
	
	
// ---------------- loader view function ---------------------------------------

    private function load_form_program_class($program_class_id=""){
        
        $this->load->model('program_class_model');

        
        $values = (array)$this->program_class_model->get_program($program_class_id)->row();
			
        if(empty($values)){
            
            $content['NAMA_PROGRAM'] = $this->input->post('nama_program');
            $content['KETERANGAN'] = $this->input->post('keterangan');
            $content['HOTEL_MAKKAH'] = $this->input->post('hotel_makkah');
            $content['HOTEL_MADINAH'] = $this->input->post('hotel_madinah');
            $content['MASKAPAI'] = $this->input->post('maskapai');
            $content['TRANSPORTASI'] = $this->input->post('transportasi');
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

      
     $contents['content'] = $this->load->view('admin/form_master_program_class',$content,true);
     
       $contents['added_js']="";
      $this->load->view('admin/front',$contents);
    }
    
    


// ---------------- Database handler function ----------------------------------
    private function form_program_classDB($action,$currentprogram_class_id=""){
        $this->load->model('program_class_model');
        $this->load->model('log_model');
        $program_class = $this->program_class_model->get_program($this->input->post('program_class_id'))->row(); 
	
        if(is_null($program_class)){
        $program_class = array();
        }
            $program_class['NAMA_PROGRAM'] = $this->input->post('nama_program');
            $program_class['KETERANGAN'] = $this->input->post('keterangan');
            $program_class['HOTEL_MAKKAH'] = $this->input->post('hotel_makkah');
            $program_class['HOTEL_MADINAH'] = $this->input->post('hotel_madinah');
            $program_class['MASKAPAI'] = $this->input->post('maskapai');
            $program_class['TRANSPORTASI'] = $this->input->post('transportasi');
       
        if($action =="insert"){
            $this->program_class_model->add_program($program_class);
			$this->session->set_userdata('notification',true);
			
			$log = "Menambah program baru";
			$this->log_model->log(null, null, $this->session->userdata('id_user'), $log);
        }
        elseif($action =="update"){
            $this->program_class_model->update_program($currentprogram_class_id,$program_class);
			$this->session->set_userdata('notification',true);
			$log = "Mengubah program dengan id ".$currentprogram_class_id;
			$this->log_model->log(null, null, $this->session->userdata('id_user'), $log);
			
        }
    }

// ---------------- Private function -------------------------------------------
	
}
?>
