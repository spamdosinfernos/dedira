<?php
class ClienteDoWebservice{

	/**
	 * Aponta para o cliente do webservice
	 * @var SoapClient
	 */
	private $soapClientInstance;

	public function __construct($enderecoDoWebService){

		$mensagemDeErro = "Falha ao iniciar o web service: ";

		try{
			$this->soapClientInstance = new SoapClient($enderecoDoWebService, array("trace" => 1, "exceptions" => 1, "user_agent"=>""));
		} catch (SoapFault $e){
			throw new SoapFault($e->getMessage());
		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}

	}

	public function getInfoDoCartao($codigo_produto, $data_venda, $cupom, $nome_cliente, $email_cliente, $cpf_cliente, $endereco_cliente, $complemento_cliente, $cep_cliente, $bairro_cliente, $cidade_cliente, $uf_cliente, $fone_cliente){

		try{
			$retorno = $this->soapClientInstance->getInfoDoCartao($codigo_produto, $data_venda, $cupom, $nome_cliente, $email_cliente, $cpf_cliente, $endereco_cliente, $complemento_cliente, $cep_cliente, $bairro_cliente, $cidade_cliente, $uf_cliente, $fone_cliente);
			return $retorno;
		} catch (Exception $e){
			throw new Exception($e->getMessage());
		} catch (SoapFault $e){
			throw new SoapFault($e->getMessage());
		}
	}


}

$codigo_produto = "TT32";
$data_venda = "2011-06-10 14:58:00";
$cupom = "09682020";
$cpf_cliente = "30007769822";
$nome_cliente = "Vinicius Andrade Canovas";
$email_cliente = "vcanovas@tectotal.com.br";
$endereco_cliente = "Rua Doutor Freak Man,220";
$complemento_cliente = "Bloco 1 - Apto: 21";
$cep_cliente = "09682020";
$bairro_cliente = "Cidade da Flores";
$cidade_cliente	= "São Paulo";
$uf_cliente	= "SP";
$fone_cliente = "1130962020";

$a = new ClienteDoWebservice("http://localhost/sandBox/ofertaNossa/server/server.wsdl");
$t = $a->getInfoDoCartao(
$codigo_produto,
$data_venda,
$cupom,
$nome_cliente,
$email_cliente,
$cpf_cliente,
$endereco_cliente,
$complemento_cliente,
$cep_cliente,
$bairro_cliente,
$cidade_cliente,
$uf_cliente,
$fone_cliente
);
?>