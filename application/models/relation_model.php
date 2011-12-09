<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Relation_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->CI = get_instance();
	}
	
	function get_all_relation()
	{
		$this->db->select("*");
		$this->db->from("relation");
		
		return $this->db->get();
	}
	
	function get_all_active_relation()
	{
		$this->db->select("*");
		$this->db->from("relation");
		$this->db->where('STATUS',1);
		
		return $this->db->get();
	}

	function get_relation($id)
	{
		$this->db->select("*");
		$this->db->from("relation");
		$this->db->where("ID_RELATION", $id);
		
		return $this->db->get();
	}
	
	function get_grid_relation(){
		
		$this->db->select("*");
		$this->db->from("relation");
		$this->CI->flexigrid->build_query();
		
		$return['records'] = $this->db->get(); 
		
		$this->db->select("*");
		$this->db->from("relation");
		
		$return['record_count'] = $this->db->get()->num_rows; 
		
		return $return;
			
	}
	
	function update_relation($id, $data){
		$this->db->trans_begin();
        $this->db->where("ID_RELATION", $id);
		$this->db->update('relation', $data);

		if ($this->db->trans_status() === FALSE)
			$this->db->trans_rollback();
		else
			$this->db->trans_commit();
	}
	
	function add_relation($data){
		$this->db->insert('relation', $data);
	}
	
	function delete_relation($data){
		$this->db->where('ID_RELATION',$data);
		$this->db->delete('relation');
	}
	

}