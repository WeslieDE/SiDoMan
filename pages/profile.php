<?php
    $HTML->setHTMLTitle("Dein Profile");
    $HTML->importSeitenInhalt("pages/HTML/profile.html");

    if(isset($_REQUEST['formInputFeldVorname']) || @$_REQUEST['formInputFeldVorname'] != "")
    {
        $NewFirstName = trim($_REQUEST['formInputFeldVorname']);

        if($NewFirstName != "")
        {
            if($_SESSION['FIRSTNAME'] != $NewFirstName)
            {
                $statement = $RUNTIME['PDO']->prepare('UPDATE UserAccounts SET FirstName = :FirstName WHERE PrincipalID = :PrincipalID'); 
                $statement->execute(['FirstName' => $NewFirstName, 'PrincipalID' => $_SESSION['UUID']]);
                $_SESSION['FIRSTNAME'] = $NewFirstName;
                $_SESSION['USERNAME'] = $_SESSION['FIRSTNAME']." ".$_SESSION['LASTNAME'];
                $_SESSION['DISPLAYNAME'] = strtoupper($_SESSION['USERNAME']);
            }
        }
    }

    if(isset($_REQUEST['formInputFeldNachname']) || @$_REQUEST['formInputFeldNachname'] != "")
    {
        $NewLastName = trim($_REQUEST['formInputFeldNachname']);

        if($NewLastName != "")
        {
            if($_SESSION['LASTNAME'] != $NewLastName)
            {
                $statement = $RUNTIME['PDO']->prepare('UPDATE UserAccounts SET LastName = :LastName WHERE PrincipalID = :PrincipalID'); 
                $statement->execute(['LastName' => $NewLastName, 'PrincipalID' => $_SESSION['UUID']]);
                $_SESSION['LASTNAME'] = $NewLastName;
                $_SESSION['USERNAME'] = $_SESSION['FIRSTNAME']." ".$_SESSION['LASTNAME'];
                $_SESSION['DISPLAYNAME'] = strtoupper($_SESSION['USERNAME']);
            }
        }
    }

    if(isset($_REQUEST['formInputFeldEMail']) || @$_REQUEST['formInputFeldEMail'] != "")
    {
        $NewEMail = trim($_REQUEST['formInputFeldEMail']);

        if($NewEMail != "")
        {
            if($_SESSION['EMAIL'] != $NewEMail)
            {
                $statement = $RUNTIME['PDO']->prepare('UPDATE UserAccounts SET Email = :Email WHERE PrincipalID = :PrincipalID'); 
                $statement->execute(['Email' => $NewEMail, 'PrincipalID' => $_SESSION['UUID']]);

                $statement = $RUNTIME['PDO']->prepare('UPDATE usersettings SET email = :Email WHERE useruuid = :PrincipalID'); 
                $statement->execute(['Email' => $NewEMail, 'PrincipalID' => $_SESSION['UUID']]);

                $_SESSION['EMAIL'] = $NewEMail;
            }
        }
    }

    if(isset($_REQUEST['formInputFeldOfflineIM']) || @$_REQUEST['formInputFeldOfflineIM'] != "")
    {
        $NewOfflineIM = trim($_REQUEST['formInputFeldOfflineIM']);

        if($NewOfflineIM != "")
        {
            if($NewOfflineIM == "on" || $NewOfflineIM == "true")
            {
                $statement = $RUNTIME['PDO']->prepare('UPDATE usersettings SET imviaemail = :IMState WHERE useruuid = :PrincipalID'); 
                $statement->execute(['IMState' => 'true', 'PrincipalID' => $_SESSION['UUID']]);
            }
        }
    }else if(!isset($_REQUEST['formInputFeldOfflineIM']) && isset($_REQUEST['saveProfileData'])){
        $statement = $RUNTIME['PDO']->prepare('UPDATE usersettings SET imviaemail = :IMState WHERE useruuid = :PrincipalID'); 
        $statement->execute(['IMState' => 'false', 'PrincipalID' => $_SESSION['UUID']]);
    }

    if(isset($_REQUEST['formInputFeldPartnerName']) || @$_REQUEST['formInputFeldPartnerName'] != "")
    {
        $NewPartner = trim($_REQUEST['formInputFeldPartnerName']);
        $CurrentPartner = $RUNTIME['OPENSIM']->getPartner($_SESSION['UUID']);

        if($CurrentPartner != "")$CurrentPartner = $RUNTIME['OPENSIM']->getUserName($CurrentPartner);

        if($NewPartner != "")
        {
            if($CurrentPartner != $NewPartner)
            {
                $newPartnerUUID = $RUNTIME['OPENSIM']->getUserUUID($NewPartner);

                if($newPartnerUUID != null)
                {
                    $statement = $RUNTIME['PDO']->prepare('UPDATE userprofile SET profilePartner = :profilePartner WHERE useruuid = :PrincipalID'); 
                    $statement->execute(['profilePartner' => $newPartnerUUID, 'PrincipalID' => $_SESSION['UUID']]);
                }
            }
        }else{
            $statement = $RUNTIME['PDO']->prepare('UPDATE userprofile SET profilePartner = :profilePartner WHERE useruuid = :PrincipalID'); 
            $statement->execute(['profilePartner' => '00000000-0000-0000-0000-000000000000', 'PrincipalID' => $_SESSION['UUID']]);
        }
    }


    $statementLocalUsers = $RUNTIME['PDO']->prepare("SELECT * FROM UserAccounts ORDER BY PrincipalID ASC");
    $statementLocalUsers->execute(); 

    $allUsers = "";
    while($row = $statementLocalUsers->fetch()) 
    {
        $name = '"'.@$row['FirstName']." ".@$row['LastName'].'"';

        if($allUsers != "")
        {
            $allUsers .= ",".$name;
        }else{
            $allUsers .= $name;
        } 
    }

    $allUsers .= '," "';

    $PartnerUUID = $RUNTIME['OPENSIM']->getPartner($_SESSION['UUID']);
    $PartnerName = "";

    if($PartnerUUID != null)$PartnerName = $RUNTIME['OPENSIM']->getUserName($PartnerUUID);

    if($RUNTIME['OPENSIM']->allowOfflineIM($_SESSION['UUID']) == "TRUE")$HTML->ReplaceSeitenInhalt("%%offlineIMSTATE%%", ' checked'); 

    $HTML->ReplaceSeitenInhalt("%%offlineIMSTATE%%", ' '); 
    $HTML->ReplaceSeitenInhalt("%%firstname%%", $_SESSION['FIRSTNAME']); 
    $HTML->ReplaceSeitenInhalt("%%lastname%%", $_SESSION['LASTNAME']); 
    $HTML->ReplaceSeitenInhalt("%%partner%%", $PartnerName); 
    $HTML->ReplaceSeitenInhalt("%%email%%", $RUNTIME['OPENSIM']->getUserMail($_SESSION['UUID'])); 
    $HTML->ReplaceSeitenInhalt("%%listAllResidentsAsJSArray%%", ""); 
    $HTML->ReplaceSeitenInhalt("%%INFOMESSAGE%%", ' ');
    $HTML->ReplaceSeitenInhalt("%%INFOMESSAGE%%", ' '); 

    $HTML->build();
    echo $HTML->ausgabe();
?>