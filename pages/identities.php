<?php
	$HTML->setHTMLTitle("Identitäten");
	$HTML->importSeitenInhalt("pages/HTML/identities.html");

    $statementCreateTable = $RUNTIME['PDO']->prepare("CREATE TABLE IF NOT EXISTS `UserIdentitys` (`PrincipalID` VARCHAR(38) NOT NULL, `IdentityID` VARCHAR(38) NOT NULL, PRIMARY KEY (`IdentityID`))"); 
    $statementCreateTable->execute(); 

    $statementCheckForEntry = $RUNTIME['PDO']->prepare("SELECT * FROM UserIdentitys WHERE PrincipalID = ? LIMIT 1");
    $statementCheckForEntry->execute(array($_SESSION['UUID'])); 

    if($statementCheckForEntry->rowCount() == 0)
    {
        $statement = $RUNTIME['PDO']->prepare('INSERT INTO `UserIdentitys` (PrincipalID, IdentityID) VALUES (:PrincipalID, :IdentityID)'); 
        $statement->execute(['PrincipalID' => $_SESSION['UUID'], 'IdentityID' => $_SESSION['UUID']]);
    }

    if(isset($_REQUEST['enableIdent']) || @$_REQUEST['enableIdent'] != "")
    {
        if(isset($_REQUEST['newuuid']) || @$_REQUEST['newuuid'] != "")
        {
            $statement = $RUNTIME['PDO']->prepare("SELECT * FROM UserIdentitys WHERE PrincipalID = :PrincipalID AND IdentityID = :IdentityID LIMIT 1");
            $statement->execute(['PrincipalID' => $_SESSION['UUID'], 'IdentityID' => $_REQUEST['newuuid']]); 

            $statementPresence = $RUNTIME['PDO']->prepare("SELECT * FROM Presence WHERE UserID = :PrincipalID LIMIT 1");
            $statementPresence->execute(['PrincipalID' => $_SESSION['UUID']]); 

            if($statementPresence->rowCount() == 0)
            {
                if($statement->rowCount() == 1)
                {
                    $statementAuth = $RUNTIME['PDO']->prepare('UPDATE auth SET UUID = :IdentityID WHERE UUID = :PrincipalID'); 
                    $statementAuth->execute(['IdentityID' => $_REQUEST['newuuid'], 'PrincipalID' => $_SESSION['UUID']]);
    
                    $statementUserIdentitys = $RUNTIME['PDO']->prepare('UPDATE UserIdentitys SET PrincipalID = :IdentityID WHERE PrincipalID = :PrincipalID'); 
                    $statementUserIdentitys->execute(['IdentityID' => $_REQUEST['newuuid'], 'PrincipalID' => $_SESSION['UUID']]);
    
                    $statementFriends = $RUNTIME['PDO']->prepare('UPDATE Friends SET PrincipalID = :IdentityID WHERE PrincipalID = :PrincipalID'); 
                    $statementFriends->execute(['IdentityID' => $_REQUEST['newuuid'], 'PrincipalID' => $_SESSION['UUID']]);
    
                    $statementReFriends = $RUNTIME['PDO']->prepare('UPDATE Friends SET Friend = :IdentityID WHERE Friend = :PrincipalID'); 
                    $statementReFriends->execute(['IdentityID' => $_REQUEST['newuuid'], 'PrincipalID' => $_SESSION['UUID']]);
    
                    $statementInventoryFolders = $RUNTIME['PDO']->prepare('UPDATE inventoryfolders SET agentID = :IdentityID WHERE agentID = :PrincipalID AND type != :InventarTyp'); 
                    $statementInventoryFolders->execute(['IdentityID' => $_REQUEST['newuuid'], 'PrincipalID' => $_SESSION['UUID'], 'InventarTyp' => 46]);
    
                    $statementInventoryItems = $RUNTIME['PDO']->prepare('UPDATE inventoryitems SET avatarID = :IdentityID WHERE avatarID = :PrincipalID'); 
                    $statementInventoryItems->execute(['IdentityID' => $_REQUEST['newuuid'], 'PrincipalID' => $_SESSION['UUID']]);
    
                    $statementGroupMembership = $RUNTIME['PDO']->prepare('UPDATE os_groups_membership SET PrincipalID = :IdentityID WHERE PrincipalID = :PrincipalID'); 
                    $statementGroupMembership->execute(['IdentityID' => $_REQUEST['newuuid'], 'PrincipalID' => $_SESSION['UUID']]);
    
                    $statementGroupRoles = $RUNTIME['PDO']->prepare('UPDATE os_groups_rolemembership SET PrincipalID = :IdentityID WHERE PrincipalID = :PrincipalID'); 
                    $statementGroupRoles->execute(['IdentityID' => $_REQUEST['newuuid'], 'PrincipalID' => $_SESSION['UUID']]);
    
                    $statementGroupRoles = $RUNTIME['PDO']->prepare('DELETE FROM Presence WHERE UserID = :PrincipalID'); 
                    $statementGroupRoles->execute(['PrincipalID' => $_SESSION['UUID']]);
    
                    $_SESSION['LOGIN'] = 'false';
                    session_destroy();
    
                    header("Location: index.php?page=identities");
                    die();
                }
            }else{
                $HTML->ReplaceSeitenInhalt("%%MESSAGE%%", '<div class="alert alert-danger" role="alert">Du kannst die Identität nicht ändern, während du angemeldet bist. Bitte schließe den Viewer.</div>'); 
            }
        }
    }

    if(isset($_REQUEST['createIdent']) || @$_REQUEST['createIdent'] != "")
    {
        if(isset($_REQUEST['newName']) || @$_REQUEST['newName'] != "")
        {
            $avatarNameParts = explode(" ", trim($_REQUEST['newName']));

            if(count($avatarNameParts) == 2)
            {
                $statement = $RUNTIME['PDO']->prepare("SELECT * FROM UserAccounts WHERE FirstName = :FirstName AND LastName = :LastName LIMIT 1");
                $statement->execute(['FirstName' => trim($avatarNameParts[0]), 'LastName' => trim($avatarNameParts[1])]); 
    
                if($statement->rowCount() == 0)
                {
                    $avatarUUID = $RUNTIME['OPENSIM']->gen_uuid();

                    $statementAccounts = $RUNTIME['PDO']->prepare('INSERT INTO UserAccounts (PrincipalID, ScopeID, FirstName, LastName, Email, ServiceURLs, Created, UserLevel, UserFlags, UserTitle, active) VALUES (:PrincipalID, :ScopeID, :FirstName, :LastName, :Email, :ServiceURLs, :Created, :UserLevel, :UserFlags, :UserTitle, :active )'); 
					$statementAccounts->execute(['PrincipalID' => $avatarUUID, 'ScopeID' => "00000000-0000-0000-0000-000000000000", 'FirstName' => $avatarNameParts[0], 'LastName' => $avatarNameParts[1], 'Email' => $_SESSION['EMAIL'], 'ServiceURLs' => "HomeURI= GatekeeperURI= InventoryServerURI= AssetServerURI= ", 'Created' => time(), 'UserLevel' => 0, 'UserFlags' => 0, 'UserTitle' => "", 'active' => 1]);
                    //print_r($statementAccounts->errorInfo());

                    $statementUserIdentitys = $RUNTIME['PDO']->prepare('INSERT INTO UserIdentitys (PrincipalID, IdentityID) VALUES (:PrincipalID, :IdentityID)'); 
                    $statementUserIdentitys->execute(['PrincipalID' => $_SESSION['UUID'], 'IdentityID' => $avatarUUID]);
                    //print_r($statementUserIdentitys->errorInfo());
                }else{
                    $HTML->ReplaceSeitenInhalt("%%MESSAGE%%", '<div class="alert alert-danger" role="alert">Dieser Name ist schon in Benutzung.</div>'); 
                }
            }else{
                $HTML->ReplaceSeitenInhalt("%%MESSAGE%%", '<div class="alert alert-danger" role="alert">Der Name muss aus einem Vor und einem Nachnamen bestehen.</div>'); 
            }
        }
    }

    $table = '<table class="table"><thead><tr><th scope="col">Name</th><th scope="col">Aktionen</th></thead><tbody>%%ENTRY%%</tbody></table>';
    $statement = $RUNTIME['PDO']->prepare("SELECT * FROM UserIdentitys WHERE PrincipalID = ? ORDER BY IdentityID ASC");
    $statement->execute(array($_SESSION['UUID'])); 

    while($row = $statement->fetch()) 
    {
        if($row['IdentityID'] == $_SESSION['UUID'])
        {
            $entry = '<tr><td>'.trim($RUNTIME['OPENSIM']->getUserName($row['IdentityID'])).' <span class="badge badge-info">Aktiv</span></td><td>-</td></tr>';
        }else{
            $entry = '<tr><td>'.trim($RUNTIME['OPENSIM']->getUserName($row['IdentityID'])).'</td><td><form action="index.php?page=identities" method="post"><input type="hidden" name="newuuid" value="'.$row['IdentityID'].'"><button type="submit" name="enableIdent" class="btn btn-success btn-sm">Aktievieren</button></form></td></tr>';
        }

        $table = str_replace("%%ENTRY%%", $entry."%%ENTRY%%", $table);
    }

    $table = str_replace("%%ENTRY%%", "", $table);
    $HTML->ReplaceSeitenInhalt("%%IDENT-LIST%%", $table);
    $HTML->ReplaceSeitenInhalt("%%link%%", ' '); 
    $HTML->ReplaceSeitenInhalt("%%MESSAGE%%", ' '); 
    
    $HTML->build();
    echo $HTML->ausgabe();
?>