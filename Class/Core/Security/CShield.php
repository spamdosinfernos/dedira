<?php
/**
 * Contêm métodos "escudo", ou seja, cuja função é proteger o sistema contra alguns tipos de ataque
 */
final class CShield{
	/**
	 * Tenta impedir os tipos mais comuns de ataques por injeção de código
	 * Se a string for informada trata a string, senão trata todas as variáveis 
	 * postadas
	 * @param string | array : string $data
	 */
	public static function treatTextFromForm(){
		//Trata o POST
		self::cleanUpData($_POST);

		//Trata o GET
		self::cleanUpData($_GET);
	}

	private static function cleanUpData(&$arrData){

		foreach ($arrData as $index => $data){

			if(is_array($data)){
					
				//Se for um array trata os itens do array
				$arrData[$index] = self::cleanUpData($data);
				continue;
			}

			$data = strip_tags($data);
			$data = stripslashes($data);
			$data = stripcslashes($data);
			$data = trim($data);

			$arrData[$index] = $data;
		}

		return $arrData;
	}
}
?>