<?php

//TODO put this in a directory users cannot access
// try out using : http://localhost/CSCI-310-Group-L/insertTransaction.php?userId=1&accountId=1&descriptor=Gas_purchase&amount=1.32&category=card&timestamp=50

echo "inserting transaction";

require_once $_SERVER['DOCUMENT_ROOT'] . "/CSCI-310-Group-L/db/queries.php";

if(isset($_GET['userId'])) {
	$userId = $_GET['userId'];
	$accountId = $_GET['accountId'];
	$descriptor = $_GET['descriptor'];
	$amount = $_GET['amount'];
	$category = $_GET['category'];
	$timestamp = $_GET['timestamp'];
}


insertTransaction($userId, $accountId, $descriptor, $amount, $category, $timestamp);