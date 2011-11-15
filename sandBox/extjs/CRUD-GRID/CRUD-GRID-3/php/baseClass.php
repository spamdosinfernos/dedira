<?php
class base {
	private $db_server   = '127.0.0.1';
	private $db_user     = 'root';
	private $db_pass     = 'root';
	private $db_database = 'crudgrid';
	protected $conn      = null;
	protected $actions   = array();
	
	public function __construct($action=null){
		$this->_connect();
		if(isset($action)){
			$this->_execAction($action);
		}
	}
	
	public function __destruct(){
		mysql_close($this->conn);
	}
	
	protected function _connect(){
		$this->conn = mysql_connect($this->db_server, $this->db_user, $this->db_pass);
		mysql_select_db($this->db_database,$this->conn);
	}
	
	public function _select($sql){
		return mysql_query($sql,$this->conn);
	}
	
	/****
	 * Esta funчуo executa um sql no banco e retorna TRUE caso tenha sucesso e FALSE caso contrario
	****/
	public function _execute($sql){
		$result = $this->_select($sql);
		return  mysql_affected_rows() > 0;
	}
	
	public function _fetch_all($query){
		$rows = array();
		while ($row = mysql_fetch_object($query)) {
			$rows[] = $row;
		}
		return $rows;
	}
	
	public function _select_fetch_all($sql){
		return $this->_fetch_all($this->_select($sql));
	}
	
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
	
	/****
	 * Aqui uso uma funчуo que converte dd/mm/yyyy para yyyy-mm-dd, formato que щ usado pelo banco
	 * passamos dois parametros, a data em si, o separador que щsta data contщm e o separador que
	 * desejamos no resultado
	****/
	public function DMY2YMD($data, $se, $ss){
		return implode($ss, array_reverse(explode($se, $data)));
	}
}
?>