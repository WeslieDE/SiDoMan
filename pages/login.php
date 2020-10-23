<?php
	$HTML = new HTML();
	$HTML->setHTMLTitle("Login");
	$HTML->importHTML("style/default/login.html");
	
	if(isset($_POST['login']))
	{
		if(!isset($_POST['username']) || !isset($_POST['password']))
		{
			$HTML->ReplaceLayoutInhalt("%%LOGINMESSAGE%%", "Bitte gebe Benutzername und Passwort an."); 
		}else{
			$statement = $RUNTIME['PDO']->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
			$statement->execute(array(trim($_POST['username']))); 

			while($row = $statement->fetch()) 
			{
				if(md5($_POST['password']) == $row['password'])
				{
					$_SESSION['USERNAME'] = $row['username'];
					$_SESSION['DISPLAYNAME'] = strtoupper($row['username']);
					$_SESSION['PREAUTH'] = 'true';
					//$_SESSION['LOGIN'] = 'true';

					include "pages/2faktor-login.php";
					die();
				}
			}

			$HTML->ReplaceLayoutInhalt("%%LOGINMESSAGE%%", "Benutzername oder Passwort falsch."); 
		}
	}

	if(isset($_REQUEST['2fakey']))
	{
		include "pages/2faktor-login.php";
		die();
	}
	
	if(file_exists("./pages/".@$_REQUEST['page'].".php"))
		$HTML->ReplaceLayoutInhalt("%%PAGENAME%%", @$_REQUEST['page']); 

	$HTML->ReplaceLayoutInhalt("%%LOGINMESSAGE%%", ""); 
	

	$HTML->build();
	echo $HTML->ausgabe();
?>