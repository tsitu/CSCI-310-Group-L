<?php

require_once "../model/DBConnection.php";
require_once "../model/Transaction.php";

/**
 * Singleton TransactionManager provides DB queries related to a user's transactions.
 * Uses connection from singleton DBManager to execute queries.
 */
class TransactionDBManager
{
	private static $instance;

	private $connection;

	/**
	 * Returns singleton instnace of TransactionManager
	 *
	 * @return singleton instance of TransactionManager
	 */
	public static function getInstance()
	{
		if(null === static::$instance)
			static::$instance = new static();

		return static::$instance;
	}

	/**
	 * Protected constructor to prevent new instances.
	 * Store reference to connection from `DBConnection`
	 */
	private function __construct()
	{
		$this->connection = DBConnection::getConnection();
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
	
}