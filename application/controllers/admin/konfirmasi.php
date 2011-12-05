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
			elseif($row->STATUS == 1) $status = "<a href='".site_url()."/admin/konfirmasi/cetak_payment/".$row->ID_PAYMENT."'><font color=\"green\">".$row->TIPE_STATUS."</font></a>";
			
			
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
		
		if($data_payment->result() != NULL)
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
	
	function cetak_payment($id_payment)
	{
		$this->load->helper('create_pdf');		
		
		$this->load->model('packet_model');
		$this->load->model('room_packet_model');
		$this->load->model('room_type_model');
		$this->load->model('room_availability_model');
		$this->load->model('jamaah_candidate_model');
		$this->load->model('payment_model');
		$this->load->model('group_departure_model');
		
		$data_payment = $this->payment_model->get_payment_view_ByID($id_payment);
		if($data_payment->result() != NULL)
		{
			foreach($data_payment->result() as $row)
			{
				$data['nama_rekening'] = $row->ATAS_NAMA;
				$data['tgl_transfer'] = date("d F Y", strtotime($row->TANGGAL_TRANSFER));
				$data['nama_bank'] = $row->BANK_PENGIRIM;
				$data['jumlah'] = $this->cek_ribuan($row->JUMLAH_KAMAR);
				$data['catatan'] = $row->CATATAN;
				$data['jenis'] = $row->JENIS_PEMBAYARAN;
				$data['bukti_file'] = $row->BUKTI_TRANSFER;
				$data['nama_user'] = $row->NAMA_USER;
				$data['tipe_status'] = $row->TIPE_STATUS;
				$data['tipe_pembayaran'] = $row->TIPE_PEMBAYARAN;
				
				$id_user = $row->ID_ACCOUNT;
				$kode_reg = $row->KODE_REGISTRASI;
				
				
				// PROSES QUERY
				$data_packet = $this->packet_model->get_packet_byAcc($id_user, $kode_reg);
				$data_jamaah = $this->jamaah_candidate_model->query_jamaah("select * from jamaah_candidate where ID_ACCOUNT = '".$id_user."' AND KODE_REGISTRASI = '".$kode_reg."' AND REQUESTED_NAMA != '0' AND REQUESTED_NAMA != '' AND STATUS_KANDIDAT != '0'");
				$data_jamaah_maningtis = $this->jamaah_candidate_model->query_jamaah("select * from jamaah_candidate where ID_ACCOUNT = '".$id_user."' AND KODE_REGISTRASI = '".$kode_reg."' AND JASA_TAMBAHAN != '0' AND STATUS_KANDIDAT != '0'");
				$data_total_jamaah = $this->jamaah_candidate_model->get_total_jamaah($id_user, $kode_reg);
				
				// HITUNG TOTAL JAMAAH PEMAKAI JASA NAMA PASPOR
				if($data_jamaah->num_rows() > 0)
				{
					$hitung_jasa_nama = $data_jamaah->num_rows();
				}else {
					$hitung_jasa_nama = 0;
				}
				$hitung_total = 20 * $hitung_jasa_nama;
				
				
				// CARI TOTAL PENGGUNA JASA MANINGTIS
				if($data_jamaah_maningtis->num_rows() > 0)
				{
					$hitung_jasa_maningtis = $data_jamaah_maningtis->num_rows();
				}else {
					$hitung_jasa_maningtis = 0;
				}
				$hitung_total_maningtis = 20 * $hitung_jasa_maningtis;
				
				
				// HITUNG TOTAL CALON JAMAAH
				if($data_total_jamaah->num_rows() > 0)
				{
					$total_calon_jamaah = $data_total_jamaah->num_rows();
				}else {
					$total_calon_jamaah = 0;
				}
				
				
				// LOOPING PILIHAN PAKET 
				$data['row_price'] = '';
				$biaya_harga_kamar = 0;
				
				if($data_total_jamaah->result() != NULL)
				{
					foreach($data_total_jamaah->result() as $brs)
					{
						$nama_calon = $brs->NAMA_LENGKAP;
						$id_room_packet_jamaah = $brs->ID_ROOM_PACKET;
						
						if($data_packet->result() != NULL)
						{
							foreach($data_packet->result() as $row)
							{
								$nama_group = $row->KODE_GROUP;
								$nama_program = $row->NAMA_PROGRAM;
								$id_program = $row->ID_PROGRAM;
								$id_group = $row->ID_GROUP;
							}
							
							// CARI NAMA KAMAR
							$data_room_packet = $this->room_packet_model->get_room_packet_byIDroomPack($id_room_packet_jamaah);
							foreach($data_room_packet->result() as $rows)
							{
								$id_room_packet = $rows->ID_ROOM_PACKET;
								$jumlah_kamar = $rows->JUMLAH;
								$id_room_type = $rows->ID_ROOM_TYPE;
								$jenis_kamar = $rows->JENIS_KAMAR;
							}
							
							// CARI TANGGAL JATUH TEMPO DP DAN PELUNASAN
							$data_group = $this->group_departure_model->get_group($id_group);
							foreach($data_group->result() as $brs)
							{
								$data['tgl_dp'] = date('d F Y', strtotime($brs->JATUH_TEMPO_UANG_MUKA));
								$data['tgl_lunas'] = date('d F Y', strtotime($brs->JATUH_TEMPO_PELUNASAN));
							}
							
							// CARI HARGA KAMAR
							$data_kamar_siap = $this->room_availability_model->get_price_room($id_room_type, $id_program, $id_group);
							if($data_kamar_siap->result() != NULL)
							{
								foreach($data_kamar_siap->result() as $rowss)
								{
									$total_harga_kamar = $rowss->HARGA_KAMAR;
								}
							}else{
								$total_harga_kamar = NULL;
							}
							
							$biaya_harga_kamar += $total_harga_kamar;
							
							$data['row_price'] .= '	<tr height="30">
														<td align="left">'.$nama_group.' - '.$nama_program.' / '.$jenis_kamar.'</td>
														<td align="center">'.$nama_calon.'</td>
														<td align="center">'.$this->cek_ribuan($total_harga_kamar).' $</td>
														<td align="center">'.$this->cek_ribuan($total_harga_kamar).' $</td>
													</tr>';
						}
					}
				}
				
				$data['hitung_jasa_nama'] = $hitung_jasa_nama;
				$data['hitung_jasa_maningtis'] = $hitung_jasa_maningtis;
				$data['hitung_total'] = $hitung_total;
				$data['hitung_total_maningtis'] = $hitung_total_maningtis;
				$data['total_biaya'] = $hitung_total + $hitung_total_maningtis + $biaya_harga_kamar;
				$data['total_biaya2'] = $this->cek_ribuan($data['total_biaya']);
				
				
				// set pdf
				$footer = "<div align='right' style='border-top:1px solid #CCC; padding:5px;'>".ucwords($this->session->userdata('username'))." &nbsp; &nbsp; | &nbsp; &nbsp; ".date("d-m-Y H:i:s")."</div>";
				$html = $this->load->view('admin/cetak_payment', $data, TRUE);
				$filename = 'Payment_'.$data['nama_user'].'.pdf';
				
				cetak_pdf($html, $footer, $filename);
			}
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