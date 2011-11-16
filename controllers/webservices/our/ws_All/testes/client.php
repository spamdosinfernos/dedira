<?php
$oSoapClient = new SoapClient("http://localhost/wwwint/controllers/webservices/our/ws_All/Server.php?srvid=12", array("trace" => 1, "exceptions" => 1, "user_agent"=>""));

$arrParams = array(
array("codigoDoProdutoVendido" => "cc09","codigoDeSequenciaDoProdutoVendido" => "00004391")
);

try{
	$teste = $oSoapClient->getStatusDoProdutoVendido($arrParams);
}catch (SoapFault $e){
	$teste2 = $oSoapClient->__getLastRequest();
	print $e->xdebug_message;
}

$teste2 = $oSoapClient->__getLastRequest();
print $teste2;
?>