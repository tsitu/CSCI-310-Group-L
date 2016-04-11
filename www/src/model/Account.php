<?php

/**
 * Account model class.
 */
class Account
{
	public $id;
	public $user_id;
	public $institution;
	public $type;

	/**
	 * Create a new Account object from given fields.
	 * Used to manually create, PDO::fetch() does not use this constructor.
	 *
	 * @param $_id
	 * @param $_institution
	 * @param $_type
	 */
	function __construct($_id, $_user_id, $_institution, $_type)
	{
		$this->id = $_id;
		$this->user_id = $_user_id;
		$this->institution = $_institution;
		$this->type = $_type;
	}


	/**
	 * Ensure numeric fields are the correct type since PDO::fetch() only generates strings
	 */
	public function fixTypes()
	{
		$this->id = (int) $this->id;
		$this->user_id = (int) $this->user_id;
	}
}
