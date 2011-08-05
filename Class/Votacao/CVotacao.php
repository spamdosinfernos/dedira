<?php
class CVotacao{
	
	/**
	 * Opções indexadas numericamente da votacao 
	 * @var Array : int => string
	 */
	private $arrOpcoes;
	
	private $descricao;

	private $nomeDaVotacao;
	
	private $dataDeCriacao;
	
	private $vigenciaEmSegundos;
	
	/**
	 * 
	 * Militantes organicos que votaram
	 * @var Array : CMilitanteOrganico
	 */
	private $arrMilitantesVotantes;
	
}
?>