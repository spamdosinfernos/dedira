<?php
class SessionSeed {

	/**
	 * The "seed" are used to ensure that no men(or woman)-in-the-middle
	 * attack happens, this is made by generating a new seed every time
	 * a request is made
	 *
	 * @return bool
	 */
	public static function providedSeedIsValid(): bool {
		if (! isset ( $_REQUEST ["seed"] )) {
			return false;
		}

		if (! isset ( $_SESSION ["seed"] )) {
			return false;
		}
		return $_REQUEST ["seed"] == $_SESSION ["seed"];
	}

	/**
	 * Get seed currrent value
	 * Returns an negative value if there is no seed
	 *
	 * @throws Exception
	 * @return int
	 */
	public static function getSeed(): int {
		if (! isset ( $_SESSION )) {
			return - 1;
		}

		return isset ( $_SESSION ["seed"] ) ? $_SESSION ["seed"] : - 1;
	}

	/**
	 * Generate seed and next seed values
	 * Use for first time seed generation
	 *
	 * @throws Exception
	 */
	public static function genNextSeed(): void {
		if (! isset ( $_SESSION )) {
			session_start ();
		}
		$_SESSION ["seed"] = rand ();
	}
}