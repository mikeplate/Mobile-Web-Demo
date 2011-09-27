<!DOCTYPE HTML>
<html>
<head>
<title>User Agent statistics</title>
<meta name="viewport" content="user-scalable=yes, width=device-width" />
<link rel="stylesheet" href="../style.css" type="text/css" />
</head>
<body>
	<h1>User Agent statistics<a href="index.html">Back</a></h1>
	<dl>
		<dt>Your browser's user agent string</dt>
		<dd><?php echo $_SERVER['HTTP_USER_AGENT'] ?></dd>
        <dt>Top list of user agents</dt>
        <dd>
<?php
if (!isset($_COOKIE['sentuseragent'])) {
    $file = fopen('useragents.txt', 'a');
    fwrite($file, $_SERVER['HTTP_USER_AGENT'] . "\r\n");
    fclose($file);
    setcookie('sentuseragent', '1');
}

$all = array();
$file = fopen('useragents.txt', 'r');
while (!feof($file)) {
    $line = fgets($file);
    if (strlen($line) > 0) {
        if (isset($all[$line])) {
            $all[$line]++;
        }
        else {
            $all[$line] = 1;
        }
    }
}
fclose($file);
arsort($all);
foreach ($all as $agent => $count) {
    echo "<p><b>$count</b> $agent</p>\r\n";
}
?>
        </dd>
	</dl>
</body>
</html>

