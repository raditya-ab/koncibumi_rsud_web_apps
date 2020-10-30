<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Access extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_user($user_id){
    	$qry_user = "SELECT * FROM master_doctor where 1 AND user_id = '$user_id'";
    	$run_user = $this->db->query($qry_user,array($user_id));
    	$res_user = $run_user->result_array();
    	return $res_user;

    }

    public function profile_patient($profile_id){
        $qry_profile = "SELECT * FROM patient_profile WHERE 1 AND id = ? ";
        $run_profile = $this->db->query($qry_profile,array($profile_id));
        $res_profile = $run_profile->result_array();
        return $res_profile;
    }
}

?>