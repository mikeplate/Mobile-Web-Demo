<?php
// Our own php function that will output BASE64 codes
function imagedata($fileName) {
	// Output the prefix that the browser will recognize
	echo "data:image/jpeg;base64,";
	
	// Open the image file on disk
	$file = fopen($fileName, "r");
	
	// Read all binary data from the opened image file
	$binarydata = fread($file, filesize($fileName));
	
	// Remember to close all opened files
	fclose($file);
	
	// Output the binary data read from the file, encoded as BASE64 with the help of built-in php function
	echo base64_encode($binarydata);
}
?>
