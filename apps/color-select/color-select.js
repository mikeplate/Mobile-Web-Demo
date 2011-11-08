var colorElement;
var colorValueElement;
var colorAmount = [0x30, 0x70, 0x90];

function updateColor() {
    function hex(value) {
        var hex = value.toString(16);
        return (hex.length < 2 ? "0":"") + hex;
    }

    var redAmount = colorAmount[0];
    var greenAmount = colorAmount[1];
    var blueAmount = colorAmount[2];
    var hexValue = "#" + hex(redAmount) + hex(greenAmount) + hex(blueAmount);
    var rgbValue = "rgb(" + redAmount + ", " + greenAmount + ", " + blueAmount + ")";

    colorValueElement.innerHTML = hexValue + " " + rgbValue;
    colorElement.style.backgroundColor = hexValue;
}

function changeColor(index, offset) {
    var current = colorAmount[index];
    if (current==255 && offset<-1)
        current = 256;
    current += offset;

    if (current<0)
        current = 0;
    else if (current>255)
        current = 255;
    
    colorAmount[index] = current;
    updateColor();
}

function onButton(ev) {
    ev = ev || window.event;
    var element = "srcElement" in ev ? ev.srcElement : ev.target;
    while (element && (!element.tagName || element.tagName!="DIV"))
        element = element.parentNode;
    var index = element.colorIndex;
    if (index<3) {
        changeColor(index, element.colorChange);
    }
    else {
        changeColor(0, element.colorChange);
        changeColor(1, element.colorChange);
        changeColor(2, element.colorChange);
    }

    if (ev.preventDefault)
        ev.preventDefault();
}

function handlePress(btn, func) {
    if ("ontouchstart" in window) {
        if ("addEventListener" in btn)
            btn.addEventListener("touchstart", func, false);
        else
            btn.attachEvent("ontouchstart", func);
    }
    else {
        if ("addEventListener" in btn)
            btn.addEventListener("mousedown", func, false);
        else
            btn.attachEvent("onmousedown", func);
    }
}

window.onload = function() {
    colorElement = document.getElementById("color");
    colorValueElement = document.getElementById("color-value");
    updateColor();

    var buttonrows = document.getElementsByTagName("ul");
    for (var i = 0; i<buttonrows.length; i++) {
        var buttons = buttonrows[i].getElementsByTagName("div");
        for (var j = 0; j<buttons.length; j++) {
            var button = buttons[j];
            button.colorIndex = i;
            button.colorChange = parseInt(button.innerHTML);
            handlePress(button, onButton);
        }
    }

    window.setTimeout(function() { window.scrollTo(0, 1); }, 100);
}


