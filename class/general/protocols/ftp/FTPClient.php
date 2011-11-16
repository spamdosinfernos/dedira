<?php
require_once 'InterfaceEnviador.php';

class FTPClient implements InterfaceEnviador{

	private $ftpAddress;
	private $connectionId;
	private $connected;
	private $login;
	private $password;
	private $testingConnection;

	public function getFtpAddres(){
		return $this->ftpAddress;
	}

	public function setFtpAddres($ftpAddress){
		$this->ftpAddress = $ftpAddress;
	}

	public function getConnectionId(){
		return $this->connectionId;
	}

	public function isConnected(){
		return $this->connected;
	}

	public function getLogin(){
		return $this->login;
	}

	public function setLogin($login){
		$this->login = $login;
	}

	public function getPassword(){
		return $this->password;
	}

	public function setPassword($password){
		$this->password = $password;
	}

	public function connect(){
		$this->connectionId = ftp_connect($this->ftpAddress);
		if($this->connectionId === false) return false;
		$loginResult = ftp_login($this->connectionId, $this->login, $this->password);
		if ((!$this->connectionId) || (!$loginResult)){
			$this->connected = false;
			return false;
		}

		ftp_pasv($this->connectionId, true);
		$this->connected = true;
		return true;
	}

	private function isDiretorioFtpCriado($caminhoParaODiretorio){
		$resultado = ftp_size($this->connectionId, $caminhoParaODiretorio);
		if($resultado == -1){
			$resultado = ftp_nlist($this->connectionId, $caminhoParaODiretorio);
		}else{
			return false;
		}

		if($resultado === FALSE){
			return false;
		}

		return true;
	}

	private function criarSubFilePathDestiny($directoryPathDestiny, $nameDestiny){

		$nomeDoArquivoSemExtensao = substr($nameDestiny, 0, strlen($nameDestiny) - 4);
		$arrLetrasDoCaminho = str_split($nomeDoArquivoSemExtensao,1);

		$status = true;

		foreach ($arrLetrasDoCaminho as $key => $letra) {
			preg_match("/[a-z0-9]*/i", $letra, $matches);
			if($matches[0] == ""){
				$letra = "dot";
			}

			$directoryPathDestiny .= Configuracao::getDirectorySeparatorRemote() . $letra;
			@ftp_mkdir($this->connectionId, $directoryPathDestiny);
				
			$status = $this->isDiretorioFtpCriado($directoryPathDestiny);
				
			if(!$status) break;
		}

		if($status != "" && $status != false) return $directoryPathDestiny;
		throw new Exception("Falha ao determinar o diretório de destino do arquivo: " . $nameDestiny);
	}

	public function download($remoteFileToDownload, $localFileToSave, $callBackFunction = null){
		if(!is_null($callBackFunction)){
			if(!is_callable($callBackFunction)) throw new Exception("Função de callback inválida");
		}

		if(!$this->isConnected()){
			$this->connect();
			if(!$this->isConnected()){
				throw new Exception("Não foi possí­vel conectar ao ftp " . $this->ftpAddress);
			}

		}

		@$ret = ftp_nb_get($this->connectionId, $localFileToSave, $remoteFileToDownload, FTP_BINARY, FTP_AUTORESUME);
		while ($ret == FTP_MOREDATA) {
			if(!is_null($callBackFunction)){
				call_user_func($callBackFunction, "Baixando...");
			}

			$ret = ftp_nb_continue($this->connectionId);
		}

		if ($ret != FTP_FINISHED) return false;
		return true;
	}

	public function upload($filePathOrign, $directoryPathDestiny, $nameDestiny, $callBackFunction = null){
		if(!is_null($callBackFunction)){
			if(!is_callable($callBackFunction)) throw new Exception("Função de callback inválida");
		}

		if(!$this->isConnected()){
			$this->connect();
			if(!$this->isConnected()){
				throw new Exception("Não foi possí­vel conectar ao ftp " . $this->ftpAddress);
			}

		}

		if(!$this->testingConnection){
			$filePathDestiny = $this->criarSubFilePathDestiny($directoryPathDestiny,$nameDestiny);
			if(!$filePathDestiny){
				throw new Exception("Não foi possí­vel criar o diretório de destino para o arquivo: " . $nameDestiny);
			}

			$filePathDestiny .= Configuracao::getDirectorySeparatorRemote() . $nameDestiny;
		}else{
			$filePathDestiny = $directoryPathDestiny . Configuracao::getDirectorySeparatorRemote() . $nameDestiny;
		}

		$fileHandle = fopen($filePathOrign, 'r');
		$tamanhoDoArquivo = fstat($fileHandle);
		$tamanhoDoArquivo = $tamanhoDoArquivo['size'];

		$ret = ftp_nb_fput($this->connectionId, $filePathDestiny, $fileHandle, FTP_BINARY);
		while ($ret == FTP_MOREDATA) {
			if(!is_null($callBackFunction)){
				$porcentagem = ftell($fileHandle) / $tamanhoDoArquivo;

				//Se a função de callback retornar false, é para cancelar o envio
				if(call_user_func($callBackFunction, $porcentagem) === false){
					throw new Exception("Envio do arquivo $filePathOrign foi cancelado!");
				}
			}

			$ret = ftp_nb_continue($this->connectionId);
		}

		fclose($fileHandle);

		if ($ret != FTP_FINISHED) return false;

		return true;
	}

	public function close(){
		ftp_close($this->connectionId);
	}

	public function selftest(){
		$nomeDoArquivoDeTeste = "ftptest";
		$conteudoDoArquivoDeTeste = "teste de conteúdo para envio";
		$this->testingConnection = true;
		if(!$this->connect()) return false;
		try {
			$ponteiro = fopen("." . DIRECTORY_SEPARATOR . $nomeDoArquivoDeTeste, "w");
			fwrite($ponteiro, $conteudoDoArquivoDeTeste, strlen($conteudoDoArquivoDeTeste));
			fclose($ponteiro);
		}

		catch (Exception $e){
			throw new Exception("Não foi possí­vel criar arquivo de teste do ftp: " . $e->getMessage());
		}

		if(is_file("." . DIRECTORY_SEPARATOR . $nomeDoArquivoDeTeste) == FALSE) throw new Exception("Não foi possí­vel criar arquivo de teste do ftp: " . $e->getMessage());
		$retorno = $this->upload("." . DIRECTORY_SEPARATOR . $nomeDoArquivoDeTeste, Configuracao::getLocalRemotoParaEnvioDosArquivos(), $nomeDoArquivoDeTeste);
		if($retorno){
			$this->testingConnection = false;
			$this->close();
			return true;
		}

		$this->testingConnection = false;
		$this->close();
		return false;
	}

}

?>