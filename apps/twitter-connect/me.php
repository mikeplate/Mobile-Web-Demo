<?php
require_once('oauth/tmhOAuth.php');
require_once('oauth/tmhUtilities.php');
require_once('../../_priv/twitter-app-secret.php');
session_start();
header('Content-type: application/json');

$oauth = new tmhOAuth(Array(
  'consumer_key' => $app_key,
  'consumer_secret' => $app_secret,
  'user_token' => $_SESSION['twitter_access_token']['oauth_token'],
  'user_secret' => $_SESSION['twitter_access_token']['oauth_token_secret'],
  'host' => 'api.twitter.com'
));

$code = $oauth->request('GET', $oauth->url('1/account/verify_credentials'));
if ($code != 200) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Error when verifying credentials', true, 403);
    die();
}

$response = $oauth->response['response'];
echo $response;
?>

