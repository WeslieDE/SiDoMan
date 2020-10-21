<?php

    $HTML->setHTMLTitle("Passwort ändern");
    $HTML->importSeitenInhalt("pages/HTML/profile.html");

    if(isset($_REQUEST['oldPassword']) || @$_REQUEST['oldPassword'] != "")
    {
        $OLDPassword = trim($_REQUEST['oldPassword']);

        if($OLDPassword != "")
        {
            if(md5(md5($OLDPassword).":".$_SESSION['SALT']) == $_SESSION['PASSWORD'])
            {
                if(isset($_REQUEST['newPassword']) || @$_REQUEST['newPassword'] != "")
                {
                    $NewPassword = trim($_REQUEST['newPassword']);
            
                    if($NewPassword != "")
                    {
                        if(isset($_REQUEST['newPasswordRepeate']) || @$_REQUEST['newPasswordRepeate'] != "")
                        {
                            $NewPasswordRepeate = trim($_REQUEST['newPasswordRepeate']);
                    
                            if($NewPasswordRepeate != "")
                            {
                                if($NewPasswordRepeate == $NewPassword)
                                {
                                    $statement = $RUNTIME['PDO']->prepare('UPDATE auth SET passwordHash = :PasswordHash WHERE UUID = :PrincipalID'); 
                                    $statement->execute(['PasswordHash' => md5(md5($NewPassword).":".$_SESSION['SALT']), 'PrincipalID' => $_SESSION['UUID']]);
                                    $_SESSION['PASSWORD'] = md5(md5($NewPassword).":".$_SESSION['SALT']);
                                    $HTML->ReplaceSeitenInhalt("%%INFOMESSAGE%%", 'Neues Passwort gespeichert.'); 
                                }else{
                                    $HTML->ReplaceSeitenInhalt("%%INFOMESSAGE%%", 'Passwörter stimmen nicht überein!'); 
                                }
                            }else{
                                $HTML->ReplaceSeitenInhalt("%%INFOMESSAGE%%", 'Bitte gib das Passwort zur bestätigung noch einmal ein!'); 
                            }
                        }else{
                            $HTML->ReplaceSeitenInhalt("%%INFOMESSAGE%%", 'Bitte gib das Passwort zur bestätigung noch einmal ein!'); 
                        }
                    }else{
                        $HTML->ReplaceSeitenInhalt("%%INFOMESSAGE%%", 'Bitte gebe ein neues Passwort ein!'); 
                    }
                }else{
                    $HTML->ReplaceSeitenInhalt("%%INFOMESSAGE%%", 'Bitte gebe ein neues Passwort ein!'); 
                }
            }else{
                $HTML->ReplaceSeitenInhalt("%%INFOMESSAGE%%", 'Das alte Passwort ist nicht richtig!'); 
            }
        }else{
            $HTML->ReplaceSeitenInhalt("%%INFOMESSAGE%%", 'Gebe bitte dein Passwort ein.');
        }
    }

    $PartnerName = "";
    $PartnerUUID = $RUNTIME['OPENSIM']->getPartner($_SESSION['UUID']);
    if($PartnerUUID != null)$PartnerName = $RUNTIME['OPENSIM']->getUserName($PartnerUUID);

    $HTML->ReplaceSeitenInhalt("%%offlineIMSTATE%%", ' '); 
    $HTML->ReplaceSeitenInhalt("%%firstname%%", $_SESSION['FIRSTNAME']); 
    $HTML->ReplaceSeitenInhalt("%%lastname%%", $_SESSION['LASTNAME']); 
    $HTML->ReplaceSeitenInhalt("%%partner%%", $PartnerName); 
    $HTML->ReplaceSeitenInhalt("%%email%%", $RUNTIME['OPENSIM']->getUserMail($_SESSION['UUID'])); 
    $HTML->ReplaceSeitenInhalt("%%listAllResidentsAsJSArray%%", ""); 
    $HTML->ReplaceSeitenInhalt("%%INFOMESSAGE%%", ' ');
    $HTML->ReplaceSeitenInhalt("%%INFOMESSAGE%%", ' '); 
    $HTML->ReplaceSeitenInhalt("%%INFOMESSAGE%%", ' '); 
    
    $HTML->build();
    echo $HTML->ausgabe();
?>