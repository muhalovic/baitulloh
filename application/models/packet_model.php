<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Packet_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function get_all_packet()
	{
		$this->db->select("*");
		$this->db->from("packet");
		
		return $this->db->get();
	}
	
	function get_packet($id){
		$this->db->select("*");
		$this->db->from("packet");
		$this->db->where("ID_PACKET", $id);
		
		return $this->db->get();
	}
	
	function get_packet_byAccAll($id_acc, $kode_reg){
        $this->db->select("packet.*,g.ID_GROUP, g.KODE_GROUP, g.KETERANGAN, p.NAMA_PROGRAM");
		$this->db->from("packet");
        $this->db->join("group_departure g","g.ID_GROUP = packet.ID_GROUP");
        $this->db->join("program_class p", "p.ID_PROGRAM = packet.ID_PROGRAM");
		$this->db->where("ID_ACCOUNT", $id_acc);
		$this->db->where("KODE_REGISTRASI", $kode_reg);
		
		return $this->db->get();
	}
	
	function get_packet_byAcc($id_acc, $kode_reg){
            $status = array(1,3);
            $this->db->select("packet.*,g.ID_GROUP, g.KODE_GROUP, g.KETERANGAN, p.NAMA_PROGRAM");
		$this->db->from("packet");
                $this->db->join("group_departure g","g.ID_GROUP = packet.ID_GROUP");
                $this->db->join("program_class p", "p.ID_PROGRAM = packet.ID_PROGRAM");
		$this->db->where("ID_ACCOUNT", $id_acc);
		$this->db->where("KODE_REGISTRASI", $kode_reg);
		$this->db->where_in("STATUS_PESANAN", $status);
		
		return $this->db->get();
	}

        function get_packet_byAcc_waiting($id_acc, $kode_reg){
            $status = array(2);
            $this->db->select("packet.*,g.ID_GROUP, g.KODE_GROUP, g.KETERANGAN, p.NAMA_PROGRAM");
		$this->db->from("packet");
                $this->db->join("group_departure g","g.ID_GROUP = packet.ID_GROUP");
                $this->db->join("program_class p", "p.ID_PROGRAM = packet.ID_PROGRAM");
		$this->db->where("ID_ACCOUNT", $id_acc);
		$this->db->where("KODE_REGISTRASI", $kode_reg);
		$this->db->where_in("STATUS_PESANAN", $status);

		return $this->db->get();
	}

        function get_packet_status($id_account, $kode_reg){
            $this->db->select('*');
            $this->db->from('packet');
            $this->db->where('KODE_REGISTRASI', $kode_reg);
            $this->db->where('ID_ACCOUNT', $id_account);
            $this->db->where('STATUS_PESANAN !=', 0);
            $this->db->group_by("ID_ACCOUNT, KODE_REGISTRASI");

            return $this->db->get();
        }
	
	function insert_packet($data){
		$this->db->trans_begin();
		$this->db->insert('packet', $data);
		
		if ($this->db->trans_status() === FALSE)
			$this->db->trans_rollback();
		else
			$this->db->trans_commit();
	}
	
	function update_packet($data, $id_packet){
		$this->db->where('ID_PACKET', $id_packet);
		$this->db->update('packet', $data);
	}

        function delete_packet($id){
            $this->db->where("ID_PACKET", $id);
            $this->db->delete("packet");
        }
		
	
	function get_packet_aktif($id_group, $id_program){
            $status = array(1,2,3);
            $this->db->select("packet.*,g.ID_GROUP, g.KODE_GROUP, g.KETERANGAN, p.NAMA_PROGRAM");
		$this->db->from("packet");
                $this->db->join("group_departure g","g.ID_GROUP = packet.ID_GROUP");
                $this->db->join("program_class p", "p.ID_PROGRAM = packet.ID_PROGRAM");
		$this->db->where("packet.ID_GROUP", $id_group);
		$this->db->where("packet.ID_PROGRAM", $id_program);
		$this->db->where_in("STATUS_PESANAN", $status);
		
		return $this->db->get();
	}
}

?>
