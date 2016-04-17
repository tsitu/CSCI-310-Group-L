<?php

require_once "../model/DBConnection.php";
require_once "../model/Account.php";


/**
 * Singleton AccountManager provides DB queries related to a user's financial accounts.
 * Uses connection from singleton DBManager to execute queries.
 */
class AccountDBManager
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
	public static function addAccount($institution, $type, $user_id) {
		$stmt = $this->connection->prepare("INSERT INTO Accounts (institution, type, user_id) VALUES (:institution, :type, :user_id)");

		$stmt->bindParam(':institution', DBManager::encrypt($institution));
		$stmt->bindParam(':type', DBManager::encrypt($type));
		$stmt->bindParam(':user_id', DBManager::encrypt($user_id));

		$stmt->execute();
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
		$stmt = DBManager::getConnection()->prepare("UPDATE Accounts SET institution=:institution, type=:type WHERE id=:id");

		$stmt->bindParam(':type', DBManager::encrypt($new_type));
		$stmt->bindParam(':institution', DBManager::encrypt($new_institution));
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
		$stmt->bindParam(':user_id', DBManager::encrypt($user_id));
		$stmt->execute();

		$ret = array();
		while($row = $stmt->fetch()) {
			$ret[] = new Account(DBManager::decrypt($row['id']), DBManager::decrypt($row['institution']), DBManager::decrypt($row['type']), DBManager::decrypt($row['user_id']));
		}

		return $ret;
	}

	//Returns an account matching parameters. Returns null if not found.
	/**
	 * Returns an `Account` instance with the given name owned by the specified user.
	 *
	 * @param $institution  - string of account's institution (ex. Bank of America)
	 * @param $type 		- string of account type (ex. Credit, Debit)
	 * @param $user_id 		- user_id of user this account belongs to
	 * @return new `Account` instance matching given params
	 */
	public function getAccountByInfo($institution, $type, $user_id) {
		$stmt = $this->connection->prepare("SELECT * FROM Accounts WHERE user_id=:user_id AND type=:type AND institution=:institution");

		$stmt->bindParam(':user_id', DBManager::encrypt($user_id));
		$stmt->bindParam(':type', DBManager::encrypt($type));
		$stmt->bindParam(':institution', DBManager::encrypt($institution));

		$stmt->execute();
		$count = $stmt->rowCount();

		if($count == 0) {
			return null;
		}

		$row = $stmt->fetch();
		return new Account(DBManager::decrypt($row['id']), DBManager::decrypt(row['institution']), DBManager::decrypt($row['type']), DBManager::decrypt($row['user_id']));
	}
}
