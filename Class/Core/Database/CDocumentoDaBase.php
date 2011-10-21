<?php
require_once __DIR__ . '/../../Core/CCore.php';
require_once __DIR__ . '/../../Core/BaseDeDados/CDatabase.php';
require_once __DIR__ . '/../../Core/Configuration/CConfiguration.php';

/**
 * Todos as classes que tiverem propriedades que se queira salvar
 * deve extender esta classe, mas atenção: As propriedades salvas 
 * serão apenas aquelas cuja visibilidade for "protected" ou "public"
 *
 * @author andre
 *
 */
class CDocumentoDaBase extends CCore{

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
	 * @var CDatabase
	 */
	private $operadorDeBancoDeDados;

	/**
	 * Usado para indicar quando um item
	 * do documento é um objeto
	 * @var int
	 */
	const CONST_TIPO_INFORMACAO_OBJETO = 0;

	/**
	 * Usado para indicar quando um item
	 * do documento é um arranjo
	 * @var int
	 */
	const CONST_TIPO_INFORMACAO_ARRAY = 1;

	/**
	 * Usado para indicar quando um item
	 * do documento é uma string ou número
	 * @var int
	 */
	const CONST_TIPO_INFORMACAO_ORDINARIA = 2;

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

	public function __construct(){
		parent::__construct();
	}

	/**
	 * Carrega uma informação da base dada sua identificação
	 *
	 * ATENÇÃO: Infelizmente não é possivel, de forma 
	 * eficiente, substituir um objeto por outro gerado
	 * pelo mesmo de forma interna à classe, sendo assim,
	 * é necessário chamar este método de forma externa.
	 * @return Object - O objeto carregado e gerado
	 * @see setId()
	 */
	public function carregar(){

		//A id tem que estar setada
		if($this->id == "") return null;

		$this->abrirConexaoComObancoDeDados();

		if(!$this->operadorDeBancoDeDados->loadDocument($this->id)) return null;

		$information = $this->operadorDeBancoDeDados->getResponse();

		$expressao = $this->gerarExpressaoSerializada(null, $information);

		return unserialize($expressao);
	}

