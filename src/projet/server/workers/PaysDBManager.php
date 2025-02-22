<?php 
	include_once(__DIR__.'/Connection.php');
	include_once(__DIR__.'/../beans/Pays.php');

        
	/**
	* Classe PaysBDManager
	*
	* Cette classe permet la gestion des Payss dans la base de données 
	*
	* @version 1.0
	* @author Tercicer colin
	* @project project
	*/
	class PaysBDManager
{
    /**
    * Fonction permettant la lecture des Pays.
    * @return array Liste de Pays
    */
    public function readPays()
    {
        $liste = [];
        try {
            $connection = new Connection();
            $query = $connection->SelectQuery("SELECT * FROM t_pays");

            foreach ($query as $data) {
                // Assuming 'PK_pays' and 'nom' are the correct column names
                $pays = new Pays($data['PK_pays'], $data['nom']);
                $liste[] = $pays;
            }
        } catch (Exception $e) {
            // Handle database errors
            echo 'Error fetching countries: ' . $e->getMessage();
        }

        return $liste;
    }
}

?>