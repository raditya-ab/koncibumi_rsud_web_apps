<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('../vendor/autoload.php');
use \Firebase\JWT\JWT;

class Profile extends CI_Controller {

	function __construct() {
		header('Access-Control-Allow-Origin: * ');
        header("Access-Control-Allow-Headers: * ");
        header("Access-Control-Allow-Methods: GET,POST,OPTIONS");

		
	    parent::__construct();
	    $this->load->model('access_model','access');
	    $this->load->model('profile_model','profile');
	    $this->load->model('master_model','master');
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

		    if ( !(isset($_SERVER['HTTP_TOKEN']))) {
		    	header("HTTP/1.1 401");
		    	$data['code'] = "401";
		    	$data['message'] = "HEADER TOKEN NOT AVAILABLE";
		    	echo json_encode($data);
		    	exit;
		    }
	    }
	    
	}

	public function detail_profile(){

		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
			$access_token = $_SERVER['HTTP_TOKEN'];
			if ( $this->profile->check_token($access_token) == false ){
				header("HTTP/1.1 401");
		    	$data['code'] = "401";
		    	$data['message'] = "HEADER TOKEN NOT MATCH";
		    	echo json_encode($data);
		    	exit;
			}

			$enabled_order = "enabled";
			$secret_key = $this->config->item('secret_key');
			$decoded = JWT::decode($access_token, $secret_key, array('HS256'));
			if ( !(isset($decoded->profile_data->patient_profile_id))) {
				header("HTTP/1.1 401");
		    	$data['code'] = "401";
		    	$data['message'] = "INVALID TOKEN";
		    	echo json_encode($data);
		    	exit;
			}

			$patient_profile_id = $decoded->profile_data->patient_profile_id;
			$qry_check_order = "SELECT * FROM order_patient WHERE 1 AND patient_id = ? order by id DESC LIMIT 0,1";
			$run_check_order = $this->db->query($qry_check_order, array($patient_profile_id));
			if ( $run_check_order->num_rows() > 0 ){
				$data_order = $run_check_order->result_array();
				if ( $data_order[0]['keluhan'] == 1 ){
					$now = strtotime(date("Y-m-d H:i:s"));
					$order_time = strtotime($data_order[0]['created_at']);
					$diff = ($now - $order_time) / (60 * 60 * 24);
					if ( $diff <= 7 ){
						$enabled_order = "disabled";
					}
				}
			}

			$data['code'] = "200";
	    	$data['message'] = "Success Profile";
	    	$data['token'] = $access_token;
	    	$data['order'] = $enabled_order;

	    	echo json_encode($data);
	    	exit();
    	}

    	$data['code'] = "200";
    	echo json_encode($data);
	}

	public function order(){
		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
			$obj = file_get_contents('php://input');
			$edata = json_decode($obj);
			$access_token = $_SERVER['HTTP_TOKEN'];

		
			if ( $this->profile->check_token($access_token) == false ){
				header("HTTP/1.1 401");
				$data['code'] = "401";
				$data['message'] = "INVALID TOKEN";
				echo json_encode($data);
				exit();
			}


			if ( !(isset($edata->bpjs_number))) {
				header("HTTP/1.1 401");
				$data['code'] = "401";
				$data['message'] = "INVALID REQUEST";
				echo json_encode($data);
				exit();
			}

			$check_bpjs = "SELECT pl.remember_token as remember_token, pp.id as patient_profile_id,pl.id as patient_login_id,pp.address as delivery_address FROM patient_login as pl 
				INNER JOIN patient_profile as pp ON (pl.id = pp.patient_login_id) 
				WHERE 1 AND ( pl.no_bpjs = ? OR pl.no_medrec = ? ) ";
			$run_bpjs = $this->db->query($check_bpjs,array($edata->bpjs_number, $edata->bpjs_number));
			if ( $run_bpjs->num_rows() <= 0 ){
				header("HTTP/1.1 401");
				$data['code'] = "401";
				$data['message'] = "Data can't retrieve";
				echo json_encode($data);
				exit();
			}

			$res_bpjs = $run_bpjs->result_array();
			$profile_id = $res_bpjs[0]['patient_profile_id'];
			$patient_login_id = $res_bpjs[0]['patient_login_id'];

			$doctor_id = "";
			$qry_get_latest_visit = "SELECT * FROM kunjungan WHERE 1 AND patient_login_id = ? ";
			$run_get_latest_visit = $this->db->query($qry_get_latest_visit,array($patient_login_id));
			if ( $run_get_latest_visit->num_rows() > 0 ){
				$res_get_latest_visit = $run_get_latest_visit->result_array();
				$doctor_id = $res_get_latest_visit[0]['doctor_id'];
			}


			$array_insert = array(
				"patient_id" => $profile_id,
				"delivery_date" => date("Y-m-d H:i:s"),
				"created_at" => date("Y-m-d H:i:s"),
				"status" => 1,
				"keluhan" => $edata->complaint,
				'description' => $edata->complaint_description,
				"doctor_id" => $doctor_id,
				"delivery_address" => $res_bpjs[0]['delivery_address']
			);
			$this->db->insert("order_patient", $array_insert);
			$order_id = $this->db->insert_id();

			if ( $edata->complaint == "1" ){
				header("HTTP/1.1 406");
				$data['code'] = "406";
				$data['message'] = "Order have complaint";
				echo json_encode($data);
				exit();
			}
			
			$order_no = "KNCBM".$order_id;
			$this->db->query("UPDATE order_patient set order_no = '$order_no' WHERE id ='$order_id'");
			$data['code'] = "200";
			$data['message'] = "Success Order";
			echo json_encode($data);
			exit();
		}

		$data['code'] = "200";
		echo json_encode($data);
		
	}

	public function update_address(){
		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
			$obj = file_get_contents('php://input');
			$edata = json_decode($obj);
			$access_token = $_SERVER['HTTP_TOKEN'];

		
			if ( $this->profile->check_token($access_token) == false ){
				header("HTTP/1.1 401");
				$data['code'] = "401";
				$data['message'] = "INVALID TOKEN";
				echo json_encode($data);
				exit();
			}


			if ( !(isset($edata->address))) {
				header("HTTP/1.1 401");
				$data['code'] = "401";
				$data['message'] = "INVALID REQUEST";
				echo json_encode($data);
				exit();
			}
			$address = $edata->address;
			$latitude = $edata->latitude;
			$longitude = $edata->longitude;

			$secret_key = $this->config->item('secret_key');
			$status_order = $this->config->item('status_order');
			$decoded = JWT::decode($access_token, $secret_key, array('HS256'));
			$patient_profile_id = $decoded->profile_data->patient_profile_id;
			$patient_login_id = $decoded->profile_data->patient_login_id;
			$this->db->query("UPDATE patient_profile set address = '$address',latitude = '$latitude', longitude = '$longitude' where id = '$patient_profile_id'");
			$this->db->query("UPDATE patient_login set address = '$address' where id = '$patient_login_id'");

			$data['code'] = "200";
			$data['message'] = "Address has been updated";
			echo json_encode($data);
			exit();
		}

		$data['code'] = "200";
		echo json_encode($data);
	}

	public function history(){
		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
			$access_token = $_SERVER['HTTP_TOKEN'];
			$status = true;
			if ( isset($_GET['status']) ) {
				$status = $_GET['status'];
			}

			if ( $this->profile->check_token($access_token) == false ){
				header("HTTP/1.1 401");
				$data['code'] = "401";
				$data['message'] = "INVALID TOKEN";
				echo json_encode($data);
				exit();
			}

			$whereClause = " AND status != '6'";
			if ( $status == "false" ){
				$whereClause = " AND status = '6'";
			}


			$secret_key = $this->config->item('secret_key');
			$status_order = $this->config->item('status_order');
			$decoded = JWT::decode($access_token, $secret_key, array('HS256'));
			$patient_profile_id = $decoded->profile_data->patient_profile_id;
			$array_history = array();
			$qry_check_order = "SELECT * FROM order_patient WHERE 1 AND patient_id = ? $whereClause";
			$run_check_order = $this->db->query($qry_check_order,array($patient_profile_id));
			if ( $run_check_order->num_rows() > 0 ){
				$res_check_order = $run_check_order->result_array();
				$profile_id = $res_check_order[0]['patient_id'];

				$check_profile = "SELECT * FROM patient_profile WHERE 1 AND patient_login_id = ? ";
				$run_profile = $this->db->query($check_profile,array($profile_id));
				$detail_profile = array();
				
				if ( $run_profile->num_rows() > 0 ){
					$res_profile = $run_profile->result_array();
					$detail_profile['bpjs_number'] = $res_profile[0]['bpjs_number'];
					$detail_profile['medic_number'] = $res_profile[0]['medical_number'];
					$detail_profile['shipping_method'] = $res_check_order[0]['delivery_type'];
					$detail_profile['received_date'] = date("d-M-Y",strtotime($res_check_order[0]['delivery_date']));
					$detail_profile['address'] = $res_check_order[0]['delivery_address'];
					$detail_profile['lat'] = $res_profile[0]['latitude'];
					$detail_profile['long'] = $res_profile[0]['longitude'];
				}

				$qry_check_receipt = "SELECT * FROM receipt_header WHERE 1 AND kunjungan_id = ? ";
				$run_check_receipt = $this->db->query($qry_check_receipt, array($res_check_order[0]['id']));
				$array_list_obat = array();
				if ( $run_check_receipt->num_rows() > 0 ){
					$res_check_receipt = $run_check_receipt->result_array();
					$receipt_id = $res_check_receipt[0]['id'];
					$restricted = $res_check_receipt[0]['restricted'];
					$restricted_drug = false;
					if ( $restricted == "1"){
						$restricted_drug = true;
					}
					$detail_profile['restricted_drugs'] = $restricted_drug;

					$qry_detail_receipt = "SELECT * FROM receipt_detail where 1 AND receipt_header_id = ? ";
					$run_detail_receipt = $this->db->query($qry_detail_receipt,array($receipt_id));
					if ( $run_detail_receipt->num_rows() > 0 ){
						$res_detail_recept = $run_detail_receipt->result_array();
						foreach ($res_detail_recept as $key_detail_receipt => $value_detail_receipt) {
							$obat_id = $value_detail_receipt['obat'];
							$qry_get_obat = "SELECT * FROM master_medicine WHERE 1 AND id = ? ";
							$run_get_obat = $this->db->query($qry_get_obat,array($obat_id));
							if ( $run_get_obat->num_rows() > 0 ){
								$res_get_obat = $run_get_obat->result_array();
								$detail_obat = array();
								$detail_obat['name'] = $res_get_obat[0]['name'];
								$detail_obat['group'] = $res_get_obat[0]['golongan'];
								$detail_obat['quantity'] = $value_detail_receipt['dosis'];
								$detail_obat['limit'] = $res_get_obat[0]['qty'];
								$detail_obat['unit_type'] = $res_get_obat[0]['satuan'];
								$array_list_obat[] = $detail_obat;
							}
						}
					}
				}

				
				$detail_profile['ordered_drugs'] = $array_list_obat;

				$detail_array_history = array();
				$detail_array_history['id'] = $res_check_order[0]['id'];
				$detail_array_history['order_number'] = "AA".$res_check_order[0]['id'];
				$detail_array_history['order_date'] = date("d-M-Y", strtotime($res_check_order[0]['created_at']));
				$detail_array_history['doctor_name'] = "Docter A";
				$detail_array_history['status'] = $status_order[$res_check_order[0]['status']];
				$detail_array_history['qr'] = "www.google.com";
				$detail_array_history['details'] = $detail_profile;
				$array_history[] = $detail_array_history;

			}

			$data['code'] = "200";
			$data['message'] = "All history loaded";
			$data['history'] = $array_history;
			echo json_encode($data);
			exit();
		}
		$data['code'] = "200";
		echo json_encode($data);
		
	}

	public function detail_order(){
		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
			$access_token = $_SERVER['HTTP_TOKEN'];
			$order_id = $_GET['order_id'];
			$status_order = $this->config->item('status_order');

			if ( $this->profile->check_token($access_token) == false ){
				header("HTTP/1.1 401");
				$data['code'] = "401";
				$data['message'] = "INVALID TOKEN";
				echo json_encode($data);
				exit();
			}

			$qry_check_order = "SELECT * FROM order_patient WHERE 1 AND id = ? ";
			$run_check_order = $this->db->query($qry_check_order,array($order_id));
			if ( $run_check_order->num_rows() > 0 ){
				$res_check_order = $run_check_order->result_array();
				$profile_id = $res_check_order[0]['patient_id'];

				$check_profile = "SELECT * FROM patient_profile WHERE 1 AND patient_login_id = ? ";
				$run_profile = $this->db->query($check_profile,array($profile_id));
				$detail_profile = array();

				if ( $run_profile->num_rows() > 0 ){
					$res_profile = $run_profile->result_array();
					$detail_profile['bpjs_number'] = $res_profile[0]['bpjs_number'];
					$detail_profile['medic_number'] = $res_profile[0]['medical_number'];
					$detail_profile['shipping_method'] = $res_check_order[0]['delivery_type'];
					$detail_profile['received_date'] = date("d-M-Y",strtotime($res_check_order[0]['delivery_date']));
					$detail_profile['address'] = $res_check_order[0]['delivery_address'];
					$detail_profile['lat'] = $res_profile[0]['latitude'];
					$detail_profile['long'] = $res_profile[0]['longitude'];
				}

				$qry_check_receipt = "SELECT * FROM receipt_header WHERE 1 AND kunjungan_id = ? ";
				$run_check_receipt = $this->db->query($qry_check_receipt, array($res_check_order[0]['id']));
				$array_list_obat = array();
				if ( $run_check_receipt->num_rows() > 0 ){
					$res_check_receipt = $run_check_receipt->result_array();
					$receipt_id = $res_check_receipt[0]['id'];

					$qry_detail_receipt = "SELECT * FROM receipt_detail where 1 AND receipt_header_id = ? ";
					$run_detail_receipt = $this->db->query($qry_detail_receipt,array($receipt_id));
					if ( $run_detail_receipt->num_rows() > 0 ){
						$res_detail_recept = $run_detail_receipt->result_array();
						foreach ($res_detail_recept as $key_detail_receipt => $value_detail_receipt) {
							$obat_id = $value_detail_receipt['obat'];
							$qry_get_obat = "SELECT * FROM master_medicine WHERE 1 AND id = ? ";
							$run_get_obat = $this->db->query($qry_get_obat,array($obat_id));
							if ( $run_get_obat->num_rows() > 0 ){
								$res_get_obat = $run_get_obat->result_array();
								$detail_obat = array();
								$detail_obat['name'] = $res_get_obat[0]['name'];
								$detail_obat['group'] = $res_get_obat[0]['golongan'];
								$detail_obat['quantity'] = $value_detail_receipt['dosis'];
								$detail_obat['limit'] = $res_get_obat[0]['qty'];
								$detail_obat['unit_type'] = $res_get_obat[0]['satuan'];
								$array_list_obat[] = $detail_obat;
							}
						}
					}
				}

				$detail_array_history = array();
				$detail_array_history['id'] = $res_check_order[0]['id'];
				$detail_array_history['order_number'] = "AA".$res_check_order[0]['id'];
				$detail_array_history['order_date'] = date("d-M-Y", strtotime($res_check_order[0]['created_at']));
				$detail_array_history['doctor_name'] = "Docter A";
				$detail_array_history['status'] = $status_order[$res_check_order[0]['status']];
				$detail_array_history['qr'] = "www.google.com";
				$detail_array_history['details'] = $detail_profile;
			}

			$data['status'] = "200";
			$data['message'] = "All history loaded";
			$data['history'] = $detail_array_history;
			echo json_encode($data);
			exit();
		}

		$data['code'] = "200";
		echo json_encode($data);
		
	}

	public function update_pesanan(){
		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
			$obj = file_get_contents('php://input');
			$edata = json_decode($obj);
			if ( isset($edata->order_id)) {
				$status = $edata->status;
				$order_id = $edata->order_id;
				$this->db->query("UPDATE order_patient set status = '$status' where order_id = '$order_id'");
				$data['code'] = "200";
				$data['message'] = "Order ".$order_id ." has been update to status ".$status;
				echo json_encode($data);
				exit();
			}
		}
		$data['code']= "200";
		echo json_encode($data);
	}


	public function edit_profile(){
		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
			$access_token = $_SERVER['HTTP_TOKEN'];
			$secret_key = $this->config->item('secret_key');
			$decoded = JWT::decode($access_token, $secret_key, array('HS256'));

			$obj = file_get_contents('php://input');
			$edata = json_decode($obj);
			if ( isset($edata->fullname)){
				$address = $edata->address;
				$gender = $edata->gender;
				$first_name = "";
				$last_name = "";

				$explode =explode(" ",$edata->fullname);
				if ( count($explode) > 1 ){
					$first_name = $explode[0];
					$last_name = str_replace($explode[0], "", $edata->fullname);
				}else{
					$first_name = $edata->fullname;
				}

				$patient_profile_id = $decoded->profile_data->patient_profile_id;
				$patient_login_id = $decoded->profile_data->patient_login_id;
				
				$array_update = array(
					"first_name" => $first_name,
					"last_name" => $last_name,
					"mobile_number" => $edata->mobile_number,
					"address" => $edata->address,
					"latitude" => $edata->lat,
					"longitude" => $edata->long
 				);

 				$this->db->where("id",$patient_profile_id);
 				$this->db->update("patient_profile",$array_update);
 				$this->db->query("UPDATE patient_login SET address = '$address', gender = '$gender' WHERE id = '$patient_login_id'");
			}
		}
		$data['code'] = "200";
		$data['message'] = "Data has been updated";
		echo json_encode($data);
	}

	public function about(){
		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
			$qry_about = "SELECT * FROM about ";
			$run_about = $this->db->query($qry_about);
			$res_about = $run_about->result_array();
			$data['code'] = "200";
			$data['content'] = $res_about[0]['content'];
			echo json_encode($data);
			exit();
		}
	}

	public function tnc(){
		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
			$qry_tnc = "SELECT * FROM tnx ";
			$run_tnc = $this->db->query($qry_tnc);
			$res_tnc = $run_tnc->result_array();
			$tnc = array();
			if ( count($res_tnc) > 0 ){
				foreach ($res_tnc as $key => $value) {
					$detail_tnc = array();
					$detail_tnc['id'] = $value['id'];
					$detail_tnc['content'] = $value['content'];
					$tnc[] = $detail_tnc;
				}
			}

			$data['code'] = "200";
			$data['content'] = $tnc;
			echo json_encode($data);
			exit();
		}
	}

	public function logout(){
		session_destroy();
		$secret_key = $this->config->item('secret_key');
		$access_token = $_SERVER['HTTP_TOKEN'];
		if ( $this->profile->check_token($access_token) == false ){
			header("HTTP/1.1 401");
			$data['code'] = "401";
			$data['message'] = "INVALID TOKEN";
			echo json_encode($data);
			exit();
		}
		$decoded = JWT::decode($access_token, $secret_key, array('HS256'));
		$patient_login_id = $decoded->profile_data->patient_login_id;

		$this->db->query("UPDATE patient_login set remember_token = '' WHERE 1 AND id = '$patient_login_id'");
		$data['code'] = "200";
		$data['message'] = "Success Logout";
		echo json_encode($data);
		exit();

	}

	public function version(){
		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
			$obj = file_get_contents('php://input');
			$edata = json_decode($obj);
			if ( isset($edata->current_version)) {
				$qry_version = $this->db->query("SELECT * FROM tag_version order by id desc");
				$run_version = $qry_version->result_array();	
				$data['code'] = "200";
				$data['latest_version'] = $run_version[0]['version'];
				$data['mandatory_update'] = true;
				if ( $run_version[0]['version'] == $edata->current_version ){
					$data['mandatory_update'] = false;
					$data['url_apps'] = "https://play.google.com/store/apps/details?id=com.halfbrick.fruitninjafree";
				}
				echo json_encode($data);
				exit();
			}
		}
	}

	public function faq(){
		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
			$list_faq = array();

			for ( $i=0; $i<2; $i++){
				$detail_list_faq['question'] = "<h3>Question ".$i."</h3>";
				$detail_list_faq['answer'] = "<p>Sample Faq Content ".$i."</p>";
				$list_faq[] = $detail_list_faq;
			}

			$data['code'] = "200";
			$data['faq'] = $list_faq;
			echo json_encode($data);
			exit();
		}
	}


	public function contact(){
		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
			$obj = file_get_contents('php://input');
			$edata = json_decode($obj);
			if ( isset($edata->question_type)){
				$access_token = $_SERVER['HTTP_TOKEN'];
				$secret_key = $this->config->item('secret_key');
				$decoded = JWT::decode($access_token, $secret_key, array('HS256'));
				$patient_profile_id = $decoded->profile_data->patient_profile_id;
				$array_insert = array(
					"profile_id" => $patient_profile_id,
					"question_type" => $edata->question_type,
					"question_subject" => $edata->question_subject,
					"question_content" => $edata->question_content,
					"created_at" =>date("Y-m-d H:i:s")
				);
				$this->db->insert("contact",$array_insert);
			}
		}

		$data['code'] = "200";
		$data['message'] = "Success Submit";
		echo json_encode($data);
		exit();
	}

	public function update_notifikasi(){
		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
			$obj = file_get_contents('php://input');
			$edata = json_decode($obj);

			$access_token = $_SERVER['HTTP_TOKEN'];
			$secret_key = $this->config->item('secret_key');
			$decoded = JWT::decode($access_token, $secret_key, array('HS256'));
			$type = $edata->type;
			$status = $edata->status;
			$array_type = array(
				"sms" => "notif_sms",
				"app" => "notif_app"
			);
			$field = $array_type[$type];
			$profile_id = $decoded->profile_data->patient_profile_id;

			$this->db->query("UPDATE patient_profile SET $field = '$status' WHERE id ='$profile_id'");
			$data['code'] = "200";
			$data['message'] = "Success Update";
			echo json_encode($data);
		}
	}



}

?>