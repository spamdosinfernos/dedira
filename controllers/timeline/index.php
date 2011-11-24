<?php
require_once __DIR__ . '/../../class/general/template/CustomXtemplate.php';
require_once __DIR__ . '/../../class/timeline/configuration/TimelineConfiguration.php';


class index{

	/**
	 * Renderiza as páginas
	 * @var CustomXtemplate
	 */
	private $customXtemplate;

	/**
	 * Constrói a página o cronograma
	 */
	public function __construct(){
		$this->customXtemplate = new CustomXtemplate(TimelineConfiguration::getTemplatesDirectory() . "index.html");
		$this->customXtemplate->parse("main");
		$this->customXtemplate->out("main");
		
		//TODO Parei aqui, já fiz uma primeira tela básica que faz nada, preciso me focar na classe de templates afim de gerar telas mais facilmente além de poder traduzi-las também, preciso pensar em uma forma eficiente de carregar os idiomas no sistema sem carregar a memória
	}

	/**
	 * Mostra mensagem de erro em caso de falha na autenticação
	 */
	private function showAuthenticationFailurePage(){
		$this->xtemplate->assign("mensagemDeErro","Falha na autenticação");
		$this->xtemplate->parse("main.mensagemDeErro");
		$this->xtemplate->parse("main.login");
		$this->xtemplate->parse("main");
		$this->xtemplate->out("main");
	}

	/**
	 * Tenta autenticar o usuário no sistema
	 */
	private function authenticate(){

		//Cria o objeto que contêm as regras para autenticação neste sistema
		$authParams = new IntegracaoAuthRules();
		$authParams->setLogin($_POST["login"]);
		$authParams->setPassword($_POST["password"]);

		//Tenta authenticate no sistema
		$this->authenticator->setAuthenticationRules($authParams);
		return $this->authenticator->authenticate();
	}
}
new index();
?>