var HPan = function(elementId, customSettings) {
    var settings = {
        pos: 0,
        width: 600,
        overpan: 0,
        allowTemporaryOverpan: true,
        upIsRight: true
    };
    for (var item in customSettings)
        settings[item] = customSettings[item];

    var _element = document.getElementById(elementId);
    var _current = settings.pos;
    var _temp = 0;
    var _offset = -1;
    var _viewportWidth = settings.width;
    var _maxPan = settings.overpan;
    var _minPan = _viewportWidth - settings.overpan;

    this.onStart = function(ev) {
        _offset = getPosition(ev).x;
        _temp = _current;
        if (ev.preventDefault)
            ev.preventDefault();
    }

    this.onMove = function(ev) {
        if (_offset >= 0) {
            var newoffset = getPosition(ev).x;
            _temp = _current + (newoffset - _offset);
            if (!settings.allowTemporaryOverpan)
                _temp = ensurePanInside(_temp);
            setTranslate(_temp);
            if (ev.preventDefault)
                ev.preventDefault();
        }
    }

    this.onEnd = function(ev) {
        if (_offset >= 0) {
            _current = ensurePanInside(_temp);
            _offset = -1;
            _temp = 0;
            setTranslate(_current);
            if (ev.preventDefault)
                ev.preventDefault();
        }
    }

    this.onWheel = function(ev) {
        var isUp = ev.wheelDelta ? ev.wheelDelta>0 : ev.detail<0;
        var temp = _current + (_viewportWidth/5)*((isUp && settings.upIsRight) || (!isUp && !settings.upIsRight) ? -1 : 1);
        _current = ensurePanInside(temp);
        setTranslate(_current);
    }

    this.onPan = function(pan) {
    }

    var me = this;
    function callWithThis(method, arg) {
        me[method].call(me, arg);
    }

    function getPosition(ev) {
        if (ev.type && /touch/.test(ev.type)) {
            return { x: ev.touches[0].pageX, y: ev.touches[0].pageY };
        }
        else {
            var posx = 0;
            var posy = 0;
            if (ev.pageX || ev.pageY) {
                posx = ev.pageX;
                posy = ev.pageY;
            }
            else if (ev.clientX || ev.clientY) 	{
                posx = ev.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
                posy = ev.clientY + document.body.scrollTop + document.documentElement.scrollTop;
            }
            return { x: posx, y: posy };
        }
    }

    function setTranslate(xpos) {
        _element.style.MozTransform = "translateX(" + xpos + "px)";
        _element.style.WebkitTransform = "translateX(" + xpos + "px)";
        _element.style.msTransform = "translateX(" + xpos + "px)";
        _element.style.OTransform = "translateX(" + xpos + "px)";
    }

    function ensurePanInside(pos) {
        if (pos > _maxPan)
            pos = _maxPan;
        else if (pos < (_minPan - _element.offsetWidth))
            pos = _minPan - _element.offsetWidth;
        return pos;
    }

    var elParent = _element.parentNode;
    if (_element.addEventListener) {
        if ("ontouchstart" in _element) {
            _element.addEventListener("touchstart", function(ev) { callWithThis("onStart", ev); }, false);
            window.addEventListener("touchmove", function(ev) { callWithThis("onMove", ev); }, false);
            window.addEventListener("touchend", function(ev) { callWithThis("onEnd", ev); }, false);
        }
        else {
            _element.addEventListener("mousedown", function(ev) { callWithThis("onStart", ev); }, false);
            if (/firefox/i.test(navigator.userAgent))
                elParent.addEventListener("DOMMouseScroll", function(ev) { callWithThis("onWheel", ev); }, false);
            else
                elParent.addEventListener("mousewheel", function(ev) { callWithThis("onWheel", ev); }, false);
            window.addEventListener("mousemove", function(ev) { callWithThis("onMove", ev); }, false);
            window.addEventListener("mouseup", function(ev) { callWithThis("onEnd", ev); }, false);
        }
    }
    else {
        if ("ontouchstart" in _element) {
            _element.attachEvent("ontouchstart", function() { callWithThis("onStart", window.event); });
            window.attachEvent("ontouchmove", function() { callWithThis("onMove", window.event); });
            window.attachEvent("ontouchend", function() { callWithThis("onEnd", window.event); });
        }
        else {
            _element.attachEvent("onmousedown", function() { callWithThis("onStart", window.event); });
            elParent.attachEvent("onmousewheel", function() { callWithThis("onWheel", window.event); });
            window.attachEvent("onmousemove", function() { callWithThis("onMove", window.event); });
            window.attachEvent("onmouseup", function() { callWithThis("onEnd", window.event); });
        }
    }

    setTranslate(_current);
}

