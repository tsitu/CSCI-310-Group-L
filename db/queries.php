<?php

require_once __DIR__ . "/db/connect.php";
require_once __DIR__ . "/data/Transaction.php";


//Removes all transactions tied to $userId "AND" $accountId
//"Remove Account" function on UI.
function removeAccount($userId, $accountId) {
	//prepare
	if( ($stmt = $db->prepare("DELETE FROM transactions WHERE userId=? AND accountId=?"))) 
	{	
		//bind
		if(! $stmt->bind_param("ii", $userId, $accountId) )
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			
		//execute
		if(! $stmt->execute() ) echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;

	} else {
		echo "Prepare failed: (" . $db->errno . ") " . $db->error; //remove after debug
	}
}

//Gets an accountId given input. Creates one if none exists.
function getAccountId($institution, $type) {
	//prepare
	if( ($stmt = $db->prepare(""
		. "INSERT INTO accounts (institution, type)"
		. "SELECT id"
		. "WHERE NOT EXISTS (SELECT id FROM accounts WHERE institution=? AND type=?)"
		. "SELECT id FROM accounts WHERE institution=? AND type=?") )) {

		//escape special characters
		$institution = htmlentities($institution);
		$type = htmlentities($type);

		//bind
		if(! $stmt->bind_param("ssss", $institution, $type, $institution, $type) )
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			
		//execute
		if(! $stmt->execute() ) echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		
		//fetch result set
		$stmt->bind_result($id);
		$stmt->fetch();

		return $id;
	} else {
		echo "Prepare failed: (" . $db->errno . ") " . $db->error; //remove after debug
	}
}

//Get all accountId's tied to $userId.
function getAccountIds($userId) {

}

//Inserts the transaction into the database.
//Uses prepared statements and converts chars to htmlentities.
function insertTransaction($userId, $accountId, $descriptor, $amount, $category, $timestamp) {
	//prepare
	if( ($stmt = $db->prepare("INSERT INTO transactions (userId, accountId, descriptor, amount, category, `timestamp`) VALUES (?,?,?,?,?,?)"))) 
	{
		
		//get timestamp
		$date = new DateTime();
		$time = $date->getTimestamp();

		//escape special characters
		$descriptor = htmlentities($descriptor);
		$category = htmlentities($amount);
		
		
		//bind
		if(! $stmt->bind_param("iisdsi", $userId, $accountId, $descriptor, $amount, $category, $timestamp) )
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			
		//execute
		if(! $stmt->execute() ) echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		
	}
	else {
		echo "Prepare failed: (" . $db->errno . ") " . $db->error; //remove after debug
	}
}

//Gets all transactions tied to $userId "AND" $accountId.
//Returns an array of transactions.
function getTransactions($userId, $accountId) {
	$ret = array();	//what is to be returned.

	//prepare
	if( ($stmt = $db->prepare("SELECT id, userId, accountId, descriptor, amount, category, `timestamp` FROM accounts WHERE userId=? AND accountId=?") )) {

		//bind
		if(! $stmt->bind_param("ii", $userId, $accountId) )
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			
		//execute
		if(! $stmt->execute() ) echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		
		//fetch result set
		$stmt->bind_result($id, $userId, $accountId, $descriptor, $amount, $category, $timestamp);
		while($stmt->fetch()) {
			$ret[] = new Transaction($id, $userId, $accountId, $descriptor, $amount, $category, $timestamp);
		}

		return $ret;
	} else {
		echo "Prepare failed: (" . $db->errno . ") " . $db->error; //remove after debug
	}
}


?>