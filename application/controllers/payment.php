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
						// PENDING DULU ~
						
						$list_jamaah .= '
						<tr height="30">
							<td align="left" >
								<span class="price_list_jamaah">- '.$nama_jamaah.'</span>
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
						
				for($i=1;$i<=($jumlah_jamaah_per_kamar - $hitung_data_jamaah);$i++)
				{
					$list_jamaah .= '
					<tr height="30">
						<td align="left" >
							<span class="price_list_jamaah">
								- <i>Belum Terdaftar, klik <a href="'.site_url().'/biodata/input">disini</a> untuk registrasi</i>
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
		
		$data['content'] = $this->load->view('form_payment',$data,true);
		$this->load->view('front_backup',$data);
	}
	
	function get_payment($jenis_bayar, $total_nominal = NULL, $limit)
	{
		$this->load->model('payment_model');
		
		$id_user = $this->session->userdata("id_account");
		$kode_reg = $this->session->userdata("kode_registrasi");
		
		// GET DATA PAYMENT
		$data_payment = $this->payment_model->get_payment_byJenis_Bayar($id_user, $kode_reg, $jenis_bayar, $limit);
		
		// GET DATA PAYMENT LIMIT 1 (BARIS PERTAMA)
		$data_yg_dibayarkan = $this->payment_model->get_payment_byJenis_Bayar($id_user, $kode_reg, $jenis_bayar, '1');
		$total_bayar_awal = $data_yg_dibayarkan->row()->JUMLAH_KAMAR;
		
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