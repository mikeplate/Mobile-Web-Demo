<?php
function resizeImage() {
    // Error checking
    if (count($_FILES)==0) {
        return 'You must specify an image file!';
    }

    // Retrieve destination size
    $destWidth = intval($_POST['width']);
    $destHeight = intval($_POST['height']);

    // Error checking
    if ($destWidth<=0 || $destHeight<=0) {
        return 'You must specify a width and height for the resulting image!';
    }

    // Create image to hold the destination image
	$destImage = imagecreatetruecolor($destWidth, $destHeight);
    if ($_POST['format']=="png") {
        $alphacolor = imagecolorallocatealpha($destImage, 0, 0, 0, 127);
        imagecolortransparent($destImage, $alphacolor);
        imagealphablending($destImage, false);
        imagesavealpha($destImage, true);
        imagefilledrectangle($destImage, 0, 0, $destWidth, $destHeight, $alphacolor);
    }
    else {
        $bkgndcolor = imagecolorallocate($destImage, 255, 255, 255);
        imagefilledrectangle($destImage, 0, 0, $destWidth, $destHeight, $bkgndcolor);
    }

    // Open and resize image
    $srcName = $_FILES['image']['tmp_name'];
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    if ($ext=='jpg' || $ext=='jpeg')
        $srcImage = imagecreatefromjpeg($srcName);
    else if ($ext[$i]=='png')
        $srcImage = imagecreatefrompng($srcName);
    $srcWidth = imagesx($srcImage);
    $srcHeight = imagesy($srcImage);
    $scaleX = $srcWidth / $destWidth;
    $scaleY = $srcHeight / $destHeight;
    if ($_POST['resize']=='in') {
        if ($scaleX > $scaleY) {
            $scale = $scaleY;
            imagecopyresampled($destImage, $srcImage, 0, 0, ($srcWidth - $destWidth*$scale)/2, 0, $destWidth, $destHeight, $destWidth*$scale, $destHeight*$scale);
        }
        else {
            $scale = $scaleX;
            imagecopyresampled($destImage, $srcImage, 0, 0, 0, ($srcHeight - $destHeight*$scale)/2, $destWidth, $destHeight, $destWidth*$scale, $destHeight*$scale);
        }
    }
    else {
        if ($scaleX > $scaleY) {
            $scale = $scaleX;
            imagecopyresampled($destImage, $srcImage, 0, ($destHeight - $srcHeight/$scale)/2, 0, 0, $destWidth, $srcHeight/$scale, $srcWidth, $srcHeight);
        }
        else {
            $scale = $scaleY;
            imagecopyresampled($destImage, $srcImage, ($destWidth - $srcWidth/$scale)/2, 0, 0, 0, $srcWidth/$scale, $destHeight, $srcWidth, $srcHeight);
        }
    }
    
    $output = uniqid();
    if ($_POST['format']=="png") {
        $output = 'resize-' . $output . '.png';
        imagepng($destImage, '../_temp/' . $output, 0);
    }
    else {
        $output = 'resize-' . $output . '.jpg';
        imagejpeg($destImage, '../_temp/' . $output, 95);
    }

    header('Location: image-resize.php?image=' . $output . '&width=' . $destWidth . '&height=' . $destHeight);
    die();
}

$errmsg = NULL;
if ($_SERVER['REQUEST_METHOD']=='POST') {
    $errmsg = resizeImage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Resize Image</title>
    <meta name="viewport" content="user-scalable=yes, width=device-width" />
    <link rel="stylesheet" href="../style.css" type="text/css" />
</head>
<body>
	<h1>Resize Image <a href="index.html">Back</a></h1>
<?php
if ($errmsg!=NULL) {
    ?>
    <dl>
        <dt>Error</dt>
        <dd><?php echo $errmsg; ?></dd>
    </dl>
    <?php
}
else if (!isset($_GET['image'])) {
    ?>
	<dl>
		<dt>Upload image</dt>
		<dd>
            <form method="POST" enctype="multipart/form-data">
                <p>
                    <input type="file" name="image" /><br />
                </p>
                <p>
                    Resize image to fit the box:<br />
                    <input type="text" name="width" size="5" /> x 
                    <input type="text" name="height" size="5" />
                </p>
                <p>
                    Resizing strategy:<br />
                    <input type="radio" id="resizeout" name="resize" value="out" checked="checked" />
                    <label for="resizeout">Zoom out, adding empty area</label><br />
                    <input type="radio" id="resizein" name="resize" value="in" />
                    <label for="resizein">Zoom in, accepting cutting off area</label>
                </p>
                <p>
                    Output image format:<br />
                    <input type="radio" id="formatpng" name="format" value="png" checked="checked" />
                    <label for="formatpng">png</label><br />
                    <input type="radio" id="formatjpg" name="format" value="jpg" />
                    <label for="formatjpg">jpg</label>
                </p>
                <p>
                    <input type="submit" value="Upload" />
                </p>
            </form>
        </dd>
	</dl>
    <?php
}
else {
    ?>
    <dl>
        <dt>Resized image <?php echo  $_GET['width'] . ' x ' . $_GET['height']; ?></dt>
        <dd>
            <img src="../_temp/<?php echo $_GET['image']; ?>" />
        </dd>
    </dl>
    <?php
}
?>
</body>
</html>
