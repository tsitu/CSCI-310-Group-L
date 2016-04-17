<?php

/**
 *
 */
class User
{
	public $id;
	public $email;
	public $password;

	private $raw_password;
	public $hashed_password;

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
}