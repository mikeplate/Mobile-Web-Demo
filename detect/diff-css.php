<!DOCTYPE html>
<html>
<head>
<title>Different CSS</title>
<meta name="viewport" content="user-scalable=yes, width=device-width" />
<link rel="stylesheet" href="../style.css" type="text/css" />
<style>
<?php
require_once 'mobile-detect.php';
$device = new Mobile_Detect();
if ($device->isMobile()) {
?>
    .desktop-only {
        display: none;
    }
<?php } else { ?>
    .mobile-only {
        display: none;
    }
<?php } ?>
</style>
</head>
<body>
	<h1>Different CSS <a href="index.html">Back</a></h1>
	<dl class="mobile-only">
		<dt>Mobile</dt>
		<dd>This is content for the mobile browser</dd>
	</dl>
	<dl class="desktop-only">
		<dt>Desktop</dt>
		<dd>This is content for the desktop browser</dd>
	</dl>
</body>
</html>
