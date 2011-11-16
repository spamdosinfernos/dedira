<?php
require_once 'XTemplate.php';

/**
 * Extende a classe XTemplate de forma que o webserver sempre 
 * mande charset=ut8, isso é necessário pois o código do 
 * sistema está em utf8
 */
class XTemplatePersonalizado extends XTemplate {
	
	public function __construct($file, $tpldir = '', $files = null, $mainblock = 'main', $autosetup = true){
		//Manda o charset=utf8
		header('Content-Type: text/html; charset=utf-8');
		
		//Se o arquivo não existir lança uma nova excessão
		if(!file_exists($file)) throw new Exception("O arquivo de template $file não existe!");
		
		//Constrói a classe normalmente
		parent::__construct($file, $tpldir, $files, $mainblock, $autosetup);
	}
	
}
?>