<?php

//testing some of the functions

require_once $_SERVER['DOCUMENT_ROOT'] . "/db/queries.php";



$accountId = getAccountId("Bank of America", "Student Loan");

insertTransaction(1, $accountId, "Student loan dispersed", 3000.00, "loan", 0);
insertTransaction(1, $accountId, "Student loan payment 1", -150.00, "loan", 2400);
insertTransaction(1, $accountId, "Student loan payment 2", -150.00, "loan", 2700);
insertTransaction(1, $accountId, "Student loan payment 3", -150.00, "loan", 2900);

$accountId2 = getAccountId("Wells Fargo", "Debit Card");

insertTransaction(1, $accountId2, "Deposit", 3000.00, "card", 9);
insertTransaction(1, $accountId2, "Gas purchase 1", -60.00, "card", 2409);
insertTransaction(1, $accountId2, "Food purchase from McDonalds", -32.00, "card", 2709);
insertTransaction(1, $accountId2, "Gas purchase 2", -150.00, "card", 2909);

$all_transactions = getTransactions(1, $accountId2);
array_merge($all_transactions, getTransactions(1, $accountId));

echo "<br>---<br>";
foreach($all_transactions as $transaction) {

	echo $transaction->timestamp . "(time)\t";
	echo $transaction->descriptor . "\t$";
	echo $transaction->amount . "\t";	
	echo $transaction->category . "(cat)\t";
	echo "<br>";
}