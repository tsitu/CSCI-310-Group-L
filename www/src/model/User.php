<?php

/**
 * Account model class.
 */
class User
{
	public $id;
	public $email;
	public $password;

	/**
	 * User constructor
	 *
	 * @param $_id
	 * @param $_institution
	 * @param $_type
	 */
	function __construct($id, $email, $password)
	{
		$this->id = $id;
		$this->email = $email;
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