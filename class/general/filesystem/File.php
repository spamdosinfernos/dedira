<?php

/**
 * Representa um arquivo no sistema.
 * Contêm as operações mais comuns referentes a arquivos como
 * mover, comprimir, enviar, apagar, etc
 */
class File{

	/**
	 * Md5 do arquivo quando está no seu estado original
	 * @var string
	 */
	protected $md5;

	/**
	 * Md5 do arquivo quando compactado
	 * @var string
	 */
	protected $md5Compressed;

	/**
	 * Caminho do arquivo
	 * @var string
	 */
	protected $filePath;

	/**
	 * Data de criação do arquivo
	 * @var string
	 */
	protected $fileCreationDate;

	/**
	 * Indica se o arquivo está comprimido
	 * @var boolean
	 */
	protected $compressed;

	/**
	 * Contêm o objeto responsável por enviar o arquivo para algum lugar remoto
	 * @var InterfaceEnviador
	 */
	protected $sender;

	const CONST_COMPRESSED_EXTENSION = ".zip";

	const CONST_TENTATIVAS_DE_OPERACAO_DE_ARQUIVOS = 10;

	public function __construct($filePath = null){
		if(!is_null($filePath)){
			$this->setCaminhoDoArquivo($filePath);
			$this->readMetaInformation();
		}
	}

	/**
	 * Lê as informações sobre o arquivo
	 */
	public function readMetaInformation(){

		/*
		 * Ao apagar o md5 o mesmo será gerado apenas na próxima vez que 
		 * for solicitado. Isso é feito para ganhar desempenho
		 */
		$this->md5 = "";

		//Verifica se a extensão é a de um arquivo compactado
		if($this->getFileExtension() == self::CONST_COMPRESSED_EXTENSION){
			$this->compressed = true;
		}

		$this->fileCreationDate = filemtime($this->filePath);
	}

	public function isCompressed(){
		return $this->compressed;
	}

	public function getFileSize(){
		return filesize($this->filePath);
	}

	public function erase(){
		if(!$this->unlinkPersonalizado($this->filePath)){
			throw new Exception("Impossível apagar o arquivo " . $this->filePath);
		}
	}

	/**
	 * Foi necessário implementar este procedimento pois existe
	 * um problema na plataforma windows (só para variar...) que
	 * não permite a deleção de arquivos quando solicitado
	 * @throws Exception
	 * @param string $filePath
	 * @return boolean
	 */
	private function unlinkPersonalizado($filePath){

		if(!unlink($filePath)){
			return false;
		}

		if(file_exists($filePath)) return false;

		//Se chegar até aqui beleza
		return true;
	}

	public function getMd5(){

		/*
		 * O md5 apenas será gerado quando for solicitado. 
		 * Isso é feito para ganhar desempenho pois esta
		 * operação pode demorar considerávelmente
		 */
		if($this->md5 == "" ){
			$this->md5 = md5_file($filePath);
		}

		return $this->md5;
	}

	public function getMd5Compactado(){
		return $this->md5Compressed;
	}

	public function getFileName(){
		$arrNomeDoArquivo = array_reverse(explode(DIRECTORY_SEPARATOR, $this->filePath));
		return $arrNomeDoArquivo[0];
	}

	public function getFileContents(){
		return file_get_contents($this->getFilePath());
	}

	public function getFileExtension(){
		$arrCaminhoDoArquivo = array_reverse(explode(DIRECTORY_SEPARATOR, $this->filePath));
		$arrNomeDoArquivo = array_reverse(explode(".", $arrCaminhoDoArquivo[0]));
		return $arrNomeDoArquivo[0];
	}

	public function getFilePath(){
		return $this->filePath;
	}

	public function getFileDirectory(){
		return dirname($this->filePath);
	}

	/**
	 * Seta o enviador caso se queira enviar o arquivo
	 * @param InterfaceEnviador $sender
	 */
	public function setSender(InterfaceEnviador $sender){
		$this->sender = $sender;
	}

	public function setCaminhoDoArquivo($filePath){
		if(is_file($filePath) == FALSE){
			throw new Exception("O arquivo " . $filePath . " não existe ou é um atalho apontando para arquivo inválido, certifique-se também que as permissões de escrita e leitura estejam liberadas.");
		}
		$this->filePath = realpath($filePath);
	}

