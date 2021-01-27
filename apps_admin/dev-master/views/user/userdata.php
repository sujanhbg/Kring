
   <table class="w3-table-all">

    <?php
    $ret = "
		<thead>
			<tr>
";
    $n = 0;


    foreach ($headers as $value) {
        $valuex = ['ID','firstname','lastname','email','active','cell','username'];
        $tdwidth = ["ID" => null,"firstname" => null,"lastname" => null,"email" => null,"active" => null,"cell" => null,"username" => null];
        if (isset($_GET['field']) && $_GET['field'] == $valuex[$n]) {
            if ($_GET['shrt'] == "asc") {
                $headergenurl = "{$this->baseurl()}/?app=user&opt=userdata&fd=fd&shrt=desc&field={$valuex[$n]}";
                $headericons = "&nbsp;<i class=\"fa fa-caret-up\" aria-hidden=\"true\"></i>";
            } else {
                $headergenurl = "{$this->baseurl()}/?app=user&opt=userdata&fd=fd&shrt=asc&field={$valuex[$n]}";
                $headericons = "&nbsp;<i class=\"fa fa-caret-down\" aria-hidden=\"true\"></i>";
            }
        } else {
            $headergenurl = "{$this->baseurl()}/?app=user&opt=userdata&fd=fd&shrt=asc&field={$valuex[$n]}";
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
    foreach ($userdata as $user) {
        echo "<tr>"
        . "<td>" . $user['ID'] . "</td>"
. "<td>" . $user['firstname'] . "</td>"
. "<td>" . $user['lastname'] . "</td>"
. "<td>" . $user['email'] . "</td>"
. "<td>" . $user['active'] . "</td>"
. "<td>" . $user['cell'] . "</td>"
. "<td>" . $user['username'] . "</td>"
        . "<td>"
        . "<a href=\"{$this->baseurl()}/user/edit/{$user['ID']}\" class=\"text-y\"><i class=\"fa fa-pencil-square-o \" aria-hidden=\"true\"></i></a> "
        . "<a href=\"{$this->baseurl()}/user/delete/{$user['ID']}\" class=\"text-red\"><i class=\"fa fa-trash \" aria-hidden=\"true\"></i></a> "
        . "</td>"
        . "</tr>";
    }
    ?>

</table>
<?php
echo $this->get_pagi();

