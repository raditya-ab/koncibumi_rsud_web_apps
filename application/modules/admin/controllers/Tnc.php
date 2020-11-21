<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Tnc extends Public_controller
{
    public function __construct(){
    	parent::__construct();
    	$this->load->model("login/login_model","login");
        $this->load->model("Admin_model","admin");

    	if ( isset($_SESSION['user_id'])){
            $this->profile_data = $this->login->get_profile_data($_SESSION['user_id']);
            $module = $this->profile_data['menu']['url'];
            if ( strtolower($module) != "admin"){
                redirect("login/logout");
            }
        }else{
            redirect("login/logout");
        }
    }

    public function index(){
    	$data['profile'] = $this->profile_data;
    	$data['tnc'] = $this->admin->tnc();
    	$this->load->view("tnc/editor",$data);
    }

    public function submit(){
    	$array_insert = array(
    		"content" => $this->input->post("content")
    	);
    	$this->db->insert("tnx",$array_insert);
    	redirect("admin/tnc");
    }
}

?>