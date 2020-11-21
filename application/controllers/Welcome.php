<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MX_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function temp_kunjungan(){
		$obj = file_get_contents('php://input');
		$edata = json_decode($obj);

		$data['status'] = "0";
		$data['id_kunjungan'] = rand(1,100);
		$id_kunjungan = $data['id_kunjungan'];
		$order_no = $edata->kode_pesanan;
		$this->db->query("UPDATE order_patient set id_pesanan = '$id_kunjungan' WHERE order_no = '$order_no'");
		echo json_encode($data);
	}
}
