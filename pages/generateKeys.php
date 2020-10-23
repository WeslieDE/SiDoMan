<?php

if($_REQUEST['MASTERKEY'] != $RUNTIME['SYSTEMKEY'])
    die("ACCESS DENIED!");
    
$dockerClient = new Docker();
$allContainers = $dockerClient->getAllContainers();

foreach($allContainers as $thisContainer)
{
    $apiKey	= calcAPIKey($thisContainer);

    echo $thisContainer['Name']." == ".$apiKey."<br>\n";
}

?>