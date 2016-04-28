<?php

require_once __DIR__ . '/../model/UserManager.php';
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

$um = UserManager::getInstance();
$am = AccountManager::getInstance();
$tm = TransactionManager::getInstance();

$newIDs = [];
foreach ($data as $t)
{
	$newID = $am->addAccount($t->institution, $t->type, $user_id);
	$tm->addTransaction($user_id, $t);

	if ($newID > 0)
		$newIDs[] = $newID;
}

$totals = [];
$totals['Net Worth'] = $um->getAssetHistory('net', $user_id, $beg, $end);
$totals['Assets'] = $um->getAssetHistory('asset', $user_id, $beg, $end);
$totals['Liabilities'] = $um->getAssetHistory('liability', $user_id, $beg, $end);


//response
$response = new stdClass;
$response->accounts = $am->getAccountsWithBalance($user_id);
// $response->spendings = $um->getCategorySpendingsForTime($user_id, $month, $year);
$response->totals = $totals;

$response->transactions = [];
foreach ($response->accounts as $a)
	$response->transactions[$a->id] = $tm->getListForAccountBetween($a->id, $beg, $end);

$response->newIDs = $newIDs;
$response->beg = $beg;
$response->end = $end;

//success
header('Content-Type: application/json');
echo json_encode($response);