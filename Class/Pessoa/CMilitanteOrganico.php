<?php
require_once __DIR__ . '/CMilitante.php';

/**
 * O militante orgânico tem direito a entrar no sistema
 * @author andre
 *
 */
class CMilitanteOrganico extends CMilitante{

	protected $usuario;

	protected $senha;

	public function __construct(){

		parent::__construct();

		$this->nivelDeAcessoAoSitema = CCore::CONST_NIVEL_ACESSO_MILITANTE_ORGANICO;
	}

	public function getUsuario(){
		return $this->usuario;
	}

	public function setUsuario($usuario){
		$this->usuario = $usuario;
	}

	public function getSenha(){
		return $this->senha;
	}

	public function setSenha($senha){
		$this->senha = $senha;
	}
}

?>