<?php

use kring\core\Controller;

class Home extends Controller {

    private $model;
    public $adminarea;

    function __construct() {
        $this->adminarea = 1;
        $this->model = $this->loadmodel('user');
    }

    function index($pr) {
        $data['title'] = "Sujan@KringFW";
        $data['var'] = "Variable";
        $data['userdata'] = $this->model->user_data();
        $this->tg('home/body.html', $data);
        //$this->lv('tex', $data);
    }

    function user($pr) {
        
    }

}
