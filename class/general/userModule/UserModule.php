<?php

/**
 * Responsável por carregar os módulos do sistema 
 * @author tatupheba
 *
 */
class CUserModule extends CCore{

	/**
	 * Carrega o módulo de usuário dado seu nome.
	 * Para carregar os módulos do próprio sistema use "require" ou "require_once"
	 * @param string $userModuleName
	 */
	public function ativar($userModuleName){
		require_once Configuration::getUserModuleDiretory() . DIRECTORY_SEPARATOR . $userModuleName . ".php";

		$this->arrUserModules[$userModuleName] = new $userModuleName;
	}

	/**
	 * Descarrega o módulo de usuário dado seu nome.
	 * @param string $userModuleName
	 */
	public function desativar($userModuleName){
		unset($this->arrUserModules[$userModuleName]);
	}

	/**
	 * Instala um módulo, dado o caminho original do mesmo
	 * @param string $caminhoDoDiretorioDeOrigemDoModulo : Caminho para o diretório onde o módulo se encontra
	 * @return -1 : O caminho fornecido não é o caminho de um diretório | -2 : Falha ao copiar arquivos
	 */
	public function instalar($caminhoDoDiretorioDeOrigemDoModulo){

		if(is_dir($caminhoDoDiretorioDeOrigemDoModulo)) return -1;
		
		$copiou = copy($caminhoDoDiretorioDeOrigemDoModulo, Configuration::getUserModuleTestDiretory());
		
		if(!$copiou) return -2;
		//TODO definir a estrutura dos módulos afim de escrever as rotinas de verificação do módulo
		//TODO fazer teste de validação dos módulo TEMPORÁRIAMENTE carregado, tentar bolar um jeito de isolar o código do resto do sistema enquanto faço isso, já criei um diretório que para o código temporário em Modulos/teste
	}
}
?>