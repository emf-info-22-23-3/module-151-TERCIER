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
        $sql = "INSERT INTO t_volcan (nom, altitude, latitude, longitude, pk_Pays) 
                VALUES (?, ?, ?, ?, ?)";
        $params = [
            htmlspecialchars($volcan->getNom()),
            (float) $volcan->getAltitude(),
            (float) $volcan->getLatitude(),
            (float) $volcan->getLongitude(),
            (int) $volcan->getPkPays()
        ];
        return $connection->executeQuery($sql, $params);
    }

    /**
     * Modifier un volcan existant.
     * @param int $pk Identifiant du volcan
     * @param Volcan $volcan Objet Volcan mis à jour
     * @return bool Succès de la modification
     */
    public function modifyVolcan($pk, $volcan)
    {
        $connection = new Connection();
        $sql = "UPDATE t_volcan SET 
                nom = ?, 
                altitude = ?, 
                latitude = ?, 
                longitude = ?, 
                pk_Pays = ? 
                WHERE pk_Volcan = ?";
        $params = [
            htmlspecialchars($volcan->getNom()),
            (float) $volcan->getAltitude(),
            (float) $volcan->getLatitude(),
            (float) $volcan->getLongitude(),
            (int) $volcan->getPkPays(),
            (int) $pk
        ];
        return $connection->executeQuery($sql, $params);
    }

    /**
     * Supprimer un volcan par son ID.
     * @param int $pk Identifiant du volcan
     * @return bool Succès de la suppression
     */
    public function deleteVolcan($pk)
    {
        $connection = new Connection();
        $sql = "DELETE FROM t_volcan WHERE pk_Volcan = ?";
        return $connection->executeQuery($sql, [(int) $pk]);
    }
}
?>
