// This is where the counter value is stored in memory (not persistent)
var current = 0;

// Catch the window load event. Make it work in IE too.
if (window.addEventListener)
	window.addEventListener("load", start, false);
else
	window.attachEvent("onload", start);

// This function is called when the page has finished loading its contents
function start() {
	// Make sure onChange is called for touch and mouse events
	handleChange("decreaseButton", "touchstart", -1);
	handleChange("decreaseButton", "mousedown", -1);
	handleChange("increaseButton", "touchstart", 1);
	handleChange("increaseButton", "mousedown", 1);
	
	// Special case for IE, where mousedown is not sent when double clicking
	if (document.attachEvent) {
		handleChange("decreaseButton", "dblclick", -1);
		handleChange("increaseButton", "dblclick", 1);
	}

	// Check if web storage is supported by the browser
	if (window.localStorage) {
		// Fetch persisted value from web storage. Make sure it is an integer that we can change.
		var storedValue = window.localStorage.getItem("counter");
		if (storedValue != null)
			current = parseInt(storedValue);
			
		// Update the user interface by calling onChange with 0 change
		onChange(0);
	}
	else {
		// Tell user that counter can't be persisted
		document.getElementById("counter").innerHTML = "Your browser does not support Web Storage";
	}
}

// Call this function to handle an event by calling onChange with a specified change of counter value
function handleChange(elementName, eventName, offset) {
	var button = document.getElementById(elementName);
	if (button.addEventListener) {
		button.addEventListener(eventName, function(ev) { 
			onChange(offset);
			ev.preventDefault();
		}, false);
	}
	else {
		button.attachEvent("on" + eventName, function(ev) { 
			onChange(offset);
			ev.cancelBubble = true;
		});
	}
}

// Call this function to change the counter value
function onChange(offset) {
	// Change the counter
	current += offset;
	
	// Show the value of the counter in the user interface
	document.getElementById("counter").innerHTML = current;

	// Persist the new value to web storage, if supported by the browser
	if (window.localStorage) {
		window.localStorage.setItem("counter", current);
	}
}
