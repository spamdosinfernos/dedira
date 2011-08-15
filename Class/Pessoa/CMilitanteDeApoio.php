<?php
require_once __DIR__ . '/CMilitante.php';

/**
 * O militante orgânico tem direito a entrar no sistema 
 * @author andre
 */
class CMilitanteOrganico extends CMilitante{

	protected $usuario;
	
	protected $senha;
	
	public function __construct(){
		
		parent::__construct();
		
		$this->nivelDeAcessoAoSitema = CCore::CONST_NIVEL_ACESSO_MILITANTE_DE_APOIO;
	}
	
}

?>