<?php
require_once __DIR__ . '/../database/DataBase.php';
require_once __DIR__ . '/../security/AuthRules.php';
/**
 * Representa um usuário no sistema
 */
class User{

	/**
	 * DDD do telefone
	 * @var string
	 */
	protected $ddd;

	/**
	 * Email primário do usuário
	 * @var string
	 */
	protected $email;

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
	 * Telefone de contato
	 * @var string
	 */
	protected $telefone;

	/**
	 * Nome real do usuário
	 * @var string
	 */
	protected $realName;

	/**
	 * Email alternativo do usuário
	 * @var string
	 */
	protected $anotherEmail;

	/**
	 * Identificação do usuário
	 * @var string | int
	 */
	protected $userId;

	/**
	 * Status do usuário
	 * @var boolean
	 */
	protected $status;

	/**
	 * Indica se um admnistrador
	 * @var boolean
	 */
	protected $admin;

	/**
	 * Ids dos parceiros vinculados a este usuário
	 * @var array : int
	 */
	protected $arrBoundedPartners;

	public function __construct(){
		$this->arrBoundedPartners = array();
	}

	public function getDdd(){
		return $this->ddd;
	}

	public function setDdd($ddd){
		$this->ddd = $ddd;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;
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

	public function getTelefone(){
		return $this->telefone;
	}

	public function setTelefone($telefone){
		$this->telefone = $telefone;
	}

	public function getRealName(){
		return $this->realName;
	}

	public function setRealName($realName){
		$this->realName = $realName;
	}

	public function getAnotherEmail(){
		return $this->anotherEmail;
	}

	public function setAnotherEmail($anotherEmail){
		$this->anotherEmail = $anotherEmail;
	}

	public function getUserId(){
		return $this->userId;
	}

	public function setUserId($userId){
		$this->userId = trim($userId);
	}

	public function getStatus(){
		return $this->status;
	}

	public function setStatus($status){

		if(!is_bool($status)){

			$status = trim(strtolower($status));

			if($status == "true"){
				$status = true;
			}else{
				$status = false;
			}
		}

		$this->status = $status;
	}

	public function getArrBoundedPartners(){
		return $this->arrBoundedPartners;
	}

	public function setArrBoundedPartners($arrBoundedPartners){
		$this->arrBoundedPartners = $arrBoundedPartners;
	}

	/**
	 * Apaga os dados do usuário.
	 * Para não dar pau é necessário setar a id do usuário
	 * @throws Exception : Id da usuário não informada ou falha na execução do banco de dados
	 */
	public function erase(){

		if($this->userId == "") throw new Exception("Id do usuário não informado. Use setUserId()");

		$db = new DataBase(new V3DbOperations());

		//Primeiro apagamos as relações com os parceiros
		$query = 'delete from "tbl_usuario_Parceiro" where "id_usuario" = ' . $this->userId;
		$ok = $db->execNoReturnableSql($query);
		if(!$ok) throw new Exception("Falha ao executar a query que apaga os vinculos com parceiros do usuário com id " . $this->userId);

		//Depois os dados do usuário em si
		$query = 'delete from "tbl_usuario" where "id_usuario" = ' . $this->userId;
		$ok = $db->execNoReturnableSql($query);
		if(!$ok) throw new Exception("Falha ao executar a query que apaga os dados do usuário com id " . $this->userId);
	}

	/**
	 * Carrega os dados do usuário.
	 * Para não dar pau é necessário setar a id do usuário
	 * @throws Exception : Id da usuário não informada ou falha na execução do banco de dados
	 * @return true : Usuário existe - carregado com sucesso | false : Usuário não existe 
	 */
	public function load(){

		if($this->userId == "") throw new Exception("Id do usuário não informado. Use setUserId()");

		$db = new DataBase(new V3DbOperations());
		$arrPartnersIds = array();

		//Recuperando os dados do usuário
		$query = 'SELECT "login", "nome", "email", "emailAlternativo", "ddd", "telefone", "status", "dataDeInclusao", "admin" from tbl_usuario where id_usuario = ' . $this->userId;
		$ok = $db->execReturnableSql($query);
		if(!$ok) throw new Exception("Falha ao executar a query que recupera os dados do usuário " . $this->getLogin());

		$arrResult = $db->getFetchArray();

		//Só preciso da primeira linha, mesmo porque só vai retornar uma linha mesmo...
		$arrResult = $arrResult[0];

		//O usuário não existe
		if(count($arrResult) == 0) return false;

		$this->setDdd($arrResult["ddd"]);
		$this->setEmail($arrResult["email"]);
		$this->setLogin($arrResult["login"]);
		$this->setAdmin($arrResult["admin"]);
		$this->setStatus($arrResult["status"]);
		$this->setTelefone($arrResult["telefone"]);
		$this->setRealName($arrResult["nome"]);
		$this->setAnotherEmail($arrResult["emailAlternativo"]);

		//Recuperando os dados dos parceiros vinculados ao usuário
		$query = 'select id_parceiro from "tbl_usuario_Parceiro" where id_usuario = ' . $this->userId;
		$ok = $db->execReturnableSql($query);
		if(!$ok) throw new Exception("Falha ao executar a query que os vinculos com parceiros para o usuário " . $this->getLogin());

		$arrResult = $db->getFetchArray();
		foreach ($arrResult as $line) {
			$arrPartnersIds[] = $line[0];
		}

		if(count($arrPartnersIds) > 0){
			$this->setArrBoundedPartners($arrPartnersIds);
		}

		//Deu tudo certo!
		return true;
	}

	/**
	 * Salva o usuário na base de dados
	 * @return iny : id do usuário salvo
	 * @throws Exception : Alguma falha no meio do caminho
	 */
	public function save(){

		$db = new DataBase(new V3DbOperations());

		//Verificando se o login já existe
		$query = 'select "id_usuario" from tbl_usuario where "login" = \'' . $this->getLogin() . '\'';
		$db->execReturnableSql($query);
		$arrResult = $db->getFetchArray();

		//Inserindo um novo usuário
		if($this->getUserId() == ""){

			//Se o usuário existir dá erro
			if(isset($arrResult[0][0])) throw new Exception("O usuário " . $this->getLogin() . " já existe, escolha outro nome para um novo usuário.");

			$query = 'insert into tbl_usuario ("login", "senha", "nome", "email", "emailAlternativo", "status", "ddd",  "telefone", "admin") values (\'' . $this->getLogin() . '\',\'' . strtolower(md5($this->getPassword())) . '\',\'' . $this->getRealName() . '\',\'' . $this->getEmail() . '\',\'' . $this->getAnotherEmail() . '\',\'' . ($this->getStatus() ? "true" : "false") . '\',\'' .  $this->getDdd() . '\',\'' . $this->getTelefone() . '\',\'' . ($this->isAdmin() ? "true" : "false") . '\') returning "id_usuario"';
			$ok = $db->execReturnableSql($query);
			if(!$ok) throw new Exception("Falha ao executar a query de inserção de usuário para " . $this->getLogin());

			$arrResult = $db->getFetchArray();
			$this->setUserId($arrResult[0][0]);

		}else{

			//Se o usuário NÃO existir dá erro
			if(!isset($arrResult[0][0])) throw new Exception("O usuário " . $this->getLogin() . " não existe, não é possível atualiza-lo.");

			$query = 'update tbl_usuario set
			"login" = \'' . $this->getLogin() . '\', "senha" = \'' . strtolower(md5($this->getPassword())) . '\', 
			"nome" = \'' . $this->getRealName() . '\', "email" = \'' . $this->getEmail() . '\', 
			"emailAlternativo" = \'' . $this->getAnotherEmail() . '\', "status" = \'' . ($this->getStatus() ? "true" : "false") . '\',
			"admin" = \'' . ($this->isAdmin() ? "true" : "false") . '\', 
			"ddd" = \'' . $this->getDdd() . '\', "telefone" = \'' . $this->getTelefone() . '\' where "id_usuario" = \'' . $this->getUserId() . '\'';

			$ok = $db->execNoReturnableSql($query);

			if(!$ok) throw new Exception("Falha ao executar a query de atualização de usuário para: " . $this->getLogin());
		}

		//Se não houverem parceiros para vincular retorna a id do usuário e sai do procedimento
		if(count($this->arrBoundedPartners) == 0) return $this->getUserId();


		//Apaga as relações anteriores com os parceiros, se houverem
		$query = 'delete from "tbl_usuario_Parceiro" where "id_usuario" = \'' . $this->userId . '\'';
		$ok = $db->execNoReturnableSql($query);
		if(!$ok){
			throw new Exception("Falha ao executar a consulta que apaga os vinculos do usuário " . $this->getLogin() . " com o fornecedor de id " . $partnerId);
		}

		//Vinculando o usuário aos parceiros
		foreach ($this->arrBoundedPartners as $partnerId) {

			//Insere a relação atual
			$query = 'insert into "tbl_usuario_Parceiro" ("id_usuario", "id_parceiro") values (\'' . $this->userId . '\',\'' . $partnerId . '\')';
			$ok = $db->execNoReturnableSql($query);
			if(!$ok){
				throw new Exception("Falha ao executar a consulta que vincula o usuário " . $this->getLogin() . " com o fornecedor de id " . $partnerId);
			}
		}

		//Retorna a id do usuário
		return $this->getUserId();
	}

	/**
	 * Retorna se o usuário é administrador
	 * @return boolean
	 */
	public function isAdmin(){
		return $this->admin;
	}

	/**
	 * Seta se o usuário é adminstrador
	 * @param boolean $admin
	 */
	public function setAdmin($admin){

		if(!is_bool($admin)){

			$admin = trim(strtolower($admin));

			if($admin == "true"){
				$admin = true;
			}else{
				$admin = false;
			}
		}

		$this->admin = $admin;
	}
}
?>