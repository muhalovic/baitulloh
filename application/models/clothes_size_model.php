<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clothes_size_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->CI = get_instance();
	}
	
	function get_all_clothes()
	{
		$this->db->select("*");
		$this->db->from("clothes_size");
		
		return $this->db->get();
	}
	
	function get_all_active_clothes()
	{
		$this->db->select("*");
		$this->db->from("clothes_size");
		$this->db->where('STATUS',1);
		
		return $this->db->get();
	}
	
	function get_clothes_size($id){
		$this->db->select("*");
		$this->db->from("clothes_size");
		$this->db->where("ID_SIZE", $id);
		
		return $this->db->get();
	}
	
	function get_grid_clothes_size(){
		
		$this->db->select("*");
		$this->db->from("clothes_size");
		$this->CI->flexigrid->build_query();
		
		$return['records'] = $this->db->get(); 
		
		$this->db->select("*");
		$this->db->from("clothes_size");
		
		$return['record_count'] = $this->db->get()->num_rows; 
		
		return $return;
			
	}
	
	
	function update_clothes_size($id, $data){
		$this->db->trans_begin();
                $this->db->where("ID_SIZE", $id);
		$this->db->update('clothes_size', $data);

		if ($this->db->trans_status() === FALSE)
			$this->db->trans_rollback();
		else
			$this->db->trans_commit();
	}
	
	function add_clothes_size($data){
		$this->db->insert('clothes_size', $data);
	}
	
	function delete_clothes_size($data){
		$this->db->where('ID_SIZE',$data);
		$this->db->delete('clothes_size');
	}
	
}