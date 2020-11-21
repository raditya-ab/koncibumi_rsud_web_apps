<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Login_model extends CI_Model {
	
  	function check_login($email,$password){
		$data = array();
		$enc_password = crypt($password,'$6$rounds=5000$saltsalt$');
		$qry_data = "SELECT * FROM members WHERE 1 AND email = ? AND password = ?";
		$run_data = $this->db->query($qry_data,array($email,$enc_password));
		if ( $run_data->num_rows() > 0 ){
			$res_data = $run_data->result_array();
			$member_id = $res_data[0]['id'];
			$data = $this->get_profile_data($member_id);
		}

 		return $data;
  	}
	  
    function get_profile_data($user_id){
		$this->config->load('config');
		$data = array();
		$qry_member = "SELECT mg.member_id, mm.name,m.email,m.id,m.username FROM member_group as mg 
		INNER JOIN master_group as mm ON ( mm.id = mg.group_id )
		INNER JOIN members as m ON ( m.id = mg.member_id )
		where 1 
		AND mg.member_id = ?
		AND m.status = 1 ";
		$run_member = $this->db->query($qry_member,array($user_id));
		$res_member = $run_member->result_array();
		if ( $run_member->num_rows() > 0 ){
			foreach ( $res_member as $key => $value ){
				$list_menu['menu'] = $value['name'];
				$list_menu['url'] = $this->config->item('route_access')[strtolower($value['name'])];
			}
			$data['menu'] = $list_menu;
			$data['email'] = $res_member[0]['email'];
			$data['menu_name'] = $res_member[0]['name'];
			$data['member_id'] = $res_member[0]['id'];
			$data['username'] = $res_member[0]['username'];
			$_SESSION['user_id'] = ($res_member[0]['id']);
		}
		return $data;
	}
}
