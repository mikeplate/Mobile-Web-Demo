<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>File Upload</title>
<meta name="viewport" content="user-scalable=yes, width=device-width" />
<link rel="stylesheet" href="../style.css" type="text/css" />
</head>
<body>
	<h1>Upload completed <a href="index.html">Back</a></h1>
	<dl>
		<dt>File name</dt>
		<dd><?php echo $_FILES['File']['name']; ?></dd>
		<dt>File size</dt>
		<dd><?php echo $_FILES['File']['size']; ?></dd>
	</dl>
</body>
</html>
