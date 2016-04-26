<?php

/**
 * Budget class
 */
class Budget
{
	public $id;
	public $user_id;
	public $month;
	public $year;
	public $budget;

	function __construct($id, $user_id, $month, $year, $budget) {
		$this->id = $id;
		$this->user_id = $user_id;
		$this->month = $month;
		$this->year = $year;
		$this->budget = $budget;
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

		//$this->month = rtrim(DBManager::decrypt($this->month));
		//$this->year = rtrim(DBManager::decrypt($this->year));

		$this->budget = (double) $this->budget;
	}
}
?>