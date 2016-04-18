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
}