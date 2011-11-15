<?php
class CVotacao extends CCore{
	
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
	 * @var Array : CMilitanteOrganico
	 */
	protected $arrMilitantesVotantes;
	
}
?>