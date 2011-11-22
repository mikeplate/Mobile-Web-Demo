<?php
require_once('../../_priv/google-app-secret.php');
session_start();
header('Content-type: application/json');

$callbackUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

// This script will load under any of the following circumstances:
// 1. A page is requesting the url to redirect to for starting the auth process.
// 2. The user has approved access to our app in the confirmation page.
// 3. The user has denied access to our app in the confirmation page.

// 1. A page is requesting the url to redirect to for starting the auth process.
if (!isset($_GET['code']) && !isset($_GET['error'])) {
    $authurl = 'https://accounts.google.com/o/oauth2/auth?client_id=' . $app_key;
    $authurl .= '&redirect_uri=' . urlencode($callbackUrl);
    $authurl .= '&response_type=code&scope=' . urlencode('https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile');
    echo json_encode(Array('success' => true, 'url' => $authurl));
    die();
}
// 2. The user has approved access to our app in the confirmation page.
else if (isset($_GET['code'])) {
    $authurl = 'https://accounts.google.com/o/oauth2/token';
    $authpost = 'client_id=' . $app_key;
    $authpost .= '&redirect_uri=' . urlencode($callbackUrl);
    $authpost .= '&client_secret=' . urlencode($app_secret);
    $authpost .= '&code=' . urlencode($_GET['code']);
    $authpost .= '&grant_type=authorization_code';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $authurl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $authpost);
    $response = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if ($status == 200) {
        $values = json_decode($response, TRUE);
        $_SESSION['google_access_token'] = $values['access_token'];
        header('Location: index.html');
    }
    else {
        echo 'Error when getting auth token from Google (' . $status . ')';
    }
}
// 3. The user has denied access to our app in the confirmation page.
else if (isset($_GET['error'])) {
    unset($_SESSION['google_access_token']);
    header('Location: index.html');
}
else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad request', true, 400);
    die();
}
?>

