<?php

//require_once "Account.php";
require_once "Transaction.php";


/* CONST */
const HOST_NAME = "sql3.freemysqlhosting.net";
const HOST_IP 	= "54.215.148.52";
const USERNAME  = "sql3114710";
const PASSWORD  = "3zaKKK36kN";
const DB 		= "sql3114710";
const PORT 		= "3306";

/**
 * Database manager class.
 * When initialized, connects to the MySQL server specified by constants.
 * Provides query methods that return data as php objects.
 */
class DBManager
{
	protected $connection;

	/**
	 * Initialize DBManager object and connect to MySQL specified by constants.
	 * @throws exception if connection fails
	 */
	function __construct()
	{
		$dsn = "mysql:host=54.215.148.52;dbname=sql3114710";
		$this->connection = new PDO($dsn, USERNAME, PASSWORD);

		//throw exceptions
		$this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$this->connection->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
		$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	/**
	 * Close connection when destructing
	 */
	function __destruct()
	{
		$this->connection = null;
	}



	/* --- QUERIES --- */
	/**
	 * Return user id with given username and password.
	 *
	 * @param username - email of user to get
	 * @param password - of user to get
	 * @return user_id if valid; null otherwise.
	 * @throws exception when statement fails
	 */
	public function getUser($username, $password)
	{
		$statement = $connection->prepare("SELECT * FROM Users WHERE email = ?");
		$statement->bindParam('s', $username);
		$statement->execute();

		$retArr = $statement->fetch();

		if(password_verify($password, $retArr['password']))
			return $statement->fetch()['id'];
		else
			return null;
	}

	
	
}