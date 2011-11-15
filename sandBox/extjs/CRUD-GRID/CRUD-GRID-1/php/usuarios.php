<?php
/*
 * Devemos requisitar aqui nossa classe base para que possamos criar nossa classe de usuсrios,
 * usamos require_once se esta classe nуo for encontrada o resto do cѓdigo nуo pode ser executado
 * e o (_once) garante que ela serс carregada apenas uma vez
 */
require_once 'baseClass.php';

/*
 * Aqui estendemos a classe base para uma classe usuсrio, щ recomendсvel ter uma classe por tabela
 * Note que em momento algum usamos de funчѕes proprias de algum banco de dados, isso щ tratado
 * na classe pai
 */
class usuario extends base {
	//A variсvel $actions deve conter os mщtodos que podem ser executados pelo mщtodo execAction da
	//classe pai, щ uma lista de strings com o mesmo nome dos mщtodos
	protected $actions = array(
		'select'
	);
	
	//Este mщtodo estс na lista de aчѕes e poderс ser executado
	public function select(){
		//Definimos um sql para buscar os dados
		$sql = "SELECT * FROM usuarios";
		//Usamos de nossa funчуo para buscar os dados do banco e nos retornar um array pronto
		$result = $this->_select_fetch_all($sql);
		
		//Aqui apenas montamos nosso JSON da forma que quisermos
		echo json_encode(array(
			"data" => $result
		));
	}
}

/*
 * Aqui temos um detalho, podemos fazer de duas formas.
 * 1 - Criar a classe e juntamente a classe passar a aчуo a ser executadam, lembre que dentro
 *     do constructor da classe pai temos um teste para isso. Questуo apenas de facilitar.
 * 2 - Criar a classe e chamar a funчуo que executa a aчуo que for passada
 */
$usuario = new usuario($_POST['_action']);
//Poderia ser executado como abaixo
//$usuario = new usuario();
//$usuario->execAction('select');
?>