<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/DBManager.php";

class TransactionDBManager {

	private $db;
	protected static $transactiondb;

	private function __construct() {
		$db = DBManager::getConnection();
	}

	public static function getUserDBManager() {
		if(null === static::$transactiondb) {
			static::$transactiondb = new static();
		}

		return static::$transactiondb;
	}
}


















// 	/*
// 	 * Fetch all distinct financial accounts of specified user, including each account's balance.
// 	 * Balance is retrieved by doing left join with Transactions table.
// 	 *
// 	 * @param user_id - unique id of user to get accounts of
// 	 * @return array of Account objects for user
// 	 * @throws exception when MySQL statement fails
// 	 */
// 	public function getAccountsWithBalance($user_id)
// 	{
// 		$str = "
// 		SELECT Accounts.*, IFNULL(t.balance, 0) AS balance, t.time
// 		FROM 
// 			Accounts
// 		LEFT JOIN 
// 			(SELECT account_id, balance, time FROM Transactions ORDER BY time DESC limit 1) t
// 		ON Accounts.id = t.account_id 
// 		WHERE Accounts.user_id = ?;
// 		";

// 		$statement = $this->connection->prepare($str);
// 		$statement->execute( [$user_id] );
// 		$accounts = $statement->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Account", ["_id", "_user_id", "_institution", "_type", "_balance"]);

// 		foreach ($accounts as $a)
// 			$a->fixTypes();

// 		return $accounts;
// 	}

// 	//Adds Account to database.
// 	public function addToDatabase($account) {
// 		$stmt = $this->connection->prepare("INSERT INTO Accounts (institution, type, user_id) VALUES (:institution, :type, :user_id)");
// 		$stmt->bindParam(':institution', $account->institution);
// 		$stmt->bindParam(':value', $account->type);
// 		$stmt->bindParam(':user_id', $account->user_id);
// 		$stmt->execute(); 	//safe from SQL injection
// 	}

// 	//Removes Account from database.
// 	public function removeFromDatabase($account) {
// 		$stmt = $this->connection->prepare("DELETE FROM Accounts WHERE id = :id");
// 		$stmt->bindParam(':id', $account->institution);
// 		$stmt->execute();	//safe from SQL injection
// 	}

// 	//Returns an Account matching the id.
// 	public function getAccount($type, $institution, $user_id) {
// 		$stmt = $this->connection->prepare("SELECT * FROM Accounts WHERE institution=:institution AND type=:type");
// 		$stmt->bindParam(':institution', $institution);
// 		$stmt->bindParam(':type', $type);
// 		$stmt->execute();

// 		$row = $stmt->fetch();

// 		echo "got it... <br> ";
// 		print_r($row);
// 	}

// }