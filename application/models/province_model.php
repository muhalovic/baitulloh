<?php 
class Province_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
		$this->CI = get_instance();
    }
	
	function get_all_province(){
		$this->db->select('*');
		$this->db->from('province');
		
		return $this->db->get();
	}
	
	function get_all_active_province(){
		$this->db->select('*');
		$this->db->from('province');
		$this->db->where('STATUS',1);
		
		return $this->db->get();
	}
	
	function get_province($id){
		$this->db->select('*');
		$this->db->from('province');
		$this->db->where('ID_PROPINSI', $id);
		
		return $this->db->get();
	}
	
	
	function get_total_propinsi(){
		$this->db->select('*');
		$this->db->from('province');
		
		return $this->db->count_all_results();
	}
	
	function get_grid_allover()
	{
		$this->db->select('*');
		$this->db->from('province');
		
		$this->CI->flexigrid->build_query();
		$return['records'] = $this->db->get();
		
		$this->db->select('*');
		$this->db->from('province');
		
		$this->CI->flexigrid->build_query(FALSE);
		$return['record_count'] = $this->db->count_all_results();
	
		return $return;
	}
	
	function insert_propinsi($data){
		$this->db->trans_begin();
		$this->db->insert('province', $data);
		
		if ($this->db->trans_status() === FALSE)
			$this->db->trans_rollback();
		else
			$this->db->trans_commit();
	}
	
	function update_propinsi($data, $id_room){
		$this->db->trans_begin();
		$this->db->where('ID_PROPINSI', $id_room);
		$this->db->update('province', $data);
		
		if ($this->db->trans_status() === FALSE)
			$this->db->trans_rollback();
		else
			$this->db->trans_commit();
	}
	
	function hapus_propinsi($id_room)
	{
		$this->db->delete('province', array('ID_PROPINSI' => $id_room));
		
		return TRUE;
	}
}

?>