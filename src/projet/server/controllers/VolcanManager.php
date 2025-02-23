<?php //postman check methode
class VolcanManager
{
    private $volcanBDManager;

    public function __construct()
    {
        $this->volcanBDManager = new VolcanBDManager();
    }

    public function getAllVolcans()
    {
        return $this->volcanBDManager->readVolcans();
    }

    public function addVolcan($volcan)
    {
        return $this->volcanBDManager->addVolcan( $volcan);
    }

    public function modifyExistingVolcan($pk, $volcan)
    {
        return $this->volcanBDManager->modifyVolcan($pk, $volcan);
    }

    public function deleteVolcanById($pk)
    {
        return $this->volcanBDManager->deleteVolcan($pk);
    }
}

/*<?php
include_once(__DIR__ . '/../controllers/SessionManager.php'); // Assurez-vous d'inclure SessionManager

class VolcanManager
{
    private $volcanBDManager;
    private $sessionManager;

    public function __construct()
    {
        $this->volcanBDManager = new VolcanBDManager();
        $this->sessionManager = new SessionManager();
    }

    private function isAuthenticated(): bool
    {
        return $this->sessionManager->isConnected();
    }

    private function handleRequest(callable $callback)
    {
        if (!$this->isAuthenticated()) {
            return json_encode(["status" => "error", "message" => "Accès refusé. Authentification requise."]);
        }

        return json_encode(["status" => "success", "data" => $callback()]);
    }

    public function getAllVolcans()
    {
        return $this->volcanBDManager->readVolcans();
    }

    public function addNewVolcan($auteur, $volcan)
    {
        return $this->handleRequest(fn() => $this->volcanBDManager->addVolcan($auteur, $volcan));
    }

    public function modifyExistingVolcan($pk, $volcan)
    {
        return $this->handleRequest(fn() => $this->volcanBDManager->modifyVolcan($pk, $volcan));
    }

    public function deleteVolcanById($pk)
    {
        return $this->handleRequest(fn() => $this->volcanBDManager->deleteVolcan($pk));
    }
}
?>
*/

?>