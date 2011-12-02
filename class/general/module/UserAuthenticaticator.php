<?php
require_once 'IModule.php';
require_once __DIR__ . '/../user/User.php';
require_once __DIR__ . '/../template/CustomXtemplate.php';
require_once __DIR__ . '/../security/PasswordPreparer.php';
require_once __DIR__ . '/../protocols/http/HttpRequest.php';
require_once __DIR__ . '/../security/authentication/UserAuthRules.php';
require_once __DIR__ . '/../security/authentication/Authenticator.php';
require_once __DIR__ . '/../configuration/module/UserAuthenticaticatorConf.php';
/**
 * Responsável por carregar os módulos do sistema 
 * @author tatupheba
 *
 */
class UserAuthenticaticator implements IModule{

	/**
	 * Gerencia os templates
	 * @var XTemplate
	 */
	protected $xTemplate;

	public function __construct(){
		$this->xTemplate = new CustomXtemplate(UserAuthenticaticatorConf::getAutenticationRequestTemplate());
	}

	/**
	 * Carrega o módulo de usuário dado seu nome.
	 * Para carregar os módulos do próprio sistema use "require" ou "require_once"
	 * @param string $userModuleName
	 */
	public function activate($userModuleName){
		print "activate";
	}

	/**
	 * Descarrega o módulo de usuário dado seu nome.
	 * @param string $userModuleName
	 */
	public function deactivate($userModuleName){
		print "deactivate";
	}

	/**
	 * Instala um módulo, dado o caminho original do mesmo
	 * @param string $moduleDirectoryPath : Caminho para o diretório onde o módulo se encontra
	 * @return -1 : O caminho fornecido não é o caminho de um diretório | -2 : Falha ao copiar arquivos
	 */
	public function install($moduleDirectoryPath){
		print $moduleDirectoryPath;
	}

	public function handleRequest(){

		//Se já estiver autenticado sai do método com true
		$authenticator = new Authenticator();
		if($authenticator->isAuthenticated()) return true;

		//Recupera a requisição
		$httpRequest = new HttpRequest();
		$postedVars = $httpRequest->getPostRequest();

		//Se os dados não foram postados corretamente sai do método com false 
		if(!isset($postedVars["user"]) || !isset($postedVars["password"])) return false;

		//Prepara o usuário para a verificação
		$user = new User();
		$user->setLogin($postedVars["user"]);
		$user->setPassword(PasswordPreparer::messItUp($postedVars["password"]));

		//Tenta autenticar o usuário no sistema
		$authenticator->setAuthenticationRules(new UserAuthRules($user));
		return $authenticator->authenticate();
	}

	public function showGui($arrGuiBlockNames = array()){
		$this->xTemplate->assign("title", $this->getTitle());


		//Mostra os blocos de interface especificados
		foreach ($arrGuiBlockNames as $guiBlock) {
			$this->xTemplate->parse($guiBlock);
		}

		//Mostra o bloco principal
		$this->xTemplate->parse("main");
		$this->xTemplate->out("main");
	}

	public function getTitle(){
		return UserAuthenticaticatorConf::getAuthMessage();
	}
}
?>