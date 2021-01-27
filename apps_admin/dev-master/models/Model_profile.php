<?php

/*
 * Copyright 2021 sjnx.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Description of Model_profile
 *
 * @author sjnx
 */
use kring\database AS db;
use kring\utilities\comm;

class Model_profile {

    function __construct() {
        
    }

    function comm() {
        return new comm();
    }

    function dbal() {
        return new db\dbal();
    }

    function get_mydata() {
        return $this->dbal()->query("SELECT * FROM user WHERE ID='{$_SESSION['UsrID']}' LIMIT 1;");
    }

    function changepass() {
        $oldpass = $this->comm()->post('oldpass');
        $newpass = $this->comm()->post('newpass');
        $renewpass = $this->comm()->post('renewpass');
        $mdopass = substr(md5($oldpass), 8, 23);
        $mdnewpass = substr(md5($newpass), 8, 23);
        $uppercase = preg_match('@[A-Z]@', $newpass);
        $lowercase = preg_match('@[a-z]@', $newpass);
        $number = preg_match('@[0-9]@', $newpass);
        $specialChars = preg_match('@[^\w]@', $newpass);

        $error = null;
        if (!$oldpass || !$newpass || !$renewpass) {
            $error .= "<div class=\"w3-center\">"
                    . "<img src=\"https://i.imgur.com/u9gmK4e.png\" class=\"w3-image\">"
                    . "<h1>Click Click Click, have fun!</h1>"
                    . "</div>";
        } else {

            $error .= $newpass != $renewpass ? "Entered New and Old Passwords are not matched<br>" : null;
            $error .= $oldpass == $newpass ? "Old and new passwords are the same!<br>" : null;
            $error .= $error .= !$this->dbal()->get_single_result("SELECT ID FROM user WHERE ID='{$_SESSION['UsrID']}' AND password='{$mdopass}'") ? "Old Password not matched!<br>" : null;
            $error .= !$uppercase || !$lowercase || !$number || !$specialChars || (strlen($newpass) < 8) ? 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.' : null;
            //echo "SELECT ID FROM user WHERE ID='{$_SESSION['UsrID']}' AND password='{$mdopass}'";
        }
        if (!$error) {
            if ($this->dbal()->update_database("UPDATE `user` SET `password`='{$mdnewpass}' WHERE `ID`={$_SESSION['UsrID']} LIMIT 1")) {
                return 1;
            } else {
                return "Can not Change Password!";
            }
        } else {
            return $error;
        }
    }

}
