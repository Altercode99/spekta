<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dhtmlx\Connector\ComboConnector;

class HomeController extends Erp_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('HrModel');
        $this->HrModel->myConstruct('hr');

        $this->auth->isAuth();
    }


    public function getNews()
    {
        $template = $this->load->view('html/home/news', null, true);
        response(['status' => 'success', 'template' => $template]);
    }
}