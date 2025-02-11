<?php 
  /**
  * Classe Volcan
  *
  * Cette classe représente un Volcan.
  *
  */
  class Volcan
  {
    /**
    * Variable représentant le nom du Volcan
    * @access private
    * @var string
    */
    private $nom;
    /**
    * Variable représentant la pk du Volcan
    * @access private
    * @var integer
    */
    private $pk_Volcan;

    /**
    * Variable représentant l'altitude du Volcan
    * @access private
    * @var float
    */
    private $altitude;

    /**
    * Variable représentant la latitude du Volcan
    * @access private
    * @var float
    */
    private $latitude;

    /**
    * Variable représentant la longitude du Volcan
    * @access private
    * @var float
    */
    private $longitude;    

    /**
    * Variable représentant la pk du pays du Volcan
    * @access private
    * @var integer
    */
    private $pk_Pays;

    /**
     * Constructeur de la classe Volcan
     *
     * @param int    $pk_Volcan Identifiant unique du volcan
     * @param string $nom Nom du volcan
     * @param float  $altitude Altitude du volcan
     * @param float  $latitude Latitude du volcan
     * @param float  $longitude Longitude du volcan
     * @param int    $pk_Pays Identifiant du pays
     */
    public function __construct($pk_Volcan, $nom, $altitude, $latitude, $longitude, $pk_Pays)
    {
        $this->pk_Volcan = $pk_Volcan;
        $this->nom = $nom;
        $this->altitude = $altitude;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->pk_Pays = $pk_Pays;
    }
    
    /** @return string Nom du volcan */
    public function getNom()
    {
        return $this->nom;
    }

    /** @return int Identifiant du volcan */
    public function getPkVolcan()
    {
        return $this->pk_Volcan;
    }

    /** @return float Altitude du volcan */
    public function getAltitude()
    {
        return $this->altitude;
    }

    /** @return float Latitude du volcan */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /** @return float Longitude du volcan */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /** @return int Identifiant du pays */
    public function getPkPays()
    {
        return $this->pk_Pays;
    }
    
    /**
    * Fonction qui retourne le contenu du bean au format XML
    * @return le contenu du bean au format XML
    *
    public function toXML()
    {
      $result = '<Volcan>';
      $result = $result . '<pk_Volcan>'.$this->getPkVolcan().'</pk_Volcan>';
      $result = $result . '<nom>'.$this->getNom().'</nom>';
      $result = $result . '</Volcan>';
      return $result;
    }*/
  }
?>