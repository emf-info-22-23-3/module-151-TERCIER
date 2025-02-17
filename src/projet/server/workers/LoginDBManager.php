<?php 
	include_once('Connexion.php');
	include_once('beans/Login.php');

        
	/**
	* Classe LoginBDManager
	*
	* Cette classe permet la gestion des Logins dans la base de données 
	*
	* @version 1.0
	* @author Tercicer colin
	* @project project
	*/
	class LoginBDManager
	{
		/**
		* Fonction permettant la lecture des Logins.
		* @return liste de Login
		*/
		public function readLogins($user, $pass): bool
		{
            $connection = new Connection(); 
            $bool = false;

            $stmt = $connection->prepare("SELECT * FROM t_admin WHERE nom = ? AND password = ?");
            $stmt->bind_param("ss", $user, $pass);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $bool = true;
            }

            return $bool;	
		}
		
		/**
		* Fonction permettant d'ajouter un Login à la liste des Logins.
		* @param auteur le nom de l'auteur
		* @param login le login à ajouter
		* @return true si tout s'est passé correctement, sinon false.
		*/
		public function addLogin($auteur, $login)
		{
			$res = "";
			$connection = new Connection();
			$sql = "insert into t_login (auteur, login) values ('" .$auteur . "','" .$login. "')";
			$resultat = $connection->executeQuery($sql);		
			if ($resultat)
			{
				$res = '{"result":true}';
			}
			else{
				$res = '{"result":false}';
			}
			return $res;
		}
	}
?>