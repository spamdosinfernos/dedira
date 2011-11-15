<?php
require_once __DIR__ . '/IPessoa.php';
require_once __DIR__ . '/../Core/BaseDeDados/CDocumentoDaBase.php';

class CPessoa extends CDocumentoDaBase implements IPessoa{

	protected $nome;

	protected $sobrenome;

	protected $dataDeNascimento;

	protected $sexo;

	protected $arrTelefone;

	protected $nivelDeAcessoAoSitema;

	public function __construct(){
		parent::__construct();
		$this->setNomeDaBaseDeDados(Configuration::CONST_DB_PEOPLE_NAME);
		$this->nivelDeAcessoAoSitema = CCore::CONST_ACCESS_LEVEL_NONE;
	}

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}

	public function getSobrenome(){
		return $this->sobrenome;
	}

	public function setSobrenome($sobrenome){
		$this->sobrenome = $sobrenome;
	}

	public function getDataDeNascimento(){
		return $this->dataDeNascimento;
	}

	public function setDataDeNascimento($dataDeNascimento){
		$this->dataDeNascimento = $dataDeNascimento;
	}

	public function getSexo(){
		return $this->sexo;
	}

	public function setSexo($sexo){
		$this->sexo = $sexo;
	}

	public function getArrTelefone(){
		return $this->arrTelefone;
	}

	public function setArrTelefone($arrTelefone){
		$this->arrTelefone = $arrTelefone;
	}

	public function getNivelDeAcessoAoSitema(){
		return $this->nivelDeAcessoAoSitema;
	}

	public function setNivelDeAcessoAoSitema($nivelDeAcessoAoSitema){
		$this->nivelDeAcessoAoSitema = $nivelDeAcessoAoSitema;
	}
	
	public function autenticar($user, $password){
		$this->autenticarUsuario($user, $password);
	}

}
?>