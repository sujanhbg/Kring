<?php

/*
  This Controller is auto Genarated by @kringCoder
  Href# {{ baseurl }}/mailer
  page('/admin/mailer', function () {
  loadurl('{{baseurl}}/mailer/index/fd/fd', 'mainbody');
  });

 */

use kring\core\Controller;
use kring\utilities;

class Mailer extends Controller {

    public $adminarea;

    function __construct() {
        parent::__construct();
        $this->adminarea = 0;
    }

    function comm() {
        return new \kring\utilities\comm();
    }

    function model() {
        return $this->loadmodel('mailer');
    }

    function index() {
        $data['title'] = "mailer";
        if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
            $this->lv('mailer/view', $data);
        } else {
            $this->tg('home/dashboard.html', $data);
        }
    }

    function compose() {
        
    }

    function send() {
        
    }

}
