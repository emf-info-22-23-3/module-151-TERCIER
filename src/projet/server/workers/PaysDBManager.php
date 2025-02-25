<?php 
// Inclusion des fichiers nécessaires pour la gestion des connexions et des objets Pays.
include_once(__DIR__.'/Connection.php');
include_once(__DIR__.'/../beans/Pays.php');

/**
 * Classe PaysBDManager
 *
 * Cette classe permet la gestion des Pays dans la base de données.
 *
 * @version 1.0
 * @author Tercicer Colin
 * @project Project
 */
class PaysBDManager
{
    /**
     * Récupère la liste de tous les pays présents dans la base de données.
     * 
     * @return array Liste des objets Pays.
     */
    public function readPays()
    {
        // Initialisation d'un tableau vide pour stocker les pays.
        $liste = [];
        
        try {
            // Création d'une connexion à la base de données.
            $connection = new Connection();

            // Exécution de la requête SQL pour récupérer tous les pays.
            $query = $connection->SelectQuery("SELECT * FROM t_pays");

            // Parcours des résultats et création des objets Pays.
            foreach ($query as $data) {
                // Création d'un objet Pays avec les données récupérées.
                // Assurez-vous que 'PK_pays' et 'nom' correspondent bien aux colonnes de la table.
                $pays = new Pays($data['PK_pays'], $data['nom']);

                // Ajout de l'objet Pays à la liste.
                $liste[] = $pays;
            }
        } catch (Exception $e) {
            // Gestion des erreurs en cas de problème avec la base de données.
            echo 'Erreur lors de la récupération des pays : ' . $e->getMessage();
        }

        // Retourne la liste des pays sous forme d'un tableau d'objets Pays.
        return $liste;
    }
}
?>
