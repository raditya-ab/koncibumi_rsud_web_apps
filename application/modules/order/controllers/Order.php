<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Order extends Public_controller
{
    public function __construct(){
        parent::__construct();
    }

    public function index()
    {
        $this->template->build('order_list');
    }

    public function new()
    {
        $this->template->build('new_order_list');
    }
    public function proses(){
        $this->data['sidebar_header'] = $this->template->load_view('pages/partials/sidebar_header');
        $this->data['sidebar_content'] = $this->template->load_view('pages/partials/sidebar_content');
        $this->template->set_partial('sidebar','partials/_sidebar.php', $this->data);

        $this->template->set_layout('main_with_sidebar');
        $this->template->build('proses_pesanan');
    }
}