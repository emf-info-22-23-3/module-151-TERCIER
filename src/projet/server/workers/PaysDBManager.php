<?php 
	include_once('Connexion.php');
	include_once('beans/Pays.php');

        
	/**
	* Classe PaysBDManager
	*
	* Cette classe permet la gestion des Payss dans la base de données 
	*
	* @version 1.0
	* @author Tercicer colin
	* @project project
	*/
	class PaysBDManager
	{
		/**
		* Fonction permettant la lecture des Payss.
		* @return liste de Pays
		*/
		public function readPayss()
		{
			$count = 0;
			$liste = array();
			$connection = new Connection();
			$query = $connection->SelectQuery("select * from t_pays");
			foreach($query as $data){
				$pays = new Pays($data['auteur'], $data['pays']);
				$liste[$count++] = $pays;
			}	
			return $liste;	
		}
		
		/**
		* Fonction permettant d'ajouter un pays à la liste des payss.
		* @param auteur le nom de l'auteur
		* @param pays le pays à ajouter
		* @return true si tout s'est passé correctement, sinon false.
		*/
		public function addPays($auteur, $pays)
		{
			$res = "";
			$connection = new Connection();
			$sql = "insert into t_pays (auteur, pays) values ('" .$auteur . "','" .$pays. "')";
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