<?php
require_once __DIR__ . '/../class/general/filesystem/DirectoryLister.php';
require_once __DIR__ . '/../class/general/filesystem/File.php';

class Empacotador {

	private $diretorioDeOrigem;

	private $diretorioDeDestino;

	private $listadorDeDiretorios;

	private $regexNaoOfuscavel;

	private $regexIgnorar;

	private $pacoteZip;

	private $nomeDoProjeto;

	const SUFIXO_PARA_O_NOME_DO_ARQUIVO_DE_PACOTE = "update.zip";

	public function processarTudo(){

		$this->abrirPacote();

		//Listando os diretórios de primeiro nível
		$this->listadorDeDiretorios = new DirectoryLister($this->diretorioDeOrigem);
		$this->listadorDeDiretorios->readDirectory();
		$arrDirDePrimeiroNivel = $this->listadorDeDiretorios->getArrDirectoriesAtDirectory();

		//Copia os arquivos requeridos no projeto varrendo os diretórios de primeiro nível
		foreach ($arrDirDePrimeiroNivel as $dirDePrimeiroNivel) {
			$this->processar($dirDePrimeiroNivel);
		}

		$this->fecharPacote();
	}

	public function setRegexIgnorar($regexIgnorar){
		$this->regexIgnorar = $regexIgnorar;
	}

	public function setRegexNaoOfuscavel($regexNaoOfuscavel){
		$this->regexNaoOfuscavel = $regexNaoOfuscavel;
	}

	public function setDiretorioDeOrigem($diretorioDeOrigem){
		$this->diretorioDeOrigem = $this->tratarCaminhoDeDiretorio($diretorioDeOrigem);
	}

	private function tratarCaminhoDeDiretorio($caminho){

		if(substr($caminho,-1,1) == DIRECTORY_SEPARATOR){
			$caminho = substr($caminho,0,strlen($caminho) - 1);
		}

		return $caminho;
	}

	public function setDiretorioDeDestino($diretorioDeDestino){
		$this->diretorioDeDestino = $this->tratarCaminhoDeDiretorio($diretorioDeDestino);
	}

	private function fecharPacote(){
		$this->pacoteZip->close();
	}

	private function abrirPacote(){
		$this->pacoteZip = new ZipArchive();
		$this->pacoteZip->open($this->diretorioDeDestino . DIRECTORY_SEPARATOR . self::SUFIXO_PARA_O_NOME_DO_ARQUIVO_DE_PACOTE, ZIPARCHIVE::OVERWRITE);
	}

	private function getArrDepencias($arquivo){

		$arquivo = new File($arquivo);

		$arrResult = array();

		if($arquivo->getFileExtension() != "php") return $arrResult;

		//Recupera o código fonte
		$arq = $arquivo->getFileContents();

		//Filtra os requires
		preg_match_all('/[^(((<?|<?php)\n|)require_once)](__DIR__ .*(\n*))/i',$arq, $matches);

		//Pega os requires
		$arrIncluded = $matches[1];

		foreach ($arrIncluded as $included) {

			try{
				$includedFilePath = eval("return " . str_replace("__DIR__", "'" . dirname($arquivo->getFilePath()) . "'", $included));
				$includedFile = new File($includedFilePath);
				$arrResult[] = $includedFile->getFilePath();
				$arrResult = array_merge($arrResult, $this->getArrDepencias($includedFile->getFilePath()));
			}catch (Exception $e){
				$this->arrNotSolvedFilesPaths[$arquivo][] = $included;
			}
		}

		return $arrResult;

	}

	private function processar($diretorioDeOrigem = null){

		$this->listadorDeDiretorios = new DirectoryLister($diretorioDeOrigem);
		$this->listadorDeDiretorios->readDirectory();

		//Lista os arquivos e subdiretórios do diretório atual
		$arrArquivos = $this->listadorDeDiretorios->getArrFilesAtDirectory();
		$arrDiretorios = $this->listadorDeDiretorios->getArrDirectoriesAtDirectory();

		//Processando os arquivos
		foreach ($arrArquivos as $arquivo) {

			if(!stripos($arquivo, $this->nomeDoProjeto)) continue;
			if($this->ignorarObjeto($arquivo)) continue;

			$arrEmpacotar[] = $arquivo;
			$arrEmpacotar = array_merge($arrEmpacotar, $this->getArrDepencias($arquivo));

			foreach ($arrEmpacotar as $empacotar) {
				$caminhoDeSaida = $this->gerarCaminhoDeSaida($arquivo);
				$this->empacotar($arquivo,$caminhoDeSaida);
			}
		}

		//Processando os subdiretórios
		foreach ($arrDiretorios as $diretorio) {
			if($this->ignorarObjeto($diretorio)) continue;
			$this->processar($diretorio);
		}
	}

	private function ignorarObjeto($nomeDoObjeto){
		preg_match($this->regexIgnorar, $nomeDoObjeto, $arrResultados);
		return count($arrResultados)>0;
	}

	private function gerarCaminhoDeSaida($arquivoDeEntrada){
		$caminhoFinal = str_replace($this->diretorioDeOrigem, $this->diretorioDeDestino, $arquivoDeEntrada);
		return $caminhoFinal;
	}

	private function empacotar($arquivoDeEntrada, $arquivoDeSaida){

		//Gera o caminho para dentro do pacote
		$arquivoDeSaida = str_ireplace($this->diretorioDeDestino . DIRECTORY_SEPARATOR ,"",$arquivoDeSaida);

		if($this->naoOfuscarArquivo($arquivoDeEntrada)){
			$this->pacoteZip->addFromString($arquivoDeSaida,file_get_contents($arquivoDeEntrada));
			return;
		}

		$out = $this->gerarConteudoOfuscado($arquivoDeEntrada);

		//Salva o arquivo
		$this->pacoteZip->addFromString($arquivoDeSaida,$out);
	}

	private function gerarConteudoOfuscado($arquivoDeEntrada){

		$data = "ob_end_clean();?>";
		$data .= php_strip_whitespace($arquivoDeEntrada);

		//Comprime a informação
		$data = gzcompress($data,9);

		//Codifica o conteúdo em base64
		$data = base64_encode($data);

		//Gera o código de saída
		return '<?ob_start();$a=\''.$data.'\';eval(gzuncompress(base64_decode($a)));$v=ob_get_contents();ob_end_clean();?>';
	}

	private function naoOfuscarArquivo($arquivo){
		preg_match($this->regexNaoOfuscavel, $arquivo, $arrResultados);
		return count($arrResultados)>0;
	}

	private function ofuscarArquivo($arquivo){
		preg_match($this->regexNaoOfuscavel, $arquivo, $arrResultados);
		return count($arrResultados)>0;
	}

	private function addAoPacote($objeto){
		$this->arrObjetoASerEmpacotado[] = $objeto;
	}

	public function setNomeDoProjeto($nomeDoProjeto){
		$this->nomeDoProjeto = $nomeDoProjeto;
	}
}

$ob = new Empacotador();
$ob->setDiretorioDeOrigem(realpath("/home/andre/workspace/repositorio/wwwint"));
$ob->setDiretorioDeDestino(realpath("./compiled/"));
$ob->setRegexIgnorar("/(.*php-gtk2|.*ftptest|.*log$|.*Empacotador\.php$|.*ul$|\.meta$|tools|doc)/");
$ob->setRegexNaoOfuscavel("/.*(\.html$|\.bat$|\.css$|\.glade$|\.png$|ul$|\.xml$|.*php-gtk2|.*resourses)/");
$ob->setNomeDoProjeto("ws_integracao_ui");
$ob->processarTudo();
?>