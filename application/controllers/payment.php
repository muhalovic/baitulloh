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
		
		
		$list_jamaah = '';
		$biaya_harga_kamar = 0;
		
		// CARI DATA JAMAAH
		$data_jamaah = $this->jamaah_candidate_model->get_jamaah_ByIdAcc($id_user, $kode_reg);
		if($data_jamaah->result() != NULL)
		{
			// HITUNG JUMLAH JAMAAH 
			$hitung_jamaah = $data_jamaah->num_rows();
			
			foreach($data_jamaah->result() as $row)
			{
				// CARI DATA TIPE KAMAR
				$data_kamar = $this->room_packet_model->get_room_packet_byIDroomPack($row->ID_ROOM_PACKET);
				$nama_kamar = $data_kamar->row()->JENIS_KAMAR;
				$nama_jamaah = $row->NAMA_LENGKAP;
				$id_room_type = $data_kamar->row()->ID_ROOM_TYPE;
				
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
				
				$biaya_harga_kamar += $harga_kamar;
				
				$list_jamaah .= '
				<tr height="30">
					<td align="center"><h4>'.$nama_group.' - '.$nama_program.' / '.$nama_kamar.'</h4></td>
					<td align="center"><h4>'.$nama_jamaah.'</h4></td>
					<td align="center"><h4>'.$this->cek_ribuan($harga_kamar).' $</h4></td>
					<td align="center"><h4>'.$this->cek_ribuan($harga_kamar).' $</h4></td>
				</tr>';
				
				
			}
		}
		
		
		// CARI TOTAL JAMAAH DI PACKET
		$data_total_jamaah_per_packet = $this->packet_model->sum_jumlah_orang_by_id_account($id_user, $kode_reg);
		$total_jamaah_per_packet = $data_total_jamaah_per_packet->row()->JUMLAH_ORANG;
		
		
		// LOOPING HASIL TOTAL JAMAAH
		$total_jam = '';
		for($i=1;$i<=$total_jamaah_per_packet;$i++)
		{
			$list_jamaah .= '
				<tr height="30">
					<td align="center"><h4>'.$nama_group.' - '.$nama_program.' / - </h4></td>
					<td align="center"><h4><em>belum terdaftar</em></h4></td>
					<td align="center"><h4>xxx $</h4></td>
					<td align="center"><h4>xxx $</h4></td>
				</tr>';
		}
		
		$data['list_jamaah'] = $list_jamaah;
		$data['total_jam'] = $biaya_harga_kamar;
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