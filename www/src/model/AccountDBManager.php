<?php


require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/Account.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/DBManager.php";

class AccountDBManager extends DBManager {

	/**
	 * Fetch all distinct financial accounts of specified user, including each account's balance.
	 * Balance is retrieved by doing left join with Transactions table.
	 *
	 * @param user_id - unique id of user to get accounts of
	 * @return array of Account objects for user
	 * @throws exception when MySQL statement fails
	 */
	public function getAccountsWithBalance($user_id)
	{
		$str = "
		SELECT Accounts.*, IFNULL(t.balance, 0), t.time
		FROM 
			Accounts
		LEFT JOIN 
			(SELECT account_id, balance, time FROM Transactions ORDER BY time DESC limit 1) t
		ON Accounts.id = t.account_id 
		WHERE Accounts.user_id = 1;
		";

		$statement = $this->connection->prepare($str);
		$statement->execute( [$user_id] );
		$accounts = $statement->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Account", ["_id", "_user_id", "_institution", "_type", "_balance"]);

		foreach ($accounts as $a)
			$a->fixTypes();

		return $accounts;
	}

}