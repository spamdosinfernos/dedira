<?php
require_once __DIR__ . '/../../class/general/filesystem/File.php';
require_once __DIR__ . '/../../class/general/template/CustomXtemplate.php';

require_once __DIR__ . '/../../class/general/user/User.php';
require_once __DIR__ . '/../../class/general/user/UserLister.php';

require_once __DIR__ . '/../../class/general/partner/Partner.php';
require_once __DIR__ . '/../../class/general/partner/PartnerLister.php';

require_once __DIR__ . '/../../class/general/database/Database.php';
require_once __DIR__ . '/../../class/general/database/IDataBaseOperations.php';

require_once __DIR__ . '/../../class/general/security/Shield.php';
require_once __DIR__ . '/../../class/general/security/Authenticator.php';
require_once __DIR__ . '/../../class/general/security/IntegracaoAuthRules.php';

require_once __DIR__ . '/../../class/ws_integracao_ui/Chart.php';
require_once __DIR__ . '/../../class/ws_integracao_ui/WsIntegracaoUiLog.php';
require_once __DIR__ . '/../../class/ws_integracao_ui/WsIntegracaoUiConf.php';

/**
 * Mostra a tela de login pra o usuário
 * Caso o mesmo esteja auitenticado mostra
 * o menu inicial da aplicação
 */
class index{

	/**
	 * Aponta para o montador da página
	 * @var XTemplatePersonalizado
	 */
	private $xtemplate;

	/**
	 * Aponta para o objeto responsável pela autenticação do sistema
	 * @var Authenticator
	 */
	private $authenticator;

	/**
	 * Aponta para o objeto que representa o usuário
	 * @var User
	 */
	private $user;

	/**
	 * Contêm a id da página que está sendo solicitada
	 * @var int
	 */
	private $currentPage;

	/**
	 * Constrói a página
	 */
	public function __construct(){

		try{
			Shield::treatTextFromForm();

			//Instancia o autenticador
			$this->authenticator = new Authenticator();
			//Instancia a classe que retornará o caminho relativo da folha de estilo
			$styleSheet = new File(WsIntegracaoUiConf::getStyleSheetPath());
			//Instancia o gerenciador de templates
			$this->xtemplate = new XTemplatePersonalizado(WsIntegracaoUiConf::getCaminhoDosTemplates() . DIRECTORY_SEPARATOR . "index.html");

			//Setando os propriedades de cabeçalho da página
			$this->xtemplate->assign("title",WsIntegracaoUiConf::CONST_MAIN_TITLE);

			//Seta a folha de estilo
			$this->xtemplate->assign("cssPath",$styleSheet->getRelativePath(__DIR__));

			//Verifica se o usuário está autenticado
			if($this->authenticator->isAuthenticated()){
				$this->user = new User();
				$this->user->setUserId($this->authenticator->getUserId());
				$this->user->load();
				$this->treateSentData();
				return;
			}

			//Se não estiver verifica se a autenticação foi requerida
			if($this->authenticator->isAuthenticationRequested()){

				//Se a autenticação foi requerida tentar authenticate o usuário
				$this->authenticate();

				//Mostra a falha de autenticação se houver
				if($this->authenticator->isAuthenticated()){
					$this->user = new User();
					$this->user->setUserId($this->authenticator->getUserId());
					$this->user->load();
					$this->showMainPage();
					return;
				}

				$this->showAuthenticationFailurePage();
				return;
			}

			/*
			 * Se o usuário não estiver autenticado e ainda não requisitou 
			 * autenticação não entra na página (pede login e senha)
			 */
			$this->showLoginRequestPage();
			return;

		}catch (Exception $e){
			new WsIntegracaoUiLog("Erro na Linha: " . $e->getLine() . " no arquivo: " . $e->getFile() . " Mensagem: " . $e->getMessage());
			print("Erro inesperado veja o log para mais informações: " . $e->getMessage());
		}
	}


