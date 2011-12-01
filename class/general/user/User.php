<?php
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../person/Person.php';

/**
 * Representa um usuário no sistema
 *
 * @author tatupheba
 *
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

	/**
	 * Indica qual o grupo de acesso o usuário pertence
	 * @var Group
	 */
	protected $accessGroup;

	public function __construct(){
		$this->active = true;
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

		if(!is_bool($active)) throw new SystemException("Um valor booleano deve ser informado.", __CLASS__.__LINE__);

		$this->active = $active;
	}

	public function getAccessGroup(){
		return $this->accessGroup;
	}

	public function setAccessGroup(Group $accessGroup){
		$this->accessGroup = $accessGroup;
	}
}
?>