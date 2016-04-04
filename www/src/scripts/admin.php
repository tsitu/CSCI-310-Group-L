<?php

//Interface between queries and javascript
//Use AJAX to inject data into this page through GET request, and it will execute functions in query.php

//Must have "function" variable set, and any other variables needed to run that function.

//Returns everything in JSON format.

//TODO add something so users can't access this file.

header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'] . "/CSCI-310-Group-L/db/queries.php";

if(isset($_GET['function'])) {
	$function = $_GET['function'];
	if(isset($_GET['userId'])) $userId = $_GET['userId'];
	if(isset($_GET['accountId'])) $accountId = $_GET['accountId'];
	if(isset($_GET['descriptor'])) $descriptor = $_GET['descriptor'];
	if(isset($_GET['amount'])) $amount = $_GET['amount'];
	if(isset($_GET['category'])) $category = $_GET['category'];
	if(isset($_GET['timestamp'])) $timestamp = $_GET['timestamp'];
	if(isset($_GET['institution'])) $institution = $_GET['institution'];
	if(isset($_GET['type'])) $type = $_GET['type'];
}

if($function == "insertTransaction") {
	insertTransaction($userId, $accountId, $descriptor, $amount, $category, $timestamp);
	echo json_encode("OK");
}

if($function == "removeAccount") {
	removeAccount($userId, $accountId);
	echo json_encode("OK");
}

if($function == "getAccountId") {
	echo json_encode(getAccountId($institution, $type));
}

if($function == "getAccountIds") {
	echo json_encode(getAccountIds($userId));
}

if($function == "getAccount") {
	echo json_encode(getAccount($accountId));
}

if($function == "getTransactions") {
	echo json_encode(getTransactions($userId, $accountId));
}