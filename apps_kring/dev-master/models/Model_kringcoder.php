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

    function conn() {
        $kring = new Kring();
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
            $gumpmsg .= " \n             '{$field}' => ['required' => '{$field} is required.','min_len'=>'Invalid {$field}'],";
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
        $output .= <<<EOT
use kring\core\Controller;
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
        &#36;data['blogdata'] = &#36;this->model()->get{$table_name}Data();
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

    function new() {
        &#36;data['title'] = "Admin Dashboard";

        if (isset(&#36;_GET['fd']) && &#36;_GET['fd'] == "fd") {
            &#36;data['title'] = "Edit {$table_name}s_content";
            &#36;data['subforSelectData'] = &#36;this->model()->get_subforSelectData();
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
            &#36;data['subforSelectData'] = &#36;this->model()->get_subforSelectData();
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
    &lt;a href="javascript:void();" onclick="loadurl('?app=&#36;this->appname&opt={$table_name}_delete_confirm&ID={&#36;this->rqstr('ID')}','mainbody');document.getElementById('id01').style.display='none';" class="w3-btn w3-red">Yes Delete&lt;/a>

        &lt;a href="javascript:void();" onclick="document.getElementById('id01').style.display='none';" class="w3-btn w3-green">No! Go Back&lt;/a>

    &lt;/div>
EOTEE;
    }

    function {$table_name}_delete_confirm() {
        &#36;this->update_database(&#36;this->mod('{$table_name}','{$_SESSION['app']}')->{$table_name}DeleteSql());
        echo "&lt;script&gt;window.location.reload();&lt;/script&gt;";
    }

    function {$table_name}_restore() {
        echo "";
        echo &lt;&lt;&lt;EOTEE
        &lt;div class="w3-large">
            &lt;h1>You are goind to restore this! &lt;/h1>
    &lt;a href="javascript:void();" onclick="loadurl('?app=&#36;this->appname&opt={$table_name}_restore_confirm&static_page_ID={&#36;this->rqstr('static_page_ID')}&ID={&#36;this->rqstr('ID')}','mainbody');document.getElementById('id01').style.display='none';" class="w3-btn w3-red">Yes Restore&lt;/a>

        &lt;a href="javascript:void();" onclick="document.getElementById('id01').style.display='none';" class="w3-btn w3-green">No! Go Back&lt;/a>

    &lt;/div>
EOTEE;
    }

    function {$table_name}_restore_confirm() {
        &#36;this->update_database(&#36;this->mod('{$table_name}','{$_SESSION['app']}')->{$table_name}RestoreSql());
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

        &#36;disprow = &#36;_SESSION['{$table_name}displayrow'];

        &#36;displayfrom = (&#36;pageno * &#36;disprow) - &#36;disprow;

        &#36;ret = "SELECT * FROM {$table_name}s_content " . &#36;wherestr . &#36;shortby . " LIMIT  " . &#36;displayfrom . "," . &#36;disprow;

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
&#36;editsql="UPDATE  {$table_name} SET $updatevars WHERE `ID`={&#36;this->rqstr('ID')} LIMIT 1";

        if(&#36;this->dbal()-&gt;update_database(&#36;editsql)){ &#36;return= 1;}else{ &#36;return= "<span class=\"validerror\">"
            . "We are Sorry; We can not save your update</span>"; }
        }else{
              &#36;return= "<span class=\"validerror\">Data Exists!</span>";
        }

}
        return &#36;return;
}

   function {$table_name}DeleteSql(){
                return "UPDATE  {$table_name} SET `deleted` =  '1'  WHERE `ID`={&#36;this->rqstr('ID')} LIMIT 1";
                }
                
   function {$table_name}RestoreSql(){
            return "UPDATE  {$table_name} SET `deleted` =  '0'  WHERE `ID`={&#36;this->rqstr('ID')} LIMIT 1";
   
   }
            
            }
            
            
EOT;

        return [$filecontent, $output_models];
    }

}
