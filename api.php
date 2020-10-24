<?php
	date_default_timezone_set("Europe/Berlin");
	header('Strict-Transport-Security: max-age=657000');
	error_reporting(E_ALL);

	include_once("classen/helper.php");
	include_once("classen/docker.php");

	if(isset($_REQUEST['CONTAINER']) || isset($_REQUEST['KEY']))
	{
		if(trim($_REQUEST['CONTAINER']) != "" && trim($_REQUEST['KEY']) != "")
		{
			$dockerClient = new Docker();
			$allContainers = $dockerClient->getAllContainers();
			$container = NULL;

			foreach($allContainers as $thisContainer)
			{
				if($thisContainer['Id'] == trim($_REQUEST['CONTAINER']) || trim($_REQUEST['CONTAINER']) == ltrim($thisContainer['Names']['0'], '/'))
				{
					if(isset($thisContainer['Labels']['remotepass']))
					{
						if(trim($thisContainer['Labels']['remotepass']) == trim($_REQUEST['KEY']))
						{
							$container = $thisContainer;
						}
					}
				}	
			}
		}
	}

	if($container == NULL)
		die("access denied!");

	if(isset($_REQUEST['METODE']))
	{
		if(trim($_REQUEST['METODE']) != "")
		{
			if(strtoupper($_REQUEST['METODE']) == "START")
			{
				$dockerClient->startContainer($container['Id']);
				echo "DONE";
			}
			
			if(strtoupper($_REQUEST['METODE']) == "STOP")
			{
				$dockerClient->stopContainer($container['Id']);
				echo "DONE";
			}

			if(strtoupper($_REQUEST['METODE']) == "KILL")
			{
				$dockerClient->killContainer($container['Id']);
				echo "DONE";
			}

			if(strtoupper($_REQUEST['METODE']) == "RESTART")
			{
				$dockerClient->killContainer($container['Id']);
				$dockerClient->startContainer($container['Id']);
				echo "DONE";
			}

			if(strtoupper($_REQUEST['METODE']) == "STATE")
			{
				echo $container['Status'];
			}

			if(strtoupper($_REQUEST['METODE']) == "LOG")
			{
				$logOutput = $dockerClient->getContainerLogs($container['Id']);
				echo clean($logOutput);
			}

			if(strtoupper($_REQUEST['METODE']) == "COMMAND")
			{
				if(isset($_REQUEST['COMMAND']))
				{
					if(trim($_REQUEST['COMMAND']) != "")
					{
						$filename = "/tmp/command".time().".txt";
						$command = trim($_REQUEST['COMMAND']);

						file_put_contents($filename, $command."\n");

						system('cat '.$filename.' | socat EXEC:"docker attach '.$container['Id'].'",pty STDIN');
						unlink($filename);
						echo "DONE";
					}
				}
			}
		}
	}

?>