<?php

require_once __DIR__ . '/../model/AccountManager.php';
require_once __DIR__ . '/../model/TransactionManager.php';

//session
session_start();
$user_id = $_SESSION['user_id'];

//post data
$newBeg = date_create('@' . $_POST['newBeg']);
$oldBeg = date_create('@' . $_POST['oldBeg']);


//fetch
$am = AccountManager::getInstance();
$tm = TransactionManager::getInstance();

$transactions = [];
$list = $tm->getListForUserBetween($user_id, $newBeg, $oldBeg);
foreach ($list as $ta)
	$transactions[$ta->account_id][] = $ta;


header('Content-Type: application/json');
echo json_encode($transactions);