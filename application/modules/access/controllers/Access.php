<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Access extends Public_controller
{
    public function __construct(){
        parent::__construct();
    }

    public function index(){
    	$this->load->view("login");
    }

    public function logout(){
        if ( isset($_SESSION['user_id'])){
            $user_id = $_SESSION['user_id'];
            $this->db->query("UPDATE members set login_status = NULL WHERE id = '$user_id'");
        }
        session_destroy();
        redirect(base_url());
    }
}


?>