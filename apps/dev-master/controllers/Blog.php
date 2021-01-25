<?php

use kring\core\Controller;

class Blog extends Controller {

    public $adminarea;

    function __construct() {
        parent::__construct();
        $this->adminarea = 0;
    }

    function model() {
        return $this->loadmodel('blog_category');
    }

    function index($pr) {
        $data['title'] = "All blog_category";
        $data['headers'] = $this->model()->getblog_categoryHeader();
        $data['blogdata'] = $this->model()->getblog_categoryData();
        $data['pagination'] = $this->get_pagi();
        if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
            $this->lv('blog_category/blog_categorybody', $data);
        } else {
            $this->tg('home/dashboard.html', $data);
        }
    }

    function blog_categorydata() {
        $data['headers'] = $this->model()->getblog_categoryHeader();
        $data['blog_categorydata'] = $this->model()->getblog_categoryData();
        $data['pagination'] = $this->get_pagi();
        $this->lv('blog_category/blog_categorydata', $data);
    }

    function setblog_categorydisplayrow() {
        if (isset($_GET['blog_categorydisplayrow'])) {
            $_SESSION['blog_categorydisplayrow'] = $_GET['blog_categorydisplayrow'];
        } else {
            $_SESSION['blog_categorydisplayrow'] = 10;
        }
        $this->blog_categorydata();
    }

    function get_pagi() {
        $pagi = new \kring\database\pagi();
        $pagi->url = ["type" => "js", "url" => "{$this->baseurl()}/blog_category/blog_categorydata/?page=@pg", "divid" => "tabledata"];
        $pagi->totalpage = $this->model()->get_totalblog_category();
        $pagi->displayrow = isset($_SESSION['blog_categorydisplayrow']) ? $_SESSION['blog_categorydisplayrow'] : 10;
        $pagi->currentpage = isset($_GET['page']) ? $_GET['page'] : 1;
        $pagi->fieldname = "ID";
        $pagi->itemname = "blog_categorys";
        return $pagi->pagi();
    }

    function new() {
        $data['title'] = "Admin Dashboard";

        if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
            $data['title'] = "Edit blog_categorys_content";
            $data['subforSelectData'] = $this->model()->get_subforSelectData();
            $this->tg('blog_category/new', $data);
        } else {
            $this->tg('home/dashboard.html', $data);
        }
    }

    function newsave() {
        $data = $this->model()->blog_categorynew__record_create();
        echo $data;
    }

    function edit() {
        $data['title'] = "Admin Dashboard";

        if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
            $data['title'] = "Edit blog_categorys_content";
            $data['blog_categoryEditData'] = $this->model()->get_blog_categoryEditData();
            $data['subforSelectData'] = $this->model()->get_subforSelectData();
            $this->tg('blog_category/edit', $data);
        } else {
            $this->tg('home/dashboard.html', $data);
        }
    }

    function editsave() {
        $data = $this->model()->blog_categoryedited_data_save();
        echo $data;
    }

    function view($pr) {
        if (isset($_GET['fd']) && $_GET['fd'] == "fd") {
            $data['blog_categorydata'] = $this->model()->blog_categoryViewdata($pr[2]);
            $data['title'] = "View blog_category " . $pr[2];
            $this->tg('blog_category/view', $data);
        } else {
            $data['title'] = "Admin Dashboard";
            $this->tg('home/dashboard.html', $data);
        }
    }

    function blog_category_delete() {
        echo "";
        echo <<<EOTEE
        <div class="w3-large">
            <h1>Are you Sure?</h1>
    <a href="javascript:void();" onclick="loadurl('?app=$this->appname&opt=blog_category_delete_confirm&ID={$this->rqstr('ID')}','mainbody');document.getElementById('id01').style.display='none';" class="w3-btn w3-red">Yes Delete</a>

        <a href="javascript:void();" onclick="document.getElementById('id01').style.display='none';" class="w3-btn w3-green">No! Go Back</a>

    </div>
EOTEE;
    }

    function blog_category_delete_confirm() {
        $this->update_database($this->mod('blog_category', 'blog_category')->blog_categoryDeleteSql());
        echo "<script>window.location.reload();</script>";
    }

    function blog_category_restore() {
        echo "";
        echo <<<EOTEE
        <div class="w3-large">
            <h1>You are goind to restore this! </h1>
    <a href="javascript:void();" onclick="loadurl('?app=$this->appname&opt=blog_category_restore_confirm&static_page_ID={$this->rqstr('static_page_ID')}&ID={$this->rqstr('ID')}','mainbody');document.getElementById('id01').style.display='none';" class="w3-btn w3-red">Yes Restore</a>

        <a href="javascript:void();" onclick="document.getElementById('id01').style.display='none';" class="w3-btn w3-green">No! Go Back</a>

    </div>
EOTEE;
    }

    function blog_category_restore_confirm() {
        $this->update_database($this->mod('blog_category', 'blog_category')->blog_categoryRestoreSql());
        echo "<script>window.location.reload();</script>";
    }

}
