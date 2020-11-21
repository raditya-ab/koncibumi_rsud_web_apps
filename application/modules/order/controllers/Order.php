<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

Class Order extends Public_controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_m','order_m');
        $this->load->model('access/access','access');
        $this->load->model('api/Profile_model','profile');
        $this->load->model("login/login_model","login");
        $this->load->model("admin/Admin_model","admin");
        $this->config->load('config');
        
        if ( isset($_SESSION['user_id'])){
            $this->profile_data = $this->login->get_profile_data($_SESSION['user_id']);
            $module = $this->profile_data['menu']['url'];
            if ( strtolower($module) != "pages/dashboard/index/"){
                redirect("login/logout");
            }
        }else{
            redirect("login/logout");
        }
    }

    public function index()
    {
        $this->template->build('order_list');
    }

    public function list($type = "")
    {

        $array_type = array(
            "new" => 1,
            "all" => NULL,
            "" => NULL
        );

        $this->data['user_detail'] = $this->access->get_user($_SESSION['user_id']);
        $new_orders = array();
        if ( count($this->data['user_detail']) > 0 ){
            $new_orders = $this->order_m->fetch_orders($array_type[$type],5,$this->data['user_detail'][0]['id']);
        }
        $this->data['new_orders'] = $new_orders;
        $this->data['type'] = $type;
        $this->template->build('new_order_list', $this->data);
    }
    public function proses(){
        $order_id = $_GET['order_id'];
        $array_gender = array(
            "L" => "Laki-laki",
            "P" => "Perempuan"
        );
        $this->data['obat'] = $this->order_m->getObat();
        $this->data['user_detail'] = $this->access->get_user($_SESSION['user_id']);
        $this->data['new_orders'] = $this->order_m->fetch_orders(1,5,$this->data['user_detail'][0]['id']);
        $this->data['order_detail'] = $this->order_m->order_detail($order_id);
        $this->data['latest_visit'] = $this->order_m->latest_visit($order_id);
        $this->data['latest_receipt'] = $this->order_m->latest_receipt($order_id);
        $this->data['array_gender'] = $array_gender;

        $this->template->set_partial('sidebar','partials/_sidebar.php', $this->data);
        $this->data['sidebar_header'] = $this->template->load_view('pages/partials/sidebar_header');
        $this->data['sidebar_content'] = $this->template->load_view('pages/partials/sidebar_content',$this->data);

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
            "all" => NULL,
            "" => NULL
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

            $get_diagones = $this->profile->get_diagones($value['patient_id']);
            $list_diagnose['icd_code'] = $get_diagones['icd_code'];
            $list_diagnose['icd_description'] = $get_diagones['icd_description'];

            $detail_order['no'] = $key + 1;
            $detail_order['id_pesanan'] = $value['id'];
            $detail_order['no_pesanan'] = $value['order_no'];
            $detail_order['patient_data'] = $list_patient_data;
            $detail_order['nama_pasien'] = $profile_data[0]['first_name'] .' '.$profile_data[0]['last_name'];
            $detail_order['diagnose'] = $list_diagnose;
            $detail_order['tanggal_pesanan'] = $value['created_at'];
            $detail_order['total_order_after_last_visit'] = $get_diagones['total_kunjungan'];
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

    public function download($filetype, $datatype = ""){
        $array_status = array(
            "new" => 1,
            "" => NULL
        );

        $status_order = $this->config->item('status_order');
        $user_detail = $this->access->get_user($_SESSION['user_id']);
        $list_order = $this->order_m->fetch_orders($array_status[$datatype],NULL, $_SESSION['user_id']);
        $spreadsheet = new Spreadsheet;
        $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A1', 'No')
        ->setCellValue('B1', 'No.Pesanan')
        ->setCellValue('C1', 'Tanggal Pesanan')
        ->setCellValue('D1', 'No. BPJS / Rekam Medis')
        ->setCellValue('E1', 'Nama Pasien')
        ->setCellValue('F1', 'Diagnosa Terakhir')
        ->setCellValue('G1', 'Pesanan Obat')
        ->setCellValue('H1', 'Status Pesanan');

        $kolom = 2;
        $nomor = 1;
        foreach ($list_order as $key => $value) {
            $icd_description = "";
            if ( isset($value['icd_description'])){
                $icd_description = $value['icd_description'];
            }

            $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A'.$kolom, $nomor)
            ->setCellValue('B'.$kolom, $value['order_no'])
            ->setCellValue('C'.$kolom, date("d-M-Y",strtotime($value['created_at'])))
            ->setCellValue('D'.$kolom, $value['no_bpjs'])
            ->setCellValue('E'.$kolom, $value['nama_pasien'])
            ->setCellValue('F'.$kolom, $icd_description)
            ->setCellValue('G'.$kolom, count($this->order_m->total_receipt($value['id'])))
            ->setCellValue('H'.$kolom, $status_order[$value['status']]);
            $kolom++;
            $nomor++;
        }

        $writer = new Xlsx($spreadsheet);
        $doctor_name = $user_detail[0]['first_name'];

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Order_'.$doctor_name.'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function submit_receipt(){
        $order_id = $this->input->post("order_id");
        $data_doctor = $this->access->get_user($_SESSION['user_id']);
        $obat = $this->input->post("obat");
        $qty = $this->input->post("qty");
        $unit = $this->input->post("unit");
        $dosis = $this->input->post("dosis");
        $frekuensi = $this->input->post("frekuensi");
        $description_receupt = $this->input->post("description_receupt");

        if ( count($obat) > 0 ){
            $save_receipt = $this->order_m->save_receipt($order_id,$data_doctor[0]['id'],$obat,$qty,$unit,$dosis,$frekuensi,$description_receupt);
        }

        redirect(base_url().'order/proses/?order_id='.$order_id); 
    }
}