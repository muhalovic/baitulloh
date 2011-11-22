<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		if($this->session->userdata('id_user') == NULL)
			redirect(site_url()."/admin/login");
	}

	function index()
	{
		$this->front();
	}
	
	function front()
	{
		redirect(site_url()."/admin/data_jamaah");
	}
	
	function cetak($id_group, $id_program)
	{
		// call helper
		$this->load->helper('create_pdf');
		
		// call model here	
		$this->load->model('jamaah_candidate_model');
		$this->load->model('group_departure_model');
		$this->load->model('program_class_model');
		$this->load->model('packet_model');
		$this->load->model('relation_model');
		
		// get data group departure
		$data_group = $this->group_departure_model->get_group($id_group);
		if($data_group->result() != NULL)
		{
			foreach($data_group->result() as $row)
			{
				$data['nama_group'] = $row->KODE_GROUP;
				$tgl_berangkat = $row->TANGGAL_KEBERANGKATAN_JD;
				$lama_hari = $row->HARI;
				$id_groups = $row->ID_GROUP;
			}
			
		}else{
			
			redirect(site_url()."/admin/data_jamaah");
		}
		
		// get data program class
		if($id_program == '100')
		{
			$data['nama_program'] = "SEMUA PROGRAM";
			$data['maskapai'] = "-";
			$data['hotel_mk'] = "-";
			$data['hotel_md'] = "-";
			
			$data_program = $this->program_class_model->get_all_program();
			foreach($data_program->result() as $row)
			  {
				  $id_prog[$row->ID_PROGRAM] = $row->ID_PROGRAM;
			  }
			 $id_programs = $id_prog;
			  
		}else{
			$data_program = $this->program_class_model->get_program($id_program);
			if($data_program->result() != NULL)
			{
				foreach($data_program->result() as $row)
				{
					$data['nama_program'] = $row->NAMA_PROGRAM;
					$data['maskapai'] = $row->MASKAPAI;
					$data['hotel_mk'] = $row->HOTEL_MAKKAH;
					$data['hotel_md'] = $row->HOTEL_MADINAH;
					$id_programs = array($row->ID_PROGRAM);
				}
				
			}else{
				
				redirect(site_url()."/admin/data_jamaah");
			}
		}
		
		// get data jamaah
		$no = 0;
		$data['list_jamaah'] = '';
		$max_row = 15;
		$data_packet = $this->packet_model->get_packet_aktif($id_groups, $id_programs);
		if($data_packet->result() != NULL)
		{
			foreach($data_packet->result() as $row)
			{
				$id_account = $row->ID_ACCOUNT;
				$kode_reg = $row->KODE_REGISTRASI;
				
				$data_jamaah = $this->jamaah_candidate_model->get_total_jamaah($id_account, $kode_reg);
				if($data_jamaah->result() != NULL)
				{
					foreach($data_jamaah->result() as $rows)
					{
						$no = $no+1;
						// filter gender
						$gender = $rows->GENDER == 1 ? $gender = 'M' : $gender = 'F';
						
						// filter relasi
						$data_relation = $this->relation_model->get_relation($rows->ID_RELATION);
						foreach($data_relation->result() as $rowss)
						{
							$nama_relasi = $rowss->JENIS_RELASI;
						}
						$data['list_jamaah'] .= '
								  <tr>
									<td align="center">'.$no.'</td>
									<td>'.$rows->NAMA_LENGKAP.'</td>
									<td align="center">'.$gender.'</td>
									<td align="left">'.$rows->TEMPAT_LAHIR.'</td>
									<td align="center">'.$this->konversi_tanggal($rows->TANGGAL_LAHIR).'</td>
									<td align="center">'.$rows->NO_PASPOR.'</td>
									<td align="center">'.$this->konversi_tanggal($rows->TANGGAL_DIKELUARKAN).'</td>
									<td align="center">'.$this->konversi_tanggal($rows->TANGGAL_HABIS).'</td>
									<td>'.$rows->KANTOR_PEMBUATAN.'</td>
									<td>'.$nama_relasi.'</td>
								  </tr>';
								  
						
					}
					
				}else{
						// loping
				}
			}
			
			$nomor = $no+1;
			if($nomor < $max_row)
			{
				// loping
			}
		}else{
			
			// loping
		}
		
		$pecah_pulang = explode("-",$tgl_berangkat);
		$data['tgl_berangkat'] = date("d F Y", strtotime($tgl_berangkat));
		$data['tgl_pulang'] = date("d F Y", mktime(0,0,0,$pecah_pulang[1],$pecah_pulang[2]+$lama_hari,$pecah_pulang[0]));
		
		// set pdf
		$nama_group = str_replace(" ", "", $data['nama_group']);
		$html = $this->load->view('admin/cetak_manifest', $data, TRUE);
		$head = $this->load->view('admin/cetak_manifest_head', $data, TRUE);
		$filename = 'Pramanifest_'.$nama_group.'_'.date("d-m-Y").'.pdf';
		
		pdf_manifest($html, $head, $filename);
	}
	
	function konversi_tanggal($tgl){
      $tanggal = substr($tgl,8,2);
      $bln    = substr($tgl,5,2);
	  $bulan = ""; $strHari = "";
      $tahun    = substr($tgl,0,4);

      switch ($bln){
        case 1:
          $bulan =  "Jan";
          break;
        case 2:
          $bulan =  "Feb";
          break;
        case 3:
          $bulan = "Mar";
          break;
        case 4:
          $bulan =  "Apr";
          break;
        case 5:
          $bulan =  "Mei";
          break;
        case 6:
          $bulan =  "Jun";
          break;
        case 7:
          $bulan =  "Jul";
          break;
        case 8:
          $bulan =  "Agu";
          break;
        case 9:
          $bulan =  "Sep";
          break;
        case 10:
          $bulan =  "Okt";
          break;
        case 11:
          $bulan =  "Nov";
          break;
        case 12:
          $bulan =  "Des";
          break;
	   }
	   return $tanggal.' '.$bulan.' '.$tahun;
    }
	
}//end class

/* End of file /admin/data_jamaah.php */
/* Location: ./application/controllers/admin/laporan.php */