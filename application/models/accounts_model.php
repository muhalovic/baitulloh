<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accounts_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->CI = get_instance();
	}

        // get all account without waiting list
	function get_all_login()
	{
		$this->db->select("ID_ACCOUNT, KODE_REGISTRASI, NAMA_USER, EMAIL, PASSWORD");
		$this->db->from("accounts");
                $this->db->where("STATUS", 1);

		return $this->db->get();
	}

        function get_account($id_account, $kode){
		$this->db->select("*");
		$this->db->from("accounts");
                $this->db->where('ID_ACCOUNT', $id_account);
		$this->db->where("KODE_REGISTRASI", $kode);

		return $this->db->get();
	}
        
	function get_account_byKode($kode){
		$this->db->select("*");
		$this->db->from("accounts");
		$this->db->where("KODE_REGISTRASI", $kode);

		return $this->db->get();
	}

	function get_notification_info($kode){
		$this->db->select("*");
		$this->db->from("accounts");
		$this->db->join("province","province.ID_PROPINSI=accounts.ID_PROPINSI");
		$this->db->where("KODE_REGISTRASI", $kode);
		$this->db->where("accounts.STATUS", 0);

		return $this->db->get();
	}

	function insert_new_account($data){
		$this->db->trans_begin();
		$this->db->insert('accounts', $data);

		if ($this->db->trans_status() === FALSE)
			$this->db->trans_rollback();
		else
			$this->db->trans_commit();
	}

	function cek_forgot($email)
	{
		$this->db->select('KODE_REGISTRASI, NAMA_USER, EMAIL, PASSWORD');
		$this->db->from('accounts');
		$this->db->where('EMAIL', $email);

		return $this->db->get();
	}

	function get_data_account($id_account){
		$this->db->select('*');
		$this->db->from('accounts');
		$this->db->where('id_account',$id_account);

		return $this->db->get();
	}

	function update_password($data,$email) 
	{                
		$this->db->where('EMAIL', $email);
		$this->db->update('accounts' , $data);
	}

	function update_account($data, $kode_reg) 
	{                
		$this->db->where('KODE_REGISTRASI', $kode_reg);
		$this->db->update('accounts' , $data);
	}

	function update_with_id_account($data,$id_account)
	{
		$this->db->where('ID_ACCOUNT', $id_account);
		$this->db->update('accounts' , $data);
	}
	
	function get_grid_account(){
		
		$this->db->select("*");
		$this->db->from("accounts");
		$this->db->where("STATUS",1);
		$this->CI->flexigrid->build_query();
		
		$return['records'] = $this->db->get(); 
		
		$this->db->select("*");
		$this->db->from("accounts");
		
		$return['record_count'] = $this->db->get()->num_rows; 
		
		return $return;
		
	
		
	}
	
	function delete_account($id_account,$kode_registrasi){
		$this->db->where('ID_ACCOUNT',$id_account)->where('KODE_REGISTRASI',$kode_registrasi)->delete('accounts');
	}
	
	function is_email_enable($email){
		$this->db->select('*');
		$this->db->from('accounts');
		$this->db->where('EMAIL',$email);
		$list = $this->db->get()->result();
		
		if(empty($list)){
			return true;
		}
		else{
			return false;
		}
		
	}
	
	function is_email_active($email){
		$this->db->select('*');
		$this->db->from('accounts');
		$this->db->where('EMAIL',$email);
		$this->db->where('STATUS','1');
		$list = $this->db->get()->result();
		
		if(empty($list)){
			return false;
		}
		else{
			return true;
		}
		
	}

	function cek_login($email,$pass){
		$this->db->select('*');
		$this->db->from('accounts');
		$this->db->where('EMAIL',$email);
		$this->db->where('PASSWORD',$pass);
		$list = $this->db->get()->row();
		
		return $list;

		
	}
	
	
	
}

?>