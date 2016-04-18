<?php

require_once "DBManager.php";
require_once "Account.php";


/**
 * Singleton AccountManager provides DB queries related to a user's financial accounts.
 * Uses connection from singleton DBManager to execute queries.
 */
class AccountManager
{
	protected static $instance;

	private $connection;

	/* --- INIT --- */
	/**
	 * Returns singleton instnace of UserManager
	 *
	 * @return singleton instance of UserManager
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
	 * @param $institution  - string of account's institution (ex. Bank of America)
	 * @param $type 		- string of account type (ex. Credit, Debit)
	 * @param $user_id 		- user_id of user this account belongs to
	 */
	public function addAccount($inst, $type, $user_id)
	{
		$str = "INSERT IGNORE INTO Accounts (institution, type, user_id) VALUES (:institution, :type, :user_id)";

		//encrypt
		$inst = DBManager::encrypt($inst);
		$type = DBManager::encrypt($type);

		$stmt = $this->connection->prepare($str);
		$stmt->bindParam(':user_id', $user_id);
		$stmt->bindParam(':institution', $inst);
		$stmt->bindParam(':type', $type);
		$stmt->execute();

		return $this->connection->lastInsertId();
	}

	/**
	 * Delets the financial account with given `$id`.
	 *
	 * @param $id - unique id of account to delete.
	 */
	public function deleteAccount($id)
	{
		$str = "DELETE FROM Accounts WHERE id = :id";

		$stmt = $this->connection->prepare($str);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
	}

	/**
	 * Updates institution and type of account matching $id.
	 *
	 * @param $id
	 * @param $
	 */
	public function updateAccount($id, $newInst, $newType)
	{
		$str = "UPDATE Accounts SET institution = :inst, type = :type WHERE id = :id";

		$stmt = $this->connection->prepare($str);
		$stmt->bindParam(':inst', $newInst);
		$stmt->bindParam(':type', $newType);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
	}

	//Returns an array containing all accounts owned by user_id.
	/**
	 * Return array of all accounts owned by specified user.
	 *
	 * @param $user_id - unique id of user to get accounts for
	 * @return array of accounts owned by user
	 */
	public function getAllAccounts($user_id)
	{
		$str = "SELECT id, user_id, institution, type, FROM Accounts WHERE user_id = :user_id";

		$stmt = $this->connection->prepare($str);
		$stmt->bindParam(':user_id', $user_id);
		$stmt->execute();

		$accounts = $stmt->fetchAll(PDO::FETCH_CLASS);
		if (!$accounts)
			return [];

		$list = [];
		foreach ($accounts as $a)
		{
			//decrypt
			$a->institution = DBManager::decrypt( $a->institution );
			$a->type 		= DBManager::decrypt( $a->type );

			$list[] = new Account($row->id, $row->institution, $row->type, $row->user_id);
		}
		return $list;
	}

	/**
	 * Returns an `Account` instance with the given name owned by the specified user.
	 *
	 * @param $institution  - string of account's institution (ex. Bank of America)
	 * @param $type 		- string of account type (ex. Credit, Debit)
	 * @param $user_id 		- user_id of user this account belongs to
	 * @return new `Account` instance if found, null otherwise
	 */
	public function getAccountByInfo($user_id, $inst, $type)
	{
		$str = "SELECT id, user_id, institution, type FROM Accounts WHERE user_id = :user_id AND type = :type AND institution = :inst";

		//encrypt
		$inst = DBManager::encrypt($inst);
		$type = DBManager::encrypt($type);

		$stmt = $this->connection->prepare($str);
		$stmt->bindParam(':user_id', $user_id);
		$stmt->bindParam(':type', $type);
		$stmt->bindParam(':inst', $inst);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_OBJ);
		if (!$row)
			return null;

		//decrypt
		$row->institution = DBManager::decrypt( $row->institution );
		$row->type = DBManager::decrypt( $row->type );

		return new Account($row->id, $row->institution, $row->type, $row->user_id);
	}

	/**
	 * Get all accounts with balances owned by specified user
	 */
	public function getAccountsWithBalance($user_id)
	{
		$str = "
		SELECT a.id, a.user_id, a.institution, a.type, ta.balance
		FROM 
			Accounts as a
		JOIN 
			(SELECT account_id, balance FROM Transactions ORDER BY t DESC) ta
		ON a.id = ta.account_id 
		WHERE a.user_id = ?
        GROUP BY a.id;
		";

		$stmt = $this->connection->prepare($str);
		$stmt->execute([$user_id]);

		$accounts = $stmt->fetchAll(PDO::FETCH_OBJ);
		if (!$accounts)
			return [];

		foreach ($accounts as $a)
		{
			$a->id = (int) $a->id;
			$a->user_id = (int) $a->user_id;
			$a->balance = (double) $a->balance;

			$a->type = rtrim(DBManager::decrypt($a->type));
			$a->institution = rtrim(DBManager::decrypt($a->institution));
		}
		return $accounts;
	}

	/**
	 * Get a single account (institution, type) with current balance owned by specified user 
	 */
	public function getAccountWithBalance($user_id, $institution, $type)
	{
		$str = "
		SELECT id, user_id, institution, type, IFNULL(ta.balance, 0) AS balance
		FROM 
			Accounts
		LEFT JOIN 
			(SELECT account_id, balance, t FROM Transactions ORDER BY t DESC limit 1) ta
		ON Accounts.id = ta.account_id 
		WHERE (Accounts.user_id, Accounts.institution, Accounts.type) = (?, ?, ?);
		";

		$stmt = $this->connection->prepare($str);
		$stmt->execute([$user_id, $institution, $type]);

		$a = $stmt->fetch(PDO::FETCH_OBJ);
		if (!$a)
			return null;
		
		//decrypt
		$a->id = (int) $a->id;
		$a->user_id = (int) $a->user_id;
		$a->balance = (double) $a->balance;

		$a->type = rtrim(DBManager::decrypt($a->type));
		$a->institution = rtrim(DBManager::decrypt($a->institution));

		return $a;
	}
}
