<?php

if($_REQUEST['MASTERKEY'] == $RUNTIME['SYSTEMKEY'])
{
    $dockerClient = new Docker();
    $allContainers = $dockerClient->getAllContainers();
    
    foreach($allContainers as $thisContainer)
    {
        $apiKey	= calcAPIKey($thisContainer);
    
        echo trim(ltrim($thisContainer['Names']['0'], '/'))." == ".$apiKey."<br>\n";
    }
    die();
}

die("ACCESS DENIED!");



?>