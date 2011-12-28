<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Userman_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		$this->CI = get_instance();
	}
	
	function userman_grid()
	{	
		$this->db->select('u.*, k.NAMA_KOTA, r.NAMA_ROLE');
		$this->db->from("internal_users u");
		$this->db->join('kota k', 'u.REGIONAL = k.ID_KOTA', 'left');
		$this->db->join('roles r', 'u.ROLE = r.ID_ROLE', 'left');
		$this->CI->flexigrid->build_query();
		
		$return['records'] = $this->db->get(); 
		
		$this->db->select('*');
		$this->db->from("internal_users u");
		$this->db->join('kota k', 'u.REGIONAL = k.ID_KOTA', 'left');
		$this->db->join('roles r', 'u.ROLE = r.ID_ROLE', 'left');
		
		$return['record_count'] = $this->db->get()->num_rows; 
		
		return $return;
	}
	
	function get_user($id)
	{
		$this->db->select("*");
		$this->db->from("internal_users");
		$this->db->where("ID_USER", $id);
		
		return $this->db->get();
	}
	
	function ubah_status($id, $status)
	{
        $this->db->where("ID_USER", $id);
		$this->db->update('internal_users', array('STATUS' => $status) );
	}
	
	function get_kota()
	{
		$this->db->select("*");
		$this->db->from("kota");
		$this->db->where("STATUS", 1);
		
		return $this->db->get();
	}
	
	function get_role()
	{
		$this->db->select("*");
		$this->db->from("roles");
		$this->db->where("STATUS", '1');
		
		return $this->db->get();
	}
	
	function insert_data($data)
	{
		$this->db->insert('internal_users', $data);
	}
	
	function get_byusername($user)
	{
		$this->db->select("*");
		$this->db->from("internal_users");
		$this->db->where("USERNAME", $user);
		
		return $this->db->get();
	}
	
	function update_data($id, $data)
	{
        $this->db->where("ID_USER", $id);
		$this->db->update('internal_users', $data);
	}
}//end of class

?>
