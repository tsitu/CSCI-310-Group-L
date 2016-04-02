<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/CSCI-310-Group-L/db/connect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/CSCI-310-Group-L/data/Transaction.php";


//Removes all transactions tied to $userId "AND" $accountId
//"Remove Account" function on UI.
function removeAccount($userId, $accountId) {
	global $mysqli;
	//prepare
	if( ($stmt = $mysqli->prepare("DELETE FROM transactions WHERE userId=? AND accountId=?"))) 
	{	
		//bind
		if(! $stmt->bind_param("ii", $userId, $accountId) )
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error . "<br />\n";
			
		//execute
		if(! $stmt->execute() ) echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error . "<br />\n";

	} else {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "<br />\n"; //remove after debug
	}
}

//Gets an accountId given input. Creates one if none exists.
function getAccountId($institution, $type) {
	global $mysqli;
	//prepare
	$stmt1 = $mysqli->prepare("
		-- One select
		SELECT @id = id
		FROM accounts
		WHERE institution=? AND type=?");

	if(! $stmt1->bind_param("ss", $institution, $type) )
			echo "Binding parameters failed: (" . $stmt1->errno . ") " . $stmt1->error . "<br />\n";

	if(! $stmt1->execute() ) echo "Execute failed: (" . $stmt1->errno . ") " . $stmt1->error . "<br />\n";

	$stmt1->bind_result($id);
	$stmt1->fetch();

	$stmt2 = $mysqli->prepare("
		-- Optionally one insert
		IF @id IS NULL THEN
		INSERT INTO accounts (institution, type)
		VALUES (?,?)
		SET @id = SCOPE_IDENTITY()
		END IF");

	if(! $stmt2->bind_param("ss", $institution, $type) )
			echo "Binding parameters failed: (" . $stmt2->errno . ") " . $stmt2->error . "<br />\n";

	if(! $stmt2->execute() ) echo "Execute failed: (" . $stmt2->errno . ") " . $stmt2->error . "<br />\n";

	$stmt2->bind_result($id);
	$stmt2->fetch();

	return $id;

	/*if( ($stmt = $mysqli->prepare("
		-- One select
		SELECT @id = id
		FROM accounts
		WHERE institution=? AND type=?
		
		-- Optionally one insert
		IF @id IS NULL THEN
		INSERT INTO accounts (institution, type)
		VALUES (?,?)
		SET @id = SCOPE_IDENTITY()
		END IF")) ) {

		//escape special characters
		/*$institution = htmlentities($institution);
		$type = htmlentities($type);

		//bind
		if(! $stmt->bind_param("ssss", $institution, $type, $institution, $type) )
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error . "<br />\n";
			
		//execute
		if(! $stmt->execute() ) echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error . "<br />\n";
		
		//fetch result set
		$stmt->bind_result($id);
		$stmt->fetch();

		return $id;
	} else {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "<br />\n"; //remove after debug
	}*/
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
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error . "<br />\n";
			
		//execute
		if(! $stmt->execute() ) echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error . "<br />\n";
		
	}
	else {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "<br />\n"; //remove after debug
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
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error . "<br />\n";
			
		//execute
		if(! $stmt->execute() ) echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error . "<br />\n";
		
		//fetch result set
		$stmt->bind_result($id, $userId, $accountId, $descriptor, $amount, $category, $timestamp);
		while($stmt->fetch()) {
			$ret[] = new Transaction($id, $userId, $accountId, $descriptor, $amount, $category, $timestamp);
		}

		return $ret;
	} else {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "<br />\n"; //remove after debug
	}
}

//Do we need this functionality?
function getTransactionId() {}


?>