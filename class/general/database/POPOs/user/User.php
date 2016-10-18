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
	 * Indica qual o grupo de acesso o usuário pertence
	 *
	 * @var Group
	 */
	protected $accessGroup;
	
	/**
	 * User constructor
	 */
	public function __construct() {
		$this->active = true;
	}
	public function getLogin() {
		return $this->login;
	}
	public function setLogin(string $login) {
		$this->login = $login;
		$this->AddChange ( "login", $login );
	}
	public function getPassword() {
		return $this->password;
	}
	public function setPassword(string $password) {
		$this->password = $password;
		$this->AddChange ( "password", $password );
	}
	public function getActive(): bool {
		return $this->active;
	}
	public function setActive(bool $active) {
		$this->active = $active;
		$this->AddChange ( "active", $active );
	}
	public function getAccessGroup() {
		return $this->accessGroup;
	}
	public function setAccessGroup(Group $accessGroup) {
		$this->accessGroup = $accessGroup;
		$this->AddChange ( "accessGroup", $accessGroup );
	}
}
?>