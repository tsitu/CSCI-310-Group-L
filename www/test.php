<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/AccountDBManager.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/User.php";


//Account functions
$account_db = AccountDBManager::getAccountDBManager();
//$account_db->addAccount(1, "Chase", "loan", 2266);
//$account_db->addAccount(2, "Wells Fargo", "loan", 2266);
//$account_db->addAccount(3, "Chase3", "loan", 2266);
//$account_db->addAccount(4, "Chase4", "loan", 2266);
$account_db->deleteAccount(1);
$account_db->updateAccount(2, "Chase2", "loan", 2266);
$list = $account_db->getAllAccounts(2266);
$x = $account_db->getAccountByInfo("Chase2", "loan", 2266);
$x = $account_db->getAccountByInfo("Chase2", "loan", 123456);
if(is_null($x)) echo "tests done successfully<br>";
else echo "something wrong with AccountDBManager<br>";