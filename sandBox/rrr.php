<?php
require_once '/var/www/MiliSystem/class/general/filesystem/DirectoryLister.php';
require_once '/var/www/MiliSystem/class/general/filesystem/File.php';

function process($dirPath){

	$dl = new DirectoryLister($dirPath);

	$dl->readDirectory();

	$arrFiles = $dl->getArrFilesAtDirectory();
	$arrDirs = $dl->getArrDirectoriesAtDirectory();

	foreach ($arrFiles as $filePath) {
		$file = new File($filePath);

		if($file->getFileExtension() != "php") continue;

		editFile($file);
	}

	foreach ($arrDirs as $dirPath) {
		process($dirPath);
	}
}

function editFile(File &$file){
	$fileContents = $file->getFileContents();

	//print $fileContents . "\n\n\n\n\n\n\n";

	$qtde = preg_match_all("/(C[A-Z][a-z]*)/",$fileContents,$matches);

	if($qtde == 0) return;

	foreach ($matches as $arrMatche) {

		foreach ($arrMatche as $matche) {
			$oldLen = strlen($matche);
			
			if($oldLen <= 2) continue;
			
			$newName = substr($matche,1,$oldLen-1);
			$fileContents = str_replace($matche,$newName,$fileContents);
		}

	}

	file_put_contents($file->getFilePath(),$fileContents);
}


process("/var/www/MiliSystem");







?>