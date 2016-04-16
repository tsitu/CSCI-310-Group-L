<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/DBManager.php";


class User extends DBManager
{
	public $id;
	public $email;
	public $hashed_password;

	private $connection;

	//If id isn't given, it will set one automatically. $this->id to find it.
	function __construct($email, $raw_password, $id = -1) {
		$hashed_password = $this->hashPassword($raw_password);

		$this->connection = DBManager::getConnection();

		$this->id = $id;
		$this->email = $email;
		$this->hashed_password = $hashed_password;

		if($id === -1) {	//if id was given...
			$this->id = $this->setId();
		} else {	//if id was not given...
			$this->id = $id;
		}
	}

	/**
	 * Ensure numeric fields are the correct type since PDO::fetch() only generates strings
	 */
	public function fixTypes()
	{
		$this->id = (int) $this->id;
	}

	//Adds this to database.
	public function addToDatabase() {
		$stmt = $this->connection->prepare("INSERT INTO Users (email, password) VALUES (:email, :password)");
		$stmt->bindParam(':email', $this->email);
		$stmt->bindParam(':password', $this->hashed_password);
		$stmt->execute(); 	//safe from SQL injection
	}

	//Removes this from database.
	public function removeFromDatabase() {
		$stmt = $this->connection->prepare("DELETE FROM Users WHERE id = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();	//safe from SQL injection
	}

	//Privately used in constructor. Sets id for this.
	private function setId() {
		$stmt = $this->connection->prepare("SELECT * FROM Users WHERE email=:email AND password=:password");
		$stmt->bindParam(':email', $this->email);
		$stmt->bindParam(':password', $this->hashed_password);
		$stmt->execute();
		$count = $stmt->rowCount();

		if($count === 0) {	//account doesn't exist...
			//echo " -created new account.";
			$this->addToDatabase();
			$this->setId();
		} else {	//account exists in db already...
			$row = $stmt->fetch();
			$this->id = $row['id'];
			//echo " -used existing account (" . $this->id . ").";
		}
	}

	//Is the info valid? Returns true or false.
	public static function validateUser($email, $raw_password)
	{

		$connection = DBManager::getConnection();

		$statement = $connection->prepare("SELECT * FROM Users WHERE email = :email");
		$statement->bindParam(':email', $email);
		$statement->execute();

		$retArr = $statement->fetch();

		if(password_verify($raw_password, $retArr['password']))
			return true;
		else
			return false;
	}

	//Wrapper for password_hash().
	public static function hashPassword($raw_password) {
		return password_hash($raw_password, PASSWORD_DEFAULT);
	}
}