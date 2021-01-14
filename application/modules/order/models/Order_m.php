<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Order_m extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('app/master_model','master');

    }

    public function fetch_orders($status, $limit = NULL, $doctor_id = NULL){
        $select = array(
            'DISTINCT(o.id) as id',
            'o.order_no',
            // 'o.created_at',
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
        // $this->db->from('order_patient o');
        // $this->db->join('patient_login pl', 'pl.id = o.patient_id', 'left');
        $this->db->join('patient_profile pp', 'pp.id = o.patient_id', 'inner');
        $this->db->join('master_doctor md', 'md.id = o.doctor_id','inner');
        if($status != NULL){
            $this->db->where('o.status', $status);
        }
        // if($doctor_id != NULL){
        //     $this->db->where('o.doctor_id', $doctor_id);
        // }
        if($limit != NULL && $limit > 0){
            $this->db->limit($limit);
        }

        $this->db->where('o.doctor_id', $doctor_id);
        $order_list = $this->db->get('order_patient o')->result_array();

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

    public function fetch_orders_after_last_visit($patient_id)
    {
        $qry_order = "SELECT * FROM order_patient WHERE 1 AND patient_id = ? LIMIT 0,10 ";
        $run_order = $this->db->query($qry_order,array($patient_id));
        $res_order = $run_order->result_array();
        return $res_order;
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
            md.first_name as first_name, md.poli as poli,op.patient_id as patient_id,
            pp.first_name as patient_name
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

    public function latest_receipt($order_id = ""){
        $order_detail = $this->order_detail($order_id);
        $all_order = $this->fetch_orders_after_last_visit($order_detail[0]['patient_id']);
        $receipt = array();
        foreach ($all_order as $key => $value) {
            $order_id = $value['id'];
            $order_no = $value['order_no'];
            $list_receipt = array();
            $qry_receipt_header = "SELECT * FROM receipt_header WHERE 1 AND kunjungan_id = ? ";
            $run_receipt_header = $this->db->query($qry_receipt_header,array($order_id));
            if ( $run_receipt_header->num_rows() > 0 ){
                $res_receipt_header = $run_receipt_header->result_array();
                $list_receipt_detail = $this->get_create_receipt($res_receipt_header[0]['id']);
                $list_receipt['order_id'] = $order_id;
                $list_receipt['order_no'] = $order_no;
                $list_receipt['receipt_id'] = $res_receipt_header[0]['id'];
                $list_receipt['receipt_no'] = $res_receipt_header[0]['receipt_no'];
                $list_receipt['created_at'] = $res_receipt_header[0]['created_at'];
                $list_receipt['receipt_detail'] = $list_receipt_detail;
                $receipt[] = $list_receipt;
            }
        }

        return $receipt;
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

        $receipt_id = "";
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
                $data_obat = $this->master->get_detail_medicine($value['value']);
                if ( count($data_obat) > 0 ){
                    $array_insert_receipt_detail = array(
                        "receipt_header_id" => $receipt_id,
                        "obat" => $value['value'],
                        "dosis" => $dosis[$key]['value'],
                        "qty" => $qty[$key]['value'],
                        "satuan" => $unit[$key]['value'],
                        "frekuensi" => $frekuensi[$key]['value']
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



        return $receipt_id;
    }

    public function get_create_receipt($receipt_id){
        $receipt = array();
        $qry_receipt = "SELECT * FROM receipt_detail WHERE 1 AND receipt_header_id = ? ";
        $run_receipt = $this->db->query($qry_receipt,array($receipt_id));
        if ( $run_receipt->num_rows() > 0 ){
            $res_receipt = $run_receipt->result_array();
            foreach ($res_receipt as $key => $value) {
                $data_obat = $this->master->get_detail_medicine($value['obat']);
                $list_array = array();
                $list_array['medicine_id'] = $value['id'];
                $list_array['name'] = $data_obat[0]['name'];
                $list_array['order_qty'] = $value['qty'];
                $list_array['unit'] = $value['satuan'];
                $list_array['dosis'] = $value['dosis'];
                $list_array['frekuensi'] = $value['frekuensi'];
                $receipt[] = $list_array;
            }
        }

        return $receipt;
    }

    public function get_resep($receipt_id){
        $qry_receipt = "SELECT * FROM receipt_header WHERE 1 AND id = ?  ";
        $run_receipt = $this->db->query($qry_receipt,array($receipt_id));
        $res_receipt = $run_receipt->result_array();
        return $res_receipt;
    }
}