	public function getFileCreationDate(){
		return $this->fileCreationDate;
	}

	/**
	 * Envia o arquivo pelo meio especificado
	 * @param string $filePathDestiny : Diretório de destino do arquivo no local remoto
	 * @param pointer $callBackFunction : Ponteiro para a função de callback
	 * @see setSender()
	 * @see IFileSender
	 */
	public function send($filePathDestiny, $callBackFunction = null){

		//Tira o separador de arquivos do fim do caminho
		if(substr($filePathDestiny, -1, 1) == DIRECTORY_SEPARATOR){
			$filePathDestiny = substr($filePathDestiny, 0, -1);
		}

		if($this->getFileName() == "") throw new Exception("O nome do arquivo a ser enviado está vazio!");

		if(!$this->sender->connect()) throw new Exception("Falha em estabelecer a conexão para enviar o arquivo");

		$status = $this->sender->upload($this->getFilePath(), $filePathDestiny, $this->getFileName(), $callBackFunction);

		$this->sender->close();

		return $status;
	}

	public function compress(){

		$caminhoDoPacote = $this->filePath . self::CONST_COMPRESSED_EXTENSION;
		$criouPacote = false;
		$fechouPacote = false;;

		//Comprime o pacote
		$quantidadeMaxDeTentativas = self::CONST_TENTATIVAS_DE_OPERACAO_DE_ARQUIVOS;

		$zip = new ZipArchive();

		do{
			$quantidadeMaxDeTentativas--;

			$criouPacote = $zip->open($caminhoDoPacote, ZIPARCHIVE::OVERWRITE);

			if($criouPacote){
				$zip->addFile($this->filePath,$this->getFileName());
				$fechouPacote = $zip->close();
			}

			if($quantidadeMaxDeTentativas == 0){
				@$this->unlinkPersonalizado($caminhoDoPacote);
				throw new Exception("Falha ao compactar arquivo: " . join("\r\n",$arrOutPut));
			}
		}while(!$fechouPacote);

		//Apaga o arquivo original
		$quantidadeMaxDeTentativas = self::CONST_TENTATIVAS_DE_OPERACAO_DE_ARQUIVOS;
		do{
			$quantidadeMaxDeTentativas--;

			if($quantidadeMaxDeTentativas == 0){
				$this->unlinkPersonalizado($caminhoDoPacote);
				throw new Exception("Falha ao compactar arquivo, não foi possível apagar o arquivo de origem: " . $this->filePath);
			}
		} while(!$this->unlinkPersonalizado($this->filePath));

		$this->compressed = true;
		$this->filePath = $caminhoDoPacote;
		$this->md5Compressed = md5_file($this->filePath);
	}

	public function uncompress(){
		$arrNomeDoArquivo = explode(".", $this->filePath);
		if("." . $arrNomeDoArquivo[count($arrNomeDoArquivo) -1] != self::CONST_COMPRESSED_EXTENSION){
			throw new Exception("O arquivo " . $this->filePath . " não é um arquivo compactado. Não é possí­vel descompactá-lo");
		}

		$zip = new ZipArchive();
		try{
			if (!($zip->open($this->filePath) === TRUE)) throw new Exception("Erro ao abrir o arquivo compactado " . $this->filePath . " verifique as permissões de leitura no seu sistema.");
			$qtdeDeArquivosNoArquivoCompactado = $zip->numFiles;
			if($qtdeDeArquivosNoArquivoCompactado > 1){

				$diretorioParaDescompactacao = str_replace($this->getFileName(), "", $this->filePath);
				$zip->extractTo($diretorioParaDescompactacao);
			}else{

				$nomeDoArquivoDescompactado = $zip->getNameIndex(0);
				$filePathDescompactado = str_replace($this->getFileName(), "", $this->filePath);

				$zip->extractTo($filePathDescompactado);
			}
		}catch (Exception $e){
			throw new Exception("Erro ao descompactar o arquivo " . $this->filePath . ":" . $e->getMessage()) . ".";
		}

		$zip->close();

		if($this->unlinkPersonalizado($this->filePath) === FALSE){
			throw new Exception("Não foi possí­vel apagar o arquivo original após a descompactação:" . $this->filePath);
		}

		if($qtdeDeArquivosNoArquivoCompactado > 1){
			unset($this);
		}else{

			$this->compressed = false;
			$this->filePath = $filePathDescompactado . $nomeDoArquivoDescompactado;
			$this->md5 = md5_file($this->filePath);
		}
	}

