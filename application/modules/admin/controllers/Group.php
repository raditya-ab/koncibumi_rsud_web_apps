<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Group extends Public_controller
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
        $data['group'] = $this->admin->all_group();
        $this->load->view("group/index",$data);
    }

    public function add(){
        $data = array(
            "name" => $this->input->post("name"),
            "created_at" => date("Y-m-d H:i:s")            
        );
        $this->db->insert("master_group",$data);

        redirect("admin/group");
    }

}