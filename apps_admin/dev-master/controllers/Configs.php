<?php

use kring\core\Controller;
                /*
                Page.js
                ###{{ baseurl }}/configs
                page('/admin/configs', function () {
                    loadurl('/admin/?app=configs&opt=index&fd=fd', 'mainbody');
                    document.title = "configs";
                });
                page('/admin/configs/new', function () {
                    loadurl('/admin/?app=configs&opt=new&fd=fd', 'mainbody');
                    document.title = "Add configs";
                });

                page('/admin/configs/edit/:id', function (ctx) {
                    loadurl('/admin/?app=configs&opt=edit&fd=fd&ID=' + ctx.params.id, 'mainbody');
                    document.title = "Edit configs";
                });
                page('/admin/configs/delete/:id', function (ctx) {
                    loadurl('/admin/?app=configs&opt=eng_level_delete&fd=fd&ID=' + ctx.params.id, 'mainbody');
                    document.title = "Delete configs";
                });
                
                */
class Configs extends Controller {

    public $adminarea;

    function __construct() {
        parent::__construct();
        $this->adminarea = 0;

    }

    function model(){
        return $this->loadmodel('configs');
    }function index() {
        $data['title'] = "All configs";
        $data['headers'] = $this->model()->getconfigsHeader();
        $data['configsdata'] = $this->model()->getconfigsData();
        $data['pagination'] = $this->get_pagi();
        if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
            $this->lv('configs/configsbody', $data);
        } else {
            $this->tg('home/dashboard.html', $data);
        }
    }

function configsdata() {
        $data['headers'] = $this->model()->getconfigsHeader();
        $data['configsdata'] = $this->model()->getconfigsData();
        $data['pagination'] = $this->get_pagi();
        $this->lv('configs/configsdata', $data);
    }

    function setconfigsdisplayrow() {
        if (isset($_GET['configsdisplayrow'])) {
            $_SESSION['configsdisplayrow'] = $_GET['configsdisplayrow'];
        } else {
            $_SESSION['configsdisplayrow'] = 10;
        }
        $this->configsdata();
    }

    function get_pagi() {
        $pagi = new \kring\database\pagi();
        $pagi->url = ["type" => "js", "url" => "{$this->baseurl()}/configs/configsdata/?page=@pg", "divid" => "tabledata"];
        $pagi->totalpage = $this->model()->get_totalconfigs();
        $pagi->displayrow = isset($_SESSION['configsdisplayrow']) ? $_SESSION['configsdisplayrow'] : 10;
        $pagi->currentpage = isset($_GET['page']) ? $_GET['page'] : 1;
        $pagi->fieldname = "ID";
        $pagi->itemname = "configss";
        return $pagi->pagi();
    }
    function configs_CheckValid() {

            $gump = new \GUMP();
            $gump->set_fields_error_messages($this->model()->configsValidationMessage());
            $data = array($_REQUEST['fname'] => $_REQUEST['fval']);
            $validated = $gump->is_valid($data, array_intersect_key($this->model()->configsValidationRules(), array_flip(array($_REQUEST['fname']))));
            $dbvalid = $this->model()->configs_dbvalid([$_REQUEST['fname'] => $_REQUEST['fval']]);

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
            $data['title'] = "Edit configss_content";
            $this->tg('configs/new', $data);
        } else {
            $this->tg('home/dashboard.html', $data);
        }
    }

    function newsave() {
        $data = $this->model()->configsnew__record_create();
        echo $data;
    }

    function edit() {
        $data['title'] = "Admin Dashboard";

        if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
            $data['title'] = "Edit configss_content";
            $data['configsEditData'] = $this->model()->get_configsEditData();
            $this->tg('configs/edit', $data);
        } else {
            $this->tg('home/dashboard.html', $data);
        }
    }

    function editsave() {
        $data = $this->model()->configsedited_data_save();
        echo $data;
    }

    function view($pr){
        if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
        $data['configsdata']= $this->model()->configsViewdata($pr[2]);
        $data['title']="View configs ".$pr[2];
        $this->tg('configs/view', $data);
        }else{
        $data['title'] = "Admin Dashboard";
        $this->tg('home/dashboard.html', $data);
        }
    }

function configs_delete() {
        echo "";
        echo <<<EOTEE
        <div class="w3-large">
            <h1>Are you Sure?</h1>
    <a href="javascript:void();" onclick="loadurl('?app=configs&opt=configs_delete_confirm&ID={$this->model()->comm()->rqstr('ID')}','mainbody');document.getElementById('id01').style.display='none';" class="w3-btn w3-red">Yes Delete</a>

        <a href="javascript:void();" onclick="document.getElementById('id01').style.display='none';" class="w3-btn w3-green">No! Go Back</a>

    </div>
EOTEE;
    }

    function configs_delete_confirm() {
        $this->update_database($this->mod('configs','configs')->configsDeleteSql());
        echo "<script>window.location.reload();</script>";
    }

    function configs_restore() {
        echo "";
        echo <<<EOTEE
        <div class="w3-large">
            <h1>You are goind to restore this! </h1>
    <a href="javascript:void();" onclick="loadurl('?app=$this->appname&opt=configs_restore_confirm&static_page_ID={$this->comm()->rqstr('static_page_ID')}&ID={$this->comm()->rqstr('ID')}','mainbody');document.getElementById('id01').style.display='none';" class="w3-btn w3-red">Yes Restore</a>

        <a href="javascript:void();" onclick="document.getElementById('id01').style.display='none';" class="w3-btn w3-green">No! Go Back</a>

    </div>
EOTEE;
    }

    function configs_restore_confirm() {
        $this->update_database($this->mod('configs','configs')->configsRestoreSql());
        echo "<script>window.location.reload();</script>";
    }


}




?>