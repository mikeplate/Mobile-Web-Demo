<?php
require_once('../../_priv/google-app-secret.php');
session_start();

if (!isset($_SESSION['google_access_token'])) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 No auth token set', true, 403);
    die();
}
$access_token = $_SESSION['google_access_token'];

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . urlencode($access_token));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
$response = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
if ($status != 200) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Not validated at Google', true, 403);
    die();
}

header('Content-type: application/json');
echo $response;
?>

