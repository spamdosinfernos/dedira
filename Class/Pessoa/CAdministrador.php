<?php
require_once __DIR__ . '/CMilitanteOrganico.php';

class CAdministrador extends CMilitanteOrganico{

	public function __construct(){

		parent::__construct();

		$this->nivelDeAcessoAoSitema = CCore::CONST_NIVEL_ACESSO_ADMINISTRADOR;
	}
}

?>