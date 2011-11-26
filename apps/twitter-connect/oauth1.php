<?php
function oAuth1Step() {
    if (!isset($_GET['oauth_token']) && !isset($_GET['denied'])) {
        return 'start';
    }
    else if (isset($_GET['oauth_token'])) {
        return 'end';
    }
    else {
        return 'denied';
    }
}

if (!extension_loaded('oauth')) {
    require_once('oauth/tmhOAuth.php');
    require_once('oauth/tmhUtilities.php');

    function oAuth1Start($url, $appkey, $appsecret) {
        $callbackUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        $params = array(
            'oauth_callback' => $callbackUrl
        );
        // $params['x_auth_access_type'] = 'write';
        $oauth = new tmhOAuth(Array(
            'consumer_key' => $appkey,
            'consumer_secret' => $appsecret
        ));
        $code = $oauth->request('POST', $url . '/oauth/request_token', $params);
        if ($code != 200) 
            return NULL;

        $tokens = $oauth->extract_params($oauth->response['response']);
        $result = Array();
        $result['secret'] = $tokens['oauth_token_secret'];
        $result['url'] = $url . '/oauth/authenticate' . "?oauth_token={$tokens['oauth_token']}";
        return $result;
    }

    function oAuth1End($url, $appkey, $appsecret, $secret) {
        $oauth = new tmhOAuth(Array(
            'consumer_key' => $appkey,
            'consumer_secret' => $appsecret
        ));
        $oauth->config['user_token'] = $_GET['oauth_token'];
        $oauth->config['user_secret'] = $secret;
        $code = $oauth->request('POST', $url . '/oauth/access_token', 
            isset($_GET['oauth_verifier']) ? array('oauth_verifier' => $_GET['oauth_verifier']):NULL
        );
        if ($code != 200)
            return NULL;

        return $oauth->extract_params($oauth->response['response']);
    }

    function oAuth1Get($url, $appkey, $appsecret, $access) {
        $oauth = new tmhOAuth(Array(
          'consumer_key' => $appkey,
          'consumer_secret' => $appsecret,
          'user_token' => $access['oauth_token'],
          'user_secret' => $access['oauth_token_secret']
        ));
        $code = $oauth->request('GET', $url);
        if ($code != 200)
            return NULL;

        return $oauth->response['response'];
    }
}
else {
    function oAuth1Start($url, $appkey, $appsecret) {
        $callbackUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        $oauth = new OAuth($appkey, $appsecret, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
        $oauth->enableSSLChecks();
        $tokens = $oauth->getRequestToken($url . '/oauth/request_token', $callbackUrl);
        $result = Array();
        $result['secret'] = $tokens['oauth_token_secret'];
        $result['url'] = $url . '/oauth/authenticate' . "?oauth_token={$tokens['oauth_token']}";
        return $result;
    }

    function oAuth1End($url, $appkey, $appsecret, $secret) {
        $oauth = new OAuth($appkey, $appsecret, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
        $oauth->enableSSLChecks();
        $oauth->setToken($_GET['oauth_token'], $secret);
        try {
            $response = $oauth->getAccessToken($url . '/oauth/access_token');
        }
        catch (Exception $e) {
            return NULL;
        }
        return $response;
    }

    function oAuth1Get($url, $appkey, $appsecret, $access) {
        $oauth = new OAuth($appkey, $appsecret, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
        $oauth->enableSSLChecks();
        $oauth->setToken($access['oauth_token'], $access['oauth_token_secret']);
        try {
            $oauth->fetch($url, NULL, 'GET');
        }
        catch (Exception $e) {
            return NULL;
        }
        return $oauth->getLastResponse();
    }
}
?>

