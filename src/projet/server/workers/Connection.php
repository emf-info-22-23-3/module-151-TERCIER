<?php
/**
 * Classe Connection
 *
 * Gère la connexion à la base de données et l'exécution des requêtes SQL.
 */
include_once(__DIR__ . '/configConnection.php');

class Connection {
    /**
     * Instance de l'objet PDO pour la connexion à la base de données.
     * @var PDO
     */
    private $pdo;

    /**
     * Constructeur : Initialise la connexion à la base de données.
     */
    public function __construct() {
        try {
            $this->pdo = new PDO(
                DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME,
                DB_USER,
                DB_PASS,
                [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Erreur de connexion : " . $e->getMessage());
            die(json_encode(['error' => "Erreur de connexion à la base de données."]));
        }
    }

    /**
     * Exécute une requête SELECT et retourne plusieurs résultats.
     *
     * @param string $query Requête SQL à exécuter.
     * @param array $params Paramètres pour la requête préparée.
     * @return array Résultats sous forme de tableau associatif.
     */
    public function selectQuery($query, $params = []) {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            return [];
        }
    }

    /**
     * Exécute une requête SELECT et retourne un seul résultat.
     *
     * @param string $query Requête SQL à exécuter.
     * @param array $params Paramètres pour la requête préparée.
     * @return array|false Résultat sous forme de tableau associatif ou false en cas d'erreur.
     */
    public function selectQuerySingleReturn($query, $params = []) {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Exécute une requête SQL (INSERT, UPDATE, DELETE) et retourne le succès de l'opération.
     *
     * @param string $query Requête SQL à exécuter.
     * @param array $params Paramètres pour la requête préparée.
     * @return bool Succès ou échec de l'opération.
     */
    public function executeQuery($query, $params = []) {
        try {
            $stmt = $this->pdo->prepare($query);
            
            // Exécution de la requête
            $success = $stmt->execute($params);
            
            // Vérification des erreurs après exécution
            if (!$success) {
                // Si l'exécution échoue, récupérer l'erreur SQL
                $errorInfo = $stmt->errorInfo();
                error_log("Erreur SQL : " . $errorInfo[2]); // Afficher l'erreur détaillée
                return false;
            }
            
            return $success;
        } catch (PDOException $e) {
            // Log l'exception avec les détails
            error_log("Erreur SQL : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retourne l'identifiant du dernier enregistrement inséré.
     *
     * @return string Identifiant du dernier enregistrement inséré.
     */
    public function getLastInsertId() {
        return $this->pdo->lastInsertId();
    }
}
?>
