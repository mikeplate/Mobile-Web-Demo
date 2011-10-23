<?php
require_once('oauth/tmhOAuth.php');
require_once('oauth/tmhUtilities.php');
session_start();
header('Content-type: application/json');

$app_key = 'UtMHvTNBDlABBxDKmPh5w';
$app_secret = file_get_contents('../../_priv/twitter-app-secret.txt');

$params = array(
    'oauth_callback' => tmhUtilities::php_self()
);
// $params['x_auth_access_type'] = 'write';

$oauth = new tmhOAuth(Array(
    'consumer_key' => $app_key,
    'consumer_secret' => $app_secret,
    'host' => 'api.twitter.com'
));

if (isset($_GET['denied'])) {
    $_SESSION['access_token'] = '';
    header('Location: index.html');
}
elseif (!isset($_GET['oauth_verifier'])) {
    $code = $oauth->request('POST', $oauth->url('oauth/request_token', ''), $params);
    if ($code != 200) {
        echo json_encode(Array('success' => false, 'error' => 'Error when retrieving request token from Twitter'));
        die();
    }

    $_SESSION['oauth'] = $oauth->extract_params($oauth->response['response']);
    $oauth_token = $_SESSION['oauth']['oauth_token'];
    $authurl = $oauth->url('oauth/authenticate', '') . "?oauth_token={$oauth_token}";

    echo json_encode(Array('success' => true, 'url' => $authurl));
    die();
}
else {
    $oauth->config['user_token'] = $_SESSION['oauth']['oauth_token'];
    $oauth->config['user_secret'] = $_SESSION['oauth']['oauth_token_secret'];

    $code = $oauth->request('POST', $oauth->url('oauth/access_token', ''), array(
        'oauth_verifier' => $_GET['oauth_verifier']
    ));
    if ($code != 200) {
        echo json_encode(Array('success' => false, 'error' => 'Error when verifying access to Twitter'));
        die();
    }

    $_SESSION['access_token'] = $oauth->extract_params($oauth->response['response']);
    header('Location: index.html');
}
?>

