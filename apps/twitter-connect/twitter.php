<?php
require_once('oauth/tmhOAuth.php');
require_once('oauth/tmhUtilities.php');
require_once('../../_priv/twitter-app-secret.php');
session_start();
header('Content-type: application/json');

$params = array(
    'oauth_callback' => tmhUtilities::php_self()
);
// $params['x_auth_access_type'] = 'write';

$oauth = new tmhOAuth(Array(
    'consumer_key' => $app_key,
    'consumer_secret' => $app_secret,
    'host' => 'api.twitter.com'
));

// This script will load under any of the following circumstances:
// 1. A page is requesting the url to redirect to for starting the Twitter auth process.
// 2. The user has approved access to our app in the Twitter confirmation page.
// 3. The user has denied access to our app in the Twitter confirmation page.

// 1. A page is requesting the url to redirect to for starting the Twitter auth process.
if (!isset($_GET['oauth_verifier']) && !isset($_GET['denied'])) {
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
// 2. The user has approved access to our app in the Twitter confirmation page.
else if (isset($_GET['oauth_verifier'])) {
    $oauth->config['user_token'] = $_SESSION['oauth']['oauth_token'];
    $oauth->config['user_secret'] = $_SESSION['oauth']['oauth_token_secret'];

    $code = $oauth->request('POST', $oauth->url('oauth/access_token', ''), array(
        'oauth_verifier' => $_GET['oauth_verifier']
    ));
    if ($code != 200) {
        echo json_encode(Array('success' => false, 'error' => 'Error when verifying access to Twitter'));
        die();
    }

    $_SESSION['twitter_access_token'] = $oauth->extract_params($oauth->response['response']);
    header('Location: index.html');
}
// 3. The user has denied access to our app in the Twitter confirmation page.
else if (isset($_GET['denied'])) {
    $_SESSION['twitter_access_token'] = '';
    header('Location: index.html');
}
else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad request', true, 400);
    die();
}
?>