	public function rename($novoNome){

		$arrNovoCaminhoDoArquivo = explode(DIRECTORY_SEPARATOR, $this->filePath);
		$arrNovoCaminhoDoArquivo[count($arrNovoCaminhoDoArquivo)-1] = $novoNome;
		$novoCaminho = join(DIRECTORY_SEPARATOR, $arrNovoCaminhoDoArquivo);

		try{
			$this->renamePersonalizado($novoCaminho);
		}catch (Exception $e){
			throw new Exception("Não foi possível renomear o arquivo " . $this->getFileName() . " para " . $novoNome . ". Verifique as permissões de escrita no diretório: " . $e->getMessage());
		}

		$this->filePath = $novoCaminho;
	}

	private function renamePersonalizado($caminhoDeDestino){

		$conteudoDoArquivo = "";

		do{
			copy($this->filePath, $caminhoDeDestino);
		} while(!file_exists($caminhoDeDestino));


		do{
			$this->unlinkPersonalizado($this->filePath);
		} while(file_exists($this->filePath));

		//Se a cópia foi feita com sucesso e o arquivo existe no destino vai em frente!
		if(file_exists($this->filePath)){
			throw new Exception("Erro, não foi possível apagar o arquivo de origem: " . $this->filePath);
		}

		if(!file_exists($caminhoDeDestino)){
			throw new Exception("Erro, não foi possível criar o arquivo de destino: " . $caminhoDeDestino);
		}

		$this->filePath = $caminhoDeDestino;
	}

	public function move($caminhoDoDiretorio){

		if(substr($caminhoDoDiretorio, -1, 1) == DIRECTORY_SEPARATOR){
			$caminhoDoDiretorio = substr($caminhoDoDiretorio, 0, -1);
		}

		if(!file_exists($caminhoDoDiretorio)){
			throw new Exception("O caminho para o diretório " . $caminhoDoDiretorio . " é inválido!");
		}

		if(!is_dir($caminhoDoDiretorio)){
			throw new Exception("O caminho indicado " . $caminhoDoDiretorio . " não é um diretório!");
		}

		$caminhoDeDestino = $caminhoDoDiretorio . DIRECTORY_SEPARATOR . $this->getFileName();

		do{
			copy($this->filePath, $caminhoDeDestino);
		} while(!file_exists($caminhoDeDestino));

		do{
			$this->unlinkPersonalizado($this->filePath);
		} while(file_exists($this->filePath));

		if(file_exists($this->filePath)) throw new Exception("Falha ao mover arquivo, o arquivo de origem não foi apagado: " . $this->filePath);

		if(!file_exists($caminhoDeDestino)) throw new Exception("Falha ao mover arquivo, o arquivo de destino não foi criado: " . $caminhoDeDestino);

		$this->filePath = $caminhoDeDestino;
	}

	/**
	 * Gera um caminho relativo para o arquivo dado um caminho base de diretório
	 * @param string $directoryBasePath
	 */
	public function getRelativePath($directoryBasePath){

		$arrBasePath = explode(DIRECTORY_SEPARATOR, $directoryBasePath);
		$arrPathToConvert = explode(DIRECTORY_SEPARATOR, $this->getFilePath());

		//Apaga os diretórios pais em comum
		foreach($arrBasePath as $depth => $dir){

			if(isset($arrPathToConvert[$depth])){
				if($dir === $arrPathToConvert[$depth]){
					unset($arrPathToConvert[$depth]);
					unset($arrBasePath[$depth]);
				}else{
					break;
				}
			}
		}

		//Gera o caminho relativo
		for($i=0; $i<count($arrBasePath); $i++){
			array_unshift($arrPathToConvert,'..');
		}

		//Retorna o caminho final
		return implode(DIRECTORY_SEPARATOR, $arrPathToConvert);;
	}
}
?>