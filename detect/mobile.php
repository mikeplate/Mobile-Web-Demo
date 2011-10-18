<!DOCTYPE html>
<?php
require_once 'mobile-detect.php';
$device = new Mobile_Detect();
?>
<html>
<head>
<title>Mobile Device Detection</title>
<meta name="viewport" content="user-scalable=yes, width=device-width" />
<link rel="stylesheet" href="../style.css" type="text/css" />
</head>
<body>
	<h1>Mobile Device Detection <a href="index.html">Back</a></h1>
	<dl>
        <?php if ($device->isMobile()) { ?>
		<dt>Mobile device</dt>
		<dd>
            <p>You have a mobile device</dd>
		</dd>
        <?php } else { ?>
		<dt>Computer</dt>
		<dd>You have a computer. Nothing special about that.</dd>
        <?php } ?>
	</dl>
</body>
</html>
