<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/DBConnection.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/Account.php";


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
	 * Store reference to connection from `DBConnection`
	 */
	protected function __construct()
	{
		$this->connection = DBConnection::getConnection();
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
	public function addAccount($institution, $type, $user_id) {
		$stmt = $this->connection->prepare("INSERT IGNORE INTO Accounts (institution, type, user_id) VALUES (:institution, :type, :user_id)");

		$stmt->bindParam(':institution', $institution);
		$stmt->bindParam(':type', $type);
		$stmt->bindParam(':user_id', $user_id);

		$stmt->execute();

		return $this->connection->lastInsertId();
	}

	/**
	 * Delets the financial account with given `$id`.
	 *
	 * @param $id - unique id of account to delete.
	 */
	public function deleteAccount($id) {
		$stmt = $this->connection->prepare("DELETE FROM Accounts WHERE id = :id");
		$stmt->bindParam(':id', $account->id);
		$stmt->execute();	//safe from SQL injection
	}

	//Updates institution and type of account matching $id.
	/**
	 * 
	 */
	public function updateAccount($id, $new_institution, $new_type) {
		$stmt = $this->connection->prepare("UPDATE Accounts SET institution=:institution, type=:type WHERE id=:id");

		$stmt->bindParam(':type', $new_type);
		$stmt->bindParam(':institution', $new_institution);
		$stmt->bindParam(':id', $id);

		$stmt->execute();	//safe from SQL injection
	}

	//Returns an array containing all accounts owned by user_id.
	/**
	 * Return array of all accounts owned by specified user.
	 *
	 * @param $user_id - unique id of user to get accounts for
	 * @return array of accounts owned by user
	 */
	public function getAllAccounts($user_id) {
		$stmt = $this->connection->prepare("SELECT * FROM Accounts WHERE user_id=:user_id");
		$stmt->bindParam(':user_id', $user_id);
		$stmt->execute();

		$ret = array();
		while($row = $stmt->fetch()) {
			$ret[] = new Account($row['id'], $row['institution'], $row['type'], $row['user_id']);
		}

		return $ret;
	}

	/**
	 * Returns an `Account` instance with the given name owned by the specified user.
	 *
	 * @param $institution  - string of account's institution (ex. Bank of America)
	 * @param $type 		- string of account type (ex. Credit, Debit)
	 * @param $user_id 		- user_id of user this account belongs to
	 * @return new `Account` instance if found, null otherwise
	 */
	public function getAccountByInfo($institution, $type, $user_id) {
		$stmt = $this->connection->prepare("SELECT * FROM Accounts WHERE user_id=:user_id AND type=:type AND institution=:institution");

		$stmt->bindParam(':user_id', $user_id);
		$stmt->bindParam(':type', $type);
		$stmt->bindParam(':institution', $institution);

		$stmt->execute();
		$row = $stmt->fetch();
		if (!$row)
			return null;

		return new Account($row['id'], $row['institution'], $row['type'], $row['user_id']);
	}

	/**
	 *
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
		}

		return $accounts;
	}

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
		
		$a->id = (int) $a->id;
		$a->user_id = (int) $a->user_id;
		$a->balance = (double) $a->balance;

		return $a;
	}
}
