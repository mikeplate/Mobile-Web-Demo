<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Http Headers</title>
<meta name="viewport" content="user-scalable=yes, width=device-width" />
<link rel="stylesheet" href="../style.css" type="text/css" />
</head>
<body>
	<h1>Http Headers <a href="index.html">Back</a></h1>
	<dl>
	<?php
		foreach($_SERVER as $varName => $varValue) {
			if (ereg('HTTP_(.+)', $varName)) {
				$httpName = substr($varName, 5);
				$httpValue = htmlspecialchars($varValue);
				echo "<dt>$httpName</dt><dd>$httpValue</dd>";
			}
		}
	?>
	</dl>
</body>
</html>
