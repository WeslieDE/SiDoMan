<?php
	$HTML = new HTML();
	$HTML->setHTMLTitle("Login");
	$HTML->importHTML("style/login/login.html");
	
	if(isset($_POST['login']))
	{
		if(!isset($_POST['username']) || !isset($_POST['password']))
		{
			$HTML->ReplaceLayoutInhalt("%%LOGINMESSAGE%%", "Bitte gebe Benutzername und Passwort an."); 
		}else{
			$statementUser = $RUNTIME['PDO']->prepare("SELECT * FROM UserAccounts WHERE FirstName = ? AND LastName = ? LIMIT 1");
			$statementUser->execute(explode(" ", trim($_POST['username']))); 

			$RUNTIME['MESSAGE']['LOGINERROR'] = "Benutzername nicht gefunden!";

			while($rowUser = $statementUser->fetch()) 
			{
				$statementAuth = $RUNTIME['PDO']->prepare("SELECT * FROM auth WHERE UUID = ? LIMIT 1");
				$statementAuth->execute(array($rowUser['PrincipalID'])); 
				
				$RUNTIME['DEBUG']['LOGIN']['UUID'] = $rowUser['PrincipalID'];

				while($rowAuth = $statementAuth->fetch()) 
				{
					if(md5(md5($_POST['password']).":".$rowAuth['passwordSalt']) == $rowAuth['passwordHash'])
					{
						$_SESSION['USERNAME'] = trim($_POST['username']);
						$_SESSION['FIRSTNAME'] = trim($rowUser['FirstName']);
						$_SESSION['LASTNAME'] = trim($rowUser['LastName']);
						$_SESSION['EMAIL'] = trim($rowUser['Email']);
						$_SESSION['PASSWORD'] = $rowAuth['passwordHash'];
						$_SESSION['SALT'] = $rowAuth['passwordSalt'];
						$_SESSION['UUID'] = $rowUser['PrincipalID'];
						$_SESSION['LEVEL'] = $rowUser['UserLevel'];
						$_SESSION['DISPLAYNAME'] = strtoupper(trim($_POST['username']));
						$_SESSION['LOGIN'] = 'true';

						header("Location: index.php?page=".$_REQUEST['page']);
						die();
					}
				}

				$RUNTIME['MESSAGE']['LOGINERROR'] = "Passwort falsch!";
			}

			$HTML->ReplaceLayoutInhalt("%%LOGINMESSAGE%%", $RUNTIME['MESSAGE']['LOGINERROR']); 
			$HTML->ReplaceLayoutInhalt("%%LASTUSERNAME%%", $_POST['username']); 
		}
	}
	
	if(file_exists("./pages/".@$_REQUEST['page'].".php"))
		$HTML->ReplaceLayoutInhalt("%%PAGENAME%%", @$_REQUEST['page']); 

	$HTML->ReplaceLayoutInhalt("%%LOGINMESSAGE%%", ""); 
	$HTML->ReplaceLayoutInhalt("%%LASTUSERNAME%%", ""); 
	$HTML->ReplaceLayoutInhalt("%%PAGENAME%%", "dashboard"); 

	$HTML->build();
	echo $HTML->ausgabe();
?>