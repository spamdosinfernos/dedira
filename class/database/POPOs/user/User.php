<?php
require_once __DIR__ . '/../person/Person.php';
/**
 * Representa um usuário no sistema
 *
 * @author André Furlan
 */
class User extends Person {
	
	/**
	 * Login
	 *
	 * @var string
	 */
	protected $login;
	
	/**
	 * Senha
	 *
	 * @var string
	 */
	protected $password;
	
	/**
	 * Indica se o usuário está ativo ou não
	 *
	 * @var boolean @Column(nullable = false)
	 */
	protected $active;
	
	/**
	 * User constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->active = true;
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getLogin() {
		return $this->login;
	}
	
	/**
	 *
	 * @param
	 *        	$login
	 */
	public function setLogin(string $login) {
		$this->login = $login;
		$this->AddChange ( "login", $login );
		return $this;
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getPassword() {
		return $this->password;
	}
	
	/**
	 *
	 * @param
	 *        	$password
	 */
	public function setPassword(string $password) {
		$this->password = $password;
		$this->AddChange ( "password", $password );
		return $this;
	}
	
	/**
	 *
	 * @return the boolean
	 */
	public function getActive() {
		return $this->active;
	}
	
	/**
	 *
	 * @param
	 *        	$active
	 */
	public function setActive(bool $active) {
		$this->active = $active;
		$this->AddChange ( "active", $active );
		return $this;
	}
}
?>