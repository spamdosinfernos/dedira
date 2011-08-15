<?php
require_once __DIR__ . '/../CCore.php';

/**
 * Lista os arquivos presentes em um diretório
 */
class CDirectoryLister extends CCore{

	/**
	 * Caminho do diretório que se pretende listar os arquivos
	 * @var string
	 */
	protected $directoryPath;

	/**
	 * Lista de arquivos encntrados
	 * @var array : string
	 */
	protected $arrFilesAtDirectory;

	/**
	 * Instância um novo listador de diretório
	 * @param string $directoryPath
	 */
	public function __construct($directoryPath){
		
		parent::__construct();
		
		$this->setDirectoryPath($directoryPath);
		$this->arrFilesAtDirectory = array();
	}

	/**
	 * Recupera o caminho do diretório setado
	 * @return string
	 */
	public function getDirectoryPath(){
		return $this->directoryToList;
	}

	/**
	 * Seta o caminho do diretório
	 * @param string $directoryPath
	 * @throws Exception
	 */
	public function setDirectoryPath($directoryPath){

		/*
		 * Verifica se exite um separador de diretório no final do caminho
		 * se tiver tira esta barra
		 */
		if(substr($directoryPath, -1, 1) == DIRECTORY_SEPARATOR){
			$directoryPath = substr($directoryPath, 0, -1);
		}

		if(!file_exists($directoryPath)){
			throw new CException("O caminho para o diretório é inválido!");
		}

		if(!is_dir($directoryPath)){
			throw new CException("O caminho indicado não é um diretório!");
		}

		$this->directoryToList = $directoryPath;
	}

	/**
	 * Recupera a lista de arquivos
	 * @return array : string
	 */
	public function getArrFilesAtDirectory(){
		return $this->arrFilesAtDirectory;
	}

	/**
	 * Lê o conteúdo do diretório
	 */
	public function readDirectory(){
		$this->arrFilesAtDirectory = array();
		if ($dh = opendir($this->directoryToList)) {
			while (($file = readdir($dh)) !== false) {
				if(is_file($this->directoryToList . DIRECTORY_SEPARATOR . $file)){
					$this->arrFilesAtDirectory[] = $this->directoryToList . DIRECTORY_SEPARATOR . $file;
				}
			}
			closedir($dh);
		}
	}
}
?>