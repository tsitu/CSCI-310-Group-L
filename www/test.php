<?php

//require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/AccountDBManager.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/Account.php";

// echo "These are the accounts related to userid 1:<br>";

// $accountdb = new AccountDBManager();
// $theirAccounts = $accountdb->getAccountsWithBalance(1);

// foreach ($theirAccounts as $account) {
// 	echo $account->institution . " - " . $account->type . " --- $" . $account->balance . "<br>";
// }

// echo "<br>";



// echo "These are the last 3 transactions for userid 1:<br>";

// $transactiondb = new TransactionDBManager();
// $theirTransactions = $transactiondb->getTransactionsForUser(1);

// foreach ($theirTransactions as $transaction) {
// 	echo $transaction->category . " - " . $transaction->descriptor . " --- $" . $transaction->amount . "<br>";
// }


$account = new Account(1, "Bank of America", "Savings");

echo "<br>done.";