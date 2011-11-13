<?php

function textToHtml($text) {
    $text = htmlspecialchars($text);
    $text = str_replace("\n", '<br />', $text);
    $text = str_replace("\t", '&nbsp;&nbsp;', $text);
    $text = preg_replace('/(https?:\/\/[A-Za-z0-9\/\.~#-]+)\.?/', "<a target=\"_blank\" href=\"$1\">$1</a>", $text);
    $text = preg_replace('/([a-z0-9\.]+@[a-z0-9\.]+)\.?/', "<a href=\"mailto:$1\">$1</a>", $text);
    $text = preg_replace('/([^a-z]@)([a-z0-9\.]+)\.?/', "$1<a target=\"_blank\" href=\"http://twitter.com/$2\">$2</a>", $text);
    return $text;
}
?>

