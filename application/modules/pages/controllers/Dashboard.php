<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Dashboard extends Public_controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_m','order_m');
        $this->load->model('access/access','access');
        $this->load->model("login/login_model","login");
        $this->load->model("admin/Admin_model","admin");
        
        $this->template->set_layout('main_without_sidebar');

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

    public function index(){
        $new_orders = array();
        $list_orders = array();

        $config_status = array(
            "1" => "Menunggu Konfirmasi Dokter",
            "2" => "Menunggu Konfirmasi Farmasi",
            "3" => "Dijadwalkan Pengambilan/Pengiriman",
            "4" => "Sedang Dikirim/Siap diambil",
            "5" => "Sedang Dikirim",
            "6" => "Selesai",
            "7" => "Ditolak"
        );
  
        $this->data['user_detail'] = $this->access->get_user($_SESSION['user_id']);
        if ( count($this->data['user_detail']) > 0 ){
            $new_orders = $this->order_m->fetch_orders(1,5, $this->data['user_detail'][0]['id']);
            $list_orders = $this->order_m->fetch_orders(NULL,10, $this->data['user_detail'][0]['id']);
        }

        $this->data['new_orders'] = $new_orders;
        $this->data['list_orders'] = $list_orders;
        $this->data['config_status'] = $config_status;

        $this->template->build('dashboard', $this->data);
    }
}