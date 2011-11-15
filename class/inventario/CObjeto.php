<?php
require_once __DIR__ . '/../../core/baseDeDados/DocumentoDaBase.php';

class Objeto extends DocumentoDaBase{
	
	protected $foto;

	protected $descricaoCurta;
	
	protected $descricaoLonga;

	/**
	 * Pessoa que tem a posse do objeto 
	 * @var Pessoa
	 * @var IOrganizacao
	 */
	protected $possuidor;
	
	/**
	 * Pessoa que tem a posse do objeto 
	 * @var Pessoa
	 * @var IOrganizacao
	 */
	protected $possuidorTemporario;
	
	protected $dataDeAquisicaoDoPossuidor;
	
	
}
?>