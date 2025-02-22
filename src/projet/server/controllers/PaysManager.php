<?php
class PaysManager
{
    private $paysBDManager;

    public function __construct()
    {
        $this->paysBDManager = new PaysBDManager();
    }

    public function getAllPays()
    {
        return $this->paysBDManager->readPays();
    }
}
?>