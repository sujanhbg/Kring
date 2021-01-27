function RunJS(divid) {
    var ob = document.getElementById(divid).getElementsByTagName("script");
    var s = document.createElement("script");
    s.type = "text/javascript";
    s.text = ob[0].text;
    document.getElementsByTagName("head")[0].appendChild(s);
}
function loadurl(url, divid) {
    var loader = document.getElementById('loader');
    loader.style.display = "block";
    fetch(url)
            .then(response => response.text())
            .then(data => {
                document.getElementById(divid).innerHTML = data;
                loader.style.display = "none";
                RunJS(divid);
            });
}
function ssend(url) {
    var xhttp = new XMLHttpRequest();
    var loader = document.getElementById('loader');
    loader.style.display = "block";
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            loader.style.display = "none";
            snackbar("done");
            snackbar(this.responseText);

        } else {
            snackbar("Ssend request error");
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
function makeview(type, tablename)
{
    var loader = document.getElementById('loader');
    loader.style.display = "block";
    const url = "{{baseurl}}/kringcoder/makeview/" + tablename + "/" + type;
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


function snackbar(msg) {
    var x = document.getElementById("snackbar");
    x.innerHTML = msg;
    x.className = "show";
    setTimeout(function () {
        x.className = x.className.replace("show", "");
    }, 3000);
}
