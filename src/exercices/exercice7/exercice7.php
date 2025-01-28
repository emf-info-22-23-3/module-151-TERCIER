<?php
    $bdd = new PDO('mysql:host=localhost;dbname=nomDB', 'root', 'pwd');
    $reponse = $bdd->prepare('SELECT titre from jeuxvideo');
    $reponse->execute();
   
    while ($data=$reponse->fetch())
    {  
        echo $data
    }
    $reponse->closeCursor();
?>
 
 