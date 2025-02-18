<?php 
	include_once(__DIR__ . '/../workers/LoginDBManager.php');
	include_once(__DIR__ . '/../workers/Connection.php'); // Si utilisé dans LoginDBManager
	include_once(__DIR__ . '/../workers/configConnection.php'); // Si nécessaire
	include_once(__DIR__ . '/../beans/Login.php');
	include_once(__DIR__ . '/../controllers/SessionManger.php');
        
	/**
	* Classe loginManager
	*
	* Cette classe permet la gestion des login 
	*
	* @version 1.0
	* @author Tercicer colin
	* @project project
	*/
	class LoginManager
	{
        private $sessionManager;

        public function __construct() {
            // Créer une instance de SessionManager
            $this->sessionManager = new SessionManager();
        }

        // Vérifier et gérer la connexion
        public function Post_checkLogin($user, $password) {
            $loginBD = new LoginBDManager();
            $bool = $loginBD->readLogins($user, $password);
            
            if ($bool) {
                // Connexion réussie, ouvrir une session
                $this->sessionManager->openSession($user);
                return json_encode(["status" => "success", "message" => "Login réussi"]);
            } else {
                // Login échoué
                return json_encode(["status" => "error", "message" => "Nom ou mot de passe incorrect"]);
            }
        }

        // Gérer la déconnexion de l'utilisateur
        public function Post_disconnect() {
            // Détruire la session
            $this->sessionManager->destroySession();
            return json_encode(["status" => "success", "message" => "Déconnexion réussie"]);
        }

        // Vérifier si l'utilisateur est déjà connecté
       /* public function isUserConnected() {
            return $this->sessionManager->isConnected();
        }*/
	}
?>