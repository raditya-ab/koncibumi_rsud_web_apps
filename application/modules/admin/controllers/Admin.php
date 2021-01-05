<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Admin extends Public_controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model("login/login_model","login");
        $this->load->model("Admin_model","admin");
        $this->load->model("crontask/crontask","crontask");
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

    public function expired(){
        $data['profile'] = $this->profile_data;
        $data['expired'] = $this->admin->all_expired_order();
        $data['status'] = $this->config->item("status_order");
        $data['doctor'] = $this->admin->all_docter();
        $this->load->view("expired/expired",$data);
    }

    public function update_doctor(){
        $order_id = $this->input->post("id");
        $doctor_id = $this->input->post("doctor_id");
        $this->db->query("UPDATE order_patient set doctor_id = '$doctor_id' WHERE id = '$order_id'");
        $data['status'] = "0";
        echo json_encode($data);
    }

    public function patient(){
        $arrayGender = array(
            "L" => "Pria",
            "W" => "Wanita",
            "" => ""
        );

        $arrayStatus = array(
            "" => "Active",
            "1" => "Non Active"
        );

        $data['profile'] = $this->profile_data;
        $data['patient'] = $this->admin->all_patient();
        $data['arrayGender'] = $arrayGender;
        $data['arrayStatus'] = $arrayStatus;
        $this->load->view("patient/index",$data);
    }

    public function add_patient(){
        $data['profile'] = $this->profile_data;
        $this->load->view("patient/add_user",$data);
    }

    public function register_pasien(){
        $status = "";
        if ( $this->input->post("active") && $this->input->post("active") != "" ){
            $status = "1";
        }
        $array_insert = array(
            "no_bpjs" => $this->input->post("bpjs"),
            "no_medrec" => $this->input->post("medrek"),
            "status" => $status
        );

        $mode = "create";
        if ( $this->input->post("user_id")){
            $mode = "update";
        }

        $get_kunjungan = $this->crontask->get_kunjungan($this->input->post("bpjs"),$this->input->post("medrek"));
        if ( count($get_kunjungan) <= 0 ){
            return redirect("admin/failed_add_patient");
        }

        if ( $mode == "create"){
            $this->db->insert("patient_login", $array_insert);
            $patient_login_id = $this->db->insert_id();
        }else{
            $this->db->where('id', $this->input->post("user_id"));
            $this->db->update("patient_login", $array_insert);
            $patient_login_id = $this->input->post("user_id");
        }

        return redirect("admin/show_pasien/".$patient_login_id);
    }

    public function show_pasien($pasien_id){
        $data['profile'] = $this->profile_data;
        $data['patient'] = $this->admin->detail_patient($pasien_id);
        $data['user_id'] = $pasien_id;

        $data['select_kawin'] = "";
        $data['select_tidak_kawin'] = "";
        if ( $data['patient'][0]['marrital_status'] == "Tidak Kawin"){
            $data['select_tidak_kawin'] = "selected";
        }
        $data['select_blood_a'] = "";
        $data['select_blood_b'] = "";
        $data['select_blood_ab'] = "";
        $data['select_blood_o'] = "";

        if ( $data['patient'][0]['blood_type'] != "" ){
            $data['select_blood_'.strtolower(trim($data['patient'][0]['blood_type']))] = "selected";
        }

        $this->load->view("patient/show_user",$data);
    }

    public function failed_add_patient(){
        $data['profile'] = $this->profile_data;
        $this->load->view("patient/failed_add_user",$data);
    }
}

?>