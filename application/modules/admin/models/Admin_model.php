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
		$array_group = array();
		foreach ($res_all as $key => $value) {
			$can_remove = true;
			$group_id = $value['id'];
			$check_member_group = "SELECT mg.* FROM member_group as mg
				INNER JOIN members ON (members.id = mg.member_id)
				WHERE 1 AND mg.group_id = ? ";
			$run_member_group = $this->db->query($check_member_group, $group_id);
			if ( $run_member_group->num_rows() > 0 ){
				$can_remove = false;
			}
			$array_group[] = array(
				"name" => $value['name'],
				'id' => $value['id'],
				'can_remove' => $can_remove
			);
		}
		return $array_group;
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

	function tnc(){
		$qry_tnc = "SELECT * FROM tnx order by id DESC limit 0,1";
		$run_tnc = $this->db->query($qry_tnc);
		$res_tnc = $run_tnc->result_array();
		return $res_tnc;
	}

	function about(){
		$qry_about = "SELECT * FROM about order by id DESC limit 0,1";
		$run_about = $this->db->query($qry_about);
		$res_about = $run_about->result_array();
		return $res_about;
	}


	function kurir(){
		$qry_kurir = "SELECT * FROM master_kurir order by ID DESC ";
		$run_kurir = $this->db->query($qry_kurir);
		$res_kurir = $run_kurir->result_array();
		return $res_kurir;
	}

	function all_expired_order(){
		$limit_date = date("Y-m-d H:i:s",strtotime("-7 days"));
		$qry_expired = "SELECT op.*, pp.first_name, pp.last_name,
			(SELECT poli FROM kunjungan WHERE patient_id = op.patient_id LIMIT 1) as poli,
			md.first_name as doctor_name
			FROM order_patient as op 
			INNER JOIN patient_profile as pp ON (pp.id = op.patient_id )
			LEFT JOIN master_doctor as md ON (md.id = op.doctor_id )
			WHERE op.status = '1' AND op.created_at <= '$limit_date' ORDER by op.id";
		$run_expired = $this->db->query($qry_expired);
		$res_kurir = $run_expired->result_array();
		return $res_kurir;
	}

	function all_patient(){
		$query = "SELECT * FROM patient_login";
		$run = $this->db->query($query);
		return $run->result_array();
	}

	function detail_patient($pasien_id){
		$query = "SELECT * FROM patient_login WHERE 1 AND id = ? ";
		$run = $this->db->query($query,array($pasien_id));
		$result = $run->result_array();
		return $result;
	}

	function all_blocking_patient(){
		$query = "SELECT op.*, pp.first_name as first_name, pp.bpjs_number as bpjs_number, 
			pp.medical_number as medical_number , md.first_name as doctor_name
			FROM order_patient as op 
			INNER JOIN patient_profile as pp on ( pp.id = op.patient_id )
			LEFT JOIN master_doctor as md on ( md.id = op.doctor_id)
			WHERE 1 AND op.status = 1 AND op.keluhan = 1 order by op.id desc ";
		$run = $this->db->query($query);
		$result = $run->result_array();
		return $result;
	}

	function all_poli(){
		$array_poli = array();
		$query = "SELECT DISTINCT poli FROM master_doctor";
		$run = $this->db->query($query);
		$res = $run->result_array();
		foreach ($res as $key => $value) {
			array_push($array_poli, $value['poli']);
		}
		return $array_poli;
	}

	function get_profile_patient($patient_login_id){
		$qry_profile = "SELECT * FROM patient_profile WHERE 1 AND patient_login_id = ? ";
		$run_profile = $this->db->query($qry_profile,array($patient_login_id));
		$res_profile = $run_profile->result_array();
		return $res_profile;
	}

	function get_rujukan($patient_profile_id){
		$qry_rujukan = "SELECT pr.*, md.first_name as doctor FROM patient_rujukan as pr 
		INNER JOIN master_doctor as md ON (md.id = pr.doctor_id)
		WHERE 1 AND pr.patien_profile_id = ?
		ORDER BY pr.end_date DESC";
		$run_rujukan = $this->db->query($qry_rujukan,array($patient_profile_id));
		$res_rujukan = $run_rujukan->result_array();
		return $res_rujukan;
	}

	function detail_group($id){
		$qry_detail_group = "SELECT mg.*, mr.username as username , mr.email as email FROM member_group as mg 
			INNER JOIN members as mr ON (mg.member_id = mr.id )
			WHERE 1 AND mg.group_id = ?";
		$run_detail_group = $this->db->query($qry_detail_group,array($id));
		$res_detail_group = $run_detail_group->result_array();
		return $res_detail_group;
	}

}
