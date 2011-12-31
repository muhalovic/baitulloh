<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Room_packet_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function get_all_room_packet()
	{
		$this->db->select("*");
		$this->db->from("room_packet");
		
		return $this->db->get();
	}
	
	function get_room_packet($id){
		$this->db->select("*");
		$this->db->from("room_packet");
		$this->db->where("ID_ROOM_PACKET", $id);
		
		return $this->db->get();
	}
	
	function get_room_packet_byIDpack($id){
		$this->db->select("*");
		$this->db->from("room_packet");
        $this->db->join("room_type rt","rt.ID_ROOM_TYPE = room_packet.ID_ROOM_TYPE");
		$this->db->where("ID_PACKET", $id);
		
		return $this->db->get();
	}
	
	function get_room_packet_byIDroomPack($id){
		$this->db->select("*");
		$this->db->from("room_packet");
                $this->db->join("room_type rt","rt.ID_ROOM_TYPE = room_packet.ID_ROOM_TYPE");
		$this->db->where("ID_ROOM_PACKET", $id);
		
		return $this->db->get();
	}
	
	function insert_room_packet($data){
		$this->db->trans_begin();
		$this->db->insert('room_packet', $data);
		
		if ($this->db->trans_status() === FALSE)
			$this->db->trans_rollback();
		else
			$this->db->trans_commit();
	}

        function delete_data_bypacket($id){
            $this->db->where("ID_PACKET", $id);
            $this->db->delete("room_packet");
        }
		
	function get_room_packet_related_with_room_type($id_room_type){
            $this->db->select("*");
			$this->db->from("room_packet");
			$this->db->where("ID_ROOM_TYPE", $id_room_type);
		
		return $this->db->get();
	}
	
	function periksa_kamar_aktif($id_room_type, $id_group, $id_program)
	{
		$this->db->select("count(ID_CANDIDATE) as JUMLAH");
		$this->db->from('jamaah_candidate jc');
		$this->db->join('room_packet rp', 'rp.ID_ROOM_PACKET = jc.ID_ROOM_PACKET');
		$this->db->join('packet p', 'p.ID_PACKET = rp.ID_PACKET');
		$this->db->where('rp.ID_ROOM_TYPE', $id_room_type);
		$this->db->where('p.ID_GROUP', $id_group);
		$this->db->where('p.ID_PROGRAM', $id_program);
		$this->db->where('p.STATUS_PESANAN', 3);
		$this->db->where('jc.STATUS_KANDIDAT', 0);
		return $this->db->get();
	}
	
	function get_data_byRoomTypePacket($id_room_type, $id_packet){
            $this->db->select("*");
			$this->db->from("room_packet");
			$this->db->where("ID_ROOM_TYPE", $id_room_type);
			$this->db->where("ID_PACKET", $id_packet);
		
		return $this->db->get();
	}
	
	function update_room_packet($data, $id_tipe_kamar, $id_packet){
		$this->db->where('ID_ROOM_TYPE', $id_tipe_kamar);
		$this->db->where('ID_PACKET', $id_packet);
		$this->db->update('room_packet', $data);
	}
	

}

?>
