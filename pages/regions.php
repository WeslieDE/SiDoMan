<?php
	$HTML->setHTMLTitle("Deine Regionen");
	$HTML->importSeitenInhalt("pages/HTML/deine-regionen.html");

    $table = '<table class="table"><thead><tr><th scope="col">Region Name</th><th scope="col">Eigentümer</th><th scope="col">Position</th><th scope="col">Aktionen</th></thead><tbody>%%ENTRY%%</tbody></table>';

    if(@$_SESSION['LEVEL'] > 100 && @$_REQUEST['SHOWALL'] == "1")
    {
        $statement = $RUNTIME['PDO']->prepare("SELECT * FROM regions ORDER BY owner_uuid ASC");
        $statement->execute(array($_SESSION['UUID'])); 
    }else{
        $statement = $RUNTIME['PDO']->prepare("SELECT * FROM regions WHERE owner_uuid = ? ORDER BY uuid ASC");
        $statement->execute(array($_SESSION['UUID'])); 
    }

    $statement->execute(array($_SESSION['UUID'])); 

    while($row = $statement->fetch()) 
    {
        $entry = '<tr><td>'.$row['regionName'].'</td><td>'.$RUNTIME['OPENSIM']->getUserName($row['owner_uuid']).'</td><td>'.fillString(($row['locX'] / 256), 4).' / '.fillString(($row['locY'] / 256), 4).'</td><td>TELEPORT | LÖSCHEN</td></tr>';       
        $table = str_replace("%%ENTRY%%", $entry."%%ENTRY%%", $table);
    }

    $table = str_replace("%%ENTRY%%", "", $table);
    $HTML->ReplaceSeitenInhalt("%%REGION-LIST%%", $table);

    $HTML->build();
    echo $HTML->ausgabe();
?>