<?php
class PasswordPreparer{
	
	/**
	 * "Bagunça" a senha afim da mesma ser armazenada
	 */
	static public function messItUp($password){
		return md5(md5($password));
	}
}