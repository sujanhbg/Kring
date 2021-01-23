function loadurl(url, divid)
{
    var xhttp = new XMLHttpRequest();
    var loader = document.getElementById('loader');
    loader.style.display = "block";
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(divid).innerHTML = this.responseText;
            loader.style.display = "none";
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}
function loadurls(url, divid)
{
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(divid).innerHTML = this.responseText;
            loader.style.display = "none";
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}

function submitformf(type, tablename)
{
    var loader = document.getElementById('loader');
    loader.style.display = "block";
    const url = "{{baseurl}}/kringcoder/makeController/" + tablename + "/" + type;
    fetch(url, {
        method: "POST",
        body: new FormData(document.getElementById("mkform"))
    }).then(
            response => response.text()
    ).then(
            html => document.getElementById('modalbody').innerHTML = html
    );
    loader.style.display = "none";
    openmodal();
}
function viewmodel(type, tablename)
{
    var loader = document.getElementById('loader');
    loader.style.display = "block";
    const url = "{{baseurl}}/kringcoder/makeModel/" + tablename + "/" + type;
    fetch(url, {
        method: "POST",
        body: new FormData(document.getElementById("mkform"))
    }).then(
            response => response.text()
    ).then(
            html => document.getElementById('modalbody').innerHTML = html
    );
    loader.style.display = "none";
    openmodal();
}

$("#savebtn").click(function () {
    var url = "formmaker_gen.php?act=makeformac&tblnm={tablename}";
    $("#formsubmitmsg").show();
    $.ajax({
        type: "POST",
        url: url,
        data: $("#form1").serialize(),
        success: function (data)
        {

            if (data == 1) {
                $("#modelbody").html("<b style='font-size:48px;'>Saved....</b>");

            } else {
                $("#mainbody").html(data);
            }

        }
    });

    return false;


});

function loadmoreoption(formtype, fieldname, fieldnm) {
    loadurl('{{baseurl}}/kringcoder/formoptions/?tblnm={tablename}&type=' + formtype + '&fieldnm=' + fieldname + '&form_field=' + fieldnm, fieldname + 'moreo');
}
function submitForm(oFormElement)
{
    var loader = document.getElementById('loader');
    loader.style.display = "block";
    var xhr = new XMLHttpRequest();
    xhr.onload = function () {
        document.getElementById('formbody').innerHTML = xhr.responseText;
    };
    xhr.open(oFormElement.method, oFormElement.getAttribute("action"));
    xhr.send(new FormData(oFormElement));
    loader.style.display = "none";
    //openmodal();
    return false;
}

function submitAutoForm(oFormElement, action = null)
{
    var loader = document.getElementById('loader');
    loader.style.display = "block";
    var xhr = new XMLHttpRequest();
    xhr.onload = function () {
        if (xhr.responseText == '1') {
            document.getElementById('form_msg').innerHTML = "Saved Success!";
            document.getElementById('modalbody').innerHTML = xhr.responseText;
            openmodal();
        } else {
            document.getElementById('modalbody').innerHTML = xhr.responseText;
            openmodal();
        }
    };
    xhr.open(oFormElement.method, oFormElement.getAttribute("action"));
    xhr.send(new FormData(oFormElement));
    loader.style.display = "none";
    return false;
}
