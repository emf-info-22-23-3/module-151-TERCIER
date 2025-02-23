<?php 
include_once(__DIR__.'/../workers/Connection.php');
include_once('beans/Volcan.php');

/**
 * Classe VolcanBDManager
 * Gère les interactions avec la base de données pour les volcans.
 */
class VolcanBDManager
{
    /**
     * Lire tous les volcans depuis la base de données.
     * @return array Liste des volcans
     */
    public function readVolcans()
    {
        $connection = new Connection();
        $query = "SELECT * FROM t_volcan";
        $results = $connection->selectQuery($query);

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
     * Ajouter un volcan dans la base de données.
     * @param string $auteur Nom de l'auteur
     * @param Volcan $volcan Objet Volcan à insérer
     * @return bool Succès de l'insertion
     */
    public function addVolcan($auteur, $volcan)
	{
	    $connection = new Connection();

	    // Vérifier que tous les paramètres sont valides
	    if (empty($volcan->getNom()) || 
	        !is_numeric($volcan->getAltitude()) || 
	        !is_numeric($volcan->getLatitude()) || 
	        !is_numeric($volcan->getLongitude()) || 
	        !is_numeric($volcan->getPkPays())) {
			
	        return json_encode(['status' => 'error', 'message' => 'Paramètres invalides']);
	    }

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

	    $inserted = $connection->executeQuery($sql, $params);

	    if ($inserted) {
	        return json_encode(['status' => 'success', 'message' => 'Volcan ajouté avec succès']);
	    } else {
	        return json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'ajout du volcan']);
	    }
	}


    /**
     * Modifier un volcan existant.
     * @param int $pk Identifiant du volcan
     * @param Volcan $volcan Objet Volcan mis à jour
     * @return bool Succès de la modification
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
	            WHERE PK_volcan = ?"; // Correction du nom des colonnes

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
    	    json_encode('Error executing query: ' . $e->getMessage());
    	    return false;
    	}
	}


    /**
     * Supprimer un volcan par son ID.
     * @param int $pk Identifiant du volcan
     * @return bool Succès de la suppression
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
