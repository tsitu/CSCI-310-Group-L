<?php

require_once __DIR__ . '/DBManager.php';

/**
 * Budget class
 */
class Budget
{
	public $id;
	public $user_id;
	public $category;
	public $budget;
	public $month;
	public $year;

	function __construct($id, $user_id, $month, $year, $category, $budget) {
		$this->id = $id;
		$this->user_id = $user_id;
		$this->category = $category;
		$this->budget = $budget;
		$this->month = $month;
		$this->year = $year;
	}

	/**
	 * Ensure numeric fields are the correct type since PDO::fetch() only generates strings
	 */
	public function fixTypes()
	{
		$this->id = (int) $this->id;
		$this->user_id = (int) $this->user_id;
		$this->month = (int) $this->month;
		$this->year = (int) $this->year;

		$this->category = rtrim(DBManager::decrypt($this->category));

		$this->budget = (double) $this->budget;
	}
}
?>