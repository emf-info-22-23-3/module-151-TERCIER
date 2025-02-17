<?php 
	include_once(__DIR__ . '/../workers/Connection.php');
	include_once(__DIR__ . '/../beans/Login.php');

        
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
		public function readLogins($user, $pass)
        {
            $connection = new Connection(); 
            $query = "SELECT password FROM t_admin WHERE nom = ?";
            $params = [$user];
            $result = $connection->selectQuery($query, $params);

            if ($result) {
                $row = $result[0]; // PDO retourne un tableau d'associatif, on prend la première ligne.
                return password_verify($pass, $row['password']);
            }
        
            return false; 
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