<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Farmasi extends CI_Controller {

	function __construct() {
	   	parent::__construct();
	}

	public function index(){
		$data['html'] = "";
		$array_keluhan = array(
			"1" => "Keluhan",
			"0" => "Tidak ada keluhan"
		);
		$array_html = array();

		$qry_order = "SELECT op.created_at as created_at,op.id as order_id, pp.first_name as first_name,pp.last_name as last_name, op.keluhan as keluhan, op.description as description, op.doctor_approve_time as doctor_approve_time
			FROM order_patient as op
			INNER JOIN patient_profile as pp ON ( pp.id = op.patient_id )
		 	where status = 2 ";
		$run_order = $this->db->query($qry_order);
		if ( $run_order->num_rows() > 0 ){
			foreach ($run_order->result_array() as $key => $value) {
				$doctor_approve_time = "";
				if ( $value['doctor_approve_time'] != ""){
					$doctor_approve_time = date("d/M/Y",strtotime($value['doctor_approve_time']));
				}

				$html = array();
				$html['order_id'] = $value['order_id'];
				$html['patient_name'] = $value['first_name'].' '.$value['last_name'];
				$html['created'] = date("d-M-Y",strtotime($value['created_at']));
				$html['keluhan'] = $array_keluhan[$value['keluhan']];
				$html['description'] = $value['description'];
				$html['doctor_name'] = "Doctor A";
				$html['doctor_approve'] = $doctor_approve_time;
				$array_html[] = $html;
			}
		}

		$data['html'] = $array_html;
		echo json_encode($data);
	}

	public function update(){
		$order_id = $this->input->post("order_id");
		$farmasi_id = 1;
		$this->db->query("UPDATE order_patient set farmasi_id = $farmasi_id, farmasi_approve_time = now() where id = '$order_id' ");
		$data['status'] = "0";
		$data['message'] = "Order has updated";
		echo json_encode($data);
	}

}

?>