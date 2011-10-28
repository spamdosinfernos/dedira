<?php
require 'LogDaRequisao.php';

/**
 * Tem a função de retornar as informações
 * sobre um determinado cartão e talvez,
 * num futuro, possa representar o cartão
 * no sistema.
 */
final Class Cartao{
	/**
	 * Usuário do banco de dados
	 * @var string
	 */
	const CONST_USUARIO = "sys_user_integra";
	/**
	 * Senha do banco de dados
	 * @var string
	 */
	//const CONST_SENHA = "95075400";
	const CONST_SENHA = "2JAqzxeNs4XEZhK5";
	/**
	 * Base de dados usada
	 * @var string
	 */
	const CONST_NOMEDABASE = "sca";

	/**
	 * Endereço do servidor de banco de dados
	 * @var string
	 */
	//const CONST_ENDERECOHOST = "192.168.130.244";
	const CONST_ENDERECOHOST = "201.77.195.246";

	/**
	 * Guarda a conexão com o banco de dados
	 * @var objeto:conexão
	 */
	private static $conexao;

	/**
	 * Abre a conexão com o banco de dados
	 * @return true - Conexão estabelecida
	 * @return false - Erro ao conectar
	 */
	private static function abrirConexaoComObancoDeDados(){
		try{
			$dsn = "mysql:host=" . self::CONST_ENDERECOHOST . ";dbname=" . self::CONST_NOMEDABASE;
			self::$conexao = new PDO($dsn, self::CONST_USUARIO, self::CONST_SENHA);
			return true;
		}catch (Exception $e){
			echo $e->getMessage();
			return false;
		}
	}

	/**
	 * Recupera as informações do cartão
	 * dadas as informações da cliente
	 *
	 * @param int $produto_vendido
	 * @param data $data_pedido
	 * @param moeda $preco_vendido
	 * @param int $cod_venda
	 * @param string $nome_cliente
	 * @param string $sobrenome_cliente
	 * @param string $email_cliente
	 * @param int $cpf_cliente
	 * @param string $endereco_cliente
	 * @param int $numero_cliente
	 * @param string $complemento_cliente
	 * @param string $cep_cliente
	 * @param string $bairro_cliente
	 * @param string $cidade_cliente
	 * @param string $uf_cliente
	 * @param int $fone_cliente
	 * @return 0 - Existe algum parâmetro em branco
	 * @return array - Executado com sucesso, este arranjo contêm os dados do cartão
	 * @return 2 - Não foi possivel abrir conexao com o banco de dados
	 * @return 3 - Nao há resultados para esses parâmetros:
	 */
	static function getInfoDoCartao(
	$codigo_produto,
	$data_venda,
	$cupom,
	$cpf_cliente,
	$nome_cliente,
	$email_cliente,
	$endereco_cliente,
	$complemento_cliente,
	$cep_cliente,
	$bairro_cliente,
	$cidade_cliente,
	$uf_cliente,
	$fone_cliente
	){
		//Gravando o log das requisições e retornos - INICIO
		$log = new LogDeRequisicoesDoCartao();
		$log->setSolcitacao(array($codigo_produto,$data_venda,$cupom,$cpf_cliente,$nome_cliente,$email_cliente,$endereco_cliente,$complemento_cliente,$cep_cliente,$bairro_cliente,$cidade_cliente,$uf_cliente,$fone_cliente));

		//Verifica se algum dos parâmetros não está em branco
		if($codigo_produto=="" || $data_venda=="" ||
		 $cupom==""|| $nome_cliente=="" ||
		$email_cliente=="" || $cpf_cliente=="" ||
		$endereco_cliente=="" || $cep_cliente=="" || $cidade_cliente=="" ||
		$uf_cliente==""){
			return 0;
		}

		//Verifica se a conexao com o banco de dados foi realizada
		if(!self::abrirConexaoComObancoDeDados()) return 2;

		
		//Quando complemento de endereco for vazio, atribuir valor 'Null'
		if (trim($complemento_cliente) == "") $complemento_cliente == ' ';
		
		//Realiza a consulta
		$sql="CALL sp_get_ofertanossa('".$codigo_produto."','".$data_venda."',".$cupom.",'".$cpf_cliente."','".$nome_cliente."','".$email_cliente."','".$endereco_cliente."','".$complemento_cliente."','".$cep_cliente."','".$bairro_cliente."','".$cidade_cliente."','".$uf_cliente."','".$fone_cliente."')";

		//Constrói o retorno
		if($resultado = self::$conexao->query($sql)->fetch(PDO::FETCH_ASSOC)){
			$retorno=array(
			'cartao'=>$resultado['cartao_gerado'],
			'mensagem'=>utf8_decode($resultado['retorno'])
			);
		}else{
			return 3;
		}
		self::$conexao = null;

		//Gravando o log das requisições e retornos - FIM
		$log->setRetorno($retorno);
		$log->gravarLog();

		return $retorno;
	}
}

?>
