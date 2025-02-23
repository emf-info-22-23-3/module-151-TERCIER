<?php 
  /**
  * Classe Login
  *
  * Cette classe représente un Login.
  *
  */
  class Login
  {
    /**
    * Variable représentant le nom du Login
    * @access private
    * @var string
    */
    private $nom;
    /**
    * Variable représentant la pk du Login
    * @access private
    * @var integer
    */
    private $pk_login;
    
    /**
    * Constructeur de la classe Login
    *
    * @param int $pk_login. PK du login
    * @param string nom. Nom du login
    * @param int $dossardCoureur. Numéro de dossard du coureur
    */
    public function __construct($pk_login, $nom)
    {
      $this->nom = $nom;
      $this->pk_login = $pk_login;    
    }
    
    /**
    * Fonction qui retourne le nom du login.
    *
    * @return nom du login.
    */
    public function getNom()
    {
      return $this->nom;
    }
    
    /**
    * Fonction qui retourne la pk du login.
    *
    * @return pk du login.
    */
    public function getPklogin()
    {
      return $this->pk_login;
    }
    
  }
?>