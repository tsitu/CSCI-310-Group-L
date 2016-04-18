<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/AccountDBManager.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/UserDBManager.php";


//Account functions
//$account_db = AccountDBManager::getAccountDBManager();
//$account_db->addAccount(1, "Chase", "loan", 2266);
//$account_db->addAccount(2, "Wells Fargo", "loan", 2266);
//$account_db->addAccount(3, "Chase3", "loan", 2266);
//$account_db->addAccount(4, "Chase4", "loan", 2266);
// $account_db->deleteAccount(1);
// $account_db->updateAccount(2, "Chase2", "loan", 2266);
// $list = $account_db->getAllAccounts(2266);
// $x = $account_db->getAccountByInfo("Chase2", "loan", 2266);
// $x = $account_db->getAccountByInfo("Chase2", "loan", 123456);
// if(is_null($x)) echo "tests done successfully<br>";
// else echo "something wrong with AccountDBManager<br>";

//User functions
//$user_db = UserDBManager::getUserDBManager();
//$user_db->addUser("test@gmail.com", "test");
//$user_db->addUser("test2@gmail.com", "test2");

//$id1 = $user_db->getUserId("test@gmail.com", "test");
//$id2 = $user_db->getUserId("test2@gmail.com", "test2");

//$user_db->deleteUser($id2);

//$user = $user_db->getUser($id1);

//if($user->email = "test@gmail.com") echo "User tests successful.<br>";
//else echo "User tests not successful.";
//
//



$dbm = AccountDBManager::getAccountDBManager();
//$acc = $dbm->addAccount("bank", "card", 2266);
$acc = $dbm->getAccountByInfo("bank", "card", 2266);

echo $acc->id . " is my id<br>";