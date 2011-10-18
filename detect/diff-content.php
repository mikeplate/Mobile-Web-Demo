<!DOCTYPE html>
<html>
<head>
<title>Different content</title>
<meta name="viewport" content="user-scalable=yes, width=device-width" />
<link rel="stylesheet" href="../style.css" type="text/css" />
</head>
<body>
	<h1>Different content <a href="index.html">Back</a></h1>
<?php
require_once 'mobile-detect.php';
$device = new Mobile_Detect();
if ($device->isMobile()) {
?>
	<dl>
		<dt>Mobile</dt>
		<dd>This is content for the mobile browser</dd>
	</dl>
<?php } else { ?>
    <h2>Desktop</h2>
    <div>
        This is content for the desktop browser
    </div>
<?php } ?>
</body>
</html>
