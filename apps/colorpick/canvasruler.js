var CanvasRuler = function(elementId, customSettings) {
    var settings = {
        from: 0,
        to: 100,
        markCount: 10,
        tickCount: 10,
        horzMargin: 10,
        backgroundColor: "#ffffff",
        markTextColor: "#000000",
        markColor: "#000000",
        markThickness: 1,
        markLength: 20,
        tickColor: "#000000",
        tickThickness: 1,
        tickLength: 10,
        markFont: "12px Verdana, sans-serif",
        topValue: function(value) {
            return value;
        },
        bottomValue: null
    };
    for (var item in customSettings)
        settings[item] = customSettings[item];

    var percentWidth = null;

    var el = document.getElementById(elementId);
    if ("width" in settings) {
        if (typeof settings.width==="string" && settings.width.length>1 && settings.width.substring(settings.width.length-1)==="%") {
            settings.width = parseInt(settings.width.substring(0, settings.width.length-1))/100.0*el.parentNode.offsetWidth;
        }
        el.width = settings.width;
    }
    else
        settings.width = el.width;
    if ("height" in settings)
        el.height = settings.height;
    else
        settings.height = el.height;
    var c = el.getContext("2d");

    c.fillStyle = settings.backgroundColor;
    c.fillRect(0, 0, el.width, el.height);

    c.fillStyle = settings.markTextColor;
    c.textBaseline = "top";
    c.font = settings.markFont;
    c.textAlign = "center";

    var markDistance = (settings.width - settings.horzMargin*2) / settings.markCount;
    var tickDistance = markDistance / settings.tickCount;
    var dualValues = settings.topValue && settings.bottomValue;
    for (var markIndex = 0; markIndex <= settings.markCount; markIndex++) {
        var markStart = markDistance * markIndex + settings.horzMargin;
        var x = markStart;
        c.strokeStyle = settings.markColor;
        c.lineWidth = settings.markThickness;
        c.beginPath();
        c.moveTo(x, 15);
        c.lineTo(x, 35);
        c.closePath();
        c.stroke();
        if (markIndex < settings.markCount) {
            c.strokeStyle = settings.tickColor;
            c.lineWidth = settings.tickThickness;
            c.beginPath();
            for (var tickIndex = 1; tickIndex < settings.tickCount; tickIndex++) {
                c.moveTo(x + tickIndex*tickDistance, 19);
                c.lineTo(x + tickIndex*tickDistance, 31);
            }
            c.closePath();
            c.stroke();
        }

        var markValue = markIndex * Math.floor((settings.to - settings.from + 1) / settings.markCount);
        if (markIndex == settings.markCount)
            markValue = settings.to;
        c.fillText(markValue.toString(), x, 2);
        c.fillText(markValue.toString(16).toUpperCase(), x, 36);
    }
}

