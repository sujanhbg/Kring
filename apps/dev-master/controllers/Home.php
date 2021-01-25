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
        $data['title'] = "Admin Dashboard";
        $data['var'] = "Variable";
        $this->tg('home/dashboard.html', $data);
    }

}
