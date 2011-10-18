<?php
require_once('filestore.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
        <script src="https://jquery-json.googlecode.com/svn/trunk/src/jquery.json.min.js"></script>
        <script src="https://raw.github.com/jquery/jquery-tmpl/master/jquery.tmpl.min.js"></script>
    </head>
    <body>
        <h1>File Store Demo</h1>
<?php

$store = new FileStore('data', false);
$userstore = $store->open('mikeplate');

$userdata = Array('id' => 1, 'name' => 'Mikael Plate', 'updated' => date('c'));
$userstore->write('.user', $userdata);

$data = $userstore->read('.user');
echo '<h1>Read back name: ' . $data['name'] . '</h1>';

for ($i = 0; $i<5; $i++) {
    $data = Array('index' => $i);
    $userstore->write($i, $data);
}

$list = $userstore->contents();
?>
        <script>
        </script>
    </body>
</html>

