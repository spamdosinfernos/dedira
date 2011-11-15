<?php 

  $parametrosIG = array(
  'authType' =>2,
  'callerAddr' =>"",
  'login' =>"prt_tectotal",
  'password' =>"sWuphujuT66ruth",
  'sessionId' =>""
  );
  
  $spc = new SoapClient("http://hom.ig.sydle.com/igws/services/ExtendedSupportWS?wsdl", $parametrosIG);
  
  
  $param = array('AuthToken'=>$parametrosIG,'customerCpf'=>"28773109819");
$resultClient = $spc->__call('getCustomers',$param); 
?>