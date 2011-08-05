<?php
interface IEvento{
	
	/**
	 * Salva o evento
	 */
	public function salvar();
	
	/**
	 * Apaga o evento
	 */
	public function apagar();
	
	/**
	 * Carrega um evento da base dada sua identificação
	 */
	public function carregar();

}
?>