<?php

require_once __DIR__ . '/../model/UserManager.php';
require_once __DIR__ . '/../model/AccountManager.php';
require_once __DIR__ . '/../model/TransactionManager.php';

session_start();
$user_id = $_SESSION['user_id'];

$id = $_POST['id'];
$beg = date_create('@' . $_POST['beg']);
$end = date_create('@' . $_POST['end']);

//managers
$um = UserManager::getInstance();
$am = AccountManager::getInstance();


//data
$am->deleteAccount($id);

$totals = [];
$totals['Net Worth'] = $um->getAssetHistory('net', $user_id, $beg, $end);
$totals['Assets'] = $um->getAssetHistory('asset', $user_id, $beg, $end);
$totals['Liabilities'] = $um->getAssetHistory('liability', $user_id, $beg, $end);

$response = new stdClass;
$response->totals = $totals;

header('Content-Type: application/json');
echo json_encode($response);