	/**
	 * Salva as informações da classe
	 * @return boolean
	 */
	public function salvar(){

		$this->abrirConexaoComObancoDeDados();

		if($this->id == ""){
			$ok = $this->operadorDeBancoDeDados->saveDocument("", $this->toArray());
		}else{
			$ok = $this->operadorDeBancoDeDados->updateDocumentInformation($this->id, $this->rev, $this->toArray());
		}

		if($ok){
			$reponse = $this->operadorDeBancoDeDados->getResponse();
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
	public function apagar(){
		if($this->id == "") throw new Exception("Texto - Falha ao apagar informação: O evento não tem uma identificação.");

		$this->abrirConexaoComObancoDeDados();

		return $this->operadorDeBancoDeDados->eraseDocument($this->id, $this->rev);
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

	private function abrirConexaoComObancoDeDados(){
		
		if($this->getNomeDaBaseDeDados() == "") throw new Exception("text - Para conectar na base de dados é necessário informar seu nome com 'setNomeDaBaseDeDados()'");
		
		if(is_null($this->operadorDeBancoDeDados)){
			//Preparando para realizar as transações com o banco de dados
			$this->operadorDeBancoDeDados = new CDatabase();
			$this->operadorDeBancoDeDados->databaseSelect($this->getNomeDaBaseDeDados());
		}
	}

	private function getTipoDaInformacao($information){

		if(is_null($information) || is_string($information) || is_numeric($information)){
			return self::CONST_TIPO_INFORMACAO_ORDINARIA;
		}

		if(isset($information->CLASSNAME)){
			if($information->CLASSNAME != "stdClass") return self::CONST_TIPO_INFORMACAO_OBJETO;
		}

		if(get_class($information) != "stdClass") return self::CONST_TIPO_INFORMACAO_OBJETO;
		return self::CONST_TIPO_INFORMACAO_ARRAY;
	}

	/**
	 * Retorna o código da visibilidade 
	 * @param object $information
	 * @return int : (ReflectionProperty::IS_PROTECTED, ReflectionProperty::IS_PRIVATE ou ReflectionProperty::IS_PUBLIC) | null : não há visibilidade definida
	 */
	private function getVisibilidade($information){

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
	 * @param int $visibilidade
	 * @return mixed
	 */
	private function getValor($information,$visibilidade){

		if(!is_object($information)) return $information;

		try{
			//Se a informação existe retorna-a
			$reflect = new ReflectionObject($information);
			if($reflect->hasProperty($visibilidade)){
				return $information->$visibilidade;
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
	 * @param string $propriedade
	 * @param int $visibilidade
	 * @return string
	 */
	private function getValorComAsFlagsDeVisibilidade($tipo, $propriedade, $visibilidade){
		if($visibilidade == ReflectionProperty::IS_PROTECTED) return self::CONST_FLAG_DE_PROPRIEDADE_PROTEGIDA . self::CONST_FLAG_DE_PROPRIEDADE_PROTEGIDA_MEIO . self::CONST_FLAG_DE_PROPRIEDADE_PROTEGIDA . $propriedade;
		if($visibilidade == ReflectionProperty::IS_PRIVATE) return self::CONST_PRI . get_class($this) . self::CONST_PRI . $propriedade;
		return $propriedade;
	}

	/**
	 * Gera a expressão serializada que converte o objeto genérico vindo do banco de dados em um objeto definido.
	 * @param string $propriedade - Nome da propriedade tratada (null para objeto raiz)
	 * @param mixed $information - Objeto, string, número, array que contêm a informação 
	 */
	private function gerarExpressaoSerializada($propriedade, $information){

		//Conêm o conteúdo serializado
		$conteudoSerializado = "";

		//Gerando os dados necessários a criação da string serializada
		$visibilidade = $this->getVisibilidade($information);
		$valor = $this->getValor($information,$visibilidade);
		$tipo = $this->getTipoDaInformacao($valor);
		$propriedade = $this->getValorComAsFlagsDeVisibilidade($tipo, $propriedade, $visibilidade);

		//Trata objetos
		if($tipo == self::CONST_TIPO_INFORMACAO_OBJETO){

			if(is_null($propriedade)){
				//Se a propriedade é nula então estamos no objeto raíz
				$conteudoSerializado = "O:" . strlen($valor->CLASSNAME) . ":\"" . $valor->CLASSNAME . "\":" . (count(get_object_vars($valor)) - 1) . ":{";
			}else{
				//Se a propriedade é NÃO nula então estamos precessando um propriedade do objeto
				$conteudoSerializado = "s:" . strlen($propriedade) . ":\"" . $propriedade . "\";O:" . strlen($valor->CLASSNAME) . ":\"" . $valor->CLASSNAME . "\":" . (count(get_object_vars($valor)) - 1) . ":{";
			}

			//Processa as propriedades do objeto
			foreach ($valor as $subPropriedade => $subValor) {
				if($subPropriedade == "CLASSNAME") continue;
				$conteudoSerializado .= $this->gerarExpressaoSerializada($subPropriedade, $subValor);
			}

			$conteudoSerializado .= "}";

			return $conteudoSerializado;
		}

		//Trata arranjos
		if($tipo == self::CONST_TIPO_INFORMACAO_ARRAY){

			$valor = get_object_vars($valor);

			if(is_null($propriedade)){
				//Se a propriedade é nula então estamos no arranjo raíz
				$conteudoSerializado = "a:" . count($valor) . ":{";
			}else{
				//Se a propriedade é NÃO nula então estamos precessando um item do arranjo
				$conteudoSerializado = "s:" . strlen($propriedade) . ":\"" . $propriedade . "\";a:" . count($valor) . ":{";
			}

			//Processa os itens do arranjo
			foreach ($valor as $subPropriedade => $subValor){
				$conteudoSerializado .= $this->gerarExpressaoSerializada($subPropriedade, $subValor);
			}

			$conteudoSerializado .= "}";

			return $conteudoSerializado;
		}

		//Traduz a id do banco para a id do sistema
		if($propriedade === "_id"){
			$strId = self::CONST_PRI . __CLASS__ . self::CONST_PRI . "id";
			$conteudoSerializado .= "s:" . strlen($strId) . ":\"" . $strId . "\";";
			$conteudoSerializado .= "s:" . strlen($valor) . ":\"" . $valor . "\";";
			return $conteudoSerializado;
		}

		//Traduz a série da revisão do banco para a revisão do sistema
		if($propriedade === "_rev"){
			$strRev = self::CONST_PRI . __CLASS__ . self::CONST_PRI . "rev";
			$conteudoSerializado .= "s:" . strlen($strRev) . ":\"" . $strRev . "\";";
			$conteudoSerializado .= "s:" . strlen($valor) . ":\"" . $valor . "\";";
			return $conteudoSerializado;
		}

		//Cria a descrição das propriedade para os tipo primitivos (string, número, etc)
		$conteudoSerializado .= "s:" . strlen($propriedade) . ":\"" . $propriedade . "\";";

		//Monta a propridade nula
		if(trim($valor) == ""){
			$conteudoSerializado .= "N;";
			return $conteudoSerializado;
		}

		//Monta a propridade numérica	
		if(is_numeric($valor)){
			$conteudoSerializado .= "i:" . $valor . ";";
			return $conteudoSerializado;
		}

		//Monta a propridade textual
		if(is_string($valor)){
			$conteudoSerializado .= "s:" . strlen($valor) . ":\"" . $valor . "\";";
			return $conteudoSerializado;
		}
	}

	/**
	 * Recupera o nome da base de dados na qual serão salvos os dados
	 * @return string
	 */
	public function getNomeDaBaseDeDados(){
		return $this->nomeDaBaseDeDados;
	}

	/**
	 * Seta o nome da base de dados na qual serão salvos os dados
	 * @param string $dataBaseName
	 */
	public function setNomeDaBaseDeDados($dataBaseName){
		if(!is_string($dataBaseName)) throw new Exception("text - O nome da base de dados tem que ser uma string");
		$this->nomeDaBaseDeDados = $dataBaseName;
	}
}
?>