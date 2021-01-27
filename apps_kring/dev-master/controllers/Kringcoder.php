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

class Kringcoder extends kring\core\Controller {

    public $adminarea;

    function __construct() {
        parent::__construct();
        $this->adminarea = 1;
    }

    function md() {
        return $this->loadmodel('kringcoder');
    }

    function index($pr) {
        $data['title'] = "Kring@PHP";
        $data['var'] = "Variable";
        $data['tablesInDb'] = $this->md()->get_tables();
        $data['sfd'] = "Tables_in_" . $this->md()->get_current_db();
        $data['dbName'] = $this->md()->get_current_db();
        $data['apps'] = $this->md()->get_apps();
        $_SESSION['sapp'] = isset($_SESSION['sapp']) ? $_SESSION['sapp'] : "apps";
        $this->tg('header', $data);
        $this->tg('fotter', $data);
    }

    function set_sess_app($pr) {

        $_SESSION['sapp'] = isset($pr[4]) ? $pr[4] : "apps";
        $_SESSION['sappname'] = isset($pr[5]) ? $pr[5] : "";
        $this->rendTxt($_SESSION['sapp']);
    }

    function showtables($pr) {
        $data['title'] = "Kring@PHP";
        $data['tbldata'] = $this->md()->get_tabledtl($pr[4]);
        $data['tablename'] = $pr[4];

        if (isset($pr[5]) && $pr[5] == "fd") {
            $this->lv('tabledtl', $data);
        } else {
            $this->index($pr);
        }
    }

    function formmaker($pr) {
        $data['title'] = "Kring@PHP";
        $data['tbldata'] = $this->md()->get_tabledtl($pr[4]);
        $data['tablename'] = $pr[4];

        if (isset($pr[5]) && $pr[5] == "fd") {
            $this->lv('form_maker', $data);
        } else {
            $this->index($pr);
        }
    }

    function formoptions() {
        $data['dbName'] = $this->md()->get_current_db();
        switch ($_REQUEST['type']) {
            case "selectdb":
                echo "<select name='{$_REQUEST['fieldnm']}_selectdb' onchange=\"loadurl('{$this->baseurl()}/kringcoder/formoptions/?type=selectopt&dbnm={$data['dbName']}&fieldnm={$_REQUEST['fieldnm']}&tblnm='+this.value,'{$_REQUEST['fieldnm']}selectopt_db')\">";
                echo "<option value=\"\">Select Table</option>";
                foreach ($this->md()->get_tables() as $value) {
                    echo "<option value=\"{$value[0]}\">" . $value[0] . "</option>";
                }
                echo "</select>";
                echo "<span id=\"{$_REQUEST['fieldnm']}selectopt_db\"></span>";
                break;
            case "selectopt":
                //print_r($_REQUEST);
                echo "<select name='{$_REQUEST['fieldnm']}1'>";
                foreach ($this->md()->get_tabledtl($_REQUEST['tblnm']) as $row) {
                    echo "<option value='{$row['Field']}'>{$row['Field']}</option>\n";
                }
                echo "</select>";
                echo "<select name='{$_REQUEST['fieldnm']}2'>";
                foreach ($this->md()->get_tabledtl($_REQUEST['tblnm']) as $row) {
                    echo "<option value='{$row['Field']}'>{$row['Field']}</option>\n";
                }
                echo "</select>";
                break;
            case "textarea2":
                echo "height:<input name=\"{$_REQUEST['fieldnm']}height\" style=\"width:50px;\" type=\"number\" value=\"150\">";

                break;

            case "autocomplitdataoption":
                echo "<select name='{$_REQUEST['fieldnm']}_selectdb' onchange=\"loadurl('{$this->baseurl()}/kringcoder/formoptions/?type=autocomplitdataoptionoption&dbnm={$data['dbName']}&fieldnm={$_REQUEST['fieldnm']}&tblnm='+this.value,'{$_REQUEST['fieldnm']}selectopt_db')\">";
                echo "<option value=\"\">Select Table</option>";
                foreach ($this->md()->get_tables() as $value) {
                    echo "<option value=\"{$value[0]}\">" . $value[0] . "</option>";
                }
                echo "</select>";
                echo "<span id=\"{$_REQUEST['fieldnm']}selectopt_db\"></span>";
                break;
            case "autocomplitdataoptionoption":
                echo "<select name='{$_REQUEST['fieldnm']}1'>";
                foreach ($this->md()->get_tabledtl($_REQUEST['tblnm']) as $row) {
                    echo "<option value='{$row['Field']}'>{$row['Field']}</option>\n";
                }
                echo "</select>";
                break;
            default:
                echo "<select name='moreopt[]'><option value='no'></option></select>";
                break;
        }
    }

