<?php

use kring\core\Controller;

class Profile extends Controller {

    public $adminarea;
    public $pagejs;

    function __construct() {
        parent::__construct();
        $this->adminarea = 1;
        $this->pagejs = 1;
    }

    function model() {
        return $this->loadmodel('profile');
    }

    function index($pr) {
        if (isset($_REQUEST['fd'])) {
            $data['title'] = "Profile Controller";
            //$this->tg('home/dashboard.html', $data);
        } else {
            $data['title'] = "Admin Dashboard";
            $data['var'] = "Variable";
            $this->tg('home/dashboard.html', $data);
        }
    }

    function my($pr) {
        if (isset($_REQUEST['fd'])) {
            $data['title'] = "My Profile";
            $data['mydata'] = $this->model()->get_mydata();
            $this->tg('profile/mydata', $data);
        } else {
            $data['title'] = "Admin Dashboard";
            $data['var'] = "Variable";
            $this->tg('home/dashboard.html', $data);
        }
    }

    function changepassword() {
        $data['title'] = "Change Password";
        $this->tg('auth/changpass', $data);
    }

    function change_pass_conf() {
        $this->rendTxt($this->model()->changepass());
    }

}
