<?php
require_once __DIR__ . '/MenuItem.php';
require_once __DIR__ . '/../../database/Database.php';
require_once __DIR__ . '/../../database/DatabaseQuery.php';
require_once __DIR__ . '/../../database/DatabaseRequestedData.php';
/**
 * Loads all menuItens registered on database
 *
 * @author ensismoebius
 *        
 */
class MenuItensLoader {

	/**
	 * Loads and returns all menuItens registered on database
	 *
	 * @return bool
	 */
	public static function load(): array {
		// Assembling the querie
		$query = new DatabaseQuery ();
		$query->setObject ( new MenuItem () );
		$query->setOperationType ( DatabaseQuery::OPERATION_GET );

		// Everything is allright?
		if (! Database::execute ( $query ))
			return array ();

		return Database::getResults ()->getData ();
	}
}

