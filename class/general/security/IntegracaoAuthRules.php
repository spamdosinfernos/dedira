<?php
require_once __DIR__ . '/IAuthenticationRules.php';
require_once __DIR__ . '/../database/direct/V3DbOperations.php';
require_once __DIR__ . '/../database/DataBase.php';
require_once __DIR__ . '/../database/IDataBaseOperations.php';

class IntegracaoAuthRules implements IAuthenticationRules{
	
	private $login;
	
	private $password;

	/**
	 * Seta o login do usuário
	 * @return string
	 */
	public function setLogin($login){
		$this->login = $login;
	}

	/**
	 * Seta a senha do usuário
	 * @return string
	 */
	public function setPassword($password){
		$this->password = strtolower(md5($password));
	}

	public function verifyUserAndPassword(){

		$database = new DataBase(new V3DbOperations());

		$sql = "select id_usuario from tbl_usuario where login = '$this->login' and senha = '$this->password'";

		$flag = $database->execReturnableSql($sql);

		if($flag){
			$arrResultado = $database->getFetchArray();

			if(count($arrResultado) > 0){
				$this->autenticationId = $arrResultado[0][0];
				return true;
			}

			return false;
		}

		//Não pode chegar até aqui, mas se chegar pelo menos saberemos oque aconteceu
		$log = new Log();
		$log->addMessage("Falha ao verificar login: " . $sql);
		$log->setSaveDirectory(Configuration::getLogDirectoryPath());
		$log->setLogFileName(__CLASS__ . ".log");
		$log->save();
		
		throw new Exception("Falha ao verificar login: " . $sql);

	}

	public function getAutenticationId(){
		return $this->autenticationId;
	}
}
?>