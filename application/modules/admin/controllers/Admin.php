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
        $this->load->library("endpoint");

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
            "" => "",
            "P" => "Perempuan"
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
            "no_bpjs" => $this->input->post("detail_bpjs"),
            "no_medrec" => $this->input->post("detail_medrek"),
            "date_created" => date("Y-m-d H:i:s"),
            "first_name" => $this->input->post("detail_name"),
            "dob" => date("Y-m-d",strtotime($this->input->post("detail_dob"))),
            "gender" => $this->input->post("detail_gender"),
            "blood_type" => $this->input->post("detail_blood"),
            "address" => $this->input->post("detail_adress"),
            "mobile_number" => $this->input->post("detail_handphone"),
            "marrital_status" => $this->input->post("detail_marital")
        );
        
        $mode = "create";
        if ( $this->input->post("user_id")){
            $mode = "update";
        }

        if ( $mode == "create"){
            $this->db->insert("patient_login", $array_insert);
            $patient_login_id = $this->db->insert_id();
        }else{
            $array_insert = array(
                "no_bpjs" => $this->input->post("detail_bpjs"),
                "no_medrec" => $this->input->post("detail_medrek"),
                "first_name" => $this->input->post("detail_name"),
                "address" => $this->input->post("detail_adress"),
                "mobile_number" => $this->input->post("detail_handphone")
            );

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

    public function check_pasien(){
        $bpjs = $this->input->post("bpjs");
        $medrek = $this->input->post("medrek");
        $data = array();
        $data['total_kunjungan'] = 0;
        $call_login = $this->set_login();
        $html = "";

        $qry_login_id = "SELECT * FROM patient_login WHERE 1 AND no_medrec = ? ";
        $run_login_id = $this->db->query($qry_login_id,array($medrek));
        if ( $run_login_id->num_rows() > 0 ){
            $data['total_kunjungan'] = 0;
            $data['message'] = "Data pasien sudah ada sebelumnya";
            echo json_encode($data);
            exit();
        }

        if ( $call_login == false ){
           $data['total_kunjungan'] = 0;
           $data['message'] = "Gagal Login ke Database SIM Rumah Sakit";
           echo json_encode($data);
        }else{
            $config_sync = $this->config->item("api_rs");
            $url = $config_sync['url'].'/'.$config_sync['master_path'].'/'.$config_sync['endpoint_path']['visit'].'/'.$medrek;
            $headers['x-token'] = "Bearer ".$call_login['token'];
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';

            $call_visit = $this->endpoint->call_endpoint($config_sync,$url,$headers);
            $array_insert = array(
                "endpoint" => $url,
                "responses" => $call_visit,
                "created_at" => date("Y-m-d H:i:s")
            );
            $this->db->insert("log_respons",$array_insert);

            $response_visit = json_decode($call_visit);
            if ( isset($response_visit->status) && $response_visit->status == false ) {
                $data['total_kunjungan'] = 0;
                $data['message'] = "Data pasien tidak tersedia di SIM Rumah Sakit";
            }else{
                $call_login = $this->set_login();
                $config_sync = $this->config->item("api_rs");
                $explode_medical_number = explode("-",$medrek);

                $url = $config_sync['url'].'/'.$config_sync['master_path'].'/'.$config_sync['endpoint_path']['patient'].'/'.$medrek;
                $headers['x-token'] = "Bearer ".$call_login['token'];
                $headers['Content-Type'] = 'application/x-www-form-urlencoded';
                $call_patient = $this->endpoint->call_endpoint($config_sync,$url,$headers);
                $array_insert = array(
                    "endpoint" => $url,
                    "responses" => $call_patient,
                    "created_at" => date("Y-m-d H:i:s")
                );
                $this->db->insert("log_respons",$array_insert);
                $data['detail_patient'] = json_decode($call_patient);

                $data['total_kunjungan'] = count($response_visit);
                $data['message'] = "Sukses Sync Data Kunjungan ke SIM Rumag Sakit";
                foreach ($response_visit as $key => $value) {
                    $html .= "<tr>";
                    $html .= "<td>".($key + 1) ."</td>";
                    $html .= "<td>".$value->id_kunjungan."</td>";
                    $html .= "<td>". date("d-M-Y", strtotime($value->tgl_kunjungan))."</td>";
                    $html .= "<td>".$value->id_dokter."</td>";
                    $html .= "<td>".$value->id_poli."</td>";
                    $html .= "<td>".$value->icd_code."</td>";
                    $html .= "<td>".$value->icd_description."</td>";
                    $html .= "<td>".$value->tindak_lanjut."</td>";
                    $html .= "</tr>";

                    $qry_check_doctor = "SELECT * FROM master_doctor WHERE 1 AND first_name like ? AND poli like ? ";
                    $run_check_doctor = $this->db->query($qry_check_doctor, array('%'.$value->id_dokter.'%','%'.$value->id_poli.'%'));
                    $doctor_id = NULL;
                    if ( $run_check_doctor->num_rows() > 0 ){
                        $res_check_doctor = $run_check_doctor->num_rows();
                        $doctor_id = $res_check_doctor[0]['id'];
                    }

                    $qry_check_kunjungan = "SELECT * FROM kunjungan WHERE 1 AND icd_code = ? AND medical_number = ? ";
                    $run_check_kunjungan = $this->db->query($qry_check_kunjungan,array($value->icd_code,$value->no_medical_record));

                    $array_insert = array(
                        "medical_number" => $value->no_medical_record,
                        "icd_code" => $value->icd_code,
                        "icd_description" => $value->icd_description,
                        "tanggal_kunjungan" => date("Y-m-d", strtotime($value->tgl_kunjungan)),
                        "action_type" => $value->tindak_lanjut,
                        "id_kunjungan" => $value->id_kunjungan,
                        "patient_login_id" => NULL,
                        "doctor_id" => $doctor_id,
                        "created_at" => date("Y-m-d H:i:s"),
                        "patient_id" => NULL,
                        'poli' => $value->id_poli
                    );
                    $this->db->insert("kunjungan",$array_insert);

                }
                $data['html'] = $html;
            }
            
            echo json_encode($data);
        }

    

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