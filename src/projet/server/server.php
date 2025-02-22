<?php
include_once(__DIR__.'/controllers/LoginManager.php') ;
include_once(__DIR__.'/controllers/VolcanManager.php');
include_once(__DIR__.'/beans/Volcan.php');
//include_once 'workers/VolcanDBManager.php';
include_once(__DIR__.'/workers/Connection.php');
include_once(__DIR__.'/workers/VolcanDBManager.php');
include_once(__DIR__.'/controllers/PaysManager.php');
include_once(__DIR__.'/beans/Pays.php');
include_once(__DIR__.'/workers/PaysDBManager.php');

if (isset($_SERVER['REQUEST_METHOD']))
	{
		switch ($_SERVER['REQUEST_METHOD'])
		{
			case 'GET':

				// On prépare une réponse JSON
				$response = array();
			
				if (isset($_GET['action']) && $_GET['action'] === 'Get_volcans') {
					// Récupérer tous les volcans
					$VolcanManager = new VolcanManager();
					$volcans = $VolcanManager->getAllVolcans();
					
					// Formatage des volcans dans le tableau de réponse
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
			
					// Renvoyer les volcans au format JSON
					echo json_encode($response);
					exit();  // Fin du script ici pour ne pas exécuter la suite du code
				}
			
				if (isset($_GET['action']) && $_GET['action'] === 'Get_pays') {
					// Créer une instance de PaysManager pour accéder aux pays
					$paysManager = new PaysManager();
					
					// Récupérer tous les pays
					$paysList = $paysManager->getAllPays();
					
					// Vérifier si la liste des pays est vide
					if (empty($paysList)) {
						echo json_encode([]);  // Retourne un tableau vide si aucun pays n'est trouvé
					} else {
						// Créer un tableau associatif pour les pays à envoyer en réponse
						$paysArray = [];
						foreach ($paysList as $pays) {
							$paysArray[] = [
								'pk_Pays' => $pays->getPkPays(),
								'nom' => $pays->getNom()
							];
						}
						// Retourner la réponse en JSON
						echo json_encode($paysArray);
					}
					exit();  // Fin du script ici pour ne pas exécuter la suite du code
				}
			
				// Si aucune des conditions n'est remplie, vous pouvez ajouter un message d'erreur ou une action par défaut
				echo json_encode(['error' => 'Aucune action valide spécifiée']);
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
				/*if (isset($_POST['Volcan']) and isset($_POST['Altitude']) and isset($_POST['Latitude']) and isset($_POST['Longitude']) and isset($_POST['Pk_Pays'])) {
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
					$VolcanManager = new VolcanManager();
					$result = $volcanManager->addNewVolcan('Admin', $volcan);
					echo $result;  // Réponse sur l'ajout
					exit;
				} else {
					echo 'Paramètre(s) manquant(s)';
					exit;
				}*/
				
			case 'PUT':
				
				break;
			case 'DELETE':
				
				break;
		}
	}
	
?>

