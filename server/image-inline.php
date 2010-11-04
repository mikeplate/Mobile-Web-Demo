<?php
require("image-inline.inc");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Images inline</title>
<meta name="viewport" content="user-scalable=yes, width=device-width" />
<link rel="stylesheet" href="../style.css" type="text/css" media="screen" />
</head>
<body>
	<h1>Images inline <a href="index.html">Back</a></h1>
	<dl>
		<dt>Automatic server side encoding of inline images to BASE64</dt>
		<dd><img src="<?php imagedata("subway.jpg"); ?>" /></dd>
	</dl>
</body>
</html>
