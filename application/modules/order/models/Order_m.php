<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Order_m extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function fetch_orders($status){
        $select = array(
            'o.id',
            'o.order_no as order_no',
            'o.created_at',
            'o.patient_id',
            'pl.no_medrec',
            'pl.no_bpjs',
            'CONCAT(pp.first_name," ",pp.last_name) as nama_pasien',
            'k.icd_code',
            'k.icd_description',
            'o.status'
        );

        $this->db->select($select);
        $this->db->from('order_patient o');
        $this->db->join('patient_login pl', 'pl.id = o.patient_id', 'left');
        $this->db->join('patient_profile pp', 'pp.patient_id = o.patient_id', 'left');
        $this->db->join('kunjungan k', 'k.patient_id = o.patient_id', 'left');
        
        if($status != NULL){
            $this->db->where('o.status', $status);
        }

        return $this->db->get('order_patient')->result_array();
    }

    public function fetch_orders_after_last_visit()
    {

    }
}