	/**
	 * Gerencia as requisições recebidas via get e post.
	 * Seu papel é descobrir o que fazer com as 
	 * informações recebidas (salvar, atualizar, 
	 * apagar, etc) e redirecionar o fluxo para
	 * a página requisitada 
	 */
	private function treateSentData(){

		/*
		 * Por algum motivo místico o parâmetro "page" vem 
		 * no POST embora eu não o tenha colocado lá
		 * sendo assim tenho que retirá-lo
		 */
		unset($_POST["page"]);

		//Se a página não for especificada mostra a página inicial
		if(!isset($_GET["page"])){
			$gerarNovoGrafico = (isset($_POST["force"]) && $_POST["force"] == "true") ? true : false;
			$this->showMainPage($gerarNovoGrafico);
			return;
		}

		//Seta a página que está sendo solicitada
		$this->currentPage = $_GET["page"];

		//Cria uma lista com as id de páginas válidas
		$arrValidPages = array(
		WsIntegracaoUiConf::CONST_PAGE_USER_ADD,
		WsIntegracaoUiConf::CONST_PAGE_USER_MODIFY,
		WsIntegracaoUiConf::CONST_PAGE_USER_DELETE,
		WsIntegracaoUiConf::CONST_PAGE_USER_LOGOUT,
		WsIntegracaoUiConf::CONST_PAGE_PARTNER_CHART,
		WsIntegracaoUiConf::CONST_PAGE_PARTNERS_CHARTS,
		WsIntegracaoUiConf::CONST_PAGE_USER_LIST_ACTIVE_USERS,
		WsIntegracaoUiConf::CONST_PAGE_USER_LIST_NON_ACTIVE_USERS
		);

		try{
			//Só admite código de páginas válidas
			if(!in_array($this->currentPage, $arrValidPages)) throw new Exception("O código da página usado não é válido: $this->currentPage");

			//Entra aqui caso o usuário poste alguma coisa
			if(isset($_POST) && count($_POST) > 0){

				//Trata os dados postados
				switch ($this->currentPage){
					case WsIntegracaoUiConf::CONST_PAGE_USER_ADD :
						//Não permite que usuários normais modifique os dados
						if(!$this->user->isAdmin()) break;
						$userId = $this->saveNewUser();
						$this->setSystemAnounce("Usuário inserido com sucesso, seu código é $userId");
						break;
					case WsIntegracaoUiConf::CONST_PAGE_USER_MODIFY :
						//Não permite que usuários normais modifique os dados
						if(!$this->user->isAdmin()) break;
						$userId = $this->modifyUser();
						if($userId > 0) $this->setSystemAnounce("Dados do usuário $userId atualizados com sucesso");
						break;
					case WsIntegracaoUiConf::CONST_PAGE_USER_DELETE :
						//Não permite que usuários normais modifique os dados
						if(!$this->user->isAdmin()) break;
						$this->deleteUser();
						$this->setSystemAnounce("Usuário apagado com sucesso");
						break;
					case WsIntegracaoUiConf::CONST_PAGE_PARTNER_CHART :
						$this->showPartnerChartPage();
						return;
				}
			}

		}catch(Exception $e){
			new WsIntegracaoUiLog("Erro na Linha: " . $e->getLine() . " no arquivo: " . $e->getFile() . " Mensagem: " . $e->getMessage());
			$this->setSystemAnounce($e->getMessage());
		}

		//Mostra a página escolhida pelo usuário
		switch ($this->currentPage){
			case WsIntegracaoUiConf::CONST_PAGE_USER_LIST_ACTIVE_USERS :
				//Não permite que usuários normais modifique os dados
				if(!$this->user->isAdmin()) break;
				$this->showUsersList(true);
				break;
			case WsIntegracaoUiConf::CONST_PAGE_USER_LIST_NON_ACTIVE_USERS :
				//Não permite que usuários normais modifique os dados
				if(!$this->user->isAdmin()) break;
				$this->showUsersList(false);
				break;
			case WsIntegracaoUiConf::CONST_PAGE_USER_ADD :
				//Não permite que usuários normais modifique os dados
				if(!$this->user->isAdmin()) break;
				$this->showModifyUserPage();
				break;
			case WsIntegracaoUiConf::CONST_PAGE_USER_MODIFY :
				//Não permite que usuários normais modifique os dados
				if(!$this->user->isAdmin()) break;
				$this->showModifyUserPage($_POST["userId"]);
				break;
			case WsIntegracaoUiConf::CONST_PAGE_USER_DELETE :
				//Não permite que usuários normais modifique os dados
				if(!$this->user->isAdmin()) break;
				$this->showUserEraseConfirmationPage($_POST["userId"]); //TODO Implementar
				break;
			case WsIntegracaoUiConf::CONST_PAGE_USER_LOGOUT :
				$this->authenticator->unauthenticate();
				new index();
				break;
			case WsIntegracaoUiConf::CONST_PAGE_PARTNERS_CHARTS :
				$this->showPartnersChartsPage();
				break;
			case WsIntegracaoUiConf::CONST_PAGE_PARTNER_CHART :
				$this->showPartnerChartPage();
				break;
		}
	}

