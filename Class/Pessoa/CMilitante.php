<?php
require_once __DIR__ . '/CPessoa.php';

class CMilitante extends CPessoa{

	protected $email;

	protected $idOrganizacao;

	protected $chavePgp;

	public function __construct(){
		parent::__construct();
		$this->nivelDeAcessoAoSitema = CCore::CONST_ACCESS_LEVEL_BASIC;
	}

	public function getEmail(){
	    return $this->email;
	}

	public function setEmail($email){
	    $this->email = $email;
	}

	public function getIdOrganizacao(){
	    return $this->idOrganizacao;
	}

	public function setIdOrganizacao($idOrganizacao){
	    $this->idOrganizacao = $idOrganizacao;
	}

	public function getChavePgp(){
	    return $this->chavePgp;
	}

	public function setChavePgp($chavePgp){
	    $this->chavePgp = $chavePgp;
	}
}
?>

