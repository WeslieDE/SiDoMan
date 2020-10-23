<?php

echo "Print all login data to STDOUT... ";


$dockerClient = new Docker();
$allContainers = $dockerClient->getAllContainers();

foreach($allContainers as $thisContainer)
{
    $apiKey	= calcAPIKey($thisContainer);

    fwrite(STDOUT, $thisContainer['Name']." == ".$apiKey);
}

echo "done."
?>