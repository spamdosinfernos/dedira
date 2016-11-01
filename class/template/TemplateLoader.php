<?php
require_once '../general/template/XTemplate.php';

/**
 * Changes the default behavior of XTemplate class to send all pages using UTF8
 */
class TemplateLoader extends XTemplate {
	const ERROR_1 = 1;
	public function __construct($file, $tpldir = '', $files = null, $mainblock = 'main', $autosetup = true) {
		// Manda o charset=utf8
		header ( 'Content-Type: text/html; charset=utf-8' );

		// Constrói a classe normalmente
		parent::__construct ( $file, $tpldir, $files, $mainblock, $autosetup );
	}
}
?>