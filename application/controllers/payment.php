<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		if($this->session->userdata('email') == NULL)
			redirect(site_url()."/login");

                $this->cekOrder();
	}

	function index()
	{
		$this->front();
	}
	
	function front()
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
		$this->load->model('canceled_candidate_model');
		
		$id_user = $this->session->userdata("id_account");
		$kode_reg = $this->session->userdata("kode_registrasi");
		
		// CARI DATA PAKCET / GROUP / KELAS PROGRAM
		$data_packet = $this->packet_model->get_packet_byAcc($id_user, $kode_reg);
		$id_group = $data_packet->row()->ID_GROUP;
		$nama_group = $data_packet->row()->KODE_GROUP;
		$id_program = $data_packet->row()->ID_PROGRAM;
		$nama_program = $data_packet->row()->NAMA_PROGRAM;
		$id_packet = $data_packet->row()->ID_PACKET;
		
		$list_jamaah = '';
		$total_harga_kamar = 0;
		$total_jamaah_per_kamar = 0;
		$uang_muka = 1100;
		$meningitis = 20;
		$diskon = 70;
		
		// LOOPING DATA BERDASARKAN ROOM PACKET
		$data_room_packet = $this->room_packet_model->get_room_packet_byIDpack($id_packet);
		if($data_room_packet->result() != NULL)
		{
			foreach($data_room_packet->result() as $row)
			{
				$nama_kamar = $row->JENIS_KAMAR;
				$jumlah_jamaah_per_kamar = $row->JUMLAH;
				$id_room_type = $row->ID_ROOM_TYPE;
				$id_room_packet = $row->ID_ROOM_PACKET;
				
				// CARI HARGA KAMAR
				$data_kamar_siap = $this->room_availability_model->get_price_room($id_room_type, $id_program, $id_group);
				if($data_kamar_siap->result() != NULL)
				{
					foreach($data_kamar_siap->result() as $rows)
					{
						$harga_kamar = $rows->HARGA_KAMAR;
					}
				}else{
					$harga_kamar = NULL;
				}
				
				$total_harga_per_tipe_kamar = $jumlah_jamaah_per_kamar * $harga_kamar;
				$total_harga_kamar += $total_harga_per_tipe_kamar;
				$total_jamaah_per_kamar += $jumlah_jamaah_per_kamar;
				
				
				$list_jamaah .= '
				<tr height="30">
					<td align="left" >
						<h4>
							<span class="price_list_packet">
							'.$nama_group.' - '.$nama_program.' - '.$nama_kamar.' (<i>'.$jumlah_jamaah_per_kamar.' Orang</i>)
							</span>
						</h4>
					</td>
					<td align="center"><h4>$ '.$this->cek_ribuan($harga_kamar).'</h4></td>
					<td align="center"><h4>-</h4></td>
					<td align="center"><h4>'.$jumlah_jamaah_per_kamar.' x</h4></td>
					<td align="left">
						<h4>
							<span class="price_list_packet">
								$ '.$this->cek_ribuan($total_harga_per_tipe_kamar).'
							</span>
						</h4>
					</td>
				</tr>';
				
				
				// LOOPING JAMAAH PER KAMAR
				$data_jamaah = $this->jamaah_candidate_model->get_jamaah_byRoomPacket($id_user, $kode_reg, $id_room_packet);
				if($data_jamaah->result() != NULL)
				{
					$hitung_data_jamaah = $data_jamaah->num_rows();
					foreach($data_jamaah->result() as $rows)
					{
						
						$nama_jamaah = $rows->NAMA_LENGKAP;
						
						// PERIKSA DATA PEMBATALAN
						$btl = $this->canceled_candidate_model->get_data_byCandidate($id_user, $kode_reg, $rows->ID_CANDIDATE);
						
						$text_batal = '';
						if($rows->STATUS_KANDIDAT == 0 && $btl->result() != NULL)
						{
							$tgl_batal = date("Y-m-d", strtotime($btl->row()->TANGGAL_PEMBATALAN));
							$tgl_skrg = date("Y-m-d");
							
							$hitung_selisih = (strtotime($tgl_batal) - strtotime($tgl_skrg))/86400;
							
							$text_batal = "<font color=\"red\"><em>( Batal )</em></font>";
							
							if($hitung_selisih > 14 && $hitung_selisih < 30)
							{
								$harga_kamar = $harga_kamar * 25 / 100;
							}
							if($hitung_selisih > 7 && $hitung_selisih < 13)
							{
								$harga_kamar = $harga_kamar * 50 / 100;
							}
							if($hitung_selisih > 3 && $hitung_selisih < 6)
							{
								$harga_kamar = $harga_kamar * 75 / 100;
							}
							if($hitung_selisih > 0 && $hitung_selisih < 3)
							{
								$harga_kamar = $harga_kamar * 100 / 100;
							}
						}
						
						
						$list_jamaah .= '
						<tr height="30">
							<td align="left" >
								<span class="price_list_jamaah">- '.$nama_jamaah.' '.$text_batal.'</span>
							</td>
							<td align="center">-</td>
							<td align="center">$ '.$this->cek_ribuan($harga_kamar).'</td>
							<td align="center">1 x</td>
							<td align="left">
									<span class="price_list_jamaah">
										<i><font color="grey">$ '.$this->cek_ribuan($harga_kamar).'</font></i>
									</span>
							</td>
						</tr>';
					}
				}else{
					$hitung_data_jamaah = 0;
				}
				
				
				// FILETER JIKA SUDAH PAYMENT, LINK INPUT JAMAAH DITUTUP
				$data_packet_approve = $this->packet_model->get_packet_byPayment($id_user, $kode_reg);
				if($data_packet_approve->result() == NULL)
				{
					$text_kosong = ', klik <a href="'.site_url().'/biodata/input">disini</a> untuk registrasi';
				}else{
					$text_kosong = NULL;
				}
				
						
				for($i=1;$i<=($jumlah_jamaah_per_kamar - $hitung_data_jamaah);$i++)
				{
					$list_jamaah .= '
					<tr height="30">
						<td align="left" >
							<span class="price_list_jamaah">
								- <i>Belum Terdaftar'.$text_kosong.'</i>
							</span>
						</td>
						<td align="center">-</td>
						<td align="center">$ '.$this->cek_ribuan($harga_kamar).'</td>
						<td align="center">1 x</td>
						<td align="left">
								<span class="price_list_jamaah">
									<i><font color="grey">$ '.$this->cek_ribuan($harga_kamar).'</font></i>
								</span>
						</td>
					</tr>';
				}
					
			}
			
			
			// JASA MENINGITIS
			$data_meningitis = $this->jamaah_candidate_model->get_jamaah_byManingtis($id_user, $kode_reg);
			if($data_meningitis->num_rows() > 0)
			{
				$data['total_maningtis'] = $data_meningitis->num_rows();
			}else{
				$data['total_maningtis'] = 0;
			}
			
		}

		
		// CARI TANGGAL JATUH TEMPO UANG MUKA DAN PELUNASAN
		$data_group = $this->group_departure_model->get_group($id_group);
		foreach($data_group->result() as $brs)
		{
			$data['tgl_uang_muka'] = date('d F Y', strtotime($brs->JATUH_TEMPO_UANG_MUKA));
			$data['tgl_pelunasan'] = date('d F Y', strtotime($brs->JATUH_TEMPO_PELUNASAN));
		}
		
		
		// VAR MASTER
		$total_harga_keseluruhan = $total_harga_kamar + ($meningitis * $data['total_maningtis']) - $diskon;
		
		
		// RINCIAN UANG MUKA
		$biaya_uang_muka = ($total_jamaah_per_kamar * $uang_muka);
		$rowspan_uangmuka = $this->payment_model->get_payment_byJenis_Bayar($id_user, $kode_reg, '1', '3')->num_rows();
		
		if($rowspan_uangmuka < 2)
		{
			$rowspan_uangmuka = '';
			$looping_uangmuka = $this->get_payment(1, $biaya_uang_muka, '1');
			$looping_uangmuka2 = '';
		}else{
			$rowspan_uangmuka = $rowspan_uangmuka;
			$looping_uangmuka = $this->get_payment(1, $biaya_uang_muka, '1');
			$looping_uangmuka2 = $this->get_payment(1, $biaya_uang_muka, '2');
		}
		
		
		// RINCIAN PELUNASAN
		$biaya_pelunasan = ($total_harga_keseluruhan - $biaya_uang_muka);
		$rowspan_pelunasan = $this->payment_model->get_payment_byJenis_Bayar($id_user, $kode_reg, '2', '3')->num_rows();
		
		if($rowspan_pelunasan < 2)
		{
			$rowspan_pelunasan = '';
			$looping_pelunasan = $this->get_payment(2, $biaya_pelunasan, '1');
			$looping_pelunasan2 = '';
		}else{
			$rowspan_pelunasan = $rowspan_pelunasan;
			$looping_pelunasan = $this->get_payment(2, $biaya_pelunasan, '1');
			$looping_pelunasan2 = $this->get_payment(2, $biaya_pelunasan, '2');
		}
		
		
		// RINCIAN TAX dan MANASIK
		$biaya_manasik = '700000';
		$rowspan_manasik = $this->payment_model->get_payment_byJenis_Bayar($id_user, $kode_reg, '3', '3')->num_rows();
		
		if($rowspan_manasik < 2)
		{
			$rowspan_manasik = '';
			$looping_manasik = $this->get_payment(3, $biaya_manasik, '1');
			$looping_manasik2 = '';
		}else{
			$rowspan_manasik = $rowspan_manasik;
			$looping_manasik = $this->get_payment(3, $biaya_manasik, '1');
			$looping_manasik2 = $this->get_payment(3, $biaya_manasik, '2');
		}
		
		
		// HITUNG TOTAL YG DIBAYARKAN
		$total_b_um = $this->payment_model->get_sum_payment_byJenis_Bayar($id_user, $kode_reg, '1')->row()->JUMLAH_KAMAR;
		$total_b_pe = $this->payment_model->get_sum_payment_byJenis_Bayar($id_user, $kode_reg, '2')->row()->JUMLAH_KAMAR;
		$total_b_ma = $this->payment_model->get_sum_payment_byJenis_Bayar($id_user, $kode_reg, '3')->row()->JUMLAH_KAMAR;
		
		$total_yg_sudah_dibayarkan = $total_b_um + $total_b_pe;
		
		// SET OUTPUT
		$data['looping_uangmuka'] = $looping_uangmuka;
		$data['looping_uangmuka2'] = $looping_uangmuka2;
		$data['rowspan_uangmuka'] = $rowspan_uangmuka;
		
		$data['looping_pelunasan'] = $looping_pelunasan;
		$data['looping_pelunasan2'] = $looping_pelunasan2;
		$data['rowspan_pelunasan'] = $rowspan_pelunasan;
		
		$data['looping_manasik'] = $looping_manasik;
		$data['looping_manasik2'] = $looping_manasik2;
		$data['rowspan_manasik'] = $rowspan_manasik;
		
		$data['biaya_pelunasan'] = $this->cek_ribuan($total_harga_keseluruhan - $biaya_uang_muka);
		$data['biaya_uang_muka'] = $this->cek_ribuan($biaya_uang_muka);
		
		$data['total_jamaah_per_kamar'] = $total_jamaah_per_kamar;
		$data['total_harga_keseluruhan'] =  $this->cek_ribuan($total_harga_keseluruhan);
		$data['total_yg_sudah_dibayarkan'] = $this->cek_ribuan($total_yg_sudah_dibayarkan);
		$data['total_yg_sudah_dibayarkan_rupiah'] = $this->cek_ribuan($total_b_ma);
		
		$data['list_jamaah'] = $list_jamaah;
		
		
		$data['notifikasi'] = null;
		if($this->session->userdata('sukses') == 'true'){
			$data['notifikasi'] = '<div id="message-green">
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="green-left">Konfirmasi pembayaran berhasil. Periksa Email Anda </td>
						<td class="green-right"><a class="close-green">
							<img src="'.base_url().'images/table/icon_close_green.gif"   alt="" /></a>
						</td>
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
								<td class="blue-right"><a class="close-blue">
								<img src="'.base_url().'images/table/icon_close_blue.gif"   alt="" /></a></td>
							</tr>
						</table><br>
					</div>';
			
		}
		
		$data['content'] = $this->load->view('form_payment',$data,true);
		$this->load->view('front_backup',$data);
		$this->session->unset_userdata('upload_file');
	}
	
	function get_payment($jenis_bayar, $total_nominal = NULL, $limit)
	{
		$this->load->model('payment_model');
		
		$id_user = $this->session->userdata("id_account");
		$kode_reg = $this->session->userdata("kode_registrasi");
		
		// GET DATA PAYMENT
		$data_payment = $this->payment_model->get_payment_byJenis_Bayar($id_user, $kode_reg, $jenis_bayar, $limit);
		
		// GET DATA PAYMENT LIMIT 1 (BARIS PERTAMA)
		$data_yg_dibayarkan = $this->payment_model->get_payment_byJenis_Bayar($id_user, $kode_reg, $jenis_bayar, 1);
		if($data_yg_dibayarkan->result() != NULL)
		{
			$total_bayar_awal = $data_yg_dibayarkan->row()->JUMLAH_KAMAR;
		}else{
			$total_bayar_awal = 0;
		}
		
		// VAR LOOPING
		$list_payment = '';
		$total_yg_dibayarkan2 = $total_bayar_awal;
		$total_yg_dibayarkan3 = $total_bayar_awal;

		
		if($data_payment->result() != NULL)
		{
			foreach($data_payment->result() as $row)
			{
				// HITUNG JUMLAH YG DIBAYARKAN
				$total_yg_dibayarkan = $row->JUMLAH_KAMAR + $total_bayar_awal;
				$total_yg_dibayarkan2 += $total_yg_dibayarkan;
				$total_yg_dibayarkan3 += $row->JUMLAH_KAMAR;
				
				// CEK JIKA MANASIK, TIDAK PERLU DI CARI TOTAL BIAYA NYA (FIX 700000)
				if($jenis_bayar == 3 && $limit == '1')
				{
					$total_yg_dibayarkan3 = $row->JUMLAH_KAMAR;
				}
				
				// CEK JIKA LIMIT 1 MAKA YG DITAMPILKAN HANYA 1 BARIS
				if($limit == '2')
				{
					$tr_open = '<tr>';
					$tr_close = '</tr>';
					$total_bayar = $total_yg_dibayarkan3;
				}else{
					$tr_open = '';
					$tr_close = '';
					$total_bayar = $row->JUMLAH_KAMAR;
				}
				
				// CEK STATUS LUNAS, JIKA NOMINAL YG DIBAYARKAN SAMADENGAN/LEBIHDARI TOTAL JENIS PEMBAYARAN
				if($total_nominal != NULL)
				{
					if($total_yg_dibayarkan3 == $total_nominal || $total_yg_dibayarkan3 > $total_nominal)
					{
						$status_lunas = " <i><strong>Lunas</strong></i>";
					}else{
						$status_lunas = "<i>Belum Lunas</i>";
					}
				}else{
					$status_lunas = NULL;
				}
				
				// CEK JENIS BAYAR (RUPIAH / DOLAR)
				if($jenis_bayar == '3')
				{
					$mata_uang = "Rp. ";
				}else{
					$mata_uang = "$ ";
				}
				
				// CEK KEKURANGAN / SELISIH ANTARA YG DIBAYARKAN DENGAN TOTAL JENIS PEMBAYARAN
				$selisih_kekurangan = $total_nominal - $total_bayar;
				
				$list_payment .= $tr_open.'
				<td align="center">'.$mata_uang.$this->cek_ribuan($row->JUMLAH_KAMAR).'</td>
				<td align="center">'.$row->TIPE_STATUS.'</td>
				<td align="center">'.$mata_uang .$this->cek_ribuan($total_bayar).'</td>
				<td align="center">'.$status_lunas.'</td>
				<td align="center">'.$mata_uang .$this->cek_ribuan($selisih_kekurangan).'</td>'.$tr_close;
			}
		}else{
			$list_payment .= '
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"></td>';
		}
		
		return $list_payment;
	}
	
	
	
	function do_send()
	{
		$this->load->library('form_validation');
		$this->load->model('payment_model');
		$this->load->model('packet_model');
		$this->load->model('log_model');
				
		if ($this->cek_validasi() == FALSE){
			$this->front();
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
			$id_user = $this->session->userdata("id_account");
			$kode_reg = $this->session->userdata("kode_registrasi");
			$email_ses = $this->session->userdata("email");
			
			$nama_rekening = $this->input->post('nama_rekening');
			$tgl_transfer = $this->input->post('tgl_transfer');
			$bank_pengirim = $this->input->post('bank');
			$bank_tujuan = $this->input->post('tujuan');
			$jumlah = $this->input->post('nominal');
			$metode = $this->input->post('metode');
			$catatan = $this->input->post('catatan');
			
			// filter tanggal transfer
			$pecah_tanggal = explode("/", $tgl_transfer);
			$tgl_transfer_fix = $pecah_tanggal[2]."-".$pecah_tanggal[1]."-".$pecah_tanggal[0];
			
			// get id packet
			$data_packet = $this->packet_model->get_packet_status($id_user, $kode_reg);
			if($data_packet->result() != NULL)
			{
				foreach($data_packet->result() as $row)
				{
					$id_packet = $row->ID_PACKET;
				}
			}
			
			$data = array(
				'ID_ACCOUNT' => $id_user,
				'KODE_REGISTRASI' => $kode_reg,
				'ID_PACKET' => $id_packet,
				'JENIS_PEMBAYARAN' => $metode,
				'ATAS_NAMA' => $nama_rekening,
				'BANK_PENGIRIM' => $bank_pengirim,
				'BAYAR_MELALUI' => $bank_tujuan,
				'TANGGAL_TRANSFER' => $tgl_transfer_fix,
				'JUMLAH_KAMAR' => $jumlah,
				'BUKTI_TRANSFER' => $bukti,
				'CATATAN' => $catatan,
				'STATUS' => 0,
				'TANGGAL_ENTRI' => date("Y-m-d H:i:s"),
				'TANGGAL_UPDATE' => date("Y-m-d H:i:s")
				);
			
			$konfirmasi = array(
				'NAMA_REKENING' => $nama_rekening,
				'TGL_TRANSFER' => $tgl_transfer_fix,
				'NAMA_BANK' => $bank_pengirim,
				'BANK_TUJUAN' => $bank_tujuan,
				'JUMLAH' => $jumlah,
				'JENIS' => $metode,
				'CATATAN' => $catatan,
				'BUKTI_FILE' => $bukti,
				'EMAIL_SES' => $email_ses
				);
			
			if($valid_file)
			{
				//jika upload file scan berhasil
				$this->session->set_userdata('sukses','true');
				$this->log_model->log($id_user, $kode_reg, NULL, "Melakukan Konfirmasi pembayaran");
				$this->payment_model->insert_payment($data);
				$this->send_email($konfirmasi);
				
				redirect(site_url().'/payment/');
				
			}else{
				$this->front();
			}
		}
	}
	
	
	function cek_validasi() 
	{
		$this->load->library('form_validation');
		
		//setting rules
		$config = array(
				array('field'=>'nama_rekening','label'=>'Atas Nama', 'rules'=>'required'),
				array('field'=>'tgl_transfer','label'=>'Tgl. Transfer', 'rules'=>'required'),
				array('field'=>'bank','label'=>'Nama Bank', 'rules'=>'required'),
				array('field'=>'tujuan','label'=>'Bank Tujuan', 'rules'=>'required'),
				array('field'=>'nominal','label'=>'Jumlah', 'rules'=>'required|numeric'),
				array('field'=>'metode','label'=>'Jenis Pembayaran', 'rules'=>'callback_cek_dropdown'),
				array('field'=>'catatan','label'=>'Catatan', 'rules'=>'min_length[3]'),
			);
		
		$this->form_validation->set_rules($config);
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
	
	
	function send_email($konfirmasi)
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
		
		$id_user = $this->session->userdata("id_account");
		$kode_reg = $this->session->userdata("kode_registrasi");
		$nama_user = $this->session->userdata('nama');
		
		
		// LOAD DATA INPUTAN KONFIRMASI
		$data['NAMA_REKENING'] = $konfirmasi['NAMA_REKENING'];
		$data['TGL_TRANSFER'] = date("d F Y", strtotime($konfirmasi['TGL_TRANSFER']));
		$data['NAMA_BANK'] = $konfirmasi['NAMA_BANK'];
		$data['BANK_TUJUAN'] = $konfirmasi['BANK_TUJUAN'];
		$data['JUMLAH'] = $this->cek_ribuan($konfirmasi['JUMLAH']);
		$data['JENIS'] = $konfirmasi['JENIS'];
		$data['CATATAN'] = $konfirmasi['CATATAN'];
		$data['BUKTI_FILE'] = $konfirmasi['BUKTI_FILE'];
		$data['EMAIL_SES'] = $konfirmasi['EMAIL_SES'];
		
		
		// CARI DATA PAKCET / GROUP / KELAS PROGRAM
		$data_packet = $this->packet_model->get_packet_byAcc($id_user, $kode_reg);
		$id_group = $data_packet->row()->ID_GROUP;
		$nama_group = $data_packet->row()->KODE_GROUP;
		$id_program = $data_packet->row()->ID_PROGRAM;
		$nama_program = $data_packet->row()->NAMA_PROGRAM;
		$id_packet = $data_packet->row()->ID_PACKET;
		
		$row_price = '';
		$total_biaya = 0;
		$meningitis = 20;
		$diskon = 70;
		
		// JASA MENINGITIS
		$data_meningitis = $this->jamaah_candidate_model->get_jamaah_byManingtis($id_user, $kode_reg);
		if($data_meningitis->num_rows() > 0)
		{
			$total_maningtis = $data_meningitis->num_rows();
		}else{
			$total_maningtis = 0;
		}
		
		
		// LOOPING DATA BERDASARKAN ROOM PACKET
		$data_room_packet = $this->room_packet_model->get_room_packet_byIDpack($id_packet);
		if($data_room_packet->result() != NULL)
		{
			foreach($data_room_packet->result() as $row)
			{
				$nama_kamar = $row->JENIS_KAMAR;
				$jumlah_jamaah_per_kamar = $row->JUMLAH;
				$id_room_type = $row->ID_ROOM_TYPE;
				$id_room_packet = $row->ID_ROOM_PACKET;
				
				// CARI HARGA KAMAR
				$data_kamar_siap = $this->room_availability_model->get_price_room($id_room_type, $id_program, $id_group);
				if($data_kamar_siap->result() != NULL)
				{
					foreach($data_kamar_siap->result() as $rows)
					{
						$harga_kamar = $rows->HARGA_KAMAR;
					}
				}else{
					$harga_kamar = NULL;
				}
				
				$total_harga_per_tipe_kamar = $jumlah_jamaah_per_kamar * $harga_kamar;
				$total_biaya += $total_harga_per_tipe_kamar;
		
				$row_price .= '
							<tr height="30">
								<td align="left">'.$nama_group.' - '.$nama_program.' / '.$nama_kamar.'</td>
								<td align="center">'.$jumlah_jamaah_per_kamar.'</td>
								<td align="center">$ '.$this->cek_ribuan($harga_kamar).'</td>
								<td align="center">$ '.$this->cek_ribuan($total_harga_per_tipe_kamar).'</td>
							</tr>';
			}
		}
		
		$data['total_maningtis'] = $total_maningtis;
		$data['hitung_meningitis'] =  $total_maningtis * $meningitis;
		$data['total_biaya'] = $total_biaya + $data['hitung_meningitis'];
		$data['total_biaya2'] = $this->cek_ribuan($data['total_biaya']);
		$data['total_biaya_keseluruhan'] = $this->cek_ribuan($data['total_biaya'] - $diskon);
		$data['nama_user'] = $nama_user;
		$data['row_price'] = $row_price;
		
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
		//$content = $this->load->view('email_payment',$data);
	}
	
		
	function cek_ribuan($txt)
	{
		$pecah = number_format($txt);
		$ubah = str_replace(",", ".", $pecah);
		
		return $ubah;
	}
	
        // cek order packet
        function cekOrder(){
            $this->load->model('packet_model');
            $id_user = $this->session->userdata("id_account");
            $kode_reg = $this->session->userdata("kode_registrasi");

            $packet = $this->packet_model->get_packet_byAcc($id_user, $kode_reg);
            if ($packet->num_rows() < 1)
                    redirect('beranda');
        }
		
}

/* End of file Check_availability.php */
/* Location: ./application/controllers/payment.php */