	/**
	 * Mostra a página dos charts de um único parceiro
	 * @param int $partnerId
	 */
	private function showPartnerChartPage(){

		//O nível padrão neste caso é por hora
		$detailLevel = isset($_POST["detailLevel"]) == "" ?  WsIntegracaoUiConf::CONST_CHARTS_DETAIL_LEVEL_HOUR : $_POST["detailLevel"];

		//Devo forçar a geração de um novo gráfico?
		$force = (isset($_POST["force"]) && $_POST["force"] == "true") ? true : false;

		$rebuildDefaultCharts = (isset($_POST["forceDefault"]) && $_POST["forceDefault"] == "on") ? true : false;

		//Carregando os dados do parceiro
		$partner = new Partner();
		$partner->setPartnerId($_POST["partnerId"]);
		$partner->load();

		//Recupera a data inicial e final para a consulta no banco de dados
		try{
			@$inicialDate = new DateTime($_POST["anoInicial"] . "-" . $_POST["mesInicial"] . "-" . $_POST["diaIncial"] . " " . $_POST["horaInicial"] . ":" . $_POST["minutoInicial"]);
			@$finalDate = new DateTime($_POST["anoFinal"] . "-" . $_POST["mesFinal"] . "-" . $_POST["diaFinal"] . " " . $_POST["horaFinal"] . ":" . $_POST["minutoFinal"]);
		}catch (Exception $e){

			//O periodo padrão é de um dia, ou seja, das 0 as 23:59:59
			$today = new DateTime();
			$inicialDate = new DateTime($today->format("Y-m-d") . " 00:00:00");
			$finalDate = new DateTime($today->format("Y-m-d") . " 23:59:59");
			$detailLevel = WsIntegracaoUiConf::CONST_CHARTS_DETAIL_LEVEL_HOUR;
		}

		//Gera um data formatada usada na visualização
		$fomatedIncialDate = $inicialDate->format("d/m/Y H:i:s");
		$fomatedFinalDate = $finalDate->format("d/m/Y H:i:s");

		//Gera o gráfico
		$chartImage = new File($this->generateChart($force, $inicialDate, $finalDate, $detailLevel, $partner->getPartnerId()));

		//Gera os graficos padrão
		$arrDefaultCharts = $this->generatePartnerDefaultCharts($rebuildDefaultCharts, $partner->getPartnerId());

		//Feito tudo isso, finalmente seta os resultados a serem visualizados
		$this->xtemplate->assign("name", $partner->getName());
		$this->xtemplate->assign("finalDate", $fomatedFinalDate);
		$this->xtemplate->assign("incialDate", $fomatedIncialDate);
		$this->xtemplate->assign("partnerId", $partner->getPartnerId());
		$this->xtemplate->assign("pageId", WsIntegracaoUiConf::CONST_PAGE_PARTNER_CHART);
		$this->xtemplate->assign("detailLevelText", WsIntegracaoUiConf::getChartsDetailLevelDescription($detailLevel));
		$this->xtemplate->assign("chartImagemPath", $chartImage->getRelativePath(__DIR__));

		$this->xtemplate->assign("detailLevelDay", WsIntegracaoUiConf::CONST_CHARTS_DETAIL_LEVEL_DAY);
		$this->xtemplate->assign("detailLevelHour", WsIntegracaoUiConf::CONST_CHARTS_DETAIL_LEVEL_HOUR);
		$this->xtemplate->assign("detailLevelYear", WsIntegracaoUiConf::CONST_CHARTS_DETAIL_LEVEL_YEAR);
		$this->xtemplate->assign("detailLevelMonth", WsIntegracaoUiConf::CONST_CHARTS_DETAIL_LEVEL_MONTH);
		$this->xtemplate->assign("detailLevelMinuteCod", WsIntegracaoUiConf::CONST_CHARTS_DETAIL_LEVEL_MINUTE);

		$this->xtemplate->assign("dayLogname", "Gráfico do dia");
		$this->xtemplate->assign("dayChartImagemPath", $arrDefaultCharts["dayChart"]->getRelativePath(__DIR__));

		$this->xtemplate->assign("weekLogname", "Gráfico da semana");
		$this->xtemplate->assign("weekChartImagemPath", $arrDefaultCharts["weekChart"]->getRelativePath(__DIR__));

		$this->xtemplate->assign("monthLogname", "Gráfico do mês");
		$this->xtemplate->assign("monthChartImagemPath", $arrDefaultCharts["monthChart"]->getRelativePath(__DIR__));

		//Termina de exibir a página
		$this->setupMenu();
		$this->xtemplate->parse("main.contents.partnerChart");
		$this->terminatePageShowing();
	}

