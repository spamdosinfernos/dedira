<?php
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../security/AuthRules.php';
require_once __DIR__ . '/../person/Person.php';

/**
 * Representa um usuário no sistema
 */
class User extends Person{

	/**
	 * Login
	 * @var string
	 */
	protected $login;

	/**
	 * Senha
	 * @var string
	 */
	protected $password;

	/**
	 * Identificação do usuário
	 * @var string | int
	 */
	protected $userId;

	/**
	 * Indica se o usuário está ativo ou não
	 * @var boolean
	 */
	protected $active;
	
	private $teste;

	/**
	 * Indica qual o grupo de acesso o usuário pertence
	 * @var AccessGroup
	 */
	protected $accesGroup;

	public function __construct(){
		parent::setDataBaseName(Configuration::CONST_DB_NAME_PEOPLE);
	}


	public function getLogin(){
	    return $this->login;
	}

	public function setLogin($login){
	    $this->login = $login;
	}

	public function getPassword(){
	    return $this->password;
	}

	public function setPassword($password){
	    $this->password = $password;
	}

	public function getActive(){
	    return $this->active;
	}

	public function setActive($active){
	    $this->active = $active;
	}

	public function getAccesGroup(){
	    return $this->accesGroup;
	}

	public function setAccesGroup($accesGroup){
	    $this->accesGroup = $accesGroup;
	}
}
?>