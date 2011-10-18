<?php
if (isset($_GET['force'])) {
    $force = $_GET['force'];
    setcookie('force', $force);
}
else {
    $force = $_COOKIE['force'];
}

require_once 'mobile-detect.php';
$device = new Mobile_Detect();
if (($device->isMobile() || $force=='mobile') && $force!='desktop') {
    header('Location: diff-sites-mobile.html', true, 302);
}
else {
    header('Location: diff-sites-desktop.html', true, 302);
}
?>

