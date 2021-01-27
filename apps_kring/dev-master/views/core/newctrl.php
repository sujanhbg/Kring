<h3>Create Controller</h3>
<form action="{baseurl}/core/createctrl" method="POST">

    <input type="text" placeholder="Controller Name" class="w3-input" style="width: 50%;" name="ctrlname" id="controllername" onchange="getfiledir();" onkeyup="this.onchange();" onpaste="this.onchange();" oncut="this.onchange();" oninput="this.onchange();">
    <br><br>
    <div id="formfs">
    </div>
</form>

<script>
    function getfiledir() {
        let f = document.getElementById("controllername").value;
        let fn = f.charAt(0).toUpperCase() + f.slice(1);
        document.getElementById("formfs").innerHTML = `
        Create files:<br> 
        {sapp}/dev-master/controllers/${fn}.php<br>
        {sapp}/dev-master/models/Model_${f}.php<br>      

<input type="submit" class="w3-btn btn btnr" value="Create Controller & Model">

`;
    }




</script>