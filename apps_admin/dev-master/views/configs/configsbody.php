
   <div class="w3-card kdt">
    <div class="w3-padding w3-row datatitle">
        <div class="w3-col s12 m3"><a href="{baseurl}/configs/new"><button class="newbtn btng">Add New configs</button></a></div>
        <div class="w3-col s12 m9 w3-hide-small"><b>configss</b></div>

    </div>
    <div class="w3-padding w3-row">
        <div class="w3-col s3">
            <select class="datacounterSelect" onchange="loadurl('{baseurl}/configs/setconfigsdisplayrow/?configsdisplayrow=' + this.value, 'tabledata');">
                <?php
                foreach ([2, 5, 10, 15, 20, 30, 50, 100, 200, 500] as $value) {
                    if ($value == $_SESSION['configsdisplayrow']) {
                        echo "<option value=\"{$value}\" selected=\"selected\">{$value}</option>";
                    } else {
                        echo "<option value=\"{$value}\">{$value}</option>";
                    }
                }
                ?>

            </select>
        </div>
        <div class="w3-col s9" style="text-align: right;">
            <input type="search" id="searchconfigs" placeholder="Search your data here...."
                   onchange="loadurl('{baseurl}/configs/configsdata/?keyw=' + this.value, 'tabledata');"
                   onkeyup="this.onchange();"
                   onpaste="this.onchange();"
                   oncut="this.onchange();"
                   oninput="this.onchange();" >
        </div>
    </div>
    <div id="tabledata">
        <?php
        include('configsdata.php');
        ?>
    </div>
</div>

