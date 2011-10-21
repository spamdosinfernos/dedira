<?php
require_once __DIR__ . '/Security/CAuthenticator.php';
require_once __DIR__ . '/Security/CAuthRules.php';

class CCore{

	private $arrUserModules;

	private $authenticator;

	const CONST_ACCESS_LEVEL_ADMINISTRATOR = 0;

	const CONST_ACCESS_LEVEL_FULL = 1;

	const CONST_ACCESS_LEVEL_MEDIUM = 2;

	const CONST_ACCESS_LEVEL_BASIC = 3;

	const CONST_ACCESS_LEVEL_NONE = 4;

	protected function __construct(){

		$this->authenticator = new CAuthenticator(new CAuthRules());

		//Deve ser sempre o primeiro comando, pois disto depende todo o sistema
		if(!$this->authenticator->isAuthenticated()){
			$this->authenticator->authenticate();
		}
	}

	protected function getUserId(){
		return $this->authenticator->getUserId();
	}

	protected function unauthenticate(){
		$this->authenticator->unauthenticate();
	}

	/**
	 * Transforma as propriedades do objeto em um arranjo
	 * @return array:mixed
	 */
	protected function toArray(){
		return $this->convertToArray($this);
	}

	/**
	 * Transforma as propriedades do objeto ou array em um arranjo
	 * @return array:mixed
	 */
	private function convertToArray($info){

		$arrSerial = array();

		//Se a informação for um objeto 
		if(is_object($info)){
			//As propriedades são as propriedades do objeto
			$reflection = new ReflectionObject($info);
			$arrPropriedades = $reflection->getProperties();
		}else{
			//Senão as propridades são os itens do arranjo
			$arrPropriedades = $info;
		}

		//Construindo a estrutura que será salva
		foreach ($arrPropriedades as $indice => $propriedade) {

			//Visibilidade da propriedade (caso o item varrido seja um objeto)
			$visibilidade = "";
			//Nome da propriedade
			$nomeDaPropriedade = "";
			//Valor da propriedade
			$valorDaPropriedade = "";

			//Recupera os dados da propridade do objeto
			if(is_object($info)){
				$nomeDaPropriedade = $propriedade->getName();
				$visibilidade = $propriedade->getModifiers();
				$valorDaPropriedade = $info->$nomeDaPropriedade;

				//Correção de bug aparente: As vezes a visibilidade fica em 4096 sendo que o máximo é 102
				$visibilidade = $visibilidade > ReflectionMethod::IS_PRIVATE ? ReflectionMethod::IS_PUBLIC : $visibilidade;
			}

			//Se o valor da propriedade é um objeto, chama recursivamente o método
			if(is_object($valorDaPropriedade)){
				$arrSerial[$nomeDaPropriedade][$visibilidade] = $this->convertToArray($valorDaPropriedade);
				continue;
			}

			//Se o valor da propriedade é um array, chama recursivamente o método
			if(is_array($valorDaPropriedade)){
				$arrSerial[$nomeDaPropriedade][$visibilidade] = $this->convertToArray($valorDaPropriedade);
				continue;
			}

			if($visibilidade == ""){
				//Quando a visibilidade é vazia, isso significa que a propriedade é o item de um array
				$arrSerial[$indice] = $propriedade;
			}else{
				//Quando a visibilidade não é vazia, isso significa que é uma propriedade de um objeto
				$arrSerial[$nomeDaPropriedade][$visibilidade] = $info->$nomeDaPropriedade;
			}
		}

		//Se a informação tratada for um objeto, adiciona o nome da classe na estrura a ser salva
		if(is_object($info)) $arrSerial["CLASSNAME"] = get_class($info);

		return $arrSerial;
	}
}
?>