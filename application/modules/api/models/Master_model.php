<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_model extends CI_Model {

  	function create_visit(  $patient_id = "", $medical_number = "", $doctor_id = "", $obat = "", $tanggal_kunjungan = "" ){
  		$array_insert = array(
			"patient_id" => $patient_id,
			"medical_number" => $medical_number,
			"doctor_id" => $doctor_id,
			"created_at" => date("Y-m-d H:i:s"),
			"tanggal_kunjungan" => $tanggal_kunjungan
		);
		$this->db->insert("kunjungan", $array_insert);
		$kunjungan_id = $this->db->insert_id();

		$array_insert_receipt = array(
			"kunjungan_id" => $kunjungan_id,
			"doctor_id" => $doctor_id,
			"created_at" => date("Y-m-d H:i:s")
		);
		$this->db->insert("receipt_header", $array_insert_receipt);
		$receipt_id = $this->db->insert_id();

		$list_obat = explode(",", $obat);
		foreach ($list_obat as $key => $value) {
			$array_insert_receipt_detail = array(
				"receipt_header_id" => $receipt_id,
				"obat" => $value,
				"dosis" => ( $key + 1 ) * 10
			);
			$this->db->insert("receipt_detail", $array_insert_receipt_detail);
		}

		return true;
  	}

  	function list_medicine($visit_id){
      $restricted = false;
  		$check_receipt = "SELECT rh.id as receipt_id FROM receipt_header as rh
  			INNER JOIN order_patient as op ON rh.kunjungan_id = op.id
  				WHERE 1 AND rh.id = ? ORDER BY rh.id desc";
  		$run_receipt = $this->db->query($check_receipt,array($visit_id));
  		$data_medicine = array();
      $list_restricted = array();

  		if ( $run_receipt->num_rows() > 0 ){
  			$data_receipt = $run_receipt->result_array();
  			$receipt_id = $data_receipt[0]['receipt_id'];
  			$check_medicine = "SELECT * FROM receipt_detail WHERE 1 AND receipt_header_id = ?";
  			$run_medicine = $this->db->query($check_medicine, array($receipt_id));
  			if ( $run_medicine->num_rows() > 0 ){
  				foreach ($run_medicine->result_array() as $key_medicine => $value_medicine) {
  					$data_obat = $this->get_detail_medicine($value_medicine['obat']);
  					$data_medicine[$key_medicine]['name'] = $data_obat[0]['name'];
  					$data_medicine[$key_medicine]['group'] = $data_obat[0]['golongan'];
  					$data_medicine[$key_medicine]['quantity'] = $value_medicine['qty'];
  					$data_medicine[$key_medicine]['unit_type'] = $value_medicine['satuan'];
            if ( $data_obat[0]['restricted'] == 1 ){
              $restricted = true;
              $restricted_name['name'] = $data_obat[0]['name'];
              $list_restricted[] = $restricted_name;
            }
  				}
  			}
  		}

  		$data['error_code'] = "200";
  		$data['medicine'] = $data_medicine;
      $data['restricted'] = $restricted;
      $data['list_restricted'] = $list_restricted;
   		return $data;
  	}

  	function get_detail_medicine( $medicine_id){
  		$check_medicine = "SELECT * FROM master_medicine WHERE 1 AND id = ? ";
  		$run_medicine = $this->db->query($check_medicine, array($medicine_id));
  		return $run_medicine->result_array();
  	}

    function generateOtp(){
      $otp_key = "";
      for ( $i=0; $i<6; $i++){
          $number = rand(0,9);
          $otp_key .= $number;
        }

        return $otp_key;
    }

    function get_doctor($doctor_id){
      $check_doctor = "SELECT * FROM master_doctor WHERE 1 AND id = ? ";
      $run_doctor = $this->db->query($check_doctor,array($doctor_id));
      $res_doctor = $run_doctor->result_array();
      return $res_doctor;
    }

  	
}
