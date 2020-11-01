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
        $qry_detail_order = "SELECT op.*,pp.bpjs_number as bpjs_number,pp.medical_number as medical_number,
            pp.dob as dob,pp.gender as gender,pl.blood_type as gol_darah,
            md.first_name as first_name, md.poli as poli,op.patient_id as patient_id
            FROM order_patient as op
            INNER JOIN patient_profile as pp ON (op.patient_id = pp.id)
            INNER JOIN patient_login as pl ON (pp.patient_login_id = pl.id)
            INNER JOIN master_doctor as md ON ( md.id = op.doctor_id )
            WHERE 1 AND op.id = ?" ;
        $run_detail_order = $this->db->query($qry_detail_order,array($id));
        $res_detail_order = $run_detail_order->result_array();
        return $res_detail_order;
    }

    public function latest_visit($order_id = ""){
        $data = array();
        $order_detail = $this->order_detail($order_id);
        $qry_kunjungan = "SELECT k.id_kunjungan as order_no, k.tanggal_kunjungan as tanggal_kunjungan,
            md.first_name as name, md.poli as poli, k.icd_code as icd_code, k.icd_description as icd_description
            FROM kunjungan as k 
            INNER JOIN patient_profile as pp ON (k.patient_id = pp.id)
            INNER JOIN master_doctor as md ON (md.id = k.doctor_id)
            WHERE 1 AND k.patient_id = ? 
            order by k.id desc limit 0,3";
        $run_kunjungan = $this->db->query($qry_kunjungan,array($order_detail[0]['patient_id']));
        $data = $run_kunjungan->result_array();
        return $data;
    }

    public function latest_receipt($id = ""){
        $data = array();
        return $data;
    }

    public function total_receipt($order_id){
        $total = 0;
        $qry_check_receipt = "SELECT * FROM receipt_header where 1 AND kunjungan_id = ? ";
        $run_check_receipt = $this->db->query($qry_check_receipt,array($order_id));
        if ( $run_check_receipt->num_rows()){
            $res_check_receipt = $run_check_receipt->result_array();
            $qry_total_detail = "SELECT * FROM receipt_detail where 1 AND receipt_header_id = ? ";
            $run_total_detail = $this->db->query($qry_total_detail,array($res_check_receipt[0]['id']));
            return $run_total_detail->result_array();
        }

        return $total;
    }

    public function save_receipt($order_id,$doctor_id,$obat,$qty,$unit,$dosis,$frekuensi,$description_receupt){
        $this->load->model('app/master_model','master');


        if ( count($obat) > 0 ){
            $array_insert_receipt = array(
                "kunjungan_id" => $order_id,
                "doctor_id" => $doctor_id,
                "created_at" => date("Y-m-d H:i:s"),
                "description" => $description_receupt
            );
            $this->db->insert("receipt_header",$array_insert_receipt);
            $receipt_id = $this->db->insert_id(); 
            $receipt_no = "KNCBMR".$receipt_id;

            $restricted = 0;
            foreach ($obat as $key => $value) {
                $data_obat = $this->master->get_detail_medicine($value);
                if ( count($data_obat) > 0 ){
                    $array_insert_receipt_detail = array(
                        "receipt_header_id" => $receipt_id,
                        "obat" => $value,
                        "dosis" => $dosis[$key],
                        "qty" => $qty[$key],
                        "satuan" => $unit[$key],
                        "frekuensi" => $frekuensi[$key]
                    );
                    $this->db->insert("receipt_detail",$array_insert_receipt_detail);
                    if ( $data_obat[0]['restricted'] == "1"){
                        $restricted = 1;
                    }
                }
            }

            $this->db->query("UPDATE receipt_header set receipt_no = '$receipt_no', restricted = '$restricted' where id = '$receipt_id'");

            $this->db->query("UPDATE order_patient set status = 2, doctor_approve_time = now() where id = '$order_id'");
        }



        return true;
    }
}