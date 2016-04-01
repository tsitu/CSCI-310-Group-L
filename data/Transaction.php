<?php

class Transaction {
	public id;			//unique id
	public userId;		//user tied to this transaction
	public accountId;	//account tied to this transaction
	public descriptor;	//"McDonalds", "Loan Payment", etc.
	public amount;		//"300.01" decimal
	public category;	//"fast food", "loan"
	public timestamp;	//unix timestamp of when this transaction occured

	function __construct($_id, $_userId, $_accountId, $_descriptor, $_amount, $_category, $_timestamp) {
		public id = $_id;
		public userId = $_userId;
		public accountId = $accountId;
		public descriptor = $descriptor; 
		public amount = $_amount;
		public category = $_category;
		public timestamp = $_timestamp;
	}
}