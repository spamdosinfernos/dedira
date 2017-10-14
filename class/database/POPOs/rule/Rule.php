<?php
require_once __DIR__ . '/../abstractions/AStorableObject.php';
/**
 *
 * @author ensis
 *        
 */
class Rule extends AStorableObject {
	
	/**
	 * The original author
	 *
	 * @var mixed
	 */
	protected $authorId;
	
	/**
	 * The law datetime creation
	 *
	 * @var DateTime
	 */
	protected $creationDatetime;
	
	/**
	 * Approved?
	 *
	 * @var bool
	 */
	protected $approved;
	
	/**
	 * The law contents
	 *
	 * @var string
	 */
	protected $lawContents;
	
	/**
	 * The upvoters ids
	 *
	 * @var array
	 */
	protected $arrUpVotersIds;
	
	/**
	 * The dowvoters ids
	 *
	 * @var array
	 */
	protected $arrDowVotersIds;
	
	/**
	 *
	 * @return DateTime
	 */
	public function getCreationDatetime(): DateTime {
		return $this->creationDatetime;
	}
	
	/**
	 *
	 * @param DateTime $creationDatetime        	
	 */
	public function setCreationDatetime(DateTime $creationDatetime) {
		$this->creationDatetime = $creationDatetime;
		$this->AddChange ( "creationDatetime", $creationDatetime );
		return $this;
	}
	
	/**
	 *
	 * @return mixed
	 */
	public function getAuthorId() {
		return $this->authorId;
	}
	
	/**
	 *
	 * @param mixed $authorId        	
	 *
	 */
	public function setAuthorId($authorId) {
		$this->authorId = $authorId;
		$this->AddChange ( "authorId", $authorId );
		return $this;
	}
	
	/**
	 *
	 * @return bool
	 */
	public function getApproved(): bool {
		return $this->approved;
	}
	
	/**
	 *
	 * @param bool $approved        	
	 *
	 */
	public function setApproved(bool $approved) {
		$this->approved = $approved;
		$this->AddChange ( "approved", $approved );
		return $this;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getLawContents(): string {
		return $this->lawContents;
	}
	
	/**
	 *
	 * @param string $lawContents        	
	 */
	public function setLawContents(string $lawContents) {
		$this->lawContents = $lawContents;
		$this->AddChange ( "lawContents", $lawContents );
		return $this;
	}
	
	/**
	 *
	 * @return array
	 */
	public function getArrUpVotersIds(): array {
		return $this->arrUpVotersIds;
	}
	
	/**
	 *
	 * @param array $arrUpVotersIds        	
	 */
	public function setArrUpVotersIds(array $arrUpVotersIds) {
		$this->arrUpVotersIds = $arrUpVotersIds;
		$this->AddChange ( "arrUpVotersIds", $arrUpVotersIds );
		return $this;
	}
	
	/**
	 *
	 * @param mixed $upVotersId        	
	 */
	public function addUpVoterId($upVotersId) {
		$this->arrUpVotersIds [] = $upVotersId;
		$this->AddChange ( "arrUpVotersIds", $upVotersId, self::COLLECTION_ADD );
		return $this;
	}
	
	/**
	 *
	 * @param mixed $upVotersId        	
	 */
	public function removeUpVoterId($upVotersId) {
		$this->arrUpVotersIds [] = $upVotersId;
		$this->AddChange ( "arrUpVotersIds", $upVotersId, self::COLLECTION_REMOVE );
		return $this;
	}
	
	/**
	 *
	 * @return array
	 */
	public function getArrDowVotersIds(): array {
		return $this->arrDowVotersIds;
	}
	
	/**
	 *
	 * @param array $arrDowVotersIds        	
	 */
	public function setArrDowVotersIds(array $arrDowVotersIds) {
		$this->arrDowVotersIds = $arrDowVotersIds;
		$this->AddChange ( "arrDowVotersIds", $arrDowVotersIds );
		return $this;
	}
	
	/**
	 *
	 * @param mixed $dowVotersId        	
	 */
	public function addDowVoterId($dowVotersId) {
		$this->arrDowVotersIds [] = $dowVotersId;
		$this->AddChange ( "arrDowVotersIds", $dowVotersId, self::COLLECTION_ADD );
		return $this;
	}
	
	/**
	 *
	 * @param mixed $dowVotersId        	
	 */
	public function removeDowVoterId($dowVotersId) {
		unset ( $this->arrDowVotersIds [$dowVotersId] );
		$this->AddChange ( "arrDowVotersIds", $dowVotersId, self::COLLECTION_REMOVE );
		return $this;
	}
}
?>