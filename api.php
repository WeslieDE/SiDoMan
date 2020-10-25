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
			$currentContainer = NULL;

			foreach($allContainers as $thisContainer)
			{
				if($thisContainer['Id'] == trim($_REQUEST['CONTAINER']) || trim($_REQUEST['CONTAINER']) == ltrim($thisContainer['Names']['0'], '/'))
				{
					if(isset($thisContainer['Labels']['remotepass']))
					{
						if(trim($thisContainer['Labels']['remotepass']) == trim($_REQUEST['KEY']))
						{
							$currentContainer = $thisContainer;
						}
					}
				}	
			}
		}
	}

	if($currentContainer == NULL)
		die("access denied!");

	if(isset($_REQUEST['METODE']))
	{
		if(trim($_REQUEST['METODE']) != "")
		{
			if(strtoupper($_REQUEST['METODE']) == "START")
			{
				$dockerClient->startContainer($currentContainer['Id']);
				echo "DONE";
			}
			
			if(strtoupper($_REQUEST['METODE']) == "STOP")
			{
				$dockerClient->stopContainer($currentContainer['Id']);
				echo "DONE";
			}

			if(strtoupper($_REQUEST['METODE']) == "KILL")
			{
				$dockerClient->killContainer($currentContainer['Id']);
				echo "DONE";
			}

			if(strtoupper($_REQUEST['METODE']) == "RESTART")
			{
				$dockerClient->killContainer($currentContainer['Id']);
				$dockerClient->startContainer($currentContainer['Id']);
				echo "DONE";
			}

			if(strtoupper($_REQUEST['METODE']) == "STATE")
			{
				echo $container['Status'];
			}

			if(strtoupper($_REQUEST['METODE']) == "LOG")
			{
				$logOutput = $dockerClient->getContainerLogs($currentContainer['Id']);
				echo clean($logOutput);
			}

			if(strtoupper($_REQUEST['METODE']) == "HTMLLOG")
			{
				$logOutput = $dockerClient->getContainerLogs($currentContainer['Id']);
				echo html_entity_decode(clean($logOutput))."\n";
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

						system('cat '.$filename.' | socat EXEC:"docker attach '.$currentContainer['Id'].'",pty STDIN');
						unlink($filename);
						echo "DONE";
					}
				}
			}
		}
	}

?>