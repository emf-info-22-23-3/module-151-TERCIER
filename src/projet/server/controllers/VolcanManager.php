<?php //postman check methode
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

    public function addVolcan( $volcan)
    {
        return $this->handleRequest(fn() => $this->volcanBDManager->addVolcan( $volcan));
    }

    public function modifyExistingVolcan($pk, $volcan)
    {
        return $this->handleRequest(fn() => $this->volcanBDManager->modifyVolcan($pk, $volcan));
    }

    public function deleteVolcanById($pk)
    {
        return $this->handleRequest(fn() => $this->volcanBDManager->deleteVolcan($pk));
    }
}/*
?>*/


?>