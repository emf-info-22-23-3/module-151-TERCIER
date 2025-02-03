<?php
        require('Wrk.php');
        
        $wrk = new Wrk();

        $equipes = $wrk->getEquipes();
        $conteur = 1;
        $reponce = '';

          //echo '<tr><td>'.$conteur.'</td><td>'.$equipe.'</td></tr>';
          if ($_GET['action'] == "equipe") {
            $equipes = $wrk->getEquipes();
            
            // Iterate through the list of teams
            foreach($equipes as $index => $equipe){
                $reponce .= '{"nom":"'.$equipe['nom'].'","id":"'.$equipe['pk_equipe'].'"}';
                if ($index < count($equipes) - 1) {
                    $reponce .= ','; // Add comma between items, but not after the last one
                }
            }
    
            $reponce2 = '[' . $reponce . ']'; // Wrap the response in an array
            echo $reponce2;
        }
          
?>