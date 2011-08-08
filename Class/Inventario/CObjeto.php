<?php
class CObjeto{
	
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