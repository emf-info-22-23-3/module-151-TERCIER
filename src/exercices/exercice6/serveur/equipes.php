
      <?php
        require('Wrk.php');
        
        $wrk = new Wrk();

        $equipes = $wrk->getEquipes();
        $conteur = 1;
        
          //echo '<tr><td>'.$conteur.'</td><td>'.$equipe.'</td></tr>';
          if ($_GET['action'] == "equipe") {
            echo json_encode($wrk->getEquipes());
        }
          
        

        
      ?>
      