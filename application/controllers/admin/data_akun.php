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

    public function edit_accounts($accounts_id=null){
       
		if(is_null($accounts_id)){
			redirect('accounts/view_list_accounts');

		}
          if($this->validate_form_accounts()==true){
            $this->form_accountsDB("update",$accounts_id);
			redirect('accounts/view_list_accounts');
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
				redirect('accounts/view_list_accounts');
            }
            else{
                $this->load_form_accounts();
            }
    }

    public function delete_accounts($accounts_id){
		$this->load->model('accounts_model');
        
		if(is_null($accounts_id)){
			redirect('accounts/view_list_accounts');

		}
		
		$value = $this->accounts_model->get_group_berdasarkan_id($accounts_id)->result();
        
		if(!empty($value)){
            
			 $this->accounts_model->delete_group($accounts_id);
        }       
		redirect('accounts/view_list_accounts');
       
    }
    

    public function view_list_accounts(){
         $this->load->helper('flexigrid');

		
		$colModel['no'] = array('No',30,TRUE,'center',0);
		$colModel['edit'] = array('Edit',50,FALSE,'center',0);
		$colModel['delete'] = array('Delete',50,FALSE,'center',0);
		$colModel['paket'] = array('Paket',50,FALSE,'center',0);
		$colModel['jamaah'] = array('Tambah Jamaah',100,FALSE,'center',0);
		
		$colModel['NAMA_USER'] = array('Nama User',100,TRUE,'center',2);
		$colModel['EMAIL'] = array('Email',100,TRUE,'center',2);
		$colModel['TELP'] = array('Telepon',100,TRUE,'center',2);
		$colModel['MOBILE'] = array('Mobile',100,TRUE,'center',2);
		$colModel['ALAMAT'] = array('Alamat',100,TRUE,'center',2);
		$colModel['KOTA'] = array('Kota',100,TRUE,'center',2);
		




		$gridParams = array(
			'width' => 'auto',
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


			function edit(hash){
				if(confirm('Anda yakin ingin merubah data ini?')){
					location.href='".site_url()."/admin/data_akun/edit_accounts/'+hash;
				}
			}			
			
			function add_jamaah(hash,hash1){
					location.href='".site_url()."/admin/data_jamaah/do_daftar/'+hash+'/'+hash1;
			}
			
			function paket(hash){
				window.open('".site_url()."/admin/data_jamaah/view_paket/'+hash,'Paket','width=600,height=210,left=400,top=100,screenX=400,screenY=100');
			}

			function hapus(hash){
				if(confirm('Anda yakin ingin menghapus data ini?')){
					location.href='".site_url()."/admin/data_akun/delete_accounts/'+hash;
				}
			}
             
			</script>
			";
                $contents['content'] = $this->load->view('admin/data_jamaah',$content,true);
                
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
                    
			
			$edit = '<img alt="Edit"  style="cursor:pointer" src="'.base_url().'images/flexigrid/edit.jpg" onclick="edit(\''.$row->ID_ACCOUNT.'\')">';
			$delete = '<img alt="Delete"  style="cursor:pointer" src="'.base_url().'images/flexigrid/delete.jpg" onclick="hapus(\''.$row->ID_ACCOUNT.'\')">';
			$add_jamaah = '<img alt="Tambah Jamaah"  style="cursor:pointer" src="'.base_url().'images/flexigrid/delete.jpg" onclick="add_jamaah(\''.$row->ID_ACCOUNT.'\',\''.$row->KODE_REGISTRASI.'\')">';
			$paket = '<img alt="Paket"  style="cursor:pointer" src="'.base_url().'images/flexigrid/delete.jpg" onclick="paket(\''.$row->ID_ACCOUNT.'\')">';
                 
			
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

    private function load_form_accounts($accounts_id=""){
        
        $this->load->model('accounts_model');
		$this->load->model('province_model');
		$this->load->model('group_departure_model');
		$this->load->model('program_class_model');
		$this->load->model('room_type_model');
        
        $account = (array)$this->accounts_model->get_data_account($accounts_id)->row();
		
		$province = $this->province_model->get_all_province();
		
		$province_options['0'] = '-- Pilih Propinsi --';
		foreach($province->result() as $row){
				$province_options[$row->ID_PROPINSI] = $row->NAMA_PROPINSI;
		}
			
		$group = $this->group_departure_model->get_all_group();
		$program = $this->program_class_model->get_all_program();
		$room = $this->room_type_model->get_all_roomType();

		$group_options['0'] = '-- Pilih Group --';
		foreach($group->result() as $row){
				$group_options[$row->ID_GROUP] = $row->KODE_GROUP;
		}
		
		$program_options['0'] = '-- Pilih Program --';
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
            
       }
       else{
           
		   $content = $account;
                     
       }

      
     $contents['content'] = $this->load->view('admin/form_registration',$content,true);
     
       $contents['added_js']="";
      $this->load->view('admin/front',$contents);
    }
    
    


// ---------------- Database handler function ----------------------------------
    private function form_accountsDB($action,$currentaccounts_id=""){
        $this->load->model('accounts_model');
        $accounts = $this->accounts_model->get_accounts($this->input->post('accounts_id')); 
	
        if(is_null($accounts)){
        $accounts = new stdClass();
        }
        $accounts->accounts_id = $this->input->post('accounts_id');
        $accounts->PASSWORD = $this->input->post('password');
        $accounts->ID_M_ROLE = $this->input->post('role');
		$accounts->AKTIF = $this->input->post('aktif');
       
        if($action =="insert"){
            $this->accounts_model->add_accounts($accounts);
        }
        elseif($action =="update"){
			$accounts->accounts_id_CURRENT = $currentaccounts_id;
            $this->accounts_model->edit_accounts($accounts);
        }
    }

// ---------------- Private function -------------------------------------------
	private function cek_session(){
		if(!$this->session->accountsdata('accounts_id')){
			redirect('login');
		}
		if($this->session->accountsdata('id_role')!=1){
			redirect('menu_utama');
		}
	}
}
?>
