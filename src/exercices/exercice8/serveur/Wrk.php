<?php

class Wrk
{
    public function getEquipes(){
 
        $bdd = new PDO('mysql:host=mysql;dbname=hockey_stats', 'root', 'root');

        $reponse = $bdd->prepare('SELECT pk_equipe, nom from t_equipe');
        $reponse->execute();
        
        return $reponse->fetchall();
        
    }
}

    
?>
 
 