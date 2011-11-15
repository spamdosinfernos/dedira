<?php
// Request Yahoo! REST Web Service using
// HTTP POST with curl. PHP4/PHP5
// Allows retrieval of HTTP status code for error reporting
// Author: Jason Levitt
// February 1, 2006

error_reporting(E_ALL);

// The POST URL and parameters
$request =  'http://201.77.195.246/ofertanossa/server.php';

$codigo_produto = "TT32";
$data_ame = "2011-06-10 14:58:00";
$cupom = "09682020";
$cpf_cliente = "30007769822";
$nome_cliente = "Vinicius Andrade Canovas";
$email_cliente = "vcanovas@tectotal.com.br";
$endereco_cliente = "Rua Doutor Freak Man,220";
$complemento_cliente = "Bloco 1 - Apto: 21";
$zipcode_cliente = "09682020";
$bairro_cliente = "Cidade da Flores";
$cidade	= "São Paulo";
$uf_cliente	= "SP";
$fone_cliente = "1130962020";

$webservice .= "   <soapenv:Body>\n";
$webservice .= "      <vok:getInfoDoCartao soapenv:encodingStyle=\"http://schemas.xmlsoap.org/soap/encoding/\">\n";
$webservice .= "        <codigo_produto xsi:type=\"xsd:string\">TT32</codigo_produto>\n";
$webservice .= "        <data_venda xsi:type=\"xsd:string\">$data_ame</data_venda>\n";
$webservice .= "        <cupom xsi:type=\"xsd:string\"></cupom>\n";
$webservice .= "        <cpf_cliente xsi:type=\"xsd:string\">$cpf_cliente</cpf_cliente>\n";
$webservice .= "        <nome_cliente xsi:type=\"xsd:string\">$nome_cliente</nome_cliente>\n";
$webservice .= "        <email_cliente xsi:type=\"xsd:string\">$email_cliente</email_cliente>\n";
$webservice .= "        <endereco_cliente xsi:type=\"xsd:string\">$endereco_cliente</endereco_cliente>\n";
$webservice .= "        <complemento_cliente xsi:type=\"xsd:string\"></complemento_cliente>\n";
$webservice .= "        <cep_cliente xsi:type=\"xsd:string\">$zipcode_cliente</cep_cliente>\n";
$webservice .= "        <bairro_cliente xsi:type=\"xsd:string\"></bairro_cliente>\n";
$webservice .= "        <cidade_cliente xsi:type=\"xsd:string\">$cidade</cidade_cliente>\n";
$webservice .= "        <uf_cliente xsi:type=\"xsd:string\"></uf_cliente>\n";
$webservice .= "        <fone_cliente xsi:type=\"xsd:string\">$mobile_cliente</fone_cliente>\n";
$webservice .= "      </vok:getInfoDoCartao>\n";
$webservice .= "   </soapenv:Body>\n";

$xml = "<soapenv:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:voki=\"OfertaNossa\"\n";
$xml .= "<soapenv:Header/>\n";
$xml .= $webservice;
$xml .= "</soapenv:Envelope>\n";

// Get the curl session object
$session = curl_init($request);

// Set the POST options.
curl_setopt ($session, CURLOPT_POST, true);
curl_setopt ($session, CURLOPT_POSTFIELDS, $xml);
curl_setopt($session, CURLOPT_HEADER, true);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// Do the POST and then close the session
$response = curl_exec($session);
curl_close($session);

// Get HTTP Status code from the response
$status_code = array();
preg_match('/\d\d\d/', $response, $status_code);

// Get the XML from the response, bypassing the header
if (!($xml = strstr($response, '<?xml'))) {
	$xml = null;
}

// Output the XML
print $xml;

?>