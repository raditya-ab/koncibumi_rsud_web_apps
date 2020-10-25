<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Public_controller extends MX_Controller
{
    protected $data;

    public function __construct(){
        parent::__construct();

        $this->template->set_partial('page-loader','partials/_page-loader.php');
        $this->template->set_partial('header','partials/_header.php');
        $this->template->set_partial('header-mobile','partials/_header-mobile.php');
        $this->template->set_partial('subheader-v1','partials/_subheader/subheader-v1.php');
        $this->template->set_partial('aside','partials/_aside.php');
        // $this->template->set_partial('sidebar','partials/_sidebar.php');
        $this->template->set_partial('quick-panel','partials/_extras/offcanvas/quick-panel.php');
        $this->template->set_partial('quick-user','partials/_extras/offcanvas/quick-user.php');
        $this->template->set_partial('scrolltop','partials/_extras/scrolltop.php');
        $this->template->set_partial('footer','partials/_footer.php');

        $this->template->set_layout('main_without_sidebar');
        $this->template->set_theme('doctor');
        
    }
}