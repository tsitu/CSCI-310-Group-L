<?php

require_once __DIR__ .  "/DBManager.php";
require_once __DIR__ . "/Account.php";

class AccountDBManager {

	protected static $accountdb;

	function __construct() {
	}

	public static function getAccountDBManager() {
		if(null === static::$accountdb) {
			static::$accountdb = new static();
		}

		return static::$accountdb;
	}

	//Adds account to database with given parameters.
<<<<<<< HEAD
	public static function addAccount($institution, $type, $user_id) {
=======
	public function addAccount($institution, $type, $user_id) {
>>>>>>> steve
		$stmt = DBManager::getConnection()->prepare("INSERT INTO Accounts (institution, type, user_id) VALUES (:institution, :type, :user_id)");

		$stmt->bindParam(':institution', DBManager::encrypt($institution));
		$stmt->bindParam(':type', DBManager::encrypt($type));
		$stmt->bindParam(':user_id', $user_id);

		$stmt->execute(); 	//safe from SQL injection
	}

	//Deletes account from database matching $id.
	public function deleteAccount($id) {
		$stmt = DBManager::getConnection()->prepare("DELETE FROM Accounts WHERE id = :id");

		$stmt->bindParam(':id', $id);

		$stmt->execute();	//safe from SQL injection
	}

	//Updates institution and type of account matching $id.
	public function updateAccount($id, $new_institution, $new_type) {
		$stmt = DBManager::getConnection()->prepare("UPDATE Accounts SET institution=:institution, type=:type WHERE id=:id");

		$stmt->bindParam(':type', DBManager::encrypt($new_type));
		$stmt->bindParam(':institution', DBManager::encrypt($new_institution));
		$stmt->bindParam(':id', $id);

		$stmt->execute();	//safe from SQL injection
	}

	//Returns an array containing all accounts owned by user_id.
	public function getAllAccounts($user_id) {
		$ret = array();

		$stmt = DBManager::getConnection()->prepare("SELECT * FROM Accounts WHERE user_id=:user_id");

		$stmt->bindParam(':user_id', $user_id);

		$stmt->execute();

		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach ($rows as $row) {
		   $ret[] = new Account($row['id'], DBManager::decrypt($row['institution']), DBManager::decrypt($row['type']), $row['user_id']);
		}

		return $ret;
	}

	//Returns an account matching parameters. Returns null if not found.
	public function getAccountByInfo($institution, $type, $user_id) {
		$stmt = DBManager::getConnection()->prepare("SELECT * FROM Accounts WHERE user_id=:user_id AND type=:type AND institution=:institution");

		$stmt->bindParam(':user_id', $user_id);
		$stmt->bindParam(':type', DBManager::encrypt($type));
		$stmt->bindParam(':institution', DBManager::encrypt($institution));

		$stmt->execute();
		$count = $stmt->rowCount();

		if($count == 0) {
			return null;
		}

		$row = $stmt->fetch();
		return new Account($row['id'], DBManager::decrypt(row['institution']), DBManager::decrypt($row['type']), $row['user_id']);
	}

}
