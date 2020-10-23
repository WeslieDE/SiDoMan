<?php

function clean($string)
{
    $string = str_replace("", "", $string);
    $string = str_replace("\r", "", $string);
    return preg_replace('#\\x1b[[][^A-Za-z]*[A-Za-z]#', '', $string);
}

function calcAPIKey($container)
{
    global $RUNTIME;

    return md5($RUNTIME['SYSTEMKEY'].md5($container['Names'][0]));
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

        $HTML->ReplaceLayoutInhalt("%%ContainerName%%", trim(ltrim($container['Names']['0'], '/'))); 
        $HTML->ReplaceLayoutInhalt("%%UserAPIKey%%", calcAPIKey($container)); 
        $HTML->ReplaceLayoutInhalt("%%ContainerLogOutput%%", html_entity_decode(clean($logOutput))); 

        $HTML->build();
        echo $HTML->ausgabe();
    }else{
        die("Login is not valid!");
    }
}else{
    die("Access denied!");
}


?>