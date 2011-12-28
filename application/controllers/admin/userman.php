<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * Description of userman
 *
 * @author wiwit
 */
class Userman extends CI_Controller{

    public function  __construct() {
        parent::__construct();
		if($this->session->userdata('id_user') == NULL || $this->session->userdata('id_user') !== '1')
			redirect("admin/login");
    }

    function index()
	{    
		$this->daftar_user();
    }

	function daftar_user()
	{
        $this->load->helper('flexigrid');
		
		$colModel['no'] = array('No',30,TRUE,'center',0);
		$colModel['edit'] = array('Edit',50,FALSE,'center',0);		
		$colModel['u.NAMA_USER_INTERNAL'] = array('Nama',200,TRUE,'center',2);
		$colModel['u.USERNAME'] = array('Username',110,TRUE,'center',1);
		$colModel['k.NAMA_KOTA'] = array('Regional',150,TRUE,'center',1);
		$colModel['r.NAMA_ROLE'] = array('Role',90,TRUE,'center',1);
		$colModel['u.STATUS'] = array('Aktif',50,TRUE,'center',0);

		$gridParams = array(
			'width' => '1190',
			'height' => 285,
			'rp' => 20,
			'rpOptions' => '[5,10,15,20,25,40,100]',
			'pagestat' => 'Menampilkan: {from} hingga {to} dari {total} hasil.',
			'blockOpacity' => 0.5,
			'title' => 'Daftar User Aplikasi',
			'showTableToggleBtn' => false
		);

		$buttons[] = array('separator');
		$buttons[] = array('Tambah','add','js');
        $buttons[] = array('separator');
		/* $buttons[] = array('Deaktif','delete','js');
		$buttons[] = array('separator'); */

		$content['js_grid'] = build_grid_js('flex1',site_url('admin/userman/grid_user'),$colModel,'no','asc',$gridParams,$buttons);
		$content['added_js'] =
			"<script type='text/javascript'>
			function js(com,grid)
			{
				if(com=='Tambah')
				{
					location.href='".site_url('/admin/userman/add_user')."';
				}
			}

			function edit(hash){
				if(confirm('Anda yakin ingin merubah data ini?')){
					location.href='".site_url()."/admin/userman/edit_user/'+hash;
				}
			}
			
			function status(hash, flag){
				if(confirm('Anda yakin ingin mengubah status data ini?')){
					location.href='".site_url()."/admin/userman/status_user/'+hash+'/'+flag;
				}
			}
             
			</script>
			";
			
			
		if($this->session->userdata('notification')==true)
		{
		   $content['notifikasi'] = $this->session->userdata('notification_messege');
		   $this->session->unset_userdata('notification');
		   $this->session->unset_userdata('notification_messege');
		}
		
		if($this->session->userdata('error')==true)
		{
		   $content['error'] = $this->session->userdata('error_messege');
		   $this->session->unset_userdata('error');
		   $this->session->unset_userdata('error_messege');
		}
			
		$contents['content'] = $this->load->view('admin/grid',$content,true);
		$this->load->view('admin/front',$contents);
    }

    function grid_user()
	{            
		$this->load->library('flexigrid');
        $this->load->model('userman_model');

		$valid_fields = array('u.ID_USER', 'u.NAMA_USER_INTERNAL', 'u.USERNAME', 'k.NAMA_KOTA', 'r.NAMA_ROLE', 'u.STATUS');
		$this->flexigrid->validate_post('ID_USER','asc',$valid_fields);
		
	
		$records = $this->userman_model->userman_grid();
		$this->output->set_header($this->config->item('json_header'));
		
		$no = 0;
		foreach ($records['records']->result() as $row)
		{
			$edit = '<img alt="Edit" style="cursor:pointer" src="'.base_url().'images/flexigrid/edit.jpg" onclick="edit(\''.$row->ID_USER.'\')">';
			if( $row->ID_USER == $this->session->userdata("id_user") )
				$status = '-';
			elseif($row->STATUS === '1')
				$status = '<img alt="Aktif" style="cursor:pointer" src="'.base_url().'images/front/tersedia.png" onclick="status(\''.$row->ID_USER.'\',\'0\')">';
			else
				$status = '<img alt="Deaktif" style="cursor:pointer" src="'.base_url().'images/shared/hapus.png" onclick="status(\''.$row->ID_USER.'\',\'1\')">';	 
			
			$record_items[] = array(
									$row->ID_USER,
									$no = $no+1,
									$edit,
									$row->NAMA_USER_INTERNAL,
									$row->USERNAME,
									$row->NAMA_KOTA,
									$row->NAMA_ROLE,
									$status
							);
		}

		if(isset($record_items))
			$this->output->set_output($this->flexigrid->json_build($records['record_count'],
				$record_items));
		else
			$this->output->set_output('{"page":"1","total":"0","rows":[]}');

    }

