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
	    $this->load->model('master_model','master');
	    $this->config->load('config');
	    $this->load->library('ciqrcode');
	    $this->load->library('zanzifa');

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

		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
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
				    	$data['message'] = "Registrasi Gagal. Maaf No. Rekam Medis yang anda masukan belum terdaftar sebagai pengguna aplikasi Koncibumi";
				    	echo json_encode($data);
				    	exit();
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
					$address = $res_bpjs[0]['address'];

					$array_insert = array(
						"patient_login_id" => $res_bpjs[0]['id'],
						"first_name" => $first_name,
						"last_name" => $last_name,
						"dob" => $date_of_birth,
						"created_at" => date("Y-m-d H:i:s"),
						"mobile_number" => $mobile_number,
						"bpjs_number" => $bpjs_number,
						"medical_number" => $medic_number,
						"address" => $address
					);
					$this->db->insert("patient_profile", $array_insert);
					$patient_profile_id = $this->db->insert_id();
					$current_date = date("Y-m-d H:i:s");

			        $this->db->query("UPDATE patient_login set last_login='$current_date',last_activity = '$current_date', password ='$password' where id = '$login_id'");

			        

			        $otp_key = $this->master->generateOtp();
			    	$sms = $this->zanzifa->sender($otp_key,$mobile_number);
			    	
			        $this->db->query("UPDATE patient_login set last_login='$current_date',last_activity = '$current_date', password ='$password',verification_code='$otp_key' where id = '$login_id'");

			        $data_profile = array();
					$data_profile['patient_login_id'] = $res_bpjs[0]['id'];
					$data_profile['patient_profile_id'] = $patient_profile_id;
					$data_profile['first_name'] = $first_name;
					$data_profile['last_name'] = $last_name;
					$data_profile['mobile_number'] = $mobile_number;
					$data_profile['address'] = $address;
					$data_profile['profile_pict'] = NULL;
					$data_profile['bpjs_number'] = $bpjs_number;
					$data_profile['medic_number'] = $medic_number;
					$data_profile['date_of_birth'] = date("d/m/Y",strtotime($res_bpjs[0]['dob']));

			        $token = array(
			            "iss" => $_SERVER['SERVER_NAME'],
			            "iat" => strtotime($res_bpjs[0]['date_created']),
			            "profile_data" => $data_profile
			        );
			        $secret_key = $this->config->item('secret_key');
			        $access_token = JWT::encode($token, $secret_key);


			        $data['code'] = "200";
			    	$data['message'] = "Success Registrasi";
			    	$data['access_token'] = $access_token;
			    	echo json_encode($data);
				}
			}		

		}
		
	}

	public function login(){

		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
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
			$array_notif = array(
				"0" => false,
				"1" => true,
				"" => false,
				NULL => false
			);
			
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
			$data_profile['date_of_birth'] = date("d/m/Y",strtotime($res_patient_profile[0]['dob']));
			$data_profile['push_notif'] = $array_notif[$res_patient_profile[0]['notif_app']];
			$data_profile['sms_notif'] = $array_notif[$res_patient_profile[0]['notif_sms']];

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

	}

	public function forgot_password(){
		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
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
	}

	public function change_password(){

		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
			$obj = file_get_contents('php://input');
			$edata = json_decode($obj);

			if ( !(isset($_SERVER['HTTP_TOKEN']))) {

				header("HTTP/1.1 401");
				$data['code'] = "401";
				$data['message'] = "Invalid Header";
				echo json_encode($data);
				exit;
			}

			$secret_key = $this->config->item('secret_key');
			$decoded = JWT::decode($_SERVER['HTTP_TOKEN'], $secret_key, array('HS256')) ;
			$patient_login_id = $decoded->profile_data->patient_login_id;
			$mobile_number = $decoded->profile_data->mobile_number;
			$otp_key = $this->master->generateOtp();
			$sms = $this->zanzifa->sender($otp_key,$mobile_number);
	        $temp_password =  $edata->password;
	        $confirm_password = $edata->confirm_password;
	        if ( $temp_password != $confirm_password ){
	        	header("HTTP/1.1 403");
				$data['code'] = "403";
		    	$data['message'] = "New and Confirm Password not match";
		    	echo json_encode($data);
		    	exit;
	        }

			$password = crypt($temp_password,'$6$rounds=5000$saltsalt$');

			$check_old_pass = "SELECT * FROM patient_login WHERE 1 AND id = ?";
			$run_old_pass = $this->db->query($check_old_pass,array($patient_login_id));
			if ( $run_old_pass->num_rows() > 0 ){
				$res_old_pass = $run_old_pass->result_array();
				$old_password = $res_old_pass[0]['password'];
				if ( $old_password == $password ){
					header("HTTP/1.1 403");
					$data['code'] = "403";
			    	$data['message'] = "Password has been used. Please use new password ";
			    	echo json_encode($data);
			    	exit;
				}
			}
			
			$this->db->query("UPDATE patient_login set password = '$password',verification_code='$otp_key' where id = $patient_login_id");

			$data['code'] = "200";
			$data['message'] = "Success to change password";
			$data['token'] = $_SERVER['HTTP_TOKEN'];
			echo json_encode($data);
		}

	}


	public function generateQR(){
		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
			header("Content-Type: image/png");
			$order_id = $_GET['order_id'];
			
			$params['data'] = base_url().'courier/finish/?order_id='.$order_id;
			$params['savename'] = './assets/order/order_'.$order_id.'_qr.png';
			$generate = $this->ciqrcode->generate($params);
			$qr_path = base_url().str_replace("./assets", "assets", $params['savename']);
			$this->db->query("UPDATE order_patient set qr = '$qr_path' where id = '$order_id'");
			return $qr_path;
		}
	}


	public function confirm_otp(){
		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
			$obj = file_get_contents('php://input');
			$edata = json_decode($obj);
			$otp_key = "1234";

			$access_token = $_SERVER['HTTP_TOKEN'];
			$secret_key = $this->config->item('secret_key');
			$decoded = JWT::decode($access_token, $secret_key, array('HS256'));
			$patient_login_id = $decoded->profile_data->patient_login_id;

			$qry_token = "SELECT * FROM patient_login WHERE 1 AND id = ? ";
			$run_token = $this->db->query($qry_token,array($patient_login_id));

			if ( !(isset($edata->otp)) || $run_token->num_rows() <= 0 ){
				header("HTTP/1.1 403");
				$data['code'] = "403";
		    	$data['message'] = "User not authorized ";
		    	echo json_encode($data);
		    	exit;
				
			}

			$res_token = $run_token->result_array();
			$otp_key = $res_token[0]['verification_code'];

			if ( $edata->otp != $otp_key ){
				header("HTTP/1.1 403");
				$data['code'] = "403";
		    	$data['message'] = "User not authorized ";
		    	echo json_encode($data);
		    	exit();
			}

			$data['code'] = "200";
			$data['message'] = "User Register";
			echo json_encode($data);
			exit();
		}

	}

	public function random(){
	
		$bpjs = "";
		for ( $i=0; $i<=9; $i++){
			$bpjs .= rand(0,9);
		}

		$medic_number = "";
		for ( $i=0; $i<=9; $i++){
			$medic_number .= rand(0,9);
		}

		$this->db->query("INSERT INTO patient_login (no_bpjs,no_medrec,dob) VALUES ('$bpjs','$medic_number','1980-07-13')");

		$data['bpjs'] = $bpjs;
		$data['medic_number'] = $medic_number;
		$data['dob'] = "1980-07-13";
		echo json_encode($data);
	}

	

}
?>