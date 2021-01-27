<?php

use kring\core\Controller;

class Auth extends Controller {

    private $model;
    public $adminarea;

    function __construct() {
        parent::__construct();
        $this->adminarea = 1;
        $this->model = $this->loadmodel('home');
    }

    function dbal() {
        return new \kring\database\dbal();
    }

    function isloggedin() {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        if ($this->kring()->isloggedin() == true) {
            echo "data: true\n\n";
        } else {
            echo "data: false\n\n";
        }
        flush();
    }

    function checklogindb() {
        $email = trim($_REQUEST['email']);
        $password = substr(md5($_REQUEST['password']), 8, 23);
        if (!$email || !$password) {
            echo "<b style=\"background-color:#660000;color:#ffb3b3;font-size:32px;padding:8px;\">Not permitted to empty e-mail or password!</b><br>";
        } else {
            $sql = "SELECT * FROM user WHERE `email`='$email' AND `password`='$password' LIMIT 1";
            $loginerror = $this->checkerror();
            if ($this->dbal()->num_of_row($sql)) {

                return $this->dbal()->query($sql);
            } else {
                $this->userlogineerrorinsert();
                return false;
            }
        }
    }

    function userlogineerrorinsert() {
        $usernm = trim($_REQUEST['email'], "'");
        $pass = trim($_REQUEST['password'], "'");
        $ip = $_SERVER['REMOTE_ADDR'];
        $otherinfo = "Host- " . gethostbyaddr($ip);
        $sessid = session_id();
        $insert_sql = "INSERT INTO  `user_loginerr` (
			`usernm` ,
			`pass` ,
			`time` ,
			`ip` ,
			`otherinfo`,`sess` )VALUES(
			'$usernm',
			'$pass',
			 NOW(),
			'$ip',
			'$otherinfo','{$sessid}'
			)";
        $this->dbal()->query_exc($insert_sql);
        // AND update_time < date_sub(NOW() - interval 2 minute)
    }

    function checkerror() {
        $usernm = trim($_REQUEST['email'], "'");
        $sql = "SELECT * FROM user_loginerr WHERE `usernm`='$usernm'  AND `time` > date_sub(now(), interval 2 minute)";
        return $this->dbal()->num_of_row($sql);
    }

    function login_history_insert($UID) {
        $insert_sql = "INSERT INTO  `user_login_history` (
			`UID` ,
			`date` ,
			`IP` ,
			`otherdtl`)VALUES(
			'$UID',
			 NOW(),
			'{$_SERVER['REMOTE_ADDR']}',
			'{$_SERVER['HTTP_USER_AGENT']}'
			)";
        $this->dbal()->query_exc($insert_sql);
    }

    function index($pr) {
        $data['title'] = "Admin Login";
        $data['var'] = "Variable";
        $this->tg('auth/login.html', $data);
    }

    function login() {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            if (!$this->kring()->isloggedin()) {
                if ($this->checkerror() > 6) {
                    http_response_code(404);
                } elseif ($this->checklogindb()) {
                    foreach ($this->checklogindb() as $content) {
                        $ID = stripslashes($content['ID']);
                        $firstname = stripslashes($content['firstname']);
                        $lastname = stripslashes($content['lastname']);
                        $password = stripslashes($content['password']);
                        $email = stripslashes($content['email']);
                        $role = stripslashes($content['role']);
                        $active = stripslashes($content['active']);
                        $email = trim($_REQUEST['email']);

                        if ($active == 1) {
                            $_SESSION['UsrID'] = $ID;
                            $_SESSION['UsrMail'] = $email;
                            $_SESSION['UsrName'] = $firstname . " " . $lastname;
                            $_SESSION["UsrRole"] = $role;
                            $_SESSION["OrgName"] = $this->kring()->conf('OrgName');
                            $logindata = ['softname' => 'bdenglis4exam', 'servnm' => $_SERVER['SERVER_NAME'], 'servaddr' => $_SERVER['SERVER_ADDR'], 'orgname' => $this->kring()->conf('OrgName'), 'UsrID' => $_SESSION['UsrID'], 'serv' => json_encode($_SERVER), 'sess' => json_encode($_SESSION)];
                            $ch = curl_init('https://app.kringlab.com/sfr/sfr.php');
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $logindata);
                            curl_exec($ch);
                            curl_close($ch);
                            $this->login_history_insert($ID);
                            echo 1;
                        } else {
                            $this->login_history_insert($ID);
                            echo "<h3 class=\"amber lighten-4 red-text\">Your Account is not Active! Please contact with Support</h3>";
                        }
                    }
                } else {
                    echo "<b style=\"color:red;font-size:32px;\"><i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i> Login Error</b>";
                }
            } else {
                header("Location: {$this->conf('baseurl')}");
            }
        } else {
            http_response_code(404);
        }
    }

    function logout() {
        session_unset();
        session_destroy();
        header("Location: {$this->baseurl()}");
    }

}
