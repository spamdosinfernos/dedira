<?php
require_once __DIR__ . '/../CCore.php';
/**
 * Responsável por carregar muitos objetos de uma só vez, dados parâmetros 
 * que não necessáriamente seja a id dos mesmos no banco.
 * @author andre
 */ 
class CCarregadorDeObjetos extends CCore{

	/**
	 * Abriga o objeto responsável por carregar as informações
	 * @var CBaseDeDados
	 */
	private $carregadorDeInformacao;
	
	public function __construct($nomeDaBaseDeDados){
		parent::__construct();
		$this->carregadorDeInformacao = new CBaseDeDados();
		$this->carregadorDeInformacao->selecionarBaseDeDados($nomeDaBaseDeDados);
	}
	
	public function getIdsDeUsuarioViaLoginESenha($usuario, $senha){
		
		//Ante executar uma view, devo verificar se a mesma existe
		$this->carregadorDeInformacao->carregarTodasAsViews();
		
		$views = $this->carregadorDeInformacao->getResposta();
	}
	
}
?>