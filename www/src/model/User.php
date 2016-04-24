<?php

/**
 *
 */
class User
{
	public $id;
	public $email;
	public $password;
	
	//Omitting id parameter will automatically generate one.
	function __construct($id, $username, $password)
	{
		$this->id = $id;
		$this->username = $username;
		$this->password = $password;
	}

	/**
	 * Ensure numeric fields are the correct type since PDO::fetch() only generates strings
	 */
	public function fixTypes()
	{
		$this->id = (int) $this->id;
	}
	//Wrapper for password_hash().
	public static function hashPassword($raw_password) {
		return password_hash($raw_password, PASSWORD_DEFAULT);
	}

}