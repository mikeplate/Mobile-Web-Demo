<?php
require_once('filestore.php');
require_once('config.php');

if (!isset($_POST['username']) || !isValidName($_POST['username'])) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 You must specify a valid username', true, 500);
    die();
}
if (!isset($_POST['password']) || strlen($_POST['password'])<$config['min_password_length'] || $_POST['username']==$_POST['password']) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 You must specify a valid password', true, 500);
    die();
}

$store = new FileStore($config['root'], false);
$username = $_POST['username'];
$userstore = $store->open($username);
if ($userstore->exists('.user')) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Username already exists', true, 500);
    die();
}

$salt = sha1(rand());
$userdata = Array(
    'username' => $username,
    'password' => calcHash($salt . $username . $_POST['password']),
	'salt' => $salt,
    'email' => isset($_POST['email']) ? $_POST['email'] : '',
    'ip' => $_SERVER['REMOTE_ADDR'],
    'useragent' => $_SERVER['HTTP_USER_AGENT'],
    'created' => date('c')
);
$userstore->write('.user', $userdata);

unset($userdata['password']);
unset($userdata['salt']);
header('Content-Type: application/json');
echo json_encode($userdata);
?>
