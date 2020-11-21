<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class About extends Public_controller
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
    	$data['about'] = $this->admin->about();
    	$this->load->view("about/editor",$data);
    }

    public function submit(){
    	$array_insert = array(
    		"content" => $this->input->post("content")
    	);
    	$this->db->insert("about",$array_insert);
    	redirect("admin/about");
    }
}

?>