<?php
require_once __DIR__ . '/../../general/database/StorableObject.php';

class Objeto extends StorableObject{
	
	protected $foto;

	protected $descricaoCurta;
	
	protected $descricaoLonga;

	/**
	 * Pessoa que tem a posse do objeto 
	 * @var Person
	 * @var IOrganizacao
	 */
	protected $possuidor;
	
	/**
	 * Pessoa que tem a posse do objeto 
	 * @var Person
	 * @var IOrganizacao
	 */
	protected $possuidorTemporario;
	
	protected $dataDeAquisicaoDoPossuidor;
	
	
}
?>