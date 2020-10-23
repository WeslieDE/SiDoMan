<?php
if(isset($_SESSION['LOGIN']))
{
    if($_SESSION['LOGIN'] == 'true')
    {
        $dockerClient = new Docker();
        $allContainers = $dockerClient->getAllContainers();
        $container = NULL;

        foreach($allContainers as $thisContainer)
        {
            if($thisContainer['Id'] == $_SESSION['CONTAINER'] || $_SESSION['CONTAINER'] == ltrim($thisContainer['Names']['0'], '/'))
                $container = $thisContainer;
        }

        if($container == NULL)
            die("unknown container");

        $logOutput = $dockerClient->getContainerLogs($_SESSION['CONTAINER']);

        $HTML = new HTML();
        $HTML->setHTMLTitle(ltrim($container['Names']['0'], '/'));
        $HTML->importHTML("style/default/dashboard.html");

        $HTML->ReplaceLayoutInhalt("%%ContainerName%%", trim(ltrim($container['Names']['0'], '/'))); 
        $HTML->ReplaceLayoutInhalt("%%UserAPIKey%%", calcAPIKey($container)); 
        $HTML->ReplaceLayoutInhalt("%%ContainerLogOutput%%", html_entity_decode(clean($logOutput))); 
        $HTML->ReplaceLayoutInhalt("%%STATUS%%", html_entity_decode($container['Status'])); 

        $HTML->build();
        echo $HTML->ausgabe();
    }else{
        die("Login is not valid!");
    }
}else{
    die("Access denied!");
}


?>