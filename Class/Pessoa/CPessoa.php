<?php
require_once 'Class/Core/CCore.php';
require_once 'IPessoa.php';


class CPessoa extends CCore implements IPessoa{

	protected $id;

	protected $nome;

	protected $sobrenome;

	protected $dataDeNascimento;

	protected $sexo;

	protected $arrTelefone;

	protected $nivelDeAcessoAoSitema;

	public function __construct(){

		parent::__construct();

		$this->nivelDeAcessoAoSitema = CCore::CONST_NIVEL_ACESSO_PESSOA;
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
	
	public function autenticar($usuario, $senha){
		$this->autenticarUsuario($usuario, $senha);
	}

}
?>