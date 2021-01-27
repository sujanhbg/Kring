<?php

class Core extends kring\core\Controller {

    public $adminarea;

    function __construct() {
        parent::__construct();
        $this->adminarea = 1;
    }

    function md() {
        return $this->loadmodel('kringcoder');
    }

    function index($pr) {

        if (isset($pr[4]) && $pr[4] == "fd") {
            $data['title'] = "";
            $data['selectapp'] = $this->md()->appmods();
            $data['sapp'] = $_SESSION['sapp'];
            $this->lv('core/newctrl', $data);
        } else {
            require_once 'Kringcoder.php';
            $kri = new Kringcoder();
            $kri->index($pr);
        }
    }

    function createctrl() {
        $data['title'] = "";
        $cn = $_REQUEST['ctrlname'];
        $cnu = ucfirst($cn);
        $data['ccontent'] = $this->md()->get_controller_content($cn);
        $data['sapp'] = $_SESSION['sapp'];
        $filedir = $this->md()->kring()->get_dir() . "/" . $data['sapp'] . "/dev-master/controllers/";
        $filedir2 = $this->md()->kring()->get_dir() . "/" . $data['sapp'] . "/dev-master/models/";
        $this->md()->writefile($filedir . $cnu . ".php", $data['ccontent']);
        $this->md()->writefile($filedir2 . "Model_" . $cn . ".php", $this->md()->get_model_content($cn));
        echo $filedir;
    }

}
