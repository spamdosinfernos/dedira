<?php

namespace main;

class MenuItemData {

	/**
	 * @var string
	 */
	private $menuText;

	/**
	 * @var string
	 */
	private $menuAddress;

	/**
	 * @var int
	 */
	private $updatesAmount;

	/**
	 * @return string
	 */
	public function getMenuText() {
		return $this->menuText;
	}

	/**
	 * @return string
	 */
	public function getMenuAddress() {
		return $this->menuAddress;
	}

	/**
	 * @return number
	 */
	public function getUpdatesAmount() {
		return $this->updatesAmount;
	}

	/**
	 * @param string $menuText
	 */
	public function setMenuText($menuText) {
		$this->menuText = $menuText;
	}

	/**
	 * @param string $menuAddress
	 */
	public function setMenuAddress($menuAddress) {
		$this->menuAddress = $menuAddress;
	}

	/**
	 * @param number $updatesAmount
	 */
	public function setUpdatesAmount($updatesAmount) {
		$this->updatesAmount = $updatesAmount;
	}
}