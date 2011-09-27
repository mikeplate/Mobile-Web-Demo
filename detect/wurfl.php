<!DOCTYPE HTML>
<?php
require_once 'wurfl-include.php';
$device = $wurflManager->getDeviceForHttpRequest($_SERVER);
?>
<html>
<head>
<title>WURFL Device Database</title>
<meta name="viewport" content="user-scalable=yes, width=device-width" />
<link rel="stylesheet" href="../style.css" type="text/css" />
</head>
<body>
	<h1>WURFL Device Database <a href="index.html">Back</a></h1>
	<dl>
        <?php if ($device->getCapability('is_wireless_device')=='true') { ?>
		<dt>About your device</dt>
		<dd>
            <p>Brand Name: <?php echo $device->getCapability("brand_name"); ?> </p>
            <p>Model Name: <?php echo $device->getCapability("model_name"); ?> </p>
            <p>Device OS: <?php echo $device->getCapability("device_os") . ' ' . $device->getCapability("device_os_version"); ?> </p>
            <p>Xhtml Preferred Markup: <?php echo $device->getCapability("preferred_markup"); ?> </p>
            <p>Resolution Width: <?php echo $device->getCapability("resolution_width"); ?> </p>
            <p>Resolution Height: <?php echo $device->getCapability("resolution_height"); ?> </p>
            <p>Tablet: <?php echo $device->getCapability("is_tablet"); ?> </p>
            <p>Pointing Method: <?php echo $device->getCapability("pointing_method"); ?> </p>
        </dd>
        <?php } else { ?>
		<dt>About your computer</dt>
		<dd>WURFL is only for mobile devices</dd>
        <?php } ?>
	</dl>
</body>
</html>
