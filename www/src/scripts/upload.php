<?php

require_once "../model/AccountManager.php";
require_once "../model/TransactionManager.php";

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

foreach ($data as $t)
{
	$am->addAccount($t->institution, $t->type, $user_id);
	$tm->addTransaction($user_id, $t);
}

$updated = $am->getAccountsWithBalance($user_id);

echo json_encode($updated);