<?php

class TransactionDBManager extends DBManager {

	//Adds Transaction to database. Takes care of all fields.
	public function addToDatabase($obj) {

		//1. find current balance for accountid, userid.
		//2. deduct.
		//3. add with timestamp.
		
		//INSERT INTO `sql3114710`.`Transactions` (`id`, `user_id`, `account_id`, `time`, `category`, `descriptor`, `amount`, `balance`) VALUES (NULL, '1', '2', '2016-04-13 00:00:00', 'credit', 'test transaction', '-10.01', '10.00');
		echo "Added this transaction to the database.<br>";
	}

	/**
	 * Fetch the latest `limit` transactions for specified user across all accounts.
	 * Note: does not return the account balance snapshot
	 *
	 * @param $user_id - unique id of user to transactions of
	 * @param $limit = 30 - number of transactions to get
	 */
	public function getTransactionsForUser($user_id, $limit = 30)
	{
		$str = "
		SELECT id, account_id, time, amount, category, descriptor FROM Transactions 
		WHERE user_id = ?
		ORDER BY time DESC 
		LIMIT ?;
		";

		$statement = $this->connection->prepare($str);
		$statement->execute( [$user_id, $limit] );
		$transactions = $statement->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Transaction", ["_id", "_user_id", "_account_id", "_time", "_amount", "_category", "_descriptor"]);

		foreach ($transactions as $t)
			$t->fixTypes();

		return $transactions;
	}

}

