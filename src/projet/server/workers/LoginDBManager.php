<?php
// Inclusion des fichiers nécessaires pour la gestion des connexions et des objets Login.
include_once(__DIR__ . '/../workers/Connection.php');
include_once(__DIR__ . '/../beans/Login.php');

/**
 * Classe LoginBDManager
 *
 * Cette classe permet la gestion des Logins dans la base de données.
 *
 * @version 1.0
 * @author Tercicer Colin
 * @project Project
 */
class LoginBDManager
{
    /**
     * Vérifie si un utilisateur peut se connecter avec son nom et son mot de passe.
     * 
     * @param string $user Nom de l'utilisateur.
     * @param string $pass Mot de passe de l'utilisateur.
     * @return bool Retourne true si l'authentification est réussie, sinon false.
     */
    public function readLogins($user, $pass)
    {

        $resulte = false;

        try {
            // Création d'une nouvelle connexion à la base de données.
            $connection = new Connection();

            // Requête SQL pour récupérer le mot de passe haché d'un utilisateur en fonction de son nom.
            $query = "SELECT pass FROM bd_ProjectVolcan.t_admin WHERE nom = :nom";

            // Paramètres de la requête préparée.
            $params = array("nom" => $user);

            // Exécution de la requête et récupération du résultat sous forme de tableau associatif.
            $result = $connection->selectQuerySingleReturn($query, $params);

            // Vérification si un résultat a été trouvé.
            if ($result != null && isset($result["pass"])) {
                $row = $result["pass"];
                
                
                $resulte = password_verify($pass, $row);
            }
        } catch (Exception $e) {
            // Gérer l'exception, par exemple en loggant l'erreur
            $resulte = false;
        }

        // Retourne false si aucun utilisateur ne correspond ou si l'authentification échoue.
        return $resulte;
    }

    /**
     * Ajoute un nouvel utilisateur dans la base de données.
     *
     * @param string $auteur Nom de l'auteur du login.
     * @param string $login Identifiant du login à ajouter.
     * @return string Retourne un JSON indiquant le succès ou l'échec de l'opération.
     */
    public function addLogin($auteur, $login)
    {
        // Variable pour stocker la réponse sous forme JSON.
        $res = "";

        // Création d'une nouvelle connexion à la base de données.
        $connection = new Connection();

        // Requête SQL pour insérer un nouveau login.
        $sql = "INSERT INTO t_login (auteur, login) VALUES ('" . $auteur . "','" . $login . "')";

        // Exécution de la requête SQL.
        $resultat = $connection->executeQuery($sql);

        // Vérification si l'insertion s'est bien déroulée.
        if ($resultat) {
            $res = '{"result":true}';
        } else {
            $res = '{"result":false}';
        }

        // Retourne le résultat sous forme JSON.
        return $res;
    }
}
