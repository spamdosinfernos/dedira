<?php
require_once 'CMilitante.php';

/**
 * O militante orgânico tem direito a entrar no sistema 
 * @author andre
 */
class CMilitanteOrganico extends CMilitante{

	private $usuario;
	
	private $senha;
	
	public function __construct(){
		
		parent::__construct();
		
		$this->nivelDeAcessoAoSitema = CCore::CONST_NIVEL_ACESSO_MILITANTE_DE_APOIO;
	}
	
}

?>