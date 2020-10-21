<?php
	$HTML->setHTMLTitle("Seite nicht gefunden");

    $HTML->build();
    header("HTTP/1.0 404 Not Found");
    echo $HTML->ausgabe();
?>