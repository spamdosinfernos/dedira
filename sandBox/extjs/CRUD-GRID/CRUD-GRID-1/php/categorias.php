<?php
require_once 'baseClass.php';

class categorias extends base {
	protected $actions = array(
		'select'
	);

	public function select(){
		$sql = "SELECT * FROM categorias";
		$result = $this->_select_fetch_all($sql);
		
		echo json_encode(array(
			"data" => $result
		));
	}
}

$categorias = new categorias($_POST['_action']);
?>