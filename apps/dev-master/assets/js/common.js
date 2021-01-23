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