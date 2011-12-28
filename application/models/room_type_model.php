<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Room_type_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		$this->CI = get_instance();
	}
	
	function get_all_roomType()
	{
		$this->db->select("*");
		$this->db->from("room_type");
		
		return $this->db->get();
	}
	
	
	function get_all_roomType_aktif()
	{
		$this->db->select("*");
		$this->db->from("room_type");
		$this->db->where("STATUS", 1);
		
		return $this->db->get();
	}
	
	function get_roomType($id){
		$this->db->select("*");
		$this->db->from("room_type");
		$this->db->where("ID_ROOM_TYPE", $id);
		
		return $this->db->get();
	}
	
	function get_grid_room_type(){
		
		$this->db->select("*");
		$this->db->from("room_type");
		$this->CI->flexigrid->build_query();
		
		$return['records'] = $this->db->get(); 
		
		$this->db->select("*");
		$this->db->from("room_type");
		
		$return['record_count'] = $this->db->get()->num_rows; 
		
		return $return;
			
	}
	
	
	function update_room_type($id, $data){
		$this->db->trans_begin();
                $this->db->where("ID_ROOM_TYPE", $id);
		$this->db->update('room_type', $data);

		if ($this->db->trans_status() === FALSE)
			$this->db->trans_rollback();
		else
			$this->db->trans_commit();
	}
	
	function add_room_type($data){
		$this->db->insert('room_type', $data);
	}
	
	function delete_room_type($data){
		$this->db->where('ID_ROOM_TYPE',$data);
		$this->db->delete('room_type');
	}
	
	
	function get_all_roomType_ByStatusTampil($status_tampil)
	{
		$this->db->select("*");
		$this->db->from("room_type");
		$this->db->where('AREA_TAMPIL', $status_tampil);
		
		return $this->db->get();
	}
	
}

?>
