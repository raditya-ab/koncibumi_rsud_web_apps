<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Api extends Public_controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('order_m');
    }

    public function fetch_pesanan()
    {   
        $status = ($this->input->post('status')) ? $this->input->post('status') : NULL;
        
        $orders = $this->order_m->fetch_orders($status);

        foreach($orders as $i => $order):
            $json = array(
                'no' => $i+1,
                'no_pesanan' => $order

            );
        endforeach;

        $this->output->set_content_type('application/json')->set_output(json_encode($orders));
    }

    public function generate_resep_form()
    {
        $form_html = $this->load->view('order/partials/form_resep', NULL, TRUE);
        $this->output->set_content_type('application/html')->set_output($form_html);
        
    }

}