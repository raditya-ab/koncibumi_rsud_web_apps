<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Order_m extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function fetch_orders($status, $limit = NULL, $doctor_id = ""){
        $select = array(
            'DISTINCT(o.id) as id',
            'o.order_no',
            'o.created_at',
            'o.patient_id',
            'pp.medical_number as no_medrec',
            'pp.bpjs_number as no_bpjs',
            'CASE WHEN pp.gender = "P" THEN "Perempuan" ELSE "Laki-laki" END as gender',
            'CONCAT(pp.first_name," ",pp.last_name) as nama_pasien',
            'o.created_at',
            'o.status',
            'o.doctor_approve_time',
            'o.id_pesanan'
        );

        $this->db->select($select, FALSE);
        $this->db->from('order_patient o');
        // $this->db->join('patient_login pl', 'pl.id = o.patient_id', 'left');
        $this->db->join('patient_profile pp', 'pp.id = o.patient_id', 'inner');
        $this->db->join('master_doctor md', 'md.id = o.doctor_id','inner');
        if($status != NULL){
            $this->db->where('o.status', $status);
        }
        if($limit != NULL){
            $this->db->limit($limit);
        }

        $this->db->where('o.doctor_id', $doctor_id);
        $order_list = $this->db->get('order_patient')->result_array();

        foreach($order_list as $i => $item){
            $select = array(
                'k.icd_code',
                'k.icd_description',
                'CONCAT(d.first_name," ",d.last_name) as doctor_name',
                'd.poli'
            );
            $this->db->select($select);
            
            $this->db->from('kunjungan k');
            $this->db->join('master_doctor d', 'k.doctor_id = d.id', 'inner');
            $this->db->where('k.patient_id', $item['patient_id']);
            $this->db->order_by('k.tanggal_kunjungan', 'desc');
            $this->db->limit(1);
            
            $kunjungan = $this->db->get()->row_array();

            if(!empty($kunjungan)){
                $order_list[$i]+=$kunjungan;
            }

            $this->db->select('COUNT(o.patient_id) as jml_kunjungan');
            $this->db->from('order_patient o');
            $this->db->join('kunjungan k', 'k.patient_id = o.patient_id', 'left');
            
            $this->db->where('o.created_at > k.tanggal_kunjungan');
            $jml_kunjungan = $this->db->get()->row_array();
            
            if(!empty($jml_kunjungan)){
                $order_list[$i]+=$jml_kunjungan;
            }
            
        }

        return $order_list;
    }

    public function fetch_orders_after_last_visit()
    {

    }

    public function getObat(){
        $obat = array();
        $qry = "SELECT * from master_medicine";
        $run = $this->db->query($qry);
        $res = $run->result_array();
        $obat = $res;
        return $obat;
    }

    public function order_detail($id){
        $qry_detail_order = "SELECT * FROM order_patient WHERE 1 AND id = ?" ;
        $run_detail_order = $this->db->query($qry_detail_order,array($id));
        $res_detail_order = $run_detail_order->result_array();
        return $res_detail_order;
    }

    public function latest_visit($id = ""){
        $data = array();
        return $data;
    }

    public function latest_receipt($id = ""){
        $data = array();
        return $data;
    }
}