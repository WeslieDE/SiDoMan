<?php
	if(!isset($_REQUEST['code']))
		die("MISSING INVITE CODE!");

	$statementInviteCode = $RUNTIME['PDO']->prepare("SELECT * FROM InviteCodes WHERE InviteCode = ? LIMIT 1");
	$statementInviteCode->execute([@$_REQUEST['code']]); 

	if($statementInviteCode->rowCount() != 0)
	{
		$RUNTIME['REGISTER']['Name']	=	null;
		$RUNTIME['REGISTER']['PASS']	=	null;
		$RUNTIME['REGISTER']['EMAIL']	=	null;
		$RUNTIME['REGISTER']['AVATAR']	=	null;
		$RUNTIME['REGISTER']['TOS']		=	false;
	
		if(isset($_REQUEST['tos']) || @$_REQUEST['tos'] != "")
		{
			$RUNTIME['REGISTER']['TOS'] = true;
		}

	    if(isset($_REQUEST['username']) || @$_REQUEST['username'] != "")
		{
			$name = trim($_REQUEST['username']);

			if($name != "")
			{
				$nameParts = explode(" ", $name);

				if(count($nameParts) == 1)
				{
					$name .= " Resident";
					$nameParts = explode(" ", $name);
				}
					
				if(count($nameParts) <= 2)
				{
					$statementAvatarName = $RUNTIME['PDO']->prepare("SELECT * FROM UserAccounts WHERE FirstName = :FirstName AND LastName = :LastName LIMIT 1");
					$statementAvatarName->execute(['FirstName' => $nameParts[0], 'LastName' => $nameParts[1]]); 

					if($statementAvatarName->rowCount() == 0)
					{
						$RUNTIME['REGISTER']['Name']	=	$name;
					}
				}		
			}
		}

		if(isset($_REQUEST['password']) || @$_REQUEST['password'] != "")
		{
			$pass = trim($_REQUEST['password']);

			if($pass != "")
			{
				$RUNTIME['REGISTER']['PASS']	=	$pass;	
			}
		}

		if(isset($_REQUEST['email']) || @$_REQUEST['email'] != "")
		{
			$email = trim($_REQUEST['email']);

			if($email != "")
			{
				$RUNTIME['REGISTER']['EMAIL']	=	$email;	
			}
		}

		if(isset($_REQUEST['avatar']) || @$_REQUEST['avatar'] != "")
		{
			$avatar = trim($_REQUEST['avatar']);

			if($avatar != "")
			{
				if(isset($RUNTIME['DEFAULTAVATAR'][$avatar]['UUID']))
				{
					$RUNTIME['REGISTER']['AVATAR']	=	$avatar;
				}
			}
		}

		$HTML = new HTML();
		$HTML->setHTMLTitle("Registrieren");
		$HTML->importHTML("style/login/register.html");

		if(isset($_REQUEST['doRegister']) || @$_REQUEST['doRegister'] != "")
		{
			if($RUNTIME['REGISTER']['TOS'] == true)
			{
				if($RUNTIME['REGISTER']['AVATAR'] != null && $RUNTIME['REGISTER']['EMAIL'] != null && $RUNTIME['REGISTER']['PASS'] != null && $RUNTIME['REGISTER']['Name'] != null && $RUNTIME['REGISTER']['TOS'] == true)
				{
					$avatarUUID = $RUNTIME['OPENSIM']->gen_uuid();
					$passwordSalt = md5($avatarUUID.time());
					$passwordHash = md5(md5($RUNTIME['REGISTER']['PASS']).":".$passwordSalt);
					$avatarNameParts = explode(" ", $RUNTIME['REGISTER']['Name']);
	
					$statementAuth = $RUNTIME['PDO']->prepare('INSERT INTO `auth` (`UUID`, `passwordHash`, `passwordSalt`, `webLoginKey`, `accountType`) VALUES (:UUID, :HASHVALUE, :SALTVALUE, :WEBKEY, :ACCTYPE)'); 
					$statementAuth->execute(['UUID' => $avatarUUID, 'HASHVALUE' => $passwordHash, 'SALTVALUE' => $passwordSalt, 'WEBKEY' => "00000000-0000-0000-0000-000000000000", 'ACCTYPE' => "UserAccount"]);
	
					$statementAccounts = $RUNTIME['PDO']->prepare('INSERT INTO `UserAccounts` (`PrincipalID`, `ScopeID`, `FirstName`, `LastName`, `Email`, `ServiceURLs`, `Created`, `UserLevel`, `UserFlags`, `UserTitle`, `active`) VALUES (:PrincipalID, :ScopeID, :FirstName, :LastName, :Email, :ServiceURLs, :Created, :UserLevel, :UserFlags, :UserTitle, :active )'); 
					$statementAccounts->execute(['PrincipalID' => $avatarUUID, 'ScopeID' => "00000000-0000-0000-0000-000000000000", 'FirstName' => $avatarNameParts[0], 'LastName' => $avatarNameParts[1], 'Email' => $RUNTIME['REGISTER']['EMAIL'], 'ServiceURLs' => "HomeURI= GatekeeperURI= InventoryServerURI= AssetServerURI= ", 'Created' => time(), 'UserLevel' => 0, 'UserFlags' => 0, 'UserTitle' => "", 'active' => 1]);
	
					$statementProfile = $RUNTIME['PDO']->prepare('INSERT INTO `userprofile` (`useruuid`, `profilePartner`, `profileImage`, `profileFirstImage`) VALUES (:useruuid, :profilePartner, :profileImage, :profileFirstImage)'); 
					$statementProfile->execute(['useruuid' => $avatarUUID, 'profilePartner' => "00000000-0000-0000-0000-000000000000", 'profileImage' => "00000000-0000-0000-0000-000000000000", 'profileFirstImage' => "00000000-0000-0000-0000-000000000000"]);
	
					$Inventory 				= array('Calling Cards' => 2, 'Objects' => 6, 'Landmarks' => 3, 'Clothing' => 5, 'Gestures' => 21, 'Body Parts' => 13, 'Textures' =>  0, 'Scripts' => 10, 'Photo Album' => 15, 'Lost And Found' => 16, 'Trash' => 14, 'Notecards' =>  7, 'My Inventory' =>  8, 'Sounds' =>  1, 'Animations' => 20);
					$InventoryRootFolder 	= $RUNTIME['OPENSIM']->gen_uuid();
	
					foreach ($Inventory as $FolderName => $InventoryType)
					{
						$FolderUUID = $RUNTIME['OPENSIM']->gen_uuid();
	
						if ($InventoryType == 8)
						{
							$FolderUUID = $InventoryRootFolder;
							$FolderParent = "00000000-0000-0000-0000-000000000000";
						}else{
							$FolderParent = $InventoryRootFolder;
						}
	
						$statementInventoryFolder = $RUNTIME['PDO']->prepare('INSERT INTO `inventoryfolders` (`folderName`, `type`, `version`, `folderID`, `agentID`, `parentFolderID`) VALUES (:folderName, :folderTyp, :folderVersion, :folderID, :agentID, :parentFolderID)'); 
						$statementInventoryFolder->execute(['agentID' => $avatarUUID, 'folderName' => $FolderName, 'folderTyp' => $InventoryType, 'folderVersion' => 1, 'folderID' => $FolderUUID, 'parentFolderID' => $FolderParent]);
					}
	
					$statementInviteDeleter = $RUNTIME['PDO']->prepare('DELETE FROM InviteCodes WHERE InviteCode = :code'); 
					$statementInviteDeleter->execute(['code' => $_REQUEST['code']]);
	
					$_SESSION['USERNAME'] = trim($RUNTIME['REGISTER']['Name']);
					$_SESSION['FIRSTNAME'] = trim($avatarNameParts[0]);
					$_SESSION['LASTNAME'] = trim($avatarNameParts[1]);
					$_SESSION['EMAIL'] = trim($RUNTIME['REGISTER']['EMAIL']);
					$_SESSION['PASSWORD'] = $passwordHash;
					$_SESSION['SALT'] = $passwordSalt;
					$_SESSION['UUID'] = $avatarUUID;
					$_SESSION['LEVEL'] = 0;
					$_SESSION['DISPLAYNAME'] = strtoupper(trim($RUNTIME['REGISTER']['Name']));
					$_SESSION['LOGIN'] = 'true';
					include "./pages/dashboard.php";
					die();
				}else{
					$HTML->ReplaceLayoutInhalt("%%MESSAGE%%", "Ups da stimmt was nicht. Versuche es bitte noch mal."); 
				}
			}else{
				$HTML->ReplaceLayoutInhalt("%%MESSAGE%%", "Du musst die Nutzungsbedingungen lesen und Akzeptieren."); 
			}
		}
		

		$HTML->ReplaceLayoutInhalt("%%MESSAGE%%", ""); 
		$HTML->ReplaceLayoutInhalt("%%tosURL%%", $RUNTIME['TOOLS']['TOS'] ); 
		$HTML->ReplaceLayoutInhalt("%%INVCODE%%", $_REQUEST['code']); 
	
		$HTML->build();
		echo $HTML->ausgabe();
		die();

	}else{
		die("INVALID INVITE CODE!");
	}
?>