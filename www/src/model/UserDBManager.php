<?php

require_once __DIR__ . "/DBManager.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/User.php";

class UserDBManager {

	private $db;
	protected static $userdb;

	private function __construct() {
		$db = DBManager::getConnection();
	}

	public static function getUserDBManager() {
		if(null === static::$userdb) {
			static::$userdb = new static();
		}

		return static::$userdb;
	}

	//Adds user to database with given parameters.
	public function addUser($email, $raw_password) {
		$stmt = DBManager::getConnection()->prepare("INSERT INTO Users (email, password) VALUES (:email, :hashed_password)");

		$stmt->bindParam(':email', DBManager::encrypt($email));
		$stmt->bindParam(':hashed_password', password_hash($raw_password, PASSWORD_DEFAULT));

		$stmt->execute(); 	//safe from SQL injection
	}

	//Deletes account from database matching $id.
	public function deleteUser($id) {
		$stmt = DBManager::getConnection()->prepare("DELETE FROM Users WHERE id = :id");

		$stmt->bindParam(':id', $id);

		$stmt->execute();	//safe from SQL injection
	}

	//Returns id matching given paramters
	public function getUserId($email, $raw_password) {
		$stmt = DBManager::getConnection()->prepare("SELECT * FROM Users WHERE email = :email");

		$stmt->bindParam(":email", DBManager::encrypt($email));
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if(password_verify($raw_password, $row['password']))
			return $row['id'];

	}

	//returns user object matching id.
	public function getUser($id) {
		$stmt = DBManager::getConnection()->prepare("SELECT * FROM Users WHERE id = :id");

		$stmt->bindParam(':id', $id);

		$stmt->execute();	//safe from SQL injection

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return new User($row['id'], DBManager::decrypt($row['email']), $row['password']);
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