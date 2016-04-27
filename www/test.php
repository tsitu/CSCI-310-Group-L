<?php

require_once 'src/model/BudgetManager.php';

$bm = BudgetManager::getInstance();

// $bm->addBudget(2, 2, 2016, "loan", 500.00);
// $bm->addBudget(2, 3, 2016, "loan", 400.00);
// $bm->addBudget(2, 4, 2016, "loan", 300.00);
// $id = $bm->addBudget(2, 5, 2016, "loan", 200.00);

// $bm->deleteBudget($id);

$budget = $bm->getBudgetByInfo(2, 3, 2016, "loan");

$budgets = $bm->getBudgetsByUser(2);


foreach($budgets as $b) {
echo $b->month . "/" . $b->year . " - " . $b->category . " -- $" . $b->budget . "<br>";

} 