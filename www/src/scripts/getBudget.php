<?php

require_once __DIR__ . '/../model/UserManager.php';
require_once __DIR__ . '/../model/BudgetManager.php';

//params
session_start();
$user_id = $_SESSION['user_id'];

$month = $_POST['month'];
$year = $_POST['year'];

//data
$um = UserManager::getInstance();
$bm = BudgetManager::getInstance();

$budgets = $bm->getBudgetsForTime($user_id, $month, $year);
$spenings = $um->getCategorySpendingsForTime($user_id, $month, $year);

$result = [];
foreach ($budgets as $c => $b)
{
	$result[$c] = [
		'budget' => $b->budget,
		'spent'  => array_key_exists($c, $spenings) ? $spenings[$c] : 0
	];
}


header('Content-Type: application/json');
echo json_encode($result);