<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Room_availability_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		$this->CI = get_instance();
	}
	
	function get_all_room()
	{
		$this->db->select("*");
		$this->db->from("room_availability");
		
		return $this->db->get();
	}
	
	function get_room_byId($id)
	{
		$this->db->select("*");
		$this->db->from("room_availability");
		$this->db->where("ID_AVAILABILITY", $id);
		
		return $this->db->get();
	}
	
	function get_price_room($id_room, $id_program, $id_group){
		$this->db->select("*");
		$this->db->from("room_availability");
		$this->db->where("ID_ROOM_TYPE", $id_room);
		$this->db->where("ID_PROGRAM", $id_program);
		$this->db->where("ID_GROUP", $id_group);
		
		return $this->db->get();
	}
	
	function get_total_room(){
		$this->db->select('*');
		$this->db->from('room_availability');
		
		return $this->db->count_all_results();
	}
	
	function get_grid_allover_room()
	{
		$this->db->select('ra.*, rt.JENIS_KAMAR, g.KODE_GROUP, p.NAMA_PROGRAM');
		$this->db->from('room_availability ra');
		$this->db->join('room_type rt','ra.ID_ROOM_TYPE = rt.ID_ROOM_TYPE');	
		$this->db->join('program_class p','ra.ID_PROGRAM = p.ID_PROGRAM');	
		$this->db->join('group_departure g','ra.ID_GROUP = g.ID_GROUP');	
		
		$this->CI->flexigrid->build_query();
		$return['records'] = $this->db->get();
		
		$this->db->select('ra.*, rt.JENIS_KAMAR, g.KODE_GROUP, p.NAMA_PROGRAM');
		$this->db->from('room_availability ra');
		$this->db->join('room_type rt','ra.ID_ROOM_TYPE = rt.ID_ROOM_TYPE');	
		$this->db->join('program_class p','ra.ID_PROGRAM = p.ID_PROGRAM');	
		$this->db->join('group_departure g','ra.ID_GROUP = g.ID_GROUP');
		
		$this->CI->flexigrid->build_query(FALSE);
		$return['record_count'] = $this->db->count_all_results();
	
		return $return;
	}
	
	function insert_room($data){
		$this->db->trans_begin();
		$this->db->insert('room_availability', $data);
		
		if ($this->db->trans_status() === FALSE)
			$this->db->trans_rollback();
		else
			$this->db->trans_commit();
	}
	
	function update_room($data, $id_room){
		$this->db->trans_begin();
		$this->db->where('ID_AVAILABILITY', $id_room);
		$this->db->update('room_availability', $data);
		
		if ($this->db->trans_status() === FALSE)
			$this->db->trans_rollback();
		else
			$this->db->trans_commit();
	}
	
	function hapus_room($id_room)
	{
		$this->db->delete('room_availability', array('ID_AVAILABILITY' => $id_room));
		
		return TRUE;
	}
	
	function get_room_availability_related_with_program($id_program){
            $this->db->select("*");
			$this->db->from("room_availability");
			$this->db->where("ID_PROGRAM", $id_program);
		
		return $this->db->get();
	}
	
	function get_room_availability_related_with_group($id_group){
            $this->db->select("*");
			$this->db->from("room_availability");
			$this->db->where("ID_GROUP", $id_group);
		
		return $this->db->get();
	}

}

?>
