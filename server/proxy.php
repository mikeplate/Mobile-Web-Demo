<?php
$url = $_GET['url'];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
 
$data = curl_exec($ch);
$status = curl_getinfo($ch);
curl_close($ch);

echo $data
?>
