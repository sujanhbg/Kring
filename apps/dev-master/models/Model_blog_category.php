<?php

use kring\database AS db;
use kring\utilities\comm;

class Model_blog_category {

    function __construct() {
        
    }

    function comm() {
        return new comm();
    }

    function dbal() {
        return new db\dbal();
    }

    function getblog_categoryHeader() {
        return ['ID', 'subfor', 'category_name', 'deleted'];
    }

    function get_query() {
        if (isset($_GET['field'])) {
            if ($_GET['shrt'] == "asc") {
                $shortby = " ORDER BY " . $this->comm()->filtertxt($_GET['field']) . " ASC";
            } else {
                $shortby = " ORDER BY " . $this->comm()->filtertxt($_GET['field']) . " DESC";
            }
        } else {
            $shortby = " ORDER BY ID " . " DESC";
        }

        if (!isset($_GET['page'])) {
            $pageno = 1;
            $pgurl = null;
        } else {
            $pageno = $_GET['page'];
            $pgurl = "&page=$pageno";
        }
        $wherestr = isset($_REQUEST['keyw']) ? "WHERE title like '%{$this->comm()->get('keyw')}%' " : null;

        $disprow = isset($_SESSION['blog_categorydisplayrow']) ? $_SESSION['blog_categorydisplayrow'] : 10;

        $displayfrom = ($pageno * $disprow) - $disprow;

        $ret = "SELECT * FROM blog_category " . $wherestr . $shortby . " LIMIT  " . $displayfrom . "," . $disprow;

        return $ret;
    }

    function getblog_categoryData() {
        $page = isset($_GET['page']) ? $_GET['page'] : 0;
        return $this->dbal()->query($this->get_query());
    }

    function get_totalblog_category() {
        return $this->dbal()->get_count("blog_category");
    }

    function blog_categoryViewdata() {
        return $this->dbal()->query("SELECT 
				`ID`,
				`subfor`,
				`category_name`,
				`deleted` 
                                FROM blog_category 
                                WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }

    function blog_categoryValidationRules() {
        return [
            'ID' => 'required|min_len,1',
            'subfor' => 'required|min_len,1',
            'category_name' => 'required|min_len,1',
            'deleted' => 'required|min_len,1'
        ];
    }

    function blog_categoryValidationMessage() {
        return [
            'ID' => ['required' => 'ID is required.', 'min_len' => 'Invalid ID'],
            'subfor' => ['required' => 'subfor is required.', 'min_len' => 'Invalid subfor'],
            'category_name' => ['required' => 'category_name is required.', 'min_len' => 'Invalid category_name'],
            'deleted' => ['required' => 'deleted is required.', 'min_len' => 'Invalid deleted']
        ];
    }

    function blog_categoryFilterRules() {
        return [
            'ID' => 'trim|sanitize_string|basic_tags',
            'subfor' => 'trim|sanitize_string|basic_tags',
            'category_name' => 'trim|sanitize_string|basic_tags',
            'deleted' => 'trim|sanitize_string|basic_tags'
        ];
    }

    function blog_category_dbvalid($data) {
        $cond = "SELECT ID FROM blog_category WHERE ";
        foreach ($data as $serv => $sdata) {
            $cond .= " " . $serv . "='" . $sdata . "' OR";
        }
        $condi = trim($cond, "OR");
        if ($this->dbal()->num_of_row($condi) > 0) {
            return false;
        } else {
            return true;
        }
    }

    function blog_categorynew__record_create() {
        $gump = new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->blog_categoryValidationRules());
        $gump->filter_rules($this->blog_categoryFilterRules());
        $gump->set_fields_error_messages($this->blog_categoryValidationMessage());
        $validated_data = $gump->run($_POST);
        $dbvalidation = null;
        //if($this->check_exits("students", "title={$validated_data['title']}")){$dbvalidation.="Data Already Exits";}
        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            if ($dbvalidation == null) {
                //$return= $validated_data['cellnumber'];
                $insertsql = "INSERT INTO  `blog_category` (
        `ID`,
`subfor`,
`category_name`,
`deleted`)VALUES('{$validated_data['ID']}',
'{$validated_data['subfor']}',
'{$validated_data['category_name']}',
'{$validated_data['deleted']}');";

                if ($this->dbal()->query_exc($insertsql)) {
                    $return = 1;
                } else {
                    $return = ""
                            . "We are Sorry; We can not record your Input to our Database Server";
                }
            } else {
                $return = "$dbvalidation";
            }
        }
        return $return;
    }

    function blog_categoryedited_data_save() {
        $gump = new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->blog_categoryValidationRules());
        $gump->filter_rules($this->blog_categoryFilterRules());
        $gump->set_fields_error_messages($this->blog_categoryValidationMessage());
        $validated_data = $gump->run($_POST);

        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            $dbvalidation = true; //$this->blog_category_dbvalid(['email' => $validated_data['email'], 'cell' => $validated_data['cell']]);
            if ($dbvalidation == true) {
                //$return= $validated_data['cellnumber'];
                $editsql = "UPDATE  blog_category SET 
				`ID` =  '{$validated_data['ID']}',
				`subfor` =  '{$validated_data['subfor']}',
				`category_name` =  '{$validated_data['category_name']}',
				`deleted` =  '{$validated_data['deleted']}', WHERE `ID`={$this->rqstr('ID')} LIMIT 1";

                if ($this->dbal()->update_database($editsql)) {
                    $return = 1;
                } else {
                    $return = ""
                            . "We are Sorry; We can not save your update";
                }
            } else {
                $return = "Data Exists!";
            }
        }
        return $return;
    }

    function blog_categoryDeleteSql() {
        return "UPDATE  blog_category SET `deleted` =  '1'  WHERE `ID`={$this->rqstr('ID')} LIMIT 1";
    }

    function blog_categoryRestoreSql() {
        return "UPDATE  blog_category SET `deleted` =  '0'  WHERE `ID`={$this->rqstr('ID')} LIMIT 1";
    }

}
