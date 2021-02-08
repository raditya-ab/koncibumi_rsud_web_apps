<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Courier_model extends CI_Model {

  	function get_all_jobs($kurir_id){
  		$qry_all_receipt = "SELECT rh.*,op.order_no,pp.first_name as first_name, 
			pp.last_name as last_name,md.first_name as doctor_name, rh.id as receipt_id,
      pp.mobile_number as mobile_number, pp.address as address,op.notes as notes
			FROM receipt_header as rh 
			INNER JOIN order_patient as op ON (rh.kunjungan_id = op.id)
			INNER JOIN patient_profile as pp ON (op.patient_id = pp.id)
			INNER JOIN master_doctor as md ON (op.doctor_id = md.id)
            WHERE 1 
            AND op.status IN (5) 
            AND op.delivery_id = ?";
  		$run_get_jobs = $this->db->query($qry_all_receipt,array($kurir_id));
  		$res_get_jobs = $run_get_jobs->result_array();
  		return $res_get_jobs;
  	}

    function get_detail_order($order_id){
      $qry_order = "SELECT * FROM order_patient WHERE 1 AND id = ? ";
      $run_order = $this->db->query($qry_order,array($order_id));
      $res_order = $run_order->result_array();
      return $res_order;
    }

}

?>