    function make_form() {
        if (isset($_POST['fordbs'])) {

            echo "<pre><code  class=\"html\">";
            echo $this->makenewform_for_dbs_twig();
            echo "</code></pre>";
        } else {

            echo "<pre><code>";
            $this->makeautoformform();
            echo "</code></pre>";
            echo "<h1>New Form...........................</h1>";
            echo "<div  style=\"padding:10px;background-color:#fff; color:#000;width:100%;over-flow:auto;\"><pre><code>";
            echo $this->makenewform_for_dbs_twig(1);
            echo "</code></pre></div>";
        }
    }

    function makeautoformform() {
        $table_name = $_REQUEST['tblnm'];
        $for_fields = $_POST['field'];
        $fieldtype = $_POST['fieldtype'];
        $grids = $_POST['grids'];
        $gridm = $_POST['gridm'];
        $gridl = $_POST['gridl'];
        $frmdata = null;
        $frmwid = null;
        $num = 0;
        $outvar = null;
        $fcontent = null;

        foreach ($for_fields as $field) {

            $field_labal = ucwords(str_replace("_", " ", $field));

            if ($fieldtype[$num] == "textarea2") {
                $fcontent = "\"textarea2\"";
            } elseif ($fieldtype[$num] == "textarea") {
                $fcontent = "\"textarea\"";
            } elseif ($fieldtype[$num] == "selectdb") {
                $fcontent = "\"selectdb\",\"{$_REQUEST[$field . '1']}\",\"{$_REQUEST[$field . '2']}\",\"{$_REQUEST[$field . '_selectdb']}\",\"300\",\"WHERE deleted=0\",\"Select {$field}\"";
            } elseif ($fieldtype[$num] == "yn") {
                $fcontent = "\"yn\",[0=>\"None\",1=>\"Other\",2=>\"Other once\"]";
            } elseif ($fieldtype[$num] == "none") {
                $fcontent = "\"none\"";
            } else {
                $fcontent = "\"text\"";
            }
            //-------------------------------------------------------
            $frmwid .= <<<EOT

             '{$field}' => 6,
EOT;

            $frmdata .= <<<EOT

             ["{$field}","*{$field_labal}",{$fcontent}],
EOT;


            $num++;
        }
        $frmdatafinal = rtrim($frmdata, ",");
        $frmwidfinal = rtrim($frmwid, ",");
        echo <<<EOT
   function {$table_name}create()
	{
        if(&#36;_SERVER['REQUEST_METHOD'] == 'POST'){
                &#36;this-&gt;{$table_name}new__record_create();
            }else{
                &#36;data['title']="Add {$table_name}";
                //&#36;this->loadview('forms/{$table_name}_new_form', &#36;data);
                &#36;dataseet=&#36;this->loadlib('autoform');
                &#36dataseet->formwidth = array({$frmwidfinal}
                            );
                &#36dataseet->formdata = "[{$frmdatafinal}]";
                &#36;dataseet->resultdivid="body";
                &#36;dataseet->resulturl="?app=&#36;this->appname&opt={$table_name}";
                echo &#36;dataseet->forms_new(__FUNCTION__."&app={&#36;this->appname}");

            }
	}
   function {$table_name}edit()
        {
        if(&#36;_SERVER['REQUEST_METHOD'] == 'POST'){
                &#36;this-&gt;{$table_name}edited_data_save();
            }else{
                &#36;data['title']="Edit {$table_name}";
                //&#36;this->loadview('forms/{$table_name}_new_form', &#36;data);
                &#36;dataseet=&#36;this->loadlib('autoform');
                 &#36;dataseet->tablename="{$table_name}";
                 &#36dataseet->formwidth = array({$frmwidfinal}
                                );
                 &#36dataseet->formdata = "{$frmdatafinal}";
                 &#36;dataseet->resultdivid="body";
                &#36;dataseet->resulturl="?app=&#36;this->appname&opt={$table_name}";
                echo &#36;dataseet->forms_update(&#36;this->rqstr('ID'),__FUNCTION__."&app={&#36;this->appname}");
            }
        }



