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
}


?>