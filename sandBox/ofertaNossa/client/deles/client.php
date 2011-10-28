<?php
$xml  = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
	xmlns:ns1="vokiWebService" xmlns:xsd="http://www.w3.org/2001/XMLSchema"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/"
	SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
	<SOAP-ENV:Body>
		<ns1:addItens>
			<operationId xsi:type="xsd:int">230</operationId>
			<arrItens xsi:type="ns1:addItensParam">
				<arrItens xsi:type="ns1:itemType">
					<codigoDoCupomDeVenda xsi:type="xsd:string">09682020</codigoDoCupomDeVenda>
					<dataDaVenda xsi:type="xsd:string">2010-02-03 14:58:00</dataDaVenda>
					<precoDoProdutoVendido xsi:type="xsd:string">1.00</precoDoProdutoVendido>
					<codigoDoProdutoVendido xsi:type="xsd:string">TT01</codigoDoProdutoVendido>
					<status xsi:type="xsd:string">CA</status>
					<tipoDeCliente xsi:type="xsd:string">2</tipoDeCliente>
					<tipoDePessoa xsi:type="xsd:string">F</tipoDePessoa>
					<tipoDeInterface xsi:type="xsd:string">CLI</tipoDeInterface>
					<nomeOuRazaoSocialDoCliente xsi:type="xsd:string">Vinicius</nomeOuRazaoSocialDoCliente>
					<sobrenomeDoCliente xsi:type="xsd:string"></sobrenomeDoCliente>
					<nomeFantasiaOuDoCliente xsi:type="xsd:string">Vinicius</nomeFantasiaOuDoCliente>
					<ddd xsi:type="xsd:string">11</ddd>
					<fax xsi:type="xsd:string"></fax>
					<foneDoCliente xsi:type="xsd:string">92468892</foneDoCliente>
					<emailDoCliente xsi:type="xsd:string">tatupheba@gmail.com.br</emailDoCliente>
					<rg xsi:type="xsd:string">427390400</rg>
					<cpfOuCnpjDoCliente xsi:type="xsd:string">30007769822</cpfOuCnpjDoCliente>
					<inscricaoEstadual xsi:type="xsd:string"></inscricaoEstadual>
					<inscricaoMunicipal xsi:type="xsd:string"></inscricaoMunicipal>
					<cepDoCliente xsi:type="xsd:string">03040010</cepDoCliente>
					<ufDoCliente xsi:type="xsd:string">SP</ufDoCliente>
					<cidadeDoCliente xsi:type="xsd:string">São Paulo</cidadeDoCliente>
					<bairroDoCliente xsi:type="xsd:string">Brás</bairroDoCliente>
					<enderecoDoCliente xsi:type="xsd:string">Rua Carneiro Leão</enderecoDoCliente>
					<numeroDeEnderecoDoCliente xsi:type="xsd:string">290</numeroDeEnderecoDoCliente>
					<complementoDeEnderecoDoCliente	xsi:type="xsd:string">apto 84, bloco 2</complementoDeEnderecoDoCliente>
				</arrItens>
			</arrItens>
		</ns1:addItens>
	</SOAP-ENV:Body>
</SOAP-ENV:Envelope>
XML;

$curl = curl_init();

curl_setopt($curl, CURLOPT_HEADER , 1 );
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER , 1 );
curl_setopt($curl, CURLOPT_FOLLOWLOCATION , 1 );
curl_setopt($curl, CURLOPT_URL , 'http://172.16.100.15/integracao/server.php' );
curl_setopt($curl, CURLOPT_POST , 1 );
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/soap+xml; charset=utf-8'));
curl_setopt($curl, CURLOPT_POSTFIELDS , $xml);

$ret = curl_exec($curl);
$ern = curl_errno($curl);
$err = curl_error($curl);

curl_close( $curl );

print $ret;
?>