    function add_user()
	{
        //load model
		$this->load->model('userman_model');
		
		if($this->validate_user()==true)
		{
			//get post variables
			$data['NAMA_USER_INTERNAL'] = $this->input->post('nama');
			$data['USERNAME'] = $this->input->post('username');
			$data['PASSWORD'] = md5($this->input->post('passconf'));
			$data['REGIONAL'] = $this->input->post('regional');
			$data['KETERANGAN'] = $this->input->post('ket');
            $data['ROLE'] = $this->input->post('role');
			$data['STATUS'] = 1;
			
			//insert into table
			$this->userman_model->insert_data($data);
			
			//load log model
			$this->load->model('log_model');
			
			// set notification message
			$username = $data['USERNAME'];
			$this->session->set_userdata(array('notification' => true));
			$this->session->set_userdata(array('notification_messege' => 'Username "'.$username.'" <strong>berhasil</strong> ditambahkan!'));
			// insert into LOG TABLE
			$log = $this->session->userdata('username').' menambah user baru dengan username "'.$username.'"';
			$this->log_model->log(null, null, $this->session->userdata('id_user'), $log);
			
			redirect('admin/userman/');
		}
		else
		{			
			//get data master
			$kota = $this->userman_model->get_kota();
			$role = $this->userman_model->get_role();
			
			//fetch data master
			$opsi_kota[''] = '-- Pilih Regional --';
			foreach($kota->result() as $row) { $opsi_kota[$row->ID_KOTA] = $row->NAMA_KOTA; }
			
			$opsi_role[''] = '-- Pilih Role --';
			foreach($role->result() as $row) { $opsi_role[$row->ID_ROLE] = $row->KETERANGAN; }
			
			//set variables
			$content['opsi_kota'] = $opsi_kota;
			$content['opsi_role'] = $opsi_role;
			$content['is_edit'] = false;
			$content['action'] = 'admin/userman/add_user';
			$contents['content'] = $this->load->view('admin/userman_input',$content,true);
			$this->load->view('admin/front',$contents);
		}
    }
	
	function edit_user($id=null)
	{
		//load model
		$this->load->model('userman_model');
		
		if(is_null($id))
		{
			redirect('admin/userman');
			break;
		}
		
		if($this->validate_user()==true)
		{
			//get post variables
			$data['NAMA_USER_INTERNAL'] = $this->input->post('nama');
			$data['USERNAME'] = $this->input->post('username');
			$data['PASSWORD'] = md5($this->input->post('passconf'));
			$data['REGIONAL'] = $this->input->post('regional');
			$data['KETERANGAN'] = $this->input->post('ket');
            $data['ROLE'] = $this->input->post('role');
			
			//insert into table
			$this->userman_model->update_data($id, $data);
			
			//load log model
			$this->load->model('log_model');
			
			// set notification message
			$username = $data['USERNAME'];
			$this->session->set_userdata(array('notification' => true));
			$this->session->set_userdata(array('notification_messege' => 'Username "'.$username.'" <strong>berhasil</strong> diperbaharui!'));
			// insert into LOG TABLE
			$log = $this->session->userdata('username').' mengubah data user dengan username "'.$username.'"';
			$this->log_model->log(null, null, $this->session->userdata('id_user'), $log);
			
			redirect('admin/userman/');
		}
		else
		{
			//load user data
			$record = $this->userman_model->get_user($id);
			
			if($record->num_rows() > 0)
			{
				foreach($record->result() as $row)
				{
					$content['e_nama'] = $row->NAMA_USER_INTERNAL;
					$content['e_username'] = $row->USERNAME;
					$content['e_pass'] = '';
					$content['e_regional'] = $row->REGIONAL;
					$content['e_ket'] = $row->KETERANGAN;
					$content['e_role'] = $row->ROLE;
				}
				
				//get data master
				$kota = $this->userman_model->get_kota();
				$role = $this->userman_model->get_role();
				
				//fetch data master
				$opsi_kota[''] = '-- Pilih Regional --';
				foreach($kota->result() as $row) { $opsi_kota[$row->ID_KOTA] = $row->NAMA_KOTA; }
				
				$opsi_role[''] = '-- Pilih Role --';
				foreach($role->result() as $row) { $opsi_role[$row->ID_ROLE] = $row->KETERANGAN; }
				
				//set variables
				$content['opsi_kota'] = $opsi_kota;
				$content['opsi_role'] = $opsi_role;
				$content['is_edit'] = true;
				$content['action'] = 'admin/userman/edit_user/'.$id;
				$contents['content'] = $this->load->view('admin/userman_input',$content,true);
				$this->load->view('admin/front',$contents);
			}//end if num_rows
			else
				redirect('admin/userman');
		}
    }

