<?php
require_once 'CPessoa.php';

class CMilitante extends CPessoa{

	protected $email;

	protected $idOrganizacao;

	protected $chavePgp;

	public function __construct(){

		parent::__construct();

		$this->nivelDeAcessoAoSitema = CCore::CONST_NIVEL_ACESSO_MILITANTE;
	}
}
?>

