<?php
require_once __DIR__ . '/CBaseDeDados.php';

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
		
		$arrViews = $this->carregadorDeInformacao->getResposta();
		
		foreach ($arrViews as $view) {
			if(__FUNCTION__);
		}
	}
	
	public function getIdsDeUsuarioViaLoginESenha($usuario, $senha){

		$viewEncontrada = false;
		$enderecoDaView = "";

		//Ante executar uma view, devo verificar se a mesma existe
		$this->carregadorDeInformacao->carregarTodasAsViews();

		$arrDesigns = $this->carregadorDeInformacao->getResposta();

		foreach ($arrDesigns as $design) {
			$r = new ReflectionObject($design->views);
			$arrViews = $r->getProperties();

			foreach ($arrViews as $view) {
				if($view->name == __FUNCTION__){
					$enderecoDaView = $design->_id . "/_view/" . $view->name;
					break;
				}
			}
		}

		$this->carregadorDeInformacao->executaView($enderecoDaView);
	}
}
?>