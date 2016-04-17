<?php

require_once "DBConnection.php";
require_once "Transaction.php";

/**
 * Singleton TransactionManager provides DB queries related to a user's transactions.
 * Uses connection from singleton DBManager to execute queries.
 */
class TransactionManager
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
	/**
	 *
	 */
	public function addTransaction($user_id, $t)
	{
		$str = "
		INSERT IGNORE INTO Transactions(user_id, account_id, t, descriptor, category, amount, balance)
		SELECT
			:user_id, 
			Accounts.id, 
			:time,
			:descriptor,
			:category,
		    :amount,
			:amount2 + IFNULL((SELECT balance FROM Transactions WHERE account_id = Accounts.id ORDER BY t DESC LIMIT 1), 0)
		FROM Accounts
		WHERE (institution, type) = (:institution, :type);
		";

		$stmt = $this->connection->prepare($str);
		$stmt->execute([':user_id' => $user_id, ':institution' => $t->institution, ':type' => $t->type, 
						':time' => $t->time, ':descriptor' => $t->descriptor, ':category' => $t->category, 
						':amount' => $t->amount, ':amount2' => $t->amount]);

		return $this->connection->lastInsertId();
	}
}





