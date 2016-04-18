<?php

/**
 *
 */
class User
{
	public $id;
	public $email;
	public $password;

<<<<<<< HEAD
	private $raw_password;
	public $hashed_password;

	//Omitting id parameter will automatically generate one.
	function __construct($id, $username, $password)
	{
		$this->id = $id;
		$this->username = $username;
		$this->password = $password;
=======
	//Omitting id parameter will automatically generate one.
	function __construct($id, $email, $hashed_password) {

		$this->id = $id;
		$this->email = $email;
		$this->hashed_password = $hashed_password;
>>>>>>> steve
	}

	/**
	 * Ensure numeric fields are the correct type since PDO::fetch() only generates strings
	 */
	public function fixTypes()
	{
		$this->id = (int) $this->id;
	}
<<<<<<< HEAD
=======

	//Wrapper for password_hash().
	public static function hashPassword($raw_password) {
		return password_hash($raw_password, PASSWORD_DEFAULT);
	}
>>>>>>> steve
}