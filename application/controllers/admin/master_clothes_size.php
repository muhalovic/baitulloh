<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * Description of master_clothes_size
 *
 * @author wiwit
 */
class master_clothes_size extends CI_Controller{

    public function  __construct() {
        parent::__construct();
		if($this->session->userdata('id_user') == NULL)
			redirect(site_url()."/login");
    }


// ------------------------------ index ----------------------------------------

    public function index(){
        
		$this->view_list_clothes_size();
    }

// ----------------- Public view -----------------------------------------------

    public function edit_clothes_size($clothes_size_id=null){
       
		if(is_null($clothes_size_id)){
			redirect('master_clothes_size/view_list_clothes_size');

		}
          if($this->validate_form_clothes_size()==true){
            $this->form_clothes_sizeDB("update",$clothes_size_id);
			redirect('admin/master_clothes_size/edit_clothes_size/'.$clothes_size_id);
            }
            else{
                $this->load_form_clothes_size($clothes_size_id);
            }
    }

    // public function view_clothes_size(){
       // Auth::cekSession('');
         
    // }

    public function add_clothes_size(){
      
      
        if($this->validate_form_clothes_size()==true){
                $this->form_clothes_sizeDB("insert");
				
				redirect('admin/master_clothes_size/add_clothes_size');
            }
            else{

                $this->load_form_clothes_size();
            }
    }

    public function delete_clothes_size(){
		$this->load->model('clothes_size_model');
		$this->load->model('jamaah_candidate_model');
		$this->load->model('log_model');
        
		if(!isset($_POST['items'])){
			redirect('admin/master_clothes_size/view_list_clothes_size');
		}
		
		$error = '';
		$success = '';
		$messege = '';
		$clothes_size_ids = explode(',',$this->input->post('items'));
		
		foreach($clothes_size_ids as $clothes_size_id){
		$value = $this->clothes_size_model->get_clothes_size($clothes_size_id)->result();
        
		
		if(!empty($value)){
		$jamaah = $this->jamaah_candidate_model->get_jamaah_related_with_clothes_size($clothes_size_id)->result();
		
		
			if(!empty($jamaah)){
				if($error != ''){
					$error .= ', ';
				}
					$error .= $value[0]->SIZE;	
			}
			else{
				$this->clothes_size_model->delete_clothes_size($clothes_size_id);
				if($success != ''){
				$success .= ', ';
				}
					$success .= $value[0]->SIZE;
			 
				$log = "Menghapus ukuran pakaian dengan id ".$clothes_size_id.' ('.$value[0]->SIZE.')';
				$this->log_model->log(null, null, $this->session->userdata('id_user'), $log);

			}
        }
		
		}
		
		
		if($success != ''){
			$messege .= 'data ukuran pakaian : '.$success.' berhasil dihapus';
		}
		if($success != '' && $error != ''){
			$messege .= ' sedangkan ';
		}
		if($error != ''){
			$messege .= 'data ukuran pakaian : '.$error.' tidak dapat dihapus karena masih digunakan data yang lain';
		}
	
		
		$this->output->set_header($this->config->item('ajax_header'));
		$this->output->set_output($messege);
       
    }
    

