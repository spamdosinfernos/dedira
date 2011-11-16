<?php
require_once __DIR__ . '/IDataBaseOperations.php';
require_once __DIR__ . '/../log/Log.php';

/**
 * Representa o relatório dos tiquetes no sistema
 */
Class DataBase{
	/**
	 * Guarda a conexão com o banco de dados
	 * @var IDataBaseOperations
	 */
	private $dataBaseInterface;

	/**
	 * Guarda result da consulta
	 * @var array
	 */
	private $result;


	/**
	 * É necessário fornecer os dados de conexão (via IDataBaseOperations) para manipular o banco de dados 
	 * @param IDataBaseOperations $dataBaseInterface
	 */
	public function __construct(IDataBaseOperations $dataBaseInterface){
		$this->dataBaseInterface = $dataBaseInterface;
	}


	/**
	 * Abre a conexão com o banco de dados
	 * @throws Exception: Não foi possível conectar ao banco de dados
	 */
	private function openConnection(){
		try{
			$this->dataBaseInterface->openConnection();
		}catch (Exception $e){
			new Log("Não foi possível conectar ao banco de dados: " . $e->getMessage());
			throw new Exception("Não foi possível conectar ao banco de dados: " . $e->getMessage());
		}
	}

	/**
	 * Executa uma consulta sql que não retorna linhas de result,
	 * este procedimento atualiza a propriedade $this->result com o
	 * número de linhas afetadas
	 * @example delete from tabela where ...
	 * @example update tabela where ...
	 * @param string $query
	 * @return boolean
	 */
	public function execNoReturnableSql($query){

		//Verifica se a conexao com o banco de dados foi realizada
		try{
			$this->openConnection();
		}catch (Exception $e){
			return false;
		}

		try {
			$this->result = $this->dataBaseInterface->execQuery($query);
			if(!$this->result){
				$this->dataBaseInterface->closeConnection();
				return false;
			}
		}catch (Exception $e){
			return false;
		}

		$this->dataBaseInterface->closeConnection();;

		return true;
	}

	/**
	 * Executa uma consulta sql que retorna linhas de result
	 * este procedimento atualiza a propriedade $this->result com as
	 * linhas retornadas
	 * @example select * from tabela where ...
	 * @param string $query
	 * @return boolean
	 */
	public function execReturnableSql($query){

		//Verifica se a conexao com o banco de dados foi realizada
		$this->openConnection();
		
		//Executa a consulta
		$this->result = $this->dataBaseInterface->execQuery($query);

		try {
			if(!$this->result) return false;
		}catch (Exception $e){
			return false;
		}

		$this->dataBaseInterface->closeConnection();
		return true;
	}

	/**
	 * Executa uma procedure
	 * @param string $procedure
	 * @return boolean
	 */
	public function execProcedure($procedure){
		return $this->execReturnableSql($this->dataBaseInterface->getProcedureCallToken() . $procedure);
	}

	public function getResult(){
		return $this->result;
	}

	public function getRowsCount(){
		return $this->dataBaseInterface->getRowsCount();
	}

	public function getFetchArray(){
		return $this->dataBaseInterface->getFetchArray();
	}
}
?>