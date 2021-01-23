<div class="w3-row-padding">
    <div class="w3-col s12 m4" style="height: 92vh;padding-bottom: 100px;
         overflow: auto;">
         <?php
         echo "<b>{tablename}</b>";
         echo "<form method=\"POST\" action=\"{baseurl}/kringcoder/make_form\" onsubmit=\"return submitForm(this);\">";
         echo "<input type=\"hidden\" name=\"tblnm\" value=\"{tablename}\">";
         foreach ($tbldata as $row) {


             echo "<div style=\"background-color:#a6a6a6\">"
             . "<div  class=\"w3-brown w3-medium\">"
             . " <input type=\"checkbox\" name=\"field[]\" value=\"{$row['Field']}\" class=\"w3-check\" checked/>"
             . "<label>{$row['Field']} <span class=\"fieldinfo\">{$row['Type']}-{$row['Null']}-{$row['Default']}"
             . "</span></label></div>";
             echo "<select name=\"fieldtype[]\" class=\"w3-select w3-border\" onchange=\"loadmoreoption(this.value,'{$row['Field']}','{$row['Field']}');\">"
             . "<option value=\"text\">Text</option>"
             . "<option value=\"none\">None</option>"
             . "<option value=\"textarea\">Textarea</option>"
             . "<option value=\"textarea2\">CK Editor</option>"
             . "<option value=\"yn\">Yes/No</option>"
             . "<option value=\"selectdb\">Select From DB</option>"
             . "<option value=\"autocomplitdataoption\">Autocomplite</option>"
             . "<option value=\"url\">URL</option>"
             . "<option value=\"checkbox\">checkbox</option>"
             . "<option value=\"color\">color</option>"
             . "<option value=\"date\">date</option>"
             . "<option value=\"datetime-local\">datetime-local</option>"
             . "<option value=\"email\">email</option>"
             . "<option value=\"hidden\">hidden</option>"
             . "<option value=\"file\">file</option>"
             . "<option value=\"image\">image</option>"
             . "<option value=\"imageurl\">imageurl</option>"
             . "<option value=\"number\">number</option>"
             . "<option value=\"password\">password</option>"
             . "<option value=\"range\">range</option>"
             . "<option value=\"search\">search</option>"
             . "<option value=\"tel\">tel</option>"
             . "<option value=\"time\">time</option>"
             . "<option value=\"week\">week</option>"
             . "<option value=\"month\">month</option>"
             . "<option value=\"readonly\">Readonly</option>"
             . "</select><br>";
             echo "<select name=\"grids[]\">"
             . "<option value=\"s12\">S-12</option>"
             . "<option value=\"s6\">S-6</option>"
             . "<option value=\"s10\">S-10</option>"
             . "<option value=\"s11\">S-11</option>"
             . "<option value=\"s9\">S-9</option>"
             . "<option value=\"s8\">S-8</option>"
             . "<option value=\"s7\">S-7</option>"
             . "<option value=\"s6\">S-6</option>"
             . "<option value=\"s5\">S-5</option>"
             . "<option value=\"s4\">S-4</option>"
             . "<option value=\"s3\">S-3</option>"
             . "<option value=\"s2\">S-2</option>"
             . "<option value=\"s1\">S-1</option>"
             . "</select>";
             echo "<select name=\"gridm[]\">"
             . "<option value=\"m12\">M-12</option>"
             . "<option value=\"m6\">M-6</option>"
             . "<option value=\"m10\">M-10</option>"
             . "<option value=\"m11\">M-11</option>"
             . "<option value=\"m9\">M-9</option>"
             . "<option value=\"m8\">M-8</option>"
             . "<option value=\"m7\">M-7</option>"
             . "<option value=\"m6\">M-6</option>"
             . "<option value=\"m5\">M-5</option>"
             . "<option value=\"m4\">M-4</option>"
             . "<option value=\"m3\">M-3</option>"
             . "<option value=\"m2\">M-2</option>"
             . "<option value=\"m1\">M-1</option>"
             . "</select>";
             echo "<select name=\"gridl[]\">"
             . "<option value=\"l8\">L-8</option>"
             . "<option value=\"l6\">L-6</option>"
             . "<option value=\"l10\">L-10</option>"
             . "<option value=\"l11\">L-11</option>"
             . "<option value=\"l9\">L-9</option>"
             . "<option value=\"l12\">L-12</option>"
             . "<option value=\"l7\">L-7</option>"
             . "<option value=\"l6\">L-6</option>"
             . "<option value=\"l5\">L-5</option>"
             . "<option value=\"l4\">L-4</option>"
             . "<option value=\"l3\">L-3</option>"
             . "<option value=\"l2\">L-2</option>"
             . "<option value=\"l1\">L-1</option>"
             . "</select>";


             echo "</div>"
             . "<div id=\"{$row['Field']}moreo\"><select name='moreopt[]'><option value='no'></option></select></div>"
             . "<br>";
         }


         echo "
                        <input type=\"checkbox\" name=\"editform\" value=\"Bike\"> Edit From<br>
                        <input type=\"checkbox\" name=\"fordbs\" value=\"Bikes\" checked> For DBS<br>
                        <input type=\"checkbox\" name=\"webi\" value=\"Bikes\" checked> For WebSite<br>
		<input type=\"submit\" id=\"savebtn\" value=\"Make form\" class=\"btn btnr w3-margin\">
		</form>
                ";
         ?>

    </div>
    <div class="w3-col s12 m8 w3-padding" id="formbody" style="height: 92vh;padding-bottom: 100px;border:1px solid #ccc;
         overflow: auto;">&nbsp;</div>
</div>