    function status_user($id, $flag)
	{
		$this->load->model('userman_model');
		$this->load->model('log_model');        
		
		$row = $this->userman_model->get_user($id);
		
		if($row->num_rows() > 0)
		{
			// update status user
			$this->userman_model->ubah_status($id, $flag);
			// set notification message
			$username = $row->row()->USERNAME;
			$this->session->set_userdata(array('notification' => true));
			if($flag === '1')	$this->session->set_userdata(array('notification_messege' => 'Username "'.$username.'" berhasil diaktifkan!'));
			else	$this->session->set_userdata(array('notification_messege' => 'Username "'.$username.'" berhasil di deaktif!'));
			// insert into LOG TABLE
			$log = $this->session->userdata('username')." men-deaktif user dg id_user = ".$id;
			$this->log_model->log(null, null, $this->session->userdata('id_user'), $log);
		}
		else
		{
			// set error message
			$this->session->set_userdata(array('error' => true));
			$this->session->set_userdata(array('error_messege' => 'User yang anda deaktif tidak ditemukan dalam sistem!'));
		}
		
		redirect('admin/userman');
    }
    

    
    
// ---------------- Validation function ----------------------------------------

    private function validate_user()
	{
        $this->load->library('form_validation');
		
		$this->form_validation->set_rules('nama','Nama Lengkap','required|xss_clean|prep_for_form');
		$this->form_validation->set_rules('username','Username','required|xss_clean|prep_for_form|callback_cek_username');
		$this->form_validation->set_rules('pass', 'Password', 'required|matches[passconf]|xss_clean|prep_for_form');
		$this->form_validation->set_rules('passconf', 'Konfirmasi Password', 'required|xss_clean|prep_for_form');
		$this->form_validation->set_rules('regional', 'Regional Kota', 'required|xss_clean|prep_for_form');
		$this->form_validation->set_rules('ket','Keterangan','xss_clean|prep_for_form');
        $this->form_validation->set_rules('role','Role','required|xss_clean|prep_for_form');
		
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');

		$this->form_validation->set_message('required', '<strong>%s</strong> wajib diisi!');
		$this->form_validation->set_message('matches', 'Password tidak sama!');
		$this->form_validation->set_message('xss_clean', 'No javascript allowed');
		$this->form_validation->set_message('prep_for_form', '%s tidak sesuai format');

         return $this->form_validation->run();
    }
 

// ---------------- Validation callback function -------------------------------

	//cek apakah username dengan role tertentu sudah dipakai atau belum
	function cek_username($value)
	{
		//load model
		$this->load->model('userman_model');
		
		$user = $value;
		$row = $this->userman_model->get_byusername($user);
		
		if($row->num_rows() > 0 && $this->input->post('is_edit') == false )
		{
			$this->form_validation->set_message('cek_username', '<strong>%s</strong> telah terpakai!');
			return FALSE;
		}
		else
			return TRUE;
	}
	
}//end of class
?>
