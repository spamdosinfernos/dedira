<?php
require_once 'Database.php';
require_once __DIR__ . '/../configuration/Configuration.php';

/**
 * Todos as classes que tiverem propriedades que se queira salvar
 * deve extender esta classe, mas atenção: As propriedades salvas 
 * serão apenas aquelas cuja visibilidade for "protected" ou "public"
 *
 * @author tatupheba
 *
 */
class StorableObject {

	/**
	 * Indentificação das informações na base de dados
	 * @var mixed
	 */
	private $id;

	/**
	 * Série que identifica a versão das informações na base de dados
	 * @var mixed
	 */
	private $rev;

	/**
	 * Nome da base de dados que receberá os dados
	 * @var string
	 */
	private $dataBaseName;

	/**
	 * Responsável por realizar as transações com o banco de dados
	 * @var Database
	 */
	private $dataBaseOperator;

	/**
	 * Usado para indicar quando um item
	 * do documento é um objeto
	 * @var int
	 */
	const CONST_INFORMATION_TYPE_OBJETO = 0;

	/**
	 * Usado para indicar quando um item
	 * do documento é um arranjo
	 * @var int
	 */
	const CONST_INFORMATION_TYPE_ARRAY = 1;

	/**
	 * Usado para indicar quando um item
	 * do documento é uma string ou número
	 * @var int
	 */
	const CONST_INFORMATION_TYPE_ORDINARIA = 2;

	/**
	 * Usado para sinalizar um propriedade protegida,
	 * esta string é marca o início e o fim da flag
	 * @var string
	 */
	const CONST_FLAG_DE_PROPRIEDADE_PROTEGIDA = "\0";

	/**
	 * Usado para sinalizar um propriedade protegida,
	 * esta string é marca o meio da flag
	 * @var string
	 */
	const CONST_FLAG_DE_PROPRIEDADE_PROTEGIDA_MEIO = "*";

	/**
	 * Usado para sinalizar um propriedade privada, esta
	 * string é marca o início e o fim da flag, no meio 
	 * deve constar o nome da classe a qual pertence a
	 * propriedade
	 * @var string
	 */
	const CONST_PRI = "\0";

	/**
	 * Carrega uma informação da base dada sua identificação
	 *
	 * ATENÇÃO: Infelizmente não é possivel, de forma 
	 * eficiente e internamente , substituir o objeto por
	 * outro gerado por ele mesmo, sendo assim,
	 * é necessário chamar este método de forma externa.
	 * @return Object - O objeto carregado e gerado
	 * @see setId()
	 */
	public function load(){

		//A id tem que estar setada
		if($this->id == "") return null;

		$this->openDataBaseConexion();

		if(!$this->dataBaseOperator->loadDocument($this->id)) return null;

		$stdObject = $this->dataBaseOperator->getResponse();

		$expression = $this->generateSerializedData(null, $stdObject);
		
		return unserialize($expression);
	}

	/**
	 * Salva as informações da classe
	 * @return boolean
	 */
	public function save(){

		$this->openDataBaseConexion();

		if($this->id == ""){
			$ok = $this->dataBaseOperator->saveDocument("", $this->toArray());
		}else{
			$ok = $this->dataBaseOperator->updateDocumentInformation($this->id, $this->rev, $this->toArray());
		}

		if($ok){
			$reponse = $this->dataBaseOperator->getResponse();
			$this->id = $reponse->id;
			$this->rev = $reponse->rev;
			return true;
		}
		return false;
	}

	/**
	 * Apaga as informações da classe
	 * @return boolean
	 */
	public function erase(){

		if($this->id == "") throw new Exception("Texto - Falha ao apagar informação: O evento não tem uma identificação.");

		if($this->rev == "") $this->load();
		
		$this->openDataBaseConexion();

		return $this->dataBaseOperator->eraseDocument($this->id, $this->rev);
	}

	public function setId($id){
		$this->id = $id;
	}

	public function setRev($rev){
		$this->rev = $rev;
	}

	public function getId(){
		return $this->id;
	}

