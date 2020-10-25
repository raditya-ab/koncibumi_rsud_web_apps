<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Dashboard extends Public_controller
{
    public function __construct(){
        parent::__construct();

        $this->template->set_layout('main_without_sidebar');
    }

    public function index(){
        $this->template->build('dashboard');
    }
}