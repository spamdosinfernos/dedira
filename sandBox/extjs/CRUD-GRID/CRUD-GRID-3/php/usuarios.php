<?php
require_once 'baseClass.php';

class usuario extends base {
	protected $actions = array(
		'select',
		'selectLimited',
		'insert', //Permitimos a chamada da funчуo insert
		'update', //Permitimos a chamada da funчуo update
		'delete'  //Permitimos a chamada da funчуo delete
	);
	
	public function __construct(){
		if(isset($_POST['usu_data_nascimento'])){
			$_POST['usu_data_nascimento'] = $this->DMY2YMD($_POST['usu_data_nascimento'], "/", '-');
		}
		//Assim funciona para a maiorir as versѕes do php
		$args = func_get_args();
		call_user_func_array(array($this, 'parent::__construct'), $args);
		//Assim funciona apenas para a versуo 5.3
		//call_user_func_array(array($this, 'parent::__construct'), func_get_args());
	}
	
	public function select(){
		$sql = "SELECT * FROM usuarios";
		$result = $this->_select_fetch_all($sql);
		
		echo json_encode(array(
			"data" => $result
		));
	}
	
	public function selectLimited(){
		$post = (object) $_POST;
		
		$sql = "SELECT COUNT(usu_login) AS count FROM usuarios";
		$result = $this->_select_fetch_all($sql);
		$total = $result[0]->count;
		
		$sql = "SELECT * FROM usuarios LIMIT $post->start, $post->limit";
		$result = $this->_select_fetch_all($sql);
		
		echo json_encode(array(
			"data" => $result,
			"total" => $total
		));
	}
	
	/*
	 * Esta funчуo fica resposсvel por montar o SQL que serс usado para inserir
	 * os dados no banco, estes dados vem da interface por POST
	 */
	protected function insert(){
		$data = (object) $_POST; //Aqui novamente passamos o array POST para um objeto
		//Perceba abaixo como isto facilita a montamgem do sql
		$sql = 
			" INSERT INTO usuarios(
				usu_login,
				usu_nome,
				usu_senha,
				usu_email,
				usu_data_nascimento,
				cat_id
			) VALUES(
				'$data->usu_login',
				'$data->usu_nome',
				'$data->usu_senha',
				'$data->usu_email',
				'$data->usu_data_nascimento',
				$data->cat_id
			)";
		echo json_encode(array(
			//Executamos o SQL no banco, esta funчуo nos retorna true caso tudo esteja OK
			//ou false caso ocorra algum erro
			"success" => $this->_execute($sql),
			//Mandamos tambщm a mensagem de erro do mysql, caso exista alguma, aqui щ uma
			//boa ideia tratar este erro com alguma funчуo
			"msg" => mysql_error()
		));
	}
	
	/*
	 * Esta funчуo fica resposсvel por montar o SQL que serс usado para atualizar
	 * os dados no banco, estes dados vem da interface por POST
	 */
	protected function update(){
		$data = (object) $_POST;//Novamente transformamos o array em objeto
		$sql = 
			" UPDATE usuarios SET
				usu_nome            = '$data->usu_nome',
				usu_senha           = '$data->usu_senha',
				usu_email           = '$data->usu_email',
				usu_data_nascimento = '$data->usu_data_nascimento',
				cat_id              = $data->cat_id
			WHERE
				usu_login = '$data->usu_login'
			";
		echo json_encode(array(
			//Aqui fazemos o mesmo procedimento da funчуo de inserir
			"success" => $this->_execute($sql),
			"msg" => mysql_error()
		));
	}
	
	/*
	 * Esta funчуo fica resposсvel por montar o SQL que serс usado para deletar
	 * os dados no banco, estes dados vem da interface por POST.
	 * Aqui basta receber o campo PrimaryKey para montarmos o WHERE
	 */
	protected function delete(){
		$data = (object) $_POST; //Procedemos como as demais funчѕes acima
		$sql = " DELETE FROM usuarios WHERE usu_login = '$data->usu_login'";
		echo json_encode(array(
			"success" => $this->_execute($sql),
			"msg" => mysql_error()
		));
	}
}

$usuario = new usuario($_POST['_action']);
?>