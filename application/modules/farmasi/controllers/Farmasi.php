<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Farmasi extends Public_controller
{
    public function __construct(){
        parent::__construct();
        $this->load->library('email');
        $this->config->load('config');
    }

    public function index(){
    	$this->load->view("index");
    }
}	

?>