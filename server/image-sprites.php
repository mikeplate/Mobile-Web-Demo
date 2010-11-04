<?php
// Prepare some variables that will be set and used later
$mergedImage = NULL;
$xpos = 0;
$imageWidth = 0;
$imageHeight = 0;

// Convert the address part after "?" to an array of strings with "," as separator
$files = split(",", $_SERVER["QUERY_STRING"]);

// Remember the path to this script, since we will only accept images in this script's folder and sub folders
$baseDir = dirname(realpath(__FILE__));

// Go through all specified file names
foreach ($files as $fileName) {
	// Build the path to the image file name
	$filePath = realpath($fileName);
	
	// Make sure that the image full path is in, or below, the directory of the current script
	// Anything else could be a security risk where protected images could be shown
	if (strlen($filePath) > strlen($baseDir) && $baseDir == substr($filePath, 0, strlen($baseDir))) {
		// Read the file data into memory and place it inside an image object
		$img = imagecreatefromjpeg($fileName);
		
		// When we are processing the first image, retrieve the image dimensions and assume that all other
		// images are of the exact same dimension
		if (is_null($mergedImage)) {
			$imageWidth = imagesx($img);
			$imageHeight = imagesy($img);
			
			// Create the merged image onto which we'll paint all read images
			$mergedImage = imagecreatetruecolor($imageWidth * count($files), $imageHeight);
		}
		
		// Paint the read image onto the surface of our merged image
		imagecopy($mergedImage, $img, $xpos, 0, 0, 0, $imageWidth, $imageHeight);
		
		// Read file data is not needed anymore
		imagedestroy($img);
		
		// Move the position for the next image on the merged image surface
		$xpos += $imageWidth;
	}
}

// Output the resulting image to a buffer. We do this so that we can calculate the number of bytes that
// the merged image has. We need to send this in a header.
ob_start();
imagejpeg($mergedImage, NULL, 95);
$imageBinary = ob_get_contents();
$imageBytes = ob_get_length();
ob_end_clean();
imagedestroy($mergedImage);

// Send headers for image type and image size in bytes
header("Content-type: image/jpeg");
header("Content-Length: " . $imageBytes);

// Send the actual merged image binary data
echo $imageBinary;
?>
