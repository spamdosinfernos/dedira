<?php
class CNiveisDeAcesso{
	/**
	 * Não acessa coisa alguma!
	 * @var int
	 */
	const CONST_NIVEL_ACESSO_PESSOA = 0;

	/**
	 * Talvez acesse uma coisinha ou outra...
	 * @var int
	 */
	const CONST_NIVEL_ACESSO_MILITANTE = 1;

	/**
	 * Acessa módulos básicos.
	 * @var int
	 */
	const CONST_NIVEL_ACESSO_MILITANTE_DE_APOIO = 2;

	/**
	 * Acessa tudo da alçada de um usuário.
	 * @var int
	 */
	const CONST_NIVEL_ACESSO_MILITANTE_ORGANICO = 3;

	/**
	 * Acessa tudo!
	 * @var int
	 */
	const CONST_NIVEL_ACESSO_ADMINISTRADOR = 4;
}

?>