EOT;
    }

    function makenewform() {
        $table_name = $_REQUEST['tblnm'];
        $for_fields = $_POST['field'];
        $fieldtype = $_POST['fieldtype'];
        $grids = $_POST['grids'];
        $gridm = $_POST['gridm'];
        $gridl = $_POST['gridl'];
        $frmdata = "";
        $jscontent = null;
        $extdata = null;
        $outvar = null;
        $outvar2 = null;
        $num = 0;
        foreach ($for_fields as $field) {
            $field_labal = ucwords(str_replace("_", " ", $field));
            if (isset($_POST['editform'])) {
                $valuepasee = " value=\"{&#36;{$field}}\" ";
                $valuepasee2 = " {&#36;{$field}} ";
                $add_editfield = "&lt;input type=\"hidden\" name=\"ID\" value=\"{&#36;ID}\"&gt;";
                $add_title = "Edit " . ucwords(str_replace("_", " ", $table_name));
                $imageurlvalue = "{&#36;{$field}}";
            } else {
                $valuepasee = "";
                $valuepasee2 = "";
                $add_editfield = "";
                $add_title = "Add New " . ucwords(str_replace("_", " ", $table_name));
                $imageurlvalue = "https://i.imgur.com/m0dlpHL.png";
            }

            switch ($fieldtype[$num]) {
                case "textarea":
                    $fcontent = "&lt;textarea rows=\"5\" name=\"{$field}\" id=\"{$field}\" class=\"w3-input w3-border\" "
                            . "onchange=\"loadurl(&#36;{$field}validurl,'{$field}msgg');\"&gt;{$valuepasee2}&lt;/textarea&gt;";
                    break;
                case "yn":
                    $fcontent = "&lt;select name=\"{$field}\" id=\"{$field}\" class=\"w3-select w3-border\" "
                            . "onchange=\"loadurl(&#36;{$field}validurl,'{$field}msgg');\"&gt;\n ";
                    if (isset($_POST['editform'])) {
                        $ynselected = "";
                    } else {
                        $ynselected = "";
                    }
                    $fcontent .= "\t\t&lt;option value=\"1\"&gt;Yes, {$field_labal}&lt;/option&gt;\n"
                            . "\t\t&lt;option value=\"0\"&gt;No {$field_labal}&lt;/option&gt;\n";
                    $fcontent .= "\t\t&lt;/select&gt;";
                    break;
                case "textarea2":
                    $fcontent = "&lt;textarea id=\"{$field}_t\" class=\"w3-input w3-border\" name=\"{$field}\">{$valuepasee2}&lt;/textarea&gt;\n";
                    $jscontent .= <<<EOT
                                   &lt;script src="&lt;?php echo &#36;this->conf('themepath'); ?&gt;/ck4/ckeditor.js"&gt;&lt;/script&gt;
                                    &lt;script type="text/javascript"&gt;

                                    CKEDITOR.replace( '{$field}_t',
                                        {
                                            language: 'bn',
                                            uiColor: '#bdc3c7',
                                            height:150,
                                            filebrowserBrowseUrl : '&lt;?php echo &#36;this->baseurl()?&gt;/filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
                                            filebrowserUploadUrl : '&lt;?php echo &#36;this->baseurl()?&gt;/filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
                                            filebrowserImageBrowseUrl : '&lt;?php echo &#36;this->baseurl()?&gt;/filemanager/dialog.php?type=1&editor=ckeditor&fldr=',
                                        });
                                            CKEDITOR.instances.{$field}_t.on('blur', function() {
                                                loadurl(&lt;?php echo &#36;{$field}validurl; ?&gt;,'{$field}msgg');
                                            });
                                         function CKupdate(){
                                        for ( instance in CKEDITOR.instances )
                                            CKEDITOR.instances[instance].updateElement();
                                            }
                                       $( "#savebtn" ).click(function() {
                                        CKupdate();
                                        });
                                       $( "#{$field}_t" ).change(function() {
                                        CKupdate();
                                            loadurl(&lt;?php echo &#36;{$field}validurl; ?&gt;,'{$field}msgg');
                                        });


                            &lt;/script&gt;

EOT;

                    break;
                case "imageurl":
                    $fcontent = <<<EOTT

          &lt;input type="hidden" name="{$field}" id="{$field}" {$valuepasee} onchange=""&gt;
          &lt;img src="{$imageurlvalue}" id="{$field}_preview" class="w3-image" style="max-height:150px;"&gt;&lt;br&gt;
          &lt;a href="../filemanager/dialog.php?type=1&amp;field_id={$field}" class="btn iframe-btn" type="button"&gt;Open Filemanager&lt;/a&gt;
          &lt;span id="{$field}_msg"&gt;&lt;/span&gt;

          &lt;script&gt;
            $('.iframe-btn').fancybox({
                    'width'	: 900,
                    'height'	: 600,
                    'type'		: 'iframe',
                    'autoScale'    	: false
                });
          function responsive_filemanager_callback(field_id){
            var url=jQuery('#'+field_id).val();
            $('#{$field}_preview').attr("src",url);
            $.fancybox.close();
        }
         &lt;/script&gt;&lt;span id="_{$field}_msg"&gt;&lt;/span&gt;&lt;br&gt;


EOTT;
                    break;
                case "selectdb":
                    $fcontent = "&lt;select name=\"{$field}\" id=\"{$field}\" class=\"w3-input w3-border\" "
                            . "onchange=\"loadurl(&#36;{$field}validurl,'{$field}msgg');\"&gt;\n"
                            . "{&#36;{$field}optiondata}"
                            . "&lt;/select&gt;"
                            . "&lt;br&gt;";
                    if (isset($_POST['editform'])) {
                        $extdata .= <<<EOT
&#36;{$field}optiondata="&lt;option value=\"\" &gt;Select {$field_labal}&lt;/option&gt;";
foreach (&#36;this->query('SELECT `ID`,`name` FROM table_name WHERE `deleted`=0') as &#36;valuee) {
        if (&#36;valuee['ID'] == &#36;{$field}) {
         &#36;{$field}optiondata .= "&lt;option style=\"background-color:#008AB8;color:#99D6EB\" value=\"{&#36;valuee['ID']}\" selected>{&#36;valuee['name']}&lt;/option&gt;";
        } else {
         &#36;{$field}optiondata .= "&lt;option value=\"{&#36;valuee['ID']}\" &gt;{&#36;valuee['name']}&lt;/option&gt;";
        }
    }
EOT;
                    } else {
                        $extdata .= <<<EOT
&#36;{$field}optiondata="&lt;option value=\"\" &gt;Select {$field_labal}&lt;/option&gt;";
foreach (&#36;this->query('SELECT `ID`,`name` FROM table_name WHERE `deleted`=0') as &#36;valuee) {

         &#36;{$field}optiondata .= "&lt;option value=\"{&#36;valuee['ID']}\" &gt;{&#36;valuee['name']}&lt;/option&gt;";

    }
EOT;
                    }
                    break;
                default :
                    $fcontent = "&lt;input type=\"{$fieldtype[$num]}\" name=\"{$field}\" id=\"{$field}\" class=\"w3-input w3-border\" {$valuepasee} "
                            . "onchange=\"loadurl(&#36;{$field}validurl,'{$field}msgg');\"&gt";
            }

            if ($fieldtype[$num] == "none") {
                
            } else {
                $frmdata .= <<<EOT
//{$field} ___________________________________________________________________\n
&#36;{$field}validurl="'{&#36;this->conf('baseurl')}/?app={&#36;this->appname}&opt={$table_name}_CheckValid&fname={$field}&fval='+this.value";
echo &lt;&lt;&lt;EQRTTY\n
         {&#36;this->w3_grid_start()}
         &lt;div class="input-field w3-col s12 m2 w3-text-blue label"&gt;
            &lt;label&gt;{&#36;this->{$table_name}_get_mendatory('{$field}')} {$field_labal}:&lt;/label&gt;
         &lt;/div&gt;
         &lt;div class="input-field w3-col s12 m7 l7 w3-text-blue"&gt;
            {$fcontent}
         &lt;/div&gt;
         &lt;div class="w3-col s12 m3 l3 w3-text-blue"  id="{$field}msgg"&gt;
            &amp;nbsp;
         &lt;/div&gt;
         {&#36;this->w3_grid_end()}

EOT;
                $frmdata .= "\nEQRTTY;\n\n\n\n";
            }
            $outvar .= "                &#36;{$field}=stripslashes(&#36;content['{$field}']);\n";

            $num++;
        }


//================================================== if it edit form
        if (isset($_POST['editform'])) {
            echo <<<EOT
&lt;?php
foreach(&#36;this->query("SELECT * FROM {$table_name} WHERE `ID`='{&#36;this->rqstr('ID')}' LIMIT 1") as &#36;content)
		{
{$outvar}
		}

?&gt;
EOT;
            $jscontent .= <<<EOT
&lt;script type="text/javascript"&gt;

function submitthisform(oFormElement) {
        var loader = document.getElementById('loader');
        loader.style.display = "block";
        var xhr = new XMLHttpRequest();
        xhr.onload = function () {
            if (xhr.responseText === '1') {
                snackbar('Save Success');
                loader.style.display = "none";
                location.replace('{{ baseurl }}/{$table_name}');
            } else {
                document.getElementById('modalbody').innerHTML = xhr.responseText;
                openmodal(30, 33);
                loader.style.display = "none";
            }
        };
        xhr.open(oFormElement.method, oFormElement.getAttribute("action"));
        xhr.send(new FormData(oFormElement));

        return false;
    }

&lt;/script&gt;





EOT;
        } else {
            $jscontent .= <<<EOT
&lt;script type="text/javascript"&gt;

function submitthisform(oFormElement) {
        var loader = document.getElementById('loader');
        loader.style.display = "block";
        var xhr = new XMLHttpRequest();
        xhr.onload = function () {
            if (xhr.responseText === '1') {
                snackbar('Save Success');
                loader.style.display = "none";
                location.replace('{{ baseurl }}/{$table_name}');
            } else {
                document.getElementById('modalbody').innerHTML = xhr.responseText;
                openmodal(30, 33);
                loader.style.display = "none";
            }
        };
        xhr.open(oFormElement.method, oFormElement.getAttribute("action"));
        xhr.send(new FormData(oFormElement));

        return false;
    }

});

&lt;/script&gt;





EOT;
        }


//============================================================





        echo <<<EOT
&lt;?php
&#36;validmethod = "{$table_name}_CheckValid";
{$extdata}

echo &lt;&lt;&lt;EQRTTY
   &lt;div class="w3-card-4 w3-light-grey"&gt;

    &lt;div class="w3-container w3-indigo w3-padding"&gt;
        &lt;b class="w3-medium">{$add_title}&lt;/b&gt;
    &lt;/div>

&lt;div class="w3-padding"&gt;

	&lt;form id="form1"&gt;
        {$add_editfield}

EQRTTY;

	$frmdata


echo &lt;&lt;&lt;EQRTTY
        {&#36;this->w3_grid_start()}
        &lt;div class="input-field w3-col s12 m2 l2 w3-text-blue"&gt;&amp;nbsp;&lt;/div&gt;
        &lt;div class="input-field w3-col s12 m7 l7 w3-text-blue"&gt;
            {&#36;this->w3_idbtn("Save Page", "savebtn")}
        &lt;/div&gt;
        &lt;div class="input-field w3-col s12 m3 l3 w3-text-blue"&gt;&amp;nbsp;&lt;/div&gt;
        {&#36;this->w3_grid_end()}



       &lt;/form&gt;
&lt;/div&gt;
&lt;/div&gt;

EQRTTY;

            ?&gt;
            {$jscontent}


EOT;
    }

    function makenewform_for_dbs_twig($sopt = 0) {
        $table_name = $_REQUEST['tblnm'];
        $for_fields = $_POST['field'];
        $fieldtype = $_POST['fieldtype'];

        if ($sopt == 0) {
            $soptstring = "sopt=CheckValid";
        } else {
            $soptstring = "opt={$table_name}_CheckValid";
        }
        $frmdata = "";
        $jscontent = null;
        $extdata = null;
        $outvar = null;
        $outvar2 = null;
        $num = 0;
        $returndataforf = "";
        //print_r($_REQUEST);
        foreach ($for_fields as $field) {
            $field_labal = ucwords(str_replace("_", " ", $field));
            if (isset($_POST['editform'])) {
                $valuepasee = " value=\"{{ {$table_name}E.{$field} }}\" ";
                $valuepasee2 = " {{ {$table_name}E.{$field} }} ";
                $add_editfield = "&lt;input type=\"hidden\" name=\"ID\" value=\"{{ {$table_name}E.ID }}\"&gt;";
                $add_title = "Edit " . ucwords(str_replace("_", " ", $table_name));
                $imageurlvalue = "{{ {$table_name}E.{$field} }}";
            } else {
                $valuepasee = "";
                $valuepasee2 = "";
                $add_editfield = "";
                $add_title = "Add New " . ucwords(str_replace("_", " ", $table_name));
                $imageurlvalue = "https://i.imgur.com/m0dlpHL.png";
            }

//_____________________________________________________The Menual form start
            switch ($fieldtype[$num]) {
                case "textarea":
                    $fcontent = "&lt;textarea rows=\"5\" name=\"{$field}\" id=\"{$field}\" class=\"w3-input w3-border\" "
                            . "onchange=\"loadurl('{{ baseurl }}/?app={$table_name}&{$soptstring}&fname={$field}&fval='+this.value,'{$field}msgg');\"&gt;{$valuepasee2}&lt;/textarea&gt;";
                    break;
                case "yn":
                    $fcontent = "&lt;select name=\"{$field}\" id=\"{$field}\" class=\"w3-select w3-border\" "
                            . "onchange=\"loadurl('{{ baseurl }}/?app={$table_name}&{$soptstring}&fname={$field}&fval='+this.value,'{$field}msgg');\"&gt;\n ";
                    if (isset($_POST['editform'])) {
                        $ynselected = "";
                    } else {
                        $ynselected = "";
                    }
                    $fcontent .= "\t\t&lt;option value=\"1\"&gt;Yes, {$field_labal}&lt;/option&gt;\n"
                            . "\t\t&lt;option value=\"0\"&gt;No {$field_labal}&lt;/option&gt;\n";
                    $fcontent .= "\t\t&lt;/select&gt;";
                    break;
                case "textarea2":
                    $fcontent = "&lt;textarea id=\"{$field}_t\" class=\"w3-input w3-border\" name=\"{$field}\" onchange=\"loadurl('{{ baseurl }}/?app={$table_name}&{$soptstring}&fname={$field}&fval='+this.value,'{$field}msgg');\">{$valuepasee2}&lt;/textarea&gt;\n";
                    $jscontent .= <<<EOT
                                   &lt;script src="{{ theme }}/ck4/ckeditor.js"&gt;&lt;/script&gt;
                                    &lt;script type="text/javascript"&gt;

                                    CKEDITOR.replace( '{$field}_t',
                                        {
                                            language: 'bn',
                                            uiColor: '#bdc3c7',
                                            height:{$_REQUEST[$field . 'height']},
                                            filebrowserBrowseUrl : '{{ baseurl }}/filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
                                            filebrowserUploadUrl : '{{ baseurl }}/filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
                                            filebrowserImageBrowseUrl : '{{ baseurl }}/filemanager/dialog.php?type=1&editor=ckeditor&fldr=',
                                        });
                                            CKEDITOR.instances.{$field}_t.on('blur', function() {

                                            });
                                         function CKupdate(){
                                        for ( instance in CKEDITOR.instances )
                                            CKEDITOR.instances[instance].updateElement();
                                            }
                                       $( "#savebtn" ).click(function() {
                                        CKupdate();
                                        });
                                       $( "#{$field}_t" ).change(function() {
                                        CKupdate();

                                        });


                            &lt;/script&gt;

EOT;

                    break;
                case "imageurl":
                    $fcontent = <<<EOTT

          &lt;input type="hidden" name="{$field}" id="{$field}" {$valuepasee} onchange=""&gt;
          &lt;img src="{$imageurlvalue}" id="{$field}_preview" class="w3-image" style="max-height:250px;"&gt;&lt;br&gt;
          &lt;a href="../filemanager/dialog.php?type=1&amp;field_id={$field}" class="btn iframe-btn" type="button"&gt;Open Filemanager&lt;/a&gt;
          &lt;span id="{$field}_msg"&gt;&lt;/span&gt;



EOTT;
                    break;
                case "selectdb":
                    $fcontent = "&lt;select name=\"{$field}\" id=\"{$field}\" class=\"w3-input w3-border\" "
                            . "onchange=\"loadurl('{{ get_url }}&{$soptstring}&fname=subfor&fval='+this.value','{$field}msgg');\"&gt;\n\n";


                    if (isset($_POST['editform'])) {
                        $fcontent .= <<<EOT

&lt;option value="0" &gt;Select {$field_labal}&lt;/option&gt;
{# Model
    function get_{$field}SelectData(){
        return &#36;this->dbal()->query('SELECT `{$_REQUEST[$field . '1']}`,`{$_REQUEST[$field . '2']}` FROM {$_REQUEST[$field . '_selectdb']} WHERE `deleted`=0');
            }
   Controller::
       &#36;data['{$field}SelectData']=&#36;this->model()->get_{$field}SelectData();
   #}
    {% for {$field}option in  {$field}SelectData %}
        {% if {$field}option.{$_REQUEST[$field . '1']} == {$table_name}E.{$field} %}
         &lt;option style="background-color:#008AB8;color:#99D6EB" value="{{ {$field}option.{$_REQUEST[$field . '1']} }}" selected>{{ {$field}option.{$_REQUEST[$field . '2']} }}&lt;/option&gt;";
        {% else %}
         &lt;option value="{{ {$field}option.{$_REQUEST[$field . '1']} }}" &gt;{{ {$field}option.{$_REQUEST[$field . '2']} }}&lt;/option&gt;
        {% endif %}
    {% endfor %}

EOT;
                    } else {
                        $fcontent .= <<<EOT
{# Model
    function get_{$field}SelectData(){
        return &#36;this->dbal()->query('SELECT `{$_REQUEST[$field . '1']}`,`{$_REQUEST[$field . '2']}` FROM {$_REQUEST[$field . '_selectdb']} WHERE `deleted`=0');
            }
   Controller::
       &#36;data['{$field}SelectData']=&#36;this->model()->get_{$field}SelectData();
   #}
&lt;option value="0" &gt;Select {$field_labal}&lt;/option&gt;
    {% for {$field}option in  {$field}SelectData %}

         &lt;option value="{{ {$field}option.{$_REQUEST[$field . '1']} }}" &gt;{{ {$field}option.{$_REQUEST[$field . '2']} }}&lt;/option&gt;
    {% endfor %}

EOT;
                    }

                    $fcontent .= "\n&lt;/select&gt;"
                            . "&lt;br&gt;";
                    break;
                case "checkbox":
                    $fcontent = "&lt;input type=\"checkbox\" name=\"{$field}\" id=\"{$field}\" class=\"w3-check\" &gt;";
                    $fcontent .= "&lt;label&gt;Something&lt;/label&gt;";

                    break;
                case "autocomplitdataoption":
                    $fcontent = "&lt;input type=\"{$fieldtype[$num]}\" list=\"{$field}datalist\"  name=\"{$field}\" id=\"{$field}\" class=\"w3-input w3-border\" {$valuepasee} "
                            . "onchange=\"loadurl('{{ baseurl }}/?app={$table_name}&{$soptstring}&fname={$field}&fval='+this.value,'{$field}msgg');\"&gt;";
                    $fcontent .= "&lt;datalist id=\"{$field}datalist\"&gt;
{# &#36;dataseet->{$field}dataset=&#36;this->query('SELECT `{$_REQUEST[$field . '1']}` FROM {$_REQUEST[$field . '_selectdb']} WHERE `deleted`=0'); #}
{# &#36;data['{$field}dataset']=&#36;this->query('SELECT `{$_REQUEST[$field . '1']}` FROM {$_REQUEST[$field . '_selectdb']} WHERE `deleted`=0'); #}

                        {% for {$field}data in {$field}dataset %}
                            &lt;option&gt;{{ {$field}data.{$_REQUEST[$field . '1']} }}&lt;/option&gt;
                        {% endfor %}
                    &lt;/datalist&gt;";
                    break;
                default :
                    $fcontent = "&lt;input type=\"{$fieldtype[$num]}\" name=\"{$field}\" id=\"{$field}\" class=\"w3-input w3-border\" {$valuepasee} "
                            . "onchange=\"loadurl('{{ baseurl }}/?app={$table_name}&{$soptstring}&fname={$field}&fval='+this.value,'{$field}msgg');\"&gt;";
                //----------------------------------------------------------------------------------------------------------------------------------
            }

            if ($fieldtype[$num] == "none") {
                
            } else {
                $frmdata .= <<<EOT


{# ----------{$field}------------------------------ #}

&lt;div class="w3-row"&gt;
    &lt;div class="w3-col s12 m2 l2"&gt;
         &lt;div class="input-field  label w3-right-align w3-padding-small"&gt;
            &lt;label&gt;{$field_labal}:&lt;/label&gt;
         &lt;/div&gt;
	&lt;/div&gt;
	&lt;div class="w3-col s12 m10 l10"&gt;
         &lt;div class="input-field  w3-padding-small"&gt;
            {$fcontent}
         &lt;/div&gt;
         &lt;div class=""  id="{$field}msgg"&gt;
            &amp;nbsp;
         &lt;/div&gt;
	&lt;/div&gt;
&lt;/div&gt;


EOT;
            }
            $outvar .= "{% set {$field}={$table_name}E.{$field} %}\n";

            $num++;
        }


//================================================== if it edit form
        if (isset($_REQUEST['webi'])) {
            $jsresultquery = <<<EOT
    if(data==1){
            M.toast({html: 'Completed'});
            location.replace('{{ get_url | raw}}');
            //loadurl("?ajx=1&app=indexpage&opt=persons","mainbody");
          }else{
            &#36;("#msg3rr").html(data);
           }

EOT;
        } else {
            $jsresultquery = <<<EOT
    if(data==1){
            &#36;("#modelmsg3").html("&lt;b style='font-size:48px;'>Saved....</b>");
            loadurl("{{ get_url | raw}}", "tabledata");
            &#36;(".loader").hide();
            document.getElementById('id03').style.display = 'none';
            closemodal2();
          }else{
            &#36;("#modelbody").html(" ");
            &#36;("#modelmsg3").html(data);
            &#36;(".loader").hide();
           }

EOT;
        }
        if (isset($_POST['editform'])) {
            $returndataforf .= <<<EOT
{# Model
    function get_{$table_name}EditData()
        {
            return &#36;this->dbal()->query("SELECT * FROM {$table_name} WHERE `ID`='{&#36;this->rqstr('ID')}' LIMIT 1");
    }
  Controller
    &#36;data['{$table_name}EditData']=&#36;this->model()->get_{$table_name}EditData();
   #}

{% for {$table_name}E in {$table_name}EditData %}


EOT;
            $jscontent .= <<<EOT
{% endfor %}
&lt;script type="text/javascript"&gt;
        function submitthisform(oFormElement) {
                var loader = document.getElementById('loader');
                loader.style.display = "block";
                var xhr = new XMLHttpRequest();
                xhr.onload = function () {
                    if (xhr.responseText === '1') {
                        snackbar('Edit Save Success');
                        loader.style.display = "none";
                        location.replace('{{ baseurl }}/{$table_name}');
                    } else {
                        document.getElementById('modalbody').innerHTML = xhr.responseText;
                        openmodal(30, 33);
                        loader.style.display = "none";
                    }
                };
                xhr.open(oFormElement.method, oFormElement.getAttribute("action"));
                xhr.send(new FormData(oFormElement));

                return false;
            }

&lt;/script&gt;




EOT;
        } else {
            $jscontent .= <<<EOT

&lt;script type="text/javascript"&gt;
        function submitthisform(oFormElement) {
                var loader = document.getElementById('loader');
                loader.style.display = "block";
                var xhr = new XMLHttpRequest();
                xhr.onload = function () {
                    if (xhr.responseText === '1') {
                        snackbar('Save Success');
                        loader.style.display = "none";
                        location.replace('{{ baseurl }}/{$table_name}');
                    } else {
                        document.getElementById('modalbody').innerHTML = xhr.responseText;
                        openmodal(30, 33);
                        loader.style.display = "none";
                    }
                };
                xhr.open(oFormElement.method, oFormElement.getAttribute("action"));
                xhr.send(new FormData(oFormElement));

                return false;
            }

&lt;/script&gt;





EOT;
        }


//============================================================


        $formaction = isset($_POST['editform']) ? "editsave" : "newsave";


        $returndataforf .= <<<EOT
&lt;div class="w3-row"&gt;
    &lt;div class="w3-col s12"&gt;
		&amp;nbsp;
	&lt;/div&gt;
    &lt;div class="w3-col s12"&gt;


    &lt;div class="w3-container w3-border-bottom w3-border-green"&gt;
        &lt;b class="w3-large">{$add_title}&lt;/b&gt;
    &lt;/div>

    &lt;div class="w3-padding"&gt;
	&lt;form  method="POST" action="{{ baseurl }}/{$table_name}/{$formaction}" onsubmit="return submitthisform(this);"&gt;
        {$add_editfield}

	$frmdata


        &lt;div id="msg3rr"&gt; &lt;/div&gt;
        &lt;div class="input-field w3-text-blue w3-center"&gt;
               &lt;button class="btn waves-effect waves-light s-blue" id="savebtn"&gt;
                   &lt;i class="fa fa-floppy-o" aria-hidden="true"&gt;&lt;/i&gt; Save
                &lt;/button&gt;
        &lt;/div&gt;





       &lt;/form&gt;
&lt;/div&gt;

	&lt;/div&gt;
    &lt;div class="w3-col s12"&gt;
		&amp;nbsp;
	&lt;/div&gt;
&lt;/div&gt;


            {$jscontent}


EOT;

        return $returndataforf;
    }

    function makeController($pr) {
        $data['title'] = "Kring@PHP";
        $data['tablename'] = $pr[3];
        $data['sapp'] = $_SESSION['sapp'];
        if (isset($pr[4]) && $pr[4] == "fd") {
            
        } else {
            echo "<pre><code class=\"php\">";
            $this->rendTxt($this->md()->write_controller()[0]);
            echo "</code></pre>";
            $cnu = ucfirst($_REQUEST['tblnm']);
            $filedir = $this->md()->kring()->get_dir() . "/" . $data['sapp'] . "/dev-master/controllers/";
            $this->md()->writefile($filedir . $cnu . ".php", $this->md()->write_controller()[0]);
        }
    }

    function makemodel($pr) {
        $data['title'] = "Kring@PHP";
        $data['tablename'] = $pr[3];
        $data['sapp'] = $_SESSION['sapp'];
        if (isset($pr[4]) && $pr[4] == "fd") {
            
        } else {
            echo "<div class=\"w3-xlarge\">Model</div>";
            echo "<pre><code class=\"php\">";
            echo $this->md()->write_controller()[1];
            echo "</code></pre>";
            $cn = $_REQUEST['tblnm'];
            $filedir = $this->md()->kring()->get_dir() . "/" . $data['sapp'] . "/dev-master/models/";
            //$this->md()->writefile($filedir . $cnu . ".php", $this->md()->write_controller()[1]);
            $this->md()->writefile($filedir . "Model_" . $cn . ".php", $this->md()->write_controller()[1]);
        }
    }

    function makeview($pr) {
        $data['sapp'] = $_SESSION['sapp'];
        $filedir = $this->md()->kring()->get_dir() . "/" . $data['sapp'] . "/dev-master/views/{$_REQUEST['tblnm']}";
        if (is_dir($filedir)) {
            
        } else {
            mkdir($filedir);
        }


        echo "<div class=\"w3-xlarge\">{$_REQUEST['tblnm']}body.php</div>";
        echo "<pre><code class=\"php\">";
        echo $this->md()->writeView()[0];
        echo "</code></pre>";
        $this->md()->writefile($filedir . "/{$_REQUEST['tblnm']}body.php", $this->md()->writeView()[0]);



        echo "<div class=\"w3-xlarge\">{$_REQUEST['tblnm']}data.php</div>";
        echo "<pre><code class=\"php\">";
        echo $this->md()->writeView()[1];
        $this->md()->writefile($filedir . "/{$_REQUEST['tblnm']}data.php", $this->md()->writeView()[1]);
        echo "</code></pre>";
    }

}
