<?php
require_once __DIR__ . '/../../../database/Database.php';
require_once __DIR__ . '/../../../database/DatabaseQuery.php';
require_once __DIR__ . '/../interfaces/IAuthenticationRules.php';
require_once __DIR__ . '/../../../database/DatabaseConditions.php';
require_once __DIR__ . '/../../../database/DatabaseRequestedData.php';
class UserAuthenticatorDriver implements IAuthenticationRules {
	
	/**
	 *
	 * @var User
	 */
	private $user;
	public function __construct(User $user = null) {
		$this->user = $user;
	}
	
	/**
	 * Seta o user do usuário
	 *
	 * @return string
	 */
	public function setEntity(User $user) {
		$this->user = $user;
	}
	/**
	 * Authenticate the user on system
	 * 
	 * {@inheritdoc}
	 *
	 * @see IAuthenticationRules::checkAuthenticationData()
	 */
	public function checkAuthenticationData(): bool {
		
		// The user MUST be informed
		if (is_null ( $this->user )) throw new Exception ( "User not informed! See UserAuthenticatorDriver::setUser" );
		
		// We must be pessimistic
		$this->autenticatedEntity = null;
		
		// Setting the conditions
		$c = new DatabaseConditions ();
		$c->addCondition ( DatabaseConditions::AND, "login", $this->user->getLogin () );
		$c->addCondition ( DatabaseConditions::AND, "password", $this->user->getPassword () );
		
		// Assembling the querie
		$query = new DatabaseQuery ();
		$query->setConditions ( $c );
		$query->setObject ( $this->user );
		$query->setOperationType ( DatabaseQuery::OPERATION_GET );
		
		// Everything is allright?
		if (! Database::execute ( $query )) return false;
		
		// At least one user was returned?
		$res = Database::getResults ();
		if (! $res->next ()) return false;
		
		// If yes then stores the user
		$this->autenticatedEntity = $res->getRetrivedObject ();
		return true;
	}
	public function getAutenticatedEntity() {
		return $this->autenticatedEntity;
	}
}
?>
