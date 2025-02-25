<?php 
  /**
  * Classe Login
  *
  * Cette classe représente un Login avec une clé primaire et un nom.
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
    * Variable représentant la clé primaire (PK) du Login
    * @access private
    * @var integer
    */
    private $pk_login;
    
    /**
    * Constructeur de la classe Login
    *
    * Initialise un objet Login avec une clé primaire et un nom.
    *
    * @param int $pk_login Clé primaire du login
    * @param string $nom Nom du login
    */
    public function __construct($pk_login, $nom)
    {
      $this->nom = $nom;
      $this->pk_login = $pk_login;    
    }
    
    /**
    * Retourne le nom du login.
    *
    * Cette méthode permet d'accéder à la propriété privée $nom.
    *
    * @return string Nom du login.
    */
    public function getNom()
    {
      return $this->nom;
    }
    
    /**
    * Retourne la clé primaire du login.
    *
    * Cette méthode permet d'accéder à la propriété privée $pk_login.
    *
    * @return int Clé primaire du login.
    */
    public function getPklogin()
    {
      return $this->pk_login;
    }
  }
?>
