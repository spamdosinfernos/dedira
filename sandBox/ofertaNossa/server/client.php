<?php

$oSoapClient = new SoapClient("http://201.77.195.246/ofertanossa/server.wsdl");



var_dump($oSoapClient->__getFunctions());

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

//var_dump($oSoapClient->getInfoDoCartao($codigo_produto,$data_venda,$cupom,$cpf_cliente,$nome_cliente,$email_cliente,$endereco_cliente,$complemento_cliente,$cep_cliente,$bairro_cliente,$cidade_cliente,$uf_cliente,$fone_cliente));
?>
