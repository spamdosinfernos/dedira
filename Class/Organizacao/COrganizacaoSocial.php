<?php
require_once 'COrganizacao.php';

class COrganizacaoSocial extends COrganizacao{

	/**
	 * Tipo da organização social
	 * @example Camponesa, Sindical, Estudantil, etc
	 * @var CTipoOrganizacao
	 */
	protected $tipo;

	protected $arrCorrentesIdeologicas;
}
?>