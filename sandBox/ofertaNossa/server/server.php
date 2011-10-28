<?php
//Carrega a classe que recupera as informações de um dado cartão
require_once 'Cartao.php';

/**
 * Procedimento chamado pelo webservice
 * @see Cartao::getInfoDoCartao
 */
function getInfoDoCartao($codigo_produto, $data_venda, $cupom, $nome_cliente, $email_cliente, $cpf_cliente, $endereco_cliente, $complemento_cliente, $cep_cliente, $bairro_cliente, $cidade_cliente, $uf_cliente, $fone_cliente) {
	return Cartao::getInfoDoCartao($codigo_produto, $data_venda, $cupom, $nome_cliente, $email_cliente, $cpf_cliente, $endereco_cliente, $complemento_cliente, $cep_cliente, $bairro_cliente, $cidade_cliente, $uf_cliente, $fone_cliente);
}

//Instanciando o servidor
$server = new SoapServer("./server.wsdl");

//Disponibiliza o procedimento afim de que este seja chamado pelo webservice
$server->addfunction("getInfoDoCartao");

file_put_contents("teste.log",$HTTP_RAW_POST_DATA);

//Manipula as informações enviadas pelo client do webservice (cabeçalho POST do protocolo Http)
@$server->handle($HTTP_RAW_POST_DATA);
?>
