<?php
	$HTML->setHTMLTitle("Online Anzeige");
	$HTML->importSeitenInhalt("pages/HTML/online-anzeige.html");

    $table = '<table class="table"><thead><tr><th scope="col">Benutzername</th><th scope="col">Region</th></thead><tbody>%%ENTRY%%</tbody></table>';
    
    $statement = $RUNTIME['PDO']->prepare("SELECT * FROM Presence ORDER BY RegionID ASC");
    $statement->execute(); 

    while($row = $statement->fetch()) 
    {
        if($row['RegionID'] != "00000000-0000-0000-0000-000000000000")
        {
            $entry = '<tr><td>'.trim($RUNTIME['OPENSIM']->getUserName($row['UserID'])).'</td><td>'.$RUNTIME['OPENSIM']->getRegionName($row['RegionID']).'</td></tr>';
            $table = str_replace("%%ENTRY%%", $entry."%%ENTRY%%", $table);
        }
    }

    $table = str_replace("%%ENTRY%%", "", $table);
    $HTML->ReplaceSeitenInhalt("%%ONLINE-LIST%%", $table);

    $HTML->build();
    echo $HTML->ausgabe();
?>