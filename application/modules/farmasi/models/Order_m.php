<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Order_m extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function fetch_orders($status, $limit = NULL){
        $select = array(
            'DISTINCT(o.id) as id',
            'o.order_no',
            'o.created_at',
            'o.patient_id',
            'CONCAT(d.first_name," ",d.last_name) as doctor_name',
            'pp.medical_number as no_medrec',
            'pp.bpjs_number as no_bpjs',
            'CASE WHEN pp.gender = "P" THEN "Perempuan" ELSE "Laki-laki" END as gender',
            'CONCAT(pp.first_name," ",pp.last_name) as nama_pasien',
            'k.icd_code',
            'k.icd_description',
            'o.created_at',
            'o.status'
        );

        $this->db->select($select, FALSE);
        $this->db->from('order_patient o');
        // $this->db->join('patient_login pl', 'pl.id = o.patient_id', 'left');
        $this->db->join('patient_profile pp', 'pp.id = o.patient_id', 'left');
        $this->db->join('kunjungan k', 'k.patient_id = pp.id', 'left');
        $this->db->join('master_doctor d', 'd.id = o.doctor_id', 'left');
        
        
        if($status != NULL){
            $this->db->where('o.status', $status);
        }
        if($limit != NULL){
            $this->db->limit($limit);
        }

        return $this->db->get('order_patient')->result_array();
    }

    public function fetch_orders_after_last_visit()
    {

    }
}