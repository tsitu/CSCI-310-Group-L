<?php

require_once "../model/AccountManager.php";
require_once "../model/TransactionManager.php";

session_start();

$data = json_decode('[{"institution":"Bank of America","type":"Debit Card","time":"2016-01-01 10:00:00","descriptor":"Self","category":"Deposit","amount":1000},{"institution":"Bank of America","type":"Debit Card","time":"2016-01-01 17:30:00","descriptor":"Sketch","category":"App","amount":-99},{"institution":"Bank of America","type":"Debit Card","time":"2016-01-21 20:10:00","descriptor":"Blizzard - Overwatch","category":"Game","amount":-60},{"institution":"Bank of America","type":"Debit Card","time":"2016-01-22 21:08:00","descriptor":"Blizzard - Hearthstone Pack","category":"Game","amount":-3.99}]');
	//$_POST['data']);
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