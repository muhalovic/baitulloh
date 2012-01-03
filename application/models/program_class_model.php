<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Program_class_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		$this->CI = get_instance();
	}
	
	function get_all_program()
	{
		$this->db->select("*");
		$this->db->from("program_class");
		
		return $this->db->get();
	}
	
	function get_program($id){
		$this->db->select("*");
		$this->db->from("program_class");
		$this->db->where("ID_PROGRAM", $id);
		
		return $this->db->get();
	}
	
	function get_grid_program(){
		
		$this->db->select("*");
		$this->db->from("program_class");
		$this->CI->flexigrid->build_query();
		
		$return['records'] = $this->db->get(); 
		
		$this->db->select("*");
		$this->db->from("program_class");
		
		$return['record_count'] = $this->db->get()->num_rows; 
		
		return $return;
			
	}
	
	function update_program($id, $data){
		$this->db->trans_begin();
                $this->db->where("ID_PROGRAM", $id);
		$this->db->update('program_class', $data);

		if ($this->db->trans_status() === FALSE)
			$this->db->trans_rollback();
		else
			$this->db->trans_commit();
	}
	
	function add_program($data){
		$this->db->insert('program_class', $data);
	}
	
	function delete_program($data){
		$this->db->where('ID_PROGRAM',$data);
		$this->db->delete('program_class');
	}
	
	function get_program_ByGroup($id_program, $id_group){
		$this->db->select("*");
		$this->db->from("program_class");
		$this->db->where("ID_PROGRAM", $id_program);
		$this->db->where("ID_GROUP", $id_group);
		
		return $this->db->get();
	}
	
	
	function get_all_program_ByStatus()
	{
		$this->db->select("*");
		$this->db->from("program_class");
		$this->db->where('STATUS', 1);
		
		return $this->db->get();
	}
	
	function get_program_Group($id_group){
		$this->db->select("*");
		$this->db->from("program_class");
		$this->db->where("ID_GROUP", $id_group);
		$this->db->where('STATUS', 1);
		return $this->db->get();
	}
}

?>
