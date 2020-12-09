<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Crontask extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }


    public function save_doctor($data){
    	foreach ($data as $key => $value) {
            $qry_check = "SELECT * FROM master_doctor WHERE 1 AND id_dokter = ? ";
            $run_check = $this->db->query($qry_check, array($value->id_dokter));
            if ( $run_check->num_rows() > 0 ){
                continue;
            }

            if ( $value->nama_dokter == "" || $value->nama_dokter == "-"){
                continue;
            }

    		$doctor_name = $value->nama_dokter;
    		$explode_name = explode(".", $doctor_name);
    		$array_insert = array(
    			"id_dokter" => $value->id_dokter,
    			"nik" => $value->NIK,
    			"first_name" => $doctor_name,
    			"last_name" => NULL,
    			"jabatan" => $value->spesialis,
    			"poli" => $value->poli,
                "created_at" => date("Y-m-d H:i:s")
    		);

    		$this->db->insert("master_doctor",$array_insert);
    	}

    	return true;
    }

    public function save_patient($data){

        $qry_login = "SELECT * FROM patient_login WHERE 1 AND no_bpjs = ? OR no_medrec = ? ";
        $run_login = $this->db->query($qry_login,array($data->no_bpjs,$data->no_medical_record));

        if ( $run_login->num_rows() > 0 ){
            return true;
        }

        $first_name = "";
        $last_name = "";
        $explode_full_name = explode(" ",$data->patient_name);
        if ( count($explode_full_name) > 1 ){
            $first_name = $explode_full_name[0];
            $last_name = str_replace($explode_full_name[0], "", $data->patient_name);
        }else{
            $first_name = $data->patient_name;
        }

        $array_insert = array(
            "no_bpjs" => $data->no_bpjs,
            "no_medrec" => $data->no_medical_record,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "dob" => $data->date_of_birth,
            "gender" => $data->sex,
            "blood_type" => $data->blood_type,
            "rhesus" => $data->rhesus,
            "address" => $data->address,
            "mobile_number" => $data->mobile_no,
            "marrital_status" => $data->marital_status
        );

        $this->db->insert("patient_login",$array_insert);
        return true;
    }

    public function save_visit($data){
        foreach ($data as $key => $value) {
            $qry_check_kunjungan = "SELECT * FROM kunjungan WHERE 1 AND id_kunjungan = ? ";
            $run_check_kunjungan = $this->db->query($qry_check_kunjungan,array($value->id_kunjungan));
            if ( $run_check_kunjungan->num_rows() > 0 ){
                continue;
            }

            $patient_login_id = "";
            $qry_login_id = "SELECT * FROM patient_login WHERE 1 AND no_medrec = ? ";
            $run_login_id = $this->db->query($qry_login_id,array($value->no_medical_record));
            if ( $run_login_id->num_rows() > 0 ){
                $res_login_id = $run_login_id->result_array();
                $patient_login_id = $res_login_id[0]['id'];
            }

            $patient_id = "";
            $qry_profile_id = "SELECT * FROM patient_profile WHERE 1 AND patient_login_id = ? ";
            $run_profile_id = $this->db->query($qry_profile_id,array($patient_login_idd));
            if ( $run_profile_id->num_rows() > 0 ){
                $res_profile_id = $run_profile_id->result_array();
                $patient_id = $res_profile_id[0]['id'];
            }

            $array_insert = array(
                "medical_number" => $value->no_medical_record,
                "icd_code" => $value->icd_code,
                "icd_description" => $value->icd_description,
                "tanggal_kunjungan" => date("Y-m-d", strtotime($value->tgl_kunjungan)),
                "action_type" => $value->tindak_lanjut,
                "id_kunjungan" => $value->id_kunjungan,
                "patient_login_id" => $patient_login_id,
                "doctor_id" => 1,
                "created_at" => date("Y-m-d H:i:s"),
                "patient_id" => $patient_id
            );
            $this->db->insert("kunjungan",$array_insert);
        }

        return true;
    }

    public function save_medicine($data){
        foreach ($data as $key => $value) {
            $qry_check_medicine = "SELECT * FROM master_medicine WHERE 1 AND code = ? ";
            $run_check_medicine = $this->db->query($qry_check_medicine,array($value->kode_obat));
            $array_insert = array(
                "brand" => $value->nama_sales,
                "name" => $value->nama_dagang,
                "qty" => $value->stock_qty,
                "satuan" => $value->unit,
                "description" => $value->keterangan,
                "code" => $value->kode_obat
            );

            if ( $run_check_medicine->num_rows() > 0 ){
                
                continue;
            }


            $this->db->insert("master_medicine",$array_insert);
        }
    }

}

?>