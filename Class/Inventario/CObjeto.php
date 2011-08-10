<?php
require_once '../../Core/BaseDeDados/CDocumentoDaBase.php';

class CObjeto extends CDocumentoDaBase{
	
	protected $foto;

	protected $descricaoCurta;
	
	protected $descricaoLonga;

	/**
	 * Pessoa que tem a posse do objeto 
	 * @var CPessoa
	 * @var IOrganizacao
	 */
	protected $possuidor;
	
	/**
	 * Pessoa que tem a posse do objeto 
	 * @var CPessoa
	 * @var IOrganizacao
	 */
	protected $possuidorTemporario;
	
	protected $dataDeAquisicaoDoPossuidor;
	
	
}
?>