<?php
/**
 * Representa um arquivo no sistema.
 * Contêm as operações mais comuns referentes a arquivos como
 * mover, comprimir, enviar, apagar, etc
 */
class File {
	
	/**
	 * Md5 do arquivo quando está no seu estado original
	 *
	 * @var string
	 */
	protected $md5;
	
	/**
	 * Md5 do arquivo quando compactado
	 *
	 * @var string
	 */
	protected $md5Compressed;
	
	/**
	 * Caminho do arquivo
	 *
	 * @var string
	 */
	protected $filePath;
	
	/**
	 * Data de criação do arquivo
	 *
	 * @var string
	 */
	protected $fileCreationDate;
	
	/**
	 * Indica se o arquivo está comprimido
	 *
	 * @var boolean
	 */
	protected $compressed;
	
	/**
	 * Qualquer modificação do conteúdo deste arquivo constará aqui.
	 * Para que as mesmas sejam efetivadas é necessário usar o método "saveAs".
	 *
	 * @see setContents()
	 * @var string
	 */
	protected $temporaryContents;
	
	/**
	 * Contêm o objeto responsável por enviar o arquivo para algum lugar remoto
	 *
	 * @var InterfaceEnviador
	 */
	protected $sender;
	const COMPRESSED_EXTENSION = ".zip";
	const TENTATIVAS_DE_OPERACAO_DE_ARQUIVOS = 10;
	public function __construct($filePath = null) {
		if (! is_null ( $filePath )) {
			$this->setCaminhoDoArquivo ( $filePath );
			$this->readMetaInformation ();
		}
	}
	
	/**
	 * Salva o arquivo no lugar especificado
	 *
	 * @param string $newFilePath        	
	 * @return int - Quantidade de bytes escritos
	 */
	public function saveAs($newFilePath) {
		$bytesWrote = file_put_contents ( $newFilePath, $this->temporaryContents );
		
		if (is_bool ( $bytesWrote ))
			throw new Exception ( "Fail to save the file!" );
		
		$this->setCaminhoDoArquivo ( $newFilePath );
		
		return $bytesWrote;
	}
	
	/**
	 * Lê as informações sobre o arquivo
	 */
	public function readMetaInformation() {
		
		/*
		 * Ao apagar o md5 o mesmo será gerado apenas na próxima vez que
		 * for solicitado. Isso é feito para ganhar desempenho
		 */
		$this->md5 = "";
		
		// Verifica se a extensão é a de um arquivo compactado
		if ($this->getFileExtension () == self::COMPRESSED_EXTENSION) {
			$this->compressed = true;
		}
		
		$this->fileCreationDate = filemtime ( $this->filePath );
	}
	public function isCompressed() {
		return $this->compressed;
	}
	public function getFileSize() {
		return filesize ( $this->filePath );
	}
	public function erase() {
		if (! $this->unlink ( $this->filePath )) {
			throw new Exception ( "Fail on erasing file!" );
		}
	}
	
	/**
	 * Tenta apagar um arquivo dado seu nome
	 *
	 * @throws Exception
	 * @param string $filePath        	
	 * @return boolean
	 */
	private function unlink($filePath) {
		if (! unlink ( $filePath )) {
			return false;
		}
		
		if (file_exists ( $filePath ))
			return false;
			
			// Se chegar até aqui beleza
		return true;
	}
	public function getMd5() {
		
		/*
		 * O md5 apenas será gerado quando for solicitado.
		 * Isso é feito para ganhar desempenho pois esta
		 * operação pode demorar considerávelmente
		 */
		if ($this->md5 == "") {
			$this->md5 = md5_file ( $this->filePath );
		}
		
		return $this->md5;
	}
	public function getMd5Compactado() {
		return $this->md5Compressed;
	}
	
	/**
	 * Retorna o nome do arquivo
	 *
	 * @return string
	 */
	public function getFileName() {
		$arrNomeDoArquivo = array_reverse ( explode ( DIRECTORY_SEPARATOR, $this->filePath ) );
		return $arrNomeDoArquivo [0];
	}
	
	/**
	 * Retorna o conteúdo do arquivo
	 *
	 * @return mixed
	 */
	public function getFileContents() {
		return file_get_contents ( $this->getFilePath () );
	}
	public function setContents($contents) {
		$this->temporaryContents = $contents;
		$this->md5 = md5 ( $contents, true );
		$this->fileCreationDate = time ();
	}
	
	/**
	 * Retorna o a extensão do arquivo
	 *
	 * @return string
	 */
	public function getFileExtension() {
		$arrCaminhoDoArquivo = array_reverse ( explode ( DIRECTORY_SEPARATOR, $this->filePath ) );
		$arrNomeDoArquivo = array_reverse ( explode ( ".", $arrCaminhoDoArquivo [0] ) );
		return $arrNomeDoArquivo [0];
	}
	
	/**
	 * Retorna o nome do arquivo sem a sua extensão
	 *
	 * @return string
	 */
	public function getFileNameWithoutExtension() {
		$arrCaminhoDoArquivo = array_reverse ( explode ( DIRECTORY_SEPARATOR, $this->filePath ) );
		$arrNomeDoArquivo = explode ( ".", $arrCaminhoDoArquivo [0] );
		return $arrNomeDoArquivo [0];
	}
	
