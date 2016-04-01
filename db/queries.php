<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/db/connect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/data/Transaction.php";


//Removes all transactions tied to $userId "AND" $accountId
//"Remove Account" function on UI.
function removeAccount($userId, $accountId) {
	global $mysqli;
	//prepare
	if( ($stmt = $mysqli->prepare("DELETE FROM transactions WHERE userId=? AND accountId=?"))) 
	{	
		//bind
		if(! $stmt->bind_param("ii", $userId, $accountId) )
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			
		//execute
		if(! $stmt->execute() ) echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;

	} else {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error; //remove after debug
	}
}

//Gets an accountId given input. Creates one if none exists.
function getAccountId($institution, $type) {
	global $mysqli;
	//prepare
	if( ($stmt = $mysqli->prepare("
		-- One select
		SELECT @id = id
		FROM accounts
		WHERE institution=? AND type=?

		-- Optionally one insert
		IF @id IS NULL THEN
		    INSERT INTO accounts (institution, type)
		    VALUES (?,?)
		    SET @id = SCOPE_IDENTITY()
		END IF"
		) )) {

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
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error; //remove after debug
	}
}

//Get all accountId's tied to $userId.
function getAccountIds($userId) {

}

//Inserts the transaction into the database.
//Uses prepared statements and converts chars to htmlentities.
function insertTransaction($userId, $accountId, $descriptor, $amount, $category, $timestamp) {
	global $mysqli;
	//prepare
	if( ($stmt = $mysqli->prepare("INSERT INTO transactions (userId, accountId, descriptor, amount, category, `timestamp`) VALUES (?,?,?,?,?,?)"))) 
	{
		
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
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error; //remove after debug
	}
}

//Gets all transactions tied to $userId "AND" $accountId.
//Returns an array of transactions.
function getTransactions($userId, $accountId) {
	global $mysqli;
	$ret = array();	//what is to be returned.

	//prepare
	if( ($stmt = $mysqli->prepare("SELECT id, userId, accountId, descriptor, amount, category, `timestamp` FROM accounts WHERE userId=? AND accountId=?") )) {

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
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error; //remove after debug
	}
}

//Do we need this functionality?
function getTransactionId() {}


?>