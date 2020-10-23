<?php

function clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
 
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
 }

if(isset($_SESSION['LOGIN']))
{
    if($_SESSION['LOGIN'] == 'true')
    {
        $dockerClient = new Docker();
        $allContainers = $dockerClient->getAllContainers();
        $container = NULL;

        foreach($allContainers as $thisContainer)
        {
            if($thisContainer['Id'] == $_SESSION['CONATINER'] || $thisContainer['Names'][0] == ("/".$_SESSION['CONATINER']))
                $container = $thisContainer;
        }

        if($container == NULL)
            die("unknown container");
        
        $logOutput = $dockerClient->getContainerLogs($_SESSION['CONATINER']);

        $HTML = new HTML();
        $HTML->setHTMLTitle(ltrim($container['Names']['0'], '/'));
        $HTML->importHTML("style/default/dashboard.html");

        $HTML->ReplaceLayoutInhalt("%%ContainerName%%", ltrim($container['Names']['0'], '/')); 
        $HTML->ReplaceLayoutInhalt("%%UserAPIKey%%", $container['Id']); 
        $HTML->ReplaceLayoutInhalt("%%ContainerLogOutput%%", clean(html_entity_decode($logOutput))); 

        $HTML->build();
        echo $HTML->ausgabe();
    }else{
        die("Login is not valid!");
    }
}else{
    die("Access denied!");
}


?>