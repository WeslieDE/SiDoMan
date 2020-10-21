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

    $statement = $RUNTIME['PDO']->prepare("CREATE TABLE IF NOT EXISTS `InviteCodes` (`InviteCode` VARCHAR(64) NOT NULL, PRIMARY KEY (`InviteCode`))"); 
    $statement->execute();

    if(isset($_REQUEST['generateLink']) || @$_REQUEST['generateLink'] != "")
    {
        $inviteID   =   md5(time().$_SESSION['UUID'].rand(11111, 9999999));
        $link       =   "https://".$_SERVER['SERVER_NAME']."/index.php?page=register&code=".$inviteID;

        $statement = $RUNTIME['PDO']->prepare('INSERT INTO `InviteCodes` (`InviteCode`) VALUES (:InviteCode)'); 
        $statement->execute(['InviteCode' => $inviteID]);

        $HTML->ReplaceSeitenInhalt("%%link%%", $link); 
    }

    $table = '<table class="table"><thead><tr><th scope="col">Vorname</th><th scope="col">Nachname</th><th scope="col">Status</th><th scope="col">Aktionen</th></thead><tbody>%%ENTRY%%</tbody></table>';
    
    $statement = $RUNTIME['PDO']->prepare("SELECT * FROM UserAccounts ORDER BY Created ASC");
    $statement->execute(); 

    while($row = $statement->fetch()) 
    {
        $entry = '<tr><td>'.$row['FirstName'].'</td><td>'.$row['LastName'].'</td><td>'.$row['UserLevel'].'</td><td>PASSWORT ÄNDERN | SPERREN</td></tr>';
        $table = str_replace("%%ENTRY%%", $entry."%%ENTRY%%", $table);
    }

    $table = str_replace("%%ENTRY%%", "", $table);
    $HTML->ReplaceSeitenInhalt("%%REGION-LIST%%", $table);
    $HTML->ReplaceSeitenInhalt("%%link%%", ' '); 

    $HTML->build();
    echo $HTML->ausgabe();
?>