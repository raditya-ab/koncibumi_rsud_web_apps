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
    }

    public function index()
    {
        $this->template->build('order_list');
    }

    public function new()
    {
        $this->data['user_detail'] = $this->access->get_user($_SESSION['user_id']);
        $this->data['new_orders'] = $this->order_m->fetch_orders(1,5,$this->data['user_detail'][0]['id']);
        $this->template->build('new_order_list', $this->data);
    }
    public function proses(){
        $this->data['sidebar_header'] = $this->template->load_view('pages/partials/sidebar_header');
        $this->data['sidebar_content'] = $this->template->load_view('pages/partials/sidebar_content');
        $this->data['obat'] = $this->order_m->getObat();
        $this->template->set_partial('sidebar','partials/_sidebar.php', $this->data);

        $this->template->set_layout('main_with_sidebar');
        $this->template->build('proses_pesanan');
    }

    public function kurir()
    {
        $this->template->build('kurir');
    }

    public function approve(){
        
    }

    public function list_order($type = "new"){
        $array_type = array(
            "new" => 1,
            "all" => NULL
        );

        $data = array();
        $user_detail = $this->access->get_user($_SESSION['user_id']);
        $list_order = $this->order_m->fetch_orders($array_type[$type],5, $user_detail[0]['id']);
        $array_data = array();
        foreach ($list_order as $key => $value) {
            $detail_order = array();
            $profile_data = $this->access->profile_patient($value['patient_id']);
            $list_patient_data['no_bpjs'] = $profile_data[0]['bpjs_number'];
            $list_patient_data['no_medrek'] = $profile_data[0]['medical_number'];

            $list_status['code'] = $value['status'];
            $list_status['label'] = "";

            $list_diagnose['icd_code'] = "";
            $list_diagnose['icd_description'] = "";

            $detail_order['no'] = $key + 1;
            $detail_order['id_pesanan'] = $value['id'];
            $detail_order['no_pesanan'] = $value['id_pesanan'];
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
}