<?php
	$HTML->setHTMLTitle("Online Anzeige");
	$HTML->importSeitenInhalt("pages/HTML/online-anzeige.html");

    $table = '<table class="table"><thead><tr><th scope="col">Name</th><th scope="col">Optionen</th></thead><tbody>%%ENTRY%%</tbody></table>';
    
    $statement = $RUNTIME['PDO']->prepare("SELECT * FROM Friends WHERE PrincipalID = ? ORDER BY Friend ASC");
    $statement->execute([$_SESSION['UUID']]); 

    while($row = $statement->fetch()) 
    {
        $PrincipalID = explode(";", $row['PrincipalID'])[0];
        $FriendData = explode(";", $row['Friend']);
        $Friend = $FriendData[0];

        $entry = '<tr><td>'.trim($RUNTIME['OPENSIM']->getUserName($Friend)).'</td><td>LÖSCHEN</td></tr>';

        if(count($FriendData) > 1)
        {
            $FriendData[1] = str_replace("http://", "", $FriendData[1]);
            $FriendData[1] = str_replace("https://", "", $FriendData[1]);
            $FriendData[1] = str_replace("/", "", $FriendData[1]);
            $entry = '<tr><td>'.trim($RUNTIME['OPENSIM']->getUserName($Friend)).' @ '.strtolower($FriendData[1]).'</td><td>LÖSCHEN</td></tr>';

        }

        $table = str_replace("%%ENTRY%%", $entry."%%ENTRY%%", $table);
    }

    $table = str_replace("%%ENTRY%%", "", $table);
    $HTML->ReplaceSeitenInhalt("%%ONLINE-LIST%%", $table);

    $HTML->build();
    echo $HTML->ausgabe();
?>