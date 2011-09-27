<!DOCTYPE HTML>
<?php
require_once('geshi/geshi.php');

$basePath = $_SERVER['SCRIPT_FILENAME'];
$basePath = substr($basePath, 0, strpos($basePath, '/viewsource'));

$srcPath = $_GET['src'];
if (strpos($srcPath, '..')>0)
    die();
$srcPath = $basePath . $srcPath;

$content = file_get_contents($srcPath);
if (strlen($content)>2 && substr($content, 0, 3)=="\xef\xbb\xbf")
    $content = substr($content, 3, strlen($content)-3);
$content = preg_replace('/    /m', ' ', $content);
$content = preg_replace('/\t/m', ' ', $content);

$syntax = new GeSHi($content, 'html4strict');
$syntax->enable_classes();
$syntax->enable_keyword_links(false);

?>
<html>
<head>
<title>View Source</title>
<meta name="viewport" content="user-scalable=yes, width=device-width" />
<link rel="stylesheet" href="../style.css" type="text/css" />
<style>
<?php echo $syntax->get_stylesheet(); ?>
</style>
</head>
<body>

<?php echo $syntax->parse_code(); ?>

</body>
</html>

