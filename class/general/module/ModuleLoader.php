<?php

final class ModuleLoader{
	
	public static function loadModule($moduleId){
		
		$modulePath = Configuration::getUserModuleDiretory() . DIRECTORY_SEPARATOR . $moduleId;
		
		$directoryLister = new DirectoryLister(Configuration::getUserModuleDiretory());
		$directoryLister->readDirectory();
		
		$arrDirs = $directoryLister->getArrDirectoriesAtDirectory();
		
		//TODO Terminar de fazer este método
		if(!in_array($modulePath, $arrDirs)) throw new SystemException(Lang_ModuleLoader::getDescriptions(1), __CLASS__ . __LINE__, $moduleId);
		
		Configuration::getTemplatesDirectory()
		
		require_once $modulePath . DIRECTORY_SEPARATOR ;
		
		
	}
	
}

?>