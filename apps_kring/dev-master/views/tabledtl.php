

<h3 class="d2 w3-padding">Fields on Table:{tablename}</h3>
<form id="mkform" method="POST">
    <input type="hidden" name="tblnm" value="{tablename}">
    <table class='w3-table w3-table-dark'>
        <tr class="heading">
            <td> </td>
            <td>Field</td>
            <td>Type</td>
            <td>Null</td>
            <td>Key</td>
            <td>Default</td>
            <td>Extra</td>
        </tr>

        <?php
        foreach ($tbldata as $row) {
            echo<<<OERR
    <tr>
    <td><input type="checkbox" name="field[]" value="{$row['Field']}" checked/></td>
    <td>{$row['Field']}</td>
    <td>{$row['Type']}</td>
    <td>{$row['Null']}</td>
    <td>{$row['Key']}</td>
    <td>{$row['Default']}</td>
    <td>{$row['Extra']}</td>
    </tr>
    
OERR;
        }
        ?>
    </table>

</form>
<div class="w3-padding d2">
    <a class="w3-btn btn-primary" onclick="submitformf('{tablename}', 'mvc');">CRUD-Controller</a>
    <a class="w3-btn btn-green" onclick="viewmodel('{tablename}', 'mvc');">CRUD-Model</a>
    <a class="w3-btn btn-warning" href="{baseurl}/kringcoder/formmaker/{tablename}">Form Maker</a>
</div>