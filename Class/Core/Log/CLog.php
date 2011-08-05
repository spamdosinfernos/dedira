<?php
require_once 'Class/Core/CCore.php';
require_once 'Class/Core/Configuracao/CConfiguracao.php';

/**
 * Grava o log de solicitações
 */
class CLog extends CCore{

	/**
	 * Guarda a data de geração do log
	 * esta data é gerada quando o objeto
	 * é construído
	 * @var datetime
	 */
	private $data;

	/**
	 * Mensagem a ser gravada
	 * @var string
	 */
	private $mensagem;

	/**
	 * Separador de coluna para os dados
	 * @var string
	 */
	const CONST_SEPARADOR_DE_CAMPOS = "\t";

	/**
	 * Cria um nova entra de log
	 * @param string $mensagem
	 */
	public function __construct($mensagem){
		$this->data = date(CConfiguracao::CONST_LOG_FORMATO_DA_DATA);
		$this->mensagem = $mensagem;
		$this->gravarLog();
	}

	/**
	 * Grava o log no local especificado
	 */
	private function gravarLog(){
		$arquivoDeLog = Configuracao::getCaminhoDoLog();

		if(is_file($arquivoDeLog)){
			$arrArquivo = file($arquivoDeLog);

			//Evita que o arquivo de log supere o número de linhas limite
			if(count($arrArquivo) > Configuracao::getTamanhoDoLog()){
				unset($arrArquivo[0]);
				$filehandle = fopen($arquivoDeLog, 'w');

				if(!is_resource($filehandle)) throw new CException(
				CConfiguracao::CONST_ERR_FALHA_AO_ABRIR_OU_CRIAR_ARQUIVO_TEXTO,
				CConfiguracao::CONST_ERR_FALHA_AO_ABRIR_OU_CRIAR_ARQUIVO_COD,
				$arquivoDeLog
				);

				$resultadoDaEscrita = fwrite($filehandle, join("", $arrArquivo));
				if($resultadoDaEscrita === false){
					throw new CException(
					CConfiguracao::CONST_ERR_FALHA_AO_ESCREVER_NO_ARQUIVO_TEXTO,
					CConfiguracao::CONST_ERR_FALHA_AO_ESCREVER_NO_ARQUIVO_COD,
					$arquivoDeLog
					);
				}

				fclose($filehandle);
			}
		}

		$filehandle = fopen($arquivoDeLog, 'a');
		if(!is_resource($filehandle)){
			throw new CException(
			CConfiguracao::CONST_ERR_FALHA_AO_ABRIR_OU_CRIAR_ARQUIVO_TEXTO,
			CConfiguracao::CONST_ERR_FALHA_AO_ABRIR_OU_CRIAR_ARQUIVO_COD,
			$arquivoDeLog
			);
		}

		$resultadoDaEscrita = fwrite($filehandle, $this->data . self::CONST_SEPARADOR_DE_CAMPOS . $this->mensagem . "\n");
		
		if($resultadoDaEscrita === false) throw new CException(
		CConfiguracao::CONST_ERR_FALHA_AO_ESCREVER_NO_ARQUIVO_TEXTO,
		CConfiguracao::CONST_ERR_FALHA_AO_ESCREVER_NO_ARQUIVO_COD,
		$arquivoDeLog
		);

		fclose($filehandle);
	}
}
?>