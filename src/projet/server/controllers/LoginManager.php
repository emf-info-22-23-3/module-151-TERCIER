<?php 
	/**
	* Inclusion des fichiers nécessaires à la gestion des logins.
	*/
	include_once(__DIR__ . '/../workers/LoginDBManager.php');
	include_once(__DIR__ . '/../workers/Connection.php'); // Si utilisé dans LoginDBManager
	include_once(__DIR__ . '/../workers/configConnection.php'); // Si nécessaire
	include_once(__DIR__ . '/../beans/Login.php');
	include_once(__DIR__ . '/../controllers/SessionManger.php');
        
	/**
	* Classe LoginManager
	*
	* Cette classe permet la gestion des logins en vérifiant les identifiants,
	* en ouvrant et fermant les sessions utilisateur.
	*
	* @version 1.0
	* @author Tercicer Colin
	* @project Project
	*/
	class LoginManager
	{
        /**
        * Gestionnaire de session
        * @var SessionManager
        */
        private $sessionManager;

        /**
        * Constructeur de la classe LoginManager
        *
        * Initialise un gestionnaire de session.
        */
        public function __construct() {
            // Créer une instance de SessionManager
            $this->sessionManager = new SessionManager();
        }

        /**
        * Vérifie les informations de connexion et ouvre une session si elles sont valides.
        *
        * @param string $user Nom d'utilisateur
        * @param string $password Mot de passe
        * @return string Résultat en format JSON (succès ou échec)
        */
        public function Post_checkLogin($user, $password) {
            
            $loginBD = new LoginBDManager();
            $bool = $loginBD->readLogins($user, $password);
            if ($bool) {
                // Connexion réussie, ouvrir une session
                $this->sessionManager->openSession($user);
                return json_encode(["status" => "success", "message" => "Login réussi"]);
            } else {
                // Login échoué
                
                return json_encode(["status" => "error", "message" =>  "Nom ou mot de passe incorrect "]);
            }
        }

        /**
        * Gère la déconnexion de l'utilisateur en fermant la session.
        *
        * @return string Résultat en format JSON confirmant la déconnexion
        */
        public function Post_disconnect() {
            // Détruire la session
            $this->sessionManager->destroySession();
            return json_encode(["status" => "success", "message" => "Déconnexion réussie"]);
        }

        /**
        * Vérifie si l'utilisateur est déjà connecté.
        *
        * @return bool True si l'utilisateur est connecté, false sinon
        */
       /* public function isUserConnected() {
            return $this->sessionManager->isConnected();
        }*/
	}
?>
