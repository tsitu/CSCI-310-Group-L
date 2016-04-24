<?php

/**
 * Account class.
 */
class Account
{
	public $id;
	public $user_id;

	public $name;
	public $type;
	public $institution;
	public $balance

	function __construct($id, $institution, $type, $user_id, $balance = 0) {
		$this->id = $id;
		$this->institution = $institution;
		$this->type = $type;
		$this->user_id = $user_id;
		$this->balance = $balance;
	}

	/**
	 * Ensure numeric fields are the correct type since PDO::fetch() only generates strings
	 */
	public function fixTypes()
	{
		$this->id = (int) $this->id;
		$this->user_id = (int) $this->user_id;

		$this->type = rtrim(DBManager::decrypt($this->type));
		$this->institution = rtrim(DBManager::decrypt($this->institution));
		$this->name = $this->institution . ' - ' . $this->type;

		$this->balance = (double) $this->balance;
	}
}
?>