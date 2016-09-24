<?php
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../person/Person.php';

/**
 * Representa um usuário no sistema
 *
 * @author André Furlan
 *         @Entity
 */
class User extends Person {
	
	/**
	 * Login
	 * 
	 * @var string @Column(nullable = false)
	 */
	protected $login;
	
	/**
	 * Senha
	 * 
	 * @var string @Column(nullable = false)
	 */
	protected $password;
	
	/**
	 * Identificação do usuário
	 * 
	 * @var int @Id
	 *      @GeneratedValue
	 */
	protected $id;
	
	/**
	 * Indica se o usuário está ativo ou não
	 * 
	 * @var boolean @Column(nullable = false)
	 */
	protected $active;
	
	/**
	 * Indica qual o grupo de acesso o usuário pertence
	 * 
	 * @var Group @Column(nullable = false)
	 */
	protected $accessGroup;
	public function __construct() {
		$this->active = true;
	}
	public function getLogin() {
		return $this->login;
	}
	public function setLogin($login) {
		$this->login = $login;
	}
	public function getPassword() {
		return $this->password;
	}
	public function setPassword($password) {
		$this->password = $password;
	}
	public function getActive(): bool {
		return $this->active;
	}
	public function setActive(bool $active) {
		if (! is_bool ( $active ))
			throw new SystemException ( "Um valor booleano deve ser informado.", __CLASS__ . __LINE__ );
		
		$this->active = $active;
	}
	public function getAccessGroup() {
		return $this->accessGroup;
	}
	public function setAccessGroup(Group $accessGroup) {
		$this->accessGroup = $accessGroup;
	}
}
?>