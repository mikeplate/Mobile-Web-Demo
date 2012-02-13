<?php
require_once('filestore.php');
require_once('config.php');

session_start();
if (!isset($_SESSION['username'])) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 401 Wrong username or password', true, 401);
    die();
}

$store = new FileStore($config['root'], false);
$userstore = $store->open($_SESSION['username']);
$userdata = $userstore->read('.user');
unset($userdata['password']);
unset($userdata['salt']);

outputJSON($userdata);
?>

