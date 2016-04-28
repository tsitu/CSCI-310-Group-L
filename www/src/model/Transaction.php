<?php

require_once __DIR__ . '/DBManager.php';

/**
 * Transaction model class.
 */
class Transaction
{
	public $id;			//unique id
	public $user_id;	//user tied to this transaction
	public $account_id;	//account tied to this transaction
	public $time;		//datetime object of transaction time
	public $unixtime;	//datetime object of transaction time
	public $amount;		//double
	public $category;	//"fast food", "loan"
	public $merchant;	//"McDonalds", "Loan Payment", etc.


	/**
	 * Ensure numeric fields are the correct type since PDO::fetch() only generates strings
	 */
	public function fixTypes()
	{
		$this->id = (int) $this->id;
		$this->user_id = (int) $this->user_id;

		$this->account_id = (int) $this->account_id;
		$this->type = rtrim(DBManager::decrypt($this->type));
		$this->institution = rtrim(DBManager::decrypt($this->institution));

		$this->time = date_create($this->time);
		$this->unixtime = $this->time->getTimestamp();

		$this->amount = (double) $this->amount;
		$this->balance = (double) $this->balance;

		$this->category = rtrim(DBManager::decrypt($this->category));
		$this->merchant = rtrim(DBManager::decrypt($this->merchant));
	}
}