	/**
	 * Gera os gráficos padrões do parceiro
	 * @param boolean $force
	 * @param int $partnerId
	 */
	private function generatePartnerDefaultCharts($force = false, $partnerId){

		$arrChartsImages = array();

		/*
		 * Gráfico do dia
		 */
		$today = new DateTime();

		$inicialDate = new DateTime($today->format("Y-m-d") . " 00:00:00");
		$finalDate = new DateTime($today->format("Y-m-d") . " 23:59:59");

		//Gera um data formatada usada na visualização
		$fomatedIncialDate = $inicialDate->format("d/m/Y H:i:s");
		$fomatedFinalDate = $finalDate->format("d/m/Y H:i:s");

		$detailLevel = WsIntegracaoUiConf::CONST_CHARTS_DETAIL_LEVEL_DAY;

		//Gera o gráfico
		$arrChartsImages["dayChart"] = new File($this->generateChart($force, $inicialDate, $finalDate, $detailLevel, $partnerId));


		/*
		 * Gráfico da semana
		 */
		$today = new DateTime();

		$today->modify("last sunday");
		$inicialDate = new DateTime($today->format("Y-m-d") . " 00:00:00");

		$today->modify("next saturday");
		$finalDate = new DateTime($today->format("Y-m-d") . " 23:59:59");

		//Gera um data formatada usada na visualização
		$fomatedIncialDate = $inicialDate->format("d/m/Y H:i:s");
		$fomatedFinalDate = $finalDate->format("d/m/Y H:i:s");

		$detailLevel = WsIntegracaoUiConf::CONST_CHARTS_DETAIL_LEVEL_MONTH;

		//Gera o gráfico
		$arrChartsImages["weekChart"] = new File($this->generateChart($force, $inicialDate, $finalDate, $detailLevel, $partnerId));


		/**
		 * Grafico do mês
		 */
		$today = new DateTime();

		$inicialDate = new DateTime($today->format("Y-m-") . "01 00:00:00");

		$today->modify("+1 month");
		$today = new DateTime($today->format("Y-m-") . "01 00:00:00");
		$today->modify("-1 day");

		$finalDate = new DateTime($today->format("Y-m-d") . " 00:00:00");

		//Gera um data formatada usada na visualização
		$fomatedIncialDate = $inicialDate->format("d/m/Y H:i:s");
		$fomatedFinalDate = $finalDate->format("d/m/Y H:i:s");

		$detailLevel = WsIntegracaoUiConf::CONST_CHARTS_DETAIL_LEVEL_MONTH;

		//Gera o gráfico
		$arrChartsImages["monthChart"] = new File($this->generateChart($force, $inicialDate, $finalDate, $detailLevel, $partnerId));

		return $arrChartsImages;
	}

