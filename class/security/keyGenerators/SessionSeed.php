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
		return isset ( $_REQUEST ["seed"] ) && $_REQUEST ["seed"] == $_SESSION ["seed"];
	}

	/**
	 * Get next seed currrent value
	 *
	 * @throws Exception
	 * @return int
	 */
	public static function getSeed(): int {
		if (! isset ( $_SESSION )) {
			throw new Exception ( "session_start MUST be called before!" );
		}
		return $_SESSION ["seed"];
	}

	/**
	 * Generate seed and next seed values
	 * Use for first time seed generation
	 *
	 * @throws Exception
	 */
	public static function genNextSeed(): void {
		if (! isset ( $_SESSION )) {
			throw new Exception ( "session_start MUST be called before!" );
		}
		$_SESSION ["seed"] = rand ();
	}
}