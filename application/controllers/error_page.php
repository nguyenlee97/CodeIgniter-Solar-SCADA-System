<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error_page extends CI_Controller {

    public function __construct(){
        parent::__construct();
        session_start();
        $this->load->helper('html');
        $this->load->helper('url');
        $this->load->model('Mglobal');
    }

	public function page_not_found()
	{
        $data_view['template'] = "errors/html/error_404";
        $data_view['title'] = "Error";
        $data_view['data'] = NULL;

        $this->load->view('errors/html/error_404');
	}

}
