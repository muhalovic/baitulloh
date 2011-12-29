<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Berkas_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		$this->CI = get_instance();
	}
	
	function fisik_grid()
	{	
		$this->db->select('b.*, a.ID_ACCOUNT, c.NAMA_LENGKAP, c.KODE_REGISTRASI, a.NAMA_USER');
		$this->db->from("jamaah_candidate c");
		$this->db->join('berkas_fisik b', 'c.ID_CANDIDATE = b.ID_CANDIDATE', 'left');
		$this->db->join('accounts a', 'c.ID_ACCOUNT = a.ID_ACCOUNT AND c.KODE_REGISTRASI = a.KODE_REGISTRASI', 'left');
		$this->db->where('c.STATUS_KANDIDAT >', 0); // Status Jamaah Aktif
		$this->CI->flexigrid->build_query();
		
		$return['records'] = $this->db->get(); 
		
		$this->db->select('b.*, a.ID_ACCOUNT, c.NAMA_LENGKAP, c.KODE_REGISTRASI, a.NAMA_USER');
		$this->db->from("jamaah_candidate c");
		$this->db->join('berkas_fisik b', 'c.ID_CANDIDATE = b.ID_CANDIDATE', 'left');
		$this->db->join('accounts a', 'c.ID_ACCOUNT = a.ID_ACCOUNT AND c.KODE_REGISTRASI = a.KODE_REGISTRASI', 'left');
		$this->db->where('c.STATUS_KANDIDAT >', 0);
		
		$return['record_count'] = $this->db->get()->num_rows; 
		
		return $return;
	}
	
	function get_fisik($id, $cand)
	{
		$this->db->select("*");
		$this->db->from("berkas_fisik");
		$this->db->where("ID_BERKAS", $id);
		$this->db->where("ID_CANDIDATE", $cand);
		
		return $this->db->get();
	}
	
	function ubah_fisik($id, $cand, $flag, $berkas)
	{
        $this->db->where("ID_BERKAS", $id);
        $this->db->where("ID_CANDIDATE", $cand);
		$this->db->update('berkas_fisik', array($berkas => $flag) );
	}
	
	//------------------ BERKAS TOOLKIT ----------------------------
	
	function toolkit_grid()
	{	
		$this->db->select('c.*, a.ID_ACCOUNT, a.NAMA_USER');
		$this->db->from("jamaah_candidate c");
		$this->db->join('accounts a', 'c.ID_ACCOUNT = a.ID_ACCOUNT AND c.KODE_REGISTRASI = a.KODE_REGISTRASI', 'left');
		$this->db->join('packet p', 'a.ID_ACCOUNT = p.ID_ACCOUNT', 'left');
		$this->db->where('c.STATUS_KANDIDAT >', 0); // Status Jamaah Aktif
		$this->db->where('p.STATUS_PESANAN', 4); // Pembayaran Lunas
		$this->CI->flexigrid->build_query();
		
		$return['records'] = $this->db->get(); 
		
		$this->db->select('c.*, a.ID_ACCOUNT, a.NAMA_USER');
		$this->db->from("jamaah_candidate c");
		$this->db->join('accounts a', 'c.ID_ACCOUNT = a.ID_ACCOUNT AND c.KODE_REGISTRASI = a.KODE_REGISTRASI', 'left');
		$this->db->join('packet p', 'a.ID_ACCOUNT = p.ID_ACCOUNT', 'left');
		$this->db->where('c.STATUS_KANDIDAT >', 0); // Status Jamaah Aktif
		$this->db->where('p.STATUS_PESANAN', 4); // Pembayaran Lunas
		
		$return['record_count'] = $this->db->get()->num_rows; 
		
		return $return;
	}
	
	function get_candidate($id)
	{
		$this->db->select("*");
		$this->db->from("jamaah_candidate");
		$this->db->where("ID_CANDIDATE", $id);
		
		return $this->db->get();
	}
	
	function ubah_toolkit($id, $flag)
	{
        $this->db->where("ID_CANDIDATE", $id);
		$this->db->update('jamaah_candidate', array( 'STATUS_KIRIM_TOOLKIT' => $flag ) );
	}
	
}//end of class

?>
