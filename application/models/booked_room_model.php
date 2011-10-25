<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Booked_room_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function get_all_booked_room()
	{
		$this->db->select("*");
		$this->db->from("booked_room");
		
		return $this->db->get();
	}
	
	function get_booked_room($id){
		$this->db->select("*");
		$this->db->from("booked_room");
		$this->db->where("ID_BOOKED", $id);
		
		return $this->db->get();
	}
	
	function get_booked_candidate($id_acc, $kode_reg, $id_room){
		$this->db->select("br.*, j.*");
		$this->db->from("booked_room br");
		$this->db->join("jamaah_candidate j", "j.ID_CANDIDATE = br.ID_CANDIDATE");
		$this->db->join("room r", "r.ID_ROOM = br.ID_ROOM");
		$this->db->where("ID_ACCOUNT", $id_acc);
		$this->db->where("KODE_REGISTRASI", $kode_reg);
		$this->db->where("STATUS_KANDIDAT", 1);
		$this->db->where("br.ID_ROOM", $id_room);
		
		return $this->db->get();
	}
	
	function insert_booked_room($data){
		$this->db->trans_begin();
		$this->db->insert('booked_room', $data);
		
		if ($this->db->trans_status() === FALSE)
			$this->db->trans_rollback();
		else
			$this->db->trans_commit();
	}
}

?>
