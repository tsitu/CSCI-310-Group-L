<?php


require_once "DBManager.php";

/**
 * Transaction model class.
 */
class Transaction
{
	public $id;			//unique id
	public $user_id;	//user tied to this transaction
	public $account_id;	//account tied to this transaction
	public $t;		//datetime object of transaction time
	public $amount;		//double
	public $category;	//"fast food", "loan"
	public $merchant;	//"McDonalds", "Loan Payment", etc.
	public $balance;

	/**
	 * Create a new Transaction object from given fields.
	 */
	function __construct($_id, $_user_id, $_account_id, $_time, $_amount, $_category, $_merchant, $_balance)
	{
		$this->id = $_id;
		$this->user_id = $_user_id;
		$this->account_id = $_account_id;

		$this->time = $_time;
		$this->amount = $_amount;
		$this->category = $_category;
		$this->merchant = $_merchant;
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
		$this->balance = (double) $this->balance;

		$this->category = DBManager::decrypt($this->category);
		$this->merchant = DBManager::decrypt($this->merchant);
	}
}

