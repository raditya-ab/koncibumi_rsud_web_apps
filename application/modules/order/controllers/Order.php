<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Order extends Public_controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_m','order_m');
        $this->load->model('access/access','access');
        $_SESSION['user_id'] = '1';
        $_SESSION['user_group_id'] = '1';
        $this->config->load('config');
    }

    public function index()
    {
        $this->template->build('order_list');
    }

    public function list($type = "")
    {
        $this->data['user_detail'] = $this->access->get_user($_SESSION['user_id']);
        $new_orders = array();
        if ( count($this->data['user_detail']) > 0 ){
            $new_orders = $this->order_m->fetch_orders(1,5,$this->data['user_detail'][0]['id']);
        }
        $this->data['new_orders'] = $new_orders;
        $this->data['type'] = $type;
        $this->template->build('new_order_list', $this->data);
    }
    public function proses(){
        $order_id = $_GET['order_id'];
        $this->data['sidebar_header'] = $this->template->load_view('pages/partials/sidebar_header');
        $this->data['sidebar_content'] = $this->template->load_view('pages/partials/sidebar_content');
        $this->data['obat'] = $this->order_m->getObat();
        $this->data['user_detail'] = $this->access->get_user($_SESSION['user_id']);
        $this->data['new_orders'] = $this->order_m->fetch_orders(1,5,$this->data['user_detail'][0]['id']);
        $this->data['order_detail'] = $this->order_m->order_detail($order_id);
        $this->data['latest_visit'] = $this->order_m->latest_visit();
        $this->data['latest_receipt'] = $this->order_m->latest_receipt();

        $this->template->set_partial('sidebar','partials/_sidebar.php', $this->data);

        $this->template->set_layout('main_with_sidebar');
        $this->template->build('proses_pesanan',$this->data);
    }

    public function kurir()
    {
        $this->template->build('kurir');
    }

    public function list_detail_order($type = "new"){
        $array_type = array(
            "new" => 1,
            "all" => NULL
        );

        $data = array();
        $user_detail = $this->access->get_user($_SESSION['user_id']);
        $list_order = $this->order_m->fetch_orders($array_type[$type],5, $user_detail[0]['id']);
        $status_order = $this->config->item('status_order');
        $array_data = array();
        foreach ($list_order as $key => $value) {
            $detail_order = array();
            $profile_data = $this->access->profile_patient($value['patient_id']);
            $list_patient_data['no_bpjs'] = $profile_data[0]['bpjs_number'];
            $list_patient_data['no_medrek'] = $profile_data[0]['medical_number'];

            $list_status['code'] = $value['status'];
            $list_status['label'] = $status_order[$value['status']];

            $list_diagnose['icd_code'] = "";
            $list_diagnose['icd_description'] = "";

            $detail_order['no'] = $key + 1;
            $detail_order['id_pesanan'] = $value['id'];
            $detail_order['no_pesanan'] = $value['order_no'];
            $detail_order['patient_data'] = $list_patient_data;
            $detail_order['nama_pasien'] = $profile_data[0]['first_name'] .' '.$profile_data[0]['last_name'];
            $detail_order['diagnose'] = $list_diagnose;
            $detail_order['tanggal_pesanan'] = $value['created_at'];
            $detail_order['total_order_after_last_visit'] = 2;
            $detail_order['status'] = $list_status;
            $array_data[] = $detail_order;
        }
        $data['results'] = $array_data;
        echo json_encode($data);
    }

    public function reject(){
        $obj = file_get_contents('php://input');
        $edata = json_decode($obj);
        $doctor_approval_time = date("Y-m-d H:i:s");
        $reason = $edata->dismiss_reason;
        $order_id = $edata->id_pesanan;
        $this->db->query("UPDATE order_patient set status = '5',doctor_approve_time = '$doctor_approval_time',reason ='$reason' where id = '$order_id'");
        $data['status'] = "ok";
        echo json_encode($data);
    }

    public function submit_receipt(){
        print_r($this->input->post("obat"));
    }
}