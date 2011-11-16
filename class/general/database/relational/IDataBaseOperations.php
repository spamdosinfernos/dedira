<?php
interface IDataBaseOperations{

		/**
		 * Abre a conexão com o banco de dados
		 * @throws Exception: Não foi possível conectar ao banco de dados
		 */
		public function openConnection();
	
		/**
		 * Fecha a conexão com o banco de dados
		 */
		public function closeConnection();
	
		/**
		 * Executa a consulta
		 * @return resource
		 */
		public function execQuery($query);
		
		/**
		 * Retorna todas as linhas resultantes da consulta
		 * @return array : string
		 */
		public function getFetchArray();
		
		/**
		 * Retorna o número de linhas retornadas
		 * @return int
		 */
		public function getRowsCount();
		
		/**
		 * Retorna o chamador de procedures do banco de dados
		 * @return string
		 */
		public function getProcedureCallToken();
}
?>