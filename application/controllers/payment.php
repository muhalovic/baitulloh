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
		$total_harga_keseluruhan = $total_harga_kamar + ($meningitis * $data['total_maningtis']);
		$biaya_uang_muka = ($total_jamaah_per_kamar * $uang_muka);
		
		
		// SET OUTPUT
		$data['biaya_pelunasan'] = $this->cek_ribuan($total_harga_keseluruhan - $biaya_uang_muka);
		$data['biaya_uang_muka'] = $this->cek_ribuan($biaya_uang_muka);
		$data['total_harga_keseluruhan'] =  $this->cek_ribuan($total_harga_keseluruhan);
		$data['total_jamaah_per_kamar'] = $total_jamaah_per_kamar;
		$data['list_jamaah'] = $list_jamaah;
		
		$data['content'] = $this->load->view('form_payment',$data,true);
		$this->load->view('front_backup',$data);
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