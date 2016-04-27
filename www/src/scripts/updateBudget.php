<?php

require_once __DIR__ . '/../model/BudgetManager.php';

//params
session_start();
$user_id = $_SESSION['user_id'];

$category = $_POST['category'];
$budget = $_POST['budget'];
$month = $_POST['month'];
$year = $_POST['year'];

echo $category . '<br>';
echo $budget . '<br>';
echo $month . '<br>';
echo $year . '<br>';

//data
$bm = BudgetManager::getInstance();
echo $bm->updateBudget($user_id, $category, $month, $year, $budget);