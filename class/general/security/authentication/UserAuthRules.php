<?php
require_once 'IAuthenticationRules.php';
require_once __DIR__ . '/../../database/Database.php';
require_once __DIR__ . '/../../database/DatabaseQuery.php';
require_once __DIR__ . '/../../database/DatabaseConditions.php';
require_once __DIR__ . '/../../database/DatabaseRequestedData.php';
require_once __DIR__ . '/../../configuration/security/authentication/UserAuthRulesConf.php';
class UserAuthRules implements IAuthenticationRules {
	
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
	public function setUser(User $user) {
		$this->user = $user;
	}
	public function checkAuthenticationData() {
		
		// We must be pessimistic
		$this->autenticationId = null;
		
		// Setting the conditions
		$c = new DatabaseConditions ();
		$c->addCondition ( DatabaseConditions::AND, "user", $this->user->getLogin () );
		$c->addCondition ( DatabaseConditions::AND, "password", $this->user->getPassword () );
		
		// Assembling the querie
		$query = new DatabaseQuery ();
		$query->setConditions ( $c );
		$query->setObject ( new User () );
		$query->setOperationType ( DatabaseQuery::OPERATION_GET );
		
		// Everything is allright?
		if (!Database::execute ( $query )) return false;
		
		// At least one user was returned?
		if (! $res = Database::getResults ()->getObjectsAffectedCounting ()) return false;
		
		$res->next ();
		
		// If yes then stores the user id
		$this->autenticationId = $res->getRetrivedObject ()->getId ();
		return true;
	}
	public function getAutenticationId() {
		return $this->autenticationId;
	}
}
?>
