<?php
require_once __DIR__ . '/CMilitante.php';

/**
 * O militante orgânico tem direito a entrar no sistema 
 * @author andre
 */
class CMilitanteOrganico extends CMilitante{

	protected $user;
	
	protected $password;
	
	public function __construct(){
		
		parent::__construct();
		
		$this->nivelDeAcessoAoSitema = CCore::CONST_ACCESS_LEVEL_MEDIUM;
	}

	public function getUsuario(){
	    return $this->user;
	}

	public function setUsuario($user){
	    $this->user = $user;
	}

	public function getSenha(){
	    return $this->password;
	}

	public function setSenha($password){
	    $this->password = $password;
	}
}

?>