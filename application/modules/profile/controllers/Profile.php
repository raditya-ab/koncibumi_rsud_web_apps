<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends Public_controller {

	function __construct() {
        $this->load->model("Profile_model","profile");
        if ( isset($_SESSION['user_id'])){
            $this->profile_data = $this->profile->get_profile_data($_SESSION['user_id']);
        }else{
            redirect("login/logout");
        }
    }
    
    public function index(){
        $profile_data = $this->profile_data;
        $data['profile'] = $profile_data;
        $array['list_menu'] = array(
            "farmasi" => "farmasi",
            "superadmin" => "admin"
        );
        $data['menu'] = strtolower($array['list_menu'][strtolower($profile_data[0]['name'])]);
        $this->load->view("index",$data);
    }

    public function update(){
        $username = $this->input->post("username");
        $email = $this->input->post("email");
        $enc_password = crypt($this->input->post("password"),'$6$rounds=5000$saltsalt$');
        $user_id = $this->input->post("user_id");
        $this->db->query("UPDATE members set username = '$username', email = '$email', password = '$enc_password' WHERE id = '$user_id'");
        return redirect("profile");
    }
}    
?>