	/**
	 * Retorna o caminho do arquivo
	 *
	 * @return string
	 */
	public function getFilePath() {
		return $this->filePath;
	}
	
	/**
	 * Retorna o diretório onde o arquivo se encontra
	 *
	 * @return string
	 */
	public function getFileDirectory() {
		return dirname ( $this->filePath );
	}
	
	/**
	 * Seta o enviador caso se queira enviar o arquivo
	 *
	 * @param InterfaceEnviador $sender        	
	 */
	public function setSender(InterfaceEnviador $sender) {
		$this->sender = $sender;
	}
	
	/**
	 * Seta o caminho do arquivo
	 *
	 * @param string $filePath        	
	 * @param boolean $real
	 *        	- Specifies if its a real file or a not created one
	 * @throws Exception
	 */
	public function setCaminhoDoArquivo($filePath, bool $real = true) {
		
		if(trim($filePath) == ""){
			throw new Exception ( "Empty file name!" );
		}
		
		if ($real) {
			
			if (is_file ( $filePath ) == FALSE) {
				throw new Exception ( "There is not " . $filePath . " file, verify your permissions and try again." );
			}
			$this->filePath = realpath ( $filePath );
			return;
		}
		
		$this->filePath = $filePath;
	}
	
	/**
	 * Retorna a data de criação do arquivo
	 *
	 * @return string
	 */
	public function getFileCreationDate() {
		return $this->fileCreationDate;
	}
	
