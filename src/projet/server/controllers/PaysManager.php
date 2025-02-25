<?php
/**
 * Classe PaysManager
 *
 * Cette classe permet la gestion des pays en interagissant avec le gestionnaire de base de données PaysBDManager.
 */
class PaysManager
{
    /**
     * Instance de PaysBDManager pour la gestion des données des pays.
     * @var PaysBDManager
     */
    private $paysBDManager;

    /**
     * Constructeur de la classe PaysManager
     *
     * Initialise un gestionnaire de base de données pour les pays.
     */
    public function __construct()
    {
        $this->paysBDManager = new PaysBDManager();
    }

    /**
     * Récupère la liste de tous les pays enregistrés.
     *
     * @return array Liste des pays
     */
    public function getAllPays()
    {
        return $this->paysBDManager->readPays();
    }
}
?>