	/**
	 * Mostra as páginas de charts dos parceiros
	 * @param int $partnerId
	 */
	private function showPartnersChartsPage(){

		$partnersLister = new PartnerLister();
		$partnersLister->loadUserBoundedActivePartners($this->authenticator->getUserId());

		$arrPartners = $partnersLister->getArrPartners();


		foreach ($arrPartners as $partner) {
			//Gera o gráfico do dia para cada parceiro
			$currentMonthLogImage = new File($this->generateChart(false, new DateTime(), new DateTime(), WsIntegracaoUiConf::CONST_CHARTS_DETAIL_LEVEL_DAY, $partner->getPartnerId()));

			//Gera o caminho relativo para colocar na página
			$chartImagemPath = $currentMonthLogImage->getRelativePath(__DIR__);

			//Mostra os gráficos gerados
			$this->xtemplate->assign("name", $partner->getName());
			$this->xtemplate->assign("chartImagemPath", $chartImagemPath);
			$this->xtemplate->assign("partnerId", $partner->getPartnerId());
			$this->xtemplate->assign("pageId", WsIntegracaoUiConf::CONST_PAGE_PARTNER_CHART);

			$this->xtemplate->parse("main.contents.partnersCharts.partner");
		}

		$this->setupMenu();
		$this->xtemplate->parse("main.contents.partnersCharts");
		$this->terminatePageShowing();
	}

	/**
	 * Mostra a lista de usuários ativos e inativos 
	 * @param boolean $activeOnes : true - Usuários ativos | false - Usuários inativos
	 */
	private function showUsersList($activeOnes = true){

		$userLister = new UserLister();

		$this->setupMenu();

		//Carrega os usuários pedidos
		if($activeOnes){
			$userLister->loadActiveUsers();
		}else{
			$userLister->loadNonActiveUsers();
		}

		$arrUsers = $userLister->getArrUsers();

		foreach ($arrUsers as $user) {
			$this->xtemplate->assign("login", $user->getLogin());
			$this->xtemplate->assign("userName", $user->getRealName());
			$this->xtemplate->assign("userId", $user->getUserId());

			//A postagem deve ir para a página de modificação de usuário
			$this->xtemplate->assign("pageId", WsIntegracaoUiConf::CONST_PAGE_USER_MODIFY);

			$this->xtemplate->parse($activeOnes ? "main.contents.userManager.listActiveUsers.user" : "main.contents.userManager.listNonActiveUsers.user");
		}

		$this->xtemplate->parse($activeOnes ? "main.contents.userManager.listActiveUsers" : "main.contents.userManager.listNonActiveUsers");
		$this->xtemplate->parse("main.contents.userManager");
		$this->terminatePageShowing();
	}

