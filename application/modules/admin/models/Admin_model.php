<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {

  	function get_all_user(){
		$qry_all = "SELECT m.*,mg.name FROM members as m 
		INNER JOIN member_group as mm ON (mm.member_id = m.id)
		INNER JOIN master_group as mg ON (mm.group_id = mg.id )
		ORDER BY m.id DESC";
		$run_all = $this->db->query($qry_all);
		$res_all = $run_all->result_array();
		return $res_all;

	}

	function all_group_access(){
		$qry_all = "SELECT * FROM master_group ";
		$run_all = $this->db->query($qry_all);
		$res_all = $run_all->result_array();
		return $res_all;
	}

	function save_user($data){
		$this->db->insert("members", $data);
		$member_id = $this->db->insert_id();
		return $member_id;
	}

	function send_email($email,$password){
		$this->config->load('config');
		$config = $this->config->item('email');
		$this->email->initialize($config);

		$message = "<h3>Username  : ".$email."</h3>";
        $message .= "<h3>Password : ".$password."</h3>";
        $this->email->from('senderdummy89@gmail.com', 'Konci Bumi');
        $this->email->to($email); 
        $this->email->subject('Email Registrasi');
        $this->email->message($message);
		$this->email->send();		
	}

	function detail_user($user_id){
		$qry_all = "SELECT m.*,mg.name FROM members as m 
		INNER JOIN member_group as mm ON (mm.member_id = m.id)
		INNER JOIN master_group as mg ON (mm.group_id = mg.id )
		WHERE 1 
		AND m.id = ? ";
		$run_all = $this->db->query($qry_all,array($user_id));
		$res_all = $run_all->result_array();
		return $res_all;
	}

	function all_group(){
		$qry_all = "SELECT * FROM master_group";
		$run_all = $this->db->query($qry_all);
		$res_all = $run_all->result_array();
		return $res_all;
	}

	function save_group($data){
		$this->db->insert("member_group", $data);
		return true;
	}

	function all_docter(){
		$qry_all = "SELECT * FROM master_doctor";
		$run_all = $this->db->query($qry_all);
		$res_all = $run_all->result_array();
		return $res_all;
	}

}
