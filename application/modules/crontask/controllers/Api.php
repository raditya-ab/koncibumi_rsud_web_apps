<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Api extends Public_controller{

	public function __construct(){
        parent::__construct();
        $this->load->model("crontask");
    }

    public function get_doctor(){
    	$sample = file_get_contents("./assets/api/master/doctor.json");
    	$response = json_decode($sample);
    	$save_doctor = $this->crontask->save_doctor($response->data);
    	$data['status'] = "OK";
    	echo json_encode($data);
	}

	public function get_patient(){
        $sample = file_get_contents("./assets/api/master/patient.json");
        $response = json_decode($sample);
        $save_doctor = $this->crontask->save_patient($response->data);
        $data['status'] = "OK";
        echo json_encode($data);
	}

    public function get_visit(){
        $sample = file_get_contents("./assets/api/master/visit.json");
        $response = json_decode($sample);
        $save_visit = $this->crontask->save_visit($response->data);
        $data['status'] = "OK";
        echo json_encode($data);
    }

    public function get_medicine(){
        $sample = file_get_contents("./assets/api/master/obat.json");
        $response = json_decode($sample);
        $save_visit = $this->crontask->save_medicine($response->data);
        $data['status'] = "OK";
        echo json_encode($data);
    }

    public function get_receipt(){
        $sample = file_get_contents("./assets/api/master/resep_header.json");
        $response = json_decode($sample);
        
    }

    public function get_receipt_detail(){
        $sample = file_get_contents("./assets/api/master/resep_header_detail.json");
        $response = json_decode($sample);
    }
}

?>