<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('../vendor/autoload.php');
use \Firebase\JWT\JWT;

class Profile_model extends CI_Model {

  	function detail_profile($profile_id){
   		$patien_profile_id = $profile_id;
   		$query_profile = "SELECT pp.id,pp.address,pp.latitude,pp.longitude FROM patient_profile as pp
   			JOIN patient_login as pl on pp.patient_login_id = pl.id
   			WHERE 1 
   				AND pp.id = ? ";
   		$run_profile = $this->db->query($query_profile, array($patien_profile_id));
   		return $run_profile->result_array();
  	}

  	function visit_profile( $limit = 5){
	    $this->load->model('app/master_model','master');
  		$patien_profile_id = $this->input->post("profile_id");
  		$query_visit = "SELECT * FROM kunjungan WHERE 1 AND patient_id = ? ORDER BY id desc LIMIT 0, $limit ";
  		$run_visit = $this->db->query($query_visit, array($patien_profile_id));
  		$array_visit = array();
  		$receipt_id = "";
  		foreach ($run_visit->result_array() as $key => $value) {
  			$array_visit[$key]['general'] = $run_visit->result_array();
  			$receipt_list = $this->master->list_medicine($value['id']);
  			$array_visit[$key]['medicine_list'] = $receipt_list;
  		}


  		return $array_visit;
  	}

    function check_token($token){
      $this->config->load('config');

      $check_token = "SELECT  * FROM patient_login WHERE remember_token = ? ";
      $run_token = $this->db->query($check_token, array($token));
      if ( $run_token->num_rows() > 0 ){
        return true;
      }
      return false;
    }

    function get_diagones($profile_id){
      $data['total_kunjungan'] = 0;
      $data['icd_code'] = "";
      $data['icd_description'] = "";

      $qry_kunjungan = "SELECT * FROM kunjungan WHERE 1 AND patient_id = ? order by id desc";
      $run_kunjungan = $this->db->query($qry_kunjungan,array($profile_id));
      if ( $run_kunjungan->num_rows() > 0 ){
        $res_kunjungan = $run_kunjungan->result_array();
        $data['total_kunjungan'] = $run_kunjungan->num_rows();
        $data['icd_code'] = $res_kunjungan[0]['icd_code'];
        $data['icd_description'] = $res_kunjungan[0]['icd_description'];
      }

      return $data;
    }

    public function shipping_method($order_id,$restricted,$status_order,$profile_id){
      $shipping_method = array();
      $profile = $this->detail_profile($profile_id);
      $order_id = $this->get_detail_order($order_id);
      $delivery_date = "";
      $received_date = "";

      if ( $order_id[0]['delivery_date'] != NULL ){
        $delivery_date = date("d M Y ",strtotime($order_id[0]['delivery_date']));
      }

       if ( $order_id[0]['received_date'] != NULL ){
        $received_date = date("d M Y ",strtotime($order_id[0]['received_date']));
      }

      $array_label = array(
        true => array(
          "2" => array(
            "shipping_label" => "Tanggal Pengiriman",
            "shipping_date" => "Menunggu Konfirmasi Farmasi"
          ),
          "4" => array(
            "shipping_label" => "Tanggal Pengambilan",
            "shipping_date" => $delivery_date
          ),
          "6" => array(
            "shipping_label" => "Tanggal Pengambilan",
            "shipping_date" => $received_date
          )
        ),
        false => array(
          "2" => array(
            "shipping_label" => "Tanggal Pengiriman",
            "shipping_date" => "Menunggu Konfirmasi Farmasi"
          ),
          "3" => array(
            "shipping_label" => "Estimasi Tanggal Pengiriman",
            "shipping_date" => $delivery_date
          ),
          "5" => array(
            "shipping_label" => "Tanggal Pengiriman",
            "shipping_date" => $delivery_date
          ),
          "6" => array(
            "shipping_label" => "Tanggal Pengambilan",
            "shipping_date" => $received_date
          )
        )
      );

      $array_shipping = array(
        true => array(
          "shipping_method" => "Diambil",
          "shipping_label" => $array_label[$restricted][$status_order]['shipping_label'],
          "shipping_date" => $array_label[$restricted][$status_order]['shipping_date'],
          "shipping_address_label" => "Alamat Pengambilan",
          "shipping_address" => "RSUD Sumedang",
          "lat" => "-6.8571872",
          "lng" => "107.9207505"
        ),
        false => array(
          "shipping_method" => "Dikirim",
          "shipping_label" => $array_label[$restricted][$status_order]['shipping_label'],
          "shipping_date" => $array_label[$restricted][$status_order]['shipping_date'],
          "shipping_address_label" => "Alamat Pengiriman",
          "shipping_address" => $profile[0]['address'],
          "lat" => $profile[0]['latitude'],
          "lng" =>  $profile[0]['longitude']
        )
      );

     
      return $array_shipping[$restricted];
    }

    public function get_detail_order($order_id){
      $qry = "SELECT * FROM order_patient WHERE 1 AND id = ? ";
      $run = $this->db->query($qry,array($order_id));
      return $run->result_array();
    }
  	
}
?>