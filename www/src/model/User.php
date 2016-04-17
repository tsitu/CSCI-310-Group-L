<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/DBManager.php";


class User
{
	public $id;
	public $email;
	public $hashed_password;

	//Omitting id parameter will automatically generate one.
	function __construct($id, $email, $hashed_password) {

		$this->id = $id;
		$this->email = $email;
		$this->hashed_password = $hashed_password;
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