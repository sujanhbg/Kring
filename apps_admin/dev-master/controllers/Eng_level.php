<?php

use kring\core\Controller;

class Eng_level extends Controller {

    public $adminarea;

    function __construct() {
        parent::__construct();
        $this->adminarea = 0;
    }

    function model() {
        return $this->loadmodel('eng_level');
    }

    function index() {
        $data['title'] = "All eng_level";
        $data['headers'] = $this->model()->geteng_levelHeader();
        $data['eng_leveldata'] = $this->model()->geteng_levelData();
        $data['pagination'] = $this->get_pagi();
        if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
            $this->lv('eng_level/eng_levelbody', $data);
        } else {
            $this->tg('home/dashboard.html', $data);
        }
    }

    function eng_leveldata() {
        $data['headers'] = $this->model()->geteng_levelHeader();
        $data['eng_leveldata'] = $this->model()->geteng_levelData();
        $data['pagination'] = $this->get_pagi();
        $this->lv('eng_level/eng_leveldata', $data);
    }

    function seteng_leveldisplayrow() {
        if (isset($_GET['eng_leveldisplayrow'])) {
            $_SESSION['eng_leveldisplayrow'] = $_GET['eng_leveldisplayrow'];
        } else {
            $_SESSION['eng_leveldisplayrow'] = 10;
        }
        $this->eng_leveldata();
    }

    function get_pagi() {
        $pagi = new \kring\database\pagi();
        $pagi->url = ["type" => "js", "url" => "{$this->baseurl()}/eng_level/eng_leveldata/?page=@pg", "divid" => "tabledata"];
        $pagi->totalpage = $this->model()->get_totaleng_level();
        $pagi->displayrow = isset($_SESSION['eng_leveldisplayrow']) ? $_SESSION['eng_leveldisplayrow'] : 10;
        $pagi->currentpage = isset($_GET['page']) ? $_GET['page'] : 1;
        $pagi->fieldname = "ID";
        $pagi->itemname = "eng_levels";
        return $pagi->pagi();
    }

    function eng_level_CheckValid() {

        $gump = new \GUMP();
        $gump->set_fields_error_messages($this->model()->eng_levelValidationMessage());
        $data = array($_REQUEST['fname'] => $_REQUEST['fval']);
        $validated = $gump->is_valid($data, array_intersect_key($this->model()->eng_levelValidationRules(), array_flip(array($_REQUEST['fname']))));
        $dbvalid = $this->model()->eng_level_dbvalid([$_REQUEST['fname'] => $_REQUEST['fval']]);

        if ($validated === true) {
            if ($_REQUEST['fname'] == "email" && $dbvalid == false) {
                $return = "<span style='color:red'><i class='fa fa-times' aria-hidden='true'></i>"
                        . " {$_REQUEST['fval']} already exists</span>";
            } else {
                $return = "<span style='color:green'><i class='fa fa-check-square' aria-hidden='true'></i>"
                        . " Valid!</span>";
            }
        } else {

            $return = "<span style='color:red'><i class='fa fa-times' aria-hidden='true'></i> ";
            $return .= $validated[0] . "</span>";
        }
        echo $return;
    }

    function new() {
        $data['title'] = "Admin Dashboard";

        if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
            $data['title'] = "Edit eng_levels_content";
            $this->tg('eng_level/new', $data);
        } else {
            $this->tg('home/dashboard.html', $data);
        }
    }

    function newsave() {
        $data = $this->model()->eng_levelnew__record_create();
        echo $data;
    }

    function edit() {
        $data['title'] = "Admin Dashboard";

        if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
            $data['title'] = "Edit eng_levels_content";
            $data['eng_levelEditData'] = $this->model()->get_eng_levelEditData();
            $this->tg('eng_level/edit', $data);
        } else {
            $this->tg('home/dashboard.html', $data);
        }
    }

    function editsave() {
        $data = $this->model()->eng_leveledited_data_save();
        echo $data;
    }

    function view($pr) {
        if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
            $data['eng_leveldata'] = $this->model()->eng_levelViewdata($pr[2]);
            $data['title'] = "View eng_level " . $pr[2];
            $this->tg('eng_level/view', $data);
        } else {
            $data['title'] = "Admin Dashboard";
            $this->tg('home/dashboard.html', $data);
        }
    }

    function eng_level_delete() {
        echo "";
        echo <<<EOTEE
        <div class="w3-large">
            <h1>Are you Sure?</h1>
    <a href="javascript:void();" onclick="loadurl('?app=eng_level&opt=eng_level_delete_confirm&ID={$this->model()->comm()->rqstr('ID')}','mainbody');document.getElementById('id01').style.display='none';" class="w3-btn w3-red">Yes Delete</a>

        <a href="javascript:void();" onclick="document.getElementById('id01').style.display='none';" class="w3-btn w3-green">No! Go Back</a>

    </div>
EOTEE;
    }

    function eng_level_delete_confirm() {
        $this->model()->eng_levelDelete();
        echo "<script>window.location.reload();</script>";
    }

}
