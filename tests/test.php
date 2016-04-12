<?php

//testing some of the functions

require_once $_SERVER['DOCUMENT_ROOT'] . "/CSCI-310-Group-L/db/queries.php";

//simulate CSV imports (two accounts added)
//run this only once//
$bank_of_america_loan = getAccountId("Bank of America", "loan");
// insertTransaction(1, $bank_of_america_loan, "Student loan dispersed", 3000.00, "loan", 0);
// insertTransaction(1, $bank_of_america_loan, "Student loan payment 1", -150.00, "loan", 2400);
// insertTransaction(1, $bank_of_america_loan, "Student loan payment 2", -150.00, "loan", 2700);
// insertTransaction(1, $bank_of_america_loan, "Student loan payment 3", -150.00, "loan", 2900);

$wells_fargo_card = getAccountId("Wells Fargo", "card");
// insertTransaction(1, $wells_fargo_card, "Deposit", 3000.00, "card", 9);
// insertTransaction(1, $wells_fargo_card, "Gas purchase 1", -60.00, "card", 2409);
// insertTransaction(1, $wells_fargo_card, "Food purchase from McDonalds", -32.00, "card", 2709);
// insertTransaction(1, $wells_fargo_card, "Gas purchase 2", -150.00, "card", 2909);


//merge the two accounts
$all_transactions = getTransactions(1, $bank_of_america_loan);
$all_transactions = array_merge($all_transactions, getTransactions(1, $wells_fargo_card));


//sort by timestamp
usort($all_transactions, array("Transaction", "cmp_timestamp"));

//print the transaction records (unsorted)
echo "Transaction History: <br>";
foreach($all_transactions as $transaction) {
	echo "timestamp: " . $transaction->timestamp;
	echo " | ";
	echo "descriptor: " . $transaction->descriptor;
	echo " | ";
	echo "amount: " . $transaction->amount;	
	echo " | ";
	echo "category: " . $transaction->category . "<br />";
}

//print total amounts for each record
echo "<br><br>";
echo "Total balance accross all accounts: $" . Transaction::tabulateAmount($all_transactions) . "<br>";

$accountsHeld = getAccountIds(1);
foreach($accountsHeld as $id) {
	$account = getAccount($id);
	echo "Total for '" . $account->institution . ": " . $account->type . "': $";
	echo Transaction::tabulateAmount(getTransactions(1, $account->id));
	echo "<br>";
}