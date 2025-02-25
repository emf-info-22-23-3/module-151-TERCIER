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
        // Création d'une nouvelle connexion à la base de données.
        $connection = new Connection(); 

        // Requête SQL pour récupérer le mot de passe haché d'un utilisateur en fonction de son nom.
        $query = "SELECT pass FROM t_admin WHERE nom = :nom";

        // Paramètres de la requête préparée.
        $params = array("nom" => $user);

        // Exécution de la requête et récupération du résultat sous forme de tableau associatif.
        $result = $connection->selectQuerySingleReturn($query, $params);

        // Vérification si un résultat a été trouvé.
        if ($result) {
            $row = $result["pass"]; // Récupération du mot de passe haché de la base.
            
            // Vérification du mot de passe en le comparant avec le hash stocké.
            return password_verify($pass, $row);
        }

        // Retourne false si aucun utilisateur ne correspond ou si l'authentification échoue.
        return false; 
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
?>
