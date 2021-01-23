<?php

use \kring\core;

/*
 * The MIT License
 *
 * Copyright 2021 sjnx.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Description of lib
 *
 * @author sjnx
 */
class lib {

    function baseurl() {
        return !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? "https://" : "http://" . $_SERVER['SERVER_NAME'] . "";
    }

    function includeFileContent($fileName, $data) {
        ob_start();
        if (is_array($data)) {
            extract($data);
        }
        ob_implicit_flush(false);
        include ($fileName);
        return ob_get_clean();
    }

    function lv($filename, $data) {

        if (is_array($data)) {
            $lang['null'] = "None";
            $data = array_merge($data, $lang);
            $keys = null;
            foreach (array_keys($data) as $kaename) {
                $keys .= "{" . "$kaename" . "},";
            }
            $keysearch = explode(",", rtrim($keys, ","));
            $valuetoplce = null;
            foreach (array_values($data) as $keyvalues) {
                if (is_array($keyvalues)) {
                    $valuetoplce .= "None,";
                } else {
                    $valuetoplce .= $keyvalues . ",";
                }
            }
            $valuetoplce22 = explode(",", rtrim($valuetoplce, ","));
            // style and script intrigation
            $global_search = [
                "{baseurl}"
            ];
            $global_paste = [$this->baseurl()];
            if (is_file(__DIR__ . "/lv_{$filename}.php")) {
                $loaderfile = __DIR__ . "/lv_{$filename}.php";
                // echo $filename . "-In system folder<br>";
            } else {

                echo $filename . ".php File not found<br>";
            }

            $themedata = $this->includeFileContent($loaderfile, $data);
            $themedata = str_ireplace($global_search, $global_paste, $themedata);

            // print_r($valuetoplce22);
            echo str_ireplace($keysearch, $valuetoplce22, $themedata);
        } else {
            echo "Error:: Data of this page cannot be initialize";
        }
    }

    function tg($filename, $data) {
        $themepath = __DIR__ . "";
        if (is_file("view_{$filename}.twig")) {
            
        }

        $array = ['baseurl' => $this->baseurl()];
        $loader = new \Twig\Loader\FilesystemLoader($themepath);
        //Deployment mode
        //$twig = new \Twig\Environment($loader, ['cache' => dirname(__DIR__) . "/cache",]);
        //Development Mode
        $twig = new \Twig\Environment($loader, ['debug' => true]);
        echo $twig->render("view_" . $filename . ".twig", array_merge($data, $array));
    }

    function conn() {
        $kring = new core\Kring();
        return new mysqli(
                $kring->dbconf('host'),
                $kring->dbconf('user'),
                $kring->dbconf('password'),
                $kring->dbconf('database'));
    }

    function query($qry) {
        $mysqli = $this->conn();
        $result = $mysqli->query($qry);
        if (!$mysqli->query($qry)) {
            echo ("Error in Query:: <i><u>$qry</u></i> " . $mysqli->error);
        }
        $returnArray = array();
        $i = 0;
        while ($row = $result->fetch_array(MYSQLI_BOTH))
            if ($row)
                $returnArray[$i++] = $row;
        return $returnArray;
    }

    function get_single_result($sql) {
        $mysqli = $this->conn();
        $result = $mysqli->query($sql);
        $value = $result->fetch_array(MYSQLI_NUM);
        return is_array($value) ? $value[0] : "";
    }

    function get_current_db() {
        $sql = "SELECT DATABASE();";
        return $this->get_single_result($sql);
    }

}
