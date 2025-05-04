<?php
// Autoriser les CORS en amont
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Si la méthode est OPTIONS (préflight), on répond juste 200 sans plus
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Inclusion des fichiers nécessaires pour la gestion des volcans, pays et la connexion à la base de données
include_once(__DIR__ . '/controllers/LoginManager.php');
include_once(__DIR__ . '/controllers/VolcanManager.php');
include_once(__DIR__ . '/beans/Volcan.php');
include_once(__DIR__ . '/workers/Connection.php');
include_once(__DIR__ . '/workers/VolcanDBManager.php');
include_once(__DIR__ . '/controllers/PaysManager.php');
include_once(__DIR__ . '/beans/Pays.php');
include_once(__DIR__ . '/workers/PaysDBManager.php');

// Vérification de la méthode HTTP utilisée pour la requête
if (isset($_SERVER['REQUEST_METHOD'])) {



    // Switch basé sur la méthode HTTP (GET, POST, PUT, DELETE)
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $response = array();

            if (isset($_GET['action'])) {
                if ($_GET['action'] === 'Get_volcans') {
                    $VolcanManager = new VolcanManager();
                    $volcans = $VolcanManager->getAllVolcans();

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
                    echo json_encode($response);
                    break;
                }

                if ($_GET['action'] === 'Get_pays') {
                    $paysManager = new PaysManager();
                    $paysList = $paysManager->getAllPays();

                    if (empty($paysList)) {
                        echo json_encode([]);
                    } else {
                        $paysArray = [];
                        foreach ($paysList as $pays) {
                            $paysArray[] = [
                                'pk_Pays' => $pays->getPkPays(),
                                'nom' => $pays->getNom()
                            ];
                        }
                        echo json_encode($paysArray);
                    }
                    break;
                }

                if ($_GET['action'] === 'Get_volcans_filtered') {
                    $VolcanManager = new VolcanManager();
                    $nom = $_GET['nom'] ?? '';
                    $pays = $_GET['pays'] ?? '';

                    $volcans = $VolcanManager->getVolcanFiltered($nom, $pays);

                    $response = [];
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
                    echo json_encode($response);
                    break;
                }
            }

            echo json_encode(['error' => 'Aucune action valide spécifiée']);
            break;


        case 'POST':
            // Décodage des données envoyées en POST (en JSON)
            $data = json_decode(file_get_contents('php://input'), true);

            if ($data === null) {
                echo json_encode(['status' => 'error', 'message' => 'Requête JSON invalide']);
                break;
            }

            $action = $data['action'] ?? ($_POST['action'] ?? '');
            // Switch pour traiter les actions POST
            switch ($action) {
                case 'Post_checkLogin':
                    // Vérification de la présence des paramètres 'Nom' et 'Pass' pour la connexion
                    if (isset($data['Pass']) && isset($data['Nom'])) {
                        $LoginManager = new LoginManager();
                        echo $LoginManager->Post_checkLogin($data['Nom'], $data['Pass']);
                    } else {
                        echo 'Paramètre Pass ou Nom manquant';
                    }
                    break;

                case 'Update_volcan':
                    // Vérification de la présence de l'ID pour la modification
                    if (!isset($data['id'])) {
                        echo json_encode(['status' => 'error', 'message' => 'L\'ID du volcan est requis pour la modification.']);
                        break;
                    }

                    // Vérification de la présence des autres paramètres nécessaires pour la mise à jour
                    if (!isset($data['nom'], $data['altitude'], $data['latitude'], $data['longitude'], $data['pays'])) {
                        echo json_encode(['status' => 'error', 'message' => 'Paramètre(s) manquant(s)']);
                        break;
                    }

                    // Création d'un objet Volcan pour la mise à jour
                    $volcan = new Volcan(
                        $data['id'],
                        $data['nom'],
                        $data['altitude'],
                        $data['latitude'],
                        $data['longitude'],
                        $data['pays']
                    );

                    // Appel à VolcanManager pour modifier le volcan
                    $volcanManager = new VolcanManager();
                    $result = $volcanManager->modifyExistingVolcan($data['id'], $volcan);

                    // Retourner un message en fonction du résultat
                    if ($result) {
                        echo json_encode(['status' => 'success', 'message' => 'Volcan modifié avec succès !']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Échec de la modification du volcan']);
                    }
                    break;

                case 'Add_volcan':
                    // Vérification de la présence des paramètres nécessaires pour ajouter un volcan
                    if (!isset($data['nom'], $data['altitude'], $data['latitude'], $data['longitude'], $data['pays'])) {
                        echo json_encode(['status' => 'error', 'message' => 'Paramètre(s) manquant(s)']);
                        break;
                    }

                    // Validation des paramètres pour s'assurer qu'ils sont numériques
                    if (!is_numeric($data['altitude']) || !is_numeric($data['latitude']) || !is_numeric($data['longitude']) || !is_numeric($data['pays'])) {
                        echo json_encode(['status' => 'error', 'message' => 'Les valeurs doivent être numériques pour altitude, latitude, longitude et pays.']);
                        break;
                    }

                    // Création d'un objet Volcan pour l'ajout
                    $volcan = new Volcan(
                        NULL,  // ID auto-incrémenté
                        $data['nom'],
                        $data['altitude'],
                        $data['latitude'],
                        $data['longitude'],
                        $data['pays']
                    );

                    // Appel au VolcanManager pour ajouter le volcan
                    $volcanManager = new VolcanManager();
                    $result = $volcanManager->addVolcan($volcan);

                    // Retourner un message en fonction du résultat
                    if ($result) {
                        echo json_encode(['status' => 'success', 'message' => 'Volcan ajouté avec succès']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'ajout du volcan']);
                    }
                    break;
            }
            break;

        case 'PUT':
            // Récupération et décodage des données envoyées en PUT
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            // Vérification que les données JSON sont valides
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(['status' => 'error', 'message' => 'Données JSON malformées.']);
                break;
            }

            // Vérification de la présence des paramètres nécessaires
            if (!isset($data['nom'], $data['altitude'], $data['latitude'], $data['longitude'], $data['pays'])) {
                echo json_encode(['status' => 'error', 'message' => 'Paramètre(s) manquant(s)']);
                break;
            }

            // Vérification des valeurs numériques
            if (!is_numeric($data['altitude']) || !is_numeric($data['latitude']) || !is_numeric($data['longitude']) || !is_numeric($data['pays'])) {
                echo json_encode(['status' => 'error', 'message' => 'Les valeurs doivent être numériques pour altitude, latitude, longitude et pays.']);
                break;
            }

            // Création de l'objet Volcan à ajouter
            $volcan = new Volcan(
                null,  // L'ID est auto-incrémenté
                htmlspecialchars($data['nom']),
                (float) $data['altitude'],
                (float) $data['latitude'],
                (float) $data['longitude'],
                (int) $data['pays']
            );

            // Ajout du volcan via le VolcanManager
            $volcanManager = new VolcanManager();
            $result = $volcanManager->addVolcan($volcan);

            // Retourner un message en fonction du résultat
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Volcan ajouté avec succès !']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Échec de l\'ajout du volcan']);
            }
            break;

        case 'DELETE':
            // Récupération des données envoyées en DELETE
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            // Vérification de la présence et validité de l'ID du volcan
            if (isset($data['id']) && is_numeric($data['id'])) {
                $volcanManager = new VolcanManager();
                $result = $volcanManager->deleteVolcanById($data['id']);

                echo json_encode( $result);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'L\'ID du volcan est requis et doit être valide.']);
            }
            break;
    }
}
