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
class Berkas extends CI_Controller{

    public function  __construct() {
        parent::__construct();
		if($this->session->userdata('id_user') == NULL)
			redirect("admin/login");
    }

    function index()
	{    
		$this->fisik();
    }

	function fisik()
	{
        $this->load->helper('flexigrid');
		
		$colModel['no'] = array('No',30,TRUE,'center',0);	
		$colModel['c.NAMA_LENGKAP'] = array('Nama Lengkap',180,TRUE,'center',2);
		$colModel['c.KODE_REGISTRASI'] = array('Kode Registrasi',110,TRUE,'center',1);
		$colModel['a.NAMA_USER'] = array('Nama Registrasi',150,TRUE,'center',1);
		$colModel['b.PASPOR'] = array('Paspor',50,TRUE,'center',0);
		$colModel['b.NPWP'] = array('NPWP',50,TRUE,'center',0);
		$colModel['b.BUKU_KUNING'] = array('B. Kuning',50,TRUE,'center',0);
		$colModel['b.FOTO'] = array('Foto',50,TRUE,'center',0);
		$colModel['b.BUKU_NIKAH'] = array('B. Nikah',50,TRUE,'center',0);
		$colModel['b.KK'] = array('KK',50,TRUE,'center',0);
		$colModel['b.AKTE_LAHIR'] = array('Akte Lahir',55,TRUE,'center',0);
		$colModel['b.IJAZAH'] = array('Ijazah',50,TRUE,'center',0);
		$colModel['b.KTP'] = array('KTP',50,TRUE,'center',0);
		$colModel['b.CATATAN_KESEHATAN'] = array('Cat. Kesehatan',80,TRUE,'center',0);

		$gridParams = array(
			'width' => '1190',
			'height' => 285,
			'rp' => 20,
			'rpOptions' => '[5,10,15,20,25,40,100]',
			'pagestat' => 'Menampilkan: {from} hingga {to} dari {total} hasil.',
			'blockOpacity' => 0.5,
			'title' => 'Daftar Berkas Fisik Jamaah',
			'showTableToggleBtn' => false
		);

		/* $buttons[] = array('separator');
		$buttons[] = array('Tambah','add','js');
        $buttons[] = array('separator');
		$buttons[] = array('Deaktif','delete','js');
		$buttons[] = array('separator'); */

		$content['js_grid'] = build_grid_js('flex1',site_url('admin/berkas/grid_fisik'),$colModel,'no','asc',$gridParams,NULL);
		$content['added_js'] =
			"<script type='text/javascript'>
			
			function ubah_fisik(id, cand, flag, berkas){
				if(confirm('Anda yakin ingin mengubah status berkas fisik ini?'))
				{
					$.ajax({
						type: 'POST',
						url: '".site_url('/admin/berkas/status_fisik/')."',
						data: 'id='+id+'&cand='+cand+'&flag='+flag+'&berkas='+berkas,
						success: function(data){
						$('#flex1').flexReload();
						alert(data);
						}
					});
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

    function grid_fisik()
	{            
		$this->load->library('flexigrid');
        $this->load->model('berkas_model');

		$valid_fields = array('b.ID_BERKAS', 'b.ID_CANDIDATE', 'a.ID_ACCOUNT', 'c.NAMA_LENGKAP', 'c.KODE_REGISTRASI', 'a.NAMA_USER', 'b.PASPOR', 'b.NPWP', 'b.BUKU_KUNING', 'b.FOTO', 'b.BUKU_NIKAH', 'b.KK', 'b.AKTE_LAHIR', 'b.IJAZAH', 'b.KTP', 'b.CATATAN_KESEHATAN');
		$this->flexigrid->validate_post('c.ID_CANDIDATE','asc',$valid_fields);
		
	
		$records = $this->berkas_model->fisik_grid();
		$this->output->set_header($this->config->item('json_header'));
		
		$no = 0;
		foreach ($records['records']->result() as $row)
		{
			$sudah = '<img alt="Sudah" style="cursor:pointer" src="'.base_url().'images/front/tersedia.png" onclick="ubah_fisik(\''.$row->ID_BERKAS.'\',\''.$row->ID_CANDIDATE.'\',\'0\',';
			$belum = '<img alt="Belum" style="cursor:pointer" src="'.base_url().'images/shared/hapus.png" onclick="ubah_fisik(\''.$row->ID_BERKAS.'\',\''.$row->ID_CANDIDATE.'\',\'1\',';	 
			
			$record_items[] = array(
									$row->ID_CANDIDATE,
									$no = $no+1,
									$row->NAMA_LENGKAP,
									$row->KODE_REGISTRASI,
									$row->NAMA_USER,
									($row->PASPOR === '1') ? $sudah.'\'1\')">' : $belum.'\'1\')">',
									($row->NPWP === '1') ? $sudah.'\'2\')">' : $belum.'\'2\')">',
									($row->BUKU_KUNING === '1') ? $sudah.'\'3\')">' : $belum.'\'3\')">',
									($row->FOTO === '1') ? $sudah.'\'4\')">' : $belum.'\'4\')">',
									($row->BUKU_NIKAH === '1') ? $sudah.'\'5\')">' : $belum.'\'5\')">',
									($row->KK === '1') ? $sudah.'\'6\')">' : $belum.'\'6\')">',
									($row->AKTE_LAHIR === '1') ? $sudah.'\'7\')">' : $belum.'\'7\')">',
									($row->IJAZAH === '1') ? $sudah.'\'8\')">' : $belum.'\'8\')">',
									($row->KTP === '1') ? $sudah.'\'9\')">' : $belum.'\'9\')">',
									($row->CATATAN_KESEHATAN === '1') ? $sudah.'\'10\')">' : $belum.'\'10\')">'
							);
		}

		if(isset($record_items))
			$this->output->set_output($this->flexigrid->json_build($records['record_count'],
				$record_items));
		else
			$this->output->set_output('{"page":"1","total":"0","rows":[]}');

    }
    
	function status_fisik()
	{
		/**
			$id = ID_BERKAS
			$cand = ID_CANDIDATE
			$flag = STATUS BERKAS yang diinginkan
			$berkas = KODE BERKAS
			
			Kode Berkas (sesuai urutan kolom dalam tabel "berkas_fisik") :
			1 = Paspor
			2 = NPWP
			3 = Buku Kuning
			4 = Foto
			5 = Buku Nikah
			6 = KK
			7 = Akte Lahir
			8 = Ijazah
			9 = KTP
			10 = Catatan Kesehatan	
		**/
		
		$id = $this->input->post('id');
		$cand = $this->input->post('cand');
		$flag = $this->input->post('flag');
		$berkas = $this->input->post('berkas');
		
		$this->load->model('berkas_model');
		$this->load->model('log_model');        
		
		$row = $this->berkas_model->get_fisik($id, $cand);
		
		if($row->num_rows() > 0)
		{
			$berkas_fisik = array('1'=>'PASPOR', '2'=>'NPWP', '3'=>'BUKU_KUNING', '4'=>'FOTO', '5'=>'BUKU_NIKAH', '6'=>'KK', '7'=>'AKTE_LAHIR', '8'=>'IJAZAH', '9'=>'KTP', '10'=>'CATATAN_KESEHATAN');
			
			// update status berkas
			$this->berkas_model->ubah_fisik($id, $cand, $flag, $berkas_fisik[$berkas]);
			
			// insert into LOG TABLE
			$log = $this->session->userdata('username')." merubah status berkas ".$berkas_fisik[$berkas]." jamaah dg id_candidate = ".$cand;
			$this->log_model->log(null, null, $this->session->userdata('id_user'), $log);
			
			// set notification message
			$msg = 'Status berkas '.$berkas_fisik[$berkas].' berhasil dirubah!';
			
			$this->output->set_header($this->config->item('ajax_header'));
			$this->output->set_output($msg);
		}
		else
		{
			$msg = "Status berkas gagal dirubah!";
			
			$this->output->set_header($this->config->item('ajax_header'));
			$this->output->set_output($msg);
		}
    }
	
	//----------------------------- BERKAS TOOLKIT ---------------------------------
	
	function toolkit()
	{
        $this->load->helper('flexigrid');
		
		$colModel['no'] = array('No',30,TRUE,'center',0);	
		$colModel['c.NAMA_LENGKAP'] = array('Nama Lengkap',180,TRUE,'center',2);
		$colModel['c.KODE_REGISTRASI'] = array('Kode Registrasi',110,TRUE,'center',1);
		$colModel['a.NAMA_USER'] = array('Nama Registrasi',150,TRUE,'center',1);
		$colModel['c.STATUS_KIRIM_TOOLKIT'] = array('Status Toolkit',80,TRUE,'center',0);

		$gridParams = array(
			'width' => '1190',
			'height' => 285,
			'rp' => 20,
			'rpOptions' => '[5,10,15,20,25,40,100]',
			'pagestat' => 'Menampilkan: {from} hingga {to} dari {total} hasil.',
			'blockOpacity' => 0.5,
			'title' => 'Daftar Berkas Fisik Jamaah',
			'showTableToggleBtn' => false
		);

		/* $buttons[] = array('separator');
		$buttons[] = array('Tambah','add','js');
        $buttons[] = array('separator');
		$buttons[] = array('Deaktif','delete','js');
		$buttons[] = array('separator'); */

		$content['js_grid'] = build_grid_js('flex1',site_url('admin/berkas/grid_toolkit'),$colModel,'no','asc',$gridParams,NULL);
		$content['added_js'] =
			"<script type='text/javascript'>
			
			function ubah_toolkit(id, flag){
				if(confirm('Anda yakin ingin mengubah status berkas toolkit ini?'))
				{
					$.ajax({
						type: 'POST',
						url: '".site_url('/admin/berkas/status_toolkit/')."',
						data: 'id='+id+'&flag='+flag,
						success: function(data){
						$('#flex1').flexReload();
						alert(data);
						}
					});
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

    function grid_toolkit()
	{            
		$this->load->library('flexigrid');
        $this->load->model('berkas_model');

		$valid_fields = array('c.ID_CANDIDATE', 'a.ID_ACCOUNT', 'c.NAMA_LENGKAP', 'c.KODE_REGISTRASI', 'a.NAMA_USER', 'c.STATUS_KIRIM_TOOLKIT');
		$this->flexigrid->validate_post('c.ID_CANDIDATE','asc',$valid_fields);
		
	
		$records = $this->berkas_model->toolkit_grid();
		$this->output->set_header($this->config->item('json_header'));
		
		$no = 0;
		foreach ($records['records']->result() as $row)
		{
			$sudah = '<img alt="Sudah" style="cursor:pointer" src="'.base_url().'images/front/tersedia.png" onclick="ubah_toolkit(\''.$row->ID_CANDIDATE.'\',\'0\')">';
			$belum = '<img alt="Belum" style="cursor:pointer" src="'.base_url().'images/shared/hapus.png" onclick="ubah_toolkit(\''.$row->ID_CANDIDATE.'\',\'1\')">';	 
			
			$record_items[] = array(
									$row->ID_CANDIDATE,
									$no = $no+1,
									$row->NAMA_LENGKAP,
									$row->KODE_REGISTRASI,
									$row->NAMA_USER,
									($row->STATUS_KIRIM_TOOLKIT === '1') ? $sudah : $belum
							);
		}

		if(isset($record_items))
			$this->output->set_output($this->flexigrid->json_build($records['record_count'],
				$record_items));
		else
			$this->output->set_output('{"page":"1","total":"0","rows":[]}');

    }
	
	function status_toolkit()
	{		
		$id = $this->input->post('id');
		$flag = $this->input->post('flag');
		
		$this->load->model('berkas_model');
		$this->load->model('log_model');        
		
		$row = $this->berkas_model->get_candidate($id);
		
		if($row->num_rows() > 0)
		{
			// update status toolkit
			$this->berkas_model->ubah_toolkit($id, $flag);
			
			// insert into LOG TABLE
			$log = $this->session->userdata('username')." merubah status berkas TOOLKIT jamaah dg id_candidate = ".$id;
			$this->log_model->log(null, null, $this->session->userdata('id_user'), $log);
			
			// set notification message
			$msg = 'Status berkas TOOLKIT berhasil dirubah!';
			
			$this->output->set_header($this->config->item('ajax_header'));
			$this->output->set_output($msg);
		}
		else
		{
			$msg = "Status berkas TOOLKIT gagal dirubah!";
			
			$this->output->set_header($this->config->item('ajax_header'));
			$this->output->set_output($msg);
		}
    }
	
}//end of class
?>