	public function getRev(){
		return $this->rev;
	}

	private function openDataBaseConexion(){

		if($this->getDatabaseName() == "") throw new Exception("text - Para conectar na base de dados é necessário informar seu nome com 'setDataBaseName()'");

		if(is_null($this->dataBaseOperator)){
			//Preparando para realizar as transações com o banco de dados
			$this->dataBaseOperator = new Database();
			$this->dataBaseOperator->databaseSelect($this->getDatabaseName());
		}
	}

	private function getInformationType($information){

		if(is_null($information) || is_string($information) || is_numeric($information)){
			return self::CONST_INFORMATION_TYPE_ORDINARIA;
		}

		if(is_array($information)) return self::CONST_INFORMATION_TYPE_ARRAY;

		if(isset($information->CLASSNAME)){
			if($information->CLASSNAME != "stdClass") return self::CONST_INFORMATION_TYPE_OBJETO;
		}

		return self::CONST_INFORMATION_TYPE_OBJETO;
	}

	/**
	 * Retorna o código da visibilidade 
	 * @param object $information
	 * @return int : (ReflectionProperty::IS_PROTECTED, ReflectionProperty::IS_PRIVATE ou ReflectionProperty::IS_PUBLIC) | null : não há visibilidade definida
	 */
	private function getVisibility($information){

		//Se não for um objeto então provávelmente é um arranjo, string ou número
		if(!is_object($information)) return null;

		try{
			//Verifica se o código de visibilidade existe e, em caso positivo, retorna o mesmo
			$reflect =  new ReflectionObject($information);
			if($reflect->hasProperty(ReflectionProperty::IS_PROTECTED)) return ReflectionProperty::IS_PROTECTED;
			if($reflect->hasProperty(ReflectionProperty::IS_PRIVATE)) return ReflectionProperty::IS_PRIVATE;
			if($reflect->hasProperty(ReflectionProperty::IS_PUBLIC)) return ReflectionProperty::IS_PUBLIC;
		}catch (Exception $e){
			return null;
		}
		return null;
	}

	/**
	 * Retorna o valor da informação sem os dados de visibilidade
	 * @param object $information
	 * @param int $visibility
	 * @return mixed
	 */
	private function getValue($information,$visibility){

		if(!is_object($information)) return $information;

		try{
			//Se a informação existe retorna-a
			$reflect = new ReflectionObject($information);
			if($reflect->hasProperty($visibility)){
				return $information->$visibility;
			}
		}catch (Exception $e){
			return $information;
		}
		return $information;
	}

	/**
	 * Afim de gerar uma string que deserialize de forma correta,
	 * junta as flags de visibilidade aos nomes das propriedades
	 * @param int $tipo
	 * @param string $property
	 * @param int $visibility
	 * @return string
	 */
	private function getValuesWithVisibilityFlags($tipo, $property, $visibility){
		if($visibility == ReflectionProperty::IS_PROTECTED) return self::CONST_FLAG_DE_PROPRIEDADE_PROTEGIDA . self::CONST_FLAG_DE_PROPRIEDADE_PROTEGIDA_MEIO . self::CONST_FLAG_DE_PROPRIEDADE_PROTEGIDA . $property;
		if($visibility == ReflectionProperty::IS_PRIVATE) return self::CONST_PRI . get_class($this) . self::CONST_PRI . $property;
		return $property;
	}

