<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/DBManager.php';
require_once __DIR__ . '/Budget.php';


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
	 * Adds a new budget to given user .
	 *
	 * @param $year 		- the year this budget references
	 * @param $month 		- the month this budget references
	 * @param $user_id 		- user_id of user this budget belongs to
	 * @return id of the budget if added, 0 otherwise
	 */
	public function addBudget($user_id, $category, $month, $year, $budget)
	{
		$str = "INSERT IGNORE INTO Budgets(user_id, category, budget, month, year) VALUES (:user_id, :category, :budget, :month, :year);";

		//encrypt
		$category = DBManager::encrypt($category);

		$stmt = $this->connection->prepare($str);
		$stmt->execute([
			':user_id' 	=> $user_id,
			':category' => $category,
			':budget'   => $budget,
			':month'	=> $month,
			':year'		=> $year,
		]);

		return $this->connection->lastInsertId();
	}

	/**
	 * Add default budget for specified user and time
	 *
	 * @return true if inserted defaults for all categories, false otherwise.
	 */
	private function addDefaultBudgetsForTime($user_id, $month, $year)
	{
		global $config;

		//defaults
		$categories = $config['budget_categories'];
		$amount = $config['budget_default'];

		//prepare
		$str = "INSERT IGNORE INTO Budgets(user_id, category, budget, month, year) VALUES (:user_id, :category, :budget, :month, :year);";
		$stmt = $this->connection->prepare($str);

		//loop execute
		$count = 0;
		foreach ($categories as $c)
		{
			$stmt->execute([
				':user_id' 	=> $user_id,
				':budget'   => $amount,
				':month'	=> $month,
				':year'		=> $year,
				':category' => DBManager::encrypt($c),
			]);

			$count += $stmt->rowCount();
		}

		return $count == count($categories);
	}

	/**
	 * Update scpecified budget for category during month, year to given amount
	 *
	 * @return true if updated, false otherwise.
	 */
	public function updateBudget($user_id, $category, $month, $year, $budget)
	{
		$str = "UPDATE Budgets SET budget = :budget WHERE (user_id, category, month, year) = (:user_id, :category, :month, :year);";

		//encrypt
		$category = DBManager::encrypt($category);

		echo $category;

		$stmt = $this->connection->prepare($str);
		$stmt->execute([
			':budget'   => $budget,
			':user_id' 	=> $user_id,
			':category' => $category,
			':month'	=> $month,
			':year'		=> $year
		]);


		return $stmt->rowCount() > 0;
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
	 * Retreive budget across all categories for specified user and time
	 *
	 * @return map of category to the budget, empty array otherwise.
	 */
	public function getBudgetsForTime($user_id, $month, $year)
	{
		//if there no budget is set, create it
		$this->addDefaultBudgetsForTime($user_id, $month, $year);

		$str = "SELECT user_id, category, budget, month, year FROM Budgets WHERE (user_id, month, year) = (:user_id, :month, :year);";

		//do the deed
		$stmt = $this->connection->prepare($str);
		$stmt->execute([
			':user_id' 	=> $user_id,
			':month'	=> $month,
			':year'		=> $year,
		]);

		//result
		$budgets = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Budget', ['id', 'user_id', 'category', 'budget', 'month', 'year']);
		if (!$budgets)
			return [];

		$response = [];
		foreach ($budgets as $b)
		{
			$b->fixTypes();
			$response[$b->category] = $b;
		}

		return $response;
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

		$stmt = $this->connection->prepare($str);
		$stmt->execute([
			':user_id' 	=> $user_id,
			':month'	=> $month,
			':year'		=> $year,
		]);

		$budget = $stmt->fetch(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Budget', ['id', 'user_id', 'category', 'budget', 'month', 'year']);
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
		$stmt->execute([
			':user_id' => $user_id
		]);

		$budgets = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Budget', ['id', 'user_id', 'category', 'budget', 'month', 'year']);
		if (!$budgets)
			return [];

		foreach ($budgets as $b)
			$b->fixTypes();

		return $budgets;
	}
}
