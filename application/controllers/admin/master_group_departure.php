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
			redirect('master_group_departure/view_list_group_departure');
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
				redirect('master_group_departure/view_list_group_departure');
            }
            else{
                $this->load_form_group_departure();
            }
    }

    public function delete_group_departure($group_departure_id){
		$this->load->model('group_departure_model');
        
		if(is_null($group_departure_id)){
			redirect('master_group_departure/view_list_group_departure');

		}
		
		$value = $this->group_departure_model->get_group_berdasarkan_id($group_departure_id)->result();
        
		if(!empty($value)){
            
			 $this->group_departure_model->delete_group($group_departure_id);
        }       
		redirect('master_group_departure/view_list_group_departure');
       
    }
    

    public function view_list_group_departure(){
         $this->load->helper('flexigrid');

		
		$colModel['no'] = array('No',30,TRUE,'center',0);
		$colModel['edit'] = array('Edit',50,FALSE,'center',0);
		$colModel['delete'] = array('Delete',50,FALSE,'center',0);
		
		$colModel['KODE_GROUP'] = array('KODE GROUP',100,TRUE,'center',2);
		$colModel['KETERANGAN'] = array('KETERANGAN',100,TRUE,'center',2);
		$colModel['TANGGAL_KEBERANGKATAN_JD'] = array('TANGGAL_KEBERANGKATAN_JD',100,TRUE,'center',2);
		$colModel['TANGGAL_KEBERANGKATAN_MK'] = array('TANGGAL_KEBERANGKATAN_MK',100,TRUE,'center',2);
		$colModel['JATUH_TEMPO_PASPOR'] = array('JATUH_TEMPO_PASPOR',100,TRUE,'center',2);
		$colModel['JATUH_TEMPO_UANG_MUKA'] = array('JATUH_TEMPO_UANG_MUKA',100,TRUE,'center',2);
		$colModel['JATUH_TEMPO_PELUNASAN'] = array('JATUH_TEMPO_PELUNASAN',100,TRUE,'center',2);
		$colModel['JATUH_TEMPO_BERKAS'] = array('JATUH_TEMPO_BERKAS',100,TRUE,'center',2);
		$colModel['BATAS_WAITING_LIST'] = array('BATAS_WAITING_LIST',100,TRUE,'center',2);
		$colModel['PAGU_SV'] = array('PAGU_SV',100,TRUE,'center',2);
		$colModel['PAGU_GA'] = array('PAGU_GA',100,TRUE,'center',2);
		$colModel['HARI'] = array('HARI',100,TRUE,'center',2);
        




		$gridParams = array(
			'width' => 'auto',
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

		$content['js_grid'] = build_grid_js('flex1',base_url()."index.php/admin/master_group_departure/json_list_group_departure",$colModel,'no','asc',$gridParams,$buttons);
		$content['added_js'] =
			"<script type='text/javascript'>
			function js(com,grid)
			{
				if(com=='Tambah')
				{
					location.href='".site_url('/admin/master_group_departure/add_group_departure')."';
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
                 
			
			$record_items[] = array(
									$row->ID_GROUP,
									$no = $no+1,
									$edit,
									$delete,
									$row->KODE_GROUP,
									$row->KETERANGAN,
									date('d-m-Y',strtotime($row->TANGGAL_KEBERANGKATAN_JD)),
									date('d-m-Y',strtotime($row->TANGGAL_KEBERANGKATAN_MK)),
									date('d-m-Y',strtotime($row->JATUH_TEMPO_PASPOR)),
									date('d-m-Y',strtotime($row->JATUH_TEMPO_UANG_MUKA)),
									date('d-m-Y',strtotime($row->JATUH_TEMPO_PELUNASAN)),
									date('d-m-Y',strtotime($row->JATUH_TEMPO_BERKAS)),
									date('d-m-Y',strtotime($row->BATAS_WAITING_LIST)),
									$row->PAGU_SV,
									$row->PAGU_GA,
									$row->HARI
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
		$this->form_validation->set_rules('keterangan','Keterangan','required|xss_clean|prep_for_form');
		$this->form_validation->set_rules('pagu_sv','Pagu Saudi Arabia Airlines','required|xss_clean|prep_for_form');
		$this->form_validation->set_rules('pagu_ga','Pagu Garuda Indonesia Airlines','required|xss_clean|prep_for_form');
        $this->form_validation->set_rules('hari','Jumlah Hari','required|xss_clean|prep_for_form');
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
            $content['PAGU_SV'] = $this->input->post('pagu_sv');
            $content['PAGU_GA'] = $this->input->post('pagu_ga');
            $content['HARI'] = $this->input->post('hari');
            
       }
       else{
           
		   $content = $values;
                     
       }

      
     $contents['content'] = $this->load->view('admin/form_master_group',$content,true);
     
       $contents['added_js']="";
      $this->load->view('admin/front',$contents);
    }
    
    


// ---------------- Database handler function ----------------------------------
    private function form_group_departureDB($action,$currentgroup_departure_id=""){
        $this->load->model('group_departure_model');
        $group_departure = $this->group_departure_model->get_group_departure($this->input->post('group_departure_id')); 
	
        if(is_null($group_departure)){
        $group_departure = new stdClass();
        }
        $group_departure->group_departure_id = $this->input->post('group_departure_id');
        $group_departure->PASSWORD = $this->input->post('password');
        $group_departure->ID_M_ROLE = $this->input->post('role');
		$group_departure->AKTIF = $this->input->post('aktif');
       
        if($action =="insert"){
            $this->group_departure_model->add_group_departure($group_departure);
        }
        elseif($action =="update"){
			$group_departure->group_departure_id_CURRENT = $currentgroup_departure_id;
            $this->group_departure_model->edit_group_departure($group_departure);
        }
    }

// ---------------- Private function -------------------------------------------
	private function cek_session(){
		if(!$this->session->group_departuredata('group_departure_id')){
			redirect('login');
		}
		if($this->session->group_departuredata('id_role')!=1){
			redirect('menu_utama');
		}
	}
}
?>
