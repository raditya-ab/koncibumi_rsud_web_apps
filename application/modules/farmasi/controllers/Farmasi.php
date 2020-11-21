<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Farmasi extends Public_controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('farmasi_model','farmasi');
        $this->load->model("login/login_model","login");
        $this->load->model("order/Order_m","order");
        $this->load->library('email');
        $this->config->load('config');

        if ( isset($_SESSION['user_id'])){
            $this->profile_data = $this->login->get_profile_data($_SESSION['user_id']);
            $module = $this->profile_data['menu']['url'];
            if ( strtolower($module) != "farmasi"){
                redirect("login/logout");
            }
        }else{
            redirect("login/logout");
        }
    }

    public function index(){
    	$data['receipt'] = $this->farmasi->get_all_receipt();
    	$data['profile'] = $this->profile_data;
        $data['kurir'] = $this->farmasi->get_all_kurir();
        $data['pending'] = $this->farmasi->get_all_pending();
    	$this->load->view("index",$data);
    }

    public function history(){

    }

    public function getdetail(){
        $receipt_id = $this->input->post("id");
        $data['order_no'] = "";
        $data['receipt_no'] = "";
        $data['patient_name'] = "";
        $data['doctor_name'] = "";
        $data['poli'] = "";
        $data['desc'] = "";
        $data['list_obat'] = "";
        $data['status'] = "";
        $data['restricted'] = "";
        $data['resep_id'] = "";
        $data['current_status'] = "";
        $data['next_status'] = "";
        $data['delivery_date'] = "";
        $html_list_obat = "";

        $qry_get_receipt = "SELECT rh.*, md.first_name as doctor_name, md.poli as poli,
        pp.first_name as patient_first_name, pp.last_name as patient_last_name,op.status as status,
        op.order_no,op.farmasi_id as farmasi_id,op.delivery_date
        FROM receipt_header as rh
        INNER JOIN order_patient as op ON (op.id = rh.kunjungan_id ) 
        INNER JOIN master_doctor as md ON (md.id = op.doctor_id)
        INNER JOIN patient_profile as pp ON (pp.id = op.patient_id)
        WHERE 1 AND rh.id = ? ";
        $run_get_receipt = $this->db->query($qry_get_receipt,array($receipt_id));
        if ( $run_get_receipt->num_rows() > 0 ){
            $res_get_receipt = $run_get_receipt->result_array();
            $receipt_id = $res_get_receipt[0]['id'];
            $list_obat = $this->order->get_create_receipt($receipt_id);
            foreach ($list_obat as $key => $value) {
                $html_list_obat .= "<tr>";
                $html_list_obat .= "<td>".($key + 1) ."</td>";
                $html_list_obat .= "<td>".$value['name']."</td>";
                $html_list_obat .= "<td>".$value['order_qty']."</td>";
                $html_list_obat .= "<td>".$value['unit']."</td>";
                $html_list_obat .= "<td>".$value['dosis']."</td>";
                $html_list_obat .= "<td>".$value['frekuensi']."</td>";
                $html_list_obat .= "</tr>";
            }


            $data['order_no'] = $res_get_receipt[0]['order_no'];
            $data['receipt_no'] = $res_get_receipt[0]['receipt_no'];
            $data['patient_name'] = $res_get_receipt[0]['patient_first_name'].' '.$res_get_receipt[0]['patient_last_name'];
            $data['doctor_name'] = $res_get_receipt[0]['doctor_name'];
            $data['poli'] = $res_get_receipt[0]['poli'];
            $data['desc'] = $res_get_receipt[0]['description'];
            $data['list_obat'] = $html_list_obat;
            $data['current_status'] = $res_get_receipt[0]['status'];
            $data['restricted'] = $res_get_receipt[0]['restricted'];
            $data['resep_id'] = $res_get_receipt[0]['id'];
            $data['delivery_date'] = $res_get_receipt[0]['delivery_date'];
            
            $data['next_status'] = 3;
            if ( $res_get_receipt[0]['restricted'] == 1 ){
                $data['next_status'] = 4;
            }

            if ( $res_get_receipt[0]['farmasi_id'] != ""){
                $data['next_status'] = 5;
                if ( $res_get_receipt[0]['restricted'] == 1 ){
                    $data['next_status'] = 6;
                }
            }
        }

        $data['status'] = 0;
        echo json_encode($data);
    }

    public function proses(){
        $resep_id = $this->input->post("id");
        $status = $this->input->post("status");
        $delivery_date = $this->input->post("delivery_date");
        $detail_resep = $this->farmasi->get_detail_receipt($resep_id);
        $order_id = $detail_resep[0]['order_id'];

        $array_message = array(
            "3" => "Dijadwalkan Pengiriman",
            "4" => "Dijadwalkan Pengambilan",
            "5" => "Pesanan dalam pengiriman",
            "6" => "Pesanan telah selesai"
        );
        $farmasi_id = $this->profile_data['member_id'];
        $delivery_date = date("Y-m-d H:i:s",strtotime($delivery_date));

        $clause_delivert_date = "";
        if ( $status == "3" || $status == "4"){
            $clause_delivert_date = ", delivery_date = '$delivery_date',";
        }
        
        $clause_finish_date = "";
        if ( $status == "6"){
            $clause_finish_date = ", updated_at = NOW(), received_date = NOW(), ";
        }

        $this->db->query("UPDATE order_patient SET farmasi_id = '$farmasi_id', farmasi_approve_time = NOW() $clause_delivert_date $clause_finish_date status = '$status' WHERE id ='$order_id'");

        $array_insert = array(
            "notification" => $array_message[$status],
            "read_status" => 1,
            "created_at" => date("Y-m-d H:i:s"),
            "profile_id" => $detail_resep[0]['patient_id']
        );

        $this->db->insert("notification",$array_insert);
        $data['status'] = 0;
        echo json_encode($data);
        
    }

    public function waiting(){
        $data['profile'] = $this->profile_data;
        $data['pending'] = $this->farmasi->get_all_pending();
        $this->load->view("waiting",$data);
    }
}	

?>