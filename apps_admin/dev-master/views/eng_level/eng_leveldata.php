<div class="w3-responsive">
    <table class="w3-table-all">

        <?php
        $ret = "
		<thead>
			<tr>
";
        $n = 0;


        foreach ($headers as $value) {
            $valuex = ['ID', 'level', 'level_desc', 'CEFR_Level', 'level_icon', 'deleted', 'published'];
            $tdwidth = ["ID" => null, "level" => null, "level_desc" => null, "CEFR_Level" => null, "level_icon" => null, "deleted" => null, "published" => null];
            if (isset($_GET['field']) && $_GET['field'] == $valuex[$n]) {
                if ($_GET['shrt'] == "asc") {
                    $headergenurl = "{$this->baseurl()}/?app=eng_level&opt=eng_leveldata&fd=fd&shrt=desc&field={$valuex[$n]}";
                    $headericons = " <i class=\"fa fa-caret-up\" aria-hidden=\"true\"></i>";
                } else {
                    $headergenurl = "{$this->baseurl()}/?app=eng_level&opt=eng_leveldata&fd=fd&shrt=asc&field={$valuex[$n]}";
                    $headericons = " <i class=\"fa fa-caret-down\" aria-hidden=\"true\"></i>";
                }
            } else {
                $headergenurl = "{$this->baseurl()}/?app=eng_level&opt=eng_leveldata&fd=fd&shrt=asc&field={$valuex[$n]}";
                $headericons = "<i class=\"fa fa-sort\" aria-hidden=\"true\" style=\"opacity: 0.3;\"></i>";
            }

            $ret .= "				<th class=\"w3-padding d5\" width=\"{$tdwidth[$valuex[$n]]}%\">
"
                    . "					<a href=\"javascript:void(0);\" "
                    . "onclick=\"javascript:loadurl('{$headergenurl}','tabledata');\" title=\"Shorting\">"
                    . ucwords(str_replace("_", " ", $value)) . $headericons . "</a>"
                    . "
				</th>
";
            $n++;
        }

        $ret .= "				<th style=\"width:50px;\" class=\"d5\">Actions</th>
";

        $ret .= "			</tr>
</thead>
<tbody>
";


        echo $ret;



        /*
          foreach ($headers as $hd) {
          $murls=$mrul."/?page=0";
          echo "<td class=\"d5\"><b>" . $hd . " <i class=\"fa fa-sort\" aria-hidden=\"true\" style=\"opacity: 0.5;\"></i></b></td>";
          }
         * */
        ?>

        <?php
        foreach ($eng_leveldata as $eng_level) {
            echo "<tr>"
            . "<td>" . $eng_level['ID'] . "</td>"
            . "<td>" . $eng_level['level'] . "</td>"
            . "<td>" . $eng_level['CEFR_Level'] . "</td>"
            . "<td>" . $eng_level['level_icon'] . "</td>"
            . "<td>" . $eng_level['deleted'] . "</td>"
            . "<td>" . $eng_level['published'] . "</td>"
            . "<td>"
            . "<a href=\"{$this->baseurl()}/eng_level/edit/{$eng_level['ID']}\" class=\"text-y\"><i class=\"fa fa-pencil-square-o \" aria-hidden=\"true\"></i></a> "
            . "<a href=\"{$this->baseurl()}/eng_level/delete/{$eng_level['ID']}\" class=\"text-red\"><i class=\"fa fa-trash \" aria-hidden=\"true\"></i></a> "
            . "</td>"
            . "</tr>";
        }
        ?>

    </table>
    <?php
    echo $this->get_pagi();
    ?>
</div>
