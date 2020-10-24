<?php
    $HTML = new HTML();
    $HTML->importHTML("style/default/error.html");

	$HTML->setHTMLTitle("Error 404");

    $HTML->build();
    header("HTTP/1.0 404 Not Found");
    echo $HTML->ausgabe();
?>