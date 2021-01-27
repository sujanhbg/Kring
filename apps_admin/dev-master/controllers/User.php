<?php

use kring\core\Controller;

/*
  Page.js
  ###{{ baseurl }}/user
  page('/admin/user', function () {
  loadurl('/admin/?app=user&opt=index&fd=fd', 'mainbody');
  document.title = "user";
  });
  page('/admin/user/new', function () {
  loadurl('/admin/?app=user&opt=new&fd=fd', 'mainbody');
  document.title = "Add user";
  });

  page('/admin/user/edit/:id', function (ctx) {
  loadurl('/admin/?app=user&opt=edit&fd=fd&ID=' + ctx.params.id, 'mainbody');
  document.title = "Edit user";
  });
  page('/admin/user/delete/:id', function (ctx) {
  loadurl('/admin/?app=user&opt=user_delete&fd=fd&ID=' + ctx.params.id, 'mainbody');
  document.title = "Delete user";
  });

 */

class User extends Controller {

    public $adminarea;

    function __construct() {
        parent::__construct();
        $this->adminarea = 0;
    }

    function model() {
        return $this->loadmodel('user');
    }

    function index() {
        $data['title'] = "All user";
        $data['headers'] = $this->model()->getuserHeader();
        $data['userdata'] = $this->model()->getuserData();
        $data['pagination'] = $this->get_pagi();
        if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
            $this->lv('user/userbody', $data);
        } else {
            $this->tg('home/dashboard.html', $data);
        }
    }

    function userdata() {
        $data['headers'] = $this->model()->getuserHeader();
        $data['userdata'] = $this->model()->getuserData();
        $data['pagination'] = $this->get_pagi();
        $this->lv('user/userdata', $data);
    }

    function setuserdisplayrow() {
        if (isset($_GET['userdisplayrow'])) {
            $_SESSION['userdisplayrow'] = $_GET['userdisplayrow'];
        } else {
            $_SESSION['userdisplayrow'] = 10;
        }
        $this->userdata();
    }

    function get_pagi() {
        $pagi = new \kring\database\pagi();
        $pagi->url = ["type" => "js", "url" => "{$this->baseurl()}/user/userdata/?page=@pg", "divid" => "tabledata"];
        $pagi->totalpage = $this->model()->get_totaluser();
        $pagi->displayrow = isset($_SESSION['userdisplayrow']) ? $_SESSION['userdisplayrow'] : 10;
        $pagi->currentpage = isset($_GET['page']) ? $_GET['page'] : 1;
        $pagi->fieldname = "ID";
        $pagi->itemname = "users";
        return $pagi->pagi();
    }

    function user_CheckValid() {

        $gump = new \GUMP();
        $gump->set_fields_error_messages($this->model()->userValidationMessage());
        $data = array($_REQUEST['fname'] => $_REQUEST['fval']);
        $validated = $gump->is_valid($data, array_intersect_key($this->model()->userValidationRules(), array_flip(array($_REQUEST['fname']))));
        $dbvalid = $this->model()->user_dbvalid([$_REQUEST['fname'] => $_REQUEST['fval']]);

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
            $data['title'] = "Edit users_content";
            $this->tg('user/new', $data);
        } else {
            $this->tg('home/dashboard.html', $data);
        }
    }

    function newsave() {
        $data = $this->model()->usernew__record_create();
        echo $data;
    }

    function edit() {
        $data['title'] = "Admin Dashboard";

        if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
            $data['title'] = "Edit users_content";
            $data['userEditData'] = $this->model()->get_userEditData();
            $this->tg('user/edit', $data);
        } else {
            $this->tg('home/dashboard.html', $data);
        }
    }

    function editsave() {
        $data = $this->model()->useredited_data_save();
        echo $data;
    }

    function view($pr) {
        if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
            $data['userdata'] = $this->model()->userViewdata($pr[2]);
            $data['title'] = "View user " . $pr[2];
            $this->tg('user/view', $data);
        } else {
            $data['title'] = "Admin Dashboard";
            $this->tg('home/dashboard.html', $data);
        }
    }

    function user_delete() {
        echo "";
        echo <<<EOTEE
        <div class="w3-large">
            <h1>Are you Sure?</h1>
    <a href="javascript:void();" onclick="loadurl('?app=user&opt=user_delete_confirm&ID={$this->model()->comm()->rqstr('ID')}','mainbody');document.getElementById('id01').style.display='none';" class="w3-btn w3-red">Yes Delete</a>

        <a href="javascript:void();" onclick="document.getElementById('id01').style.display='none';" class="w3-btn w3-green">No! Go Back</a>

    </div>
EOTEE;
    }

    function user_delete_confirm() {
        $this->model()->userDeleteSql();
        echo "<script>window.location.reload();</script>";
    }

    function user_restore() {
        echo "";
        echo <<<EOTEE
        <div class="w3-large">
            <h1>You are goind to restore this! </h1>
    <a href="javascript:void();" onclick="loadurl('?app=$this->appname&opt=user_restore_confirm&static_page_ID={$this->comm()->rqstr('static_page_ID')}&ID={$this->comm()->rqstr('ID')}','mainbody');document.getElementById('id01').style.display='none';" class="w3-btn w3-red">Yes Restore</a>

        <a href="javascript:void();" onclick="document.getElementById('id01').style.display='none';" class="w3-btn w3-green">No! Go Back</a>

    </div>
EOTEE;
    }

    function user_restore_confirm() {
        $this->update_database($this->mod('user', 'user')->userRestoreSql());
        echo "<script>window.location.reload();</script>";
    }

}

?>