<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Login extends Public_controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model("login_model","login");
    }

    public function index(){
        if ( isset($_SESSION['user_id'])){
            $user_id = $_SESSION['user_id'];
            $profile_data = $this->login->get_profile_data($user_id);
            $this->db->query("UPDATE members set login_status = 1 WHERE id ='$user_id'");
            redirect(base_url().$profile_data['menu']['url']);
        }
        //$password = crypt("123<>",'$6$rounds=5000$saltsalt$');
        //echo $password;
        $this->load->view("login");
    }

    public function submit(){
        $email = $this->input->post("email");
        $password = $this->input->post("password");
        $checkAccess = $this->login->check_login($email,$password);
        $data['status'] = "1";
        if ( count($checkAccess) > 0 ){
            $data['status'] = "0";
        }
        $data['profile'] = $checkAccess;
        echo json_encode($data);
    }

    public function logout(){
        session_destroy();
        redirect(base_url());
    }
}


?>