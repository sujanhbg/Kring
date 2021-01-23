<?php

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
}
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
$output .= <<<EOT
class {$table_name} extends Controller {

    public &#36;appname;
    public &#36;adminarea;

    function __construct() {
        parent::__construct();
        &#36;this->appname = get_class(&#36;this);
        &#36;this->adminarea = 0;

    }

EOT;

$output .= <<<EOT
function {$table_name}() {
        &#36;data['title'] = "All {$table_name}";
        &#36;page = &#36;this-&gt;rqstr('page');
        &#36;data['{$table_name}data'] = &#36;this-&gt;mod('{$table_name}','{$_SESSION['app']}')-&gt;get{$table_name}Data();
        &#36;data['pagi'] = &#36;this-&gt;xlib('etc')->paginationqq(10, "{$table_name}", "javascript:void(0)' onclick='loadurls(\"?app={&#36;this-&gt;appname}&opt={$table_name}search&page=@pg&ajx=1\",\"datadiv\");", "WHERE deleted=0", "ID", &#36;page);
        &#36;data['searchurl'] = "{&#36;this-&gt;baseurl()}?app={&#36;this-&gt;appname}&opt={$table_name}search";
        &#36;data['newurl']="?app={&#36;this->appname}&opt={$table_name}create";
        &#36;data['editurl']="?app={&#36;this->appname}&opt={$table_name}edit";
        &#36;data['viewurl']="?app={&#36;this->appname}&opt=view_{$table_name}";
        &#36;data['deleteurl']="?app={&#36;this->appname}&opt={$table_name}_delete";
        &#36;data['appname']=&#36;this-&gt;appname;

        &#36;this-&gt;loadtg('header', &#36;data);
        &#36;this-&gt;loadtg('top', &#36;data);
        &#36;this-&gt;twig('{$table_name}s', &#36;data);
        &#36;this-&gt;loadtg('fotter', &#36;data);
    }

    function {$table_name}search() {
        &#36;page = &#36;this-&gt;rqstr('page');
        &#36;data['pagi'] = &#36;this-&gt;xlib('etc')->paginationqq(10, "{$table_name}", "javascript:void(0)' onclick='loadurls(\"?app={&#36;this-&gt;appname}&opt={$table_name}search&page=@pg&ajx=1\",\"datadiv\");", "WHERE deleted=0", "ID", &#36;page);
        &#36;data['{$table_name}data'] = &#36;this-&gt;mod('{$table_name}','{$_SESSION['app']}')-&gt;get{$table_name}Data();
        &#36;data['appname'] = &#36;this->appname;
        &#36;this-&gt;twig('{$table_name}search', &#36;data);
    }
        
    
function {$table_name}_CheckValid() {

        &#36;gump = &#36;this-&gt;xlib('gump');
        &#36;gump-&gt;set_fields_error_messages(&#36;this-&gt;mod('{$table_name}', '{$table_name}')-&gt;{$table_name}ValidationMessage());
        &#36;data = array(&#36;_REQUEST['fname'] =&gt; &#36;_REQUEST['fval']);
        &#36;validated = &#36;gump-&gt;is_valid(&#36;data, array_intersect_key(&#36;this-&gt;mod('{$table_name}', '{$table_name}')-&gt;{$table_name}ValidationRules(), array_flip(array(&#36;_REQUEST['fname']))));
        &#36;dbvalid = &#36;this-&gt;mod('{$table_name}', '{$table_name}')-&gt;user_dbvalid([&#36;_REQUEST['fname'] =&gt; &#36;_REQUEST['fval']]);

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



function {$table_name}create()
	{
    if(&#36;_SERVER['REQUEST_METHOD'] == 'POST'){
        echo &#36;this-&gt;mod('{$table_name}','{$_SESSION['app']}')->{$table_name}new__record_create();
        
    }else{
        &#36;data['title']="Add {$table_name}";
        &#36;data['acurl']="?app={&#36;this->appname}&opt={$table_name}create";
        &#36;data['get_url']="?app={&#36;this->appname}&opt={$table_name}_CheckValid";
        &#36;data['appname'] = &#36;this->appname;
        &#36;this-&gt;loadtg('header', &#36;data);
        &#36;this-&gt;loadtg('top', &#36;data);
        &#36;this->twig('{$table_name}_new_form', &#36;data);
        &#36;this-&gt;loadtg('fotter', &#36;data);

EOT;

$output .= <<<EOT


    }
	}



function {$table_name}edit()
    {
    if(&#36;_SERVER['REQUEST_METHOD'] == 'POST'){
        echo &#36;this-&gt;mod('{$table_name}','{$_SESSION['app']}')->{$table_name}edited_data_save();
    }else{
        &#36;data['title']="Edit {$table_name} data";
        &#36;data['acurl']="?app={&#36;this->appname}&opt={$table_name}edit";
        &#36;data['get_url']="?app={&#36;this->appname}&opt={$table_name}_CheckValid";
        &#36;data['{$table_name}EditData']=&#36;this->query("SELECT * FROM {$table_name} WHERE `ID`='{&#36;this->rqstr('ID')}' LIMIT 1");
        &#36;data['appname'] = &#36;this->appname;
        &#36;this-&gt;loadtg('header', &#36;data);
        &#36;this-&gt;loadtg('top', &#36;data);
        &#36;this->twig('{$table_name}_edit_form', &#36;data);
        &#36;this-&gt;loadtg('fotter', &#36;data);
EOT;

$output .= <<<EOT

}
        }



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


function view_{$table_name}()
    {
        &#36;data['title'] = "{$table_name} | Management";
        &#36;data['{$table_name}data'] = &#36;this->mod('{$table_name}','{$_SESSION['app']}')->get{$table_name}view();
        &#36;data['appname'] = &#36;this->appname;
        &#36;this->loadtg('header', &#36;data);
        &#36;this->loadtg("top", &#36;data);
        &#36;this->twig("{$table_name}_view", &#36;data);
        &#36;this->loadtg('fotter', &#36;data);
    }



}
////onchange="loadurl('{&#36;this->conf('baseurl')}/?app={&#36;this->appname}&opt=invoiceliveupdate&fid={&#36;invoiceID}&fname=term&fval='+this.value,'termmsg')



EOT;

$filecontent = "&lt;?php\n\n" . $output . "\n\n\n?&gt;";
