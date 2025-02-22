<?php
include_once(__DIR__ . '/configConnection.php'); 

class Connection {
    private $pdo;

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

    public function executeQuery($query, $params = []) {
        try {
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            return false;
        }
    }

    public function getLastInsertId() {
        return $this->pdo->lastInsertId();
    }
}
?>
