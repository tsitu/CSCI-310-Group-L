<?php

require_once "DBManager.php";

/**
 * Singleton UserManager provides DB queries related to users.
 * Uses connection from singleton DBManager to execute queries.
 */
class UserManager
{
	private static $instance;

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
	 * Protected constructor to prevent new instance.
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
	 * Verify that user with given `$username` and `$password` exist, and return user id if found.
	 *
	 * @param $username - string email
	 * @param $password - string password
	 * @return user_id if valid, otherwise null
	 */
	public function verify($username, $password)
	{
		$stmt = $this->connection->prepare("SELECT id FROM Users WHERE (email = ? AND password = ?)");
		$stmt->execute([$username, $password]);

		$id = $stmt->fetch();
		if (!$id)
			return null;

		return $id['id'];
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

	//returns user object matching id.
	public function getUser($id) {
		$stmt = DBManager::getConnection()->prepare("SELECT * FROM Users WHERE id = :id");

		$stmt->bindParam(':id', $id);

		$stmt->execute();	//safe from SQL injection

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return new User($row['id'], DBManager::decrypt($row['email']), $row['password']);
	}
}