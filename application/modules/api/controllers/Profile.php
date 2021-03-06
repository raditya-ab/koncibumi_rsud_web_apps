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

			$check_bpjs = "SELECT pl.remember_token as remember_token, pp.id as patient_profile_id FROM patient_login as pl 
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
			$array_insert = array(
				"patient_id" => $profile_id,
				"delivery_date" => date("Y-m-d H:i:s"),
				"created_at" => date("Y-m-d H:i:s"),
				"status" => 1,
				"keluhan" => $edata->complaint,
				'description' => $edata->complaint_description
			);
			$this->db->insert("order_patient", $array_insert);
			$order_id = $this->db->insert_id();

			if ( $edata->complaint == 0 ){
				$array_receipt = array(
					"kunjungan_id" => $order_id,
					"doctor_id" => NULL,
					"created_at" => date("Y-m-d H:i:s"),
					"restricted" => 1
				);
				$this->db->insert("receipt_header",$array_receipt);
				$receipt_id = $this->db->insert_id();
				$array_receipt_detail = array(
					"receipt_header_id" => $receipt_id,
					"obat" => 1,
					"dosis" => rand(1,10) * 10
				);

				$this->db->insert("receipt_detail",$array_receipt_detail);
			}

			if ( $edata->complaint == "1" ){
				header("HTTP/1.1 406");
				$data['code'] = "406";
				$data['message'] = "Order have complaint";
				echo json_encode($data);
				exit();
			}

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
			$secret_key = $this->config->item('secret_key');
			$status_order = $this->config->item('status_order');
			$decoded = JWT::decode($access_token, $secret_key, array('HS256'));
			$patient_profile_id = $decoded->profile_data->patient_profile_id;
			$this->db->query("UPDATE patient_profile set address = '$address' where id = '$patient_profile_id'");

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
					$detail_profile['shipping_method'] = "Dikirim";
					if ( $restricted == "1"){
						$restricted_drug = true;
						$detail_profile['shipping_method'] = "Diambil";
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
					$restricted = $res_check_receipt[0]['restricted'];

					$restricted_drug = false;
					$detail_profile['shipping_method'] = "Dikirim";
					if ( $restricted == "1"){
						$restricted_drug = true;
						$detail_profile['shipping_method'] = "Diambil";
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


}

?>