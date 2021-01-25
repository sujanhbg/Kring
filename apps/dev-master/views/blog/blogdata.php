<table class="w3-table-all">

    <?php
    $ret = "\n\t\t<thead>\n\t\t\t<tr>\n";
    $n = 0;


    foreach ($headers as $value) {
        $valuex = ['ID', 'title'];
        $tdwidth = ["ID" => null, "title" => null];
        if (isset($_GET['field']) && $_GET['field'] == $valuex[$n]) {
            if ($_GET['shrt'] == "asc") {
                $headergenurl = "{$this->baseurl()}/?app=blog&opt=blogdata&fd=fd&shrt=desc&field={$valuex[$n]}";
                $headericons = "&nbsp;<i class=\"fa fa-caret-up\" aria-hidden=\"true\"></i>";
            } else {
                $headergenurl = "{$this->baseurl()}/?app=blog&opt=blogdata&fd=fd&shrt=asc&field={$valuex[$n]}";
                $headericons = "&nbsp;<i class=\"fa fa-caret-down\" aria-hidden=\"true\"></i>";
            }
        } else {
            $headergenurl = "{$this->baseurl()}/?app=blog&opt=blogdata&fd=fd&shrt=asc&field={$valuex[$n]}";
            $headericons = "<i class=\"fa fa-sort\" aria-hidden=\"true\" style=\"opacity: 0.3;\"></i>";
        }

        $ret .= "\t\t\t\t<th class=\"w3-padding d5\" width=\"{$tdwidth[$valuex[$n]]}%\">\n"
                . "\t\t\t\t\t<a href=\"javascript:void(0);\" "
                . "onclick=\"javascript:loadurl('{$headergenurl}','tabledata');\" title=\"Shorting\">"
                . ucwords(str_replace("_", " ", $value)) . $headericons . "</a>"
                . "\n\t\t\t\t</th>\n";
        $n++;
    }

    $ret .= "\t\t\t\t<th style=\"width:50px;\" class=\"d5\">Actions</th>\n";

    $ret .= "\t\t\t</tr>\n</thead>\n<tbody>\n";


    echo $ret;



    /*
      foreach ($headers as $hd) {
      $murls=$mrul."/?page=0";
      echo "<td class=\"d5\"><b>" . $hd . " <i class=\"fa fa-sort\" aria-hidden=\"true\" style=\"opacity: 0.5;\"></i></b></td>";
      }
     * */
    ?>

    <?php
    foreach ($blogdata as $blog) {
        echo "<tr>"
        . "<td>" . $blog['ID'] . "</td>"
        . "<td>" . $blog['title'] . "</td>"
        . "<td>"
        . "<a href=\"{$this->baseurl()}/blog/edit/{$blog['ID']}\" class=\"text-y\"><i class=\"fa fa-pencil-square-o \" aria-hidden=\"true\"></i></a> "
        . "<a href=\"{$this->baseurl()}/blog/delete/{$blog['ID']}\" class=\"text-red\"><i class=\"fa fa-trash \" aria-hidden=\"true\"></i></a> "
        . "</td>"
        . "</tr>";
    }
    ?>

</table>
<?php
echo $this->get_pagi();


