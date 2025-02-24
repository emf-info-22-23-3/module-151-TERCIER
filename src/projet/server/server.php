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
				switch ($_POST['action'] ?? '') {
					case 'Post_checkLogin' :

						if (isset($_POST['Pass']) && isset($_POST['Nom'])) {
							$LoginManager = new LoginManager();
							echo $LoginManager->Post_checkLogin($_POST['Nom'], $_POST['Pass']);
							exit;
						}else {
							echo 'Paramètre Pass ou Nom manquant';
							exit;
						}
						exit;
					
					case 'Update_volcan':
				
						// Debugging
						//var_dump($_POST);
						
						// Vérifier que l'ID est présent pour éviter un ajout
						if (!isset($_POST['id'])) {
							echo json_encode(['status' => 'error', 'message' => 'L\'ID du volcan est requis pour la modification.']);
							exit;
						}
					
						// Vérifier la présence des autres paramètres requis
						if (!isset($_POST['nom'], $_POST['altitude'], $_POST['latitude'], $_POST['longitude'], $_POST['pays'])) {
							echo json_encode(['status' => 'error', 'message' => 'Paramètre(s) manquant(s)']);
							exit;
						}
					
						// Création de l'objet Volcan pour la modification
						$volcan = new Volcan(
							$_POST['id'],
							$_POST['nom'],
							$_POST['altitude'],
							$_POST['latitude'],
							$_POST['longitude'],
							$_POST['pays']
						);
					
						$volcanManager = new VolcanManager();
						$result = $volcanManager->modifyExistingVolcan($_POST['id'], $volcan);
				
						if ($result) {
						    echo json_encode(['status' => 'success', 'message' => 'Volcan modifié avec succès !']);
						} else {
						    echo json_encode(['status' => 'error', 'message' => 'Échec de la modification du volcan']);
						}
						exit();
					case 'Add_volcan': // Nouveau POST pour l'ajout d'un volcan
						// Vérifier la présence des paramètres requis
						if (!isset($_POST['nom'], $_POST['altitude'], $_POST['latitude'], $_POST['longitude'], $_POST['pays'])) {
							echo json_encode(['status' => 'error', 'message' => 'Paramètre(s) manquant(s)']);
							exit();
						}
					
						// Validation supplémentaire pour s'assurer que les valeurs sont correctes
						if (!is_numeric($_POST['altitude']) || !is_numeric($_POST['latitude']) || !is_numeric($_POST['longitude']) || !is_numeric($_POST['pays'])) {
							echo json_encode(['status' => 'error', 'message' => 'Les valeurs doivent être numériques pour altitude, latitude, longitude et pays.']);
							exit();
						}
					
						// Création de l'objet Volcan pour l'ajout
						$volcan = new Volcan(
							NULL,  // L'ID est auto-incrémenté dans la base
							$_POST['nom'],
							$_POST['altitude'],
							$_POST['latitude'],
							$_POST['longitude'],
							$_POST['pays']
						);
					
						// Appeler le VolcanManager pour effectuer l'ajout
						$volcanManager = new VolcanManager();
						$result = $volcanManager->addVolcan($volcan);  // Méthode d'ajout dans VolcanManager
					
						// Vérifier si l'ajout a réussi
						if ($result) {
							// Retourner une réponse JSON de succès
							echo json_encode(['status' => 'success', 'message' => 'Volcan ajouté avec succès']);
						} else {
							// Retourner une réponse JSON d'erreur si l'ajout a échoué
							echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'ajout du volcan']);
						}
						exit();  // Terminer l'exécution pour renvoyer la réponse au client
						break;
					
						
						
				
						// Vous pouvez ajouter d'autres actions ici, comme la mise à jour ou la suppression des volcans
				
					}
				break;
			
			case 'PUT':
				// Récupérer les données envoyées en PUT
				$input = file_get_contents('php://input');
				$data = json_decode($input, true);
			
				// Vérifier que les données sont valides
				if (json_last_error() !== JSON_ERROR_NONE) {
					echo json_encode(['status' => 'error', 'message' => 'Données JSON malformées.']);
					exit();
				}

				// Vérifier si toutes les données requises sont présentes
				if (!isset($data['nom'], $data['altitude'], $data['latitude'], $data['longitude'], $data['pays'])) {
					echo json_encode(['status' => 'error', 'message' => 'Paramètre(s) manquant(s)']);
					exit();
				}
			
				// Vérification supplémentaire : s'assurer que les valeurs sont correctes
				if (!is_numeric($data['altitude']) || !is_numeric($data['latitude']) || !is_numeric($data['longitude']) || !is_numeric($data['pays'])) {
					echo json_encode(['status' => 'error', 'message' => 'Les valeurs doivent être numériques pour altitude, latitude, longitude et pays.']);
					exit();
				}
				
				// Création de l'objet Volcan
				$volcan = new Volcan(
					null,  // L'ID est auto-incrémenté dans la base
					htmlspecialchars($data['nom']),
					(float) $data['altitude'],
					(float) $data['latitude'],
					(float) $data['longitude'],
					(int) $data['pays']
				);
			
				// Ajouter le volcan via le VolcanManager
				$volcanManager = new VolcanManager();
				$result = $volcanManager->addVolcan($volcan);
			
				if ($result) {
					echo json_encode(['status' => 'success', 'message' => 'Volcan ajouté avec succès !']);
				} else {
					echo json_encode(['status' => 'error', 'message' => 'Échec de l\'ajout du volcan']);
				}
				exit();
			
			
			break;
			case 'DELETE':
				// Récupérer les données de la requête DELETE via php://input
				$input = file_get_contents('php://input');
			
				// Décoder le JSON
				$data = json_decode($input, true);
			
				// Vérifier si l'ID du volcan est présent et valide
				if (isset($data['id']) && is_numeric($data['id'])) {
					// Créer une instance de VolcanManager pour gérer la suppression
					$volcanManager = new VolcanManager();
					$result = $volcanManager->deleteVolcanById($data['id']);
			
					// Retourner un message de succès ou d'erreur
					if ($result) {
						echo json_encode(['status' => 'success', 'message' => 'Volcan supprimé avec succès !']);
					} else {
						echo json_encode(['status' => 'error', 'message' => 'Échec de la suppression du volcan.']);
					}
				} else {
					// Si l'ID est manquant ou invalide
					echo json_encode(['status' => 'error', 'message' => 'L\'ID du volcan est requis et doit être valide.']);
				}
				exit();
				
				
		}
	}
	
?>