    public function view_list_clothes_size(){
         $this->load->helper('flexigrid');

		
		$colModel['no'] = array('No',30,TRUE,'center',0);
		$colModel['edit'] = array('Edit',50,FALSE,'center',0);
		
		
		$colModel['SIZE'] = array('Ukuran Pakaian',100,TRUE,'center',2);
		$colModel['STATUS'] = array('Status',100,TRUE,'center',2);
		



		$gridParams = array(
			'width' => '1190',
			'height' => 285,
			'rp' => 15,
			'rpOptions' => '[5,10,15,20,25,40]',
			'pagestat' => 'Menampilkan: {from} hingga {to} dari {total} hasil.',
			'blockOpacity' => 0.5,
			'title' => 'Daftar Ukuran Pakaian',
			'showTableToggleBtn' => false
		);

		$buttons[] = array('separator');
		$buttons[] = array('Tambah','add','js');
        $buttons[] = array('separator');
		$buttons[] = array('Hapus','delete','js');

		$content['js_grid'] = build_grid_js('flex1',base_url()."index.php/admin/master_clothes_size/json_list_clothes_size",$colModel,'no','asc',$gridParams,$buttons);
		$content['added_js'] =
			"<script type='text/javascript'>
			function js(com,grid)
			{
				if(com=='Tambah')
				{
					location.href='".site_url('/admin/master_clothes_size/add_clothes_size')."';
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
							   url: '".site_url('/admin/master_clothes_size/delete_clothes_size')."',
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
					location.href='".site_url()."/admin/master_clothes_size/edit_clothes_size/'+hash;
				}
			}

			function hapus(hash){
				if(confirm('Anda yakin ingin menghapus data ini?')){
					location.href='".site_url()."/admin/master_clothes_size/delete_clothes_size/'+hash;
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

    public function json_list_clothes_size(){
                
		$this->load->library('flexigrid');
        $this->load->model('clothes_size_model');


		$valid_fields = array('ID_SIZE');
		$this->flexigrid->validate_post('ID_SIZE','asc',$valid_fields);
		
	
		$records = $this->clothes_size_model->get_grid_clothes_size();
		$this->output->set_header($this->config->item('json_header'));
		
		$no = 0;
		foreach ($records['records']->result() as $row)
		{
                    
			
			$edit = '<img alt="Edit"  style="cursor:pointer" src="'.base_url().'images/flexigrid/edit.jpg" onclick="edit(\''.$row->ID_SIZE.'\')">';
			// $delete = '<img alt="Delete"  style="cursor:pointer" src="'.base_url().'images/flexigrid/delete.jpg" onclick="hapus(\''.$row->ID_PROGRAM.'\')">';
                 
			if($row->STATUS == 0){
			$status = 'tidak aktif';
			}
			else{
			$status = 'aktif';
			}
			
			$record_items[] = array(
									$row->ID_SIZE,
									$no = $no+1,
									$edit,
									//$delete,
									$row->SIZE,
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

    private function validate_form_clothes_size(){
        $this->load->library('form_validation');
		
	
		
		$this->form_validation->set_rules('size','Size','required|xss_clean|prep_for_form');
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

    private function load_form_clothes_size($clothes_size_id=""){
        
        $this->load->model('clothes_size_model');

        
        $values = (array)$this->clothes_size_model->get_clothes_size($clothes_size_id)->row();
			
        if(empty($values)){
            
            $content['SIZE'] = $this->input->post('size');
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

      
     $contents['content'] = $this->load->view('admin/form_master_clothes_size',$content,true);
     
       $contents['added_js']="";
      $this->load->view('admin/front',$contents);
    }
    
    


// ---------------- Database handler function ----------------------------------
    private function form_clothes_sizeDB($action,$currentclothes_size_id=""){
        $this->load->model('clothes_size_model');
        $this->load->model('log_model');
        $clothes_size = $this->clothes_size_model->get_clothes_size($this->input->post('clothes_size_id'))->row(); 
	
        if(is_null($clothes_size)){
        $clothes_size = array();
        }
            $clothes_size['SIZE'] = $this->input->post('size');
            $clothes_size['STATUS'] = $this->input->post('status');
		
        if($action =="insert"){
            $this->clothes_size_model->add_clothes_size($clothes_size);
			$this->session->set_userdata('notification',true);
			
			$log = "Menambah ukuran pakaian baru : ".$clothes_size['SIZE'];
			$this->log_model->log(null, null, $this->session->userdata('id_user'), $log);
        }
        elseif($action =="update"){
            $this->clothes_size_model->update_clothes_size($currentclothes_size_id,$clothes_size);
			$this->session->set_userdata('notification',true);
			$log = "Mengubah relasi dengan id ".$currentclothes_size_id.' ('.$clothes_size['SIZE'].')';
			$this->log_model->log(null, null, $this->session->userdata('id_user'), $log);
			
        }
		
		elseif($action =="delete"){
			$clothes_size = array('STATUS'=>0);
            $this->clothes_size_model->update_clothes_size($currentclothes_size_id,$clothes_size);
			$this->session->set_userdata('notification',true);
			$log = "Menghapus/deaktif relasi dengan id ".$currentclothes_size_id;
			$this->log_model->log(null, null, $this->session->userdata('id_user'), $log);
			
        }
    }

// ---------------- Private function -------------------------------------------
	
}
?>
