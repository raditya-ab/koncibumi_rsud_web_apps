<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('../vendor/autoload.php');
use \Firebase\JWT\JWT;

class Access extends CI_Controller {

	function __construct() {
		header('Access-Control-Allow-Origin: * ');
        header("Access-Control-Allow-Headers: * ");
        header("Access-Control-Allow-Methods: GET,POST,OPTIONS");

	    parent::__construct();
	    $this->load->model('access_model','access');
	    $this->config->load('config');

	    if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){

		    $auth = $this->access->check_header();

		    if ( $auth != true ){
		    	header("HTTP/1.1 401");
		    	$data['code'] = "401";
		    	$data['message'] = "HEADER NOT ALLOWED";
		    	echo json_encode($data);
		    	exit;
		    }
	    }

	}

	public function register($step){
		$obj = file_get_contents('php://input');
		$edata = json_decode($obj);

		if ( $step == "verification"){
			if ( isset($edata->bpjs_number)){
				$bpjs_number = $edata->bpjs_number;
				$medic_number = $edata->medic_number;
				$date_of_birth = date("Y-m-d", strtotime($edata->date_of_birth));
				
				$check_bpjs = "SELECT * FROM patient_login WHERE 1 AND ( no_bpjs = ? OR no_medrec = ? ) AND dob = ? ";
				$run_bpjs = $this->db->query($check_bpjs, array($bpjs_number,$medic_number,$date_of_birth));
				if ( $run_bpjs->num_rows() <= 0 ){
					header("HTTP/1.1 422 ");
			    	$data['code'] = "422 ";
			    	$data['message'] = "No BPJS atau No Medical tidak ada";
			    	echo json_encode($data);
				}

				$array_data = $run_bpjs->result_array();
				$first_name = $array_data[0]['first_name'];
				$last_name = $array_data[0]['last_name'];
				$patient_login_id = $array_data[0]['id'];
				$check_profile = "SELECT * FROM patient_profile WHERE 1 AND patient_login_id = ? ";
				$run_profile = $this->db->query($check_profile,array($patient_login_id));
				if ( $run_profile->num_rows() > 0 ){
					header("HTTP/1.1 422 ");
					$data['code'] = "422 ";
					$data['message'] = "Data sudah ada";
					echo json_encode($data);
					exit;
				}

				$data['code'] = "200";
				$data['message'] = "User can Register";
				echo json_encode($data);
			}else{

				header("HTTP/1.1 401");
		    	$data['code'] = "401";
		    	$data['message'] = "INVALID REQUEST";
		    	echo json_encode($data);
			}
		}else{
			$data_profile = array();
			$access_token = "";
			$profile_id = "";

			if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){

				if ( !(isset($edata->bpjs_number))) {
					header("HTTP/1.1 401");
			    	$data['code'] = "401";
			    	$data['message'] = "INVALID REQUEST";
			    	echo json_encode($data);
			    	exit();
				}

				$bpjs_number =  $edata->bpjs_number;
				$medic_number =  $edata->medic_number;
				$mobile_number = $edata->mobile_number;
				$temp_password =  $edata->password;
				$password = crypt($temp_password,'$6$rounds=5000$saltsalt$');
				$date_of_birth = date("Y-m-d", strtotime($edata->date_of_birth));

				$check_bpjs = "SELECT * FROM patient_login WHERE 1 AND ( no_bpjs = ? OR no_medrec = ? ) AND dob = ? ";
				$run_bpjs = $this->db->query($check_bpjs, array($bpjs_number,$medic_number,$date_of_birth));
				if ( $run_bpjs->num_rows() <= 0 ){
					header("HTTP/1.1 422 ");
					$data['code'] = "422 ";
					$data['message'] = "User data somehow can't retrieve";
					echo json_encode($data);
					exit;
				}
				$res_bpjs = $run_bpjs->result_array();
				$login_id = $res_bpjs[0]['id'];
				$first_name = $res_bpjs[0]['first_name'];
				$last_name = $res_bpjs[0]['last_name'];

				$array_insert = array(
					"patient_login_id" => $res_bpjs[0]['id'],
					"first_name" => $first_name,
					"last_name" => $last_name,
					"dob" => $date_of_birth,
					"created_at" => date("Y-m-d H:i:s"),
					"mobile_number" => $mobile_number,
					"bpjs_number" => $bpjs_number,
					"medical_number" => $medic_number
				);
				$this->db->insert("patient_profile", $array_insert);
				$patient_profile_id = $this->db->insert_id();
				$current_date = date("Y-m-d H:i:s");
		        $this->db->query("UPDATE patient_login set last_login='$current_date',last_activity = '$current_date' where id = '$login_id'");

		        $data['code'] = "200";
		    	$data['message'] = "Success Registrasi";
		    	echo json_encode($data);
			}
		}		

	}

	public function login(){
		$obj = file_get_contents('php://input');
		$edata = json_decode($obj);
		$bpjs_number = $edata->bpjs_number;
		$enc_password = crypt($edata->password,'$6$rounds=5000$saltsalt$');
		$check_bpjs = "SELECT * FROM patient_login WHERE 1 AND ( no_bpjs = ? OR no_medrec = ? ) AND password = ?";
		$run_bpjs = $this->db->query($check_bpjs,array($bpjs_number, $bpjs_number,$enc_password));
		if ( $run_bpjs->num_rows() <= 0 ){
			header("HTTP/1.1 403");
			$data['code'] = "403";
	    	$data['message'] = "User not authorized";
	    	echo json_encode($data);
	    	exit;
		}

		$data_user = $run_bpjs->result_array();
		$patient_login_id = $data_user[0]['id'];

		$qry_patient_profile = "SELECT * FROM patient_profile WHERE patient_login_id = ? ";
		$run_patient_profile = $this->db->query($qry_patient_profile, array($patient_login_id));
		if ( $run_patient_profile->num_rows() <= 0 ){
			header("HTTP/1.1 403");
			$data['code'] = "403";
	    	$data['message'] = "User not authorized ";
	    	echo json_encode($data);
	    	exit;
		}

		$res_patient_profile = $run_patient_profile->result_array();
		$secret_key = $this->config->item('secret_key');
		
		$data_profile = array();
		$data_profile['patient_login_id'] = $patient_login_id;
		$data_profile['patient_profile_id'] = $res_patient_profile[0]['id'];
		$data_profile['first_name'] = $res_patient_profile[0]['first_name'];
		$data_profile['last_name'] = $res_patient_profile[0]['last_name'];
		$data_profile['mobile_number'] = $res_patient_profile[0]['mobile_number'];
		$data_profile['address'] = $res_patient_profile[0]['address'];
		$data_profile['profile_pict'] = $res_patient_profile[0]['profile_pict'];
		$data_profile['bpjs_number'] = $res_patient_profile[0]['bpjs_number'];
		$data_profile['medic_number'] = $res_patient_profile[0]['medical_number'];

        $token = array(
            "iss" => $_SERVER['SERVER_NAME'],
            "iat" => strtotime($data_user[0]['date_created']),
            "profile_data" => $data_profile
        );
        $access_token = JWT::encode($token, $secret_key);

		$result_array = $run_bpjs->result_array();
		$this->db->query("UPDATE patient_login set last_activity = now(), last_login = now(), remember_token = '$access_token' WHERE id = '$patient_login_id'");

		$data['code'] = "200";
		$data['message'] = "User authorized";
		$data['access_token'] = $access_token;
			
		echo json_encode($data);

	}

	public function forgot_password(){
		$obj = file_get_contents('php://input');
		$edata = json_decode($obj);
		$bpjs_number = $edata->bpjs_number;
		$mobile_number = $edata->mobile_number;

		$check_bpjs = "SELECT pl.remember_token as remember_token FROM patient_login as pl 
			INNER JOIN patient_profile as pp ON (pl.id = pp.patient_login_id) 
			WHERE 1 AND ( pl.no_bpjs = ? OR pl.no_medrec = ? ) AND pp.mobile_number = ?";
		$run_bpjs = $this->db->query($check_bpjs,array($bpjs_number, $bpjs_number,$mobile_number));
		if ( $run_bpjs->num_rows() <= 0 ){
			header("HTTP/1.1 403");
			$data['code'] = "403";
	    	$data['message'] = "User not authorized ";
	    	echo json_encode($data);
	    	exit;
		}
		$res_bpjs = $run_bpjs->result_array();

		$data['code'] = "200";
    	$data['message'] = "User can Change Password ";
    	$data['token'] = $res_bpjs['0']['remember_token'];
    	echo json_encode($data);
    	exit;

	}

	public function change_password(){
		$obj = file_get_contents('php://input');
		$edata = json_decode($obj);

		if ( !(isset($_SERVER['HTTP_TOKEN']))) {

			header("HTTP/1.1 401");
			$data['code'] = "401";
			$data['message'] = "Invalid Header";
			echo json_encode($data);
			exit;
		}

		/*$token = $_SERVER['HTTP_TOKEN'];
		$password = $edata->password;
		$secret_key = $this->config->item('secret_key');
		$decoded = JWT::decode($token, $secret_key, array('HS256'));
		if ( isset($decoded->patient_login_id) ){
			$id = $decoded->patient_login_id;
			$temp_password =  $edata->password;
			$password = crypt($temp_password,'$6$rounds=5000$saltsalt$');
			$this->db->query("UPDATE patient_login set password = '$password' where id = '1'");
		}*/
		$this->db->query("UPDATE patient_login set password = '$password' where id = '1'");
		$data['code'] = "200";
		$data['message'] = "Success to change password";
		$data['token'] = $_SERVER['HTTP_TOKEN'];
		echo json_encode($data);
		

	}

	public function confirm_otp(){
		$obj = file_get_contents('php://input');
		$edata = json_decode($obj);
		$otp_key = "1234";
		if ( isset($edata->otp)){
			if ( $edata->otp != $otp_key ){
				header("HTTP/1.1 403");
				$data['code'] = "403";
		    	$data['message'] = "User not authorized ";
		    	echo json_encode($data);
		    	exit;
			}
		}

		$data['code'] = "200";
		$data['message'] = "User Register";
		echo json_encode($data);
		exit();
	}

	

}
?>