<?php
require_once "src/scripts/timeout.php";

require_once "src/model/BudgetManager.php";

echo "Starting budget test...delete me when done...<br>";

$b = BudgetManager::getInstance();

$b->addBudget(2, 12, 2011, 3.99);
$b->addBudget(2, 1, 2012, 4.99);
$b->addBudget(2, 2, 2012, 5.99);
$id = $b->addBudget(2, 3, 2012, 6.99);
$b->addBudget(2, 4, 2012, 1.99);

$b->deleteBudget($id);

$budgets = $b->getBudgetsByUser(2);

echo "Should have 4 budgets and be missing 3/2012:<br>";
foreach($budgets as $budget) {
	echo $budget->month . "/" . $budget->year . " -- $" . $budget->budget . "<br>";
}

echo "<br>";

$budget = $b->getBudgetByInfo(2, 2, 2012);

echo "Should be $5.99: <br>";
echo $budget->budget;

echo " ===== <br>";
echo "Stay here for a minute to be redirected to logout.php";