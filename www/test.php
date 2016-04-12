<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/AccountDBManager.php";

echo "Testing account...<br>";

$accountdb = new AccountDBManager();
$theirAccounts = $accountdb->getAccountsWithBalance(1);

foreach ($theirAccounts as $account) {
	echo $account->balance . "<br>";
}

echo "done.";