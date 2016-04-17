<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/AccountManager.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/TransactionManager.php";

session_start();

$data = json_decode($_POST['data']);
$user_id = $_SESSION['user_id'];

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

$result = [];
foreach ($data as $t)
{
	$aid = $am->addAccount($t->institution, $t->type, $user_id);
	$tid = $tm->addTransaction($user_id, $t);

	if ($aid > 0 || $tid > 0)
	{
		$account = $am->getAccountWithBalance($user_id, $t->institution, $t->type);
		$result[$account->id] = $account;
	}
}

echo json_encode(array_values($result));