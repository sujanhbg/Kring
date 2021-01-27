
   <div class="w3-card kdt">
    <div class="w3-padding w3-row datatitle">
        <div class="w3-col s12 m3"><a href="{baseurl}/user/new"><button class="newbtn btng">Add New user</button></a></div>
        <div class="w3-col s12 m9 w3-hide-small"><b>users</b></div>

    </div>
    <div class="w3-padding w3-row">
        <div class="w3-col s3">
            <select class="datacounterSelect" onchange="loadurl('{baseurl}/user/setuserdisplayrow/?userdisplayrow=' + this.value, 'tabledata');">
                <?php
                foreach ([2, 5, 10, 15, 20, 30, 50, 100, 200, 500] as $value) {
                    if ($value == $_SESSION['userdisplayrow']) {
                        echo "<option value=\"{$value}\" selected=\"selected\">{$value}</option>";
                    } else {
                        echo "<option value=\"{$value}\">{$value}</option>";
                    }
                }
                ?>

            </select>
        </div>
        <div class="w3-col s9" style="text-align: right;">
            <input type="search" id="searchuser" placeholder="Search your data here...."
                   onchange="loadurl('{baseurl}/user/userdata/?keyw=' + this.value, 'tabledata');"
                   onkeyup="this.onchange();"
                   onpaste="this.onchange();"
                   oncut="this.onchange();"
                   oninput="this.onchange();" >
        </div>
    </div>
    <div id="tabledata">
        <?php
        include('userdata.php');
        ?>
    </div>
</div>

