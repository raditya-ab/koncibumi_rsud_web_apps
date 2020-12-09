<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Api extends Public_controller{

	public function __construct(){
        parent::__construct();
        $this->load->model("crontask");
        $this->load->library("endpoint");
        $this->load->config("config");
    }

    public function get_doctor(){
        $config_sync = $this->config->item("api_rs");
        $call_login = $this->set_login();
        if ( $call_login == false ){
            return false;
        }

        $headers['x-token'] = "Bearer ".$call_login['token'];
        $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $url = $config_sync['url'].'/'.$config_sync['master_path'].'/'.$config_sync['endpoint_path']['docter'];
        $call_docter = $this->endpoint->call_endpoint($config_sync,$url,$headers);
        $array_insert = array(
            "endpoint" => $url,
            "responses" => $call_docter,
            "created_at" => date("Y-m-d H:i:s")
        );
        $this->db->insert("log_respons",$array_insert);
        $save_doctor = $this->crontask->save_doctor(json_decode($call_docter));
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
        $config_sync = $this->config->item("api_rs");
        $call_login = $this->set_login();
        if ( $call_login == false ){
            return false;
        }

        $headers['x-token'] = "Bearer ".$call_login['token'];
        $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $url = $config_sync['url'].'/'.$config_sync['master_path'].'/'.$config_sync['endpoint_path']['drugs'];
        $call_medicine = $this->endpoint->call_endpoint($config_sync,$url,$headers);
        $array_insert = array(
            "endpoint" => $url,
            "responses" => $call_medicine,
            "created_at" => date("Y-m-d H:i:s")
        );
        $this->db->insert("log_respons",$array_insert);
        $save_doctor = $this->crontask->save_medicine(json_decode($call_medicine));

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

    public function set_login(){
        $config_sync = $this->config->item("api_rs");
        $url = $config_sync['url'].'/'.$config_sync['master_path'].'/'.$config_sync['endpoint_path']['login'];
        $call_login = $this->endpoint->call_login($config_sync, $url);
        $response = json_decode($call_login);
        $array_insert = array(
            "endpoint" => $url,
            "responses" => $call_login,
            "created_at" => date("Y-m-d H:i:s")
        );
        $this->db->insert("log_respons",$array_insert);

        if ( isset($response->token)){
            $data['status'] = true;
            $data['token'] = $response->token;
            return $data;
        }
        return false;
    }
}

?>