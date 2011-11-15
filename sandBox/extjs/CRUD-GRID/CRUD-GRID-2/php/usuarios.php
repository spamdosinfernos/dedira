<?php
require_once 'baseClass.php';

class usuario extends base {
	protected $actions = array(
		'select', //Poderimaos tirar esta funчуo, mas vou deixar de exemplo
		'selectLimited' //Aqui adicionamos o nome da funчуo que iremos charmar por ajax
	);
	
	public function __construct(){
		if(isset($_POST['usu_data_nascimento'])){
			$_POST['usu_data_nascimento'] = $this->DMY2YMD($_POST['usu_data_nascimento'], "/", '-');
		}
		call_user_func_array(array('parent', '__construct'), func_get_args());
	}
	
	public function select(){
		$sql = "SELECT * FROM usuarios";
		$result = $this->_select_fetch_all($sql);
		
		echo json_encode(array(
			"data" => $result
		));
	}
	
	/*
	 * Aqui criamos uma funчуo que nos retorna um select limitado de acordo com
	 * os parтmetros "start" e "limit" passados pela interface
	 */
	public function selectLimited(){
		//Transformamos o array associoativo quem vem como post em um objeto
		//isto facilita na hora de montar um sql
		$post = (object) $_POST;
		
		//Fazermos uma contagem na tabela de usuсrios afim de termos a quantidade
		//total de registro para que possamos fazer a paginaчуo
		$sql = "SELECT COUNT(usu_login) AS count FROM usuarios";
		$result = $this->_select_fetch_all($sql);
		$total = $result[0]->count;
		
		//Nosso select agora deve conter um start e um limit
		$sql = "SELECT * FROM usuarios LIMIT $post->start, $post->limit";
		$result = $this->_select_fetch_all($sql);
		
		echo json_encode(array(
			"data" => $result, //Array de registros
			"total" => $total  //Contagem total de registros
		));
	}
}

$usuario = new usuario($_POST['_action']);
?>