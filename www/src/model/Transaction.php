<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/TransactionDBManager.php";



/**
 * Transaction model class.
 */
class Transaction
{
	public $id;			//unique id
	public $user_id;	//user tied to this transaction
	public $account_id;	//account tied to this transaction
	public $time;		//datetime object of transaction time
	public $amount;		//double
	public $category;	//"fast food", "loan"
	public $descriptor;	//"McDonalds", "Loan Payment", etc.

	private $myDBConnector;

	/**
	 * Create a new Transaction object from given fields.
	 */
	function __construct($_id, $_user_id, $_account_id, $_time, $_amount, $_category, $_descriptor)
	{
		$this->id = $_id;
		$this->user_id = $_user_id;
		$this->account_id = $_account_id;
		$this->time = $_time;
		$this->amount = $_amount;
		$this->category = $_category;
		$this->descriptor = $_descriptor;

		$this->myDBConnector = new TransactionDBManager();
	}

	/**
	 * Ensure numeric fields are the correct type since PDO::fetch() only generates strings
	 */
	public function fixTypes()
	{
		$this->id = (int) $this->id;
		$this->user_id = (int) $this->user_id;
		$this->account_id = (int) $this->account_id;

		$this->time = date_create($this->time);
		$this->amount = (double) $this->amount;
	}

	//Adds 'this' to database.
	public function addToDatabase() {
		$this->myDBConnector->addToDatabase($this);
	}

}