	/**
	 * Gera a expressão serializada que converte o objeto genérico vindo do banco de dados em um objeto definido.
	 * @param string $property - Nome da propriedade tratada (null para objeto raiz)
	 * @param mixed $information - Objeto, string, número, array que contêm a informação 
	 */
	private function generateSerializedData($property, $information){

		//Conêm o conteúdo serializado
		$serializedData = "";

		//Gerando os dados necessários a criação da string serializada
		$visibility = $this->getVisibility($information);
		$value = $this->getValue($information,$visibility);
		$tipo = $this->getInformationType($value);
		$property = $this->getValuesWithVisibilityFlags($tipo, $property, $visibility);

		//Trata objetos
		if($tipo == self::CONST_INFORMATION_TYPE_OBJETO){

			if(is_null($property)){
				//Se a propriedade é nula então estamos no objeto raíz
				$serializedData = "O:" . strlen($value->CLASSNAME) . ":\"" . $value->CLASSNAME . "\":" . (count(get_object_vars($value)) - 1) . ":{";
			}else{
				//Se a propriedade é NÃO nula então estamos precessando um propriedade do objeto
				$serializedData = "s:" . strlen($property) . ":\"" . $property . "\";O:" . strlen($value->CLASSNAME) . ":\"" . $value->CLASSNAME . "\":" . (count(get_object_vars($value)) - 1) . ":{";
			}

			//Processa as propriedades do objeto
			foreach ($value as $subProperty => $subValue) {
				if($subProperty == "CLASSNAME") continue;
				$serializedData .= $this->generateSerializedData($subProperty, $subValue);
			}

			$serializedData .= "}";

			return $serializedData;
		}

		//Trata arranjos
		if($tipo == self::CONST_INFORMATION_TYPE_ARRAY){

			//$value = get_object_vars($value);

			if(is_null($property)){
				//Se a propriedade é nula então estamos no arranjo raíz
				$serializedData = "a:" . count($value) . ":{";
			}else{
				//Se a propriedade é NÃO nula então estamos precessando um item do arranjo
				$serializedData = "s:" . strlen($property) . ":\"" . $property . "\";a:" . count($value) . ":{";
			}

			//Processa os itens do arranjo
			foreach ($value as $subProperty => $subValue){
				$serializedData .= $this->generateSerializedData($subProperty, $subValue);
			}

			$serializedData .= "}";

			return $serializedData;
		}

		//Traduz a id do banco para a id do sistema
		if($property === "_id"){
			$strId = self::CONST_PRI . __CLASS__ . self::CONST_PRI . "id";
			$serializedData .= "s:" . strlen($strId) . ":\"" . $strId . "\";";
			$serializedData .= "s:" . strlen($value) . ":\"" . $value . "\";";
			return $serializedData;
		}

		//Traduz a série da revisão do banco para a revisão do sistema
		if($property === "_rev"){
			$strRev = self::CONST_PRI . __CLASS__ . self::CONST_PRI . "rev";
			$serializedData .= "s:" . strlen($strRev) . ":\"" . $strRev . "\";";
			$serializedData .= "s:" . strlen($value) . ":\"" . $value . "\";";
			return $serializedData;
		}

		//Cria a descrição das propriedade para os tipo primitivos (string, número, etc)
		$serializedData .= "s:" . strlen($property) . ":\"" . $property . "\";";

		//Monta a propridade nula
		if(trim($value) == ""){
			$serializedData .= "N;";
			return $serializedData;
		}

		//Monta a propridade numérica	
		if(is_numeric($value)){
			$serializedData .= "i:" . $value . ";";
			return $serializedData;
		}

		//Monta a propridade textual
		if(is_string($value)){
			$serializedData .= "s:" . strlen($value) . ":\"" . $value . "\";";
			return $serializedData;
		}
	}

	/**
	 * Recupera o nome da base de dados na qual serão salvos os dados
	 * @return string
	 */
	protected function getDatabaseName(){
		return $this->databaseName;
	}

	/**
	 * Seta o nome da base de dados na qual serão salvos os dados
	 * @param string $dataBaseName
	 */
	protected function setDataBaseName($dataBaseName){
		if(!is_string($dataBaseName)) throw new Exception("text - O nome da base de dados tem que ser uma string");
		$this->databaseName = $dataBaseName;
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
				
				//Não é possível guardar valores de propriedades privadas, sendo assim, pulamos todas elas 
				$visibilidade = $propriedade->getModifiers();
				if($visibilidade == ReflectionMethod::IS_PRIVATE) continue;
				
				$nomeDaPropriedade = $propriedade->getName();
				$valorDaPropriedade = $info->$nomeDaPropriedade;

				//Correção de bug aparente: As vezes a visibilidade fica em 4096 sendo que o máximo é 1024
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