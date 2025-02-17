<?php 
	include_once('workers/LoginBDManager.php');
	include_once('beans/Login.php');
    include_once('Session.php');
        
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


		/**
		* Fonction permettant verifier si l'utilisateur a une session déjà ouverte
        */

        



        public function Post_checkLogin($user, $password){
           
            $loginBD = new LoginBDManager();
			return $loginBD->readLogins($user, $password);
        }

        public function Post_disconnect(){

        }


		/**
		* Fonction permettant d'écrire la liste des logins en format JSON.
		* @return la liste des logins au format JSON
			
		public function getMessagesInJSON()
		{
			$messageBD = new messageBDManager();
			$listMessages = $messageBD->ReadMessages();
			
			$liste = array();
			for($i=0;$i<sizeof($listMessages);$i++) 
			{
				$liste[$i] = $listMessages[$i];						
			}
			return '{"messages":'. json_encode($liste) . '}';
		}
		
		
		* Fonction permettant d'ajouter un message à la liste des messages.
		* @param auteur le nom de l'auteur
		* @param message le message à ajouter
		* @return true si tout s'est passé correctement, sinon false.
		
		public function addMessage($auteur, $message)
		{
			$messageBD = new messageBDManager();
			return $messageBD->AddMessage($auteur, $message);			
		}*/
	}
?>