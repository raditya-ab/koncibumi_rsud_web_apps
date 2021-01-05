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

			$qry_profile = "SELECT pp.*,pl.gender as gender 
				FROM patient_profile as pp  
				INNER JOIN patient_login as pl ON (pl.id = pp.patient_login_id)
			WHERE 1 AND pp.id = ? ";
			$run_profile = $this->db->query($qry_profile,array($patient_profile_id));

			$list_profile = array();
			$list_profile['patient_login_id'] = "";
  			$list_profile['patient_profile_id'] = "";
  			$list_profile['first_name'] = "";
  			$list_profile['last_name'] =  "";
  			$list_profile['mobile_number'] = "";
  			$list_profile['address'] = "";
  			$list_profile['profile_pict'] = "";
  			$list_profile['bpjs_number'] = "";
  			$list_profile['medic_number'] = "";
  			$list_profile['date_of_birth'] = "";
  			$list_profile['latitude'] = "";
  			$list_profile['longitude'] = "";
  			$list_profile['gender'] = "";

			if ( $run_profile->num_rows() > 0 ){
				$res_profile = $run_profile->result_array();
				$list_profile['patient_login_id'] = $res_profile[0]['patient_login_id'];
				$list_profile['patient_profile_id'] = $res_profile[0]['id'];
				$list_profile['first_name'] = $res_profile[0]['first_name'];
				$list_profile['last_name'] = $res_profile[0]['last_name'];
				$list_profile['mobile_number'] = $res_profile[0]['patient_login_id'];
				$list_profile['address'] = $res_profile[0]['address'];
				$list_profile['profile_pict'] = $res_profile[0]['profile_pict'];
				$list_profile['bpjs_number'] = $res_profile[0]['bpjs_number'];
				$list_profile['medic_number'] = $res_profile[0]['medical_number'];
  				$list_profile['date_of_birth'] =$res_profile[0]['dob'];
	  			$list_profile['latitude'] = $res_profile[0]['latitude'];
	  			$list_profile['longitude'] = $res_profile[0]['longitude'];

	  			$list_profile['gender'] = $res_profile[0]['gender'];
			}
  			

			$data['code'] = "200";
	    	$data['message'] = "Success Profile";
	    	$data['token'] = $access_token;
	    	$data['order'] = $enabled_order;
	    	$data['profile'] = $list_profile;
	    
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
			$generateQR = "/api/access/generateQR?order_id=".$order_id;
			$this->db->query("UPDATE order_patient set order_no = '$order_no', qr='$generateQR' WHERE id ='$order_id'");
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

			if ( trim($edata->address) == "" ){
				header("HTTP/1.1 403");
				$data['code'] = "403";
		    	$data['message'] = "Address can not empty";
		    	echo json_encode($data);
		    	exit;
			}

			$address = $edata->address;
			$latitude = $edata->latitude;
			$longitude = $edata->longitude;
			$notes = $edata->notes;

			$secret_key = $this->config->item('secret_key');
			$status_order = $this->config->item('status_order');
			$decoded = JWT::decode($access_token, $secret_key, array('HS256'));
			$patient_profile_id = $decoded->profile_data->patient_profile_id;
			$patient_login_id = $decoded->profile_data->patient_login_id;
			$this->db->query("UPDATE patient_profile set address = '$address',latitude = '$latitude', longitude = '$longitude' where id = '$patient_profile_id'");
			$this->db->query("UPDATE patient_login set address = '$address' where id = '$patient_login_id'");

			$qry_get_latest = "SELECT * FROM order_patient WHERE patient_id = ?  ORDER by id DESC  LIMIT 0,1";
			$run_get_latest = $this->db->query($qry_get_latest,array($patient_profile_id));
			if ( $run_get_latest->num_rows() > 0 ){
				$res_get_latest = $run_get_latest->result_array();
				$order_id = $res_get_latest[0]['id'];
				$this->db->query("UPDATE order_patient SET notes = '$notes' WHERE id = '$order_id'");
			}

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

			$whereClause = " AND op.status NOT IN ('6','7')";
			if ( $status == "false" ){
				$whereClause = " AND op.status IN ('6','7')";
			}


			$secret_key = $this->config->item('secret_key');
			$status_order = $this->config->item('status_order');
			$decoded = JWT::decode($access_token, $secret_key, array('HS256'));
			$patient_profile_id = $decoded->profile_data->patient_profile_id;
			$array_history = array();
			$detail_profile = array();
			$qry_check_order = "SELECT op.id as id,op.status as status,op.created_at as created_at,
			md.first_name as doctor_name,pp.bpjs_number as bpjs_number, pp.medical_number as medical_number, 
			op.received_date as received_date,pp.address as address,pp.latitude,pp.longitude, pp.id as patient_profile_id
				FROM order_patient as op
				LEFT JOIN master_doctor as md ON (md.id = op.doctor_id )
				INNER JOIN patient_profile as pp ON ( pp.id = op.patient_id)
				WHERE 1 AND op.patient_id = ? $whereClause";
			$run_check_order = $this->db->query($qry_check_order,array($patient_profile_id));
			if ( $run_check_order->num_rows() > 0 ){
				$res_check_order = $run_check_order->result_array();
				foreach ($res_check_order as $key => $value) {
					$restricted_drugs = false;
					$restricted_message = "";
					$shipping_detail = "";
					$qr = "";
					if ( $value['status'] >= 5 ){
						$qr = "/api/access/generateQR?order_id=".$value['id'];
					}

					if ( $value['status'] == 7 ){
						$qr = "";
					}

					$received_date = "";
					if (  $value['received_date'] != NULL ){
				        $received_date = date("d M Y ",strtotime( $value['received_date']));
			      	}

					$detail_array_history['id'] = $value['id'];
					$detail_array_history['order_number'] = "KNCBM".date("Ymd",strtotime($value['created_at'])).$value['id'];
					$array_detail = array();
					$detail_array_history['order_date'] = date("d-M-Y", strtotime($value['created_at']));
					$detail_array_history['doctor_name'] = $value['doctor_name'];
					$detail_array_history['status_label'] = $status_order[$value['status']];
					$detail_array_history['status_code'] = $value['status'];
					$detail_array_history['qr'] = $qr;

					$list_drugs = $this->master->list_medicine($value['id']);
					
					if ( count($list_drugs['medicine']) > 0 && $value['status'] > 1){
						if ( $list_drugs['restricted'] == true ){
							$list_drug_restricted = $list_drugs['list_restricted'];
							$list_restricted_message['drug_list'] = $list_drug_restricted;
							$list_restricted_message['modal_description'] = "Pengambilan Obat <b><u>wajib</u></b> dilakukan oleh pasien yang bersangkutan karena terdapat obat <b>Golongan G</b>";
							$restricted_message = $list_restricted_message;
						}

						$list_shipping_detail = array();
						$shipping_detail = $this->profile->shipping_method($value['id'],$list_drugs['restricted'],$value['status'],$value['patient_profile_id']);
						$detail_profile['bpjs_number'] = $value['bpjs_number'];
						$detail_profile['medic_number'] = $value['medical_number'];
						$detail_profile['restricted_drugs'] = $list_drugs['restricted'];
						$detail_profile['restricted_message'] = $restricted_message;
						$detail_profile['ordered_drugs'] = $list_drugs['medicine'];
						$detail_profile['received_date'] = $received_date;
						$detail_profile['shipping_detail'] = $shipping_detail;

						$array_detail = $detail_profile;
					}

					$detail_array_history['details'] = NULL;
					if ( count($array_detail) > 0 ){
						$detail_array_history['details'] = $array_detail;
					}
					$array_history[] = $detail_array_history;
				}
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

			$qry_check_order = "SELECT op.id as id,op.status as status,op.created_at as created_at,
			md.first_name as doctor_name,pp.bpjs_number as bpjs_number, pp.medical_number as medical_number, 
			op.received_date as received_date,pp.address as address,pp.latitude,pp.longitude, pp.id as patient_profile_id
				FROM order_patient as op
				INNER JOIN master_doctor as md ON (md.id = op.doctor_id )
				INNER JOIN patient_profile as pp ON ( pp.id = op.patient_id)
				WHERE 1 AND op.id = ? ";
			$run_check_order = $this->db->query($qry_check_order,array($order_id));
			if ( $run_check_order->num_rows() > 0 ){
				$res_check_order = $run_check_order->result_array();

				$restricted_drugs = false;
				$restricted_message = "";
				$shipping_detail = "";
				$qr = "";
				if ( $res_check_order[0]['status'] >= 5 ){
					$qr = "/api/access/generateQR?order_id=".$res_check_order[0]['id'];
				}

				$received_date = "";
				if ( $res_check_order[0]['received_date'] != NULL ){
			        $received_date = date("d M Y ",strtotime($res_check_order[0]['received_date']));
		      	}

				$detail_array_history['id'] = $res_check_order[0]['id'];
				$detail_array_history['order_number'] = "KNCBM".date("Ymd",strtotime($res_check_order[0]['created_at'])).$res_check_order[0]['id'];
				$array_detail = array();
				$detail_array_history['order_date'] = date("d-M-Y", strtotime($res_check_order[0]['created_at']));
				$detail_array_history['doctor_name'] = $res_check_order[0]['doctor_name'];
				$detail_array_history['status_label'] = $status_order[$res_check_order[0]['status']];
				$detail_array_history['status_code'] = $res_check_order[0]['status'];
				$detail_array_history['qr'] = $qr;

				$list_drugs = $this->master->list_medicine($res_check_order[0]['id']);
				
				if ( count($list_drugs['medicine']) > 0 && $res_check_order[0]['status'] > 1){
					if ( $list_drugs['restricted'] == true ){
						$list_drug_restricted = $list_drugs['list_restricted'];
						$list_restricted_message['drug_list'] = $list_drug_restricted;
						$list_restricted_message['modal_description'] = "Pengambilan Obat <b><u>wajib</u></b> dilakukan oleh pasien yang bersangkutan karena terdapat obat <b>Golongan G</b>";
						$restricted_message = $list_restricted_message;
					}

					$list_shipping_detail = array();
					$shipping_detail = $this->profile->shipping_method($res_check_order[0]['id'],$list_drugs['restricted'],$res_check_order[0]['status'],$res_check_order[0]['patient_profile_id']);
					$detail_profile['bpjs_number'] = $res_check_order[0]['bpjs_number'];
					$detail_profile['medic_number'] = $res_check_order[0]['medical_number'];
					$detail_profile['restricted_drugs'] = $list_drugs['restricted'];
					$detail_profile['restricted_message'] = $restricted_message;
					$detail_profile['ordered_drugs'] = $list_drugs['medicine'];
					$detail_profile['received_date'] = $received_date;
					$detail_profile['shipping_detail'] = $shipping_detail;

					$array_detail = $detail_profile;
				}
				
				$detail_array_history['details'] = NULL;
				if ( count($array_detail) > 0 ){
					$detail_array_history['details'] = $array_detail;
				}
				$array_history[] = $detail_array_history;
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
			$list_profile = array();
			$obj = file_get_contents('php://input');
			$edata = json_decode($obj);
			if ( isset($edata->address)){
				$address = $edata->address;
				$patient_profile_id = $decoded->profile_data->patient_profile_id;
				$patient_login_id = $decoded->profile_data->patient_login_id;
				$latitude = $edata->lat;
				$longitude = $edata->long;
				$phone_number = $edata->phone_number;

				if ( trim($edata->address) == "" ){
					header("HTTP/1.1 403");
					$data['code'] = "403";
			    	$data['message'] = "Address can not empty";
			    	echo json_encode($data);
			    	exit;
				}

				$array_update = array(
					"address" => $edata->address,
					"latitude" => $edata->lat,
					"longitude" => $edata->long,
					"mobile_number" => $phone_number
 				);

 				$this->db->where("id",$patient_profile_id);
 				$this->db->update("patient_profile",$array_update);
 				$this->db->query("UPDATE patient_login SET address = '$address',mobile_number='$phone_number' WHERE id = '$patient_login_id'");
 				
 				
				$qry_profile = "SELECT pp.*,pl.gender as gender 
					FROM patient_profile as pp  
					INNER JOIN patient_login as pl ON (pl.id = pp.patient_login_id)
				WHERE 1 AND pp.id = ? ";
				$run_profile = $this->db->query($qry_profile,array($patient_profile_id));

				if ( $run_profile->num_rows() > 0  ){ 
				        $res_profile = $run_profile->result_array();
				        $list_profile['patient_login_id'] = $res_profile[0]['patient_login_id'];
				        $list_profile['patient_profile_id'] = $res_profile[0]['id'];
				        $list_profile['first_name'] = $res_profile[0]['first_name'];
				        $list_profile['last_name'] = $res_profile[0]['last_name'];
				        $list_profile['mobile_number'] = $res_profile[0]['patient_login_id'];
				        $list_profile['address'] = $res_profile[0]['address'];
				        $list_profile['profile_pict'] = $res_profile[0]['profile_pict'];
				        $list_profile['bpjs_number'] = $res_profile[0]['bpjs_number'];
				        $list_profile['medic_number'] = $res_profile[0]['medical_number'];
				        $list_profile['date_of_birth'] =$res_profile[0]['dob'];
				        $list_profile['latitude'] = $res_profile[0]['latitude'];
				        $list_profile['longitude'] = $res_profile[0]['longitude'];
				        $list_profile['gender'] = $res_profile[0]['gender'];
				}


		
	
			}
		}

		$data['code'] = "200";
		$data['message'] = "Data has been updated";
		$data['profile'] = $list_profile;
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
			$content = "";
			if ( count($res_tnc) > 0 ){
				foreach ($res_tnc as $key => $value) {
					$detail_tnc = array();
					$detail_tnc['id'] = $value['id'];
					$detail_tnc['content'] = $value['content'];
					$tnc[] = $detail_tnc;
					$content = $value['content'];
				}
			}

			$data['code'] = "200";
			$data['content'] = $content;
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
			$array_platform = array(
				"android" => "https://play.google.com/store/apps/details?id=com.halfbrick.fruitninjafree",
				"ios" => ""
			);


			if ( isset($edata->current_version)) {
				$qry_version = $this->db->query("SELECT * FROM tag_version order by id desc");
				$run_version = $qry_version->result_array();	
				$data['code'] = "200";
				$data['latest_version'] = $run_version[0]['version'];
				$data['mandatory_update'] = true;
				if ( $run_version[0]['version'] == $edata->current_version ){
					$data['mandatory_update'] = false;
					$data['url_apps'] = $array_platform[$edata->platform];
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

	public function question_type(){
		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
			$question_type = array(
				"General",
				"Medicine",
				"Service"
			);
		}

		$data['code'] = "200";
		$data['question_type'] = $question_type;
		echo json_encode($data);
	}

	public function notification(){
		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
			$list_notif = array();
			$access_token = $_SERVER['HTTP_TOKEN'];
			$secret_key = $this->config->item('secret_key');
			$decoded = JWT::decode($access_token, $secret_key, array('HS256'));
			$profile_id = $decoded->profile_data->patient_profile_id;

			$sql_notification = "SELECT * FROM notification WHERE 1 AND profile_id = ?";
			$run_notification = $this->db->query($sql_notification,array($profile_id));
			$array_read = array(
				1 => "not_read",
				0 => "read"
			);

			if ( $run_notification->num_rows() > 0 ){
				$list = $run_notification->result_array();
				foreach ($list as $key => $value) {
					$data_notif = array();
					$data_notif['no'] = $key + 1;
					$data_notif['title'] = $value['notification'];
					$data_notif['has_read'] = $array_read[$value['read_status']];
					$data_notif['notification_id'] = $value['id'];
					$list_notif[] = $data_notif;
				}
			}

			
			$data['code'] = "200";
			$data['list_notification'] = $list_notif;
			echo json_encode($data);
		}
	}

	public function read_notif(){
		if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
			$notification_id = $_GET['notification_id'];
			$this->db->query("UPDATE notification set read_status = 0 where id = $notification_id");
			$data['code'] = "200";
			$data['status'] = "Success read";
			echo json_encode($data);
		}
	}

	public function is_can_order(){
		$data['code'] = "200";
		$data['message'] = "User can order";

		$access_token = $_SERVER['HTTP_TOKEN'];
		$secret_key = $this->config->item('secret_key');
		$decoded = JWT::decode($access_token, $secret_key, array('HS256'));
		$patient_profile_id = $decoded->profile_data->patient_profile_id;

		$qry_profile = "SELECT latitude,longitude,address FROM patient_profile WHERE 1 AND id = ?";
		$run_profile = $this->db->query($qry_profile,array($patient_profile_id));
		if ( $run_profile->num_rows() > 0 ){
			$res_profile = $run_profile->result_array();
			if ( $res_profile[0]['latitude'] == "" && $res_profile[0]['longitude'] == "" ){
				$data['code'] = "1";
				$data['message'] = "Alamat not complete";
				echo json_encode($data);
    			exit;
			}
		}

		$qry_get_active = "SELECT * FROM order_patient WHERE 1 AND status NOT IN (6,3,7) AND patient_id = ? ";
		$run_get_active = $this->db->query($qry_get_active,array($patient_profile_id));
		if ( $run_get_active->num_rows() > 0 ){
			$data['code'] = "2";
			$data['message'] = "Any order still active";
			echo json_encode($data);
			exit;
		}

		$qry_check_order = "SELECT * FROM order_patient WHERE 1 AND patient_id = ? order by id DESC LIMIT 0,1";
		$run_check_order = $this->db->query($qry_check_order, array($patient_profile_id));
		if ( $run_check_order->num_rows() > 0 ){
			$data_order = $run_check_order->result_array();
			if ( $data_order[0]['keluhan'] == 1 ){
				$now = strtotime(date("Y-m-d H:i:s"));
				$order_time = strtotime($data_order[0]['created_at']);
				$diff = ($now - $order_time) / (60 * 60 * 24);
				if ( $diff <= 7 ){
					$data['code'] = "3";
					$data['message'] = "Any order still active and have complaint";
					echo json_encode($data);
	    			exit;
				}
			}
		}

		echo json_encode($data);
    	exit;
	}


}

?>
