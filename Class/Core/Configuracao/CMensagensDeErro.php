<?php
require_once 'CNiveisDeAcesso.php';
/**
 * Códigos de erro no sistema
 * @author tatupheba
 */
class CMensagensDeErro extends CNiveisDeAcesso{

	//Sessão inválida (login expirou ou a pessoa não logou)
	const CONST_ERR_SESSAO_INVALIDA_COD = "1";
	const CONST_ERR_SESSAO_INVALIDA_TEXTO = "Se autentique no sistema.";

	//Falha ao abrir ou criar um arquivo
	const CONST_ERR_FALHA_AO_ABRIR_OU_CRIAR_ARQUIVO_COD = "2";
	const CONST_ERR_FALHA_AO_ABRIR_OU_CRIAR_ARQUIVO_TEXTO = "Não foi possível abrir ou criar o arquivo.";

	//Não é possivel escrever no arquivo
	const CONST_ERR_FALHA_AO_ESCREVER_NO_ARQUIVO_COD = "3";
	const CONST_ERR_FALHA_AO_ESCREVER_NO_ARQUIVO_TEXTO = "Não foi possível escrever dados no arquivo";
}

?>
