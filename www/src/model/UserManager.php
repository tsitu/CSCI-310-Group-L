<?php

require_once __DIR__ . '/DBManager.php';

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
		$str = "SELECT id, password FROM Users WHERE email = ?";

		//encrypt
		$username = DBManager::encrypt($username);

		// echo $username . '<br>';
		// echo $password . '<br>';

		$stmt = $this->connection->prepare($str);
		$stmt->execute([$username]);

		$row = $stmt->fetch(PDO::FETCH_OBJ);
		if (!$row)
			return null;

		if ( !password_verify($password, $row->password) )
			return null;

		return $row->id;
	}

	/** 
	 * Adds user to database with given parameters.
	 */
	public function addUser($username, $password)
	{
		$str = "INSERT INTO Users (email, password) VALUES (:username, :password)";

		//encrypt
		$username = DBManager::encrypt($username);
		$password = password_hash($password, PASSWORD_DEFAULT);

		$stmt = $this->connection->prepare($str);
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':password', $password);
		$stmt->execute();
	}

	/**
	 * Delete specified user
	 */
	public function deleteUser($id)
	{
		$str = "DELETE FROM Users WHERE id = :id";

		$stmt = $this->connection->prepare($str);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
	}

	/**
	 * Get specified user
	 */
	public function getUser($id)
	{
		$str = "SELECT id, email, password FROM Users WHERE id = :id";

		$stmt = $this->connection->prepare($str);
		$stmt->bindParam(':id', $id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_OBJ);
		if (!$row)
			return null;

		//decrypt
		$row->email = DBManager::decrypt($row->email);

		return new User($row->id, $row->email, $row->password);
	}

	//account_type = {'asset', 'liability', 'net worth'}
	//time parameters are in datetime format or YYYY-MM-DDThh:mm:ss.nnn format
	//DOES NOT account for negatives...so if savings balance is negative, it's not counted in liabilities or net worth...
	public function getAssetHistory($account_type, $user_id, $startTime, $endTime)
	{
		//Assets = total savings
		//Liabilities = total credit, loans
		//Net Worth = assets - liabilities

		$transactions = array();
		$times = array();	//all transaction datetimes in range startTime to endTime
		$unique_accounts = array();
		$snapshot = array(); //k->datetime, v->totalAmount
		$sum = 0;

		//1. find all transaction occurances after startTime and before endTime
		if($account_type == 'asset') 
			$str = 
			"SELECT * FROM Transactions INNER JOIN Accounts ON Transactions.account_id=Accounts.id
			WHERE Transactions.t >= :startTime AND Transactions.t <= :endTime AND Transactions.user_id = :user_id AND Accounts.type = :savings";
		else if($account_type == 'liability')
			$str = 
			"SELECT * FROM Transactions INNER JOIN Accounts ON Transactions.account_id=Accounts.id
			WHERE Transactions.t >= :startTime AND Transactions.t <= :endTime AND Transactions.user_id = :user_id AND (Accounts.type = :credit OR Accounts.type = :loan)";
		else if($account_type == 'net worth')
			$str = 
			"SELECT * FROM Transactions INNER JOIN Accounts ON Transactions.account_id=Accounts.id
			WHERE Transactions.t >= :startTime AND Transactions.t <= :endTime AND Transactions.user_id = :user_id AND (Accounts.type = :savings OR Accounts.type = :credit OR Accounts.type = :loan)";
		else
		{
			echo '$account_type parameter not formatted correctly.<br>';
			return null;
		}


		$stmt = $this->connection->prepare($str);
		$stmt->bindParam(':startTime', $startTime);
		$stmt->bindParam(':endTime', $endTime);
		$stmt->bindParam(':user_id', $user_id);
		if($account_type == 'asset') 
			$stmt->bindParam(':savings', DBManager::encrypt('savings'));
		else if($account_type == 'liability')
		{
			$stmt->bindParam(':credit', DBManager::encrypt('credit'));
			$stmt->bindParam(':loan', DBManager::encrypt('loan'));
		}
		else if($account_type == 'net worth')
		{
			$stmt->bindParam(':savings', DBManager::encrypt('savings'));
			$stmt->bindParam(':credit', DBManager::encrypt('credit'));
			$stmt->bindParam(':loan', DBManager::encrypt('loan'));
		}
		$stmt->execute();

		$rows = $stmt->fetchAll();
		
		//sum up the amounts, and at each timestamp, add a snapshot into the array.
		foreach($rows as $row)
		{
			if(in_array($row['account_id'], $unique_accounts))
			{
				$unique_accounts[] = $row['account_id'];
				$sum += $row['balance'];
			}
			else 
			{
		 	$sum += $row['amount'];
		 	$snapshot[$row['t']] = $sum;
			}
			
			//echo $row['t'] . " - " $row['amount'] . "<br>";
		}

		 return $snapshot;

		//returns array of pairs, with each element containing timestamp, balance.
	}
}