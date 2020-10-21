<?php
	$HTML->setHTMLTitle("Dashboard");
	$HTML->importSeitenInhalt("pages/HTML/dashboard.html");

	$HTML->ReplaceSeitenInhalt("%%GLOBAL-USER-COUNT%%", $RUNTIME['OPENSIM']->getUserCount()); 
	$HTML->ReplaceSeitenInhalt("%%GLOBAL-REGION-COUNT%%", $RUNTIME['OPENSIM']->getRegionCount()); 

	$HTML->ReplaceLayoutInhalt("%%USERNAME%%", $_SESSION['DISPLAYNAME']); 

	$HTML->build();
	echo $HTML->ausgabe();
?>