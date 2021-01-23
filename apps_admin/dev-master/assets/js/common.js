var mySidebar = document.getElementById("sidebar");
var overlayBg = document.getElementById("myOverlay");


function w3_open() {
    if (mySidebar.style.display === 'block') {
        mySidebar.style.display = 'none';
        overlayBg.style.display = "none";
    } else {
        mySidebar.style.display = 'block';
        overlayBg.style.display = "block";
    }
}
function w3_close() {
    mySidebar.style.display = "none";
    overlayBg.style.display = "none";
}
function openmodal(height = "95", width = "95") {
    document.getElementById('id01').style.display = 'block';
    const btn = document.querySelector('#modal-content');
    btn.style.height = height + "vh";
    btn.style.width = width + "%";
}
function closemodal() {
    document.getElementById('id01').style.display = 'none';
}

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
function loadurlold(url, divid) {
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
function loadurls(url, divid) {
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
function submitformf(type, tablename) {
    var loader = document.getElementById('loader');
    loader.style.display = "block";
    const url = "{{baseurl}}/kring/make/" + tablename + "/" + type;
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

function submitAutoForm(oFormElement, action = null, actiona = null) {
    var loader = document.getElementById('loader');
    loader.style.display = "block";
    var xhr = new XMLHttpRequest();
    xhr.onload = function () {
        if (xhr.responseText == '1') {
            action;
            actiona;
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
    