	/**
	 * Mostra a caixa pedindo usuário e senha
	 */
	private function showLoginRequestPage(){
		$this->xtemplate->assign("title","Autentique-se no sistema");
		$this->xtemplate->parse("main.login");
		$this->xtemplate->parse("main");
		$this->xtemplate->out("main");
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
	 * Mostra o menu da aplicação
	 */
	private function showMainPage($force = false){

		//Periodo padrão é de um dia: das 00:00 as 23:59:59
		$inicialDate = new DateTime();
		$inicialDate = new DateTime($inicialDate->format("Y-m-d") . " 00:00:00");
		$finalDate = new DateTime();
		$finalDate = new DateTime($inicialDate->format("Y-m-d") . " 23:59:59");

		//Gera o gráfico
		$currentMonthLogImage = new File($this->generateChart($force, $inicialDate, $finalDate, WsIntegracaoUiConf::CONST_CHARTS_DETAIL_LEVEL_DAY));
		$currentDayImageRelativePath = $currentMonthLogImage->getRelativePath(__DIR__);

		$this->xtemplate->assign("currentDayLogDescription", "Log de hoje");
		$this->xtemplate->assign("currentDayLogImagePath", $currentDayImageRelativePath);

		//Mostra a página
		$this->setupMenu();
		$this->xtemplate->parse("main.contents.mainPage");
		$this->terminatePageShowing();
	}

	/**
	 * Mostra a interface de administração de usuários
	 * @param int $userId : Identificação do usuário
	 * @param int $operation : Código da operação
	 */
	private function showModifyUserPage($userId = null){

		$arrBoundedIdPartners = array();

		if(!is_null($userId)){
			$user = new User();
			$user->setUserId($userId);
			$user->load();

			//Recupera as ids dos parceiros vinculados
			$arrBoundedIdPartners = $user->getArrBoundedPartners();
		}

		//Recupera os dados do usuário e dos parceiros vinculados
		$arrAllPartnersData = $this->getAllPartnersData();

		//Seta a id da página de destino dos dados que serão postados
		$this->xtemplate->assign("pageId", $this->currentPage);

		//Monta os check boxes dos parceiros na página
		foreach ($arrAllPartnersData as $arrPartnerData) {
			$this->xtemplate->assign("partnerId", $arrPartnerData["id_parceiro"]);
			$this->xtemplate->assign("partnerName", $arrPartnerData["nomeParceiro"]);
			$this->xtemplate->parse("main.contents.userManager.addAndModify.partnerList.partnerListItem");
		}
		$this->xtemplate->parse("main.contents.userManager.addAndModify.partnerList");

		//Setando os outros campos com os dados do usuário
		if(!is_null($userId)){
			$this->xtemplate->assign("emailAlternativo", $user->getAnotherEmail());
			$this->xtemplate->assign("telefone", $user->getTelefone());
			$this->xtemplate->assign("nome", $user->getRealName());
			$this->xtemplate->assign("userId", $user->getUserId());
			$this->xtemplate->assign("email", $user->getEmail());
			$this->xtemplate->assign("login", $user->getLogin());
			$this->xtemplate->assign("ddd", $user->getDdd());

			//Setando o status
			if($user->getStatus()){
				$this->xtemplate->assign("statusTrue", 'selected="selected"');
			}else{
				$this->xtemplate->assign("statusFalse", 'selected="selected"');
			}

			//Setando se é ou não administrador
			if($user->isAdmin()){
				$this->xtemplate->assign("admin", 'checked="checked"');
			}

		}

		$this->setupMenu();
		$this->xtemplate->parse("main.contents.userManager.addAndModify");
		$this->xtemplate->parse("main.contents.userManager");
		$this->terminatePageShowing();
	}

	/**
	 * Use isto para mostrar mensagens do sistema ao usuário
	 * @param $anounce
	 */
	private function setSystemAnounce($anounce){
		$this->xtemplate->assign("systemAnounces", $anounce);
	}

	/**
	 * Tenta save na base um novo usuário
	 * @return int : id do usuário salvo
	 */
	private function saveNewUser(){
		$user = new User();

		$user->setDdd($_POST["ddd"]);
		$user->setEmail($_POST["email"]);
		$user->setLogin($_POST["login"]);
		$user->setStatus((boolean)$_POST["status"]);
		$user->setAdmin((boolean)$_POST["admin"]);
		$user->setPassword($_POST["senha"]);
		$user->setRealName($_POST["nome"]);
		$user->setTelefone($_POST["telefone"]);
		$user->setAnotherEmail($_POST["emailAlternativo"]);

		//Ainda falta implementar a vinculação com os parceiros
		$user->setArrBoundedPartners($_POST["partnerIds"]);

		return $user->save();
	}

	/**
	 * Tenta save na base as modificações de um usuário
	 * @return int : id do usuário modificado
	 */
	private function modifyUser(){

		//Só atualiza se todos os campos forem enviados
		if(count($_POST) < 5) return;

		$user = new User();
		$user->setUserId($_POST["userId"]);
		@$user->setDdd($_POST["ddd"]);
		@$user->setEmail($_POST["email"]);
		@$user->setLogin($_POST["login"]);
		@$user->setAdmin((boolean)$_POST["admin"]);
		@$user->setStatus((boolean)$_POST["status"]);
		@$user->setPassword($_POST["senha"]);
		@$user->setRealName($_POST["nome"]);
		@$user->setTelefone($_POST["telefone"]);
		@$user->setAnotherEmail($_POST["emailAlternativo"]);

		//Ainda falta implementar a vinculação com os parceiros
		@$user->setArrBoundedPartners($_POST["partnerIds"]);

		return $user->save();
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

	/**
	 * Gera o gráfico dos logs para o mês atual
	 * @param boolean $force - true : Força a geração de uma nova imagem - false : não força a geração de uma nova imagem
	 * @param $partnerId : Id do parceiro
	 * @return string : Caminho para a imagem do gráfico 
	 */
	private function generateChart($force = false, DateTime $inicialDate, DateTime $finalDate, $detailLevel, $partnerId = null){

		$arrDataGroup = array(
		WsIntegracaoUiConf::CONST_CHARTS_DETAIL_LEVEL_DAY,
		WsIntegracaoUiConf::CONST_CHARTS_DETAIL_LEVEL_HOUR,
		WsIntegracaoUiConf::CONST_CHARTS_DETAIL_LEVEL_YEAR,
		WsIntegracaoUiConf::CONST_CHARTS_DETAIL_LEVEL_MONTH,
		WsIntegracaoUiConf::CONST_CHARTS_DETAIL_LEVEL_MINUTE
		);

		//Evita que uma opção inexistente seja processada
		if(!in_array($detailLevel, $arrDataGroup)){
			//Se for escolhida uma opção inválida escolhe agrupar pelo di
			$detailLevel = WsIntegracaoUiConf::CONST_CHARTS_DETAIL_LEVEL_DAY;
		}

		//Separa os niveis de detalhes
		$arrDetailLevel = explode(",",$detailLevel);

		//Gera as datas formatadas para exibição
		$formatedInicialDate = $inicialDate->format("Y-m-d_H:i:s");
		$formatedFinalDate = $finalDate->format("Y-m-d_H:i:s");

		//Gera as datas formatadas para uso na consulta ao banco
		$queryInicialDate = $inicialDate->format("Y-m-d H:i:s");
		$queryFinalDate = $finalDate->format("Y-m-d H:i:s");

		//Gera o caminho para o gráfico do mês atual
		$imgPath = WsIntegracaoUiConf::getChartImageDirectory() . DIRECTORY_SEPARATOR . $partnerId . "-" . $formatedInicialDate . "ate" . $formatedFinalDate . ".png";

		/*
		 * Caso não seja necessário gerar forçosamente outra imagem 
		 * retorna o caminho da imagem existente, caso a imagem não
		 * exista gera a mesma.
		 */
		if(!$force){
			if(file_exists($imgPath)) return $imgPath;
		}

		//Se chegou até aqui é por que temos que gerar a imagem
		$chart = new Chart();
		$db = new DataBase(new V3DbOperations());

		//Gera o cabeça da consulta (o select)
		$sql = 'select "descricao", ';
		foreach ($arrDetailLevel as $index => $detail) {
			$arrDetailLevel[$index] = trim($detail);
			$sql .= "date_part('$arrDetailLevel[$index]',\"dataOcorrencia\") as $arrDetailLevel[$index], ";
		}
		$sql .= "count(date_part('second',\"dataOcorrencia\")) as qtde, ";
		$sql .= "\"numeroDeItensProcessados\" as processados, ";
		$sql .= '"tbl_operacao"."id_operacao" ';


		//Gera o corpo da consulta (o from e o where)
		if(is_null($partnerId)){
			//Se o parceiro não for informado carrega o gráfico geral
			$sql .= <<<SQL
			from 
			 	"tbl_ocorrencia", 
			 	"tbl_tipoDeOcorrencia"
			WHERE 
			 	tbl_ocorrencia."id_tipoOcorrencia" = "tbl_tipoDeOcorrencia"."id_tipoOcorrencia" and 
			 	tbl_ocorrencia."dataOcorrencia" >= '$queryInicialDate' and
			 	tbl_ocorrencia."dataOcorrencia" <= '$queryFinalDate'
SQL;
		}else{
			//Se o parceiro for informado carrega o gráfico do parceiro
			$sql .= <<<SQL
			FROM 
				"tbl_ocorrencia", 
				"tbl_tipoDeOcorrencia",
				"tbl_operacao", 
				"tbl_parceiro"
			WHERE 
				tbl_ocorrencia."id_tipoOcorrencia" = "tbl_tipoDeOcorrencia"."id_tipoOcorrencia" and 
				tbl_operacao."id_operacao" = tbl_ocorrencia."id_operacao" and 
				tbl_parceiro."id_parceiro" = tbl_operacao."id_parceiro" and 
				tbl_parceiro."id_parceiro" = $partnerId and 
				tbl_ocorrencia."dataOcorrencia" >= '$queryInicialDate' and
				tbl_ocorrencia."dataOcorrencia" <= '$queryFinalDate'
SQL;
		}

		//Gera o rodapé da consulta (group by e order by)
		$arrDetailLevel = array_reverse($arrDetailLevel);
		$sql .= 'GROUP BY ' . join(",", $arrDetailLevel) . ', descricao, "tbl_operacao"."id_operacao", processados ';

		$arrDetailLevel = array_reverse($arrDetailLevel);
		$sql .= 'ORDER BY "tbl_operacao"."id_operacao",' . join(",", $arrDetailLevel);

		//Executa a consulta
		$ok = $db->execReturnableSql($sql);
		if(!$ok) throw new Exception("Falha ao executa a consulta para geração dos gráficos de log para os parceiros");

		//Monta o dataset do gráfico
		$arrDetailLevel = array_reverse($arrDetailLevel);
		$arrResult = $db->getFetchArray();
		$currentOperation = "";
		foreach ($arrResult as $result) {


			//Monta a legenda do eixo x para cada um dos intervalos
			$arrLabel = array("day" => "","/","month" => "","/","year" => ""," ","hour" => "",":","minute" => "",":","second" => "");
			foreach ($arrDetailLevel as $detail) {

				//A legenda do eixo x
				$arrLabel[$detail] = $result[$detail];
			}
			$label = trim(join("",$arrLabel));
			$label = substr($label,-1,1) == ":" ? substr($label,0,strlen($label)-1) : $label;
			$label = substr($label,-1,1) == "/" ? substr($label,0,strlen($label)-1) : $label;

			//Adiciona o ponto ao gráfico
			$chart->addDataPoint($result["descricao"], $label, $result["qtde"]);

			//Seta o total de itens processados,
			if($result["id_operacao"] != $currentOperation){
				$chart->addDataPoint("Total", $label, $result["processados"]);
				$currentOperation = $result["id_operacao"];
				}

			//Setando os rótulos do eixo 
			$chart->addDataPoint("XAxisNames",$label);
		}

		//Últimos retoques na bagaça...
		$chart->setXAxisName("XAxisNames");
		$chart->setFontSize(12);
		$chart->setLineWidth(2);
		$chart->setOutputImagePath($imgPath);

		//Gera e grava o arquivo
		$chart->generate();

		//Retorna o caminho da imagem
		return $imgPath;
	}

	/**
	 * Recupera os dados de todos os parceiros
	 * @return array : string - Caminho para a imagem do gráfico 
	 */
	private function getAllPartnersData(){
		$db = new DataBase(new V3DbOperations());
		$sql = 'select "id_parceiro", "nomeParceiro", "status" from tbl_parceiro';
		$db->execReturnableSql($sql);
		return $db->getFetchArray();
	}

	/**
	 * Mostra o menu
	 */
	private function setupMenu(){

		//Coloque dentro deste if todos os menus de administrador
		if($this->user->isAdmin()){
			$this->xtemplate->assign("userAddPageId", WsIntegracaoUiConf::CONST_PAGE_USER_ADD);
			$this->xtemplate->assign("userListActiveUsersPageId", WsIntegracaoUiConf::CONST_PAGE_USER_LIST_ACTIVE_USERS);
			$this->xtemplate->assign("userListNonActiveUsersPageId", WsIntegracaoUiConf::CONST_PAGE_USER_LIST_NON_ACTIVE_USERS);
			$this->xtemplate->parse("main.menu.menuAdmin");
		}

		$this->xtemplate->assign("userlogoutPageId", WsIntegracaoUiConf::CONST_PAGE_USER_LOGOUT);
		$this->xtemplate->assign("partnersChartsPageId", WsIntegracaoUiConf::CONST_PAGE_PARTNERS_CHARTS);
		$this->xtemplate->parse("main.menu");
	}

	private function terminatePageShowing(){
		$this->xtemplate->parse("main.contents");
		$this->xtemplate->parse("main");
		$this->xtemplate->out("main");
	}
}
new index();
?>