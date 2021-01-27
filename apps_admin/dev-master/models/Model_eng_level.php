<?php

use kring\database AS db;
use kring\utilities\comm;

class Model_eng_level {

    function __construct() {
        
    }

    function comm() {
        return new comm();
    }

    function dbal() {
        return new db\dbal();
    }

    function geteng_levelHeader() {
        return ['ID', 'level', 'CEFR_Level', 'level_icon', 'deleted', 'published'];
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

        $disprow = isset($_SESSION['eng_leveldisplayrow']) ? $_SESSION['eng_leveldisplayrow'] : 10;

        $displayfrom = ($pageno * $disprow) - $disprow;

        $ret = "SELECT * FROM eng_level " . $wherestr . $shortby . " LIMIT  " . $displayfrom . "," . $disprow;

        return $ret;
    }

    function geteng_levelData() {
        $page = isset($_GET['page']) ? $_GET['page'] : 0;
        return $this->dbal()->query($this->get_query());
    }

    function get_totaleng_level() {
        return $this->dbal()->get_count("eng_level");
    }

    function eng_levelViewdata() {
        return $this->dbal()->query("SELECT 
				`ID`,
				`level`,
				`level_desc`,
				`CEFR_Level`,
				`level_icon`,
				`deleted`,
				`published`
                                FROM eng_level
                                WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }

    function eng_levelValidationRules() {
        return [
            'level' => 'required|min_len,1',
            'level_desc' => 'required|min_len,1'
        ];
    }

    function eng_levelValidationMessage() {
        return [
            'ID' => ['required' => 'ID  is required.', 'min_len' => 'Invalid ID'],
            'level' => ['required' => 'Level  is required.', 'min_len' => 'Invalid level'],
            'level_desc' => ['required' => 'Level Desc  is required.', 'min_len' => 'Invalid level_desc'],
            'CEFR_Level' => ['required' => 'CEFR Level  is required.', 'min_len' => 'Invalid CEFR_Level'],
            'level_icon' => ['required' => 'Level Icon  is required.', 'min_len' => 'Invalid level_icon'],
            'deleted' => ['required' => 'Deleted  is required.', 'min_len' => 'Invalid deleted'],
            'published' => ['required' => 'Published  is required.', 'min_len' => 'Invalid published']
        ];
    }

    function eng_levelFilterRules() {
        return [
            'ID' => 'trim|sanitize_string|basic_tags',
            'level' => 'trim|sanitize_string|basic_tags',
            'level_desc' => 'trim|sanitize_string|basic_tags',
            'CEFR_Level' => 'trim|sanitize_string|basic_tags',
            'level_icon' => 'trim|sanitize_string|basic_tags',
            'deleted' => 'trim|sanitize_string|basic_tags',
            'published' => 'trim|sanitize_string|basic_tags'
        ];
    }

    function eng_level_dbvalid($data) {
        $cond = "SELECT ID FROM eng_level WHERE ";
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

    function eng_levelnew__record_create() {
        $gump = new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->eng_levelValidationRules());
        $gump->filter_rules($this->eng_levelFilterRules());
        $gump->set_fields_error_messages($this->eng_levelValidationMessage());
        $validated_data = $gump->run($_POST);
        $dbvalidation = null;
        //if($this->check_exits("students", "title={$validated_data['title']}")){$dbvalidation.="Data Already Exits";}
        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            if ($dbvalidation == null) {
                //$return= $validated_data['cellnumber'];
                $insertsql = "INSERT INTO  `eng_level` (
        `ID`,
`level`,
`level_desc`,
`CEFR_Level`,
`published`)VALUES(NULL,
'{$validated_data['level']}',
'{$validated_data['level_desc']}',
'{$validated_data['CEFR_Level']}',
'{$validated_data['published']}');";

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

    function get_eng_levelEditData() {
        return $this->dbal()->query("SELECT * FROM eng_level WHERE `ID`='{$this->comm()->rqstr('ID')}' LIMIT 1");
    }

    function eng_leveledited_data_save() {
        $gump = new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->eng_levelValidationRules());
        $gump->filter_rules($this->eng_levelFilterRules());
        $gump->set_fields_error_messages($this->eng_levelValidationMessage());
        $validated_data = $gump->run($_POST);

        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            $dbvalidation = true; //$this->eng_level_dbvalid(['email' => $validated_data['email'], 'cell' => $validated_data['cell']]);
            if ($dbvalidation == true) {
                //$return= $validated_data['cellnumber'];
                $editsql = "UPDATE  eng_level SET 
				`level` =  '{$validated_data['level']}',
				`level_desc` =  '{$validated_data['level_desc']}',
				`CEFR_Level` =  '{$validated_data['CEFR_Level']}',
				`published` =  '{$validated_data['published']}' WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1";

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

    function eng_levelDelete() {
        return $this->dbal()->query_exc("UPDATE  eng_level SET `deleted` =  '1'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }

    function eng_levelRestoreSql() {
        return $this->dbal()->query_exc("UPDATE  eng_level SET `deleted` =  '0'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }

}
