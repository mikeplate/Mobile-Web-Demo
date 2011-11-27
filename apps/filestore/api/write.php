<?php
require_once('filestore.php');

session_start();
if (!isset($_SESSION['username'])) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 401 Wrong username or password', true, 401);
    die();
}
if (!isset($_REQUEST['name']) || !isValidName($_REQUEST['name']) || $_REQUEST['name'][0]=='.') {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 You must specify a valid name', true, 500);
    die();
}

$store = new FileStore('storage', false);
$userstore = $store->open($_SESSION['username']);
$userstore->write($_REQUEST['name'], json_decode($_REQUEST['value']));

outputJSON(Array('success' => true));
?>

