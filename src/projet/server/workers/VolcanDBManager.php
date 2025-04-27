<?php 
// Inclusion des fichiers nécessaires pour la gestion de la connexion à la base de données et de la classe Volcan.
include_once(__DIR__.'/../workers/Connection.php');
include_once('beans/Volcan.php');

/**
 * Classe VolcanBDManager
 * Gère les interactions avec la base de données pour les volcans.
 */
class VolcanBDManager
{
    /**
     * Récupère tous les volcans depuis la base de données.
     * 
     * @return array Liste des volcans sous forme d'objets Volcan.
     */
    public function readVolcans()
    {
        $connection = new Connection();
        $query = "SELECT * FROM t_volcan";
        $results = $connection->selectQuery($query);

        // Création d'un tableau d'objets Volcan
        $volcans = [];
        foreach ($results as $data) {
            $volcans[] = new Volcan(
                $data['PK_volcan'],
                $data['nom'],
                $data['altitude'],
                $data['latitude'],
                $data['longitude'],
                $data['FK_pays']
            );
        }
		
        return $volcans;
    }

    /**
     * Récupère tous les parametres pour selectioner le/s volcan/s.
     * 
     * @return array Liste des volcans sous forme d'objets Volcan.
     */
     public function getVolcansFiltered($nom = '', $pays = '') {
        $connection = new Connection();
        
        $sql = "
            SELECT v.PK_volcan, v.nom AS volcan_nom, v.altitude, v.latitude, v.longitude, p.nom AS pays_nom
            FROM t_volcan v
            INNER JOIN t_pays p ON v.FK_pays = p.PK_pays
            WHERE 1
        ";
        $params = [];
    
        if (!empty($nom)) {
            $sql .= " AND v.nom LIKE ?";
            $params[] = "%$nom%";
        }
    
        if (!empty($pays)) {
            $sql .= " AND p.PK_pays = ?";
            $params[] = (int)$pays;
        }
    
        $results = $connection->selectQuery($sql, $params);
    
        $volcans = [];
        foreach ($results as $data) {
            $volcans[] = new Volcan(
                $data['PK_volcan'],
                $data['volcan_nom'],
                $data['altitude'],
                $data['latitude'],
                $data['longitude'],
                $data['pays_nom']  //le nom du pays directement
            );
        }
    
        return $volcans;
    }
    
    /**
     * Ajoute un volcan dans la base de données.
     * 
     * @param Volcan $volcan Objet Volcan à insérer.
     * @return string JSON indiquant le succès ou l'échec de l'opération.
     */
    public function addVolcan($volcan)
    {
        $connection = new Connection();

        // Vérifier si un volcan avec le même nom et coordonnées existe déjà
        $checkSql = "SELECT COUNT(*) as count FROM t_volcan 
                     WHERE nom = ? AND latitude = ? AND longitude = ?";
        $existing = $connection->selectQuery($checkSql, [
            htmlspecialchars($volcan->getNom()),
            (float) $volcan->getLatitude(),
            (float) $volcan->getLongitude()
        ]);

        if ($existing[0]['count'] > 0) {
            return json_encode(['status' => 'error', 'message' => 'Ce volcan existe déjà']);
        }

        // Insertion du volcan
        $sql = "INSERT INTO t_volcan (nom, altitude, latitude, longitude, FK_pays) 
                VALUES (?, ?, ?, ?, ?)";
        $params = [
            htmlspecialchars($volcan->getNom()),
            (float) $volcan->getAltitude(),
            (float) $volcan->getLatitude(),
            (float) $volcan->getLongitude(),
            (int) $volcan->getPkPays()
        ];

        try {
            $result = $connection->executeQuery($sql, $params);

            if ($result) {
                return json_encode(['status' => 'success', 'message' => 'Volcan ajouté avec succès']);
            } else {
                return json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'ajout']);
            }
        } catch (Exception $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            return json_encode(['status' => 'error', 'message' => 'Erreur interne']);
        }
    }

    /**
     * Modifie un volcan existant.
     * 
     * @param int $pk Identifiant du volcan.
     * @param Volcan $volcan Objet Volcan mis à jour.
     * @return bool Succès ou échec de la mise à jour.
     */
    public function modifyVolcan($pk, $volcan)
    {
        if (!$pk || !$volcan instanceof Volcan) {
            return false; // Vérifie que l'ID est valide et que $volcan est un objet Volcan
        }

        $connection = new Connection();
        $sql = "UPDATE t_volcan SET 
                nom = ?, 
                altitude = ?, 
                latitude = ?, 
                longitude = ?, 
                FK_pays = ? 
                WHERE PK_volcan = ?";

        $params = [
            trim($volcan->getNom()), // Suppression des espaces inutiles
            (float) $volcan->getAltitude(),
            (float) $volcan->getLatitude(),
            (float) $volcan->getLongitude(),
            (int) $volcan->getPkPays(),
            (int) $pk
        ];

        try {
            return $connection->executeQuery($sql, $params);
        } catch (Exception $e) {
            error_log('Erreur SQL lors de la mise à jour du volcan : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime un volcan par son identifiant.
     * 
     * @param int $pk Identifiant du volcan.
     * @return string JSON indiquant le succès ou l'échec de la suppression.
     */
    public function deleteVolcan($pk)
    {
        $connection = new Connection();

        // Vérifier que l'ID est bien un entier positif
        if (!is_numeric($pk) || (int)$pk <= 0) {
            return json_encode(['status' => 'error', 'message' => 'ID invalide']);
        }

        // Vérifier si le volcan existe avant de supprimer
        $checkSql = "SELECT COUNT(*) as count FROM t_volcan WHERE PK_volcan = ?";
        $result = $connection->selectQuery($checkSql, [(int) $pk]);

        if ($result[0]['count'] == 0) {
            return json_encode(['status' => 'error', 'message' => 'Le volcan n\'existe pas']);
        }

        // Supprimer le volcan
        $sql = "DELETE FROM t_volcan WHERE PK_volcan = ?";
        $deleted = $connection->executeQuery($sql, [(int) $pk]);

        if ($deleted) {
            return json_encode(['status' => 'success', 'message' => 'Volcan supprimé avec succès']);
        } else {
            return json_encode(['status' => 'error', 'message' => 'Erreur lors de la suppression']);
        }
    }
}
?>
