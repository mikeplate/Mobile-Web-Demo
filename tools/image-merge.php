<!DOCTYPE html>
<html>
<head>
    <title>Merge Images</title>
    <meta name="viewport" content="user-scalable=yes, width=device-width" />
    <link rel="stylesheet" href="../style.css" type="text/css" />
</head>
<body>
	<h1>Merge Images <a href="index.html">Back</a></h1>
<?php
if ($_SERVER['REQUEST_METHOD']=='GET') {
?>
	<dl>
		<dt>Upload images</dt>
		<dd>
            <form method="POST" enctype="multipart/form-data">
                <p>
                    <input type="file" name="image0" /><br />
                    <input type="file" name="image1" /><br />
                    <input type="file" name="image2" /><br />
                    <input type="file" name="image3" /><br />
                    <input type="file" name="image4" /><br />
                    <input type="file" name="image5" /><br />
                    <input type="file" name="image6" /><br />
                    <input type="file" name="image7" /><br />
                    <input type="file" name="image8" /><br />
                    <input type="file" name="image9" /><br />
                </p>
                <p>
                    Size all images to icon size:<br />
                    <input type="text" name="width" size="5" /> x 
                    <input type="text" name="height" size="5" />
                </p>
                <p>
                    Output image format:<br />
                    <select name="format">
                        <option selected="selected">png</option>
                        <option>jpg</option>
                    </select>
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
    // Build an array of the uploaded files
    $src = Array();
    $ext = Array();
    for ($i = 0; $i < count($_FILES); $i++) {
        $tmpname = $_FILES['image' . $i]['tmp_name'];
        if (strlen($tmpname) > 0) {
            array_push($src, $tmpname);
            array_push($ext, pathinfo($_FILES['image' . $i]['name'], PATHINFO_EXTENSION));
        }
    }

    // Error checking
    if (count($src)==0) {
        echo "<dl><dt>Error</dt><dd>You must specify at least one image file!</dd></dl></body></html>";
        die();
    }

    // Retrieve icon size, the size or the individual images
    $iconWidth = intval($_POST['width']);
    $iconHeight = intval($_POST['height']);

    // Error checking
    if ($iconWidth<=0 || $iconHeight<=0) {
        echo "<dl><dt>Error</dt><dd>You must specify a width and height of the individual icons in the merged image!</dd></dl></body></html>";
        die();
    }

    // Create image to hold the merged images
	$mergedImage = imagecreatetruecolor($iconWidth * count($src), $iconHeight);
    if ($_POST['format']=="png") {
        imagecolortransparent($mergedImage, imagecolorallocatealpha($mergedImage, 0, 0, 0, 127));
        imagealphablending($mergedImage, false);
        imagesavealpha($mergedImage, true);
    }

    // Open all images
    for ($i = 0; $i < count($src); $i++) {
        if ($ext[$i]=='jpg' || $ext[$i]=='jpeg')
            $image = imagecreatefromjpeg($src[$i]);
        else if ($ext[$i]=='png')
            $image = imagecreatefrompng($src[$i]);
        $imageWidth = imagesx($image);
        $imageHeight = imagesy($image);
        $scaleX = $imageWidth / $iconWidth;
        $scaleY = $imageHeight / $iconHeight;
        if ($scaleX > $scaleY) {
            $scale = $scaleY;
            imagecopyresampled($mergedImage, $image, $i * $iconWidth, 0, ($imageWidth - $iconWidth*$scale)/2, 0, $iconWidth, $iconHeight, $iconWidth*$scale, $iconHeight*$scale);
        }
        else {
            $scale = $scaleX;
            imagecopyresampled($mergedImage, $image, $i * $iconWidth, 0, 0, ($imageHeight - $iconHeight*$scale)/2, $iconWidth, $iconHeight, $iconWidth*$scale, $iconHeight*$scale);
        }
    }
    
    $output = uniqid();
    if ($_POST['format']=="png") {
        $output = 'merge-' . $output . '.png';
        imagepng($mergedImage, $output, 0);
    }
    else {
        $output = 'merge-' . $output . '.jpg';
        imagejpeg($mergedImage, $output, 95);
    }
?>
    <dl>
        <dt>Merged image <?php echo ($iconWidth * count($src)) . ' x ' . $iconHeight; ?></dt>
        <dd>
            <img src="<?php echo $output; ?>" />
        </dd>
    </dl>
<?php
}
?>
</body>
</html>
