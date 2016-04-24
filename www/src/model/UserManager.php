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
		$str = "SELECT id, password FROM Users WHERE email = ?";

		//encrypt
		$username = DBManager::encrypt($username);

		// echo $username . '<br>';
		// echo $password . '<br>';

		$stmt = $this->connection->prepare($str);
		$stmt->execute([$username]);

		$row = $stmt->fetch(PDO::FETCH_OBJ);
		if (!$row)
			return null;

		if ( !password_verify($password, $row->password) )
			return null;

		return $row->id;
	}

	/** 
	 * Adds user to database with given parameters.
	 */
	public function addUser($username, $password)
	{
		$str = "INSERT INTO Users (email, password) VALUES (:username, :password)";

		//encrypt
		$username = DBManager::encrypt($username);
		$password = password_hash($password, PASSWORD_DEFAULT);

		$stmt = $this->connection->prepare($str);
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':password', $password);
		$stmt->execute();
	}

	/**
	 * Delete specified user
	 */
	public function deleteUser($id)
	{
		$str = "DELETE FROM Users WHERE id = :id";

		$stmt = $this->connection->prepare($str);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
	}

	/**
	 * Get specified user
	 */
	public function getUser($id)
	{
		$str = "SELECT id, email, password FROM Users WHERE id = :id";

		$stmt = $this->connection->prepare($str);
		$stmt->bindParam(':id', $id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_OBJ);
		if (!$row)
			return null;

		//decrypt
		$row->email = DBManager::decrypt($row->email);

		return new User($row->id, $row->email, $row->password);
	}
}