<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		if($this->session->userdata('id_user') == NULL)
			redirect(site_url()."/admin/login");


	}

	function index()
	{
		show_404();
	}
	
	
	
	
	function pay($id_akun,$kode_registrasi)
	{
		// LOAD LIBRARY, SESSION DAN MODEL
		$this->load->library('form_validation');
		$this->load->model('packet_model');
		$this->load->model('room_packet_model');
		$this->load->model('room_type_model');
		$this->load->model('room_availability_model');
		$this->load->model('jamaah_candidate_model');
		$this->load->model('payment_model');
		$this->load->model('group_departure_model');
		
	
		
		// PROSES QUERY
		$data_packet = $this->packet_model->get_packet_byAcc($id_akun, $kode_registrasi);
		foreach($data_packet->result() as $row)
		{
			$id_packets = $row->ID_PACKET;
		}
		
		$data_jamaah_maningtis = $this->jamaah_candidate_model->get_total_jamaah($id_akun, $kode_registrasi, array('JASA_TAMBAHAN !='=>0));
		
		 $data_pay_uangmuka = $this->payment_model->query_payment("select * from payment_view where ID_ACCOUNT = '".$id_akun."' AND KODE_REGISTRASI = '".$kode_registrasi."' AND ID_PACKET = '".$id_packets."' AND JENIS_PEMBAYARAN = '1'");
		$data_pay_lunas = $this->payment_model->query_payment("select * from payment_view where ID_ACCOUNT = '".$id_akun."' AND KODE_REGISTRASI = '".$kode_registrasi."' AND ID_PACKET = '".$id_packets."' AND JENIS_PEMBAYARAN = '2'");
		$data_pay_tax = $this->payment_model->query_payment("select * from payment_view where ID_ACCOUNT = '".$id_akun."' AND KODE_REGISTRASI = '".$kode_registrasi."' AND ID_PACKET = '".$id_packets."' AND JENIS_PEMBAYARAN = '3'");
		$data_total_jamaah = $this->jamaah_candidate_model->get_total_jamaah($id_akun, $kode_registrasi);
		

		$data['id_akun']= $id_akun;
		$data['kode_registrasi']= $kode_registrasi;
		
		
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
		
		
		// CARI DATA UANG MUKA
		if($data_pay_uangmuka->result() != NULL)
		{
			foreach($data_pay_uangmuka->result() as $row)
			{
				$data['status_dp'] = $row->TIPE_STATUS;
				$data['jumlah_dp'] = $this->cek_ribuan($row->JUMLAH_KAMAR);
				$data['jumlah_dp2'] = $row->JUMLAH_KAMAR;
				$data['css_dp'] = "sudah";
			}
		}else{
			$data['status_dp'] = "-";
			$data['jumlah_dp'] = 0;
			$data['jumlah_dp2'] = 0;
			$data['css_dp'] = "belum";
		}
		
		// CARI DATA TAX
		if($data_pay_tax->result() != NULL)
		{
			foreach($data_pay_tax->result() as $row)
			{
				$data['status_tax_pay'] = $row->STATUS;
				$data['status_tax'] = $row->TIPE_STATUS;
				$data['jumlah_tax'] = $this->cek_ribuan($row->JUMLAH_KAMAR);
				$data['jumlah_tax2'] = $row->JUMLAH_KAMAR;
				$data['css_tax'] = "sudah";
			}
		}else{
			$data['status_tax_pay'] = 0;
			$data['status_tax'] = "-";
			$data['jumlah_tax'] = 0;
			$data['jumlah_tax2'] = 0;
			$data['css_tax'] = "belum";
		}
		
		
		// CARI DATA PELUNASAN
		if($data_pay_lunas->result() != NULL)
		{
			foreach($data_pay_lunas->result() as $row)
			{
				$data['status_lunas_pay'] = $row->STATUS;
				$data['status_lunas'] = $row->TIPE_STATUS;
				$data['jumlah_lunas'] = $this->cek_ribuan($row->JUMLAH_KAMAR);
				$data['jumlah_lunas2'] = $row->JUMLAH_KAMAR;
				$data['css_lunas'] = "sudah";
			}
		}else{
			$data['status_lunas_pay'] = 0;
			$data['status_lunas'] = "-";
			$data['jumlah_lunas'] = 0;
			$data['jumlah_lunas2'] = 0;
			$data['css_lunas'] = "belum";
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
						$cwb = $row->CHILD_WITH_BED;
						$cnb = $row->CHILD_NO_BED;
						$infant = $row->INFANT;
						$id_packet = $row->ID_PACKET;
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
												<td align="center"><h4>'.$nama_group.' - '.$nama_program.' / '.$jenis_kamar.'</h4></td>
												<td align="center"><h4>'.$nama_calon.'</h4></td>
												<td align="center"><h4>'.$this->cek_ribuan($total_harga_kamar).' $</h4></td>
												<td align="center"><h4>'.$this->cek_ribuan($total_harga_kamar).' $</h4></td>
											</tr>';
				}
			}
		}
		
		$data['hitung_jasa_maningtis'] = $hitung_jasa_maningtis;
		
		$data['hitung_total_maningtis'] = $hitung_total_maningtis;
		
		$data['jumlah_calon_jamaah'] = $data_total_jamaah->num_rows();
		$hitung_dp_calon_jamaah_1 = $data['jumlah_calon_jamaah'] * 1100;
		$data['hitung_dp_calon_jamaah'] = $this->cek_ribuan($hitung_dp_calon_jamaah_1);
		$data['total_biaya'] =  $hitung_total_maningtis + $biaya_harga_kamar;
		$data['total_pelunasan'] = $this->cek_ribuan($data['total_biaya'] - $hitung_dp_calon_jamaah_1);
		$data['total_biaya2'] = $this->cek_ribuan($data['total_biaya']);
		$data['total_pay'] = $data['jumlah_dp2'] + $data['jumlah_lunas2'];
		$data['total_pay_cek'] = $this->cek_ribuan($data['total_pay']);
		
		if(($data['total_pay'] == $data['total_biaya'] || $data['total_pay'] > $data['total_biaya']) && ($data['jumlah_tax2'] == '700000' || $data['jumlah_tax2'] > '700000') && $data['status_lunas_pay'] == 1 && $data['status_tax_pay'] == 1 )
		{
			$data['total_status'] = "Complete";
			$data['css_total'] = "sudah";
		}else{
			$data['total_status'] = "Pending";
			$data['css_total'] = "belum";
		}
					
		$data['notifikasi'] = null;
		if($this->session->userdata('sukses') == 'true'){
			$data['notifikasi'] = '<div id="message-green">
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="green-left">Konfirmasi pembayaran berhasil. Periksa Email Anda </td>
						<td class="green-right"><a class="close-green"><img src="'.base_url().'images/table/icon_close_green.gif"   alt="" /></a></td>
					</tr>
				</table><br>
			</div>';
			$this->session->unset_userdata('sukses');
		}
		
		$data['error_file'] = '';
		if($this->session->userdata('upload_file') != '')
		{
			$data['error_file'] = '<div id="message-blue">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="blue-left">'.$this->session->userdata('upload_file').'</td>
								<td class="blue-right"><a class="close-blue"><img src="'.base_url().'images/table/icon_close_blue.gif"   alt="" /></a></td>
							</tr>
						</table><br>
					</div>';
			
		}
				
		$data['content'] = $this->load->view('admin/form_payment',$data,true);
		$this->load->view('admin/front',$data);
		$this->session->unset_userdata('upload_file');
	}
	
	function do_send()
	{
		$this->load->library('form_validation');
		$this->load->model('payment_model');
		$this->load->model('packet_model');
		$this->load->model('log_model');
		
		$log = "Melakukan Konfirmasi pembayaran";
		
		if ($this->cek_validasi() == FALSE){
			$this->pay($this->input->post('id_akun'),$this->input->post('kode_registrasi'));
		}
		else{
			
			// cek foto
			$cek_foto = $_FILES['foto']['name'];
			if($cek_foto != "")
			{
				if(!is_dir('./images/upload/bukti_transfer'))
				{
					mkdir('./images/upload/bukti_transfer',0777);
				}
				
				// Upload Foto
				$config['upload_path'] = './images/upload/bukti_transfer/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
				$config['max_size']	= '5000';
				$config['encrypt_name']	= TRUE;
				
				$this->load->library('upload', $config);
				
				if(!$this->upload->do_upload('foto'))
				{
					$this->session->set_userdata('upload_file', $this->upload->display_errors("<p>Error Scan Bukti Transfer : ", "</p>"));
					$data_file = NULL;
					$valid_file = FALSE;
				
				}else{
					$data_file = $this->upload->data();
					$valid_file = TRUE;
				}
				
				$bukti = $data_file['file_name'];
			
			} else {
				$bukti = NULL;
				$valid_file = TRUE;
			}

			// update table
			 $id_akun = $this->input->post("id_akun");
			 $kode_registrasi = $this->input->post("kode_registrasi");
			// $email_ses = $this->session->userdata("email");
			
			$nama_rekening = null;
			$tgl_transfer = null;
			$bank_pengirim = null;
			$tgl_transfer_fix = null;
			
			$jumlah = $this->input->post('nominal');
			$metode = $this->input->post('metode');
			$catatan = $this->input->post('catatan');
			
			// get id packet
			$data_packet = $this->packet_model->get_packet_status($id_akun, $kode_registrasi);
		
			if($data_packet->result() != NULL)
			{
				foreach($data_packet->result() as $row)
				{
					$id_packet = $row->ID_PACKET;
				}
			}
			
			if($this->input->post('tipe_pembayaran')=='0'){
				$status = 1;
				$melalui = 0;
			}
			else{
				$nama_rekening = $this->input->post('nama_rekening');
				$tgl_transfer = $this->input->post('tgl_transfer');
				$bank_pengirim = $this->input->post('bank');
				$status = 0;
				// filter tanggal transfer
				$pecah_tanggal = explode("/", $tgl_transfer);
				$tgl_transfer_fix = $pecah_tanggal[2]."-".$pecah_tanggal[1]."-".$pecah_tanggal[0];
				$melalui = $this->input->post('transfer');
			}
			
			
			$data = array(
				'ID_ACCOUNT' => $id_akun,
				'KODE_REGISTRASI' => $kode_registrasi,
				'ID_PACKET' => $id_packet,
				'JENIS_PEMBAYARAN' => $metode,
				'ATAS_NAMA' => $nama_rekening,
				'BANK_PENGIRIM' => $bank_pengirim,
				'TANGGAL_TRANSFER' => $tgl_transfer_fix,
				'JUMLAH_KAMAR' => $jumlah,
				'BUKTI_TRANSFER' => $bukti,
				'CATATAN' => $catatan,
				'STATUS' => $status,
				'BAYAR_MELALUI' => $melalui,
				'TANGGAL_ENTRI' => date("Y-m-d H:i:s"),
				'TANGGAL_UPDATE' => date("Y-m-d H:i:s")
				);
			
			
			
			// $konfirmasi = array(
				// 'NAMA_REKENING' => $nama_rekening,
				// 'TGL_TRANSFER' => $tgl_transfer_fix,
				// 'NAMA_BANK' => $bank_pengirim,
				// 'JUMLAH' => $jumlah,
				// 'JENIS' => $metode,
				// 'CATATAN' => $catatan,
				// 'BUKTI_FILE' => $bukti,
				// 'EMAIL_SES' => $email_ses
				// );
			
			
			
			if($valid_file && $this->input->post('tipe_pembayaran')=='1')
			{
				//jika upload file scan berhasil
				$this->session->set_userdata('sukses','true');
				$this->log_model->log(null,null, $this->session->userdata('id_user'), $log);
				$this->payment_model->insert_payment($data);
				// $this->send_email($konfirmasi);
				
				redirect(site_url().'/admin/data_akun/');
				
			}
			else if($this->input->post('tipe_pembayaran')=='0'){
				
				$this->log_model->log(null,null, $this->session->userdata('id_user'), $log);
				$this->payment_model->insert_payment($data);
				// $this->send_email($konfirmasi);
				
				redirect(site_url().'/admin/data_akun/');
			
			}
			else{
				$this->pay($id_akun,$kode_registrasi);
			}
		}
	}

	function cek_validasi() 
	{
		$this->load->library('form_validation');
		
		//setting rules
		
		
		
		
		
		$this->form_validation->set_rules('tipe_pembayaran','Tipe Pembayaran','required|xss_clean|prep_for_form');
		if($this->input->post('tipe_pembayaran')=='1'){
		$this->form_validation->set_rules('nama_rekening','Atas Nama','required|xss_clean|prep_for_form');
		$this->form_validation->set_rules('tgl_transfer','Tgl. Transfer','required|xss_clean|prep_for_form');
		$this->form_validation->set_rules('bank','Nama Bank','required|xss_clean|prep_for_form');
		$this->form_validation->set_rules('transfer','Tujuan Transfer','required|xss_clean|prep_for_form');
		}
		
		$this->form_validation->set_rules('nominal','Jumlah','required|numeric|xss_clean|prep_for_form');
		$this->form_validation->set_rules('metode','Jenis Pembayaran','required|xss_clean|prep_for_form');
		$this->form_validation->set_rules('catatan','Catatan','xss_clean|prep_for_form');
		
		
		$this->form_validation->set_message('required', 'Kolom <strong>%s</strong> harus diisi !');
		$this->form_validation->set_message('min_length', 'Kolom <strong>%s</strong> minimal 3 karakter!');
		$this->form_validation->set_message('numeric', 'Kolom <strong>%s</strong> harus berupa angka !');
		//$this->form_validation->set_error_delimiters('<li class="error">', '</li>');

		return $this->form_validation->run();
    }
	
	function cek_dropdown($value){
		$this->load->library('form_validation');
		if($value==0){
				$this->form_validation->set_message('cek_dropdown', 'Pilih salah satu <strong>%s</strong> !');
				return FALSE;
		}else
				return TRUE;
    }
	
	function cek_ribuan($txt)
	{
		$pecah = number_format($txt);
		$ubah = str_replace(",", ".", $pecah);
		
		return $ubah;
	}
	
	function send_email($konfirmasi)
	{
		// LOAD LIBRARY, SESSION DAN MODEL
		$this->load->library('form_validation');
		$this->load->library('email');
		$this->load->library('parser');
		
		$this->load->model('packet_model');
		$this->load->model('room_packet_model');
		$this->load->model('room_type_model');
		$this->load->model('room_availability_model');
		$this->load->model('jamaah_candidate_model');
		$this->load->model('payment_model');
		$this->load->model('group_departure_model');
		
		// $id_akun = $this->session->userdata("id_account");
		// $kode_registrasi = $this->session->userdata("kode_registrasiistrasi");
		// $data['nama_user'] = $this->session->userdata('nama');
		
		
		// LOAD DATA INPUTAN KONFIRMASI
		$data['NAMA_REKENING'] = $konfirmasi['NAMA_REKENING'];
		$data['TGL_TRANSFER'] = date("d F Y", strtotime($konfirmasi['TGL_TRANSFER']));
		$data['NAMA_BANK'] = $konfirmasi['NAMA_BANK'];
		$data['JUMLAH'] = $this->cek_ribuan($konfirmasi['JUMLAH']);
		$data['JENIS'] = $konfirmasi['JENIS'];
		$data['CATATAN'] = $konfirmasi['CATATAN'];
		$data['BUKTI_FILE'] = $konfirmasi['BUKTI_FILE'];
		$data['EMAIL_SES'] = $konfirmasi['EMAIL_SES'];
		
		// PROSES QUERY
		$data_packet = $this->packet_model->get_packet_byAcc($id_akun, $kode_registrasi);
		$data_jamaah = $this->jamaah_candidate_model->query_jamaah("select * from jamaah_candidate where ID_ACCOUNT = '".$id_akun."' AND KODE_REGISTRASI = '".$kode_registrasi."' AND REQUESTED_NAMA != '0' AND REQUESTED_NAMA != '' AND STATUS_KANDIDAT != '0'");
		$data_jamaah_maningtis = $this->jamaah_candidate_model->query_jamaah("select * from jamaah_candidate where ID_ACCOUNT = '".$id_akun."' AND KODE_REGISTRASI = '".$kode_registrasi."' AND JASA_TAMBAHAN != '0' AND STATUS_KANDIDAT != '0'");
		$data_total_jamaah = $this->jamaah_candidate_model->get_total_jamaah($id_akun, $kode_registrasi);
		
		
		// CARI TOTAL PEMAKAI JASA NAMA PASPOR
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
						$cwb = $row->CHILD_WITH_BED;
						$cnb = $row->CHILD_NO_BED;
						$infant = $row->INFANT;
						$id_packet = $row->ID_PACKET;
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
	
		
		// PROSES KIRIM EMAIL KONFIRMASI
		$config['protocol'] = 'mail';
		$config['mailtype'] = 'html';

		$this->email->initialize($config);
		
		$htmlMessage =  $this->parser->parse('email_payment', $data, true);
		$data['subject'] = "Konfirmasi Pembayaran";		
		
		$this->email->from('noreply@umrahkamilah.com', 'Kamilah Wisata Muslim');
		$this->email->to($data['EMAIL_SES']);
		$this->email->subject('Konfirmasi Pembayaran');
		$this->email->message($htmlMessage);

		$this->email->send();
		//echo $data['EMAIL_SES'];	
		//$content = $this->load->view('email_payment',$data);
	}
	
	
       
}

/* End of file Check_availability.php */
/* Location: ./application/controllers/payment.php */