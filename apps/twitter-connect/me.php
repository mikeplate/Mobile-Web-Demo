<?php
require_once('oauth/tmhOAuth.php');
require_once('oauth/tmhUtilities.php');
session_start();
header('Content-type: application/json');

$app_key = 'UtMHvTNBDlABBxDKmPh5w';
$app_secret = file_get_contents('../../_priv/twitter-app-secret.txt');

$oauth = new tmhOAuth(Array(
  'consumer_key' => $app_key,
  'consumer_secret' => $app_secret,
  'user_token' => $_SESSION['access_token']['oauth_token'],
  'user_secret' => $_SESSION['access_token']['oauth_token_secret'],
//  'host' => 'api.twitter.com'
));

$code = $oauth->request('GET', $oauth->url('1/account/verify_credentials'));
if ($code != 200) {
    echo json_encode(Array('success' => false, 'error' => 'Error when verifying credentials'));
    die();
}

$response = $oauth->response['response'];
echo $response;
?>

