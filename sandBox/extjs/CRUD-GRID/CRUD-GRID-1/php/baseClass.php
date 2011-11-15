<?php
//Aqui temos uma simples demonstraчуo de como fazer um cѓdigo organizado e orientado a objetos de forma
//a economizar trabalho, tempo e linhas de cѓdigo

/**
Classe base.
Esta classe щ responsсvel por executar todo que щ redundante a todas as demais classes, como por exemplo a conexуo ao banco de dados.
Aqui definimos algumas variaveis privadas contendo as informaчѕes de conecxуo com o banco de dados e algumas funчѕes base como
a funчуo de coneчуo.

As demais classes devem herdar desta classe e implementar apenas os mщtodos de manipulaчуo de dados como insert, delete, select, update, etc.
**/
class base {
	private $db_server   = '127.0.0.1';
	private $db_user     = 'root';
	private $db_pass     = 'root';
	private $db_database = 'crudgrid';
	protected $conn      = null;
	protected $actions   = array();
	
	public function __construct($action=null){
		//Esta funчуo щ disparada quando se cria o objeto
		//Entуo ao criamos um objeto desta classe щ executada uma conexуo ao banco de dados
		$this->_connect();
		//Se alguma aчуo foi passada chama a funчуo que se encarrega desta tarefa
		if(isset($action)){
			$this->_execAction($action);
		}
	}
	
	/*
	 * Esta funчуo щ disparada quando objeto щ destruэdo, assim devemos destruir tudo que criamos,
	 * como a nossa conecxуo
	 */
	public function __destruct(){
		mysql_close($this->conn);
	}
	
	protected function _connect(){
		//Conecta ao banco
		$this->conn = mysql_connect($this->db_server, $this->db_user, $this->db_pass);
		//Seleciona o banco de dados desejado
		mysql_select_db($this->db_database,$this->conn);
	}
	
	/****
	 * Funчуo que executa uma query no banco de dados, se um dia precisarmos mudar de banco teoricamente bastaria mudar
	 * as funчѕes de manipulaчуo do banco que estуo abstraidas aqui, tambщm facilita por nуo precisar passar 2 parтmetros
	 * passando apenas o sql
	****/
	public function _select($sql){
		return mysql_query($sql,$this->conn);
	}
	
	/****
	 * Como o mysql nуo nos prove uma funчуo que retorne todos os registros em forma de array aqui crio uma que faz isso
	****/
	public function _fetch_all($query){
		$rows = array();
		while ($row = mysql_fetch_object($query)) {
			$rows[] = $row;
		}
		return $rows;
	}
	
	/****
	 * Aqui temos uma facilidade, geralmente o que fazermos щ fazer um select montar um array e imprimir em JSON,
	 * chamando esta funчуo temos o resultado de um sql jс em array pronta para ser codificado em JSON
	****/
	public function _select_fetch_all($sql){
		return $this->_fetch_all($this->_select($sql));
	}
	
	/****
	 * Esta funчуo executa um mщtodo da classe pelo seu nome em STRING caso ele exista e esteja na lista
	 * de aчѕes contiga em $actions, esta lista deve ser definida em cada classe filha
	****/
	public function _execAction($action){
		if((in_array($action, $this->actions))&&(method_exists($this, $action))){
			call_user_func(array($this, $action));
		}else{
			echo json_encode(array(
				'success' => false,
				'msg' => utf8_encode("Aчуo invсlida: '$action'")
			));
		}
	}
}
?>