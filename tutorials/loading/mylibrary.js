var isStandard = "addEventListener" in window;

if (isStandard)
    window.addEventListener("load", start, false);
else
    window.attachEvent("onload", start);

function start() {
    var showtime = document.getElementById("showtime");
    if (showtime)
        showtime.innerHTML = formatDateTime(new Date());

    if ("onstart" in window)
        onstart();
}

