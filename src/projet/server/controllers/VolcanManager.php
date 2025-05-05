<?php 
// Vérification avec Postman

/**
 * Classe VolcanManager
 *
 * Cette classe permet la gestion des volcans en interagissant avec VolcanBDManager
 * et en vérifiant l'authentification via SessionManager.
 * 
 * @version 3.0 / 06.05.2025
 * @author Tercicer Colin
 * @project Project
 */
class VolcanManager
{
    /**
     * Instance de VolcanBDManager pour la gestion des données des volcans.
     * @var VolcanBDManager
     */
    private $volcanBDManager;

    /**
     * Instance de SessionManager pour la gestion des sessions utilisateurs.
     * @var SessionManager
     */
    private $sessionManager;

    /**
     * Constructeur de la classe VolcanManager
     *
     * Initialise un gestionnaire de base de données pour les volcans et un gestionnaire de session.
     */
    public function __construct()
    {
        $this->volcanBDManager = new VolcanBDManager();
        $this->sessionManager = new SessionManager();
    }

    /**
     * Vérifie si l'utilisateur est authentifié.
     *
     * @return bool Retourne true si l'utilisateur est connecté, sinon false.
     */
    private function isAuthenticated(): bool
    {
        return $this->sessionManager->isConnected();
    }

    /**
     * Gère une requête en vérifiant l'authentification avant d'exécuter l'opération demandée.
     *
     * @param callable $callback Fonction de traitement de la requête.
     * @return string Réponse JSON contenant le statut et les données.
     */
    private function handleRequest(callable $callback)
    {
        if (!$this->isAuthenticated()) {
            http_response_code(401);
            return json_encode(["status" => "error", "message" => "Accès refusé. Authentification requise."]);
        }

        return json_encode(["status" => "success", "data" => $callback()]);
    }

    /**
     * Récupère la liste de tous les volcans enregistrés.
     *
     * @return array Liste des volcans.
     */
    public function getAllVolcans()
    {
        return $this->volcanBDManager->readVolcans();
    }

    /**
     * Récupère le volcan recherché.
     *
     * @return array volcan recherché.
     */
    public function getVolcanFiltered($nom, $pays){
        return $this->volcanBDManager->getVolcansFiltered($nom, $pays);
    }

    /**
     * Ajoute un volcan après vérification de l'authentification.
     *
     * @param mixed $volcan Données du volcan à ajouter.
     * @return string Réponse JSON contenant le statut de l'opération.
     */
    public function addVolcan($volcan)
    {
        return $this->handleRequest(fn() => $this->volcanBDManager->addVolcan($volcan));
    }

    /**
     * Modifie un volcan existant après vérification de l'authentification.
     *
     * @param int $pk Identifiant du volcan à modifier.
     * @param mixed $volcan Nouvelles données du volcan.
     * @return string Réponse JSON contenant le statut de l'opération.
     */
    public function modifyExistingVolcan($pk, $volcan)
    {
        return $this->handleRequest(fn() => $this->volcanBDManager->modifyVolcan($pk, $volcan));
    }

    /**
     * Supprime un volcan par son identifiant après vérification de l'authentification.
     *
     * @param int $pk Identifiant du volcan à supprimer.
     * @return string Réponse JSON contenant le statut de l'opération.
     */
    public function deleteVolcanById($pk)
    {
        return $this->handleRequest(fn() => $this->volcanBDManager->deleteVolcan($pk));
    }
}



?>
