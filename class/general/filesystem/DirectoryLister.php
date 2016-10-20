<?php
class DirectoryLister{
	
	protected $directoryPath;
	protected $arrFilesAtDirectory;
	protected $arrDirectoriesAtDirectory;
	
	public function __construct($directoryPath){
		$this->setDirectoryPath($directoryPath);
		$this->arrFilesAtDirectory = array();
	}

	public function getDirectoryPath(){
		return $this->directoryToList;
	}

	public function setDirectoryPath($directoryPath){
		
		if(substr($directoryPath, -1, 1) == DIRECTORY_SEPARATOR){
			$directoryPath = substr($directoryPath, 0, -1);
		}

		if(!file_exists($directoryPath)){
			throw new Exception("Invalid directory path!");
		}

		if(!is_dir($directoryPath)){
			throw new Exception("Not a directory!");
		}

		$this->directoryToList = $directoryPath;
	}

	public function getArrFilesAtDirectory(){
		return $this->arrFilesAtDirectory;
	}
	
	public function getArrDirectoriesAtDirectory(){
		return $this->arrDirectoriesAtDirectory;
	}

	public function readDirectory(){
		
		$this->arrFilesAtDirectory = array();
		$this->arrDirectoriesAtDirectory = array();
		
		if ($dh = opendir($this->directoryToList)) {
			while (($file = readdir($dh)) !== false) {
				
				$objeto = $this->directoryToList . DIRECTORY_SEPARATOR . $file;
				
				if(is_file($objeto)){
					$this->arrFilesAtDirectory[] = $this->directoryToList . DIRECTORY_SEPARATOR . $file;
					continue;
				}
				
				if(is_dir($objeto) && $file != "." && $file != ".."){
					$this->arrDirectoriesAtDirectory[] = $this->directoryToList . DIRECTORY_SEPARATOR . $file;
				}
			}

			closedir($dh);
		}

	}

}
?>