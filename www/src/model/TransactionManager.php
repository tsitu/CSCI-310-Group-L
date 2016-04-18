<?php

require_once "DBManager.php";
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
	 * Store reference to connection from `DBManager`
	 */
	private function __construct()
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

		$a = $t->amount;

		$stmt = $this->connection->prepare($str);
		$stmt->execute([':user_id' => $user_id, 
						':institution' => $t->institution, 
						':type' => $t->type, 
						':time' => $t->time,
						':descriptor' => $t->descriptor,
						':category' => $t->category, 
						':amount' => $a,
						':amount2' => $a
						]);

		return $this->connection->lastInsertId();
	}

	/**
	 *
	 */
	public function getListForAccountBetween($account_id, $beg, $end)
	{
		$str = "
		SELECT ta.id, a.id as account_id, a.institution, a.type, ta.t, ta.descriptor, ta.category, ta.amount, ta.balance 
		FROM Transactions as ta
		JOIN
		Accounts as a
		ON a.id = ta.account_id
		WHERE a.id = :id AND (ta.t BETWEEN :beg AND :end) ORDER BY ta.t DESC;
		";

		$stmt = $this->connection->prepare($str);
		$stmt->execute([':id' => $account_id, 
						':beg' => DBManager::sqlDatetime($beg),
						':end' => DBManager::sqlDatetime($end)
						]);

		$list = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Transaction',
								['_id', '_user_id', '_account_id', '_t', '_amount', '_category', '_descriptor', '_balance']);
		foreach ($list as $a)
			$a->fixTypes();

		return $list;
	}
}





