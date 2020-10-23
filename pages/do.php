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
        $dockerClient->startContainer($thisContainer['Id']);

        if(isset($_REQUEST['do-stop']))
        $dockerClient->stopContainer($thisContainer['Id']);

        if(isset($_REQUEST['do-kill']))
        $dockerClient->killContainer($thisContainer['Id']);

        if(isset($_REQUEST['do-restart']))
        {
            $dockerClient->killContainer($thisContainer['Id']);
            $dockerClient->startContainer($thisContainer['Id']);
        }
        
        include "./pages/dashboard.php";
    }else{
        die("Login is not valid!");
    }
}else{
    die("Access denied!");
}

?>