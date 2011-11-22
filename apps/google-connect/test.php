<?php

$settings = explode("\n", file_get_contents('../../_priv/test.txt'));
$app_key = trim($settings[0]);
$app_secret = trim($settings[1]);

echo 'Key is ' . $app_key . ', Secret is ' . $app_secret . ']';

?>

