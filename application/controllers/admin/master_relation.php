<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * Description of master_relation
 *
 * @author wiwit
 */
class master_relation extends CI_Controller{

    public function  __construct() {
        parent::__construct();
		if($this->session->userdata('id_user') == NULL)
			redirect(site_url()."/login");
    }


// ------------------------------ index ----------------------------------------

    public function index(){
        
		$this->view_list_relation();
    }

// ----------------- Public view -----------------------------------------------

    public function edit_relation($relation_id=null){
       
		if(is_null($relation_id)){
			redirect('master_relation/view_list_relation');

		}
          if($this->validate_form_relation()==true){
            $this->form_relationDB("update",$relation_id);
			redirect('admin/master_relation/edit_relation/'.$relation_id);
            }
            else{
                $this->load_form_relation($relation_id);
            }
    }

    // public function view_relation(){
       // Auth::cekSession('');
         
    // }

    public function add_relation(){
      
      
        if($this->validate_form_relation()==true){
                $this->form_relationDB("insert");
				
				redirect('admin/master_relation/add_relation');
            }
            else{

                $this->load_form_relation();
            }
    }

    public function delete_relation(){
		$this->load->model('relation_model');
		$this->load->model('jamaah_candidate_model');
		$this->load->model('log_model');
        
		if(!isset($_POST['items'])){
			redirect('admin/master_relation/view_list_relation');
		}
		
		$error = '';
		$success = '';
		$relation_ids = explode(',',$this->input->post('items'));
		
		foreach($relation_ids as $relation_id){
		$value = $this->relation_model->get_relation($relation_id)->result();
        
		
		if(!empty($value)){
			$jamaah = $this->jamaah_candidate_model->get_jamaah_related_with_relation($relation_id)->result();
		
		
			if(!empty($jamaah)){
				if($error != ''){
					$error .= ', ';
				}
					$error .= $value[0]->JENIS_RELASI;	
			}
			else{
				$this->relation_model->delete_relation($relation_id);
				if($success != ''){
				$success .= ', ';
				}
					$success .= $value[0]->JENIS_RELASI;
			 
				$log = "Menghapus relasi dengan id ".$relation_id.' ('.$value[0]->JENIS_RELASI.')';
				$this->log_model->log(null, null, $this->session->userdata('id_user'), $log);

			}
        }
		
		}
		
		
		$messege = '';
		
		if($success != ''){
			$messege .= 'data relasi : '.$success.' berhasil dihapus';
		}
		if($success != '' && $error != ''){
			$messege .= ' sedangkan ';
		}
		if($error != ''){
			$messege .= 'data relasi : '.$error.' tidak dapat dihapus karena masih digunakan data yang lain';
		}
	
		
		$this->output->set_header($this->config->item('ajax_header'));
		$this->output->set_output($messege);
       
    }
    

    public function view_list_relation(){
         $this->load->helper('flexigrid');

		
		$colModel['no'] = array('No',30,TRUE,'center',0);
		$colModel['edit'] = array('Edit',50,FALSE,'center',0);
		
		
		$colModel['JENIS_RELASI'] = array('Jenis Relasi',100,TRUE,'center',2);
		$colModel['KETERANGAN'] = array('Keterangan',200,TRUE,'center',2);
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

		$content['js_grid'] = build_grid_js('flex1',base_url()."index.php/admin/master_relation/json_list_relation",$colModel,'no','asc',$gridParams,$buttons);
		$content['added_js'] =
			"<script type='text/javascript'>
			function js(com,grid)
			{
				if(com=='Tambah')
				{
					location.href='".site_url('/admin/master_relation/add_relation')."';
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
							   url: '".site_url('/admin/master_relation/delete_relation')."',
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
					location.href='".site_url()."/admin/master_relation/edit_relation/'+hash;
				}
			}

			function hapus(hash){
				if(confirm('Anda yakin ingin menghapus data ini?')){
					location.href='".site_url()."/admin/master_relation/delete_relation/'+hash;
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

    public function json_list_relation(){
                
		$this->load->library('flexigrid');
        $this->load->model('relation_model');


		$valid_fields = array('ID_RELATION');
		$this->flexigrid->validate_post('ID_RELATION','asc',$valid_fields);
		
	
		$records = $this->relation_model->get_grid_relation();
		$this->output->set_header($this->config->item('json_header'));
		
		$no = 0;
		foreach ($records['records']->result() as $row)
		{
                    
			
			$edit = '<img alt="Edit"  style="cursor:pointer" src="'.base_url().'images/flexigrid/edit.jpg" onclick="edit(\''.$row->ID_RELATION.'\')">';
			// $delete = '<img alt="Delete"  style="cursor:pointer" src="'.base_url().'images/flexigrid/delete.jpg" onclick="hapus(\''.$row->ID_PROGRAM.'\')">';
                 
			if($row->STATUS == 0){
			$status = 'tidak aktif';
			}
			else{
			$status = 'aktif';
			}
			
			$record_items[] = array(
									$row->ID_RELATION,
									$no = $no+1,
									$edit,
									//$delete,
									$row->JENIS_RELASI,
									$row->KETERANGAN,
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

    private function validate_form_relation(){
        $this->load->library('form_validation');
		
	
		
		$this->form_validation->set_rules('jenis_relasi','Jenis Relasi','required|xss_clean|prep_for_form');
		$this->form_validation->set_rules('keterangan','Keterangan','xss_clean|prep_for_form');
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

    private function load_form_relation($relation_id=""){
        
        $this->load->model('relation_model');

        
        $values = (array)$this->relation_model->get_relation($relation_id)->row();
			
        if(empty($values)){
            
            $content['JENIS_RELASI'] = $this->input->post('jenis_relasi');
            $content['KETERANGAN'] = $this->input->post('keterangan');
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

      
     $contents['content'] = $this->load->view('admin/form_master_relation',$content,true);
     
       $contents['added_js']="";
      $this->load->view('admin/front',$contents);
    }
    
    


// ---------------- Database handler function ----------------------------------
    private function form_relationDB($action,$currentrelation_id=""){
        $this->load->model('relation_model');
        $this->load->model('log_model');
        $relation = $this->relation_model->get_relation($this->input->post('relation_id'))->row(); 
	
        if(is_null($relation)){
        $relation = array();
        }
            $relation['JENIS_RELASI'] = $this->input->post('jenis_relasi');
            $relation['KETERANGAN'] = $this->input->post('keterangan');
            $relation['STATUS'] = $this->input->post('status');
		
        if($action =="insert"){
            $this->relation_model->add_relation($relation);
			$this->session->set_userdata('notification',true);
			
			$log = "Menambah relasi baru : ".$relation['JENIS_RELASI'];
			$this->log_model->log(null, null, $this->session->userdata('id_user'), $log);
        }
        elseif($action =="update"){
            $this->relation_model->update_relation($currentrelation_id,$relation);
			$this->session->set_userdata('notification',true);
			$log = "Mengubah relasi dengan id ".$currentrelation_id.' ('.$relation['JENIS_RELASI'].')';
			$this->log_model->log(null, null, $this->session->userdata('id_user'), $log);
			
        }
		
		elseif($action =="delete"){
			$relation = array('STATUS'=>0);
            $this->relation_model->update_relation($currentrelation_id,$relation);
			$this->session->set_userdata('notification',true);
			$log = "Menghapus/deaktif relasi dengan id ".$currentrelation_id;
			$this->log_model->log(null, null, $this->session->userdata('id_user'), $log);
			
        }
    }

// ---------------- Private function -------------------------------------------
	
}
?>
