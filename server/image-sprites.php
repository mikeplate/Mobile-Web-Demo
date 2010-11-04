<?php
$mergedImage = NULL;
$xpos = 0;
$imageWidth = 0;
$imageHeight = 0;

$files = split(",", $_SERVER["QUERY_STRING"]);
foreach ($files as $fileName) {
	$img = imagecreatefromjpeg($fileName);
	
	if (is_null($mergedImage)) {
		$imageWidth = imagesx($img);
		$imageHeight = imagesy($img);
		$mergedImage = imagecreatetruecolor($imageWidth * count($files), $imageHeight);
	}
	imagecopy($mergedImage, $img, $xpos, 0, 0, 0, $imageWidth, $imageHeight);
	imagedestroy($img);
	$xpos += $imageWidth;
}

ob_start();
imagejpeg($mergedImage, NULL, 95);
$imageBinary = ob_get_contents();
$imageBytes = ob_get_length();
ob_end_clean();
imagedestroy($mergedImage);

header("Content-type: image/jpeg");
header("Content-Length: " . $imageBytes);
echo $imageBinary;
?>
