<?php
require_once __DIR__ . '/User.php';
require_once __DIR__ . '/../database/Database.php';

/**
 * Responsável por listar os usuários do sistema 
 */
class UserLister{

	/**
	 * @var DataBase
	 */
	private $database;

	/**
	 * Lista de usuários carregados
	 * @var array : User
	 */
	private $arrUsers;

	public function __construct(){
		$this->database = new DataBase(new V3DbOperations());
		$this->arrUsers = array();
	}

	/**
	 * Carrega os usuários ativos
	 */
	public function loadActiveUsers(){
		
		$this->arrUsers = array();

		$query = 'select id_usuario from tbl_usuario where status = true';
		$ok = $this->database->execReturnableSql($query);
		if(!$ok) throw new SystemException("A consulta de recuperação de usuários ativos falhou.",__CLASS__ .__LINE__);

		$arrResults = $this->database->getFetchArray();

		foreach ($arrResults as $result) {
			$user = new User();
			$user->setUserId($result[0]);
			$user->load();
			$this->arrUsers[$result[0]] = $user;
		}
	}

	/**
	 * Carrega os usuários inativos
	 */
	Public function loadNonActiveUsers(){
		
		$this->arrUsers = array();
		
		$query = 'select id_usuario from tbl_usuario where status = false';
		$ok = $this->database->execReturnableSql($query);
		if(!$ok) throw new SystemException("A consulta de recuperação de usuários não ativos falhou.",__CLASS__ .__LINE__);

		$arrResults = $this->database->getFetchArray();

		foreach ($arrResults as $result) {
			$user = new User();
			$user->setUserId($result[0]);
			$user->load();
			$this->arrUsers[$result[0]] = $user;
		}
	}

	/**
	 * Retorna os resultados carregados
	 */
	public function getArrUsers(){
		return $this->arrUsers;
	}
}
?>