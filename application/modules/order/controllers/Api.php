<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Api extends Public_controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('order_m');
    }

    public function fetch_pesanan($full_return = TRUE)
    {   
        $orders = $this->order_m->fetch_orders();
        
        $status_label = array(
            'Ditolak',
            'Menunggu Konfirmasi Dokter',
            'Sudah Dikonfirmasi',
            'Sudah Dikonfirmasi',
            'Sudah Dikonfirmasi',
            'Sudah Dikonfirmasi',
            'Sudah Dikonfirmasi',
            'Sudah Dikonfirmasi'
        );
        foreach($orders as $i => $order):
            $json = array(
                'no' => $i+1,
                'no_pesanan' => $order['order_no'],
                'tanggal_pesanan' => $order['created_at'],
                'patient_data' => array(
                    "no_medrek" => $order['no_medrec'],
                    "no_bpjs" => $order['no_bpjs']
                ),
                'nama_pasien' => $order['nama_pasien'],
                'diagnose' => array(
                    "icd_code" => $order['icd_code'],
                    "icd_description" => $order['icd_description']
                ),
                'total_order_after_last_visit' => $order['jml_kunjungan'],
                'status' => array(
                    'code' => $order['status'],
                    'label' => $status_label[$order['status']]
                ),
                'id_pesanan' => $order['id']
            );
        endforeach;

        if($full_return === TRUE){
            $this->output->set_content_type('application/json')->set_output(json_encode($orders));
        } else {
            $data = array();
            $data['results'][] = $json;
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function generate_resep_form()
    {
        $form_html = $this->load->view('order/partials/form_resep', NULL, TRUE);
        $this->output->set_content_type('application/html')->set_output($form_html);
        
    }

}