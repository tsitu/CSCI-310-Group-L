<?php

//require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/AccountDBManager.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/Account.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/User.php";

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


// $account = new Account(1, "Bank of America", "Savings");
// echo "<br>";
// $account2 = new Account(1, "Chase", "Savings");

// echo "<br>done.";


echo "Attempting login...<br>";
if(User::validateUser("test@gmail.com", "test")) {
	echo $user->email . " logged in.<br>";
} else {
	echo "Login failed.<br>";
}

// echo "Registering new user...<br>";
// $user = new User("test@gmail.com", "test");

// echo "User information...<br>";
// echo "name: " . $user->email . " id: " . $user->id . "pass: " . $user->hashed_password . " encpass: " . DBManager::encrypt($user->hashed_password);

echo "<br>";