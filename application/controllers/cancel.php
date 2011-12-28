<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cancel extends CI_Controller {

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
		$this->load->library('form_validation');
		$this->load->model('canceled_candidate_model');
		$this->load->model('jamaah_candidate_model');
		$this->load->model('room_packet_model');
		$this->load->model('packet_model');
		
		// LOAD SESSION
		$id_account = $this->session->userdata('id_account');
		$kode_reg = $this->session->userdata('kode_registrasi');
		
		// LOAD DATABASE
		$canceled_jamaah = $this->canceled_candidate_model->get_data_canceled($id_account, $kode_reg);
		$candidate_jamaah = $this->jamaah_candidate_model->get_total_jamaah($id_account, $kode_reg);
		
		// LOOPING DATA JAMAAH
		$data_jamaah = '';
		$no = 0;
		if($candidate_jamaah->result() != NULL)
		{
			foreach($candidate_jamaah->result() as $row)
			{
				$disable = '';
				$data_jamaah .= '
                <tr>
                	<td valign="bottom"> 
						<input type="checkbox" name="data_jamaah[]" id="data_jamaah_'.$no.'" value="'.$row->ID_CANDIDATE.'" onchange="CekList(this)" > 
						&nbsp;&nbsp;<strong>'.$row->NAMA_LENGKAP.'</strong></td>
					<td> Keterangan Batal &nbsp;&nbsp;
						<input type="text" name="keterangan_'.$row->ID_CANDIDATE.'" id="keterangan_'.$row->ID_CANDIDATE.'" class="inp-form-text" disabled="disabled" >
					</td>
                </tr>';
				$no++;
			}
			
		}else{
			
			$disable = 'disabled';
			$data_jamaah = '
			<tr>
				<td valign="bottom" colspan="2">&nbsp;&nbsp;&nbsp;<em># Tidak Ada Jamaah yg Aktif</em></td>
			</tr>';
		}
			
		
		$data['data_jamaah'] = $data_jamaah;
		$data['disabled'] = $disable;
		
		
		// CARI DATA PAKCET / GROUP / KELAS PROGRAM
		$data_packet = $this->packet_model->get_packet_byAcc($id_account, $kode_reg);
		$data['nama_group'] = $data_packet->row()->KODE_GROUP;
		$data['nama_program'] = $data_packet->row()->NAMA_PROGRAM;
		$data['adult'] = $data_packet->row()->JUMLAH_ADULT;
		$data['with_bed'] = $data_packet->row()->CHILD_WITH_BED;
		$data['no_bed'] = $data_packet->row()->CHILD_NO_BED;
		$data['infant'] = $data_packet->row()->INFANT;
		$id_packet = $data_packet->row()->ID_PACKET;
		
		
		// CARI DATA KAMAR
		$room = $this->room_packet_model->get_room_packet_byIDpack($id_packet);
		$data['room'] = $room->result();

		
		$data['notifikasi'] = NULL;
		if($this->session->userdata('sukses') == 'jamaah'){
			$data['notifikasi'] = '<div id="message-green">
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="green-left">Pembatalan Keberangkatan Calon Jamaah berhasil. Informasi detail telah dikirim ke Email <strong><u> '.$this->session->userdata('email').'</U></strong>.</td>
						<td class="green-right"><a class="close-green"><img src="'.base_url().'images/table/icon_close_green.gif"   alt="" /></a></td>
					</tr>
				</table><br>
			</div>';
			$this->session->unset_userdata('sukses');
			
		}elseif($this->session->userdata('sukses') == 'packet'){
			
			$data['notifikasi'] = '<div id="message-green">
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="green-left">Pembatalan Paket Keberangkatan. Informasi detail telah dikirim ke Email <strong><u> '.$this->session->userdata('email').'</U></strong>.</td>
						<td class="green-right"><a class="close-green"><img src="'.base_url().'images/table/icon_close_green.gif"   alt="" /></a></td>
					</tr>
				</table><br>
			</div>';
			$this->session->unset_userdata('sukses');
		}
		
		$data['content'] = $this->load->view('cancel_page', $data, TRUE);
		$this->load->view('front_backup', $data);
	}
	
	
	function do_send_jamaah()
	{
		
		// LOAD MODEL DAN LIBRARY
		$this->load->library('form_validation');
		$this->load->library('email');
		$this->load->library('parser');
		$this->load->model('canceled_candidate_model');
		$this->load->model('jamaah_candidate_model');
		$this->load->model('packet_model');
		$this->load->model('log_model');

		
		// LOAD SESSION
		$id_account = $this->session->userdata('id_account');
		$kode_reg = $this->session->userdata('kode_registrasi');
		$email_ses = $this->session->userdata('email');
		$user_ses = $this->session->userdata('nama');
		
		// LOAD DATA INPUT
		$data_jamaah = $this->input->post('data_jamaah');
		$pid_jamaah = '';
		$list_calon = '';
		
		for($i=0; $i<count($data_jamaah); $i++)
		{
			$pid_jamaah .= $data_jamaah[$i]." , ";
			
			// INSERT TABLE CANCELED
			$keterangan = $this->input->post('keterangan_'.$data_jamaah[$i]);
			$data_insert = array(
					'ID_CANDIDATE' => $data_jamaah[$i],
					'ID_ACCOUNT' => $id_account,
					'KODE_REGISTRASI' => $kode_reg,
					'TANGGAL_PEMBATALAN' => date("Y-m-d H:i:s"),
					'KETERANGAN' => $keterangan,
					); 
			$this->canceled_candidate_model->insert_canceled($data_insert);
			
			
			// UPDATE TABLE JAMAAH CANDIDATE
			$data_update = array(
					'STATUS_KANDIDAT' => 0,
					); 
			$this->jamaah_candidate_model->update_jamaah($data_update, $data_jamaah[$i]);
			
			
			// LIST JAMAAH UNTUK DIKIRIM DI EMAIL
			$calon_jamaah = $this->jamaah_candidate_model->get_data_berdasarkan_id_candidate($data_jamaah[$i])->row();
			
			if($calon_jamaah->GENDER == 1) { $gender = "Laki Laki"; } else { $gender = "Perempuan"; }
			
			$list_calon .= "
				  <strong>Nama &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong> ".$calon_jamaah->NAMA_LENGKAP."
				  <br />
				  <strong>Tgl. Lahir &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong>
				  ".$calon_jamaah->TEMPAT_LAHIR.", ".$this->konversi_tanggal($calon_jamaah->TANGGAL_LAHIR)."
				  <br />
				  <strong>Jenis Kelamin&nbsp;&nbsp;&nbsp;:</strong>
				  ".$gender." 
				  <br />
				  <strong>Keterangan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong>
				  ".$keterangan." 
				  <div class=\"borderDashed\"></div>
				";
						
		}
		
		
		// KIRIM EMAIL PEMBERITAHUAN
		$data['subject'] = "Pembatalan Calon Jamaah";
		$data['list_calon'] = $list_calon;
		$data['nama_user'] = ucwords($user_ses);
		
		$htmlMessage =  $this->parser->parse('email_cancel', $data, true);
		
		$config['protocol'] = 'mail';
		$config['mailtype'] = 'html';

		$this->email->initialize($config);
		$this->email->from('noreply@umrahkamilah.com', 'Kamilah Wisata Muslim');
		$this->email->to($email_ses);
		$this->email->subject('Pembatalan Calon Jamaah');
		$this->email->message($htmlMessage);
		$this->email->send();
		
		// BUAT SESSION SUKSES
		$this->session->set_userdata('sukses','jamaah');
		
		// CATAT LOG
		$this->log_model->log($id_account, $kode_reg, NULL, "PEMBATALAN Jamaah .ID : ".$pid_jamaah."");
		
		redirect(site_url()."/cancel");
//		$this->load->view('email_cancel', $data);
	}
	
	
	
	function do_send_packet()
	{
		// LOAD MODEL DAN LIBRARY
		$this->load->library('form_validation');
		$this->load->library('email');
		$this->load->library('parser');
		$this->load->model('canceled_candidate_model');
		$this->load->model('jamaah_candidate_model');
		$this->load->model('packet_model');
		$this->load->model('log_model');

		$log = "Melakukan PEMBATALAN Paket";
		
		// LOAD SESSION
		$id_account = $this->session->userdata('id_account');
		$kode_reg = $this->session->userdata('kode_registrasi');
		$email_ses = $this->session->userdata('email');
		$nama_user_ses = $this->session->userdata('nama');
		
		// LOAD DATABASE
		$data_jamaah = $this->jamaah_candidate_model->get_total_jamaah($id_account, $kode_reg);
		$data['list_calon'] = '';
		$data['nama_user'] = ucwords($nama_user_ses);
		
		if($data_jamaah->result() != NULL)
		{
			// PROSES CANCEL
			foreach($data_jamaah->result() as $row)
			{
				// INSERT TABLE CANCELED
				$keterangan_cancel = "Pembatalan Packet";
				$data_insert = array(
						'ID_CANDIDATE' => $row->ID_CANDIDATE,
						'ID_ACCOUNT' => $this->session->userdata('id_account'),
						'KODE_REGISTRASI' => $this->session->userdata('kode_registrasi'),
						'TANGGAL_PEMBATALAN' => date("Y-m-d H:i:s"),
						'KETERANGAN' => $keterangan_cancel,
						); 
				$this->canceled_candidate_model->insert_canceled($data_insert);
				

				// UPDATE TABLE JAMAAH CANDIDATE
				$data_update = array(
						'STATUS_KANDIDAT' => 0,
						); 
				$this->jamaah_candidate_model->update_jamaah($data_update, $row->ID_CANDIDATE);
				
				
				// KIRIM EMAIL PEMBERITAHUAN
				$data['subject'] = "Pembatalan Calon Jamaah";
				
				if($row->GENDER == 1)
				{
					$gender = "Laki Laki";
				}else{
					$gender = "Perempuan";
				}
				
				$data['list_calon'] .= "
				  <strong>Nama &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong> ".$row->NAMA_LENGKAP."
				  <br />
				  <strong>Tgl Lahir&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong>
				  ".$row->TEMPAT_LAHIR.", ".$this->konversi_tanggal($row->TANGGAL_LAHIR)."
				  <br />
				  <strong>Jenis Kelamin&nbsp;&nbsp;&nbsp;:</strong>
				  ".$gender." 
				  <div class=\"borderDashed\"></div>
				";
			
			}
			
			// UPDATE TABLE JAMAAH CANDIDATE
			$data_packet = $this->packet_model->get_packet_status($id_account, $kode_reg);
			if($data_packet->result() > 0 )
			{
				foreach($data_packet->result() as $rows)
				{
					$data_update_packet = array(
						'STATUS_PESANAN' => 0,
						); 
					  $this->packet_model->update_packet($data_update_packet, $rows->ID_PACKET);
				}
			}
			
			
			
			// CARI DATA PAKCET / GROUP / KELAS PROGRAM
			$data_packet = $this->packet_model->get_packet_byAcc($id_account, $kode_reg);
			$data['nama_group'] = $data_packet->row()->KODE_GROUP;
			$data['nama_program'] = $data_packet->row()->NAMA_PROGRAM;
			$data['adult'] = $data_packet->row()->JUMLAH_ADULT;
			$data['with_bed'] = $data_packet->row()->CHILD_WITH_BED;
			$data['no_bed'] = $data_packet->row()->CHILD_NO_BED;
			$data['infant'] = $data_packet->row()->INFANT;
			$id_packet = $data_packet->row()->ID_PACKET;
			
	
			// KIRIM EMAIL PEMBERITAHUAN
			$htmlMessage =  $this->parser->parse('email_cancel', $data, true);
			
			$config['protocol'] = 'mail';
			$config['mailtype'] = 'html';
	
			$this->email->initialize($config);
			$this->email->from('noreply@umrahkamilah.com', 'Kamilah Wisata Muslim');
			$this->email->to($email_ses);
			$this->email->subject('Pembatalan Calon Jamaah');
			$this->email->message($htmlMessage);
			$this->email->send();
		
		} else { 
			
			$data_packet = $this->packet_model->get_packet_status($id_account, $kode_reg);
			if($data_packet->result() != NULL )
			{
				foreach($data_packet->result() as $rows)
				{
					$datas = $rows->ID_PACKET;
					$data_update_packet = array(
						'STATUS_PESANAN' => 0,
						); 
					$this->packet_model->update_packet($data_update_packet, $row->ID_PACKET);
				}
			}			
		}
		
		
		//buat session sukses
		$this->session->set_userdata('sukses','packet');
			
		redirect(site_url()."/cancel");
		
//		$this->load->view('email_cancel_packet', $data);
		
	} // end function

	
	function konversi_tanggal($tgl){
      $tanggal = substr($tgl,8,2);
      $bln    = substr($tgl,5,2);
	  $bulan = ""; $strHari = "";
      $tahun    = substr($tgl,0,4);

      switch ($bln){
        case 1:
          $bulan =  "Januari";
          break;
        case 2:
          $bulan =  "Februari";
          break;
        case 3:
          $bulan = "Maret";
          break;
        case 4:
          $bulan =  "April";
          break;
        case 5:
          $bulan =  "Mei";
          break;
        case 6:
          $bulan =  "Juni";
          break;
        case 7:
          $bulan =  "Juli";
          break;
        case 8:
          $bulan =  "Agustus";
          break;
        case 9:
          $bulan =  "September";
          break;
        case 10:
          $bulan =  "Oktober";
          break;
        case 11:
          $bulan =  "November";
          break;
        case 12:
          $bulan =  "Desember";
          break;
	   }
	   return $tanggal.' '.$bulan.' '.$tahun;
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

?>