	/**
	 * Envia o arquivo pelo meio especificado
	 *
	 * @param string $filePathDestiny
	 *        	: Diretório de destino do arquivo no local remoto
	 * @param callBackFunction
	 *        	: Ponteiro para a função de callback
	 * @see setSender()
	 */
	public function send($filePathDestiny, $callBackFunction = null) {
		
		// Tira o separador de arquivos do fim do caminho
		if (substr ( $filePathDestiny, - 1, 1 ) == DIRECTORY_SEPARATOR) {
			$filePathDestiny = substr ( $filePathDestiny, 0, - 1 );
		}
		
		if ($this->getFileName () == "")
			throw new Exception ( "Empty file name!" );
		
		if (! $this->sender->connect ())
			throw new Exception ( "Fail on stablish connection to send the file!" );
		
		$status = $this->sender->upload ( $this->getFilePath (), $filePathDestiny, $this->getFileName (), $callBackFunction );
		
		$this->sender->close ();
		
		return $status;
	}
	public function compress() {
		$caminhoDoPacote = $this->filePath . self::COMPRESSED_EXTENSION;
		$criouPacote = false;
		$fechouPacote = false;
		;
		
		// Comprime o pacote
		$quantidadeMaxDeTentativas = self::TENTATIVAS_DE_OPERACAO_DE_ARQUIVOS;
		
		$zip = new ZipArchive ();
		
		do {
			$quantidadeMaxDeTentativas --;
			
			$criouPacote = $zip->open ( $caminhoDoPacote, ZipArchive::OVERWRITE );
			
			if ($criouPacote) {
				$zip->addFile ( $this->filePath, $this->getFileName () );
				$fechouPacote = $zip->close ();
			}
			
			if ($quantidadeMaxDeTentativas == 0) {
				@$this->unlink ( $caminhoDoPacote );
				throw new Exception ( "Fail to compact" );
			}
		} while ( ! $fechouPacote );
		
		// Apaga o arquivo original
		$quantidadeMaxDeTentativas = self::TENTATIVAS_DE_OPERACAO_DE_ARQUIVOS;
		do {
			$quantidadeMaxDeTentativas --;
			
			if ($quantidadeMaxDeTentativas == 0) {
				$this->unlink ( $caminhoDoPacote );
				throw new Exception ( "Fail to compact, i cant erase the original file " . $this->filePath );
			}
		} while ( ! $this->unlink ( $this->filePath ) );
		
		$this->compressed = true;
		$this->filePath = $caminhoDoPacote;
		$this->readMetaInformation ();
	}
	public function uncompress() {
		$arrNomeDoArquivo = explode ( ".", $this->filePath );
		if ("." . $arrNomeDoArquivo [count ( $arrNomeDoArquivo ) - 1] != self::COMPRESSED_EXTENSION) {
			throw new Exception ( "The file " . $this->filePath . " is not a compressed file!" );
		}
		
		$zip = new ZipArchive ();
		try {
			if (! ($zip->open ( $this->filePath ) === TRUE))
				throw new Exception ( "Fail to open compressed file " . $this->filePath . " verify your permissions" );
			$qtdeDeArquivosNoArquivoCompactado = $zip->numFiles;
			if ($qtdeDeArquivosNoArquivoCompactado > 1) {
				
				$diretorioParaDescompactacao = str_replace ( $this->getFileName (), "", $this->filePath );
				$zip->extractTo ( $diretorioParaDescompactacao );
			} else {
				
				$nomeDoArquivoDescompactado = $zip->getNameIndex ( 0 );
				$filePathDescompactado = str_replace ( $this->getFileName (), "", $this->filePath );
				
				$zip->extractTo ( $filePathDescompactado );
			}
		} catch ( Exception $e ) {
			throw new Exception ( "Fail on uncompress the file " . $this->filePath . ":" . $e->getMessage () );
		}
		
		$zip->close ();
		
		if ($this->unlink ( $this->filePath ) === FALSE) {
			throw new Exception ( "Impossible to erase file the original file after the compression:" . $this->filePath );
		}
		
		if ($qtdeDeArquivosNoArquivoCompactado > 1) {
			unset ( $this );
		} else {
			
			$this->compressed = false;
			$this->filePath = $filePathDescompactado . $nomeDoArquivoDescompactado;
			$this->md5 = md5_file ( $this->filePath );
		}
	}
	public function rename($novoNome) {
		$arrNovoCaminhoDoArquivo = explode ( DIRECTORY_SEPARATOR, $this->filePath );
		$arrNovoCaminhoDoArquivo [count ( $arrNovoCaminhoDoArquivo ) - 1] = $novoNome;
		$novoCaminho = join ( DIRECTORY_SEPARATOR, $arrNovoCaminhoDoArquivo );
		
		try {
			$this->renamePersonalizado ( $novoCaminho );
		} catch ( Exception $e ) {
			throw new Exception ( "Impossíble to rename " . $this->getFileName () . " to " . $novoNome . ". Verify your permissions " . $e->getMessage () );
		}
		
		$this->filePath = $novoCaminho;
	}
	private function renamePersonalizado($caminhoDeDestino) {
		do {
			copy ( $this->filePath, $caminhoDeDestino );
		} while ( ! file_exists ( $caminhoDeDestino ) );
		
		do {
			$this->unlink ( $this->filePath );
		} while ( file_exists ( $this->filePath ) );
		
		// Se a cópia foi feita com sucesso e o arquivo existe no destino vai em frente!
		if (file_exists ( $this->filePath )) {
			throw new Exception ( "Impossible to erase the original file: " . $this->filePath );
		}
		
		if (! file_exists ( $caminhoDeDestino )) {
			throw new Exception ( "Fail to create the file: " . $caminhoDeDestino );
		}
		
		$this->filePath = $caminhoDeDestino;
	}
	public function move($caminhoDoDiretorio) {
		if (substr ( $caminhoDoDiretorio, - 1, 1 ) == DIRECTORY_SEPARATOR) {
			$caminhoDoDiretorio = substr ( $caminhoDoDiretorio, 0, - 1 );
		}
		
		if (! file_exists ( $caminhoDoDiretorio )) {
			throw new Exception ( "Directory: " . $caminhoDoDiretorio . " not valid!" );
		}
		
		if (! is_dir ( $caminhoDoDiretorio )) {
			throw new Exception ( "The path " . $caminhoDoDiretorio . " is not a directory!" );
		}
		
		$caminhoDeDestino = $caminhoDoDiretorio . DIRECTORY_SEPARATOR . $this->getFileName ();
		
		do {
			copy ( $this->filePath, $caminhoDeDestino );
		} while ( ! file_exists ( $caminhoDeDestino ) );
		
		do {
			$this->unlink ( $this->filePath );
		} while ( file_exists ( $this->filePath ) );
		
		if (file_exists ( $this->filePath ))
			throw new Exception ( "Fail on moving file, the original file was not erased: " . $this->filePath );
		
		if (! file_exists ( $caminhoDeDestino ))
			throw new Exception ( "Fail on moving file, the destiny file was not created: " . $caminhoDeDestino );
		
		$this->filePath = $caminhoDeDestino;
	}
	
	/**
	 * Gera um caminho relativo para o arquivo dado um caminho base de diretório
	 *
	 * @param string $directoryBasePath        	
	 */
	public function getRelativePath($directoryBasePath) {
		$arrBasePath = explode ( DIRECTORY_SEPARATOR, $directoryBasePath );
		$arrPathToConvert = explode ( DIRECTORY_SEPARATOR, $this->getFilePath () );
		
		// Apaga os diretórios pais em comum
		foreach ( $arrBasePath as $depth => $dir ) {
			
			if (isset ( $arrPathToConvert [$depth] )) {
				if ($dir === $arrPathToConvert [$depth]) {
					unset ( $arrPathToConvert [$depth] );
					unset ( $arrBasePath [$depth] );
				} else {
					break;
				}
			}
		}
		
		// Gera o caminho relativo
		for($i = 0; $i < count ( $arrBasePath ); $i ++) {
			array_unshift ( $arrPathToConvert, '..' );
		}
		
		// Retorna o caminho final
		return implode ( DIRECTORY_SEPARATOR, $arrPathToConvert );
		;
	}
	public function getBase64Encode() {
		if ($fp = fopen ( $this->filePath, "rb", 0 )) {
			$content = fread ( $fp, filesize ( $this->filePath ) );
			fclose ( $fp );
			// base64 encode the binary data, then break it
			// into chunks according to RFC 2045 semantics
			return chunk_split ( base64_encode ( $content ) );
		}
	}
}
?>