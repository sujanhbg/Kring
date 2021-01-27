<?php

use kring\core\Controller;

class Home extends Controller {

    private $model;
    public $adminarea;

    function __construct() {
        parent::__construct();
        $this->adminarea = 1;
        $this->model = $this->loadmodel('home');
    }

    function index($pr) {
        if (!isset($_SESSION['theme'])) {
            $_SESSION['theme'] = "colorl";
        }
        $data['title'] = "Admin Dashboard";
        $data['var'] = "Variable";
        $data['totaluser'] = $this->model->usercount();
        $this->tg('home/dashboard.html', $data);
    }

}
