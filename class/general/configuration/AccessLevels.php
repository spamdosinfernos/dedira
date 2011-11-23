<?php
class AccessLevels{
	/**
	 * Não acessa coisa alguma!
	 * @var int
	 */
	const CONST_ACCESS_LEVEL_NONE = 0;

	/**
	 * Talvez acesse uma coisinha ou outra...
	 * @var int
	 */
	const CONST_ACCESS_LEVEL_BASIC = 1;

	/**
	 * Acessa módulos básicos.
	 * @var int
	 */
	const CONST_ACCESS_LEVEL_MEDIUM = 2;

	/**
	 * Acessa tudo da alçada de um usuário.
	 * @var int
	 */
	const CONST_ACCESS_LEVEL_FULL = 3;

	/**
	 * Acessa tudo!
	 * @var int
	 */
	const CONST_ACCESS_LEVEL_ROOT = 4;
}
?>