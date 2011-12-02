<?php
require_once 'IAuthenticationRules.php';
require_once __DIR__ . '/../../database/Database.php';
require_once __DIR__ . '/../../configuration/security/authentication/UserAuthRulesConf.php';

class UserAuthRules implements IAuthenticationRules{

	/**
	 *
	 * @var User
	 */
	private $user;
	
	public function __construct(User $user = null){
		$this->user = $user;
	}

	/**
	 * Seta o user do usuário
	 * @return string
	 */
	public function setUser(User $user){
		$this->user = $user;
	}

	public function checkAuthenticationData(){

		//Monta os argumentos da view que recupera o id do usuário
		$arrViewArguments = array(
		"key" => '[
		{"' . ReflectionProperty::IS_PROTECTED . '":"' . $this->user->getLogin() . '"},
		{"' . ReflectionProperty::IS_PROTECTED . '":"' . $this->user->getPassword() . '"}
		]'
		);

		//Executa a view
		$database = new Database();
		$database->databaseSelect(Configuration::CONST_DB_NAME);
		$database->executeView(UserAuthRulesConf::CONST_USER_LOGINS_VIEW, $arrViewArguments);

		//Verifca se foram retornados os dados esperados
		$results = $database->getResponse();
		if(count($results->rows) > 0){

			//Se sim guarda a id do usuário
			$this->autenticationId = $results->rows[0]->id;
			return true;
		}

		//Se chegar até aqui é porque o usuário ou senha são inválidos
		$this->autenticationId = null;
		return false;
	}

	public function getAutenticationId(){
		return $this->autenticationId;
	}
}
?>