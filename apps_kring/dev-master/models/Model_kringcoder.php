<?php

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

use kring\core\Kring;

class Model_kringcoder {

    function kring() {
        return new Kring();
    }

    function conn() {
        $kring = new Kring();
        return new \mysqli(
                $kring->dbconf('host'),
                $kring->dbconf('user'),
                $kring->dbconf('password'),
                $kring->dbconf('database'));
    }

    function get_apps() {
        $app = $this->kring()->getapps();
        return $app;
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

    function get_tables() {
        $sql = "SHOW TABLES FROM {$this->get_current_db()}";
        //var_dump($this->query($sql));
        return $this->query($sql);
    }

    function get_tabledtl($table) {
        $sql = "SHOW COLUMNS FROM {$table}";

        return $this->query($sql);
    }

    function write_controller() {

        $table_name = $_REQUEST['tblnm'];
        $_SESSION['app'] = $table_name;
        $for_fields = $_POST['field'];
        $frmdata = null;
        $fldvar = null;
        $requestvars = null;
        $updatevars = null;
        $validatevars = null;
        $fildvarrtn = null;
        $gumpvalidvar = null;
        $gumpfieldinsertvar = null;
        $gumpfiltervar = null;
        $tableval = null;
        $tableheaders = null;
        $searcglikess = null;
        $gumpfieldinsertvarval = null;
        $gumpmsg = '';
        $output = '';
        $output_models = '';
        $outheader = "[";
        foreach ($for_fields as $field) {
            $fieldnames = ucwords(str_replace("_", " ", "{$field} "));
            $frmdata .= <<<EOT
	&lt;div class="row"&gt;
    	&lt;div class="input-field col s9"&gt;
        	&lt;input  id="$field" name="$field"" type="text" class="validate"&gt;
          	&lt;label for="$field"&gt;$field&lt;/label&gt;
        &lt;/div&gt;
        &lt;div class="input-field col s3"&gt;&lt;/div&gt;
EOT;

            $fldvar .= "
			&#36;$field=stripslashes(&#36;content['$field']);";
            $requestvars .= "
			&#36;$field=&#36;this-&gt;rqstr('$field');";
            $fildvarrtn .= "\n&lt;tr&gt;&lt;td&gt;$field&lt;/td&gt;&lt;td&gt;:&lt;/td&gt;&lt;td&gt; {&#36;content['$field']}&lt;/td&gt;&lt;/tr&gt;";
            $updatevars .= "
				`$field` =  '{&#36;validated_data['{$field}']}',";
            $validatevars .= "
			&#36;valdata=&#36;this->valid(&#36;$field,\"\",\"$field\");";
            $tableval .= "\n{&#36;value['$field']}::";
            $tableheaders .= "\n" . ucwords(str_replace("_", " ", "{$field}::"));
            $gumpvalidvar .= "'$field'  =>  'required|min_len,1',\n";
            $gumpfiltervar .= "'$field'  =>  'trim|sanitize_string|basic_tags',\n";
            $gumpfieldinsertvar .= "`$field`,\n";

            $gumpfieldinsertvarval .= "'{&#36;validated_data['{$field}']}',\n";
            $searcglikess .= " \n             {$field} like '%{&#36;this->filtertxt(&#36;_GET['key'])}%' OR ";
            $gumpmsg .= " \n             '{$field}' => ['required' => '{$fieldnames} is required.','min_len'=>'Invalid {$field}'],";
            $outheader .= "'$field',";
        }
        $outheader = rtrim($outheader, ",") . "];";
        $gumpvalidvar = rtrim($gumpvalidvar, ",\n");
        $gumpfiltervar = rtrim($gumpfiltervar, ",\n");
        $gumpfieldinsertvarval = trim($gumpfieldinsertvarval, ",\n");
        $gumpfieldinsertvar = trim($gumpfieldinsertvar, ",\n");
        $searcglikess = trim($searcglikess, "OR ");
        $gumpmsg = trim($gumpmsg, ",");
        $table_name_formal = ucwords(str_replace("_", " ", $table_name));
        $zzzzx = "";
        $zzzz = "";
        foreach ($for_fields as $field) {
            $zzzz .= "\n\t\t\t\t`$field`,";
        }
        $zzzzx .= trim($zzzz, ",");
        $classname = ucfirst($table_name);
        $sapp = $_SESSION['sapp'];
        $sappname = $_SESSION['sappname'];
        $output .= <<<EOT
use kring\core\Controller;
                /*
                Page.js
                ###{{ baseurl }}/{$table_name}
                page('/{$sappname}/{$table_name}', function () {
                    loadurl('/{$sappname}/?app={$table_name}&opt=index&fd=fd', 'mainbody');
                    document.title = "{$table_name}";
                });
                page('/{$sappname}/{$table_name}/new', function () {
                    loadurl('/{$sappname}/?app={$table_name}&opt=new&fd=fd', 'mainbody');
                    document.title = "Add {$table_name}";
                });

                page('/{$sappname}/{$table_name}/edit/:id', function (ctx) {
                    loadurl('/{$sappname}/?app={$table_name}&opt=edit&fd=fd&ID=' + ctx.params.id, 'mainbody');
                    document.title = "Edit {$table_name}";
                });
                page('/{$sappname}/{$table_name}/delete/:id', function (ctx) {
                    loadurl('/{$sappname}/?app={$table_name}&opt={$table_name}_delete&fd=fd&ID=' + ctx.params.id, 'mainbody');
                    document.title = "Delete {$table_name}";
                });
                
                */
class {$classname} extends Controller {

    public &#36;adminarea;

    function __construct() {
        parent::__construct();
        &#36;this->adminarea = 0;

    }

    function model(){
        return &#36;this->loadmodel('{$table_name}');
    }
EOT;

        $output .= <<<EOT
function index() {
        &#36;data['title'] = "All {$table_name}";
        &#36;data['headers'] = &#36;this->model()->get{$table_name}Header();
        &#36;data['{$table_name}data'] = &#36;this->model()->get{$table_name}Data();
        &#36;data['pagination'] = &#36;this->get_pagi();
        if (isset(&#36;_GET['fd']) && &#36;_GET['fd'] == "fd") {
            &#36;this->lv('{$table_name}/{$table_name}body', &#36;data);
        } else {
            &#36;this->tg('home/dashboard.html', &#36;data);
        }
    }

function {$table_name}data() {
        &#36;data['headers'] = &#36;this->model()->get{$table_name}Header();
        &#36;data['{$table_name}data'] = &#36;this->model()->get{$table_name}Data();
        &#36;data['pagination'] = &#36;this->get_pagi();
        &#36;this->lv('{$table_name}/{$table_name}data', &#36;data);
    }

    function set{$table_name}displayrow() {
        if (isset(&#36;_GET['{$table_name}displayrow'])) {
            &#36;_SESSION['{$table_name}displayrow'] = &#36;_GET['{$table_name}displayrow'];
        } else {
            &#36;_SESSION['{$table_name}displayrow'] = 10;
        }
        &#36;this->{$table_name}data();
    }

    function get_pagi() {
        &#36;pagi = new \kring\database\pagi();
        &#36;pagi->url = ["type" => "js", "url" => "{&#36;this->baseurl()}/{$table_name}/{$table_name}data/?page=@pg", "divid" => "tabledata"];
        &#36;pagi->totalpage = &#36;this->model()->get_total{$table_name}();
        &#36;pagi->displayrow = isset(&#36;_SESSION['{$table_name}displayrow']) ? &#36;_SESSION['{$table_name}displayrow'] : 10;
        &#36;pagi->currentpage = isset(&#36;_GET['page']) ? &#36;_GET['page'] : 1;
        &#36;pagi->fieldname = "ID";
        &#36;pagi->itemname = "{$table_name}s";
        return &#36;pagi->pagi();
    }
    function {$table_name}_CheckValid() {

            &#36;gump = new \GUMP();
            &#36;gump-&gt;set_fields_error_messages(&#36;this-&gt;model()-&gt;{$table_name}ValidationMessage());
            &#36;data = array(&#36;_REQUEST['fname'] =&gt; &#36;_REQUEST['fval']);
            &#36;validated = &#36;gump-&gt;is_valid(&#36;data, array_intersect_key(&#36;this-&gt;model()-&gt;{$table_name}ValidationRules(), array_flip(array(&#36;_REQUEST['fname']))));
            &#36;dbvalid = &#36;this-&gt;model()-&gt;{$table_name}_dbvalid([&#36;_REQUEST['fname'] =&gt; &#36;_REQUEST['fval']]);

            if (&#36;validated === true) {
                if (&#36;_REQUEST['fname'] == "email" && &#36;dbvalid == false) {
                    &#36;return = "&lt;span style='color:red'&gt;&lt;i class='fa fa-times' aria-hidden='true'&gt;&lt;/i&gt;"
                            . " {&#36;_REQUEST['fval']} already exists&lt;/span&gt;";
                } else {
                    &#36;return = "&lt;span style='color:green'&gt;&lt;i class='fa fa-check-square' aria-hidden='true'&gt;&lt;/i&gt;"
                            . " Valid!&lt;/span&gt;";
                }
            } else {

                &#36;return = "&lt;span style='color:red'&gt;&lt;i class='fa fa-times' aria-hidden='true'&gt;&lt;/i&gt; ";
                &#36;return .= &#36;validated[0] . "&lt;/span&gt;";
            }
            echo &#36;return;
        }
    function new() {
        &#36;data['title'] = "Admin Dashboard";

        if (isset(&#36;_GET['fd']) && &#36;_GET['fd'] == "fd") {
            &#36;data['title'] = "Edit {$table_name}s_content";
            &#36;this->tg('{$table_name}/new', &#36;data);
        } else {
            &#36;this->tg('home/dashboard.html', &#36;data);
        }
    }

    function newsave() {
        &#36;data = &#36;this->model()->{$table_name}new__record_create();
        echo &#36;data;
    }

    function edit() {
        &#36;data['title'] = "Admin Dashboard";

        if (isset(&#36;_GET['fd']) && &#36;_GET['fd'] == "fd") {
            &#36;data['title'] = "Edit {$table_name}s_content";
            &#36;data['{$table_name}EditData'] = &#36;this->model()->get_{$table_name}EditData();
            &#36;this->tg('{$table_name}/edit', &#36;data);
        } else {
            &#36;this->tg('home/dashboard.html', &#36;data);
        }
    }

    function editsave() {
        &#36;data = &#36;this->model()->{$table_name}edited_data_save();
        echo &#36;data;
    }

    function view(&#36;pr){
        if (isset(&#36;_GET['fd']) && &#36;_GET['fd'] == "fd") {
        &#36;data['{$table_name}data']= &#36;this->model()->{$table_name}Viewdata(&#36;pr[2]);
        &#36;data['title']="View {$table_name} ".&#36;pr[2];
        &#36;this->tg('{$table_name}/view', &#36;data);
        }else{
        &#36;data['title'] = "Admin Dashboard";
        &#36;this->tg('home/dashboard.html', &#36;data);
        }
    }

EOT;

        $output .= <<<EOT

function {$table_name}_delete() {
        echo "";
        echo &lt;&lt;&lt;EOTEE
        &lt;div class="w3-large">
            &lt;h1>Are you Sure?&lt;/h1>
    &lt;a href="javascript:void();" onclick="loadurl('?app={$table_name}&opt={$table_name}_delete_confirm&ID={&#36;this->model()->comm()->rqstr('ID')}','mainbody');document.getElementById('id01').style.display='none';" class="w3-btn w3-red">Yes Delete&lt;/a>

        &lt;a href="javascript:void();" onclick="document.getElementById('id01').style.display='none';" class="w3-btn w3-green">No! Go Back&lt;/a>

    &lt;/div>
EOTEE;
    }

    function {$table_name}_delete_confirm() {
        &#36;this->model()->{$table_name}DeleteSql();
        echo "&lt;script&gt;window.location.reload();&lt;/script&gt;";
    }

    function {$table_name}_restore() {
        echo "";
        echo &lt;&lt;&lt;EOTEE
        &lt;div class="w3-large">
            &lt;h1>You are goind to restore this! &lt;/h1>
    &lt;a href="javascript:void();" onclick="loadurl('?app=&#36;this->appname&opt={$table_name}_restore_confirm&static_page_ID={&#36;this->comm()->rqstr('static_page_ID')}&ID={&#36;this->comm()->rqstr('ID')}','mainbody');document.getElementById('id01').style.display='none';" class="w3-btn w3-red">Yes Restore&lt;/a>

        &lt;a href="javascript:void();" onclick="document.getElementById('id01').style.display='none';" class="w3-btn w3-green">No! Go Back&lt;/a>

    &lt;/div>
EOTEE;
    }

    function {$table_name}_restore_confirm() {
        &#36;this->mode()->{$table_name}RestoreSql();
        echo "&lt;script&gt;window.location.reload();&lt;/script&gt;";
    }


}


EOT;

        $filecontent = "&lt;?php\n\n" . $output . "\n\n\n?&gt;";
        $output_models = <<<EOT
use kring\database AS db;
use kring\utilities\comm;
    class Model_{$table_name}{

    function __construct() {


    }
    function comm() {
        return new comm();
    }

    function dbal() {
        return new db\dbal();
    }

    function get{$table_name}Header() {
        return {$outheader}
    }

    function get_query() {
        if (isset(&#36;_GET['field'])) {
            if (&#36;_GET['shrt'] == "asc") {
                &#36;shortby = " ORDER BY " . &#36;this->comm()->filtertxt(&#36;_GET['field']) . " ASC";
            } else {
                &#36;shortby = " ORDER BY " . &#36;this->comm()->filtertxt(&#36;_GET['field']) . " DESC";
            }
        } else {
            &#36;shortby = " ORDER BY ID " . " DESC";
        }

        if (!isset(&#36;_GET['page'])) {
            &#36;pageno = 1;
            &#36;pgurl = null;
        } else {
            &#36;pageno = &#36;_GET['page'];
            &#36;pgurl = "&page=&#36;pageno";
        }
        &#36;wherestr = isset(&#36;_REQUEST['keyw']) ? "WHERE title like '%{&#36;this->comm()->get('keyw')}%' " : null;

        &#36;disprow = isset(&#36;_SESSION['{$table_name}displayrow'])?&#36;_SESSION['{$table_name}displayrow']:10;

        &#36;displayfrom = (&#36;pageno * &#36;disprow) - &#36;disprow;

        &#36;ret = "SELECT * FROM {$table_name} " . &#36;wherestr . &#36;shortby . " LIMIT  " . &#36;displayfrom . "," . &#36;disprow;

        return &#36;ret;
    }

    function get{$table_name}Data() {
        &#36;page = isset(&#36;_GET['page']) ? &#36;_GET['page'] : 0;
        return &#36;this->dbal()->query(&#36;this->get_query());
    }

    function get_total{$table_name}() {
        return &#36;this->dbal()->get_count("{$table_name}");
    }


    function {$table_name}Viewdata() {
        return &#36;this->dbal()->query("SELECT {$zzzzx}
                                FROM {$table_name}
                                WHERE `ID`={&#36;this->comm()->rqstr('ID')} LIMIT 1");
   }


    function {$table_name}ValidationRules(){
        return [
        {$gumpvalidvar}
            ];
    }



    function {$table_name}ValidationMessage(){
        return [
        {$gumpmsg}
            ];
            }
    function {$table_name}FilterRules(){
        return [
        {$gumpfiltervar}
            ];
    }

        function {$table_name}_dbvalid(&#36;data) {
        &#36;cond = "SELECT ID FROM {$table_name} WHERE ";
        foreach (&#36;data as &#36;serv => &#36;sdata) {
            &#36;cond .= " " . &#36;serv . "='" . &#36;sdata . "' OR";
        }
        &#36;condi = trim(&#36;cond, "OR");
        if (&#36;this->dbal()->num_of_row(&#36;condi) > 0) {
            return false;
        } else {
            return true;
        }
    }

   function {$table_name}new__record_create()
    {
        &#36;gump =  new GUMP();
        //&#36;_POST = &#36;gump->sanitize(&#36;_POST);
        &#36;gump->validation_rules(&#36;this->{$table_name}ValidationRules());
        &#36;gump->filter_rules(&#36;this->{$table_name}FilterRules());
        &#36;gump->set_fields_error_messages(&#36;this->{$table_name}ValidationMessage());
        &#36;validated_data = &#36;gump->run(&#36;_POST);
        &#36;dbvalidation=null;
        //if(&#36;this->check_exits("students", "title={&#36;validated_data['title']}")){&#36;dbvalidation.="Data Already Exits";}
        &#36;return="";
        if(&#36;validated_data === false) {
            &#36;return= &#36;gump->get_readable_errors(true);
        } else {
            if(&#36;dbvalidation==null){
                //&#36;return= &#36;validated_data['cellnumber'];
        &#36;insertsql="INSERT INTO  `$table_name` (
        {$gumpfieldinsertvar})VALUES({$gumpfieldinsertvarval});";

                if(&#36;this->dbal()->query_exc(&#36;insertsql)){ &#36;return= 1;}else{ &#36;return= "<span class=\"validerror\">"
                    . "We are Sorry; We can not record your Input to our Database Server</span>"; }
                }else{
                      &#36;return= "<span class=\"validerror\">&#36;dbvalidation</span>";
                }

        }
        return &#36;return;

    }
function get_{$table_name}EditData() {
        return &#36;this->dbal()->query("SELECT * FROM {$table_name} WHERE `ID`='{&#36;this->comm()->rqstr('ID')}' LIMIT 1");
    }
    function {$table_name}edited_data_save()
    {
&#36;gump =  new GUMP();
        //&#36;_POST = &#36;gump->sanitize(&#36;_POST);
        &#36;gump->validation_rules(&#36;this->{$table_name}ValidationRules());
        &#36;gump->filter_rules(&#36;this->{$table_name}FilterRules());
        &#36;gump->set_fields_error_messages(&#36;this->{$table_name}ValidationMessage());
        &#36;validated_data = &#36;gump->run(&#36;_POST);

        &#36;return="";
if(&#36;validated_data === false) {
    &#36;return= &#36;gump->get_readable_errors(true);
} else {
        &#36;dbvalidation = true; //&#36;this->{$table_name}_dbvalid(['email' => &#36;validated_data['email'], 'cell' => &#36;validated_data['cell']]);
    if(&#36;dbvalidation==true){
    //&#36;return= &#36;validated_data['cellnumber'];
&#36;editsql="UPDATE  {$table_name} SET $updatevars WHERE `ID`={&#36;this->comm()->rqstr('ID')} LIMIT 1";

        if(&#36;this->dbal()-&gt;update_database(&#36;editsql)){ &#36;return= 1;}else{ &#36;return= "<span class=\"validerror\">"
            . "We are Sorry; We can not save your update</span>"; }
        }else{
              &#36;return= "<span class=\"validerror\">Data Exists!</span>";
        }

}
        return &#36;return;
}

   function {$table_name}DeleteSql(){
                return &#36;this->dbal()->query_exc("UPDATE  {$table_name} SET `deleted` =  '1'  WHERE `ID`={&#36;this->comm()->rqstr('ID')} LIMIT 1");
                }

   function {$table_name}RestoreSql(){
            return &#36;this->dbal()->query_exc("UPDATE  {$table_name} SET `deleted` =  '0'  WHERE `ID`={&#36;this->comm()->rqstr('ID')} LIMIT 1");

   }

            }


EOT;

        return [$filecontent, "&lt;?php\n\n" . $output_models];
    }

    function appmods() {
        $this->kring()->configfile('applications');
        $app["default"] = "app";
        return $app;
    }

    function writeView() {
        $table_name = $_REQUEST['tblnm'];
        $_SESSION['app'] = $table_name;
        $for_fields = $_POST['field'];
        $returnbody = <<<EOTS

   &lt;div class="w3-card kdt"&gt;
    &lt;div class="w3-padding w3-row datatitle"&gt;
        &lt;div class="w3-col s12 m3"&gt;&lt;a href="{baseurl}/{$table_name}/new"&gt;&lt;button class="newbtn btng"&gt;Add New {$table_name}&lt;/button&gt;&lt;/a&gt;&lt;/div&gt;
        &lt;div class="w3-col s12 m9 w3-hide-small"&gt;&lt;b&gt;{$table_name}s&lt;/b&gt;&lt;/div&gt;

    &lt;/div&gt;
    &lt;div class="w3-padding w3-row"&gt;
        &lt;div class="w3-col s3"&gt;
            &lt;select class="datacounterSelect" onchange="loadurl('{baseurl}/{$table_name}/set{$table_name}displayrow/?{$table_name}displayrow=' + this.value, 'tabledata');"&gt;
                &lt;?php
                foreach ([2, 5, 10, 15, 20, 30, 50, 100, 200, 500] as &#36;value) {
                    if (&#36;value == &#36;_SESSION['{$table_name}displayrow']) {
                        echo "&lt;option value=\"{&#36;value}\" selected=\"selected\"&gt;{&#36;value}&lt;/option&gt;";
                    } else {
                        echo "&lt;option value=\"{&#36;value}\"&gt;{&#36;value}&lt;/option&gt;";
                    }
                }
                ?&gt;

            &lt;/select&gt;
        &lt;/div&gt;
        &lt;div class="w3-col s9" style="text-align: right;"&gt;
            &lt;input type="search" id="search{$table_name}" placeholder="Search your data here...."
                   onchange="loadurl('{baseurl}/{$table_name}/{$table_name}data/?keyw=' + this.value, 'tabledata');"
                   onkeyup="this.onchange();"
                   onpaste="this.onchange();"
                   oncut="this.onchange();"
                   oninput="this.onchange();" &gt;
        &lt;/div&gt;
    &lt;/div&gt;
    &lt;div id="tabledata"&gt;
        &lt;?php
        include('{$table_name}data.php');
        ?&gt;
    &lt;/div&gt;
&lt;/div&gt;


EOTS;

        $valuex = "[";
        $tdwidth = "[";
        $returnval = "";
        foreach ($_REQUEST['field'] as $value) {
            $valuex .= "'{$value}',";
            $tdwidth .= "\"{$value}\" =&gt; null,";
            $returnval .= ". \"&lt;td&gt;\" . &#36;{$table_name}['{$value}'] . \"&lt;/td&gt;\"\n";
        }
        $valuex = trim($valuex, ",") . "]";
        $tdwidth = trim($tdwidth, ",") . "]";
        $returnval = trim($returnval);
        $returndata = <<<EOT

   &lt;table class="w3-table-all"&gt;

    &lt;?php
    &#36;ret = "\n\t\t&lt;thead&gt;\n\t\t\t&lt;tr&gt;\n";
    &#36;n = 0;


    foreach (&#36;headers as &#36;value) {
        &#36;valuex = {$valuex};
        &#36;tdwidth = {$tdwidth};
        if (isset(&#36;_GET['field']) && &#36;_GET['field'] == &#36;valuex[&#36;n]) {
            if (&#36;_GET['shrt'] == "asc") {
                &#36;headergenurl = "{&#36;this-&gt;baseurl()}/?app={$table_name}&opt={$table_name}data&fd=fd&shrt=desc&field={&#36;valuex[&#36;n]}";
                &#36;headericons = "&nbsp;&lt;i class=\"fa fa-caret-up\" aria-hidden=\"true\"&gt;&lt;/i&gt;";
            } else {
                &#36;headergenurl = "{&#36;this-&gt;baseurl()}/?app={$table_name}&opt={$table_name}data&fd=fd&shrt=asc&field={&#36;valuex[&#36;n]}";
                &#36;headericons = "&nbsp;&lt;i class=\"fa fa-caret-down\" aria-hidden=\"true\"&gt;&lt;/i&gt;";
            }
        } else {
            &#36;headergenurl = "{&#36;this-&gt;baseurl()}/?app={$table_name}&opt={$table_name}data&fd=fd&shrt=asc&field={&#36;valuex[&#36;n]}";
            &#36;headericons = "&lt;i class=\"fa fa-sort\" aria-hidden=\"true\" style=\"opacity: 0.3;\"&gt;&lt;/i&gt;";
        }

        &#36;ret .= "\t\t\t\t&lt;th class=\"w3-padding d5\" width=\"{&#36;tdwidth[&#36;valuex[&#36;n]]}%\"&gt;\n"
                . "\t\t\t\t\t&lt;a href=\"javascript:void(0);\" "
                . "onclick=\"javascript:loadurl('{&#36;headergenurl}','tabledata');\" title=\"Shorting\"&gt;"
                . ucwords(str_replace("_", " ", &#36;value)) . &#36;headericons . "&lt;/a&gt;"
                . "\n\t\t\t\t&lt;/th&gt;\n";
        &#36;n++;
    }

    &#36;ret .= "\t\t\t\t&lt;th style=\"width:50px;\" class=\"d5\"&gt;Actions&lt;/th&gt;\n";

    &#36;ret .= "\t\t\t&lt;/tr&gt;\n&lt;/thead&gt;\n&lt;tbody&gt;\n";


    echo &#36;ret;



    /*
      foreach (&#36;headers as &#36;hd) {
      &#36;murls=&#36;mrul."/?page=0";
      echo "&lt;td class=\"d5\"&gt;&lt;b&gt;" . &#36;hd . " &lt;i class=\"fa fa-sort\" aria-hidden=\"true\" style=\"opacity: 0.5;\"&gt;&lt;/i&gt;&lt;/b&gt;&lt;/td&gt;";
      }
     * */
    ?&gt;

    &lt;?php
    foreach (&#36;{$table_name}data as &#36;{$table_name}) {
        echo "&lt;tr&gt;"
        {$returnval}
        . "&lt;td&gt;"
        . "&lt;a href=\"{&#36;this-&gt;baseurl()}/{$table_name}/edit/{&#36;{$table_name}['ID']}\" class=\"text-y\"&gt;&lt;i class=\"fa fa-pencil-square-o \" aria-hidden=\"true\"&gt;&lt;/i&gt;&lt;/a&gt; "
        . "&lt;a href=\"{&#36;this-&gt;baseurl()}/{$table_name}/delete/{&#36;{$table_name}['ID']}\" class=\"text-red\"&gt;&lt;i class=\"fa fa-trash \" aria-hidden=\"true\"&gt;&lt;/i&gt;&lt;/a&gt; "
        . "&lt;/td&gt;"
        . "&lt;/tr&gt;";
    }
    ?&gt;

&lt;/table&gt;
&lt;?php
echo &#36;this-&gt;get_pagi();


EOT;
        return [$returnbody, $returndata];
    }

    function get_controller_content($controllername) {
        $controllernameuper = ucfirst($controllername);
        return<<<EOTT
        &lt;?php
        /*
        This Controller is auto Genarated by @kringCoder
        Href# {{ baseurl }}/{$controllername}
        page('/{$_SESSION['sappname']}/{$controllername}', function () {
            loadurl('{{baseurl}}/$controllername/index/fd/fd', 'mainbody');
        });
        
        */
        use kring\core\Controller;
            class {$controllernameuper} extends Controller {

                public &#36;adminarea;

                function __construct() {
                    parent::__construct();
                    &#36;this->adminarea = 0;

                }

                function model(){
                    return &#36;this->loadmodel('{$controllername}');
                }
                function index() {
                &#36;data['title'] = "$controllername";
                if (isset(&#36;_GET['fd']) && &#36;_GET['fd'] == "fd") {
                    &#36;this->lv('{$controllername}/view', &#36;data);
                } else {
                    &#36;this->tg('home/dashboard.html', &#36;data);
                }
            }
                    
                    
        }
EOTT;
    }

    function get_model_content($controllername) {
        $controllernameuper = ucfirst($controllername);
        return<<<EOTT
&lt;?php
/*

*/
use kring\database AS db;
use kring\utilities\comm;
class Model_{$controllername}{

    function __construct() {


    }
    function comm() {
        return new comm();
    }

    function dbal() {
        return new db\dbal();
    }


}
EOTT;
    }

    function get_orgcode($data) {
        return str_replace(
                ['&lt;', '&gt;', '&#36;', '&amp;'],
                ['<', '>', '$', '&'],
                $data);
    }

    function writefile($filename, $filecontent) {
        if (is_file($filename)) {
            echo "File Already Exists! Can not write to file; Please try it with your favorite editor::<b>{$filename}</b>";
        } else {
            $myfile = fopen($filename, "w") or die("Unable to open file!--{$filename}--");
            fwrite($myfile, $this->get_orgcode($filecontent));
            fclose($myfile);
        }
    }

}
