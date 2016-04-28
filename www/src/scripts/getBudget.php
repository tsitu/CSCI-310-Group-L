<?php

require_once __DIR__ . '/../model/BudgetManager.php';

//params
session_start();
$user_id = $_SESSION['user_id'];

$month = $_POST['month'];
$year = $_POST['year'];

//data
$bm = BudgetManager::getInstance();

header('Content-Type: application/json');
echo json_encode($bm->getBudgetsForTime($user_id, $month, $year));