<?php
require_once __DIR__ . '/Database.php';

/**
 * Responsável por carregar muitos ids de objetos de uma só vez
 * @author tatupheba
 */
class ObjectLoader {

	/**
	 * Abriga o objeto responsável por carregar as informações
	 * @var Database
	 */
	private $database;

	public function __construct($dataBaseName){
		$this->database = new Database();
		$this->database->databaseSelect(Configuration::CONST_DB_NAME);
	}

	public function getUserId($login, $password){




		$this->database->executeView($viewAddress);
	}

	protected function getViewUrl(){
		
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
	}


}
?>