<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/DBManager.php";


class User
{
	public $id;
	public $email;
	public $hashed_password;

	private $connection;
	private $raw_password;

	//Omitting id parameter will automatically generate one.
	function __construct($email, $raw_password, $id = -1) {

		//immediately hash+salt the password
		$hashed_password = $this->hashPassword($raw_password);

		$this->connection = DBManager::getConnection();

		$this->id = $id;
		$this->email = $email;
		$this->hashed_password = $hashed_password;

		//id generation step
		if($id === -1) {
			$this->raw_password = $raw_password;
			$this->setId();
		} else {
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

	//Adds $this to database.
	public function addToDatabase() {
		$stmt = $this->connection->prepare("INSERT INTO Users (email, password) VALUES (:email, :password)");

		//encryption step...
		$stmt->bindParam(':email', DBManager::encrypt($this->email));
		$stmt->bindParam(':password', DBManager::encrypt($this->hashed_password));

		$stmt->execute(); 	//safe from SQL injection
	}

	//Removes $this from database.
	public function removeFromDatabase() {
		$stmt = $this->connection->prepare("DELETE FROM Users WHERE id = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();	//safe from SQL injection
	}

	//Privately used in constructor. Sets id for this.
	private function setId() {
		$stmt = $this->connection->prepare("SELECT * FROM Users WHERE email=:email");
		$stmt->bindParam(':email', DBManager::encrypt($this->email));
		$stmt->execute();

		$count = $stmt->rowCount(); //number of db entries with email=email

		if($count === 0) {
			$this->addToDatabase();
			$this->setId();
		} 
		else {
			$row = $stmt->fetch();
			//verifies that the password is correct for the email.

			if (password_verify($this->raw_password, DBManager::decrypt($row['password']))) {
				$this->id = $row['id'];
				echo "debug: created account with id: " . $this->id . "<br>";
			} 
			else {
				//-1 is set if email exists but password is incorrect.
				$this->id = -1;
				echo "debug: -1<br>";
			}
		}
	}

	//Is email+password valid? Returns true or false.
	public static function validateUser($email, $raw_password)
	{

		$connection = DBManager::getConnection();

		$statement = $connection->prepare("SELECT * FROM Users WHERE email = :email");
		$statement->bindParam(':email', DBManager::encrypt($email));
		$statement->execute();

		$retArr = $statement->fetch();

		echo $statement->rowCount() . " result(s) <br>";





		echo $raw_password . " -----> " . DBManager::decrypt($retArr['password']) . "<br>";

		echo "indb: " . $retArr['password'] . "<br>";


		$hash = DBManager::decrypt($retArr['password']);


		var_dump(

			
			password_verify(

				$raw_password, $hash)
			

			);



		echo "<br>";

		if(password_verify($raw_password, DBManager::decrypt($retArr['password'])))
			return true;
		else
			return false;
	}

	//Wrapper for password_hash().
	public static function hashPassword($raw_password) {
		return password_hash($raw_password, PASSWORD_DEFAULT);
	}
}