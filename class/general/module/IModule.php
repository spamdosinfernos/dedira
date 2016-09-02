<?php
/**
 * Um módulo é um pedaço de sistema com código e interface gráfica 
 * @author André Furlan
 */
interface IModule {
	
	/**
	 * Carrega o módulo de usuário dado seu nome.
	 * Para carregar os módulos do próprio sistema use "require" ou "require_once"
	 *
	 * @param string $userModuleName        	
	 */
	public function activate($userModuleName);
	/**
	 * Descarrega o módulo de usuário dado seu nome.
	 *
	 * @param string $userModuleName        	
	 */
	public function deactivate($userModuleName);
	
	/**
	 * Instala um módulo, dado o caminho original do mesmo
	 *
	 * @param string $moduleDirectoryPath
	 *        	: Caminho para o diretório onde o módulo se encontra
	 * @return -1 : O caminho fornecido não é o caminho de um diretório | -2 : Falha ao copiar arquivos
	 */
	public function install($moduleDirectoryPath);
	
	/**
	 * Mostra a interface do módulo ou parte dela caso o nome do bloco seja especificado
	 *
	 * @param
	 *        	array : string $arrGuiBlockNames
	 */
	public function showGui($arrGuiBlockNames = array());
	
	/**
	 * Manipula as requisições (via POST, GET ou RAW_HTTP_DATA)
	 *
	 * @return boolean
	 */
	public function handleRequest();
	
	/**
	 * Recupera o título do módulo
	 *
	 * @return string
	 */
	public function getTitle();
}
?>