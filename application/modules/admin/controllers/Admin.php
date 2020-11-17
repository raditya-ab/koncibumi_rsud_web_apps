<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Admin extends Public_controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model("login/login_model","login");
        $this->load->model("Admin_model","admin");
        $this->load->library('email');
        $this->config->load('config');

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
        $data['get_user'] = $this->admin->get_all_user();
        $this->load->view("user/admin",$data);
    }

    public function add(){
        $data['profile'] = $this->profile_data;
        $data['group'] = $this->admin->all_group_access();
        $data['doctor'] = $this->admin->all_docter();
        $this->load->view("user/add_user",$data);
    }

    public function register(){

        $password = "";
        for($i=0; $i<=5; $i++){
            $password .= rand(0,9);
        }

        $data = array(
            "username" => $this->input->post("username"),
            "email" => $this->input->post("email"),
            "password" => crypt($password,'$6$rounds=5000$saltsalt$'),
            "created_at" => date("Y-m-d H:i:s"),
            "status" => 1,
        );
        $register = $this->admin->save_user($data);

        $data_group = array(
            "group_id" => $this->input->post("group"),
            "member_id" => $register,
            "created_at" => date("Y-m-d H:i:s")
        );
        $master_group = $this->admin->save_group($data_group);
        $email = $this->admin->send_email($this->input->post("email"),$password);
        $curent_date = date("Y-m-d H:i:s");
        if ( $this->input->post("group") == "2" ){
            $doctor = $this->input->post("doctor");
            $this->db->query("UPDATE master_doctor set user_id = $register WHERE id = '$doctor'");
        }
        $this->db->query("UPDATE members set verified_at = '$curent_date' WHERE id = '$register'");
    
        redirect("/admin/user_detail/".$register);
    }

    public function user_detail($user_id){
        $data['profile'] = $this->profile_data;
        $data['group'] = $this->admin->all_group_access();
        $data['user_data'] = $this->admin->detail_user($user_id);
        $this->load->view("user/edit_user",$data);
    }
    
    public function reset_password(){
        $user_id = $this->input->post("user_id");
        $email = $this->input->post('email');
        $password = "";
        for($i=0; $i<=5; $i++){
            $password .= rand(0,9);
        }

        $enc_password = crypt($password,'$6$rounds=5000$saltsalt$');
        $this->db->query("UPDATE members set password = '$enc_password' WHERE id = '$user_id'");
        $this->admin->send_email($email,$password);
        redirect("/admin/user_detail/".$user_id);
    }

    public function doctor(){
        $data['profile'] = $this->profile_data;
        $data['doctor'] = $this->admin->all_docter();
        $this->load->view("user/doctor",$data);
    }
}

?>