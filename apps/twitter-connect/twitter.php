<?php
require_once('oauth1.php');
require_once('../../_priv/twitter-app-secret.php');
session_start();
header('Content-type: application/json');

// This script will load under any of the following circumstances:
// 1. A page is requesting the url to redirect to for starting the Twitter auth process.
// 2. The user has approved access to our app in the Twitter confirmation page.
// 3. The user has denied access to our app in the Twitter confirmation page.

// 1. A page is requesting the url to redirect to for starting the Twitter auth process.
if (oAuth1Step()=='start') {
    $oauthresult = oAuth1Start('https://api.twitter.com', $app_key, $app_secret);
    if ($oauthresult == NULL) {
        echo json_encode(Array('success' => false, 'error' => 'Error when starting oauth with Twitter'));
        die();
    }

    $_SESSION['twitter_secret'] = $oauthresult['secret'];
    echo json_encode(Array('success' => true, 'url' => $oauthresult['url']));
}
// 2. The user has approved access to our app in the Twitter confirmation page.
else if (oAuth1Step()=='end') {
    $previouslySetSecret = $_SESSION['twitter_secret'];
    $oauthresult = oAuth1End('https://api.twitter.com', $app_key, $app_secret, $previouslySetSecret);
    if ($oauthresult == NULL) {
        echo json_encode(Array('success' => false, 'error' => 'Error when verifying access to Twitter'));
        die();
    }
    $_SESSION['twitter_access_token'] = $oauthresult;
    header('Location: index.html');
}
// 3. The user has denied access to our app in the Twitter confirmation page.
else if (oAuth1Step()=='denied') {
    $_SESSION['twitter_access_token'] = '';
    header('Location: index.html');
}
else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad request', true, 400);
}
?>
