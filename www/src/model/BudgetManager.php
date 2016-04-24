<?php

require_once "DBManager.php";
require_once "Budget.php";


/**
 * Singleton BudgetManager provides DB queries related to a user's monthly budgets.
 * Uses connection from singleton DBManager to execute queries.
 */
class BudgetManager
{
	protected static $instance;

	private $connection;

	/* --- INIT --- */
	/**
	 * Returns singleton instnace of BudgetManager
	 *
	 * @return singleton instance of BudgetManager
	 */
	public static function getInstance()
	{
		if(null === static::$instance)
			static::$instance = new static();

		return static::$instance;
	}

	/**
	 * Protected constructor to prevent new instances.
	 * Store reference to connection from `DBManager`
	 */
	protected function __construct()
	{
		$this->connection = DBManager::getConnection();
	}

	/**
	 * Private clone to prevent two instances
	 */
	private function __clone()
	{

	}

	/**
	 * Private wakeup to prevent unserializing
	 */
	private function __wakeup()
	{

	}


	/* --- QUERIES --- */
	/**
	 * Adds a new financial account with given name tied to specified user.
	 *
	 * @param $year 		- the year this budget references
	 * @param $month 		- the month this budget references
	 * @param $user_id 		- user_id of user this budget belongs to
	 * @return id of the budget if added, null otherwise
	 */
	public function addBudget($user_id, $month, $year, $budget)
	{
		$str = "INSERT IGNORE INTO Budgets(user_id, month, year, budget) VALUES (:user_id, :month, :year, :budget);";

		//encrypt
		//$month = DBManager::encrypt($month);
		//$year = DBManager::encrypt($year);

		$stmt = $this->connection->prepare($str);
		$stmt->bindParam(':user_id', $user_id);
		$stmt->bindParam(':month', $month);
		$stmt->bindParam(':year', $year);
		$stmt->bindParam(':budget', $budget);
		$stmt->execute();

		return $this->connection->lastInsertId();
	}

	/**
	 * Deletes the budget with given `$id`.
	 *
	 * @param $id - unique id of account to delete.
	 */
	public function deleteBudget($id)
	{
		$str = "DELETE FROM Budgets WHERE id = :id";

		$stmt = $this->connection->prepare($str);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
	}

	/**
	 * Returns an `Budget` instance with the given info owned by the specified user.
	 *
	 * @param $institution  - the year of this budget
	 * @param $month 		- the month of this budget
	 * @param $user_id 		- user_id of user this budget belongs to
	 * @return new `Budget` instance if found, null otherwise
	 */
	public function getBudgetByInfo($user_id, $month, $year)
	{
		$str = "SELECT * FROM Budgets WHERE user_id = :user_id AND month = :month AND year = :year";

		//encrypt
		//$month = DBManager::encrypt($month);
		//$year = DBManager::encrypt($year);

		$stmt = $this->connection->prepare($str);
		$stmt->bindParam(':user_id', $user_id);
		$stmt->bindParam(':month', $month);
		$stmt->bindParam(':year', $year);
		$stmt->execute();

		$stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Budget', ['id', 'user_id', 'month', 'year', 'budget']);
		$budget = $stmt->fetch();
		if (!$budget)
			return null;

		$budget->fixTypes();
		return $budget;
	}

	/**
	 * Get all budgets owned by a specific user.
	 */
	public function getBudgetsByUser($user_id)
	{
		$str = " SELECT * FROM Budgets WHERE user_id = :user_id";

		$stmt = $this->connection->prepare($str);
		$stmt->bindParam(':user_id', $user_id);
		$stmt->execute();

		$budgets = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Budget', ['id', 'user_id', 'month', 'year', 'budget']);
		if (!$budgets)
			return [];

		foreach ($budgets as $b)
			$b->fixTypes();

		return $budgets;
	}
}
