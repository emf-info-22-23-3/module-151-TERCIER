<?php 
  /**
  * Classe Volcan
  *
  * Cette classe représente un Volcan avec ses caractéristiques principales.
  *
  */
  class Volcan
  {
    /**
    * Nom du Volcan
    * @access private
    * @var string
    */
    private $nom;
    
    /**
    * Identifiant unique du Volcan
    * @access private
    * @var integer
    */
    private $pk_Volcan;

    /**
    * Altitude du Volcan en mètres
    * @access private
    * @var float
    */
    private $altitude;

    /**
    * Latitude du Volcan
    * @access private
    * @var float
    */
    private $latitude;

    /**
    * Longitude du Volcan
    * @access private
    * @var float
    */
    private $longitude;    

    /**
    * Identifiant du pays où se situe le Volcan
    * @access private
    * @var integer
    */
    private $pk_Pays;

    /**
     * Constructeur de la classe Volcan
     *
     * Initialise un objet Volcan avec ses attributs.
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
    
    /**
    * Retourne le nom du volcan.
    *
    * @return string Nom du volcan
    */
    public function getNom()
    {
        return $this->nom;
    }

    /**
    * Retourne l'identifiant unique du volcan.
    *
    * @return int Identifiant du volcan
    */
    public function getPkVolcan()
    {
        return $this->pk_Volcan;
    }

    /**
    * Retourne l'altitude du volcan en mètres.
    *
    * @return float Altitude du volcan
    */
    public function getAltitude()
    {
        return $this->altitude;
    }

    /**
    * Retourne la latitude du volcan.
    *
    * @return float Latitude du volcan
    */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
    * Retourne la longitude du volcan.
    *
    * @return float Longitude du volcan
    */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
    * Retourne l'identifiant du pays où se situe le volcan.
    *
    * @return int Identifiant du pays
    */
    public function getPkPays()
    {
        return $this->pk_Pays;
    }

    /**
     * Convertit l'objet Volcan en une chaîne de caractères.
     *
     * @return string Nom du volcan
     */
    public function __toString() {
        return $this->nom; 
    }

    /**
    * Retourne le contenu du bean Volcan au format XML.
    * 
    * @return string Représentation XML du volcan
    */
    public function toXML()
    {
        $xml = "<volcan>";
        $xml .= "<id>" . $this->pk_Volcan . "</id>";
        $xml .= "<nom>" . $this->nom . "</nom>";
        $xml .= "<altitude>" . $this->altitude . "</altitude>";
        $xml .= "<latitude>" . $this->latitude . "</latitude>";
        $xml .= "<longitude>" . $this->longitude . "</longitude>";
        $xml .= "<pays>" . $this->pk_Pays . "</pays>";
        $xml .= "</volcan>";
        return $xml;
    }
  }
?>
