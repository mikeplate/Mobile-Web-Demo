<?php
session_start();
header('Content-type: application/json');

if (!isset($_REQUEST['username']) || !isset($_REQUEST['password'])) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Must specify username and password', true, 403);
    die();
}
$username = $_REQUEST['username'];
$password = $_REQUEST['password'];

$authurl = 'https://login.ubuntu.com/api/1.0/authentications?ws.op=authenticate';
$authurl .= '&token_name=' . urlencode('Ubuntu One @ MobileAppLab');

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $authurl);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
$response = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
if ($status == 200) {
    $values = json_decode($response, TRUE);
    $authurl = 'https://one.ubuntu.com/oauth/sso-finished-so-get-tokens/' . $username;
    $oauth = new OAuth($values['consumer_key'], $values['consumer_secret'], OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
    $oauth->enableDebug();
    $oauth->enableSSLChecks();
    $oauth->setToken($values['token'], $values['token_secret']);
    try {
        $oauth->fetch($authurl);
    }
    catch (Exception $e) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Error from sso-finished-so-get-tokens', true, 403);
        die();
    }

    $_SESSION['ubuntu_auth'] = $values;
    header('Content-type: application/json');
    echo '{ "success": true }';
}
else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 401 Error when logging in', true, 401);
    die();
}
?>

