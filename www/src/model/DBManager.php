<?php

require_once "Account.php";
require_once "Transaction.php";


/* CONST */
const HOST_NAME = "sql3.freemysqlhosting.net";
const HOST_IP 	= "54.215.148.52";
const USERNAME  = "sql3114710";
const PASSWORD  = "3zaKKK36kN";
const DB 		= "sql3114710";
const PORT 		= "3306";

/**
 * Database manager class.
 * When initialized, connects to the MySQL server specified by constants.
 * Provides query methods that return data as php objects.
 */
class DBManager
{
	private $connection;

	/**
	 * Initialize DBManager object and connect to MySQL specified by constants.
	 * @throws exception if connection fails
	 */
	function __construct()
	{
		$dsn = "mysql:host=54.215.148.52;dbname=sql3114710";
		$this->connection = new PDO($dsn, USERNAME, PASSWORD);

		//throw exceptions
		$this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$this->connection->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
		$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	/**
	 * Close connection when destructing
	 */
	function __destruct()
	{
		$this->connection = null;
	}



	/* --- QUERIES --- */
	/**
	 * Return user id with given username and password.
	 *
	 * @param username - email of user to get
	 * @param password - of user to get
	 * @return user_id if valid; null otherwise.
	 * @throws exception when statement fails
	 */
	public function getUser($username, $password)
	{
		$statement = $this->connection->prepare("SELECT id FROM Users WHERE (email = ? AND password = ?)");
		$statement->execute( [htmlentities($username), htmlentities($password)] );
		return $statement->fetch()['id'];
	}

	/**
	 * Fetch all distinct financial accounts of specified user, including each account's balance.
	 * Balance is retrieved by doing left join with Transactions table.
	 *
	 * @param user_id - unique id of user to get accounts of
	 * @return array of Account objects for user
	 * @throws exception when MySQL statement fails
	 */
	public function getAccountsWithBalance($user_id)
	{
		$str = "
		SELECT Accounts.*, IFNULL(t.balance, 0), t.time
		FROM 
			Accounts
		LEFT JOIN 
			(SELECT account_id, balance, time FROM Transactions ORDER BY time DESC limit 1) t
		ON Accounts.id = t.account_id 
		WHERE Accounts.user_id = 1;
		";

		$statement = $this->connection->prepare($str);
		$statement->execute( [$user_id] );
		$accounts = $statement->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Account", ["_id", "_user_id", "_institution", "_type", "_balance"]);

		foreach ($accounts as $a)
			$a->fixTypes();

		return $accounts;
	}

	/**
	 * Fetch the latest `limit` transactions for specified user across all accounts.
	 * Note: does not return the account balance snapshot
	 *
	 * @param $user_id - unique id of user to transactions of
	 * @param $limit = 30 - number of transactions to get
	 */
	public function getTransactionsForUser($user_id, $limit = 30)
	{
		$str = "
		SELECT id, account_id, time, amount, category, descriptor FROM Transactions 
		WHERE user_id = ?
		ORDER BY time DESC 
		LIMIT ?;
		";

		$statement = $this->connection->prepare($str);
		$statement->execute( [$user_id, $limit] );
		$transactions = $statement->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Transaction", ["_id", "_user_id", "_account_id", "_time", "_amount", "_category", "_descriptor"]);

		foreach ($transactions as $t)
			$t->fixTypes();

		return $transactions;
	}

	
}