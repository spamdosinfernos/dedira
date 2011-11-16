<?php
require_once __DIR__ . '/../IDataBaseOperations.php';
require_once __DIR__ . '/../../configuration/Configuration.php';

/**
 * Operações do banco de dados para o servidor W
 */
class W3DbOperations implements IDataBaseOperations{

	private $conexao = null;

	/**
	 * Guarda o objeto que executa e retorna os resultados da consulta
	 * @var PDOStatement
	 */
	private $resultado;

	/**
	 * Abre a conexão com o banco de dados
	 * @throws Exception: Não foi possível conectar ao banco de dados
	 */
	public function openConnection(){
		$dsn = "mysql:host=" . Configuration::CONST_W3_DATABASE_URL . ";dbname=" . Configuration::CONST_W3_DATABASE_BASENAME . ";port=" . Configuration::CONST_W3_DATABASE_PORT;
		//$dsn = "mysql:host=" . Configuration::CONST_W3_DATABASE_URL . ";dbname=" . Configuration::CONST_W3_DATABASE_BASENAME . ";port=" . Configuration::CONST_W3_DATABASE_PORT;
		$this->conexao = new PDO($dsn, Configuration::CONST_W3_DATABASE_USER, Configuration::CONST_W3_DATABASE_PASSWORD);
		$this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	/**
	 * Fecha a conexão com o banco de dados
	 */
	public function closeConnection(){
		$this->conexao = null;
	}

	/**
	 * Executa a consulta
	 * @return boolean
	 */
	public function execQuery($query){
		if(is_null($this->conexao)) $this->openConnection();

		try{
			$this->conexao->beginTransaction();
			$results = $this->conexao->prepare($query);
			$results->execute();
		}catch (PDOException $e){
			new Log("Falha ao executar consulta $query :" . $e->getMessage());
			$this->conexao->rollBack();
			return false;
		}

		$this->conexao->commit();

		$this->arrResults = array();
		$this->arrResults = $results->fetchAll();

		$results->closeCursor();

		return true;
	}

	/**
	 * Retorna todas as linhas resultantes da consulta
	 * @return array : string
	 */
	public function getFetchArray(){
		return $this->arrResults;
	}

	/**
	 * Retorna o número de linhas retornadas
	 * @return int
	 */
	public function getRowsCount(){
		return count($this->arrResults);
	}

	/**
	 * Retorna o chamador de procedures do banco de dados
	 * @return string
	 */
	public function getProcedureCallToken(){
		return Configuration::CONST_W3_DATABASE_PROCEDURE_CALL_TOKEN;
	}
}
?>