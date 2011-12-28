<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group_departure_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		$this->CI = get_instance();
	}
	
	function get_all_group()
	{
		$this->db->select("*");
		$this->db->from("group_departure");
		
		return $this->db->get();
	}
	
	function get_group($id_group){
		$this->db->select("*");
		$this->db->from("group_departure");
		$this->db->where("ID_GROUP", $id_group);
		
		return $this->db->get();
	}
	
	function get_group_berdasarkan_id($id_group)
	{
		$this->db->select("*");
		$this->db->from("group_departure");
		$this->db->where('ID_GROUP', $id_group);
		
		return $this->db->get();
	}
	
	function update_group($id, $data){
		$this->db->trans_begin();
                $this->db->where("ID_GROUP", $id);
		$this->db->update('group_departure', $data);

		if ($this->db->trans_status() === FALSE)
			$this->db->trans_rollback();
		else
			$this->db->trans_commit();
	}
	
	function add_group($data){
		$this->db->insert('group_departure', $data);
	}
	
	function delete_group($data){
		$this->db->where('ID_GROUP',$data);
		$this->db->delete('group_departure');
	}
	
	function get_grid_group(){
		
		$this->db->select("*");
		$this->db->from("group_departure");
		$this->CI->flexigrid->build_query();
		
		$return['records'] = $this->db->get(); 
		
		$this->db->select("*");
		$this->db->from("group_departure");
		
		$return['record_count'] = $this->db->get()->num_rows; 
		
		return $return;
		
	
		
	}
	
	
	function get_all_group_ByStatus()
	{
		$this->db->select("*");
		$this->db->from("group_departure");
		$this->db->where('STATUS', 1);
		
		return $this->db->get();
	}
}

?>
