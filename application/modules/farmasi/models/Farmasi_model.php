<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Farmasi_model extends CI_Model {

	function get_all_receipt(){
		$current_date = date("Y-m-d 00:00:00");
		$next_date = date("Y-m-d 23:59:59");
		$qry_all_receipt = "SELECT rh.*,op.order_no,pp.first_name as first_name, 
			pp.last_name as last_name,md.first_name as doctor_name
			FROM receipt_header as rh 
			INNER JOIN order_patient as op ON (rh.kunjungan_id = op.id)
			INNER JOIN patient_profile as pp ON (op.patient_id = pp.id)
			INNER JOIN master_doctor as md ON (op.doctor_id = md.id)
            WHERE op.status IN (3,4) AND op.delivery_date >= '$current_date' AND op.delivery_date <= '$next_date'
			order by rh.id DESC LIMIT 0,30";
		$run_all_receipt = $this->db->query($qry_all_receipt);
		$res_all_receipt = $run_all_receipt->result_array();
		return $res_all_receipt;
	}


	function get_all_kurir(){
		$qry_all_kurir = "SELECT m.* FROM members as m INNER JOIN member_group as mg ON ( mg.member_id = m.id) WHERE mg.group_id = 3 ";
		$run_all_kurir = $this->db->query($qry_all_kurir);
		$res_all_kurir = $run_all_kurir->result_array();
		return $res_all_kurir;
	}

	function get_detail_receipt($resep_id){
		$qry_get_receipt = "SELECT rh.*, md.first_name as doctor_name, md.poli as poli,
        pp.first_name as patient_first_name, pp.last_name as patient_last_name,op.status as status,
        op.order_no,op.id as order_id,pp.id as patient_id
        FROM receipt_header as rh
        INNER JOIN order_patient as op ON (op.id = rh.kunjungan_id ) 
        INNER JOIN master_doctor as md ON (md.id = op.doctor_id)
        INNER JOIN patient_profile as pp ON (pp.id = op.patient_id)
        WHERE 1 AND rh.id = ? ";
        $run_get_receipt = $this->db->query($qry_get_receipt,array($resep_id));
        $res_get_receipt = $run_get_receipt->result_array();
        return $res_get_receipt;
	}

	function get_all_pending(){
		$qry_all_receipt = "SELECT rh.*,op.order_no,pp.first_name as first_name, 
			pp.last_name as last_name,md.first_name as doctor_name
			FROM receipt_header as rh 
			INNER JOIN order_patient as op ON (rh.kunjungan_id = op.id)
			INNER JOIN patient_profile as pp ON (op.patient_id = pp.id)
			INNER JOIN master_doctor as md ON (op.doctor_id = md.id)
            WHERE op.status = 2 
			order by rh.id  LIMIT 0,30";
		$run_all_receipt = $this->db->query($qry_all_receipt);
		$res_all_receipt = $run_all_receipt->result_array();
		return $res_all_receipt;
	}

	function get_history($clause,$keyword){
		$qry_get_receipt = "SELECT rh.*, md.first_name as doctor_name, md.poli as poli,
        pp.first_name as patient_first_name, pp.last_name as patient_last_name,op.status as status,
        op.order_no,op.farmasi_id as farmasi_id,op.delivery_date
        FROM receipt_header as rh
        INNER JOIN order_patient as op ON (op.id = rh.kunjungan_id ) 
        INNER JOIN master_doctor as md ON (md.id = op.doctor_id)
        INNER JOIN patient_profile as pp ON (pp.id = op.patient_id)
        WHERE 1 $clause ";
     	$run_get_receipt = $this->db->query($qry_get_receipt);
     	$res_get_receipt = $run_get_receipt->result_array();
     	return $res_get_receipt;
	}

	function get_delivery_date(){
		$config_day = array("mon","wed","fri");
		for ( $i=1; $i<30; $i++){
			$next_date = date("Y-m-d",strtotime('+'.$i.' days'));
			$day = strtolower(date('D', strtotime($next_date)));
			if ( in_array($day, $config_day)){
				$qry_validate = "SELECT * FROM order_patient WHERE delivery_date >= '$next_date 00:00:00' AND delivery_date <= '$next_date 23:59:59'";
				$run_validate = $this->db->query($qry_validate);
				if ( $run_validate->num_rows() <= 30 ){
					return $next_date;
				}
			}
		}
		return $next_date;
	}

	function get_order($order_id){
		$qry_order = "SELECT op.*,pp.mobile_number as mobile_number,pp.notif_sms as notif_sms, pp.notif_app as notif_app, pp.id as profile_id FROM order_patient as op
			INNER JOIN patient_profile pp ON (op.patient_id = pp.id)";
		$run_order = $this->db->query($qry_order,array($order_id));
		$res_order = $run_order->result_array();
		return $res_order;
	}
}