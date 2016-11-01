<?php
require_once '../general/geoLocal/Localization.php';
require_once __DIR__ . '/../configuration/LocalizationSearcherConf.php';
/**
 * Responsável por carregar endereços dado algum(ns) dado(s) dos mesmos
 */
class LocalizationSearcher {

	/**
	 * Endereço completo relativo
	 * @var Localization
	 */
	private $address;

	/**
	 * Guarda as informações referentes ao endereço quando carregadas dos servidores
	 * @var DOMDocument
	 */
	private $addressData;

	public function __construct() {
		$this->addressData = new DOMDocument();
	}

	/**
	 * Procura os dados do endereço referente ao cep informado
	 * @param string $cep
	 */
	public function searchCep($cep){
		
		//Formata o cep para o formato requerido para os serviços
		$cep = substr(str_replace('-','',$cep),0,5).'-'.substr(str_replace('-','',$cep),5,7);
		
		if($this->searchCepLivre($cep)) return true;
		if($this->searchCepUnico($cep)) return true;
		return false;
	}


	/**
	 * Busca o cep no serviço cep livre
	 * @param $cep
	 * @return boolean
	 */
	private function searchCepLivre($cep) {

		$this->addressData->load(LocalizationSearcherConf::getCepLivreUrl() . $cep);

		return $this->parseCepLivreNodes($cep);
	}

	/**
	 * Busca o cep no serviço cep único
	 * @param $cep
	 * @return boolean
	 */
	private function searchCepUnico($cep){

		$this->addressData->load(LocalizationSearcherConf::getCepUnicoUrl() . $cep);

		return $this->parseCepUnicoNodes($cep);
	}

	/**
	 * Lê o xml retornado para construir uma Localization
	 * @param $cep
	 */
	private function parseCepLivreNodes($cep) {

		$dataset = $this->addressData->getElementsByTagName("ceplivre");

		foreach($dataset as $item) {

			/*
			 * Se o status for diferente de 1 algo deu errado, sendo assim constrói
			 * uma localização vazia e retorna falso
			 */
			$status = $item->getElementsByTagName("sucesso")->item(0)->nodeValue;
			if($status != 1){
				$this->address = new Localization(0, "", "", "", "","" ,"", "", "",$cep);
				return false;
			}

			//Se deu tudo certo cria uma nova localização e retorna verdadeiro
			$this->address = new Localization(
			$status,
			$item->getElementsByTagName("tipo_logradouro")->item(0)->nodeValue,
			$item->getElementsByTagName("tipo_logradouro_id")->item(0)->nodeValue,
			$item->getElementsByTagName("logradouro")->item(0)->nodeValue,
			$item->getElementsByTagName("bairro")->item(0)->nodeValue,
			$item->getElementsByTagName("cidade")->item(0)->nodeValue,
			$item->getElementsByTagName("estado")->item(0)->nodeValue,
			$item->getElementsByTagName("estado_sigla")->item(0)->nodeValue,
			$item->getElementsByTagName("estado_id")->item(0)->nodeValue,
			$item->getElementsByTagName("cep")->item(0)->getElementsByTagName("cep")->item(0)->nodeValue);
			return true;
		}
	}

	/**
	 * Lê o xml retornado para construir uma Localization
	 * @param $cep
	 */
	private function parseCepUnicoNodes($cep){

		$this->addressData->load($path);

		$dataset = $this->addressData->getElementsByTagName("webservicecep");

		foreach($dataset as $item) {

			$status = $item->getElementsByTagName("resultado")->item(0)->nodeValue;

			switch ($status){
				case 1:
					$this->address = new Localization(1,
					$item->getElementsByTagName("tipo_logradouro")->item(0)->nodeValue,
                                	"",
					$item->getElementsByTagName("logradouro")->item(0)->nodeValue,
					$item->getElementsByTagName("bairro")->item(0)->nodeValue,
					$item->getElementsByTagName("cidade")->item(0)->nodeValue,
					$item->getElementsByTagName("uf")->item(0)->nodeValue,
                                	"", 
                                	"",
					$cep);
					return true;
				case 2:
					$this->address = new Localization(2,
                                	"", 
                                	"", 
                                	"", 
                                	"", 
					$item->getElementsByTagName("cidade")->item(0)->nodeValue,
					$item->getElementsByTagName("uf")->item(0)->nodeValue,
                                	"", 
                                	"",
					$cep);
					return true;
				default:
					$this->address = new Localization(0, "", "", "", "","" ,"", "", "",$cep);
					return false;
			}
		}
	}


	public function getAddress(){
	    return $this->address;
	}
}
?>