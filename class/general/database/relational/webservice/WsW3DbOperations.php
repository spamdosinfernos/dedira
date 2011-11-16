<?php
require_once __DIR__ . '/../IDataBaseOperations.php';
require_once __DIR__ . '/../../configuration/Configuration.php';

/**
 * Operações do banco de dados via webservice do servidor
 */
class WsW3DbOperations implements IDataBaseOperations{

	/**
	 * Cliente do webservice
	 * @var SoapClient
	 */
	private $wsClient = null;

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
		$this->wsClient = new SoapClient(Configuration::CONST_WS_W3_DATABASE_URL, array("trace" => 0, "exceptions" => 1, "user_agent"=>""));
	}

	/**
	 * Fecha a conexão com o banco de dados
	 */
	public function closeConnection(){
		$this->wsClient = null;
	}

	/**
	 * Executa a consulta só aceita procedures, 
	 * nada de selects updates ou coisas do
	 * gênero 
	 * @return boolean
	 */
	public function execQuery($query){
		try{

			$arrResult = array();
			$this->resultado = array();

			$return = $this->wsClient->executeProcedure(Configuration::CONST_WS_W3_DBOPERATIONS_CLASS, $query);
				
			if(count($return->linhas) == 0){
				return true;
			}
				
			//Se apenas uma linha foi retornada trata a mesma e monta o resultado
			if(count($return->linhas) == 1){
				
				foreach ($return->linhas->campos as $campo) {
					$arrCampos[$campo->nome] = $campo->valor;
				}
				$arrResult[] = $arrCampos;
				$this->resultado = $arrResult;
				return true;
			}

			//Se mais de uma linha for retornada o tratamento é diferente
			foreach ($return->linhas as $linha) {
				foreach ($linha->campos as $campo) {
					$arrCampos[$campo->nome] = $campo->valor;
				}
				$arrResult[] = $arrCampos;
			}
			$this->resultado = $arrResult;
			return true;

		}catch (Exception $e){
			throw new SoapFault("Falha ao executar consulta através do webservice: " . $e->getMessage());
		}catch (SoapFault $e){
			throw new SoapFault("Falha ao executar consulta através do webservice: " . $e->getMessage());
		}
	}

	/**
	 * Retorna todas as linhas resultantes da consulta
	 * @return array : string
	 */
	public function getFetchArray(){
		return $this->resultado;
	}

	/**
	 * Retorna o número de linhas retornadas
	 * @return int
	 */
	public function getRowsCount(){
		return count($this->resultado);
	}

	/**
	 * Retorna o chamador de procedures do banco de dados
	 * @return string
	 */
	public function getProcedureCallToken(){
		return Configuration::CONST_WS_W3_DATABASE_PROCEDURE_CALL_TOKEN;
	}
}
?>