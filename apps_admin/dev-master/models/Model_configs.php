<?php

use kring\database AS db;
use kring\utilities\comm;

class Model_configs {

    function __construct() {
        
    }

    function comm() {
        return new comm();
    }

    function dbal() {
        return new db\dbal();
    }

    function getconfigsHeader() {
        return ['ID', 'name', 'value'];
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

        $disprow = isset($_SESSION['configsdisplayrow']) ? $_SESSION['configsdisplayrow'] : 10;

        $displayfrom = ($pageno * $disprow) - $disprow;

        $ret = "SELECT * FROM configs " . $wherestr . $shortby . " LIMIT  " . $displayfrom . "," . $disprow;

        return $ret;
    }

    function getconfigsData() {
        $page = isset($_GET['page']) ? $_GET['page'] : 0;
        return $this->dbal()->query($this->get_query());
    }

    function get_totalconfigs() {
        return $this->dbal()->get_count("configs");
    }

    function configsViewdata() {
        return $this->dbal()->query("SELECT 
				`ID`,
				`name`,
				`value`
                                FROM configs
                                WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }

    function configsValidationRules() {
        return [
            'name' => 'required|min_len,1',
            'value' => 'required|min_len,1'
        ];
    }

    function configsValidationMessage() {
        return [
            'ID' => ['required' => 'ID  is required.', 'min_len' => 'Invalid ID'],
            'name' => ['required' => 'Name  is required.', 'min_len' => 'Invalid name'],
            'value' => ['required' => 'Value  is required.', 'min_len' => 'Invalid value']
        ];
    }

    function configsFilterRules() {
        return [
            'ID' => 'trim|sanitize_string|basic_tags',
            'name' => 'trim|sanitize_string|basic_tags',
            'value' => 'trim|sanitize_string|basic_tags'
        ];
    }

    function configs_dbvalid($data) {
        $cond = "SELECT ID FROM configs WHERE ";
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

    function configsnew__record_create() {
        $gump = new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->configsValidationRules());
        $gump->filter_rules($this->configsFilterRules());
        $gump->set_fields_error_messages($this->configsValidationMessage());
        $validated_data = $gump->run($_POST);
        $dbvalidation = null;
        //if($this->check_exits("students", "title={$validated_data['title']}")){$dbvalidation.="Data Already Exits";}
        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            if ($dbvalidation == null) {
                //$return= $validated_data['cellnumber'];
                $insertsql = "INSERT INTO  `configs` (
        `ID`,
`name`,
`value`)VALUES(NULL,
'{$validated_data['name']}',
'{$validated_data['value']}');";

                if ($this->dbal()->query_exc($insertsql)) {
                    $return = 1;
                } else {
                    $return = "<span class=\"validerror\">"
                            . "We are Sorry; We can not record your Input to our Database Server</span>";
                }
            } else {
                $return = "<span class=\"validerror\">$dbvalidation</span>";
            }
        }
        return $return;
    }

    function get_configsEditData() {
        return $this->dbal()->query("SELECT * FROM configs WHERE `ID`='{$this->comm()->rqstr('ID')}' LIMIT 1");
    }

    function configsedited_data_save() {
        $gump = new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->configsValidationRules());
        $gump->filter_rules($this->configsFilterRules());
        $gump->set_fields_error_messages($this->configsValidationMessage());
        $validated_data = $gump->run($_POST);

        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            $dbvalidation = true; //$this->configs_dbvalid(['email' => $validated_data['email'], 'cell' => $validated_data['cell']]);
            if ($dbvalidation == true) {
                //$return= $validated_data['cellnumber'];
                $editsql = "UPDATE  configs SET 
				`name` =  '{$validated_data['name']}',
				`value` =  '{$validated_data['value']}' WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1";

                if ($this->dbal()->update_database($editsql)) {
                    $return = 1;
                } else {
                    $return = "<span class=\"validerror\">"
                            . "We are Sorry; We can not save your update</span>";
                }
            } else {
                $return = "<span class=\"validerror\">Data Exists!</span>";
            }
        }
        return $return;
    }

    function configsDeleteSql() {
        return $this->dbal()->query_exc("UPDATE  configs SET `deleted` =  '1'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }

    function configsRestoreSql() {
        return $this->dbal()->query_exc("UPDATE  configs SET `deleted` =  '0'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }

}
