<?php

require_once __DIR__ . '/../model/AccountManager.php';
require_once __DIR__ . '/../model/TransactionManager.php';

//session
session_start();
$user_id = $_SESSION['user_id'];

//params
$data = json_decode($_POST['data']);
$beg = date_create('@' . $_POST['beg']);
$end = date_create('@' . $_POST['end']);

//sort by date
function comp($a, $b)
{
	$at = $a->time;
	$bt = $b->time; 
	if ($at == $bt)
		return 0;
	return ($at < $bt) ? -1 : 1;
}
usort($data, "comp");

$am = AccountManager::getInstance();
$tm = TransactionManager::getInstance();

foreach ($data as $t)
{
	$am->addAccount($t->institution, $t->type, $user_id);
	$tm->addTransaction($user_id, $t);
}


//response
$response = new stdClass;
$response->accounts = $am->getAccountsWithBalance($user_id);

$response->transactions = [];
foreach ($response->accounts as $a)
	$response->transactions[$a->id] = $tm->getListForAccountBetween($a->id, $beg, $end);

$response->beg = $beg;
$response->end = $end;

//success
header('Content-Type: application/json');
echo json_encode($response);