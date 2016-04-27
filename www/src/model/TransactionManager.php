<?php

require_once __DIR__ . '/DBManager.php';
require_once __DIR__ . '/Transaction.php';

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
	 * Add the given transaction object to specified $user
	 */
	public function addTransaction($user_id, $t)
	{
		$str1 = "
		INSERT IGNORE INTO Transactions(user_id, account_id, t, merchant, category, amount, balance)
		SELECT
			:user_id, 
			Accounts.id, 
			:time,
			:merchant,
			:category,
		    :amount,
			:amount2 + IFNULL((SELECT balance FROM Transactions WHERE account_id = Accounts.id ORDER BY t DESC LIMIT 1), 0)
		FROM Accounts
		WHERE (institution, type) = (:institution, :type);
		";

		$str2 = "
		UPDATE Transactions
		SET
			balance = balance + :amount
		WHERE t > :time AND account_id = (SELECT id FROM Accounts WHERE institution = :institution AND type = :type);
		";

		//encrypt
		$t->institution = DBManager::encrypt($t->institution);
		$t->type = DBManager::encrypt($t->type);
		$t->category = DBManager::encrypt($t->category);
		$t->merchant = DBManager::encrypt($t->merchant);
		$a = $t->amount;

		//prepare
		$insert = $this->connection->prepare($str1);
		$update = $this->connection->prepare($str2);

		//execute
		$insert->execute([
			':user_id' 	=> $user_id, 
			':institution' 	=> $t->institution, 
			':type' 		=> $t->type, 
			':time' 		=> $t->time,
			':merchant' 	=> $t->merchant,
			':category' 	=> $t->category, 
			':amount' 		=> $a,
			':amount2'		=> $a
		]);

		$update->execute([
			':amount' 		=> $a,
			':time' 		=> $t->time,
			':institution' 	=> $t->institution,
			':type' 		=> $t->type,
		]);

		return $this->connection->lastInsertId();
	}

	/**
	 * Get transaction list for specified user between [`$beg`, `$end`] datetime objects
	 */
	public function getListForUserBetween($user_id, $beg, $end)
	{
		$str = "
		SELECT ta.id, ta.user_id, a.id as account_id, a.institution, a.type, ta.t as time, ta.merchant, ta.category, ta.amount, ta.balance 
		FROM Transactions as ta JOIN Accounts as a
		ON a.id = ta.account_id
		WHERE ta.user_id = :uid AND (ta.t BETWEEN :beg AND :end) 
		ORDER BY ta.t DESC;
		";


		//format
		$beg = DBManager::sqlDatetime($beg);
		$end = DBManager::sqlDatetime($end);

		$stmt = $this->connection->prepare($str);
		$stmt->execute([
			':uid' => $user_id,
			':beg' => $beg, 
			':end' => $end
		]);

		$list = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Transaction');
		foreach ($list as $a)
			$a->fixTypes();

		return $list;
	}

	/**
	 * Get transaction list for specified account between [`$beg`, `$end`] datetime objects
	 */
	public function getListForAccountBetween($account_id, $beg, $end)
	{
		$str = "
		SELECT ta.id, a.user_id, a.id as account_id, a.institution, a.type, ta.t as time, ta.merchant, ta.category, ta.amount, ta.balance 
		FROM Transactions as ta JOIN Accounts as a
		ON a.id = ta.account_id
		WHERE a.id = :id AND (ta.t BETWEEN :beg AND :end) 
		ORDER BY ta.t DESC;
		";

		$stmt = $this->connection->prepare($str);
		$stmt->execute([':id' => $account_id, 
						':beg' => DBManager::sqlDatetime($beg),
						':end' => DBManager::sqlDatetime($end)
						]);

		$list = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Transaction');
		if (!$list)
			return [];

		foreach ($list as $a)
			$a->fixTypes();

		return $list;
	}

	/**
	 * Get transaction list for specified account NOT between [`$beg`, `$end`] datetime objects
	 */
	public function getListForAccountNotBetween($account_id, $beg, $end)
	{
		$str = "
		SELECT ta.id, a.user_id, a.id as account_id, a.institution, a.type, ta.t as time, ta.merchant, ta.category, ta.amount, ta.balance 
		FROM Transactions as ta JOIN Accounts as a
		ON a.id = ta.account_id
		WHERE a.id = :id AND (ta.t NOT BETWEEN :beg AND :end) ORDER BY ta.t DESC;
		";

		$stmt = $this->connection->prepare($str);
		$stmt->execute([
			':id' => $account_id, 
			':beg' => DBManager::sqlDatetime($beg),
			':end' => DBManager::sqlDatetime($end)
		]);

		$list = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Transaction');
		if (!$list)
			return [];

		foreach ($list as $a)
			$a->fixTypes();

		return $list;
	}
}





