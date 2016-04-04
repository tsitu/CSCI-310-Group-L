<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/CSCI-310-Group-L/db/connect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/CSCI-310-Group-L/data/Transaction.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/CSCI-310-Group-L/data/Account.php";


//Removes all transactions tied to $userId "AND" $accountId
//"Remove Account" function on UI.
function removeAccount($userId, $accountId) {
	global $mysqli;
	//prepare
	if( ($stmt = $mysqli->prepare("DELETE FROM transactions WHERE userId=? AND accountId=?"))) 
	{	
		//bind
		if(! $stmt->bind_param("ii", $userId, $accountId) )
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error . "<br />";
			
		//execute
		if(! $stmt->execute() ) echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error . "<br />";

	} else {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "<br />"; //remove after debug
	}
}

//Gets an accountId given input. Creates one if none exists.
//First does insert if not exists.
//Second returns id.
function getAccountId($institution, $type) {
	global $mysqli;

	$stmt1 = $mysqli->prepare("
		-- One select
		SELECT id
		FROM accounts
		WHERE institution=? AND type=?");

	if(! $stmt1->bind_param("ss", $institution, $type) )
			echo "Binding parameters failed: (" . $stmt1->errno . ") " . $stmt1->error . "<br />";

	if(! $stmt1->execute() ) echo "Execute failed: (" . $stmt1->errno . ") " . $stmt1->error . "<br />";

	$stmt1->bind_result($id);
	$stmt1->fetch();
	$stmt1->close();

	if ($id == 0) {
		$stmt2 = $mysqli->prepare("
			-- Optionally one insert
			INSERT INTO accounts (institution, type)
			VALUES (?,?)");

		if(! $stmt2->bind_param("ss", $institution, $type) )
				echo "Binding parameters failed: (" . $stmt2->errno . ") " . $stmt2->error . "<br />";

		if(! $stmt2->execute() ) echo "Execute failed: (" . $stmt2->errno . ") " . $stmt2->error . "<br />";

		$id = $stmt2->insert_id;
		echo "stmt2 id: " . $id . "<br />";
	}

	return $id;
}

//Get all accountId's tied to $userId.
function getAccountIds($userId) {
	$ret = array();
	global $mysqli;

	//prepare
	if( ($stmt = $mysqli->prepare("SELECT DISTINCT accountId FROM transactions WHERE userId=?") )) {

		//bind
		if(! $stmt->bind_param("i", $userId) )
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error . "<br />";

			
		//execute
		if(! $stmt->execute() ) echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error . "<br />";
		
		//fetch result set
		$stmt->bind_result($id);

		while($stmt->fetch()) {
			$ret[] = $id;
		};
		$stmt->close();

		return $ret;

	} else {
		echo "getAccountIds():Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "<br />"; //remove after debug
	}

	//Insert query
	if( ($stmt = $mysqli->prepare("INSERT INTO accounts (institution, type) VALUES (?,?)"))) 
	{
		//escape special characters
		$institution = htmlentities($institution);
		$type = htmlentities($type);
		
		//bind
		if(! $stmt->bind_param("ss", $institution, $type) )
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			
		//execute
		if(! $stmt->execute() ) echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		
	}
	else {
		echo "getAccountId-b: Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error; //remove after debug
	}

	//Return lastId
	return $mysqli->insert_id;

}

//Get an account object from an accountId.
function getAccount($accountId) {
	global $mysqli;

	//prepare
	if( ($stmt = $mysqli->prepare("SELECT id, institution, type FROM accounts WHERE id=?") )) {

		//bind
		if(! $stmt->bind_param("i", $accountId) )
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error . "<br />";
			
		//execute
		if(! $stmt->execute() ) echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error . "<br />";
		
		//fetch result set
		$stmt->bind_result($id, $institution, $type);
		$stmt->fetch();
		$stmt->close();

		return new Account($id, $institution, $type); 

	} else {
		echo "getAccount():Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "<br />"; //remove after debug
	}
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
		$category = htmlentities($category);
		
		//bind
		if(! $stmt->bind_param("iisdsi", $userId, $accountId, $descriptor, $amount, $category, $timestamp) )
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error . "<br />";
			
		//execute
		if(! $stmt->execute() ) echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error . "<br />";
		
	}
	else {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "<br />"; //remove after debug
	}
}

//Gets all transactions tied to $userId "AND" $accountId.
//Returns an array of transactions.
function getTransactions($userId, $accountId) {
	global $mysqli;
	$ret = array();	//what is to be returned.

	//prepare
	if( ($stmt = $mysqli->prepare("SELECT id, userId, accountId, descriptor, amount, category, `timestamp` FROM transactions WHERE userId=? AND accountId=?") )) {

		//bind
		if(! $stmt->bind_param("ii", $userId, $accountId) )
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error . "<br />";
			
		//execute
		if(! $stmt->execute() ) echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error . "<br />";
		
		//fetch result set
		$stmt->bind_result($id, $userId, $accountId, $descriptor, $amount, $category, $timestamp);
		while($stmt->fetch()) {
			$ret[] = new Transaction($id, $userId, $accountId, $descriptor, $amount, $category, $timestamp);
		}

		return $ret;
	} else {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "<br />"; //remove after debug
	}
}


?>