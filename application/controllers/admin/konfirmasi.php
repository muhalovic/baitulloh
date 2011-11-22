<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Konfirmasi extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		if($this->session->userdata('id_user') == NULL)
			redirect(site_url()."/login");
	}

	function index()
	{
		$this->lihat();
	}
	
	function lihat()
	{
		//call library or helper
		$this->load->library('flexigrid');	
		$this->load->helper('flexigrid');
		$this->load->library('form_validation');		
		
		//call model here	
		$this->load->model('payment_model');
		
		$total_data 	= $this->payment_model->get_total_data();
		$total_data		= ''.$total_data ;
		
		$colModel['no'] 					= array('No',40,TRUE,'center',0);
		$colModel['KODE_REGISTRASI'] 		= array('Kode Registrasi',110,TRUE,'center',0);
		$colModel['ATAS_NAMA'] 				= array('Rek . Atas Nama',110,TRUE,'center',1);
		$colModel['BANK_PENGIRIM'] 			= array('Bank Pengirim',100,TRUE,'center',1);
		$colModel['JUMLAH_KAMAR'] 			= array('Jumlah Transfer',100,TRUE,'center',1);
		$colModel['TANGGAL_TRANSFER'] 		= array('Tanggal Transfer',80,FALSE,'center',0);
		$colModel['BUKTI TRANSFER'] 		= array('Scan Bukti',70,FALSE,'center',0);
		$colModel['CATATAN'] 				= array('Catatan',150,FALSE,'center',1);
		$colModel['TIPE_PEMBAYARAN'] 		= array('Jenis Pembayaran',130,TRUE,'center',1);
		$colModel['TANGGAL_ENTRI']		 	= array('Tgl. Entri',80,FALSE,'center',0);
		$colModel['TIPE_STATUS']			= array('Status',80,FALSE,'center',1);
		
		$gridParams = array(
		'width' => '1190',
		'height' => 300,
		'rp' => 10,
		'rpOptions' => '[5,10,15,20,25,30,'.$total_data.']',
		'pagestat' => 'Menampilkan: {from} sampai {to} dari {total} hasil.',
		'blockOpacity' => 0.5,
		'title' => 'Data Konfirmasi Pembayaran',
		'showTableToggleBtn' => false
		);
		
		$grid_js = build_grid_js('flex1',site_url("/admin/konfirmasi/grid_payment/"),$colModel,'no','asc',$gridParams,null);
		$data['js_grid'] = $grid_js;
		
		$data['added_js'] = "";

		$data['content'] = $this->load->view('admin/data_pembayaran',$data,true);
		$this->load->view('admin/front',$data);		
	}

	function grid_payment() 
	{
		
		//call library or helper
		$this->load->library('flexigrid');	
		$this->load->helper('flexigrid');
		$this->load->library('form_validation');		
		
		//call model here	
		$this->load->model('payment_model');
		
		$valid_fields = array('ATAS_NAMA','BANK_PENGIRIM','JUMLAH_KAMAR', 'CATATAN','TIPE_PEMBAYARAN','TIPE_STATUS');
		$this->flexigrid->validate_post('ID_PAYMENT','desc',$valid_fields);
		
		$records = $this->payment_model->get_grid_all_payment();
		$this->output->set_header($this->config->item('json_header'));
		
		$no = 0;
                
		foreach ($records['records']->result() as $row)
		{
			// filter status
			if($row->STATUS == 0) 
			{
				$status = "<a style='cursor:pointer;' onclick=javascript:show_confirm('".site_url()."/admin/konfirmasi/cek_status/".$row->ID_PAYMENT."/".$row->JENIS_PEMBAYARAN."')><font color=\"red\">".$row->TIPE_STATUS."</a></font>";
			}
			elseif($row->STATUS == 1) $status = "<font color=\"green\">".$row->TIPE_STATUS."</font>";
			
			
			// view bukti transfer
			$file = './images/upload/bukti_transfer/'.$row->BUKTI_TRANSFER;
			if(is_file($file)) 
			{ 
				$gambar = '<a href="'.base_url().'/images/upload/bukti_transfer/'.$row->BUKTI_TRANSFER.'"><img src="'.base_url().'/images/flexigrid/book.png"></a>'; 
			
			} else { $gambar = "-"; }
			
			
			// filter catatan
			if($row->CATATAN == '0') { $catatan = "-"; }
			  else { $catatan = $row->CATATAN; }
			  
			
			// cek format jumlah dolar
			$jumlah = $this->cek_ribuan($row->JUMLAH_KAMAR)." $";
			
		
			// list grid payment
			$record_items[] = array(
				$row->ID_PAYMENT,
				$no = $no+1,
				$row->KODE_REGISTRASI,	
				$row->ATAS_NAMA,
				$row->BANK_PENGIRIM,
				$jumlah,
				date("d M Y", strtotime($row->TANGGAL_TRANSFER)),
				$gambar,
				$catatan,
				$row->TIPE_PEMBAYARAN,
				date("d M Y", strtotime($row->TANGGAL_ENTRI)),
				$status
			);
		}
		
		if(isset($record_items))
			$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
		else
			$this->output->set_output('{"page":"1","total":"0","rows":[]}');			
	}
	
	
	function cek_status($id_payment, $jenis_pembayaran)
	{
		$this->load->model('payment_model');
		$this->load->model('packet_model');
		$this->load->model('program_class_model');
		$this->load->model('group_departure_model');
		$this->load->model('jamaah_candidate_model');
		$this->load->model('accounts_model');
		
		$this->load->library('email');
		$this->load->library('parser');
		
		$data_payment = $this->payment_model->get_all_payment_ByID($id_payment, $jenis_pembayaran);
		
		if($data_payment != NULL)
		{
			foreach($data_payment->result() as $row)
			{
				$id_user_a = $row->ID_ACCOUNT;
				$kode_reg_a = $row->KODE_REGISTRASI;
				
				// CARI DATA PAYMENT YG SUDAH APPROVED
				$data_payment_app = $this->payment_model->get_payment_byAcc_complete($id_user_a, $kode_reg_a);
				if($data_payment_app->num_rows() > 0)
				{
					$valid = FALSE;
/*					echo "<script>alert('testing');</script>";
*/				}else{
					$valid = TRUE;
/*					echo "<script>alert('ok');</script>";
*/				}
				
				// UPDATE STATUS PAYMENT MENJADI APPROVED
				$data_pay = array('STATUS' => 1); 
				$this->payment_model->update_payment($data_pay, $row->ID_PAYMENT);
				
				// UPDATE STATUS PACKET MENJADI 3 = SUDAH PAYMENT
				$data_packet = $this->packet_model->get_packet_byAcc($id_user_a, $kode_reg_a);
				if($data_packet->result() != NULL)
				{
					foreach($data_packet->result() as $rows)
					{
						$id_program = $rows->ID_PROGRAM;
						$id_group = $rows->ID_GROUP;
						
						$data_update = array('STATUS_PESANAN' => 3 ); 
						$this->packet_model->update_packet($data_update, $rows->ID_PACKET);
					}
				}
				
				// GET DATA MASKAPAI
				$data_program = $this->program_class_model->get_program($id_program);
				foreach($data_program->result() as $rows)
				{
					$nama_maskapai = $rows->MASKAPAI;
				}
				
				// UPDATE PAGU
				$data_group = $this->group_departure_model->get_group($id_group);
				foreach($data_group->result() as $rows)
				{
					$pagu_ga = $rows->PAGU_GA;
					$pagu_sv = $rows->PAGU_SV;
				}
				
				// CARI TOTAL CALON JAMAAH
				$data_total_jamaah = $this->jamaah_candidate_model->get_total_jamaah($id_user_a, $kode_reg_a);
				if($data_total_jamaah->num_rows() > 0) { $total_candidate = $data_total_jamaah->num_rows(); }
				  else { $total_candidate = 0; }
				
				if($nama_maskapai == "GARUDA INDONESIA") { $field_group = "PAGU_GA"; $pagu_awal = $pagu_ga; }
				  elseif($nama_maskapai == "SAUDI AIRLINES") { $field_group = "PAGU_SV"; $pagu_awal = $pagu_sv;}
				
				// UPDATE PAGU GROUP
				if($valid)
				{
					if($pagu_ga != '0' || $pagu_sv != '0')
					{
						$data_pagu = array($field_group => ($pagu_awal - $total_candidate));
						$this->group_departure_model->update_group($id_group, $data_pagu);
					}
				}
				
				// GET DATA AKUN
				$akun = $this->accounts_model->get_account($id_user_a, $kode_reg_a)->row();
				
				if($row->JENIS_PEMBAYARAN == '1')
				{
					$jenis = "Uang Muka";
					$harga = $this->cek_ribuan($row->JUMLAH_KAMAR)."$";
				}
				elseif($row->JENIS_PEMBAYARAN == '2')
				{
					$jenis = "Pelunasan";
					$harga = $this->cek_ribuan($row->JUMLAH_KAMAR)."$";
				}
				elseif($row->JENIS_PEMBAYARAN == '3')
				{
					$jenis = "Airport Tax dan Manasik";
					$harga = "Rp. ".$this->cek_ribuan($row->JUMLAH_KAMAR).", ";
				}
				
				// KIRIM EMAIL PEMBERITAHUAN
				$data['subject'] = "Konfirmasi Pembayaran";
				$data['nama_user'] = $akun->NAMA_USER;
				$data['tanggal'] = date("d F Y", strtotime($row->TANGGAL_ENTRI));
				$data['harga'] = $harga;
				$data['tipe_pembayaran'] = $jenis;
				
				$htmlMessage =  $this->parser->parse('admin/email_konfirmasi_payment', $data, true);
				
				$config['protocol'] = 'mail';
				$config['mailtype'] = 'html';
		
				$this->email->initialize($config);
				$this->email->from('noreply@umrahkamilah.com', 'Kamilah Wisata Muslim');
				$this->email->to($akun->EMAIL);
				$this->email->subject('Konfirmasi Pembayaran');
				$this->email->message($htmlMessage);
				$this->email->send();
				
			//	$content = $this->load->view('admin/email_konfirmasi_payment',$data);
				
			}
			
			redirect(site_url()."/admin/konfirmasi");
			
		} else {
				echo "<script>alert('Data Tidak Valid');window.location='".site_url()."/admin/konfirmasi';</script>";
			}
	}
	
	
	function cek_ribuan($txt)
	{
		$pecah = number_format($txt);
		$ubah = str_replace(",", ".", $pecah);
		
		return $ubah;
	}
}//end class

/* End of file /admin/konfirmasi.php */
/* Location: ./application/controllers/admin/konfirmasi.php */