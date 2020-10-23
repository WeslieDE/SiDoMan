<?php
print_r($_SESSION);

if(isset($_SESSION['LOGIN']))
{
    if($_SESSION['LOGIN'] == 'true')
    {
        $dockerClient = new Docker();
        $allContainers = $dockerClient->getAllContainers();
        $container = NULL;

        echo ("/".$_SESSION['CONATINER']);

        foreach($allContainers as $thisContainer)
        {
            echo $thisContainer['Id']." == ".$thisContainer['Names'][0]."\n";

            if($thisContainer['Id'] == $_SESSION['CONATINER'] || $thisContainer['Names'][0] == ("/".$_SESSION['CONATINER']))
                $container = $thisContainer;
        }

        if($container == NULL)
            die("unknown container");
        
        $HTML = new HTML();
        $HTML->setHTMLTitle($container->Names[0]);
        $HTML->importHTML("style/default/dashboard.html");

        $HTML->ReplaceSeitenInhalt("%%ContainerName%%", $container->Names[0]); 
        $HTML->ReplaceSeitenInhalt("%%UserAPIKey%%", $container->id); 

        $HTML->build();
        echo $HTML->ausgabe();
    }else{
        die("Login is not valid!");
    }
}else{
    die("Access denied!");
}


?>