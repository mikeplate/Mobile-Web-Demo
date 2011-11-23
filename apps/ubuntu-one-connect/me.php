<?php
session_start();

if (!isset($_SESSION['ubuntu_auth'])) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 No auth token set', true, 403);
    die();
}
$values = $_SESSION['ubuntu_auth'];

$url = 'https://one.ubuntu.com/api/account/';
$oauth = new OAuth($values['consumer_key'], $values['consumer_secret'], OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
$oauth->enableDebug();
$oauth->enableSSLChecks();
$oauth->setToken($values['token'], $values['token_secret']);
try {
    $oauth->fetch($url, Array('Referer' => 'http://' . $_SERVER['HTTP_HOST']), 'GET');
}
catch (Exception $e) {
var_dump($e);
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Error from Ubuntu One', true, 403);
    die();
}
$response = $oauth->getLastResponse();

header('Content-type: application/json');
echo $response;
?>

