<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Dashboard extends Public_controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_m','order_m');
        
        $this->template->set_layout('main_without_sidebar');
    }

    public function index(){
        $this->data['new_orders'] = $this->order_m->fetch_orders(1,5);
        $this->data['list_orders'] = $this->order_m->fetch_orders(NULL,5);
        $this->template->build('farmasi/pages/dashboard', $this->data);
    }
}