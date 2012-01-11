<?php 
class Group extends StorableObject{
	
	protected $name;

	/**
	 * Expressão regular que libera o acesso aos diretórios e classes do sistema
	 * @var string
	 */
	protected $allowExpression;
	
	public function isAllowed($dirOrFilePath){
		//TODO Implementar
	}

	public function getAllowExpression(){
	    return $this->allowExpression;
	}

	public function setAllowExpression($allowExpression){
	    $this->allowExpression = $allowExpression;
	}

	public function getName(){
	    return $this->name;
	}

	public function setName($name){
	    $this->name = $name;
	}
}


?>