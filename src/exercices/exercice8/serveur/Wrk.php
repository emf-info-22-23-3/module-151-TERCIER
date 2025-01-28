<?php

class Wrk()
{
    public function getEquipes(){

        $bdd = new PDO('mysql:host=localhost;dbname=nomDB', 'root', 'pwd');

        $reponse = $bdd->prepare('SELECT nom from t_equipe');
        $reponse->execute();

        while ($data=$reponse->fetch())
        {  
            echo $data
        }
        return $reponse;
        $reponse->closeCursor();
    }
}

    
?>
 
 