<?php
require_once('filestore.php');
session_start();

if (!isset($_POST['username']) || !isValidName($_POST['username'])) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 You must specify a valid username', true, 500);
    die();
}

$store = new FileStore('storage', false);

$username = $_POST['username'];
$userstore = $store->open($username);
if (!$userstore->exists('.user')) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 401 Wrong username or password', true, 401);
    die();
}

$userdata = $userstore->read('.user');
if ($userdata['password'] != calcHash($userdata['salt'] . $username . $_POST['password'])) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 401 Wrong username or password', true, 401);
    die();
}

$_SESSION['username'] = $username;
unset($userdata['password']);
unset($userdata['salt']);
header('Content-Type: application/json');
echo json_encode($userdata);
?>
