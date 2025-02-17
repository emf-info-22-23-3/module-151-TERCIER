<?php 
	include_once('Connexion.php');
	include_once('beans/Volcan.php');

        
	/**
	* Classe VolcanBDManager
	*
	* Cette classe permet la gestion des Volcans dans la base de données 
	*
	* @version 1.0
	* @author Tercicer colin
	* @project project
	*/
	class VolcanBDManager
	{
		/**
		* Fonction permettant la lecture des Volcans.
		* @return liste de Volcan
		*/
		public function readVolcans()
		{
			$count = 0;
			$liste = array();
			$connection = new Connection();
			$query = $connection->SelectQuery("select * from t_volcan");
			foreach($query as $data){
				$volcan = new Volcan($data['auteur'], $data['volcan']);
				$liste[$count++] = $volcan;
			}	
			return $liste;	
		}
		
		/**
		* Fonction permettant d'ajouter un volcan à la liste des volcans.
		* @param auteur le nom de l'auteur
		* @param volcan le volcan à ajouter
		* @return true si tout s'est passé correctement, sinon false.
		*/
		public function addVolcan($auteur, $volcan)
		{
			$res = "";
			$connection = new Connection();
			$sql = "insert into t_volcan (auteur, volcan) values ('" .$auteur . "','" .$volcan. "')";
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