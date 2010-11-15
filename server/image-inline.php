<?php
// Insert php code from another file here. This will make it easier to resure the same php code
// in many web pages. Just insert this line in all pages that need the functionality.
require("image-inline.inc");

// Anywhere you want to output BASE64 encoded data for an image, just call the php function
// imagedata with the image file name that already exists on disk on the web server
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Images inline</title>
<meta name="viewport" content="user-scalable=yes, width=device-width" />
<link rel="stylesheet" href="../style.css" type="text/css" />
</head>
<body>
	<h1>Images inline <a href="index.html">Back</a></h1>
	<dl>
		<dt>Automatic server side encoding of inline images to BASE64</dt>
		<dd><img src="<?php imagedata("subway.jpg"); ?>" /></dd>
	</dl>
</body>
</html>
