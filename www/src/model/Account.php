<?php

/**
 * Account class.
 */
class Account
{
	public $id;
	public $user_id;
	public $institution;
	public $type;

	function __construct($id, $institution, $type, $user_id) {
		$this->$id = $id;
		$this->$institution = $institution;
		$this->$type = $type;
		$this->$user_id = $user_id;
	}

	/**
	 * Ensure numeric fields are the correct type since PDO::fetch() only generates strings
	 */
	public function fixTypes()
	{
		$this->id = (int) $this->id;
		$this->user_id = (int) $this->user_id;
		$this->balance = (double) $this->balance;
	}
}