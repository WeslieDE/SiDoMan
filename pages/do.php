<?php
if(isset($_SESSION['LOGIN']))
{
    if($_SESSION['LOGIN'] == 'true')
    {
        $dockerClient = new Docker();
        
        $allContainers = $dockerClient->getAllContainers();
        $container = NULL;

        foreach($allContainers as $thisContainer)
            if($thisContainer['Id'] == $_SESSION['CONTAINER'] || $_SESSION['CONTAINER'] == ltrim($thisContainer['Names']['0'], '/'))
                $container = $thisContainer;

        if($container == NULL)
            die("unknown container");

        if(isset($_REQUEST['do-start']))
            $dockerClient->startContainer($container['Id']);

        if(isset($_REQUEST['do-stop']))
            $dockerClient->stopContainer($container['Id']);

        if(isset($_REQUEST['do-kill']))
            $dockerClient->killContainer($container['Id']);

        if(isset($_REQUEST['do-restart']))
        {
            $dockerClient->killContainer($container['Id']);
            $dockerClient->startContainer($container['Id']);
        }
        
        if(isset($_REQUEST['do-command']))
        {
            if(isset($_REQUEST['commandtext']))
            {
                $filename = "/tmp/command".time().".txt";
                $command = trim($_REQUEST['commandtext']);

                if($command != "")
                {
                    file_put_contents($filename, $command."\n");

                    system('cat '.$filename.' | socat EXEC:"docker attach '.$container['Id'].'",pty STDIN');
                }
            }
        }

        header("Location: index.php");
        die();
    }else{
        die("Login is not valid!");
    }
}else{
    die("Access denied!");
}

?>