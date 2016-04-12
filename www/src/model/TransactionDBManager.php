<?php


require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/Transaction.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/DBManager.php";

class TransactionDBManager extends DBManager {

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