<?php
class Votacao {
	
	/**
	 * Opções indexadas numericamente da votacao 
	 * @var Array : int => string
	 */
	protected $arrOpcoes;
	
	protected $descricao;

	protected $nomeDaVotacao;
	
	protected $dataDeCriacao;
	
	protected $vigenciaEmSegundos;
	
	/**
	 * 
	 * Militantes organicos que votaram
	 * @var Array : MilitanteOrganico
	 */
	protected $arrMilitantesVotantes;
	
}
?>