<?php
	if(isset($_POST['do-login']))
	{
		if(isset($_POST['containername']) || isset($_POST['accesskey']))
		{
			$dockerClient = new Docker();
			$allContainers = $dockerClient->getAllContainers();
			$container = NULL;

			foreach($allContainers as $thisContainer)
			{
				if(trim($_POST['containername']) == trim(ltrim($thisContainer['Names']['0'], '/')))
				{
					if(isset($thisContainer['Labels']['remotepass']))
					{
						if(trim($thisContainer['Labels']['remotepass']) == trim($_POST['accesskey']))
						{
							$container = $thisContainer;
						}
					}
				}
			}

			if($container != NULL)
			{
				$_SESSION['LOGIN'] = "true";
				$_SESSION['CONTAINER'] = trim(ltrim($container['Names']['0'], '/'));

				include "./pages/dashboard.php";
				die();
			}
		}
	}

	$HTML = new HTML();
	$HTML->setHTMLTitle("Login");
	$HTML->importHTML("style/default/login.html");
	$HTML->build();
	echo $HTML->ausgabe();
?>