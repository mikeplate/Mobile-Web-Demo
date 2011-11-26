<?php
require_once('filestore.php');

session_start();
if (!isset($_SESSION['username'])) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 401 Wrong username or password', true, 401);
    die();
}
if (!isset($_POST['name']) || !isValidName($_POST['name']) || $_POST['name'][0]=='.') {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 You must specify a valid name', true, 500);
    die();
}

$store = new FileStore('storage', false);
$userstore = $store->open($_SESSION['username']);
$userstore->write($_POST['name'], json_decode($_POST['value']));

outputJSON(Array('success' => true));
?>

