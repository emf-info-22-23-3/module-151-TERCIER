<?php
// Autoriser les CORS en amont (spécifique à l'origine de ton front)
//header('Access-Control-Allow-Origin: http://127.0.0.1:5500'); // en local mais pour une reson obscure, ne fonctionne pas
header('Access-Control-Allow-Credentials: true'); // essentiel pour que les cookies marchent
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
                $data = json_decode(file_get_contents('php://input'), true);
        
                if ($data === null) {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'Requête JSON invalide']);
                    break;
                }
        
                $action = $data['action'] ?? ($_POST['action'] ?? '');
                switch ($action) {
                    case 'Post_checkLogin':
                        if (isset($data['Pass'], $data['Nom'])) {
                            $LoginManager = new LoginManager();
                            echo $LoginManager->Post_checkLogin($data['Nom'], $data['Pass']);
                        } else {
                            http_response_code(400);
                            echo json_encode(['status' => 'error', 'message' => 'Paramètre Pass ou Nom manquant']);
                        }
                        break;
        
                    case 'Update_volcan':
                        if (!isset($data['id'])) {
                            http_response_code(400);
                            echo json_encode(['status' => 'error', 'message' => 'ID requis']);
                            break;
                        }
                        if (!isset($data['nom'], $data['altitude'], $data['latitude'], $data['longitude'], $data['pays'])) {
                            http_response_code(400);
                            echo json_encode(['status' => 'error', 'message' => 'Paramètre(s) manquant(s)']);
                            break;
                        }
                        $volcan = new Volcan($data['id'], $data['nom'], $data['altitude'], $data['latitude'], $data['longitude'], $data['pays']);
                        $volcanManager = new VolcanManager();
                        $result = $volcanManager->modifyExistingVolcan($data['id'], $volcan);
                        if ($result) {
                            echo json_encode(['status' => 'success', 'message' => 'Volcan modifié avec succès !']);
                        } else {
                            http_response_code(500);
                            echo json_encode(['status' => 'error', 'message' => 'Échec de la modification']);
                        }
                        break;
        
                    case 'Add_volcan':
                        if (!isset($data['nom'], $data['altitude'], $data['latitude'], $data['longitude'], $data['pays'])) {
                            http_response_code(400);
                            echo json_encode(['status' => 'error', 'message' => 'Paramètre(s) manquant(s) (le pays doit être un des pays de la liste)']);
                            break;
                        }
                        if (!is_numeric($data['altitude']) || !is_numeric($data['latitude']) || !is_numeric($data['longitude']) || !is_numeric($data['pays'])) {
                            http_response_code(400);
                            echo json_encode(['status' => 'error', 'message' => 'Les valeurs des champs altitude, latitude, longitude doivent être numériques']);
                            break;
                        }
                        $volcan = new Volcan(null, $data['nom'], $data['altitude'], $data['latitude'], $data['longitude'], $data['pays']);
                        $volcanManager = new VolcanManager();
                        $result = $volcanManager->addVolcan($volcan);
                        if ($result) {
                            echo json_encode(['status' => 'success', 'message' => 'Volcan ajouté avec succès !']);
                        } else {
                            http_response_code(500);
                            echo json_encode(['status' => 'error', 'message' => 'Échec de l\'ajout']);
                        }
                        break;
                }
                break;

            case 'PUT':
                $input = file_get_contents('php://input');
                $data = json_decode($input, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'Données JSON malformées.']);
                    break;
                }
                if (!isset($data['nom'], $data['altitude'], $data['latitude'], $data['longitude'], $data['pays'])) {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'Paramètre(s) manquant(s)']);
                    break;
                }
                if (!is_numeric($data['altitude']) || !is_numeric($data['latitude']) || !is_numeric($data['longitude']) || !is_numeric($data['pays'])) {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'Les valeurs doivent être numériques']);
                    break;
                }
                $volcan = new Volcan(null, htmlspecialchars($data['nom']), (float) $data['altitude'], (float) $data['latitude'], (float) $data['longitude'], (int) $data['pays']);
                $volcanManager = new VolcanManager();
                $result = $volcanManager->addVolcan($volcan);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Volcan ajouté avec succès !']);
                } else {
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Échec de l\'ajout du volcan']);
                }
                break;

            case 'DELETE':
                $input = file_get_contents('php://input');
                $data = json_decode($input, true);
                if (!isset($data['id']) || !is_numeric($data['id'])) {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'ID requis et doit être numérique']);
                    break;
                }
                $volcanManager = new VolcanManager();
                $result = $volcanManager->deleteVolcanById($data['id']);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Volcan supprimé avec succès']);
                } else {
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Échec de la suppression']);
                }
                break;
        
            default:
                http_response_code(405);
                echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);
        }
    }