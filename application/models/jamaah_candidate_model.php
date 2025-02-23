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
	
	function query_jamaah($data)
	{
		return $this->db->query($data);
	}
	
	function get_data_berdasarkan_id_candidate($id_candidate)
	{
		$this->db->select("*");
		$this->db->from("jamaah_candidate");
		$this->db->where('ID_CANDIDATE', $id_candidate);
		
		return $this->db->get();
	}

        function get_profile($id_candidate, $kode_reg)
	{
		$this->db->select("*");
		$this->db->from("jamaah_candidate j");
                $this->db->join("province p", "p.ID_PROPINSI = j.ID_PROPINSI");
		$this->db->where('ID_CANDIDATE', $id_candidate);
                $this->db->where('KODE_REGISTRASI', $kode_reg);
                $this->db->where('STATUS_KANDIDAT', 1);

		return $this->db->get();
	}
	
	function get_profile_view($id_candidate, $kode_reg)
	{
		$this->db->select("*");
		$this->db->from("jamaah_candidate_view");
		$this->db->where('ID_CANDIDATE', $id_candidate);
		$this->db->where('KODE_REGISTRASI', $kode_reg);
		$this->db->where('STATUS_KANDIDAT', 1);

		return $this->db->get();
	}
	
	
	function get_grid_all_jamaah($kode_reg, $id_account)
	{
		$status = array(1,2,3);
		
		$this->db->select('j.*, c.SIZE AS UKURAN_BAJU');
		$this->db->from('jamaah_candidate j');
		$this->db->join('clothes_size c','j.ID_SIZE = c.ID_SIZE');	
		$this->db->where('KODE_REGISTRASI', $kode_reg );
		$this->db->where('ID_ACCOUNT', $id_account);
		$this->db->where_in('STATUS_KANDIDAT', $status);
		
		$this->CI->flexigrid->build_query();
		
		$return['records'] = $this->db->get();
		$this->db->select('count(ID_CANDIDATE) as record_count')->from('jamaah_candidate')->where('KODE_REGISTRASI', $kode_reg )->where('ID_ACCOUNT', $id_account)->where_in('STATUS_KANDIDAT', $status);
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
	
	function get_total_data_sortir($id_account, $kode_reg){
		$status = array(1,2,3);
		$this->db->select('*');
		$this->db->from('jamaah_candidate');
		$this->db->where('ID_ACCOUNT', $id_account);
		$this->db->where('KODE_REGISTRASI', $kode_reg);
		$this->db->where_in('STATUS_KANDIDAT', $status);		
		
		return $this->db->count_all_results();
	}
	
	function get_total_data_aktif(){
		$status = array(1,2,3);
		$this->db->select('*');
		$this->db->from('jamaah_candidate');
		$this->db->where_in('STATUS_KANDIDAT', $status);
		
		return $this->db->count_all_results();
	}
	
	function get_jamaah_berdasarkan_id_accaount_candidate($id_candidate, $id_account)
	{
		$this->db->select('*');
		$this->db->from('jamaah_candidate');
		$this->db->where('ID_CANDIDATE', $id_candidate);
		$this->db->where('ID_ACCOUNT', $id_account);
		
		return $this->db->get();
	}
	
	function get_total_jamaah($id_account, $kode_reg, $other_param = array())
	{
		$status = array(1,2,3);
		
		$this->db->select('*');
		$this->db->from('jamaah_candidate');
		$this->db->where('KODE_REGISTRASI', $kode_reg);
		$this->db->where('ID_ACCOUNT', $id_account);
		$this->db->where($other_param);
        $this->db->where_in('STATUS_KANDIDAT', $status);
				
		return $this->db->get();
	}
	
        /* mendapatkan data jamaah yang sdh booked (DP) tp belum masuk ke booked_Room */
        function get_jamaah_notBooked_room($id_account, $kode_reg, $data)
	{
                $status = array(2,3);
		$this->db->select('*');
		$this->db->from('jamaah_candidate');
		$this->db->where('KODE_REGISTRASI', $kode_reg);
		$this->db->where('ID_ACCOUNT', $id_account);
                //$this->db->where_in('STATUS_KANDIDAT', $status);
                $this->db->where('STATUS_KANDIDAT', 1);
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
	
	function get_grid_allover_jamaah()
	{
		$status = array(1,2,3);
		
		$this->db->select('j.*, c.SIZE AS UKURAN_BAJU');
		$this->db->from('jamaah_candidate j');
		$this->db->join('clothes_size c','j.ID_SIZE = c.ID_SIZE');	
		$this->db->where_in('STATUS_KANDIDAT', $status);
		
		$this->CI->flexigrid->build_query();
		
		$return['records'] = $this->db->get();
		$this->db->select('count(ID_CANDIDATE) as record_count')->from('jamaah_candidate');
		$this->CI->flexigrid->build_query(FALSE);
		$record_count = $this->db->get();
		$row = $record_count->row();
		$return['record_count'] = $row->record_count;
	
		return $return;
	}
	
	
	function get_grid_allover_jamaah_grouped($kode_akun=null)
	{
		$status = array(1,3);
		
		$this->db->select('*');
		$this->db->from('jamaah_candidate');	
		$this->db->join('accounts','jamaah_candidate.ID_ACCOUNT = accounts.ID_ACCOUNT');	
		if(!is_null($kode_akun)){
		$this->db->where('jamaah_candidate.ID_ACCOUNT', $kode_akun);
		}
		$this->db->where_in('STATUS_KANDIDAT', $status);
		
		$this->CI->flexigrid->build_query();
		
		$return['records'] = $this->db->get();
		$this->db->select('count(ID_CANDIDATE) as record_count')->from('jamaah_candidate')->join('accounts','jamaah_candidate.ID_ACCOUNT = accounts.ID_ACCOUNT')->where_in('STATUS_KANDIDAT', $status);
		if(!is_null($kode_akun)){
		$this->db->where('jamaah_candidate.ID_ACCOUNT', $kode_akun);
		}
		
		$this->CI->flexigrid->build_query(FALSE);
		$record_count = $this->db->get();
		$row = $record_count->row();
		$return['record_count'] = $row->record_count;
	
		return $return;
	}
	
	function get_jamaah_byRoomPacket($id_account, $kode_reg, $id_room_packet)
	{
		$status = array(0,1);
		
		$this->db->select('*');
		$this->db->from('jamaah_candidate');
		$this->db->where('KODE_REGISTRASI', $kode_reg);
		$this->db->where('ID_ACCOUNT', $id_account);
        $this->db->where_in('STATUS_KANDIDAT', $status);
        $this->db->where_in('ID_ROOM_PACKET', $id_room_packet);
				
		return $this->db->get();
	}
	
	
	function get_jamaah_related_with_clothes_size($clothes_size_id){
		$this->db->select("*");
		$this->db->from("jamaah_candidate");
		$this->db->where("ID_SIZE", $clothes_size_id);
		
		return $this->db->get();
	}
	
	function get_jamaah_related_with_relation($relation_id){
		$this->db->select("*");
		$this->db->from("jamaah_candidate");
		$this->db->where("ID_RELATION", $relation_id);
		
		return $this->db->get();
	}
	
	function get_sum_jamaah_by_id_account($id_akun,$kode_registrasi){
		$status = array(1,2,3);
	
		
		$this->db->from("jamaah_candidate");
		$this->db->where('KODE_REGISTRASI', $kode_registrasi);
		$this->db->where('ID_ACCOUNT', $id_akun);
        $this->db->where_in('STATUS_KANDIDAT', $status);
		
		return $this->db->get();
    
	}
	
	function get_jamaah_ByIdAcc($id_account, $kode_reg)
	{
		$this->db->select("*");
		$this->db->from("jamaah_candidate");
		$this->db->where('ID_ACCOUNT', $id_account);
		$this->db->where('KODE_REGISTRASI', $kode_reg);
        $this->db->where_in('STATUS_KANDIDAT', 1);
		
		return $this->db->get();
	}
	
	function get_jamaah_byManingtis($id_account, $kode_reg)
	{
		$this->db->select("*");
		$this->db->from("jamaah_candidate");
		$this->db->where('ID_ACCOUNT', $id_account);
		$this->db->where('KODE_REGISTRASI', $kode_reg);
        $this->db->where_in('JASA_TAMBAHAN', 1);
        $this->db->where_in('STATUS_KANDIDAT', 1);
		
		return $this->db->get();
	}
	
	function get_data_berdasarkan_id_candidate_byStatus($id_candidate)
	{
		$status = array(1,2,3);
		
		$this->db->select("*");
		$this->db->from("jamaah_candidate");
		$this->db->where('ID_CANDIDATE', $id_candidate);	
		$this->db->where_in('STATUS_KANDIDAT', $status);
		
		return $this->db->get();
	}
		
}

?>