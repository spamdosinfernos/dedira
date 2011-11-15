<?php
require_once __DIR__ . '/../Core.php';
require_once __DIR__ . '/../configuration/Configuration.php';
/**
 * Grava o log de solicitações
 */
class Log extends Core{

	/**
	 * Mensagem a ser gravada
	 * @var string
	 */
	protected $arrMensagem;

	/**
	 * Local onde o log será salvo
	 * @var string
	 */
	protected $diretorioDeSalvamento;

	/**
	 * Nome do arquivo de log
	 * @var string
	 */
	protected $nomeDoArquivoDeLog;

	const CONST_SEPARADOR_DE_CAMPOS = "\t";

	public function setSaveDirectory($diretorioDeSalvamento){
		$this->diretorioDeSalvamento = $diretorioDeSalvamento;
	}

	public function setLogFileName($nomeDoArquivoDeLog){
		$this->nomeDoArquivoDeLog = $nomeDoArquivoDeLog;
	}

	public function addMessage($mensagem){
		$this->arrMensagem[] = date("Y-m-d H:i:s") . self::CONST_SEPARADOR_DE_CAMPOS . $mensagem . PHP_EOL;
	}

	/**
	 * Cria um nova entrada de log
	 */
	public function save(){

		if(count($this->arrMensagem) == 0) return;

		$logFilePath = Configuration::getLogFilePath();

		if(is_file($logFilePath)){
			$arrFileContents = file($logFilePath);

			//Evita que o arquivo de log supere o número de linhas limite
			if(count($arrFileContents) > Configuration::getTamanhoDoLog()){
				unset($arrFileContents[0]);
				$filehandle = fopen($logFilePath, 'w');

				if(!is_resource($filehandle)) throw new Exception(
				Configuration::CONST_ERR_FALHA_AO_ABRIR_OU_CRIAR_ARQUIVO_TEXTO,
				Configuration::CONST_ERR_FALHA_AO_ABRIR_OU_CRIAR_ARQUIVO_COD,
				$logFilePath
				);

				$fileWriteResult = fwrite($filehandle, join("", $arrFileContents));

				if($fileWriteResult === false){
					throw new Exception(
					Configuration::CONST_ERR_FALHA_AO_ESCREVER_NO_ARQUIVO_TEXTO,
					Configuration::CONST_ERR_FALHA_AO_ESCREVER_NO_ARQUIVO_COD,
					$logFilePath
					);
				}

				fclose($filehandle);
			}

			$this->arrMensagem = array();
		}
	}
}
?>