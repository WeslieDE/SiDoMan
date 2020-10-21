<?php
    $HTML->setHTMLTitle("Gruppen");
    $HTML->importSeitenInhalt("pages/HTML/deine-regionen.html");

    $table = '<table class="table"><thead><tr><th scope="col">Name</th><th scope="col">Gründer</th><th scope="col">Aktionen</th></thead><tbody>%%ENTRY%%</tbody></table>';
    
    $statementMembership = $RUNTIME['PDO']->prepare("SELECT * FROM os_groups_membership WHERE PrincipalID = ? ORDER BY GroupID ASC");
    $statementMembership->execute(array($_SESSION['UUID'])); 

    while($rowMembership = $statementMembership->fetch()) 
    {
        $statementGroups = $RUNTIME['PDO']->prepare("SELECT * FROM os_groups_groups WHERE GroupID = ? LIMIT 1");
        $statementGroups->execute(array($rowMembership['GroupID']));

        while($rowGroups = $statementGroups->fetch()) 
        {
            $entry = '<tr><td>'.$rowGroups['Name'].'</td><td>'.$RUNTIME['OPENSIM']->getUserName($rowGroups['FounderID']).'</td><td>VERLASSEN</td></tr>';
            $table = str_replace("%%ENTRY%%", $entry."%%ENTRY%%", $table);
        }
    }

    $table = str_replace("%%ENTRY%%", "", $table);
    $HTML->ReplaceSeitenInhalt("%%REGION-LIST%%", $table);

    $HTML->build();
    echo $HTML->ausgabe();
?>