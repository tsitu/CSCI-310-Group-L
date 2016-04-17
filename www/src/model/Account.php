<?php

//require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/DBManager.php";
require_once "DBManager.php";

/**
 * Account class.
 */
class Account extends DBManager
{
	public $id;
	public $user_id;
	public $institution;
	public $type;

	private $db;
	private $connetion;

	/**
	 * Create a new Account object from given fields.
	 * Used to manually create, PDO::fetch() does not use this constructor.
	 *
	 * If `id` field is not provided, an `id` will be provided automatically.
	 *
	 * @param $_id
	 * @param $_institution
	 * @param $_type
	 */
	function __construct($_user_id, $_institution, $_type, $_id = -1)
	{
		$this->db = new DBManager();
		$this->connection = $this->db->connection;

		$this->user_id = $_user_id;
		$this->institution = $_institution;
		$this->type = $_type;

		if($_id === -1) {	//if id was given...
			$this->id = $this->setId();
		} else {	//if id was not given...
			$this->id = $_id;
		}
	}


	/**
	 * Ensure numeric fields are the correct type since PDO::fetch() only generates strings
	 */
	public function fixTypes()
	{
		$this->id = (int) $this->id;
		$this->user_id = (int) $this->user_id;
		$this->balance = (double) $this->balance;
	}

	public function getAccountsWithBalance($user_id)
	{
		$str = "
		SELECT Accounts.*, IFNULL(t.balance, 0) AS balance, t.time
		FROM 
			Accounts
		LEFT JOIN 
			(SELECT account_id, balance, time FROM Transactions ORDER BY time DESC limit 1) t
		ON Accounts.id = t.account_id 
		WHERE Accounts.user_id = ?;
		";

		$statement = $this->connection->prepare($str);
		$statement->execute( [$user_id] );
		$accounts = $statement->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Account", ["_id", "_user_id", "_institution", "_type", "_balance"]);

		foreach ($accounts as $a)
			$a->fixTypes();

		return $accounts;
	}

	//Adds this to database.
	public function addToDatabase() {
		$stmt = $this->connection->prepare("INSERT INTO Accounts (institution, type, user_id) VALUES (:institution, :type, :user_id)");
		$stmt->bindParam(':institution', $this->institution);
		$stmt->bindParam(':type', $this->type);
		$stmt->bindParam(':user_id', $this->user_id);
		$stmt->execute(); 	//safe from SQL injection
	}

	//Removes this from database.
	public function removeFromDatabase() {
		$stmt = $this->connection->prepare("DELETE FROM Accounts WHERE id = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();	//safe from SQL injection
	}

	//Privately used in constructor. Sets id for this.
	private function setId() {
		$stmt = $this->connection->prepare("SELECT * FROM Accounts WHERE institution=:institution AND type=:type AND user_id=:user_id");
		$stmt->bindParam(':institution', $this->institution);
		$stmt->bindParam(':type', $this->type);
		$stmt->bindParam(':user_id', $this->user_id);
		$stmt->execute();
		$count = $stmt->rowCount();

		if($count === 0) {	//account doesn't exist...
			//echo " -created new account.";
			$this->addToDatabase();
			$this->setId();
		} else {	//account exists in db already...
			$row = $stmt->fetch();
			$this->id = $row['id'];
			//echo " -used existing account (" . $this->id . ").";
		}
	}
}

?>