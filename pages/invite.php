<?php
    if(@$_SESSION['LEVEL'] < 100)
    {
        $HTML->setHTMLTitle("Kein Zugriff");
        $HTML->SetSeitenInhalt("Dazu hast du keine Rechte!");
        $HTML->build();
        echo $HTML->ausgabe();
        die();
    }

	$HTML->setHTMLTitle("Benutzer");
	$HTML->importSeitenInhalt("pages/HTML/users.html");


    $HTML->ReplaceSeitenInhalt("%%link%%", ' '); 
    
    $HTML->build();
    echo $HTML->ausgabe();
?>