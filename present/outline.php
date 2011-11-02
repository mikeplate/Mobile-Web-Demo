<!DOCTYPE html>
<?php
require_once('texttohtml.php');
$file = '0';
if (strlen($_SERVER['QUERY_STRING'])>0)
    $file = $_SERVER['QUERY_STRING'];
$content = file_get_contents($file . '.json');
$content = json_decode($content);
?>
<html>
    <head>
        <title><?php echo $content->title ?></title>
        <link rel="stylesheet" href="outline.css" />
    </head>
    <body>
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
            echo "<abbr>$slideNo</abbr>";
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
    </body>
</html>

