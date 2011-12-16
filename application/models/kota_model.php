<?php 
class Kota_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
		$this->CI = get_instance();
    }
	
	function get_all_kota(){
		$this->db->select('*');
		$this->db->from('kota');
		
		return $this->db->get();
	}
	
	function get_kota($id){
		$this->db->select('*');
		$this->db->from('kota');
		$this->db->where('ID_KOTA', $id);
		
		return $this->db->get();
	}

	function get_kota_by_province($id){
		$this->db->select('*');
		$this->db->from('kota');
		$this->db->where('ID_PROPINSI', $id);
		$this->db->where('STATUS', 1);
		
		return $this->db->get();
	}
	
	
	function get_total_propinsi(){
		$this->db->select('*');
		$this->db->from('kota');
		
		return $this->db->count_all_results();
	}
	
	function get_grid_allover()
	{
		$this->db->select('*');
		$this->db->from('kota');
		
		$this->CI->flexigrid->build_query();
		$return['records'] = $this->db->get();
		
		$this->db->select('*');
		$this->db->from('kota');
		
		$this->CI->flexigrid->build_query(FALSE);
		$return['record_count'] = $this->db->count_all_results();
	
		return $return;
	}
	
	function insert_propinsi($data){
		$this->db->trans_begin();
		$this->db->insert('kota', $data);
		
		if ($this->db->trans_status() === FALSE)
			$this->db->trans_rollback();
		else
			$this->db->trans_commit();
	}
	
	function update_propinsi($data, $id_room){
		$this->db->trans_begin();
		$this->db->where('ID_KOTA', $id_room);
		$this->db->update('kota', $data);
		
		if ($this->db->trans_status() === FALSE)
			$this->db->trans_rollback();
		else
			$this->db->trans_commit();
	}
	
	function hapus_propinsi($id_room)
	{
		$this->db->delete('kota', array('ID_KOTA' => $id_room));
		
		return TRUE;
	}
}

?>