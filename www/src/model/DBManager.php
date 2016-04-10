<?php

/* CONST */
const SERVER = "sql3.freemysqlhosting.net";
const USERNAME = "sql3112429";
const PASSWORD = "NqxhS6d8yQ";
const DB = "sql3112429";

/**
 * Database manager class.
 * When initialized, connects to the MySQL server specified by constants.
 * Provides query methods that return data as php objects.
 */
class DBManager
{
	private $connection;

	/**
	 * Initialize DBManager object and connect to MySQL specified by constants.
	 * @throws exception if connection fails
	 */
	function __construct()
	{
		$this->connection = new mysqli(SERVER, USERNAME, PASSWORD, DB);

		if ($this->connection->connect_error)
			throw new Exception($this->connection->connect_error);
	}

	/**
	 * Close connection when destructing
	 */
	function __destruct()
	{
		$this->connection->close();
	}


	/* Queries */
	/**
	 * Return user id with given username and password.
	 *
	 * @param username - email of user to get
	 * @param password - of user to get
	 * @return user_id if valid; null otherwise.
	 * @throws exception when statement fails
	 */
	function getUser($username, $password)
	{
		$statement = $this->connection->prepare("SELECT id FROM users WHERE (email = ? AND password = ?)");

		if (!$statement)
			throw new Exception("myslqi::prepare for DBManager::getUser failed");

		$username = htmlentities($username);
		$password = htmlentities($password);

		if (!$statement->bind_param('ss', $username, $password))
			throw new Exception("[Error] mysqli_stmt::bind_param in DBManager::getUser");

		if (!$statement->execute())
			throw new Exception("[Error] mysqli_stmt::execute in DBManager::getUser");

		$statement->bind_result($user_id);
		$success = $statement->fetch();
		if (is_null($success))
			return null;

		if (!$success)
			throw new Exception("[Error] mysqli_stmt:fetch in DBManager::getUser");

		return $user_id;
	}

	
}