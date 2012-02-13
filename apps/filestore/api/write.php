<?php
require_once('filestore.php');
require_once('config.php');

session_start();
if (!isset($_SESSION['username'])) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 401 Wrong username or password', true, 401);
    die();
}
if (!isset($_REQUEST['name']) || !isValidName($_REQUEST['name']) || $_REQUEST['name'][0]=='.') {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 You must specify a valid name', true, 500);
    die();
}

$store = new FileStore($config['root'], false);
$userstore = $store->open($_SESSION['username']);

if ((count($_GET)==2 && isset($_GET['name']) && isset($_GET['value']))
    || (count($_POST)==2 && isset($_POST['name']) && isset($_POST['value']))) {
    $userstore->write($_REQUEST['name'], json_decode($_REQUEST['value']));
}
else if (count($_GET)==1 && isset($_GET['name'])) {
    $userstore->write($_GET['name'], $_POST);
}
else {
    $userstore->write($_GET['name'], $_GET);
}

outputJSON(Array('success' => true));
?>

