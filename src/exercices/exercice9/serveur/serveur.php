<?php

session_start(); // Démarre la session pour utiliser les variables de session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action'] == "connect") {
        // Vérifier que le mot de passe est "emf"
        if ($_POST['password'] == 'emf') {
            $_SESSION['logged'] = 'emf';  // Enregistrer la connexion dans la session
            echo '<result>true</result>';
        } else {
            // Si le mot de passe est incorrect, effacer la session et renvoyer false
            session_unset();
            echo '<result>false</result>';
        }
    }

    if ($_POST['action'] == "disconnect") {
        // Effacer la variable de session 'logged' et retourner true
        session_unset();
        echo '<result>true</result>';
    }

}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($_GET['action'] == "getInfos") {
        // Vérifier si l'utilisateur est connecté en vérifiant la session
        if (isset($_SESSION['logged']) && $_SESSION['logged'] == 'emf') {
            // Si l'utilisateur est connecté, retourner les informations des utilisateurs
            echo '<users>
                    <user><name>Victor Legros</name><salaire>9876</salaire></user>
                    <user><name>Marinette Lachance</name><salaire>7540</salaire></user>
                    <user><name>Gustave Latuile</name><salaire>4369</salaire></user>
                    <user><name>Basile Ledisciple</name><salaire>2384</salaire></user>
                  </users>';
        } else {
            // Si l'utilisateur n'est pas connecté, retourner un message d'erreur
            echo '<message>DROITS INSUFFISANTS</message>';
        }
    }
}
?>
