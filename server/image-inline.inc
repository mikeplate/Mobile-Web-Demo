<?php
function imagedata($fileName) {
	echo "data:image/jpeg;base64,";
	
	$file = fopen($fileName, "r");
	$binarydata = fread($file, filesize($fileName));
	fclose($file);
	
	echo base64_encode($binarydata);
}
?>
