<?php
require_once __DIR__ . '/CDatabase.php';

/**
 * Responsável por carregar muitos objetos de uma só vez, dados parâmetros 
 * que não necessáriamente seja a id dos mesmos no banco.
 * @author andre
 */ 
class CObjectLoader extends CCore{

	/**
	 * Abriga o objeto responsável por carregar as informações
	 * @var CDatabase
	 */
	private $database;

	public function __construct($dataBaseName){
		parent::__construct();
		$this->database = new CDatabase();
		$this->database->databaseSelect($dataBaseName);
	}
	
	public function getUserIdThroughLoginAndPassword($user, $password){

		$viewExists = false;
		$viewAddress = "";

		//Ante executar uma view, devo verificar se a mesma existe
		$this->database->loadAllViews();

		$arrDesigns = $this->database->getResponse();

		foreach ($arrDesigns as $design) {
			
			$r = new ReflectionObject($design->views);
			$arrViews = $r->getProperties();

			foreach ($arrViews as $view) {
				if($view->name == __FUNCTION__){
					$viewAddress = $design->_id . "/_view/" . $view->name;
					break;
				}
			}
		}

		$this->database->executeView($viewAddress);
	}
}
?>