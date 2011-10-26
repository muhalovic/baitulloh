<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class jamaah_candidate_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->CI = get_instance();
	}
	
	function get_all_jamaah()
	{
		$this->db->select("*");
		$this->db->from("jamaah_candidate");
		
		return $this->db->get();
	}
	
	function get_data_berdasarkan_id_candidate($id_candidate)
	{
		$this->db->select("*");
		$this->db->from("jamaah_candidate");
		$this->db->where('ID_CANDIDATE', $id_candidate);
		
		return $this->db->get();
	}
	
	function get_grid_all_jamaah($kode_reg, $id_account)
	{
		$this->db->select('j.*, c.SIZE AS UKURAN_BAJU');
		$this->db->from('jamaah_candidate j');
		$this->db->join('clothes_size c','j.ID_SIZE = c.ID_SIZE');	
		$this->db->where('KODE_REGISTRASI', $kode_reg );
		$this->db->where('ID_ACCOUNT', $id_account);
		
		$this->CI->flexigrid->build_query();
		
		$return['records'] = $this->db->get();
		$this->db->select('count(ID_CANDIDATE) as record_count')->from('jamaah_candidate');
		$this->CI->flexigrid->build_query(FALSE);
		$record_count = $this->db->get();
		$row = $record_count->row();
		$return['record_count'] = $row->record_count;
	
		return $return;
	}
	
	function get_total_data(){
		$query = $this->db->count_all('jamaah_candidate');
		return $query;			
	}
	
	function get_jamaah_berdasarkan_id_accaount_candidate($id_candidate, $id_account)
	{
		$this->db->select('*');
		$this->db->from('jamaah_candidate');
		$this->db->where('ID_CANDIDATE', $id_candidate);
		$this->db->where('ID_ACCOUNT', $id_account);
		
		return $this->db->get();
	}

        /* mendapatkan data jamaah yang sdh booked (DP) tp belum masuk ke booked_Room */
        function get_jamaah_notBooked_room($id_account, $kode_reg, $data)
	{
		$this->db->select('*');
		$this->db->from('jamaah_candidate');
		$this->db->where('KODE_REGISTRASI', $kode_reg);
		$this->db->where('ID_ACCOUNT', $id_account);
                $this->db->where('STATUS_KANDIDAT', 2);
                $this->db->where_not_in('ID_CANDIDATE', $data);

		return $this->db->get();
	}
	
	function hapus_data_calon_jamaah($id_candidate)
	{
		$this->db->delete('jamaah_candidate', array('ID_CANDIDATE' => $id_candidate));
		
		return TRUE;
	}
	
	function insert_jamaah($data){
		$this->db->trans_begin();
		$this->db->insert('jamaah_candidate', $data);
		
		if ($this->db->trans_status() === FALSE)
			$this->db->trans_rollback();
		else
			$this->db->trans_commit();
	}
	
	function update_jamaah($data, $id_candidate){
		$this->db->trans_begin();
		$this->db->where('ID_CANDIDATE', $id_candidate);
		$this->db->update('jamaah_candidate', $data);
		
		if ($this->db->trans_status() === FALSE)
			$this->db->trans_rollback();
		else
			$this->db->trans_commit();
	}

}

?>
