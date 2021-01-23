<?php

namespace kring\database;

use NilPortugues\Sql\QueryBuilder\Builder\MySqlBuilder;
use kring\core;

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

class dbal {

    function qb() {
        return new MySqlBuilder();
    }

    function dbalv() {
        return "Version 1.0.0";
    }

    function conn() {
        $kring = new core\Kring();
        return new \mysqli(
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
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
            if ($row)
                $returnArray[$i++] = $row;
        return $returnArray;
    }

    function query_exc($qry) {

        $mysqli = $this->conn();
        $result = $mysqli->query($qry);
        if (!$result) {
            echo ("Error in Query:: <i><u>$qry</u></i> " . $mysqli->error);
        }
        if ($result) {
            return $mysqli->insert_id;
        } else {
            return false;
        }
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

    function get_count($table, $colmn = "ID", $condition = null) {
        $where = isset($condition) ? "WHERE $condition" : null;
        return $this->query("SELECT COUNT('{$colmn}') as num FROM `{$table}` {$where};")[0]['num'];
        //print_r($return[0]['num']);
    }

    function num_of_row($qry) {
        $mysqli = $this->conn();
        if (isset($qry)) {
            $result = $mysqli->query($qry);
            if (!$result) {
                echo ("Error in Query:: <i><b style=\"text-color:red;\">$qry</b></i> " . $mysqli->error);
            }
            $temp = $result->num_rows;
        } else {
            $temp = null;
        }
        return $temp;
    }

    function update_database($sql) {
        $mysqli = $this->conn();
        $result = $mysqli->query($sql);
        if ($result) {
            return true;
        } else {
            print_r($mysqli->error);
            return FALSE;
        }
    }

}
