<!DOCTYPE HTML>
<html>
<head>
<title>Image -> Base64</title>
<meta name="viewport" content="user-scalable=yes, width=device-width" />
<link rel="stylesheet" href="../style.css" type="text/css" />
</head>
<body>
	<h1>Image -> Base64 <a href="index.html">Back</a></h1>
<?php
if ($_SERVER['REQUEST_METHOD']=='GET') {
?>
	<dl>
		<dt>Upload image</dt>
		<dd>
            <form method="POST" enctype="multipart/form-data">
            <input type="file" name="image" /><br />
            <input type="submit" value="Upload" />
            </form>
        </dd>
	</dl>
<?php
}
else {
    $filename = $_FILES['image']['tmp_name'];
    $file = fopen($filename, 'r');
    $binarydata = fread($file, filesize($filename));
    $base64string = base64_encode($binarydata);
    fclose($file);
?>
    <dl>
        <dt>Inline Image Data URI</dt>
        <dd>
            <?php echo $base64string; ?>
        </dd>
    </dl>
<?php
}
?>
</body>
</html>
