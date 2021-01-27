<?php

use kring\database AS db;
use kring\utilities\comm;

class Model_user {

    function __construct() {
        
    }

    function comm() {
        return new comm();
    }

    function dbal() {
        return new db\dbal();
    }

    function getuserHeader() {
        return ['ID', 'firstname', 'lastname', 'email', 'active', 'cell', 'username'];
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

        $disprow = isset($_SESSION['userdisplayrow']) ? $_SESSION['userdisplayrow'] : 10;

        $displayfrom = ($pageno * $disprow) - $disprow;

        $ret = "SELECT * FROM user " . $wherestr . $shortby . " LIMIT  " . $displayfrom . "," . $disprow;

        return $ret;
    }

    function getuserData() {
        $page = isset($_GET['page']) ? $_GET['page'] : 0;
        return $this->dbal()->query($this->get_query());
    }

    function get_totaluser() {
        return $this->dbal()->get_count("user");
    }

    function userViewdata() {
        return $this->dbal()->query("SELECT 
				`ID`,
				`firstname`,
				`lastname`,
				`email`,
				`active`,
				`cell`,
				`username`
                                FROM user
                                WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }

    function userValidationRules() {
        return [
            'firstname' => 'required|min_len,1',
            'lastname' => 'required|min_len,1',
            'email' => 'required|min_len,1'
        ];
    }

    function userValidationMessage() {
        return [
            'ID' => ['required' => 'ID  is required.', 'min_len' => 'Invalid ID'],
            'firstname' => ['required' => 'Firstname  is required.', 'min_len' => 'Invalid firstname'],
            'lastname' => ['required' => 'Lastname  is required.', 'min_len' => 'Invalid lastname'],
            'email' => ['required' => 'Email  is required.', 'min_len' => 'Invalid email'],
            'active' => ['required' => 'Active  is required.', 'min_len' => 'Invalid active'],
            'cell' => ['required' => 'Cell  is required.', 'min_len' => 'Invalid cell'],
            'username' => ['required' => 'Username  is required.', 'min_len' => 'Invalid username']
        ];
    }

    function userFilterRules() {
        return [
            'ID' => 'trim|sanitize_string|basic_tags',
            'firstname' => 'trim|sanitize_string|basic_tags',
            'lastname' => 'trim|sanitize_string|basic_tags',
            'email' => 'trim|sanitize_string|basic_tags',
            'active' => 'trim|sanitize_string|basic_tags',
            'cell' => 'trim|sanitize_string|basic_tags',
            'username' => 'trim|sanitize_string|basic_tags'
        ];
    }

    function user_dbvalid($data) {
        $cond = "SELECT ID FROM user WHERE ";
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

    function usernew__record_create() {
        $gump = new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->userValidationRules());
        $gump->filter_rules($this->userFilterRules());
        $gump->set_fields_error_messages($this->userValidationMessage());
        $validated_data = $gump->run($_POST);
        $dbvalidation = null;
        //if($this->check_exits("students", "title={$validated_data['title']}")){$dbvalidation.="Data Already Exits";}
        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            if ($dbvalidation == null) {
                //$return= $validated_data['cellnumber'];
                $insertsql = "INSERT INTO  `user` (
        `ID`,
`firstname`,
`lastname`,
`email`)VALUES(NULL,
'{$validated_data['firstname']}',
'{$validated_data['lastname']}',
'{$validated_data['email']}');";

                if ($this->dbal()->query_exc($insertsql)) {
                    $message = (new Swift_Message('BDEnglish4Exam Account created!'))
                            ->setFrom(['sujanc.barty@gmail.com' => 'Sujan C.Barty'])
                            ->setTo([$validated_data['email'] => $validated_data['firstname'] . " " . $validated_data['lastname']])
                            ->setBody('Thank you for register with us. Please keep update to getting more learning material.')
                    ;
                    $result = $this->comm()->swiftmail()->send($message);
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

    function get_userEditData() {
        return $this->dbal()->query("SELECT * FROM user WHERE `ID`='{$this->comm()->rqstr('ID')}' LIMIT 1");
    }

    function useredited_data_save() {
        $gump = new GUMP();
        //$_POST = $gump->sanitize($_POST);
        $gump->validation_rules($this->userValidationRules());
        $gump->filter_rules($this->userFilterRules());
        $gump->set_fields_error_messages($this->userValidationMessage());
        $validated_data = $gump->run($_POST);

        $return = "";
        if ($validated_data === false) {
            $return = $gump->get_readable_errors(true);
        } else {
            $dbvalidation = true; //$this->user_dbvalid(['email' => $validated_data['email'], 'cell' => $validated_data['cell']]);
            if ($dbvalidation == true) {
                //$return= $validated_data['cellnumber'];
                $editsql = "UPDATE  user SET 
				`firstname` =  '{$validated_data['firstname']}',
				`lastname` =  '{$validated_data['lastname']}',
				`email` =  '{$validated_data['email']}'
                                    WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1";

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

    function userDeleteSql() {
        return $this->dbal()->query_exc("UPDATE  user SET `deleted` =  '1'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }

    function userRestoreSql() {
        return $this->dbal()->query_exc("UPDATE  user SET `deleted` =  '0'  WHERE `ID`={$this->comm()->rqstr('ID')} LIMIT 1");
    }

    function sendmail_newacc() {
        
    }

}
