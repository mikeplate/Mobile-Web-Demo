<?php
require_once('../../_priv/twitter-app-secret.php');
require_once('oauth1.php');
session_start();
header('Content-type: application/json');

$response = oAuth1Get('https://api.twitter.com/1/account/verify_credentials.json', 
    $app_key, $app_secret, $_SESSION['twitter_access_token']);
    
if ($response == NULL) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Error when verifying credentials', true, 403);
    die();
}

echo $response;
?>

