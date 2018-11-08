<?php
class PasswordPreparer{
	
	/**
	 * "Mess" the password that will be stored
	 */
	static public function messItUp($password){
		return md5(md5($password));
	}
}