<?php
include_once 'controllers/LoginManager.php';
include_once 'controllers/VolcanManager.php';
include_once 'beans/Volcan.php';
include_once 'workers/VolcanDBManager.php';
include_once 'workers/Connection.php';

if (isset($_SERVER['REQUEST_METHOD']))
	{
		switch ($_SERVER['REQUEST_METHOD'])
		{
			case 'GET':
				// Récupérer tous les volcans
				$volcans = $volcanManager->getAllVolcans();
				// On prépare une réponse JSON contenant la liste des volcans
				$response = array();
				foreach ($volcans as $volcan) {
					$response[] = array(
						'pk_Volcan' => $volcan->getPkVolcan(),
						'nom' => $volcan->getNom(),
						'altitude' => $volcan->getAltitude(),
						'latitude' => $volcan->getLatitude(),
						'longitude' => $volcan->getLongitude(),
						'pk_Pays' => $volcan->getPkPays()
					);
				}
				// Renvoi de la liste des volcans au format JSON
				echo json_encode($response);
				
				break;
			case 'POST':
				if (isset($_POST['Pass']) and isset($_POST['Nom']))
				{
					$LoginManager = new LoginManager();
					echo $LoginManager->Post_checkLogin($_POST['Nom'], $_POST['Pass']);
					exit;
				}
				/*else{
					echo 'Paramètre Pass ou Nom manquant';
					exit;
				}*/

				// Ajouter un volcan
				if (isset($_POST['Volcan']) and isset($_POST['Altitude']) and isset($_POST['Latitude']) and isset($_POST['Longitude']) and isset($_POST['Pk_Pays'])) {
					// Création d'un objet Volcan avec les données reçues
					$volcan = new Volcan(
						null, // PK n'est pas encore défini lors de l'ajout
						$_POST['Volcan'],
						$_POST['Altitude'],
						$_POST['Latitude'],
						$_POST['Longitude'],
						$_POST['Pk_Pays']
					);
					// Appel à la méthode d'ajout dans VolcanManager
					$result = $volcanManager->addNewVolcan('Admin', $volcan);
					echo $result;  // Réponse sur l'ajout
					exit;
				} else {
					echo 'Paramètre(s) manquant(s)';
					exit;
				}
				
			case 'PUT':
				
				break;
			case 'DELETE':
				
				break;
		}
	}
	
?>

