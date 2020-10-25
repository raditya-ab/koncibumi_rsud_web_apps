<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Courier extends CI_Controller {

	function __construct() {
		header('Access-Control-Allow-Origin: * ');
        header("Access-Control-Allow-Headers: * ");
        header("Access-Control-Allow-Methods: GET,POST,OPTIONS");
        parent::__construct();
    }

    public function update_order(){
    	if ( $_SERVER['REQUEST_METHOD'] != "OPTIONS"){
    		$obj = file_get_contents('php://input');
			$edata = json_decode($obj);
			if ( isset($edata->order_id)){
				$order_id = $edata->order_id;
				$this->db->query("UPDATE order_patient set delivery_date = now(), status = '6' where id ='$order_id'");
				$data['status'] = "0";
				$data['message'] = "Order has delivered";
				echo json_encode($data);
				exit();
			}
    	}
    }
}

?>