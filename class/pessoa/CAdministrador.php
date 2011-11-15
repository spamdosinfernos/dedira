<?php
require_once __DIR__ . '/MilitanteOrganico.php';

class Administrador extends MilitanteOrganico{

	public function __construct(){
		parent::__construct();
		$this->nivelDeAcessoAoSitema = Core::CONST_ACCESS_LEVEL_ADMINISTRATOR;
	}
}

?>