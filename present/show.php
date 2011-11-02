<!DOCTYPE html>
<?php
require_once('texttohtml.php');

$file = '0';
if (strlen($_SERVER['QUERY_STRING'])>0)
    $file = $_SERVER['QUERY_STRING'];
$content = file_get_contents($file . '.json');
$content = json_decode($content);
?>
<html class="no-js" lang="en">
    <head>
        <meta name="viewport" content="width=1024" />
        <meta charset="utf-8" />
        <title><?php echo $content->title ?></title>
        <link rel="stylesheet" href="deckjs/core/deck.core.css">
        <!--link rel="stylesheet" id="style-theme-link" href="deckjs/themes/style/web-2.0.css"-->
        <link rel="stylesheet" id="style-theme-link" href="paper.css">
        <link rel="stylesheet" id="transition-theme-link" href="deckjs/themes/transition/horizontal-slide.css">
        <link rel="stylesheet" href="deckjs/extensions/hash/deck.hash.css">
        <link rel="stylesheet" href="show.css">
        <script src="deckjs/modernizr.custom.js"></script>
    </head>
    <body class="deck-container">
        <section class="slide" id="slide0">
            <?php
            echo "<h1>$content->title";
            if (isset($content->subtitle))
                echo "<br /><span class=\"subtitle\">$content->subtitle</span>";
            echo "</h1>";
            ?>
        </section>
        <?php
            $slideNo = 0;
            foreach ($content->slides as $slide) {
                $slideNo++;
        ?>
        <section class="slide" id="slide<?php echo $slideNo ?>">
            <?php
            if (!isset($slide->bullets)) {
                echo "<h1>$slide->title";
                if (isset($slide->subtitle))
                    echo "<br /><span class=\"subtitle\">$slide->subtitle</span>";
                echo "</h1>";
            }
            else {
                echo "<h2>$slide->title</h2>";
                echo isset($slide->numbers) ? "<ol>":"<ul>";
                foreach ($slide->bullets as $bullet) {
                    if (is_array($bullet)) {
                        echo "<li>";
                        echo textToHtml($bullet[0]);
                        echo "<ul>";
                        for ($i = 1; $i < count($bullet); $i++)
                            echo "<li>" . textToHtml($bullet[$i]) . "</li>";
                        echo "</ul>";
                        echo "</li>";
                    }
                    else {
                        echo "<li>" . textToHtml($bullet) . "</li>";
                    }
                }
                echo isset($slide->numbers) ? "</ol>":"</ul>";
            }
            ?>
        </section>
        <?php } ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
        <script src="deckjs/core/deck.core.js"></script>
        <script src="deckjs/extensions/hash/deck.hash.js"></script>
        <script>
        $(function() {
            $.deck(".slide");
        });
        </script>
    </body>
</html>

