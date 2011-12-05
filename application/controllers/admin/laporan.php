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
	
	function cetak($id_group, $id_program, $tipe)
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
		$nama_group = str_replace(" ", "", $data['nama_group']);
		
		if($tipe == '1')
		{
			// set pdf
			$html = $this->load->view('admin/cetak_manifest', $data, TRUE);
			$head = $this->load->view('admin/cetak_manifest_head', $data, TRUE);
			$filename = 'Pramanifest_'.$nama_group.'_'.date("d-m-Y").'.pdf';
			
			pdf_manifest($html, $head, $filename);
		
		}elseif($tipe == '2'){
			
			// set excel
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=Pramanifest_".$nama_group."_".date("d-m-Y").".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$this->load->view('admin/cetak_manifest_excel', $data);
		}
			
	}
	
	function cetak_excel($id_group, $id_program)
	{
		// call library
        $this->load->library('PHPExcel/IOFactory');
        $this->load->library('PHPExcel');
		
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
		
		$pecah_pulang = explode("-",$tgl_berangkat);
		$data['tgl_berangkat'] = $this->konversi_tanggal2($tgl_berangkat);
		$data['tgl_pulang'] = $this->konversi_tanggal2(date("Y-m-d", mktime(0,0,0,$pecah_pulang[1],$pecah_pulang[2]+$lama_hari,$pecah_pulang[0])));
		$nama_group = str_replace(" ", "", $data['nama_group']);
		$tanggal_cetak = "Jakarta, ".$this->konversi_tanggal2(date("Y-m-d"));
		$text_header = "PRAMANIFEST UMRAH KAMILAH WISATA - ".$data['nama_program']."
JKT-JED ".$data['tgl_berangkat']."  -   JED-JKT ".$data['tgl_pulang']."";
		
		// set to excel
		$objReader = IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load('excel/excel_pramanifest.xls');
		$objPHPExcel->getActiveSheet()->setCellValue('A1', $data['nama_group']);
		$objPHPExcel->getActiveSheet()->setCellValue('B4', $data['maskapai']);
		$objPHPExcel->getActiveSheet()->setCellValue('C1', $text_header);
		$objPHPExcel->getActiveSheet()->setCellValue('C6', $data['hotel_mk']);
		$objPHPExcel->getActiveSheet()->setCellValue('C7', $data['hotel_md']);
		
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
						$span_tgl_cetak = 1;
						
						// filter gender
						$gender = $rows->GENDER == 1 ? $gender = 'M' : $gender = 'F';
						
						// filter relasi
						$data_relation = $this->relation_model->get_relation($rows->ID_RELATION);
						foreach($data_relation->result() as $rowss)
						{
							$nama_relasi = $rowss->JENIS_RELASI;
						}
						
						$datax[$no] = array('no' 		=> $no,
									  'nama_lengkap'	=> $rows->NAMA_LENGKAP,
									  'gender'			=> $gender,
									  'tempat_lahir'	=> $rows->TEMPAT_LAHIR,
									  'tgl_lahir'		=> $this->konversi_tanggal($rows->TANGGAL_LAHIR),
									  'no_paspor'		=> $rows->NO_PASPOR,
									  'tgl_dikerluarkan'=> $this->konversi_tanggal($rows->TANGGAL_DIKELUARKAN),
									  'tgl_habis'		=> $this->konversi_tanggal($rows->TANGGAL_HABIS),
									  'kantor'			=> $rows->KANTOR_PEMBUATAN,
									  'nama_relasi'		=> $nama_relasi
									  );
						
					}
				}
			}
			
		
		} else {
			$span_tgl_cetak = 0;
			$datax[0] = array('no' 				=> '',
							  'nama_lengkap'	=> '',
							  'gender'			=> '',
							  'tempat_lahir'	=> '',
							  'tgl_lahir'		=> '',
							  'no_paspor'		=> '',
							  'tgl_dikerluarkan'=> '',
							  'tgl_habis'		=> '',
							  'kantor'			=> '',
							  'nama_relasi'		=> ''
							  );
		}
		
		$baseRow = 11;
			
		foreach($datax as $r => $dataRow) 
		{
			$row = $baseRow + $r - $span_tgl_cetak;
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
		
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $dataRow['no']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $dataRow['nama_lengkap']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $dataRow['gender']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $dataRow['tempat_lahir']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $dataRow['tgl_lahir']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $dataRow['no_paspor']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $dataRow['tgl_dikerluarkan']);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $dataRow['tgl_habis']);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$row, $dataRow['kantor']);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$row, $dataRow['nama_relasi']);
		}
		
		$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
		$celipad = $row+2;
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$celipad, $tanggal_cetak);
		$objPHPExcel->getActiveSheet()->setTitle('PRAMANIFEST '.$data['nama_group'].'');
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="PRAMANIFEST_'.$nama_group.'_'.$data['nama_program'].'.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
			
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
	
	function konversi_tanggal2($tgl){
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
	
}//end class

/* End of file /admin/data_jamaah.php */
/* Location: ./application/controllers/admin/laporan.php */