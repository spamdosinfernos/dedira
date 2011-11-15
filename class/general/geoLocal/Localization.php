<?php
/**
 * Representa uma localização no sistema.
 * Uma localização é um endereço completo, com número, bairro, cep, etc...
 */
class Localization {

	private $status;

	private $logradouro;
	private $tipoLogradouroId;
	private $tipoLogradouroDesc;
	
	private $uf;
	private $ufId;
	private $ufSigla;
	
	private $cep;
	private $cidade;
	private $bairrro;

	function __construct($status, $tipoLogradouroDesc, $tipoLogradouroId, $logradouro, $bairro, $cidade, $uf, $ufSigla, $ufId, $cep) {
		$this->status = $status;
		$this->tipoLogradouroId = $tipoLogradouroId;
		$this->tipoLogradouroDesc = $tipoLogradouroDesc;
		$this->logradouro = $logradouro;
		$this->bairro = $bairro;
		$this->cidade = $cidade;
		$this->uf = $uf;
		$this->ufSigla = $ufSigla;
		$this->ufId = $ufId;
		$this->cep = $cep;
	}

	public function getStatus() {
		return $this->status;
	}

	public function getTipoLogradouroId() {
		return $this->tipoLogradouroId;
	}

	public function getTipoLogradouroDesc() {
		return $this->tipoLogradouroDesc;
	}

	public function getLogradouro() {
		return $this->logradouro;
	}

	public function getBairro() {
		return $this->bairro;
	}

	public function getCidade() {
		return $this->cidade;
	}

	public function getUf() {
		return $this->uf;
	}

	public function getUfSigla() {
		return $this->ufSigla;
	}

	public function getUfId() {
		return $this->ufId;
	}

	public function getCep() {
		return $this->cep;
	}

	public function getBairrro(){
		return $this->bairrro;
	}
}
?>