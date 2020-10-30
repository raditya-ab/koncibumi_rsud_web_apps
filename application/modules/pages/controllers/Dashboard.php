<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Dashboard extends Public_controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_m','order_m');
        $this->load->model('access/access','access');
        
        $this->template->set_layout('main_without_sidebar');
    }

    public function index(){
        $_SESSION['user_id'] = '1';
        $_SESSION['user_group_id'] = '1';

        $config_status = array(
            "1" => "Menunggu Konfirmasi Dokter",
            "2" => "Menunggu Konfirmasi Farmasi",
            "3" => "Dijadwalkan Pengambilan/Pengiriman",
            "4" => "Sedang Dikirim/Siap diambil",
            "5" => "Ditolak",
            "6" => "Selesai"
        );
  
        $this->data['user_detail'] = $this->access->get_user($_SESSION['user_id']);
        $this->data['new_orders'] = $this->order_m->fetch_orders(1,5, $this->data['user_detail'][0]['id']);
        $this->data['list_orders'] = $this->order_m->fetch_orders(NULL,10, $this->data['user_detail'][0]['id']);
        $this->data['config_status'] = $config_status;

        $this->template->build('dashboard', $this->data);
    }
}