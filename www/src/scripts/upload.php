<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/AccountManager.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/TransactionManager.php";

session_start();

$raw = $_POST['data'];
// $raw = '[{"institution":"Bank of America","type":"Credit Card","time":"2016-01-01 00:00:00","descriptor":"Self","category":"Deposit","amount":1000},{"institution":"Bank of America","type":"Credit Card","time":"2016-01-02 00:00:00","descriptor":"Self","category":"Deposit","amount":1000},{"institution":"Bank of America","type":"Credit Card","time":"2016-01-03 00:00:00","descriptor":"Self","category":"Deposit","amount":1000},{"institution":"Bank of America","type":"Credit Card","time":"2016-01-04 00:00:00","descriptor":"Self","category":"Deposit","amount":1000},{"institution":"Bank of America","type":"Credit Card","time":"2016-01-05 00:00:00","descriptor":"Self","category":"Deposit","amount":1000},{"institution":"Bank of America","type":"Credit Card","time":"2016-01-06 00:00:00","descriptor":"Self","category":"Deposit","amount":1000},{"institution":"Bank of America","type":"Credit Card","time":"2016-01-07 00:00:00","descriptor":"Self","category":"Deposit","amount":1000},{"institution":"Bank of America","type":"Credit Card","time":"2016-01-08 00:00:00","descriptor":"Self","category":"Deposit","amount":1000},{"institution":"Bank of America","type":"Credit Card","time":"2016-01-09 00:00:00","descriptor":"Self","category":"Deposit","amount":1000},{"institution":"Bank of America","type":"Credit Card","time":"2016-01-10 00:00:00","descriptor":"Self","category":"Deposit","amount":1000},{"institution":"Bank of America","type":"Credit Card","time":"2016-01-11 00:00:00","descriptor":"Self","category":"Deposit","amount":1000}]';
$data = json_decode($raw);
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