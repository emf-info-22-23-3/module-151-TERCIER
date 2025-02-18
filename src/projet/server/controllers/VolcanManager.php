<?php 
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

    public function addNewVolcan($auteur, $volcan)
    {
        return $this->volcanBDManager->addVolcan($auteur, $volcan);
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

?>