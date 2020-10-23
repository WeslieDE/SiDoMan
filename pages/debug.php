<?php

    $dockerClient = new Docker();
    $allContainers = $dockerClient->getAllContainers();
    
    print_r($allContainers);

?>